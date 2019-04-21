<?php
/**
 * Guide class file.
 *
 * @author Abdulrahman Mohamed <abdokamal15@gmail.com>
 * @copyright Copyright 2013
 * @license http://www.xtatar.com
 */

/**
 * user Guide page
 *
 * @author Abdulrahman Mohamed <abdokamal15@gmail.com>
 * @version $Id$
 * @package app.controllers
 * @since 1.0
 */
load_game_engine('Village');

class Guide_Controller extends VillageController
{
    public $taskNumber = 0;
    public $taskState = 0;
    public $quiz = NULL;
    public $clientAction = NULL;
    public $guideData = array();

    public function __construct()
    {
        $this->customLogoutAction = TRUE;
        parent::__construct();
        if ($this->player == NULL) {
            exit(0);
        }
        $this->layoutViewFile = NULL;
        $this->viewFile = "guide";
    }

    public function index()
    {
        $this->quiz = trim($this->data['guide_quiz']);
        //ءﺎﻬﻧا ﻲﻬﺘﻨﻣ ﺕﺎﻤﻬﻤﻟا ﺮﻳﺪﻤﻟا ﻮﻟ
        if ($this->quiz == GUIDE_QUIZ_COMPLETED) {
            exit(0);
        } else {
            $this->load_model('Guide', 'm');
            $this->taskState = 0;
            // ﻒﻗﻮﺘﻣ ﻭا اﺪﺒﻳ ﻢﻟ ﺕﺎﻤﻬﻤﻟا ﺮﻳﺪﻣ ﻮﻟ
            if ($this->quiz == GUIDE_QUIZ_NOTSTARTED || $this->quiz == GUIDE_QUIZ_SUSPENDED) {
                $this->clientAction = 0 - 1;
                if (is_get('v') && get('v') == "f") {
                    if ($this->quiz == GUIDE_QUIZ_NOTSTARTED) {
                        $this->m->setGuideTask($this->player->playerId, GUIDE_QUIZ_SUSPENDED);
                    }

                } else {
                    if (is_get('v') && get('v') == "n") {
                        $this->quiz = "1,200";
                        $this->clientAction = 1;
                        $this->m->setGuideTask($this->player->playerId, $this->quiz);
                    } else {
                        $this->taskNumber = is_get('v') && get('v') == "s" ? 1 : 0;
                        if ($this->taskNumber == 1) {
                            $this->clientAction = 0;
                            $this->quiz = "0,1";
                            $this->m->setGuideTask($this->player->playerId, $this->quiz);
                        }
                    }
                }
            } else {
                $quizArray = explode(",", $this->quiz);
                $this->taskNumber = $quizArray[1];
                if ($this->taskNumber == 200 && is_get('v')) {
                    if (get('v') == "y") {
                        $this->taskNumber = 0;
                        $this->clientAction = 1;
                        $this->quiz = GUIDE_QUIZ_NOTSTARTED;
                        $this->m->setGuideTask($this->player->playerId, $this->quiz);
                    } else if (get('v') == "c") {
                        $this->quiz = "0,201,0";
                        $quizArray = explode(",", $this->quiz);
                        $this->taskNumber = $quizArray[1];
                        $this->m->setGuideTask($this->player->playerId, $this->quiz);
                    }
                }
                if ($this->taskNumber == 201) {
                    $this->handleNoQuiz($this->m, $quizArray[2]);
                } else if ($quizArray[0] == 1) {
                    $this->clientAction = $quizArray[0] = 0;
                    $this->m->setGuideTask($this->player->playerId, implode(",", $quizArray));
                    $this->newReadQuiz($this->taskNumber, $this->m, $quizArray);
                } else {
                    $this->checkForQuiz($this->taskNumber, $this->m, $quizArray);
                }

            }
        }

        $this->viewData['taskNumber'] = $this->taskNumber;
        $this->viewData['taskState'] = $this->taskState;
        $this->viewData['quiz'] = $this->quiz;
        $this->viewData['guideData'] = $this->guideData;
        header("gquiz:" . $this->clientAction);
    }

    public function handleNoQuiz($m, $quizStep)
    {
        $time = floor(36000 / $this->gameMetadata['game_speed']);
        $this->guideData['quiztime'] = secondstostring($time);
        $result = 0;
        switch ($quizStep) {
            case 0:
                if (is_get('v') && trim(get('v')) == "y") {
                    $result = 1;
                    $this->load_library('QueueTask', 'newTask', array(
                        'taskType' => QS_PLUS1,
                        'playerId' => $this->player->playerId,
                        'executionTime' => 86400
                    ));
                    $this->newTask->villageId = "";
                    $this->newTask->tag = 0;
                    $this->queueModel->addTask($this->newTask);
                    $m->increaseGoldNumber($this->player->playerId, 15);

                    $this->load_library('QueueTask', 'newTask1', array(
                        'taskType' => QS_GUIDENOQUIZ,
                        'playerId' => $this->player->playerId,
                        'executionTime' => $time
                    ));
                    $this->queueModel->addTask($this->newTask1);
                }
                break;
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
                if (!isset($this->queueModel->tasksInQueue[QS_GUIDENOQUIZ]) && is_get('v') && trim(get('v')) == "y") {
                    $result = 1;
                    $m->addResourcesTo($this->data['selected_village_id'], array(
                        217,
                        247,
                        177,
                        207
                    ));
                    $this->load_library('QueueTask', 'newTask', array(
                        'taskType' => QS_GUIDENOQUIZ,
                        'playerId' => $this->player->playerId,
                        'executionTime' => $time
                    ));
                    $this->queueModel->addTask($this->newTask);
                }
                break;
            case 6:
                if (!isset($this->queueModel->tasksInQueue[QS_GUIDENOQUIZ]) && is_get('v') && trim(get('v')) == "y") {
                    break;
                }
                $this->clientAction = 100;
                $this->quiz = GUIDE_QUIZ_COMPLETED;
                $m->setGuideTask($this->player->playerId, $this->quiz);

                $this->load_library('QueueTask', 'newTask', array(
                    'taskType' => QS_PLUS1,
                    'playerId' => $this->player->playerId,
                    'executionTime' => 172800
                ));
                $this->newTask->villageId = "";
                $this->newTask->tag = 0;
                $this->queueModel->addTask($this->newTask);
                $m->increaseGoldNumber($this->player->playerId, 20);
        }
        if ($result == 1 && $quizStep < 6) {
            ++$quizStep;
            $this->quiz = "0,201," . $quizStep;
            $m->setGuideTask($this->player->playerId, $this->quiz);
        }
        $this->guideData['quizStep'] = $quizStep;
        $this->guideData['pended'] = isset($this->queueModel->tasksInQueue[QS_GUIDENOQUIZ]);
        if ($this->guideData['pended']) {
            $this->guideData['remainingSeconds'] = $this->queueModel->tasksInQueue[QS_GUIDENOQUIZ][0]['remainingSeconds'];
        }
    }

    public function newReadQuiz($quizNumber, $m, $quizArray)
    {
        global $gameConfig;

        switch ($quizNumber) {
            case 6:
                $this->clientAction = 2;
                $this->load_model('Message', 'mm');
                $messageId = $this->mm->sendMessage(0, 'النظام', $this->player->playerId, $this->data['name'], guide_task_msg_subject, guide_task_msg_body);
                $quizArray[] = $messageId;
                $m->setGuideTask($this->player->playerId, implode(",", $quizArray));
                break;
            case 7:
                $map_size = $this->setupMetadata['map_size'];
                $_x = $this->data['rel_x'];
                $_y = $this->data['rel_y'];
                $mapMatrix = implode(",", $this->__getVillageMatrix($map_size, $_x, $_y, 3));
                $reader = $m->getVillagesMatrix($mapMatrix);
                $availableVillages = array();
                foreach ($reader as $value) {
                    if (!$value['is_oasis'] && 0 < intval($value['player_id']) && intval($value['player_id']) != $this->player->playerId) {
                        $availableVillages[] = array(
                            $value['rel_x'],
                            $value['rel_y'],
                            $value['village_name']
                        );
                    }
                }
                unset($reader);
                if (sizeof($availableVillages) == 0) {
                    $availableVillages[] = array(
                        $this->data['rel_x'],
                        $this->data['rel_y'],
                        $this->data['village_name']
                    );
                }
                $r_indx = mt_rand(0, sizeof($availableVillages) - 1);
                $this->guideData['vname'] = $availableVillages[$r_indx][2];
                $quizArray[] = implode("|", $availableVillages[$r_indx]);
                $m->setGuideTask($this->player->playerId, implode(",", $quizArray));
                break;
            case 20:
            case 21:
                $this->taskState = $quizArray[sizeof($quizArray) - 1] == 1 ? 2 : 0;
                $this->guideData['troop_id'] = $this->getFirstTroopId($this->data['tribe_id']);
                $this->guideData['troop_name'] = constant("troop_" . $this->guideData['troop_id']);
        }
    }

    // ﺢﻴﺤﺻ ﻞﻜﺸﺑ ﺎﻫﺰﻴﻔﻨﺗ ﻦﻣ ﺪﻛﺎﺘﻳ ﻢﺛ ﻪﻴﻟﺎﺤﻟا ﻪﻤﻬﻤﻟا ﺪﻳﺪﺤﺘﺑ ﻡﻮﻘﻳ ﺎﻨﻫ
    public function checkForQuiz($quizNumber, $m, $quizArray)
    {
        switch ($quizNumber) {
            case 1: // ﺏﺎﻄﺤﻟا ﻞﻘﺣ ءﺎﻨﺑ
                foreach ($this->buildings as $build) {
                    if ($build['item_id'] == 1 && 0 < $build['level']) {
                        $this->taskState = $this->clientAction = 1;
                        $m->setGuideTask($this->player->playerId, "1,2");
                        break;
                    }
                }
                break;
            case 2: // ﺢﻤﻘﻟا ﻞﻘﺣ ءﺎﻨﺑ
                foreach ($this->buildings as $build) {
                    if ($build['item_id'] == 4 && 0 < $build['level']) {
                        $this->taskState = $this->clientAction = 1;
                        $m->setGuideTask($this->player->playerId, "1,3");

                        $this->load_library('QueueTask', 'newTask', array(
                            'taskType' => QS_PLUS1,
                            'playerId' => $this->player->playerId,
                            'executionTime' => 86400
                        ));
                        $this->newTask->villageId = "";
                        $this->newTask->tag = 0;
                        $this->queueModel->addTask($this->newTask);
                        break;
                    }
                }
                break;
            case 3: //ﻪﻳﺮﻘﻟا ﻢﺳا ﺮﻴﻴﻐﺗ
                if ($this->data['village_name'] != new_village_name_prefix . " " . $this->data['name']) {
                    $this->taskState = $this->clientAction = 1;
                    $m->setGuideTask($this->player->playerId, "1,4");
                    $m->addResourcesTo($this->data['selected_village_id'], array(
                        30,
                        60,
                        30,
                        20
                    ));
                }
                break;
            case 4: // ﺕﺎﻴﺋﺎﺼﺣﻻﺎﺑ ﺐﻋﻻﻟا ﺐﻴﺗﺮﺗ ﺪﻳﺪﺤﺗ
                if (isset($_GET['v'])) {
                    $num = trim($_GET['v']);
                    if (!is_numeric($num)) {
                        $this->taskState = 1;
                    } else {
                        $playerRank = $m->getPlayerRank($this->player->playerId, $this->data['total_people_count'] * 10 + $this->data['villages_count']);
                        if ($num == $playerRank) {
                            $this->taskState = 4;
                        } else {
                            $this->taskState = $num < $playerRank ? 2 : 3;
                        }
                        $m->setGuideTask($this->player->playerId, "1,5");
                        $m->addResourcesTo($this->data['selected_village_id'], array(
                            40,
                            30,
                            20,
                            30
                        ));
                        $this->clientAction = 1;
                    }
                }
                break;
            case 5: // ﺪﻳﺪﺣ ﻞﻘﺣﻭ ﻦﻴﻃ ﻞﻘﺣ ءﺎﻨﺑ
                $count = 0;
                foreach ($this->buildings as $build) {
                    if ($build['item_id'] == 2 && 0 < $build['level']) {
                        $count |= 1;
                    } else if ($build['item_id'] == 3 && 0 < $build['level']) {
                        $count |= 2;
                    }
                }
                if (0 < ($count & 1) && 0 < ($count & 2)) {
                    $this->taskState = $this->clientAction = 1;
                    $m->setGuideTask($this->player->playerId, "1,6");
                    $m->addResourcesTo($this->data['selected_village_id'], array(
                        50,
                        60,
                        30,
                        30
                    ));
                }
                break;
            case 6: // ﺔﻟﺎﺳﺮﻟا ﺔﺋاﺮﻗ
                if ($m->isOpenedMessage($quizArray[2])) {
                    $this->taskState = $this->clientAction = 1;
                    $m->setGuideTask($this->player->playerId, "1,7");
                    $m->increaseGoldNumber($this->player->playerId, 20);
                }
                break;
            case 7: // ﺭﺎﺠﻟا ﺕﺎﻴﺛاﺪﺣا ﺪﻳﺪﺤﺗ
                list($x, $y, $vname) = explode("|", $quizArray[sizeof($quizArray) - 1]);
                $this->guideData['vname'] = $vname;
                if (isset($_GET['v'])) {
                    $arr = explode("|", trim($_GET['v']));
                    if (sizeof($arr) < 2 || $x != $arr[0] || $y != $arr[1]) {
                        $this->taskState = 1;
                    } else {
                        $this->clientAction = 1;
                        $this->taskState = 2;
                        $m->setGuideTask($this->player->playerId, "1,8");
                        $m->addResourcesTo($this->data['selected_village_id'], array(
                            60,
                            30,
                            40,
                            90
                        ));
                    }
                }
                break;
            case 8: // ﺢﻤﻗ 200 ﻝﺎﺳﺭا
                if (isset($_GET['v']) && trim($_GET['v']) == "send") {
                    if ($this->resources[4]['current_value'] < 200) {
                        $this->taskState = 1;
                    } else {
                        $this->clientAction = 1;
                        $this->taskState = 2;
                        $qid = $this->sendReinforcements();
                        $m->setGuideTask($this->player->playerId, "1,9," . $qid);
                        $m->addResourcesTo($this->data['selected_village_id'], array(
                            0,
                            0,
                            0,
                            0 - 200
                        ));
                    }
                }
                break;
            case 9: // ﺩﺭﻮﻣ ﻞﻛ ﻦﻣ ﻰﻓﺎﺿا ﻞﻘﺣ ءﺎﻨﺑ
                $count = 0;
                foreach ($this->buildings as $build) {
                    if ($build['item_id'] == 1 && 0 < $build['level']) {
                        $count |= 1;
                    } else if ($build['item_id'] == 2 && 0 < $build['level']) {
                        $count |= 2;
                    } else if ($build['item_id'] == 3 && 0 < $build['level']) {
                        $count |= 4;
                    } else if ($build['item_id'] == 4 && 0 < $build['level']) {
                        $count |= 8;
                    }
                }
                if (0 < ($count & 1) && 0 < ($count & 2) && 0 < ($count & 4) && 0 < ($count & 8)) {
                    $this->taskState = $this->clientAction = 1;
                    $m->setGuideTask($this->player->playerId, "1,10," . $quizArray[sizeof($quizArray) - 1]);
                    $m->addResourcesTo($this->data['selected_village_id'], array(
                        100,
                        80,
                        40,
                        40
                    ));
                }
                break;
            case 10: // ﺭﺎﻔﻟا ﻝﻮﺻﻭ
                $qid = $quizArray[sizeof($quizArray) - 1];
                if ($m->guideTroopsReached($qid)) {
                    $this->taskState = $this->clientAction = 1;
                    $m->setGuideTask($this->player->playerId, "1,11");
                    $this->load_library('QueueTask', 'newTask', array(
                        'taskType' => QS_PLUS1,
                        'playerId' => $this->player->playerId,
                        'executionTime' => 2 * 86400
                    ));
                    $this->newTask->villageId = "";
                    $this->newTask->tag = 0;
                    $this->queueModel->addTask($this->newTask);
                }
                break;
            case 11: // ﺮﻳﺮﻘﺘﻟا ﺔﺋاﺮﻗ
                if ($m->isOpenedReport($this->player->playerId)) {
                    $this->taskState = $this->clientAction = 1;
                    $m->setGuideTask($this->player->playerId, "1,12");
                    $m->addResourcesTo($this->data['selected_village_id'], array(
                        75,
                        140,
                        40,
                        230
                    ));
                }
                break;
            case 12: // 1 ﻯﻮﺘﺴﻣ ﻝﻮﻘﺤﻟا ﻞﻛ ﺮﻳﻮﻄﺗ
                $result = 1;
                foreach ($this->buildings as $build) {
                    if (!($build['item_id'] == 1 && $build['level'] < 1 || $build['item_id'] == 2 && $build['level'] < 1 || $build['item_id'] == 3 && $build['level'] < 1 || $build['item_id'] == 4 && $build['level'] < 1)) {
                        continue;
                    }
                    $result = 0;
                    break;
                }
                if ($result == 1) {
                    $this->taskState = $this->clientAction = 1;
                    $m->setGuideTask($this->player->playerId, "1,13");
                    $m->addResourcesTo($this->data['selected_village_id'], array(
                        75,
                        80,
                        30,
                        50
                    ));
                }
                break;
            case 13: // ﻒﺻﻮﻟﺎﺑ ﻡﻼﺴﻟا ﺔﻣﺎﻤﺣ ﺢﺿﻭ
                if (0 < preg_match("/\\[#0\\]/", $this->data['description1']) || 0 < preg_match("/\\[#0\\]/", $this->data['description2'])) {
                    $this->taskState = $this->clientAction = 1;
                    $m->setGuideTask($this->player->playerId, "1,14");
                    $m->addResourcesTo($this->data['selected_village_id'], array(
                        120,
                        200,
                        140,
                        100
                    ));
                }
                break;
            case 14: // ءﺎﺒﺨﻤﻟا ءﺎﻨﺑ
                $result = 0;
                foreach ($this->buildings as $build) {
                    if ($build['item_id'] == 23 && 0 < $build['level']) {
                        $result = 1;
                        break;
                    }
                }
                if ($result == 1) {
                    $this->taskState = $this->clientAction = 1;
                    $m->setGuideTask($this->player->playerId, "1,15");
                    $m->addResourcesTo($this->data['selected_village_id'], array(
                        150,
                        180,
                        30,
                        130
                    ));
                }
                break;
            case 15: // 2 ﻯﻮﺘﺴﻣ ﻝﻮﻘﺤﻟا ﻞﻛ ﺮﻳﻮﻄﺗ
                $count = 0;
                foreach ($this->buildings as $build) {
                    if ($build['item_id'] == 1 && 1 < $build['level']) {
                        $count |= 1;
                    } else if ($build['item_id'] == 2 && 1 < $build['level']) {
                        $count |= 2;
                    } else if ($build['item_id'] == 3 && 1 < $build['level']) {
                        $count |= 4;
                    } else if ($build['item_id'] == 4 && 1 < $build['level']) {
                        $count |= 8;
                    }
                }
                if (0 < ($count & 1) && 0 < ($count & 2) && 0 < ($count & 4) && 0 < ($count & 8)) {
                    $this->taskState = $this->clientAction = 1;
                    $m->setGuideTask($this->player->playerId, "1,16");
                    $m->addResourcesTo($this->data['selected_village_id'], array(
                        60,
                        50,
                        40,
                        30
                    ));
                }
                break;
            case 16: // ﻪﻨﻜﺜﻟا ﺝﺎﺘﺤﺗ ﻪﺒﺸﺧ ﺢﻤﻗ
                $this->guideData['wood'] = $this->gameMetadata['items'][19]['levels'][0]['resources'][1];
                if (isset($_GET['v']) && is_numeric($_GET['v'])) {
                    if (intval($_GET['v']) == $this->guideData['wood']) {
                        $this->taskState = 3;
                        $this->clientAction = 1;
                        $m->setGuideTask($this->player->playerId, "1,17");
                        $m->addResourcesTo($this->data['selected_village_id'], array(
                            50,
                            30,
                            60,
                            20
                        ));
                    } else if ($this->guideData['wood'] < intval($_GET['v'])) {
                        $this->taskState = 1;
                    } else {
                        $this->taskState = 2;
                    }
                }
                break;
            case 17: // 2 ﻯﻮﺘﺴﻣ ﻦﻋ ﻲﺴﻴﺋﺮﻟا ﺖﻴﺒﻟا ﻊﻓﺭ
                $result = 0;
                foreach ($this->buildings as $build) {
                    if ($build['item_id'] == 15 && 2 < $build['level']) {
                        $result = 1;
                        break;
                    }
                }
                if ($result == 1) {
                    $this->taskState = $this->clientAction = 1;
                    $m->setGuideTask($this->player->playerId, "1,18");
                    $m->addResourcesTo($this->data['selected_village_id'], array(
                        75,
                        75,
                        40,
                        40
                    ));
                }
                break;
            case 18: // ﺕﺎﻴﺋﺎﺼﺣﻻﺎﺑ ﺐﻋﻻﻟا ﺐﻴﺗﺮﺗ
                if (isset($_GET['v'])) {
                    $num = trim($_GET['v']);
                    if (!is_numeric($num)) {
                        $this->taskState = 1;
                    } else {
                        $playerRank = $m->getPlayerRank($this->player->playerId, $this->data['total_people_count'] * 10 + $this->data['villages_count']);
                        if ($num == $playerRank) {
                            $this->taskState = 4;
                            $this->clientAction = 1;
                            $m->setGuideTask($this->player->playerId, "1,19");
                            $m->addResourcesTo($this->data['selected_village_id'], array(
                                100,
                                90,
                                100,
                                60
                            ));
                        } else {
                            $this->taskState = $num < $playerRank ? 2 : 3;
                        }
                    }
                }
                break;
            case 19: // ﺶﻴﺟ ﻭا ﺩﺭاﻮﻣ ﺎﻣا ﺭﺎﻴﺘﺧا
                if (sizeof($quizArray) == 2) {
                    if (isset($_GET['v'])) {
                        $num = trim($_GET['v']);
                        if (is_numeric($num)) {
                            $this->taskState = intval($num) == 1 ? 2 : 1;
                            $m->setGuideTask($this->player->playerId, "0,19," . $this->taskState);
                        }
                    }
                } else {
                    $this->taskState = $quizArray[sizeof($quizArray) - 1];
                    $result = 0;
                    // ﻊﻤﺠﺘﻟا ﺔﻄﻘﻧ ءﺎﻨﺑ
                    if ($this->taskState == 1) {
                        foreach ($this->buildings as $build) {
                            if ($build['item_id'] == 16 && 0 < $build['level']) {
                                $result = 1;
                                break;
                            }
                        }
                    } //  ﺏﻮﺒﺤﻟا ﻦﺨﻣ ءﺎﻨﺑ
                    else if ($this->taskState == 2) {
                        $result = 0;
                        foreach ($this->buildings as $build) {
                            if ($build['item_id'] == 11 && 0 < $build['level']) {
                                $result = 1;
                                break;
                            }
                        }
                    }
                    if ($result == 1) {
                        $m->setGuideTask($this->player->playerId, "1,20," . $this->taskState);
                        $this->taskState = $this->taskState == 1 ? 3 : 4;
                        $this->clientAction = 1;
                        $m->addResourcesTo($this->data['selected_village_id'], array(
                            80,
                            90,
                            60,
                            40
                        ));
                    }
                }
                break;
            case 20:
                $this->taskState = $quizArray[sizeof($quizArray) - 1] == 1 ? 2 : 0;
                $result = 0;
                // ﻪﻨﻜﺜﻟا ءﺎﻨﺑ
                if ($this->taskState == 2) {
                    foreach ($this->buildings as $build) {
                        if ($build['item_id'] == 19 && 0 < $build['level']) {
                            $result = 1;
                            $m->addResourcesTo($this->data['selected_village_id'], array(
                                70,
                                100,
                                90,
                                100
                            ));
                            break;
                        }
                    }
                } // ﻥﺰﺨﻤﻟا ءﺎﻨﺑ
                else if ($this->taskState == 0) {
                    foreach ($this->buildings as $build) {
                        if ($build['item_id'] == 10 && 0 < $build['level']) {
                            $result = 1;
                            $m->addResourcesTo($this->data['selected_village_id'], array(
                                70,
                                120,
                                90,
                                50
                            ));
                            break;
                        }
                    }
                }
                if ($result == 1) {
                    $m->setGuideTask($this->player->playerId, "1,21," . ($this->taskState == 0 ? 2 : 1));
                    $this->taskState = $this->taskState == 0 ? 1 : 3;
                    $this->clientAction = 1;
                }
                break;
            case 21:
                $this->taskState = $quizArray[sizeof($quizArray) - 1] == 1 ? 2 : 0;
                $this->guideData['troop_id'] = $this->getFirstTroopId($this->data['tribe_id']);
                $this->guideData['troop_name'] = constant("troop_" . $this->guideData['troop_id']);
                $result = 0;
                // ﺩﻮﻨﺟ 2 ﺐﻳﺭﺪﺗ
                if ($this->taskState == 2) {
                    $troops = $this->_getOnlyMyTroops();
                    if (2 <= $troops[$this->guideData['troop_id']]) {
                        $result = 1;
                        $m->addResourcesTo($this->data['selected_village_id'], array(
                            300,
                            320,
                            360,
                            570
                        ));
                    }
                } // ﻕﻮﺴﻟا ءﺎﻨﺑ
                else if ($this->taskState == 0) {
                    foreach ($this->buildings as $build) {
                        if ($build['item_id'] == 17 && 0 < $build['level']) {
                            $result = 1;
                            $m->addResourcesTo($this->data['selected_village_id'], array(
                                200,
                                200,
                                700,
                                450
                            ));
                            break;
                        }
                    }
                }
                if ($result == 1) {
                    $m->setGuideTask($this->player->playerId, "1,22");
                    $this->taskState = $this->taskState == 0 ? 1 : 3;
                    $this->clientAction = 1;
                }
                break;
            case 22:
                $result = 1;
                foreach ($this->buildings as $build) {
                    if (!($build['item_id'] == 1 && $build['level'] < 2 || $build['item_id'] == 2 && $build['level'] < 2 || $build['item_id'] == 3 && $build['level'] < 2 || $build['item_id'] == 4 && $build['level'] < 2)) {
                        continue;
                    }
                    $result = 0;
                    break;
                }
                if ($result == 1) {
                    $this->taskState = $this->clientAction = 1;
                    $m->setGuideTask($this->player->playerId, "1,23");
                    $m->increaseGoldNumber($this->player->playerId, 15);
                }
                break;
            case 23: // ﻩﺭﺎﻔﺴﻟا ءﺎﻨﺑ
                $result = 0;
                foreach ($this->buildings as $build) {
                    if ($build['item_id'] == 18 && 0 < $build['level']) {
                        $result = 1;
                        break;
                    }
                }
                if ($result == 1) {
                    $this->taskState = $this->clientAction = 1;
                    $m->setGuideTask($this->player->playerId, "1,24");
                    $m->addResourcesTo($this->data['selected_village_id'], array(
                        100,
                        60,
                        90,
                        40
                    ));
                }
                break;
            case 24: // ﻒﻟﺎﺤﺗ ءﺎﺸﻧا ﻭا ﻒﻟﺎﺤﺗ ﻰﻟا ﻡﺎﻤﻀﻧﻻا
                if (0 < intval($this->data['alliance_id'])) {
                    $this->taskState = 1;
                    $this->clientAction = 100;
                    $m->setGuideTask($this->player->playerId, GUIDE_QUIZ_COMPLETED);
                    $m->addResourcesTo($this->data['selected_village_id'], array(
                        395,
                        315,
                        345,
                        230
                    ));
                }
        }
    }

    public function _getOnlyMyTroops()
    {
        $troops = array();
        $t_arr = explode("|", $this->data['troops_num']);
        foreach ($t_arr as $t_str) {
            $t2_arr = explode(":", $t_str);
            if ($t2_arr[0] == 0 - 1) {
                $t2_arr = explode(",", $t2_arr[1]);
                foreach ($t2_arr as $t2_str) {
                    $t = explode(" ", $t2_str);
                    if (isset($troops[$t[0]])) {
                        $troops[$t[0]] += $t[1];
                    } else {
                        $troops[$t[0]] = $t[1];
                    }
                }
            }
        }
        return $troops;
    }

    public function getFirstTroopId($tribeId)
    {
        foreach ($this->gameMetadata['troops'] as $tid => $troop) {
            if (!($troop['for_tribe_id'] == $tribeId)) {
                continue;
            }
            return $tid;
        }
        return 0;
    }

    public function __getCoordInRange($map_size, $x)
    {
        if ($map_size <= $x) {
            $x -= $map_size;
        } else if ($x < 0) {
            $x = $map_size + $x;
        }
        return $x;
    }

    public function __getVillageId($map_size, $x, $y)
    {
        return $x * $map_size + ($y + 1);
    }

    public function __getVillageMatrix($map_size, $x, $y, $scale)
    {
        $matrix = array();
        $i = 0 - $scale;
        while ($i <= $scale) {
            $j = 0 - $scale;
            while ($j <= $scale) {
                $nx = $this->__getCoordInRange($map_size, $x + $i);
                $ny = $this->__getCoordInRange($map_size, $y + $j);
                $matrix[] = $this->__getVillageId($map_size, $nx, $ny);
                ++$j;
            }
            ++$i;
        }
        return $matrix;
    }

    public function sendReinforcements()
    {
        $needed_time = floor(18000 / $this->gameMetadata['game_speed']);
        $troopsStr = "31 1,32 0,33 0,34 0,35 0,36 0,37 0,38 0,39 0,40 0";
        $catapultTargets = $carryResources = "";
        $spyAction = 0;
        $procParams = $troopsStr . "|0|" . $spyAction . "|" . $catapultTargets . "|" . $carryResources . "|||0";
        $this->load_library('QueueTask', 'newTask', array(
            'taskType' => QS_WAR_REINFORCE,
            'playerId' => 0,
            'executionTime' => $needed_time
        ));
        $this->newTask->villageId = 0;
        $this->newTask->toPlayerId = $this->player->playerId;
        $this->newTask->toVillageId = $this->data['selected_village_id'];
        $this->newTask->procParams = $procParams;
        $this->newTask->tag = array(
            "troops" => NULL,
            "hasHero" => FALSE,
            "resources" => NULL
        );
        return $this->queueModel->addTask($this->newTask);
    }
}

?>
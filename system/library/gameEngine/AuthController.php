<?php
require_once LIBRARY_DIR . 'gameEngine/BaseController.php';

class AuthController extends BaseController
{
    // 1: report on message on, 2: report off message on, 3: report on message off, 4: report off message off
    public $reportMessageStatus = 4;

    public $queueModel = NULL;
    public $resources = array();
    public $playerVillages = array();
    public $playerLinks = array();
    public $villagesLinkPostfix = '';
    public $cpValue;
    public $cpRate;
    public $data;
    public $wrap;
    public $checkForNewVillage = TRUE;
    public $customLogoutAction = FALSE;


    public function __construct()
    {
        parent::__construct();

        // check is the player is logged
        if ($this->player == NULL) {
            if (!$this->customLogoutAction) {
                $this->is_redirect = TRUE;
                redirect('login');
            }
            return;
        }

        $this->load_model('Queue', 'queueModel');
        $this->queueModel->page = new StdClass;
        $this->queueModel->page->data = &$this->data;
        $this->queueModel->page->resources = &$this->resources;
        $this->queueModel->page->buildings = &$this->buildings;
        $this->queueModel->page->cpValue = &$this->cpValue;
        $this->queueModel->page->cpRate = &$this->cpRate;
        $this->queueModel->page->player = &$this->player;

        // change the selected village
        if (isset ($_GET['vid']) && $this->global_model->hasVillage($this->player->playerId, intval($_GET['vid']))
            && !$this->global_model->hasOasis($this->player->playerId, intval($_GET['vid']))
        ) {
            $this->global_model->setSelectedVillage($this->player->playerId, intval($_GET['vid']));
        }

        // fetch the player/village data
        $this->data = $this->global_model->getVillageData($this->player->playerId);
        if ($this->data == NULL) {
            $this->player->logout();
            $this->is_redirect = TRUE;
            redirect('login');
            return;
        }

        // finish the building tasks
        if (is_get('bfs') && is_get('k') && get('k') == $this->data['update_key']
            && $this->data['gold_num'] >= $this->gameMetadata['plusTable'][get('bfs')]['cost']
            && !$this->isGameTransientStopped() && !$this->isGameOver()) {
            // complete the tasks, then decrease the gold number
            $this->queueModel->finishTasks($this->player->playerId, $this->gameMetadata['plusTable'][intval(get('bfs'))]['cost'], intval(get('bfs')) == 7);

            // redirect to selceted controller
            global $loader;
            $id = (get('bfs') == 7) ? "?id=" . get('id') : NULL;
            $this->is_redirect = TRUE;
            redirect($loader->selected_controller . $id);
            return;
        }


        global $loader;
        // run the queue job
        if ($loader->selected_controller == 'build' && is_get('id')) {
            $b_arr = explode(',', $this->data['buildings']);
            $buldInfo = explode(' ', $b_arr[intval(get('id')) - 1]);
            $trainid = array(19, 20, 21, 25, 26, 29, 30);
            $type = (in_array($buldInfo[0], $trainid)) ? 2 : 1;
        } else {
            $type = 1;
        }

        if (!is_get('_a1_')) {
            $this->load_model('Queuejob', 'qj');
            $this->qj->processQueue($type, $this->player->playerId);
        }
        $this->data = $this->global_model->getVillageData($this->player->playerId);
        $usersession = session_id();
        if (!$this->player->isSpy && $this->data['UserSession'] != $usersession) {
            $this->is_redirect = TRUE;
            redirect('login');
            return;
        }

        if (!$this->player->isSpy && $this->data['is_blocked']) {
            $this->is_redirect = TRUE;
            redirect('login');
            return;
        }

        // check for Block
        if ($loader->selected_controller != 'blocked'
            && $loader->selected_controller != 'msg'
            && $loader->selected_controller != 'support') {
            if (!$this->player->isSpy && $this->data['blocked_second'] > 0) {
                $this->is_redirect = TRUE;
                redirect('blocked');
                return;
            }
        }

        $this->viewData['villagesLinkPostfix'] = '';

        $this->player->gameStatus = $this->data['gameStatus'];

        if (is_get('_a1_')) {
            return;
        }

        // check if plaer in holiday
        if ($loader->selected_controller != 'profile'
            && $loader->selected_controller != 'blocked'
            && get('t') != 6) {
            $holiday = explode(',', $this->data['holiday']);
            if (!$this->player->isSpy && $holiday[0] == 1) {
                $this->is_redirect = TRUE;
                redirect('profile?t=6');
                return;
            }
        }
        // check for global message
        if ($loader->selected_controller != 'alliancerole'
            && $loader->selected_controller != 'guide'
            && $loader->selected_controller != 'chat'
            && $loader->selected_controller != 'shownew'
            && $loader->selected_controller != 'blocked'
            && $loader->selected_controller != 'profile'
            && $this->data['blocked_second'] <= 0
            && !is_get('_gn_')) {

            if (!$this->player->isSpy && $this->data['new_gnews'] == 1) {
                $this->is_redirect = TRUE;
                redirect('shownew');
                return;
            }
        }

        if ($loader->selected_controller != 'shownew'
            && $loader->selected_controller != 'shownvill'
            && !is_get('_gn_')
        ) {
            // check for new village creation flag
            if (!$this->player->isSpy && intval($this->data['create_nvil']) == 1) {
                $this->is_redirect = TRUE;
                redirect('shownvill');
                return;
            }
        }

        // fetch the items in the queue
        $this->queueModel->fetchQueue($this->player->playerId);


        // fill the player custom links
        if (trim($this->data['custom_links']) != '') {
            $lnk_arr = explode("\n\n", $this->data['custom_links']);
            foreach ($lnk_arr as $lnk_str) {
                list ($linkName, $linkHref, $linkSelfTarget) = explode("\n", $lnk_str);
                $this->playerLinks[] = array(
                    'linkName' => $linkName,
                    'linkHref' => $linkHref,
                    'linkSelfTarget' => ($linkSelfTarget != '*')
                );
            }
        }
        $this->viewData['playerLinks'] = $this->playerLinks;

        // fill the player villages array
        $v_arr = explode("\n", $this->data['villages_data']);
        foreach ($v_arr as $v_str) {
            list ($vid, $x, $y, $vname) = explode(' ', $v_str, 4);
            $this->playerVillages [$vid] = array($x, $y, $vname);
        }
        $this->viewData['playerVillages'] = $this->playerVillages;

        // fill the resources
        $this->load_model('Artefacts', 'A');
        $crop = $this->A->CropAndRes($this->player->playerId, $this->data['selected_village_id'], 5);
        $res = $this->A->CropAndRes($this->player->playerId, $this->data['selected_village_id'], 7);
        $wrapString = '';
        $elapsedTimeInSeconds = $this->data['elapsedTimeInSeconds'];
        $r_arr = explode(',', $this->data['resources']);
        foreach ($r_arr as $r_str) {
            $r2 = explode(' ', $r_str);

            $prate = floor($r2[4] * (1 + ($r2[5] + $res) / 100)) - (($r2[0] == 4) ? floor($this->data['crop_consumption'] * $crop) : 0);
            $current_value = floor($r2[1] + $elapsedTimeInSeconds * ($prate / 3600));
            if ($current_value > $r2[2]) {
                $current_value = $r2[2];
            }

            $this->resources[$r2[0]] = array(
                'current_value' => $current_value,
                'store_max_limit' => $r2[2],
                'store_init_limit' => $r2[3],
                'prod_rate' => $r2[4],
                'prod_rate_percentage' => $r2[5],
                'calc_prod_rate' => $prate
            );
            $wrapString .= $this->resources[$r2[0]]['current_value'] . $this->resources[$r2[0]]['store_max_limit'];
        }
        $this->viewData['resources'] = &$this->resources;
        $this->viewData['crop'] = $crop;
        $this->wrap = (strlen($wrapString) > 40);
        $this->viewData['wrap'] = $this->wrap;

        // calc the cp
        list ($this->cpValue, $this->cpRate) = explode(' ', $this->data['cp']);
        $this->cpValue += $elapsedTimeInSeconds * ($this->cpRate / 86400);


        ################################# Pre-rendering ###########################
        if ($this->data['new_report_count'] < 0) {
            $this->data['new_report_count'] = 0;
        }
        if ($this->data['new_mail_count'] < 0) {
            $this->data['new_mail_count'] = 0;
        }

        $hasNewReports = ($this->data['new_report_count'] > 0);
        $hasNewMails = ($this->data['new_mail_count'] > 0);
        if ($hasNewReports && $hasNewMails) {
            $this->reportMessageStatus = 1;
        } elseif (!$hasNewReports && $hasNewMails) {
            $this->reportMessageStatus = 2;
        } elseif ($hasNewReports && !$hasNewMails) {
            $this->reportMessageStatus = 3;
        } else {
            $this->reportMessageStatus = 4;
        }
        $this->viewData['reportMessageStatus'] = $this->reportMessageStatus;
        ##########################################################################

        // check Player In Deletion Progress
        $this->viewData['isPlayerInDeletionProgress'] = $this->isPlayerInDeletionProgress();
        if ($this->isPlayerInDeletionProgress()) {
            $this->viewData['getPlayerDeletionTime'] = $this->getPlayerDeletionTime();
        }
        // assign script start timer
        $sec = explode(" ", microtime());
        $usec = explode(" ", microtime());
        list($usec, $sec) = $usec;
        $this->viewData['scriptstarttime'] = ceil(((double)$sec + (double)$usec - $GLOBALS['__scriptStart']) * 1000);

        // assign important variables

        $this->load_model('Friends', 'F');
        $this->viewData['FriendR'] = $this->F->GetRequestNum($this->player->playerId);
        $this->viewData['getGuideQuizClassName'] = $this->getGuideQuizClassName();
        $this->viewData['player'] = $this->player;
        $this->viewData['data'] = &$this->data;

        // set layout view file path
        $this->layoutViewFile = 'layout/game';
    }

    public function getGuideQuizClassName()
    {
        $quiz = trim($this->data['guide_quiz']);
        $newQuiz = ($quiz == '' || $quiz == GUIDE_QUIZ_SUSPENDED);
        if (!$newQuiz) {
            $quizArray = explode(',', $quiz);
            $newQuiz = ($quizArray[0] == 1);
        }
        return 'q_l' . $this->data['tribe_id'] . ($newQuiz ? 'g' : '');
    }

    public function isPlayerInDeletionProgress()
    {

        return isset ($this->queueModel->tasksInQueue[QS_ACCOUNT_DELETE]);
    }

    public function getPlayerDeletionTime()
    {

        return secondsToString($this->queueModel->tasksInQueue[QS_ACCOUNT_DELETE][0]['remainingSeconds']);
    }

    public function getPlayerDeletionId()
    {

        return $this->queueModel->tasksInQueue[QS_ACCOUNT_DELETE][0]['id'];
    }

    public function isGameTransientStopped()
    {

        return ($this->player->gameStatus & 2) > 0;
    }

    public function isGameOver()
    {
        $gameOver = ($this->player->gameStatus & 1) > 0;
        if ($gameOver) {
            $this->is_redirect = TRUE;
            redirect('over');
        }
        return $gameOver;
    }


    public function getFlashContent($path, $width, $height)
    {
        return sprintf(
            '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="%s" height="%s">
        <param name="movie" value="%s" />
        <param name="allowScriptAccess" value="Always" />
        <param name="quality" value="high" />
        <embed src="%s" allowScriptAccess="Always"  quality="high"  width="%s"  height="%s" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
        </object>',
            $width, $height, $path, $path, $width, $height
        );
    }


    public function __destruct()
    {
        unset($this->data);
        // Base Controller Desctuctor
        parent::__destruct();
    }

}

?>
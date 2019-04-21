<?php
/**
 * Build class file.
 *
 * @author Abdulrahman Mohamed <abdokamal15@gmail.com>
 * @copyright Copyright 2013
 * @license http://www.xtatar.com
 */

/**
 * show each build and make progress .
 *
 * @author Abdulrahman Mohamed <abdokamal15@gmail.com>
 * @version $Id$
 * @package app.controllers
 * @since 1.0
 */
load_lang('ui/custbuilds');
load_game_engine('Village');

class Build_Controller extends VillageController
{
    public $productionPane = TRUE;
    public $buildingView = '';
    public $buildingIndex = -1;
    public $buildProperties = NULL;
    public $newBuilds = NULL;
    public $troopsUpgrade = null;
    public $troopsUpgradeType = null;
    public $buildingTribeFactor = null;
    public $troops = array();
    public $selectedTabIndex = 0;
    public $villageOases = null;
    public $childVillages = null;
    public $hasHero = FALSE;
    public $totalCpRate = null;
    public $totalCpValue = null;
    public $neededCpValue = null;
    public $childVillagesCount = null;
    public $showBuildingForm = null;
    public $embassyProperty = null;
    public $merchantProperty = null;
    public $rallyPointProperty = null;
    public $crannyProperty = array('buildingCount' => 0, 'totalSize' => 0);
    public $warriorMessage = '';
    public $dataList = null;
    public $pageSize = 40;
    public $pageCount = null;
    public $pageIndex = null;

    /**
     * Constructor Method
     * This method defines view file && contentCssClass .
     */
    public function __construct()
    {
        parent::__construct();
        $this->viewFile = 'build';
        $this->viewData['contentCssClass'] = 'build';
    }


    /**
     * onLoadBuildings Method
     *
     * @param building string
     * @return void
     */
    public function onLoadBuildings($building)
    {
        $GameMetadata = $this->gameMetadata;
        if (((($this->buildingIndex == 0 - 1 && is_get('bid')) && is_numeric(get('bid'))) && get('bid') == $building['item_id'])) {
            $this->buildingIndex = $building['index'];
        }
        if (($building['item_id'] == 23 && 0 < $building['level'])) {
            /*$this->load_model('Artefacts', 'A');
            $artLevel = $this->A->Artefacts($this->player->playerId, $this->data['selected_village_id'], 8);
            $artPower = ($artLevel == 0) ? 1 : (($artLevel == 1) ? 200 : (($artLevel == 2) ? 100 : 500));*/
            ++$this->crannyProperty['buildingCount'];
            $this->crannyProperty['totalSize'] += $GameMetadata['items'][$building['item_id']]['levels'][$building['level'] - 1]['value'] * $GameMetadata['items'][$building['item_id']]['for_tribe_id'][$this->tribeId]; //* $artPower;
        }
    }


    /**
     * Index Method
     *
     * @return void
     */
    public function index()
    {
        if (((($this->buildingIndex == 0 - 1 && is_get('id')) && is_numeric(get('id'))) && isset($this->buildings[get('id')]))) {
            $this->buildingIndex = intval(get('id'));
        }
        $this->buildProperties = $this->getBuildingProperties($this->buildingIndex);
        if ($this->buildProperties == NULL) {
            $this->is_redirect = TRUE;
            redirect('village1');
            return null;
        }


        if ($this->buildProperties['emptyPlace']) {
            $this->viewData['villagesLinkPostfix'] .= '&id=' . $this->buildingIndex;
            $this->newBuilds = array(
                'available' => array(),
                'soon' => array()
            );
            foreach ($this->gameMetadata['items'] as $item_id => $build) {
                if (($item_id <= 4 || !isset($build['for_tribe_id'][$this->tribeId]))) {
                    continue;
                }
                $canBuild = $this->canCreateNewBuild($item_id);
                if (!($canBuild != 0 - 1)) {
                    continue;
                } else if ($canBuild) {
                    if (!isset($this->newBuilds['available'][$build['levels'][0]['time_consume']])) {
                        $this->newBuilds['available'][$build['levels'][0]['time_consume']] = array();
                    }
                    $this->newBuilds['available'][$build['levels'][0]['time_consume']][$item_id] = $build;
                } else {
                    $dependencyCount = 0;
                    foreach ($build['pre_requests'] as $reqId => $reqValue) {
                        if ($reqValue != NULL) {
                            $build['pre_requests_dependencyCount'][$reqId] = $reqValue - $this->_getMaxBuildingLevel($reqId);
                            $dependencyCount += $build['pre_requests_dependencyCount'][$reqId];
                        }
                    }
                    if (!isset($this->newBuilds['soon'][$dependencyCount])) {
                        $this->newBuilds['soon'][$dependencyCount] = array();
                    }
                    $this->newBuilds['soon'][$dependencyCount][$item_id] = $build;
                }
            }
            ksort($this->newBuilds['available'], SORT_NUMERIC);
            ksort($this->newBuilds['soon'], SORT_NUMERIC);
            $this->viewData['buildProperties'] = $this->buildProperties;
            $this->viewData['soonBuildings'] = $this->newBuilds['soon'];

            // avaiable buildings
            $availableBuildings = array();
            if (isset($this->newBuilds['available'])) {
                foreach ($this->newBuilds['available'] as $buildArray) {
                    foreach ($buildArray as $item_id => $build) {
                        if ($this->data['is_special_village']) {
                            if (get('id') == 25 || get('id') == 26 || get('id') == 29 || get('id') == 30 || get('id') == 33) {
                                if ($item_id != 40) {
                                    continue;
                                }
                            } else {
                                if ($item_id == 40) {
                                    continue;
                                }
                            }
                        }
                        if (get('id') == 39 && $item_id != 16) {
                            continue;
                        }
                        if (get('id') == 40 && $item_id != 31 && $item_id != 32 && $item_id != 33) {
                            continue;
                        }
                        if (get('id') != 39 && get('id') != 40 && ($item_id == 16 || $item_id == 31 || $item_id == 32 || $item_id == 33)) {
                            continue;
                        }
                        $neededResources = $build['levels'][0]['resources'];

                        $availableBuildings[] = array(
                            'item_id' => $item_id,
                            'item_id_constant' => constant("item_" . $item_id),
                            'item_desc_constant' => constant("item_desc_" . $item_id),
                            'neededResources' => $neededResources,
                            'build' => $build,
                            'getResourceGoldExchange' => $this->getResourceGoldExchange($neededResources, $item_id, $this->buildingIndex),
                            'getActionText' => $this->getActionText($neededResources, FALSE, FALSE, $item_id),
                            'time' => secondstostring($build['levels'][0]['time_consume'] / $this->gameSpeed * ($this->data['time_consume_percent'] / 100))
                        );
                    }
                }
            }
            $this->viewData['availableBuildings'] = $availableBuildings;

            return null;
        }

        $bitemId = $this->buildProperties['building']['item_id'];
        $this->viewData['villagesLinkPostfix'] .= '&id=' . $this->buildingIndex;
        if (4 < $bitemId) {
            $this->viewData['villagesLinkPostfix'] .= '&bid=' . $bitemId;
        }

        $this->buildingTribeFactor = (isset($this->gameMetadata['items'][$bitemId]['for_tribe_id'][$this->data['tribe_id']]) ? $this->gameMetadata['items'][$bitemId]['for_tribe_id'][$this->data['tribe_id']] : 1);
        if ($this->buildings[$this->buildingIndex]['level'] == 0) {
            $this->viewData['buildProperties'] = $this->buildProperties;
            $this->viewData['productionPane'] = $this->productionPane;
            $this->viewData['buildingView'] = $this->buildingView;
            $this->viewData['resource_gold_exchange'] = $this->getResourceGoldExchange($this->buildProperties['level']['resources'], $this->buildProperties['building']['item_id'], $this->buildingIndex);
            $this->viewData['get_action_text'] = $this->getActionText($this->buildProperties['level']['resources'], $this->buildProperties['building']['item_id'] <= 4, TRUE, $this->buildProperties['building']['item_id']);
            return null;
        }


        ## View
        switch ($bitemId) {
            case 12:
            case 13: // مستودع الاسلحة
                $this->productionPane = FALSE;
                $this->buildingView = 'Blacksmith_Armoury';
                $this->handleBlacksmithArmoury();

                $this->viewData['buildProperties'] = $this->buildProperties;

                $troops_training = array();
                $_arr = explode(',', $this->data['troops_training']);
                $_c = 0;
                $GameMetadata = $GLOBALS['GameMetadata'];
                foreach ($_arr as $troopStr) {
                    list($troopId, $researches_done, $defense_level, $attack_level) = explode(' ', $troopStr);
                    if ($troopId != 99) {
                        ++$_c;
                    }
                    if ($_c > 8) {
                        break;
                    }
                    if ($troopId != 99) {
                        $troops_training[] = $troopId;
                    }
                }
                $this->viewData['troops_training'] = $troops_training;

                if ($this->buildProperties['building']['item_id'] == 12) {
                    $tpower_array = array();
                    $_c = 0;
                    foreach ($_arr as $troopStr) {
                        list($troopId, $researches_done, $defense_level, $attack_level) = explode(' ', $troopStr);
                        if ($troopId != 99) {
                            ++$_c;
                        }
                        if ($_c > 8) {
                            break;
                        }
                        if ($troopId != 99) {
                            $tpower = $GameMetadata['troops'][$troopId]['attack_value'];
                            $tpower = $attack_level != 0 ? round((((2 * $attack_level) * $tpower) / 100) + $tpower) : $tpower;

                            $tpower_array[] = $tpower;
                        }
                    }
                    $this->viewData['tpower_array'] = $tpower_array;
                }

                $_c = 0;
                foreach ($_arr as $troopStr) {
                    list($troopId, $researches_done, $defense_level, $attack_level) = explode(' ', $troopStr);
                    if ($troopId != 99) {
                        ++$_c;
                    }
                    if ($_c > 8) {
                        break;
                    }
                    if ($troopId != 99) {
                        $tpower = $GameMetadata['troops'][$troopId]['defense_infantry'];
                        $tpower = $defense_level != 0 ? round((((2 * $defense_level) * $tpower) / 100) + $tpower) : $tpower;

                        $tpower_defense[] = $tpower;
                    }
                }
                $this->viewData['tpower_defense'] = $tpower_defense;

                $tpower_defense_cavalry = array();
                $_c = 0;
                foreach ($_arr as $troopStr) {
                    list($troopId, $researches_done, $defense_level, $attack_level) = explode(' ', $troopStr);
                    if ($troopId != 99) {
                        ++$_c;
                    }
                    if ($_c > 8) {
                        break;
                    }
                    if ($troopId != 99) {
                        $tpower = $GameMetadata['troops'][$troopId]['defense_cavalry'];
                        $tpower = $defense_level != 0 ? round((((2 * $defense_level) * $tpower) / 100) + $tpower) : $tpower;

                        $tpower_defense_cavalry[] = $tpower;
                    }
                }
                $this->viewData['tpower_defense_cavalry'] = $tpower_defense_cavalry;

                //
                $this->viewData['troopsUpgradeType'] = $this->troopsUpgradeType;

                //

                $troopsUpgrade_array = array();
                $_ac = 0;
                $buildingMetadata = $this->gameMetadata['items'][$this->buildProperties['building']['item_id']]['troop_upgrades'];
                foreach ($this->troopsUpgrade as $tid => $ulevel) {
                    ++$_ac;
                    $lvl = $buildingMetadata[$tid][$ulevel];
                    $lvlTime = intval($lvl['time_consume'] / $this->gameSpeed * (10 / ($this->buildProperties['building']['level'] + 9)));

                    $troopsUpgrade_array[$tid] = array(
                        'ulevel' => $ulevel,
                        'lvlTime' => secondstostring($lvlTime),
                        'lvl' => $lvl,
                        'getResourceGoldExchange' => $this->getResourceGoldExchange($lvl['resources'], 0, $this->buildingIndex),
                        'getActionText2' => $this->getResourceGoldExchange($lvl['resources'], 0, $this->buildingIndex),
                        'getActionText4' => $this->getActionText4($lvl['resources'], "a=" . $tid, LANGUI_CUSTBU_BLK_t4, $this->troopsUpgradeType, $this->buildProperties['building']['level'], $ulevel)
                    );
                }
                $this->viewData['troopsUpgrade_array'] = $troopsUpgrade_array;
                $this->viewData['_ac'] = $_ac;

                $this->viewData['tasksInQueue'] = isset($this->queueModel->tasksInQueue[$this->troopsUpgradeType]);
                if ($this->viewData['tasksInQueue']) {
                    list($tid, $ulevel) = explode(" ", $this->queueModel->tasksInQueue[$this->troopsUpgradeType][0]['proc_params']);
                    $this->viewData['tid'] = $tid;
                    $this->viewData['ulevel'] = $ulevel;
                    $this->viewData['remainingSeconds'] = secondstostring($this->queueModel->tasksInQueue[$this->troopsUpgradeType][0]['remainingSeconds']);
                }

                $tpl = new View;
                $tpl->assign($this->viewData);
                $this->viewData['buildingTemplateContent'] = $tpl->draw('buildings/Blacksmith_Armoury', true);
                break;
            case 15: // البيت الرئيسيى
                if (10 <= $this->buildings[$this->buildingIndex]['level']) {
                    $this->buildingView = 'MainBuilding';
                    $this->handleMainBuilding();

                    ## View
                    if (isset($this->queueModel->tasksInQueue[QS_BUILD_DROP])) {
                        $this->viewData['buildingIndex'] = $this->buildingIndex;
                        $this->viewData['if_QS_BUILD_DROP'] = TRUE;
                        $this->viewData['qtask'] = $this->queueModel->tasksInQueue[QS_BUILD_DROP][0];
                        $this->viewData['data'] = $this->data;
                        $this->viewData['proc_params_level'] = $this->buildings[$this->viewData['qtask']['proc_params']]['level'];
                    } else {
                        $this->viewData['if_QS_BUILD_DROP'] = FALSE;
                        $this->viewData['buildingIndex'] = $this->buildingIndex;

                        $i = 19;
                        $_c = sizeof($this->buildings);
                        $buildings_array = array();
                        while ($i < $_c) {
                            if (0 < $this->buildings[$i]['item_id']) {
                                $building_status = constant("item_" . $this->buildings[$i]['item_id']) . " " . $this->buildings[$i]['level'];
                            } else {
                                $building_status = FALSE;
                            }
                            $buildings_array[$i] = $building_status;
                            ++$i;
                        }
                        $this->viewData['buildings_array'] = $buildings_array;
                    }

                    $tpl = new View;
                    $tpl->assign($this->viewData);
                    $this->viewData['buildingTemplateContent'] = $tpl->draw('buildings/MainBuilding', true);
                }
                break;
            case 16: // نقطة التجمع
                $this->productionPane = FALSE;
                $this->buildingView = 'RallyPoint';
                $this->handleRallyPoint();

                $this->viewData['tap'] = is_get('t') && is_numeric(get('t')) && 0 <= intval(get('t')) && intval(get('t')) <= 3 ? intval(get('t')) : 0;
                $this->load_model('Artefacts', 'A');
                $artLevel = $this->A->Artefacts($this->player->playerId, $this->data['selected_village_id'], 4);
                $this->viewData['artPower'] = $artLevel;
                // war to village
                $this->viewData['war_to_village_size'] = !empty($this->rallyPointProperty['war_to_village']);
                if ($this->viewData['war_to_village_size']) {
                    $war_to_village = array();
                    foreach ($this->rallyPointProperty['war_to_village'] as $key => $taskTable) {
                        $procType = $taskTable['proc_type'];
                        $resources = NULL;
                        $action1 = "";
                        switch ($procType) {
                            case QS_WAR_REINFORCE:
                                $_arr = explode("|", $taskTable['proc_params']);
                                $troopsBack = $_arr[sizeof($_arr) - 1] == 1;
                                $action1 = $troopsBack ? LANGUI_CUSTBU_RP_t2 : LANGUI_CUSTBU_RP_t10;
                                if ($troopsBack && trim($_arr[4]) != "") {
                                    $resources = explode(" ", $_arr[4]);
                                }
                                break;
                            default:
                                switch ($procType) {
                                    case QS_WAR_ATTACK:
                                        $action1 = LANGUI_CUSTBU_RP_t3;
                                        break;
                                    default:
                                        switch ($procType) {
                                            case QS_WAR_ATTACK_PLUNDER:
                                                $action1 = LANGUI_CUSTBU_RP_t4;
                                        }
                                }
                        }
                        $action1 .= " " . $this->data['village_name'];
                        $action2 = "";
                        $actionRow = $this->m->getVillageData2ById(intval($taskTable['village_id']));
                        if ($actionRow == NULL || intval($actionRow['player_id']) != intval($taskTable['player_id'])) {
                            $action2 .= "<span class='none'>[?]</span>";
                        } else {
                            $action2 .= $actionRow['tribe_id'] == 4 ? LANGUI_CUSTBU_RP_t5 : $actionRow['village_name'];
                        }
                        $_arr = explode("|", $taskTable['proc_params']);
                        $troopsStr = explode(",", $_arr[0]);
                        $hasHero = FALSE;
                        $troops = array();
                        foreach ($troopsStr as $s) {
                            list($tid, $tnum) = explode(" ", $s);
                            if ($tnum == 0 - 1) {
                                $hasHero = TRUE;
                                continue;
                            }
                            $troops[$tid] = $tnum;
                        }
                        $colspan = $hasHero && $procType == QS_WAR_REINFORCE ? 11 : 10;

                        $war_to_village[$key] = array(
                            'actionRow' => $actionRow,
                            'taskTable' => $taskTable,
                            'action1' => $action1,
                            'action2' => $action2,
                            'colspan' => $colspan,
                            'troops' => $troops,
                            'hasHero' => $hasHero,
                            'procType' => $procType,
                            'resources' => $resources
                        );
                    }
                    $this->viewData['war_to_village'] = $war_to_village;
                }

                // war from village
                $this->viewData['war_from_village_size'] = !empty($this->rallyPointProperty['war_from_village']);
                if ($this->viewData['war_from_village_size']) {
                    $this->viewData['data'] = $this->data;

                    $war_from_village = array();
                    foreach ($this->rallyPointProperty['war_from_village'] as $key => $taskTable) {
                        $procType = $taskTable['proc_type'];
                        $_arr = explode("|", $taskTable['proc_params']);
                        $resources = NULL;
                        $action = "";
                        switch ($procType) {
                            case QS_WAR_REINFORCE:
                                $action = LANGUI_CUSTBU_RP_t10;
                                break;
                            default:
                                switch ($procType) {
                                    case QS_WAR_ATTACK:
                                        $action = LANGUI_CUSTBU_RP_t3;
                                        break;
                                    default:
                                        switch ($procType) {
                                            case QS_WAR_ATTACK_PLUNDER:
                                                $action = LANGUI_CUSTBU_RP_t4;
                                                break;
                                            default:
                                                switch ($procType) {
                                                    case QS_WAR_ATTACK_SPY:
                                                        $action = LANGUI_CUSTBU_RP_t11;
                                                        break;
                                                    default:
                                                        switch ($procType) {
                                                            case QS_CREATEVILLAGE:
                                                                $action = LANGUI_CUSTBU_RP_t12;
                                                                $resources = explode(" ", $_arr[4]);
                                                        }
                                                }
                                        }
                                }
                        }
                        if ($procType != QS_CREATEVILLAGE) {
                            $actionRow = $this->m->getVillageData2ById($taskTable['to_village_id']);
                            if ($actionRow == NULL) {
                                $action .= "<span class='none'>[?]</span>";
                            } else {
                                if ($actionRow['is_oasis']) {
                                    $action .= 0 < intval($actionRow['player_id']) ? " " . LANGUI_CUSTBU_RP_t13 : " " . LANGUI_CUSTBU_RP_t14;
                                } else {
                                    $action .= 0 < intval($actionRow['player_id']) ? " " . $actionRow['village_name'] : " <span class='none'>[?]</span>";
                                }
                            }
                        }
                        $troopsStr = explode(",", $_arr[0]);
                        $hasHero = FALSE;
                        $troops = array();
                        foreach ($troopsStr as $s) {
                            list($tid, $tnum) = explode(" ", $s);
                            if ($tnum == 0 - 1) {
                                $hasHero = TRUE;
                                continue;
                            }
                            $troops[$tid] = $tnum;
                        }
                        $colspan = $hasHero ? 11 : 10;

                        $war_from_village[$key] = array(
                            'taskTable' => $taskTable,
                            'colspan' => $colspan,
                            'action' => $action,
                            'troops' => $troops,
                            'hasHero' => $hasHero,
                            'canCancelWarTask' => $this->_canCancelWarTask($taskTable['proc_type'], $taskTable['id']),
                            'resources' => $resources,
                            'remainingSeconds' => $taskTable['remainingSeconds']
                        );
                    }
                    $this->viewData['war_from_village'] = $war_from_village;
                }

                // troops in village
                $this->viewData['troops_in_village_size'] = (!empty($this->rallyPointProperty['troops_in_village']['troopsTable']) || !empty($this->rallyPointProperty['troops_in_village']['troopsIntrapTable']));
                if ($this->viewData['troops_in_village_size']) {
                    $troops_in_village = array();
                    foreach ($this->rallyPointProperty['troops_in_village']['troopsTable'] as $vid => $troopTable) {
                        $colspan = $troopTable['hasHero'] ? 11 : 10;
                        $canBack = TRUE;

                        $troops_in_village[$vid] = array(
                            'troopTable' => $troopTable,
                            'colspan' => $colspan,
                            'canBack' => $canBack
                        );
                    }
                    $this->viewData['troops_in_village'] = $troops_in_village;

                    $troops_in_village_in_table = array();
                    foreach ($this->rallyPointProperty['troops_in_village']['troopsIntrapTable'] as $vid => $troopTable) {
                        $colspan = $troopTable['hasHero'] ? 11 : 10;

                        $troops_in_village_in_table[$vid] = array(
                            'troopTable' => $troopTable,
                            'colspan' => $colspan
                        );
                    }
                    $this->viewData['troops_in_village_in_table'] = $troops_in_village_in_table;
                }

                // troops out village
                $this->viewData['troops_out_village_size'] = (!empty($this->rallyPointProperty['troops_out_village']['troopsTable']) || !empty($this->rallyPointProperty['troops_out_village']['troopsIntrapTable']));
                if ($this->viewData['troops_out_village_size']) {
                    $troops_out_village = array();
                    foreach ($this->rallyPointProperty['troops_out_village']['troopsTable'] as $vid => $troopTable) {
                        $colspan = $troopTable['hasHero'] ? 11 : 10;

                        $troops_out_village[$vid] = array(
                            'troopTable' => $troopTable,
                            'colspan' => $colspan
                        );
                    }
                    $this->viewData['troops_out_village'] = $troops_out_village;

                    $troops_out_village_in_table = array();
                    foreach ($this->rallyPointProperty['troops_out_village']['troopsIntrapTable'] as $vid => $troopTable) {
                        $colspan = $troopTable['hasHero'] ? 11 : 10;

                        $troops_out_village_in_table[$vid] = array(
                            'troopTable' => $troopTable,
                            'colspan' => $colspan
                        );
                    }
                    $this->viewData['troops_out_village_in_table'] = $troops_out_village_in_table;
                }

                // troops in oases
                $this->viewData['troops_in_oases_size'] = (!empty($this->rallyPointProperty['troops_in_oases']));
                if ($this->viewData['troops_in_oases_size']) {
                    $troops_in_oases = array();
                    foreach ($this->rallyPointProperty['troops_in_oases'] as $oasisId => $oasisData) {
                        if (empty($oasisData['troopsTable']) && empty($oasisData['war_to'])) {
                            continue;
                        }
                        $troops_in_oases[$oasisId]['oasisData'] = $oasisData;

                        if (!empty($oasisData['war_to'])) {
                            $war_to_oasis_data = array();
                            foreach ($oasisData['war_to'] as $key => $taskTable) {
                                $procType = $taskTable['proc_type'];
                                $action1 = "";
                                switch ($procType) {
                                    case QS_WAR_REINFORCE:
                                        $action1 = LANGUI_CUSTBU_RP_t10;
                                        break;
                                    default:
                                        switch ($procType) {
                                            case QS_WAR_ATTACK:
                                                $action1 = LANGUI_CUSTBU_RP_t3;
                                                break;
                                            default:
                                                switch ($procType) {
                                                    case QS_WAR_ATTACK_PLUNDER:
                                                        $action1 = LANGUI_CUSTBU_RP_t4;
                                                }
                                        }
                                }
                                $action1 .= " " . LANGUI_CUSTBU_RP_t26;
                                $action2 = "";
                                $actionRow = $this->m->getVillageData2ById($taskTable['village_id']);
                                if ($actionRow == NULL || intval($actionRow['player_id']) != intval($taskTable['player_id'])) {
                                    $action2 .= "<span class='none'>[?]</span>";
                                } else {
                                    $action2 .= $actionRow['tribe_id'] == 4 ? LANGUI_CUSTBU_RP_t5 : $actionRow['village_name'];
                                }
                                $_arr = explode("|", $taskTable['proc_params']);
                                $troopsStr = explode(",", $_arr[0]);
                                $hasHero = FALSE;
                                $troops = array();
                                foreach ($troopsStr as $s) {
                                    list($tid, $tnum) = explode(" ", $s);
                                    if ($tnum == 0 - 1) {
                                        $hasHero = TRUE;
                                        continue;
                                    }
                                    $troops[$tid] = $tnum;
                                }
                                $colspan = $hasHero && $procType == QS_WAR_REINFORCE ? 11 : 10;

                                $war_to_oasis_data[$key] = array(
                                    'colspan' => $colspan,
                                    'action1' => $action1,
                                    'action2' => $action2,
                                    'taskTable' => $taskTable,
                                    'troops' => $troops,
                                    'hasHero' => $hasHero,
                                    'procType' => $procType
                                );
                            }
                            $troops_in_oases[$oasisId]['war_to_oasis_data'] = $war_to_oasis_data;
                        }

                        if (!empty($oasisData['troopsTable'])) {
                            $oasisdata_troopsTable = array();
                            foreach ($oasisData['troopsTable'] as $vid => $troopTable) {
                                $colspan = $troopTable['hasHero'] ? 11 : 10;

                                $oasisdata_troopsTable[] = array(
                                    'troopTable' => $troopTable,
                                    'colspan' => $colspan
                                );
                            }
                            $troops_in_oases[$oasisId]['troopsTable'] = $oasisdata_troopsTable;
                        }
                    }
                    $this->viewData['troops_in_oases'] = $troops_in_oases;
                }

                $this->viewData['buildingIndex'] = $this->buildingIndex;
                $tpl = new View;
                $tpl->assign($this->viewData);
                $this->viewData['buildingTemplateContent'] = $tpl->draw('buildings/RallyPoint', true);
                break;
            case 17: // السوق
                $this->productionPane = FALSE;
                $this->handleMarketplace();

                ## View
                $this->viewData['selectedTabIndex'] = $this->selectedTabIndex;
                $this->viewData['embassyProperty'] = $this->embassyProperty;
                $this->viewData['buildingIndex'] = $this->buildingIndex;
                $this->viewData['data'] = $this->data;
                $this->viewData['merchantProperty'] = $this->merchantProperty;

                // merchant coming
                $akey = "merchant_coming";
                $is_merchant_coming = isset($this->queueModel->tasksInQueue[$akey]) && !empty($this->queueModel->tasksInQueue[$akey]);
                $this->viewData['is_merchant_coming'] = $is_merchant_coming;
                if ($is_merchant_coming) {
                    $merchant_coming = array();

                    $qts = $this->queueModel->tasksInQueue[$akey];
                    $this->load_model('Build', 'm');
                    foreach ($qts as $qt) {
                        list($merchantNum, $resStr) = explode("|", $qt['proc_params']);
                        $mResources = explode(" ", $resStr);
                        $pn = $this->m->getPlayerName($qt['player_id']);
                        $vn = $this->m->getVillageName($qt['village_id']);

                        $merchant_coming[] = array(
                            'mResources' => $mResources,
                            'pn' => $pn,
                            'vn' => $vn,
                            'qt' => $qt
                        );
                    }
                    $this->viewData['merchant_coming'] = $merchant_coming;
                }

                // merchant travel
                $akey2 = "merchant_travel";
                $is_merchant_travel = isset($this->queueModel->tasksInQueue[$akey2]) && !empty($this->queueModel->tasksInQueue[$akey2]);
                $this->viewData['is_merchant_travel'] = $is_merchant_travel;
                if (isset($this->queueModel->tasksInQueue[$akey2]) && !empty($this->queueModel->tasksInQueue[$akey2])) {
                    $merchant_travel = array();

                    $qts2 = $this->queueModel->tasksInQueue[$akey2];
                    $this->load_model('Build', 'm');
                    foreach ($qts2 as $qt2) {
                        list($merchantNum, $resStr) = explode("|", $qt2['proc_params']);
                        $mResources = explode(" ", $resStr);
                        $vn2 = $this->m->getVillageName($qt2['to_village_id']);

                        $merchant_travel[] = array(
                            'qt' => $qt2,
                            'vn2' => $vn2,
                            'mResources' => $mResources,
                            'merchantNum' => $merchantNum
                        );
                    }
                    $this->viewData['merchant_travel'] = $merchant_travel;
                }

                if ($this->selectedTabIndex == 1) {
                    if ($this->merchantProperty['showOfferList']) {
                        // all offers
                        $all_offers = array();
                        $_c = 0;
                        $this->load_model('Build', 'm');
                        foreach ($this->merchantProperty['all_offers'] as $value) {
                            ++$_c;
                            $aid = 0;
                            if ($value['alliance_only']) {
                                if (0 < intval($this->data['alliance_id'])) {
                                    $aid = $this->m->getPlayerAllianceId($value['player_id']);
                                    if (intval($this->data['alliance_id']) != $aid) {
                                        continue;
                                    }
                                }
                                continue;
                            }
                            list($res1, $res2) = explode("|", $value['offer']);
                            $resArr1 = explode(" ", $res1);
                            $needResources = array(
                                "1" => $resArr1[0],
                                "2" => $resArr1[1],
                                "3" => $resArr1[2],
                                "4" => $resArr1[3]
                            );
                            $res1_item_id = 0;
                            $res1_value = 0;
                            $i = 0;
                            $_c = sizeof($resArr1);
                            while ($i < $_c) {
                                if (0 < $resArr1[$i]) {
                                    $res1_item_id = $i + 1;
                                    $res1_value = $resArr1[$i];
                                    break;
                                }
                                ++$i;
                            }
                            $resArr1 = explode(" ", $res2);
                            $giveResources = array(
                                "1" => $resArr1[0],
                                "2" => $resArr1[1],
                                "3" => $resArr1[2],
                                "4" => $resArr1[3]
                            );
                            $res2_item_id = 0;
                            $res2_value = 0;

                            $i = 0;
                            $_c = sizeof($resArr1);
                            while ($i < $_c) {
                                if (0 < $resArr1[$i]) {
                                    $res2_item_id = $i + 1;
                                    $res2_value = $resArr1[$i];
                                    break;
                                }
                                ++$i;
                            }

                            $acceptResultString = '';
                            $acceptResult = $this->_canAcceptOffer($needResources, $giveResources, $value['village_id'], $value['alliance_only'], $aid, $value['max_time'], $value['timeInSeconds'] / 3600 * $value['merchants_speed']);


                            switch ($acceptResult) {
                                case 1:
                                    $acceptResultString = LANGUI_CUSTBU_MKT_p2_t6;
                                    break;
                                case 2:
                                    $acceptResultString = LANGUI_CUSTBU_MKT_p2_t7;
                                    break;
                                case 5:
                                    $acceptResultString = sprintf("<a href='build?id=%s&t=%s&oid=%s'>" . LANGUI_CUSTBU_MKT_p2_t8 . "</a>", $this->buildingIndex, $this->selectedTabIndex, $value['id']);
                            }

                            $all_offers[] = array(
                                'value' => $value,
                                'res1_value' => $res1_value,
                                'res1_item_id' => $res1_item_id,
                                'res2_value' => $res2_value,
                                'res2_item_id' => $res2_item_id,
                                'acceptResultString' => $acceptResultString
                            );
                        }
                        $this->viewData['_c'] = $_c;
                        $this->viewData['all_offers'] = $all_offers;
                        $this->viewData['getPreviousLink'] = $this->getPreviousLink();
                        $this->viewData['getNextLink'] = $this->getNextLink();
                    }
                }

                if ($this->selectedTabIndex == 2) {
                    // merchant offers
                    $merchant_offers = array();
                    foreach ($this->merchantProperty['offers'] as $merchant_offer) {
                        list($res1, $res2) = explode("|", $merchant_offer['offer']);
                        $resArr1 = explode(" ", $res1);
                        $res1_item_id = 0;
                        $res1_value = 0;
                        $i = 0;
                        $_c = sizeof($resArr1);
                        while ($i < $_c) {
                            if (0 < $resArr1[$i]) {
                                $res1_item_id = $i + 1;
                                $res1_value = $resArr1[$i];
                                break;
                            }
                            ++$i;
                        }
                        $resArr1 = explode(" ", $res2);
                        $res2_item_id = 0;
                        $res2_value = 0;
                        $i = 0;
                        $_c = sizeof($resArr1);
                        while ($i < $_c) {
                            if (0 < $resArr1[$i]) {
                                $res2_item_id = $i + 1;
                                $res2_value = $resArr1[$i];
                                break;
                            }
                            ++$i;
                        }
                        $merchant_offers[] = array(
                            'merchant_offer' => $merchant_offer,
                            'res1_value' => $res1_value,
                            'res1_item_id' => $res1_item_id,
                            'res2_value' => $res2_value,
                            'res2_item_id' => $res2_item_id
                        );
                    }
                    $this->viewData['merchant_offers'] = $merchant_offers;
                }

                //
                if (isset($_GET['rid'])) {
                    $rid = intval($_GET['rid']);
                    $prop = $this->getBuildingProperties($rid);
                    $btext = LANGUI_CUSTBU_MKT_p4_t7;
                    if (!$prop['emptyPlace']) {
                        $btext .= " " . text_to_lang . " " . constant("item_" . $prop['building']['item_id']);
                    }
                    $this->viewData['rid'] = $rid;
                    $this->viewData['btext'] = $btext;
                }

                //
                $this->buildingView = 'Marketplace';
                $tpl = new View;
                $tpl->assign($this->viewData);
                $this->viewData['buildingTemplateContent'] = $tpl->draw('buildings/Marketplace', true);
                break;
            case 18: // السفارة
                $this->productionPane = FALSE;
                $this->handleEmbassy();

                $this->viewData['embassyProperty'] = $this->embassyProperty;
                $this->viewData['buildingIndex'] = $this->buildingIndex;
                $this->viewData['data'] = $this->data;
                $this->buildingView = 'Embassy';
                $tpl = new View;
                $tpl->assign($this->viewData);
                $this->viewData['buildingTemplateContent'] = $tpl->draw('buildings/Embassy', true);
                break;
            case 19: // الثنكة
            case 20: //
            case 21: //
            case 29: //
            case 30: //
            case 36: //
                $this->_getOnlyMyTroops();
                $this->productionPane = $bitemId == 36;
                $this->buildingView = 'TroopBuilding';
                $this->handleTroopBuilding();

                $this->viewData['buildingIndex'] = $this->buildingIndex;
                $this->viewData['if_building_id_36'] = $this->buildings[$this->buildingIndex]['item_id'] == 36;
                $this->viewData['data'] = $this->data;
                $this->viewData['buildProperties'] = $this->buildProperties;
                $this->load_model('Artefacts', 'A');
                $artLevel2 = $this->A->Artefacts($this->player->playerId, $this->data['selected_village_id'], 8);
                $this->artPower2 = ($artLevel2 == 0) ? 1 : (($artLevel2 == 1) ? 0.5 : (($artLevel2 == 2) ? 0.75 : 0.5));
                $troopsUpgrade = array();
                $_ac = 0;
                foreach ($this->troopsUpgrade as $tid) {
                    ++$_ac;
                    $buildingMetadata = $this->gameMetadata['troops'][$tid];
                    $timeFactor = 1;
                    if ($buildingMetadata['is_cavalry'] == TRUE) {
                        $flvl = $this->_getMaxBuildingLevel(41);
                        if (0 < $flvl) {
                            $timeFactor -= $this->gameMetadata['items'][41]['levels'][$flvl - 1]['value'] / 100;
                        }
                    }
                    $lvlTime = intval($buildingMetadata['training_time_consume'] / $this->gameSpeed * (10 / ($this->buildProperties['building']['level'] + 9)) * $timeFactor * $this->artPower);
                    $maxNumber = $this->_getMaxTrainNumber($tid, $this->buildings[$this->buildingIndex]['item_id']);
                    $manual = $tid == 99 ? "4,36" : "3," . $tid;
                    $neededResources = array();
                    foreach ($buildingMetadata['training_resources'] as $k => $v) {
                        $neededResources[$k] = $v * $this->buildingTribeFactor;
                    }

                    $troopsUpgrade[$tid] = array(
                        'manual' => $manual,
                        'maxNumber' => $maxNumber,
                        'lvlTime' => secondstostring($lvlTime),
                        'troop_name' => $this->troops[$tid],
                        'training_resources_1' => $buildingMetadata['training_resources'][1] * $this->buildingTribeFactor * $this->artPower2,
                        'training_resources_2' => $buildingMetadata['training_resources'][2] * $this->buildingTribeFactor * $this->artPower2,
                        'training_resources_3' => $buildingMetadata['training_resources'][3] * $this->buildingTribeFactor * $this->artPower2,
                        'training_resources_4' => $buildingMetadata['training_resources'][4] * $this->buildingTribeFactor * $this->artPower2,
                        'neededResources' => $this->getResourceGoldExchange($neededResources, 0, $this->buildingIndex, TRUE)
                    );
                }
                $this->viewData['troopsUpgrade'] = $troopsUpgrade;
                $this->viewData['_ac'] = $_ac;
                $this->viewData['troops'] = $this->troops;

                $this->viewData['isset_tasksInQueue'] = isset($this->queueModel->tasksInQueue[$this->troopsUpgradeType], $this->queueModel->tasksInQueue[$this->troopsUpgradeType][$this->buildProperties['building']['item_id']]);

                if ($this->viewData['isset_tasksInQueue']) {
                    $this->viewData['qts'] = $this->queueModel->tasksInQueue[$this->troopsUpgradeType][$this->buildProperties['building']['item_id']];
                }

                $tpl = new View;
                $tpl->assign($this->viewData);
                $this->viewData['buildingTemplateContent'] = $tpl->draw('buildings/TroopBuilding', true);
                break;
            case 22: // الاكادية الحربية
                $this->productionPane = FALSE;
                $this->buildingView = 'Academy';
                $this->handleAcademy();

                // View
                $troopMetadata = $this->gameMetadata['troops'];

                $troops_upgrade_available = array();

                foreach ($this->troopsUpgrade['available'] as $tid) {
                    $lvlTime = intval($troopMetadata[$tid]['research_time_consume'] / $this->gameSpeed);
                    $troops_upgrade_available[$tid] = array(
                        'troopMetadataSelected' => $troopMetadata[$tid],
                        'lvlTime' => $lvlTime,
                        'getResourceGoldExchange' => $this->getResourceGoldExchange($troopMetadata[$tid]['research_resources'], 0, $this->buildingIndex),
                        'getActionText2' => $this->getActionText2($troopMetadata[$tid]['research_resources']),
                        'getActionText3' => $this->getActionText3($troopMetadata[$tid]['research_resources'], "a=" . $tid, LANGUI_CUSTBU_ACD_t8, $this->troopsUpgradeType)
                    );
                }
                $this->viewData['troops_upgrade_available'] = $troops_upgrade_available;

                $troops_upgrade_soon = array();
                foreach ($this->troopsUpgrade['soon'] as $tid2) {
                    $troops_upgrade_soon[$tid2] = $troopMetadata[$tid2]['pre_requests'];
                }
                //
                $this->viewData['troops_upgrade_soon'] = $troops_upgrade_soon;

                //
                $this->viewData['tasksInQueue'] = $this->queueModel->tasksInQueue;
                $this->viewData['queueTroopsUpgradeType'] = isset($this->queueModel->tasksInQueue[$this->troopsUpgradeType]);

                if ($this->viewData['queueTroopsUpgradeType']) {
                    $this->viewData['tid'] = $this->queueModel->tasksInQueue[$this->troopsUpgradeType][0]['proc_params'];
                    $this->viewData['queueRemainingSeconds'] = secondstostring($this->queueModel->tasksInQueue[$this->troopsUpgradeType][0]['remainingSeconds']);
                }

                //
                $tpl = new View;
                $tpl->assign($this->viewData);
                $this->viewData['buildingTemplateContent'] = $tpl->draw('buildings/Academy', true);
                break;
            case 23: // المخبىء
                $this->productionPane = TRUE;
                $this->buildingView = 'Cranny';
                $this->viewData['crannyProperty'] = $this->crannyProperty;
                $this->viewData['buildProperties'] = $this->buildProperties;
                $tpl = new View;
                $tpl->assign($this->viewData);
                $this->viewData['buildingTemplateContent'] = $tpl->draw('buildings/Cranny', true);
                break;
            case 24: // البلدية
                $this->productionPane = FALSE;
                $this->buildingView = 'TownHall';
                $this->handleTownHall();

                ## View
                $this->viewData['buildingMetadata'] = $buildingMetadata = $this->gameMetadata['items'][$this->buildProperties['building']['item_id']];
                $this->viewData['bLevel'] = $bLevel = $this->buildings[$this->buildingIndex]['level'];
                $this->viewData['buildProperties'] = $this->buildProperties;

                if ($buildingMetadata['celebrations']['small']['level'] <= $this->buildProperties['building']['level']) {

                    $this->viewData['time_consume_seconds'] = secondstostring(intval($buildingMetadata['celebrations']['small']['time_consume'] / $this->gameSpeed * (10 / ($bLevel + 9))));
                    $this->viewData['getResourceGoldExchange'] = $this->getResourceGoldExchange($buildingMetadata['celebrations']['small']['resources'], 0, $this->buildingIndex);
                    $this->viewData['getActionText2'] = $this->getActionText2($buildingMetadata['celebrations']['small']['resources']);
                    $this->viewData['getActionText3'] = $this->getActionText3($buildingMetadata['celebrations']['small']['resources'], "a=1", LANGUI_CUSTBU_TWH_t5, QS_TOWNHALL_CELEBRATION);
                }

                if ($buildingMetadata['celebrations']['large']['level'] <= $this->buildProperties['building']['level']) {

                    $this->viewData['time_consume_seconds_large'] = secondstostring(intval($buildingMetadata['celebrations']['large']['time_consume'] / $this->gameSpeed * (10 / ($bLevel + 9))));
                    $this->viewData['getResourceGoldExchange_large'] = $this->getResourceGoldExchange($buildingMetadata['celebrations']['large']['resources'], 0, $this->buildingIndex);
                    $this->viewData['getActionText2_large'] = $this->getActionText2($buildingMetadata['celebrations']['large']['resources']);
                    $this->viewData['getActionText3_large'] = $this->getActionText3($buildingMetadata['celebrations']['large']['resources'], "a=2", LANGUI_CUSTBU_TWH_t5, QS_TOWNHALL_CELEBRATION);
                }

                $this->viewData['QS_TOWNHALL_CELEBRATION'] = isset($this->queueModel->tasksInQueue[QS_TOWNHALL_CELEBRATION]);
                if ($this->viewData['QS_TOWNHALL_CELEBRATION']) {

                    $this->viewData['proc_params'] = $this->queueModel->tasksInQueue[QS_TOWNHALL_CELEBRATION][0]['proc_params'] == 1 ? LANGUI_CUSTBU_TWH_t3 : LANGUI_CUSTBU_TWH_t6;
                    $this->viewData['remainingSeconds'] = secondstostring($this->queueModel->tasksInQueue[QS_TOWNHALL_CELEBRATION][0]['remainingSeconds']);
                }

                $tpl = new View;
                $tpl->assign($this->viewData);
                $this->viewData['buildingTemplateContent'] = $tpl->draw('buildings/TownHall', true);
                break;
            case 25:
            case 26: // السكن
                $this->productionPane = FALSE;
                $this->buildingView = 'Residence_Palace';
                $this->handleResidencePalace();

                $this->viewData['buildingIndex'] = $this->buildingIndex;
                $this->viewData['selectedTabIndex'] = $this->selectedTabIndex;
                $this->viewData['showBuildingForm'] = $this->showBuildingForm;
                $this->viewData['cpRate'] = $this->cpRate;
                $this->viewData['totalCpRate'] = $this->totalCpRate;
                $this->viewData['totalCpValue'] = $this->totalCpValue;
                $this->viewData['neededCpValue'] = $this->neededCpValue;
                $this->viewData['childVillages'] = $this->childVillages;

                //
                if ($this->selectedTabIndex == 0) {
                    $troopsUpgrade = array();
                    foreach ($this->troopsUpgrade as $troop) {
                        $tid = $troop['troopId'];
                        $buildingMetadata = $this->gameMetadata['troops'][$tid];
                        $lvlTime = intval($buildingMetadata['training_time_consume'] / $this->gameSpeed * (10 / ($this->buildProperties['building']['level'] + 9)));
                        $maxNumber = $this->_getMaxTrainNumber($tid, $this->buildings[$this->buildingIndex]['item_id']);

                        $neededResources = array();
                        foreach ($buildingMetadata['training_resources'] as $k => $v) {
                            $neededResources[$k] = $v * $this->buildingTribeFactor;
                        }

                        $troopsUpgrade[] = array(
                            'tid' => $tid,
                            'lvlTime' => $lvlTime,
                            'maxNumber' => $maxNumber,
                            'troop' => $this->troops[$tid],
                            'training_resources_1' => $buildingMetadata['training_resources'][1] * $this->buildingTribeFactor,
                            'training_resources_2' => $buildingMetadata['training_resources'][2] * $this->buildingTribeFactor,
                            'training_resources_3' => $buildingMetadata['training_resources'][3] * $this->buildingTribeFactor,
                            'training_resources_4' => $buildingMetadata['training_resources'][4] * $this->buildingTribeFactor,
                            'getResourceGoldExchange' => $this->getResourceGoldExchange($neededResources, 0, $this->buildingIndex, TRUE)
                        );
                    }
                    $this->viewData['troopsUpgrade'] = $troopsUpgrade;
                }

                //
                $this->viewData['childVillagesCount'] = $this->childVillagesCount;
                $this->viewData['data'] = $this->data;
                $this->viewData['check_build_exists'] = ($this->buildings[$this->buildingIndex]['level'] < 20 && !$this->data['is_capital'] || $this->buildings[$this->buildingIndex]['level'] < 15 && $this->data['is_capital']) ? TRUE : FALSE;

                //
                $this->viewData['building_level_10'] = ($this->buildings[$this->buildingIndex]['level'] < 10) ? TRUE : FALSE;
                $this->viewData['building_level_20'] = ($this->buildings[$this->buildingIndex]['level'] < 20) ? TRUE : FALSE;

                //
                $this->viewData['tasksInQueue_building_upgrade'] = isset($this->queueModel->tasksInQueue[$this->troopsUpgradeType], $this->queueModel->tasksInQueue[$this->troopsUpgradeType][$this->buildProperties['building']['item_id']]);
                if ($this->viewData['tasksInQueue_building_upgrade']) {
                    $qts = $this->queueModel->tasksInQueue[$this->troopsUpgradeType][$this->buildProperties['building']['item_id']];
                    $qts_view = array();
                    $nextTroopTime = 0;
                    $_f = TRUE;
                    foreach ($qts as $qt) {
                        $tid = $qt['proc_params'];
                        $troopTime = $qt['execution_time'] - ($qt['execution_time'] * $qt['threads'] - $qt['remainingSeconds']);
                        if ($troopTime < $nextTroopTime || $_f) {
                            $_f = FALSE;
                            $nextTroopTime = $troopTime;
                        }
                        $qts_view[] = array(
                            'tid' => $tid,
                            'threads' => $qt['threads'],
                            'remainingSeconds' => secondstostring($qt['remainingSeconds'])
                        );
                    }
                    $this->viewData['qts'] = $qts_view;
                    $this->viewData['nextTroopTime'] = $nextTroopTime;
                }

                //
                $this->viewData['if_building_id_26'] = $this->buildings[$this->buildingIndex]['item_id'] == 26;
                if ($this->viewData['if_building_id_26']) {
                    $this->load_model('Build', 'm');
                    $this->viewData['VillageData'] = $this->m->getVillageDataById($this->data['selected_village_id']);
                }

                $tpl = new View;
                $tpl->assign($this->viewData);
                $this->viewData['buildingTemplateContent'] = $tpl->draw('buildings/Residence_Palace', true);
                break;
            case 27:
                $this->productionPane = FALSE;
                $this->buildingView = 'Artefacts';
                $this->handleArtefacts();
                $this->viewData['buildingIndex'] = $this->buildingIndex;
                $this->viewData['selectedTabIndex'] = $this->selectedTabIndex;
                $this->viewData['Artefacts'] = $this->Artefacts;
                $tpl = new View;
                $tpl->assign($this->viewData);
                $this->viewData['buildingTemplateContent'] = $tpl->draw('buildings/Artefacts', true);
                break;
            case 37: // قصر الابطال
                $this->productionPane = FALSE;
                $this->buildingView = 'HerosMansion';
                $this->handleHerosMansion();

                $this->viewData['selectedTabIndex'] = $this->selectedTabIndex;
                $this->viewData['buildingIndex'] = $this->buildingIndex;

                if ($this->selectedTabIndex == 0) {
                    $this->viewData['hasHero'] = $this->hasHero;

                    if (!$this->hasHero) {
                        $this->viewData['troopsUpgradeType'] = isset($this->queueModel->tasksInQueue[$this->troopsUpgradeType]);

                        if (!$this->viewData['troopsUpgradeType']) {
                            $troops = array();
                            $_c = 0;
                            foreach ($this->troops as $tid => $tnum) {
                                if ($tnum <= 0) {
                                    continue;
                                }
                                ++$_c;
                                $troopMetadata = $this->gameMetadata['troops'][$tid];
                                $lvlTime = intval($troopMetadata['training_time_consume'] / $this->gameSpeed * (10 / ($this->buildProperties['building']['level'] + 9))) * 12;
                                $res = array(
                                    "1" => $troopMetadata['training_resources'][1] * 2,
                                    "2" => $troopMetadata['training_resources'][2] * 2,
                                    "3" => $troopMetadata['training_resources'][3] * 2,
                                    "4" => $troopMetadata['training_resources'][4] * 2
                                );

                                $troops[$tid] = array(
                                    'res' => $res,
                                    'getResourceGoldExchange' => $this->getResourceGoldExchange($res, 0, $this->buildingIndex),
                                    'lvlTime' => secondstostring($lvlTime),
                                    'getActionText2' => $this->getActionText2($res),
                                    'getActionText3' => $this->getActionText3($res, "a=" . $tid, LANGUI_CUSTBU_HRO_p1_t7, $this->troopsUpgradeType)
                                );
                            }
                            $this->viewData['troops'] = $troops;
                            $this->viewData['_c'] = $_c;
                        } else {
                            $this->viewData['troopsUpgradeType_remainingSeconds'] = secondstostring($this->queueModel->tasksInQueue[$this->troopsUpgradeType][0]['remainingSeconds']);
                        }
                    } else {
                        $this->viewData['data'] = $this->data;
                        $this->viewData['buildingIndex'] = $this->buildingIndex;

                    }
                } elseif ($this->selectedTabIndex == 1) {
                    $this->viewData['buildingIndex'] = $this->buildingIndex;
                    $this->viewData['data'] = $this->data;

                    $villageOases = array();
                    foreach ($this->villageOases as $id => $oasis) {
                        $oid = $this->setupMetadata['oasis'][$oasis['image_num']];
                        $str = "";
                        foreach ($oid as $k => $v) {
                            if ($str != "") {
                                $str .= PHP_EOL . " " . text_and_lang . " ";
                            }
                            $str .= sprintf("<img class='r%s' src='" . ASSETS_DIR . "x.gif' alt='%s' title='%s'>+%s%%", $k, constant("item_title_" . $k), constant("item_title_" . $k), $v);
                        }

                        $villageOases[$id] = array(
                            'QS_LEAVEOASIS' => isset($this->queueModel->tasksInQueue[QS_LEAVEOASIS][$oasis['id']]),
                            'oasis' => $oasis,
                            'str' => $str
                        );
                    }
                    $this->viewData['villageOases'] = $villageOases;
                    $this->viewData['QS_LEAVEOASIS'] = isset($this->queueModel->tasksInQueue[QS_LEAVEOASIS]);
                    if ($this->viewData['QS_LEAVEOASIS']) {
                        $this->viewData['QS_LEAVEOASIS'] = $this->queueModel->tasksInQueue[QS_LEAVEOASIS];
                    }
                }

                $tpl = new View;
                $tpl->assign($this->viewData);
                $this->viewData['buildingTemplateContent'] = $tpl->draw('buildings/HerosMansion', true);
                break;
            case 40:
                $this->productionPane = FALSE;
                break;
            case 42: // سوق المحاربين
                $this->_getOnlyMyTroops();
                $this->productionPane = TRUE;
                $this->buildingView = 'Warrior';
                $this->handleWarrior();

                $this->viewData['buildProperties'] = $this->buildProperties;
                $this->viewData['buildingIndex'] = $this->buildingIndex;
                $troopsUpgrade = $this->viewData['troopsUpgrade'] = $this->troopsUpgrade;
                $this->viewData['warriorMessage'] = $this->warriorMessage;
                $this->viewData['data'] = $this->data;
                $this->viewData['troops']['value'] = $this->troops;

                $tpl = new View;
                $tpl->assign($this->viewData);
                $this->viewData['buildingTemplateContent'] = $tpl->draw('buildings/Warrior', true);
        }
        $this->viewData['productionPane'] = $this->productionPane;
        $this->viewData['buildingView'] = $this->buildingView;
        $this->viewData['resource_gold_exchange'] = $this->getResourceGoldExchange($this->buildProperties['level']['resources'], $this->buildProperties['building']['item_id'], $this->buildingIndex);
        $this->viewData['get_action_text'] = $this->getActionText($this->buildProperties['level']['resources'], $this->buildProperties['building']['item_id'] <= 4, TRUE, $this->buildProperties['building']['item_id']);

        $this->viewData['buildProperties'] = $this->buildProperties;

        $this->viewData['data'] = $this->data;
        ########## Pre-rending ############
        if (is_get('p')) {
            $this->viewData['villagesLinkPostfix'] .= '&p=' . intval(get('p'));
        }
        if (is_get('vid2')) {
            $this->viewData['villagesLinkPostfix'] .= '&vid2=' . intval(get('vid2'));
        }
        if (0 < $this->selectedTabIndex) {
            $this->viewData['villagesLinkPostfix'] .= '&t=' . $this->selectedTabIndex;
        }

    }


    /**
     * handleBlacksmithArmoury Method
     *
     * @return void
     */
    public function handleBlacksmithArmoury()
    {
        $this->troopsUpgradeType = ($this->buildings[$this->buildingIndex]['item_id'] == 12 ? QS_TROOP_UPGRADE_ATTACK : QS_TROOP_UPGRADE_DEFENSE);
        $this->troopsUpgrade = array();
        $_arr = explode(',', $this->data['troops_training']);
        $_c = 0;
        foreach ($_arr as $troopStr) {
            list($troopId, $researches_done, $defense_level, $attack_level) = explode(' ', $troopStr);
            if ($troopId != 99) {
                ++$_c;
            }
            $tlevel = ($this->troopsUpgradeType == QS_TROOP_UPGRADE_ATTACK ? $attack_level : $defense_level);
            if (((($troopId != 99 && $_c <= 8) && $tlevel < 20) && $researches_done == 1)) {
                $this->troopsUpgrade[$troopId] = $tlevel;
            }
        }

        if (((((((is_get('a') && is_get('k')) && get('k') == $this->data['update_key']) && !isset($this->queueModel->tasksInQueue[$this->troopsUpgradeType])) && isset($this->troopsUpgrade[intval(get('a'))])) && !$this->isGameTransientStopped()) && !$this->isGameOver())) {
            $troopId = intval(get('a'));
            $level = $this->troopsUpgrade[$troopId];
            $buildingMetadata = $this->gameMetadata['items'][$this->buildProperties['building']['item_id']]['troop_upgrades'][$troopId][$level];
            if (is_get('max')) {
                $cost = 20 - $level;
                if ($cost <= $this->data['gold_num'] && $cost > 0) {
                    $this->load_model('Plus', 'P');
                    $this->P->DeletPlayerGold2($this->player->playerId, $cost);
                    $this->load_model('Queuejob', 'Q');
                    $task = array(
                        'village_id' => $this->data['selected_village_id'],
                        'player_id' => $this->player->playerId,
                        'proc_type' => $this->troopsUpgradeType,
                        'proc_params' => $troopId . ' ' . '20'
                    );
                    $this->Q->executeTroopUpgradeTask($task);
                    $this->is_redirect = TRUE;
                    redirect('build?id=' . get('id'));
                    return null;
                }
            }
            if (!$this->isResourcesAvailable($buildingMetadata['resources'])) {
                return null;
            }
            $calcConsume = intval($buildingMetadata['time_consume'] / $this->gameSpeed * (10 / ($this->buildProperties['building']['level'] + 9)));

            $this->load_library('QueueTask', 'newTask', array(
                'taskType' => $this->troopsUpgradeType,
                'playerId' => $this->player->playerId,
                'executionTime' => $calcConsume
            ));
            $this->newTask->villageId = $this->data['selected_village_id'];
            $this->newTask->procParams = $troopId . ' ' . ($level + 1);
            $this->newTask->tag = $buildingMetadata['resources'];
            $this->queueModel->addTask($this->newTask);
        }
    }


    /**
     * handleMainBuilding Method
     *
     * @return void
     */
    public function handleMainBuilding()
    {
        if ((((((((is_post('drbid') && 19 <= intval(post('drbid'))) && intval(post('drbid')) <= sizeof($this->buildings)) && isset($this->buildings[post('drbid')])) && 0 < $this->buildings[post('drbid')]['level']) && !isset($this->queueModel->tasksInQueue[QS_BUILD_DROP])) && !$this->isGameTransientStopped()) && !$this->isGameOver())) {
            $item_id = $this->buildings[post('drbid')]['item_id'];
            $calcConsume = intval($this->gameMetadata['items'][$item_id]['levels'][$this->buildings[post('drbid')]['level'] - 1]['time_consume'] / $this->gameSpeed * ($this->data['time_consume_percent'] / 400));
            if (is_post('full')) {
                $drblevls = $this->buildings[post('drbid')]['level'];
                $this->load_model('Plus', 'P');
                if ($drblevls <= $this->data['gold_num'] && $drblevls > 0) {
                    $this->P->DeletPlayerGold2($this->player->playerId, $drblevls);
                    $i = 0;
                    while ($i < $drblevls) {
                        $this->load_model('Queuejob', 'Q');
                        $this->Q->upgradeBuilding($this->data['selected_village_id'], $this->buildings[post('drbid')]['index'], $item_id, TRUE);
                        $i++;
                    }
                    $this->is_redirect = TRUE;
                    redirect('build?id=' . get('id'));
                    return null;
                }
            }

            $this->load_library('QueueTask', 'newTask', array(
                'taskType' => QS_BUILD_DROP,
                'playerId' => $this->player->playerId,
                'executionTime' => $calcConsume
            ));
            $this->newTask->villageId = $this->data['selected_village_id'];
            $this->newTask->buildingId = $item_id;
            $this->newTask->procParams = $this->buildings[post('drbid')]['index'];
            $this->queueModel->addTask($this->newTask);
            return null;
        }

        if ((((((((is_get('qid') && is_numeric(get('qid'))) && is_get('k')) && get('k') == $this->data['update_key']) && is_get('d')) && isset($this->queueModel->tasksInQueue[QS_BUILD_DROP])) && !$this->isGameTransientStopped()) && !$this->isGameOver())) {
            $this->queueModel->cancelTask($this->player->playerId, intval(get('qid')));
        }
    }

    public function handleArtefacts()
    {
        $this->selectedTabIndex = ((((is_get('t') && is_numeric(get('t'))) && 1 <= intval(get('t'))) && intval(get('t')) <= 4) ? intval(get('t')) : 0);
        $this->load_model('Artefacts', 'A');
        $this->Artefacts = 0;
        if ($this->selectedTabIndex == 0) {
            $this->Artefacts = $this->A->GetMyArtefacts($this->data['selected_village_id'], $this->player->playerId);
        } elseif ($this->selectedTabIndex <= 3) {
            $result = $this->A->GetArtefactsPyType($this->selectedTabIndex);
            if ($result) {
                $_c = 0;
                $this->Artefacts = array();
                foreach ($result as $value) {
                    ++$_c;
                    $villageData = $this->A->GetVillageDataPyId($value['in_village_id']);
                    $this->Artefacts[$_c] = array(
                        'id' => $value['id'],
                        'name' => constant("LANGUI_ART_name_" . $value['type']),
                        'player_name' => $villageData['player_name'],
                        'player_id' => $value['player_id'],
                        'village_name' => $villageData['village_name'],
                        'village_id' => $value['in_village_id'],
                        'dist' => $this->getDistance($this->data['rel_x'], $this->data['rel_y'], $villageData['rel_x'], $villageData['rel_y']),
                        'type' => $value['type']
                    );
                }
            }
            unset($result);
        } elseif ($this->selectedTabIndex == 4 AND is_get('show')) {
            $result = $this->A->GetArtefactsPyId(intval(get('show')));
            if ($result) {
                $villageData = $this->A->GetVillageDataPyId($result['in_village_id']);
                $this->Artefacts = array(
                    'id' => $result['id'],
                    'name' => constant("LANGUI_ART_name_" . $result['type']),
                    'player_name' => $villageData['player_name'],
                    'player_id' => $result['player_id'],
                    'village_name' => $villageData['village_name'],
                    'village_id' => $result['in_village_id'],
                    'type' => $result['type'],
                    'size' => $result['size'],
                    'date' => $result['mdate'],
                    'desc' => constant("LANGUI_ART_desc_" . $result['type']),
                    'alliance_id' => $villageData['alliance_id'],
                    'alliance_name' => $villageData['alliance_name']
                );
            }
        }
    }

    function getDistance($dx, $dy, $rx, $ry)
    {
        $speed = $this->gameMetadata['troops'][1]['velocity'];
        $factor = $this->gameMetadata['game_speed'];
        $speed = $speed * $factor;
        $distance = getdistance($dx, $dy, $rx, $ry, $this->setupMetadata['map_size'] / 2);
        $dis = intval($distance / $speed * 60);
        return round($dis, 1);
    }


    /**
     * handleRallyPoint Method
     *
     * @return void
     */
    public function handleRallyPoint()
    {
        if (is_get('d')) {
            $this->queueModel->cancelTask($this->player->playerId, intval(get('d')));
        }

        $this->rallyPointProperty = array(
            'troops_in_village' => array(
                'troopsTable' => $this->_getTroopsList('troops_num'),
                'troopsIntrapTable' => $this->_getTroopsList('troops_intrap_num')
            ),
            'troops_out_village' => array(
                'troopsTable' => $this->_getTroopsList('troops_out_num'),
                'troopsIntrapTable' => $this->_getTroopsList('troops_out_intrap_num')
            ),
            'troops_in_oases' => array(),
            'war_to_village' => $this->queueModel->tasksInQueue['war_troops']['to_village'],
            'war_from_village' => $this->queueModel->tasksInQueue['war_troops']['from_village'],
            'war_to_oasis' => $this->queueModel->tasksInQueue['war_troops']['to_oasis']
        );
        $village_oases_id = trim($this->data['village_oases_id']);
        if ($village_oases_id != '') {
            $this->load_model('Build', 'm');
            $result = $this->m->getOasesDataById($village_oases_id);
            foreach ($result as $value) {
                $this->rallyPointProperty['troops_in_oases'][$value['id']] = array(
                    'oasisRow' => $value,
                    'troopsTable' => $this->_getOasisTroopsList($value['troops_num']),
                    'war_to' => (isset($this->rallyPointProperty['war_to_oasis'][$value['id']]) ? $this->rallyPointProperty['war_to_oasis'][$value['id']] : NULL)
                );
            }
            unset($result);
        }
    }


    /**
     * _canCancelWarTask Method
     *
     * @param taskType int
     * @param taskId int
     *
     * @return bool
     */
    public function _canCancelWarTask($taskType, $taskId)
    {
        if (!QueueTask::iscancelabletask($taskType)) {
            return FALSE;
        }
        $timeout = QueueTask::getmaxcanceltimeout($taskType);
        if (0 - 1 < $timeout) {
            $_task = NULL;
            foreach ($this->queueModel->tasksInQueue[$taskType] as $t) {
                if ($t['id'] == $taskId) {
                    $_task = $t;
                    break;
                }
            }
            if ($_task == NULL) {
                return FALSE;
            }
            $elapsedTime = $t['elapsedTime'];
            if ($timeout < $elapsedTime) {
                return FALSE;
            }
        }
        return TRUE;
    }


    /**
     * _getOasisTroopsList Method
     *
     * @param troops_num int
     *
     * @return string
     */
    public function _getOasisTroopsList($troops_num)
    {
        $GameMetadata = $this->gameMetadata;

        $this->load_model('Build', 'm');
        $returnTroops = array();
        if (trim($troops_num) != '') {
            $t_arr = explode('|', $troops_num);
            foreach ($t_arr as $t_str) {
                $t2_arr = explode(':', $t_str);
                $vid = $t2_arr[0];
                $villageData = $this->m->getVillageData2ById($vid);
                $returnTroops[$vid] = array(
                    'villageData' => $villageData,
                    'cropConsumption' => 0,
                    'hasHero' => FALSE,
                    'troops' => array()
                );
                $t2_arr = explode(',', $t2_arr[1]);
                foreach ($t2_arr as $t2_str) {
                    list($tid, $tnum) = explode(' ', $t2_str);
                    if ($tid == 99) {
                        continue;
                    }
                    if ($tnum == 0 - 1) {
                        $tnum = 1;
                        $returnTroops[$vid]['hasHero'] = TRUE;
                    } else {
                        $returnTroops[$vid]['troops'][$tid] = $tnum;
                    }
                    $returnTroops[$vid]['cropConsumption'] += $GameMetadata['troops'][$tid]['crop_consumption'] * $tnum;
                }
            }
        }
        return $returnTroops;
    }


    /**
     * _getTroopsList Method
     *
     * @param key int
     *
     * @return string
     */
    public function _getTroopsList($key)
    {
        $GameMetadata = $this->gameMetadata;

        $this->load_model('Build', 'm');
        $returnTroops = array();
        if (trim($this->data[$key]) != '') {
            $t_arr = explode('|', $this->data[$key]);
            foreach ($t_arr as $t_str) {
                $t2_arr = explode(':', $t_str);
                $vid = intval($t2_arr[0]);
                $villageData = NULL;
                if ($vid == 0 - 1) {
                    $vid = $this->data['selected_village_id'];
                    $villageData = array(
                        'id' => $vid,
                        'village_name' => $this->data['village_name'],
                        'player_id' => $this->player->playerId,
                        'player_name' => buildings_p_thisvillage
                    );
                } else {
                    $villageData = $this->m->getVillageData2ById($vid);
                }
                $returnTroops[$vid] = array(
                    'villageData' => $villageData,
                    'cropConsumption' => 0,
                    'hasHero' => FALSE,
                    'troops' => array()
                );
                if ($vid == $this->data['selected_village_id']) {
                    $returnTroops[$vid]['hasHero'] = intval($this->data['hero_in_village_id']) == intval($this->data['selected_village_id']);
                    if ($returnTroops[$vid]['hasHero']) {
                        $returnTroops[$vid]['cropConsumption'] += $GameMetadata['troops'][$this->data['hero_troop_id']]['crop_consumption'];
                    }
                }
                $t2_arr = explode(',', $t2_arr[1]);
                foreach ($t2_arr as $t2_str) {
                    list($tid, $tnum) = explode(' ', $t2_str);
                    if ($tid == 99) {
                        continue;
                    }
                    if ($tnum == 0 - 1) {
                        $tnum = 1;
                        $returnTroops[$vid]['hasHero'] = TRUE;
                    } else {
                        $returnTroops[$vid]['troops'][$tid] = $tnum;
                    }
                    $returnTroops[$vid]['cropConsumption'] += $GameMetadata['troops'][$tid]['crop_consumption'] * $tnum;
                }
            }
        }

        return $returnTroops;
    }


    /**
     * handleMarketplace Method
     *
     * @return void
     */
    public function handleMarketplace()
    {
        $this->selectedTabIndex = ((((is_get('t') && is_numeric(get('t'))) && 1 <= intval(get('t'))) && intval(get('t')) <= 3) ? intval(get('t')) : 0);
        $itemId = $this->buildings[$this->buildingIndex]['item_id'];
        $itemLevel = $this->buildings[$this->buildingIndex]['level'];
        $tribeMetadata = $this->gameMetadata['tribes'][$this->data['tribe_id']];
        $tradeOfficeLevel = $this->_getMaxBuildingLevel(28);
        $capacityFactor = ($tradeOfficeLevel == 0 ? 1 : $this->gameMetadata['items'][28]['levels'][$tradeOfficeLevel - 1]['value'] / 100);
        $capacityFactor *= $this->gameMetadata['game_speed'];
        $total_merchants_num = $this->gameMetadata['items'][$itemId]['levels'][$itemLevel - 1]['value'];
        $exist_num = $total_merchants_num - $this->queueModel->tasksInQueue['out_merchants_num'] - $this->data['offer_merchants_count'];
        if ($exist_num < 0) {
            $exist_num = 0;
        }
        $this->merchantProperty = array(
            'speed' => $tribeMetadata['merchants_velocity'] * $this->gameMetadata['game_speed'],
            'capacity' => floor($tribeMetadata['merchants_capacity'] * $capacityFactor),
            'total_num' => $total_merchants_num,
            'exits_num' => $exist_num,
            'confirm_snd' => FALSE,
            'same_village' => FALSE,
            'vRow' => NULL
        );
        if ($this->selectedTabIndex == 0) {
            $this->load_model('Build', 'm');
            if (($_POST || is_get('vid2'))) {
                $resources = array(
                    '1' => (is_post('r1') ? intval(post('r1')) : 0),
                    '2' => (is_post('r2') ? intval(post('r2')) : 0),
                    '3' => (is_post('r3') ? intval(post('r3')) : 0),
                    '4' => (is_post('r4') ? intval(post('r4')) : 0)
                );
                $this->merchantProperty['confirm_snd'] = ($_POST ? (post('act') == 1) : is_get('vid2'));
                $map_size = $this->setupMetadata['map_size'];
                $doSend = FALSE;
                if ($this->merchantProperty['confirm_snd']) {
                    $vRow = NULL;
                    if ((((is_post('x') && is_post('y')) && post('x') != '') && post('y') != '')) {
                        $vid = $this->__getVillageId($map_size, $this->__getCoordInRange($map_size, intval(post('x'))), $this->__getCoordInRange($map_size, intval(post('y'))));
                        $vRow = $this->m->getVillageDataById($vid);
                    } else if ((is_post('vname') && post('vname') != '')) {
                        $vRow = $this->m->getVillageDataByName(post('vname'));
                    } else if (is_get('vid2')) {
                        $vRow = $this->m->getVillageDataById(intval(get('vid2')));
                        if ($vRow != NULL) {
                            $_POST['x'] = $vRow['rel_x'];
                            $_POST['y'] = $vRow['rel_y'];
                        }
                    }
                } else {
                    $doSend = TRUE;
                    $vRow = $this->m->getVillageDataById(intval(post('vid2')));
                    $this->merchantProperty['showError'] = FALSE;
                    $_POST['r1'] = $_POST['r2'] = $_POST['r3'] = $_POST['r4'] = '';
                }
                if ((0 < intval($vRow['player_id']) && $this->m->getPlayType(intval($vRow['player_id'])) == PLAYERTYPE_ADMIN)) {
                    $this->merchantProperty['showError'] = FALSE;
                    $this->merchantProperty['confirm_snd'] = FALSE;
                    return null;
                }
                $this->merchantProperty['vRow'] = $vRow;
                $vid = $this->merchantProperty['to_vid'] = ($vRow != NULL ? $vRow['id'] : 0);
                $rel_x = $vRow['rel_x'];
                $rel_y = $vRow['rel_y'];
                $this->merchantProperty['same_village'] = $vid == $this->data['selected_village_id'];
                $this->merchantProperty['available_res'] = $this->isResourcesAvailable($resources);
                $this->merchantProperty['isAgent'] = FALSE;
                if ($vRow['player_id'] != $this->player->playerId AND $this->player->isAgent) {
                    $this->merchantProperty['showError'] = TRUE;
                    $this->merchantProperty['confirm_snd'] = FALSE;
                    $this->merchantProperty['isAgent'] = TRUE;
                    return null;
                }
                $this->merchantProperty['ip'] = FALSE;
                if ($vRow['player_id'] != $this->player->playerId) {
                    $toPlayerIp = $this->m->getPlayerIp($vRow['player_id']);
                    $fromPlayerIp = $this->m->getPlayerIp($this->player->playerId);
                    $toPlayerIp = explode(',', $toPlayerIp);
                    $fromPlayerIp = explode(',', $fromPlayerIp);
                    foreach ($toPlayerIp as $value) {
                        if (in_array($value, $fromPlayerIp)) {
                            $this->merchantProperty['showError'] = TRUE;
                            $this->merchantProperty['confirm_snd'] = FALSE;
                            $this->merchantProperty['ip'] = TRUE;
                            return null;
                        }
                    }
                }

                $this->merchantProperty['vRow_merchant_num'] = ceil(($resources[1] + $resources[2] + $resources[3] + $resources[4]) / $this->merchantProperty['capacity']);
                $this->merchantProperty['confirm_snd'] = ((((0 < $vid && $this->merchantProperty['available_res']) && 0 < $this->merchantProperty['vRow_merchant_num']) && $this->merchantProperty['vRow_merchant_num'] <= $this->merchantProperty['exits_num']) && !$this->merchantProperty['same_village']);
                $this->merchantProperty['showError'] = !$this->merchantProperty['confirm_snd'];
                $distance = getdistance($this->data['rel_x'], $this->data['rel_y'], $rel_x, $rel_y, $this->setupMetadata['map_size'] / 2);
                $this->merchantProperty['vRow_time'] = intval($distance / $this->merchantProperty['speed'] * 3600);
                if ((((!$this->merchantProperty['showError'] && $doSend) && !$this->isGameTransientStopped()) && !$this->isGameOver())) {
                    $this->merchantProperty['confirm_snd'] = FALSE;
                    $this->merchantProperty['exits_num'] -= $this->merchantProperty['vRow_merchant_num'];

                    $this->load_library('QueueTask', 'newTask', array(
                        'taskType' => QS_MERCHANT_GO,
                        'playerId' => $this->player->playerId,
                        'executionTime' => $this->merchantProperty['vRow_time']
                    ));
                    $this->newTask->villageId = $this->data['selected_village_id'];
                    $this->newTask->toPlayerId = $vRow['player_id'];
                    $this->newTask->toVillageId = $vid;
                    $this->newTask->procParams = $this->merchantProperty['vRow_merchant_num'] . '|' . ($resources[1] . ' ' . $resources[2] . ' ' . $resources[3] . ' ' . $resources[4]);
                    $this->newTask->tag = $resources;
                    $this->newTask->buildingId = (intval(post('gonum')) > 4) ? 4 : intval(post('gonum'));
                    $this->queueModel->addTask($this->newTask);

                }
            }

            return null;
        }
        if ($this->selectedTabIndex == 1) {
            $this->load_model('Build', 'm');

            $showOfferList = TRUE;
            if ((is_get('oid') && 0 < intval(get('oid')))) {
                $oRow = $this->m->getOffer2(intval(get('oid')), $this->data['rel_x'], $this->data['rel_y'], $this->setupMetadata['map_size'] / 2);
                if ($oRow != NULL) {
                    $aid = 0;
                    if ($oRow['alliance_only']) {
                        if (0 < intval($this->data['alliance_id'])) {
                            $aid = $this->m->getPlayerAllianceId($oRow['player_id']);
                        }
                    }
                    list($res1, $res2) = explode('|', $oRow['offer']);
                    $resArr1 = explode(' ', $res1);
                    $needResources = array(
                        '1' => $resArr1[0],
                        '2' => $resArr1[1],
                        '3' => $resArr1[2],
                        '4' => $resArr1[3]
                    );
                    $res1_item_id = 0;
                    $res1_value = 0;
                    $i = 0;
                    $_c = sizeof($resArr1);
                    while ($i < $_c) {
                        if (0 < $resArr1[$i]) {
                            $res1_item_id = $i + 1;
                            $res1_value = $resArr1[$i];
                            break;
                        }
                        ++$i;
                    }
                    $resArr1 = explode(' ', $res2);
                    $giveResources = array(
                        '1' => $resArr1[0],
                        '2' => $resArr1[1],
                        '3' => $resArr1[2],
                        '4' => $resArr1[3]
                    );
                    $res2_item_id = 0;
                    $res2_value = 0;
                    $i = 0;
                    $_c = sizeof($resArr1);
                    while ($i < $_c) {
                        if (0 < $resArr1[$i]) {
                            $res2_item_id = $i + 1;
                            $res2_value = $resArr1[$i];
                            break;
                        }
                        ++$i;
                    }
                    $distance = $oRow['timeInSeconds'] / 3600 * $oRow['merchants_speed'];
                    $acceptResult = $this->_canAcceptOffer($needResources, $giveResources, $oRow['village_id'], $oRow['alliance_only'], $aid, $oRow['max_time'], $distance);
                    if ((($acceptResult == 5 && !$this->isGameTransientStopped()) && !$this->isGameOver())) {
                        $showOfferList = FALSE;
                        $this->merchantProperty['offerProperty'] = array(
                            'player_id' => $oRow['player_id'],
                            'player_name' => $oRow['player_name'],
                            'res1_item_id' => $res1_item_id,
                            'res1_value' => $res1_value,
                            'res2_item_id' => $res2_item_id,
                            'res2_value' => $res2_value
                        );
                        $merchantNum = ceil(($giveResources[1] + $giveResources[2] + $giveResources[3] + $giveResources[4]) / $this->merchantProperty['capacity']);

                        $this->load_library('QueueTask', 'newTask', array(
                            'taskType' => QS_MERCHANT_GO,
                            'playerId' => $this->player->playerId,
                            'executionTime' => $distance / ($this->gameMetadata['tribes'][$this->data['tribe_id']]['merchants_velocity'] * $this->gameMetadata['game_speed']) * 3600
                        ));
                        $this->newTask->villageId = $this->data['selected_village_id'];
                        $this->newTask->toPlayerId = $oRow['player_id'];
                        $this->newTask->toVillageId = $oRow['village_id'];
                        $this->newTask->procParams = $merchantNum . '|' . ($giveResources[1] . ' ' . $giveResources[2] . ' ' . $giveResources[3] . ' ' . $giveResources[4]);
                        $this->newTask->tag = $giveResources;
                        $this->queueModel->addTask($this->newTask);

                        $this->load_library('QueueTask', 'newTask1', array(
                            'taskType' => QS_MERCHANT_GO,
                            'playerId' => $oRow['player_id'],
                            'executionTime' => $oRow['timeInSeconds']
                        ));
                        $this->newTask1->villageId = $oRow['village_id'];
                        $this->newTask1->toPlayerId = $this->player->playerId;
                        $this->newTask1->toVillageId = $this->data['selected_village_id'];
                        $this->newTask1->procParams = $oRow['merchants_num'] . '|' . ($needResources[1] . ' ' . $needResources[2] . ' ' . $needResources[3] . ' ' . $needResources[4]);
                        $this->newTask1->tag = array(
                            '1' => 0,
                            '2' => 0,
                            '3' => 0,
                            '4' => 0
                        );
                        $this->queueModel->addTask($this->newTask1);
                        $this->m->removeMerchantOffer(intval($_GET['oid']), $oRow['player_id'], $oRow['village_id']);
                    }
                }
            }
            $this->merchantProperty['showOfferList'] = $showOfferList;
            if ($showOfferList) {
                $rowsCount = $this->m->getAllOffersCount($this->data['selected_village_id'], $this->data['rel_x'], $this->data['rel_y'], $this->setupMetadata['map_size'] / 2, $this->gameMetadata['tribes'][$this->data['tribe_id']]['merchants_velocity'] * $this->gameMetadata['game_speed']);
                $this->pageCount = (0 < $rowsCount ? ceil($rowsCount / $this->pageSize) : 1);
                $this->pageIndex = (((is_get('p') && is_numeric(get('p'))) && intval(get('p')) < $this->pageCount) ? intval(get('p')) : 0);
                $this->merchantProperty['all_offers'] = $this->m->getAllOffers($this->data['selected_village_id'], $this->data['rel_x'], $this->data['rel_y'], $this->setupMetadata['map_size'] / 2, $this->gameMetadata['tribes'][$this->data['tribe_id']]['merchants_velocity'] * $this->gameMetadata['game_speed'], $this->pageIndex, $this->pageSize);
            }

            return null;
        }
        if ($this->selectedTabIndex == 2) {
            $this->load_model('Build', 'm');

            $this->merchantProperty['showError'] = FALSE;
            $this->merchantProperty['showError2'] = FALSE;
            $this->merchantProperty['showError3'] = FALSE;

            if (is_post('m1')) {
                if ((((((((is_post('m1') && 0 < intval(post('m1'))) && is_post('m2')) && 0 < intval(post('m2'))) && is_post('rid1')) && 0 < intval(post('rid1'))) && is_post('rid2')) && 0 < intval(post('rid2')))) {
                    $resources1 = array(
                        '1' => ((is_post('rid1') && intval(post('rid1')) == 1) ? intval(post('m1')) : 0),
                        '2' => ((is_post('rid1') && intval(post('rid1')) == 2) ? intval(post('m1')) : 0),
                        '3' => ((is_post('rid1') && intval(post('rid1')) == 3) ? intval(post('m1')) : 0),
                        '4' => ((is_post('rid1') && intval(post('rid1')) == 4) ? intval(post('m1')) : 0)
                    );
                    $resources2 = array(
                        '1' => ((is_post('rid2') && intval(post('rid2')) == 1) ? intval(post('m2')) : 0),
                        '2' => ((is_post('rid2') && intval(post('rid2')) == 2) ? intval(post('m2')) : 0),
                        '3' => ((is_post('rid2') && intval(post('rid2')) == 3) ? intval(post('m2')) : 0),
                        '4' => ((is_post('rid2') && intval(post('rid2')) == 4) ? intval(post('m2')) : 0)
                    );
                    if (((intval(post('rid1')) == intval(post('rid2')) || intval($resources1[1] + $resources1[2] + $resources1[3] + $resources1[4]) <= 0) || intval($resources2[1] + $resources2[2] + $resources2[3] + $resources2[4]) <= 0)) {
                        $this->merchantProperty['showError'] = TRUE;
                    } else {
                        if (10 < ceil(($resources2[1] + $resources2[2] + $resources2[3] + $resources2[4]) / ($resources1[1] + $resources1[2] + $resources1[3] + $resources1[4]))) {
                            $this->merchantProperty['showError'] = TRUE;
                            $this->merchantProperty['showError3'] = TRUE;
                        }
                    }
                    $this->merchantProperty['available_res'] = $this->isResourcesAvailable($resources1);
                    if (($this->merchantProperty['available_res'] && !$this->merchantProperty['showError'])) {
                        $this->merchantProperty['vRow_merchant_num'] = ceil(($resources1[1] + $resources1[2] + $resources1[3] + $resources1[4]) / $this->merchantProperty['capacity']);
                        if ((0 < $this->merchantProperty['vRow_merchant_num'] && $this->merchantProperty['vRow_merchant_num'] <= $this->merchantProperty['exits_num'])) {
                            $this->merchantProperty['exits_num'] -= $this->merchantProperty['vRow_merchant_num'];
                            $this->data['offer_merchants_count'] += $this->merchantProperty['vRow_merchant_num'];
                            $offer = $resources1[1] . ' ' . $resources1[2] . ' ' . $resources1[3] . ' ' . $resources1[4] . '|' . ($resources2[1] . ' ' . $resources2[2] . ' ' . $resources2[3] . ' ' . $resources2[4]);
                            $this->m->addMerchantOffer($this->player->playerId, $this->data['name'], $this->data['selected_village_id'], $this->data['rel_x'], $this->data['rel_y'], $this->merchantProperty['vRow_merchant_num'], $offer, is_post('ally'), (((is_post('d1') && is_post('d2')) && 0 < intval(post('d2'))) ? intval(post('d2')) : 0), $this->gameMetadata['tribes'][$this->data['tribe_id']]['merchants_velocity'] * $this->gameMetadata['game_speed']);
                            foreach ($resources1 as $k => $v) {
                                $this->resources[$k]['current_value'] -= $v;
                            }
                            $this->queueModel->_updateVillage(FALSE, FALSE);
                        } else {
                            $this->merchantProperty['showError'] = TRUE;
                        }
                    } else {
                        $this->merchantProperty['showError'] = TRUE;
                    }
                } else {
                    $this->merchantProperty['showError'] = TRUE;
                    $this->merchantProperty['showError2'] = TRUE;
                }
            } else {
                if ((is_get('d') && 0 < intval(get('d')))) {
                    $row = $this->m->getOffer(intval(get('d')), $this->player->playerId, $this->data['selected_village_id']);
                    if ($row != NULL) {
                        $this->merchantProperty['exits_num'] += $row['merchants_num'];
                        $this->data['offer_merchants_count'] -= $row['merchants_num'];
                        list($resources1, $resources2) = explode('|', $row['offer']);
                        $resourcesArray1 = explode(' ', $resources1);
                        $res = array();
                        $i = 0;
                        $_c = sizeof($resourcesArray1);
                        while ($i < $_c) {
                            $res[$i + 1] = $resourcesArray1[$i];
                            ++$i;
                        }
                        foreach ($res as $k => $v) {
                            $this->resources[$k]['current_value'] += $v;
                        }
                        $this->queueModel->_updateVillage(FALSE, FALSE);
                        $this->m->removeMerchantOffer(intval(get('d')), $this->player->playerId, $this->data['selected_village_id']);
                    }
                }
            }
            $this->merchantProperty['offers'] = $this->m->getOffers($this->data['selected_village_id']);

            return null;
        }
        if ($this->selectedTabIndex == 3) {
            if ((((is_post('m2') && isset($_POST['m2'])) && sizeof($_POST['m2']) == 4) && $this->gameMetadata['plusTable'][6]['cost'] <= $this->data['gold_num'])) {
                $resources = array(
                    '1' => intval($_POST['m2'][0]),
                    '2' => intval($_POST['m2'][1]),
                    '3' => intval($_POST['m2'][2]),
                    '4' => intval($_POST['m2'][3])
                );
                $oldSum = $this->resources[1]['current_value'] + $this->resources[2]['current_value'] + $this->resources[3]['current_value'] + $this->resources[4]['current_value'];
                $newSum = $resources[1] + $resources[2] + $resources[3] + $resources[4];
                if ($newSum <= $oldSum) {
                    foreach ($resources as $k => $v) {
                        $this->resources[$k]['current_value'] = $v;
                    }
                    $this->queueModel->_updateVillage(FALSE, FALSE);

                    $this->load_model('Build', 'm');
                    $this->m->decreaseGoldNum($this->player->playerId, $this->gameMetadata['plusTable'][6]['cost']);

                }
            }
        }
    }


    /**
     * handleEmbassy Method
     *
     * @return void
     */
    public function handleEmbassy()
    {
        if (0 < intval($this->data['alliance_id'])) {
            return null;
        }
        $this->embassyProperty = array(
            'level' => $this->buildings[$this->buildingIndex]['level'],
            'invites' => NULL,
            'error' => 0,
            'ally1' => '',
            'ally2' => ''
        );
        $maxPlayers = $this->gameMetadata['items'][18]['levels'][$this->embassyProperty['level'] - 1]['value'];
        if (((is_post('ally1') && 3 <= $this->embassyProperty['level']) && is_post('ally2'))) {
            $this->embassyProperty['ally1'] = $ally1 = post('ally1');
            $this->embassyProperty['ally2'] = $ally2 = post('ally2');
            if (($ally1 == '' || $ally2 == '' || strlen($ally2) > 25 || strlen($ally1) > 10)) {
                $this->embassyProperty['error'] = (($ally1 == '' && $ally2 == '') ? 3 : ($ally1 == '' ? 1 : 2));
            } else {
                $this->load_model('Build', 'm');
                if (!$this->m->allianceExists($this->embassyProperty['ally1'])) {
                    $this->data['alliance_name'] = $this->embassyProperty['ally1'];
                    $this->data['alliance_id'] = $this->m->createAlliance($this->player->playerId, $this->embassyProperty['ally1'], $this->embassyProperty['ally2'], $maxPlayers);

                    return null;
                }
                $this->embassyProperty['error'] = 4;

            }
        }
        $invites_alliance_ids = trim($this->data['invites_alliance_ids']);
        $this->embassyProperty['invites'] = array();
        if ($invites_alliance_ids != '') {
            $_arr = explode("\n", $invites_alliance_ids);
            foreach ($_arr as $_s) {
                list($allianceId, $allianceName) = explode(' ', $_s, 2);
                $this->embassyProperty['invites'][$allianceId] = $allianceName;
            }
        }
        $this->viewData['embassyProperty'] = $this->embassyProperty;


        if (!is_post('ally1')) {
            if ((is_get('a') && 0 < intval(get('a')))) {
                $allianceId = intval(get('a'));
                if (isset($this->embassyProperty['invites'][$allianceId])) {
                    $this->load_model('Build', 'm');
                    $acceptResult = $this->m->acceptAllianceJoining($this->player->playerId, $allianceId);
                    if ($acceptResult == 2) {
                        $this->data['alliance_name'] = $this->embassyProperty['invites'][$allianceId];
                        $this->data['alliance_id'] = $allianceId;
                        unset($this->embassyProperty['invites'][$allianceId]);
                        $this->m->removeAllianceInvites($this->player->playerId, $allianceId);
                    } else {
                        if ($acceptResult == 1) {
                            $this->embassyProperty['error'] = 15;
                        }
                    }

                    return null;
                }
            } else {
                if ((is_get('d') && 0 < intval(get('d')))) {
                    $allianceId = intval(get('d'));
                    if (isset($this->embassyProperty['invites'][$allianceId])) {
                        unset($this->embassyProperty['invites'][$allianceId]);
                        $this->load_model('Build', 'm');
                        $this->m->removeAllianceInvites($this->player->playerId, $allianceId);

                    }
                }
            }
        }
    }


    /**
     * handleWarrior Method
     *
     * @return void
     */
    public function handleWarrior()
    {
        $itemId = $this->buildings[$this->buildingIndex]['item_id'];
        $this->troopsUpgrade = array();
        $_arr = explode(',', $this->data['troops_training']);
        foreach ($_arr as $troopStr) {
            list($troopId, $researches_done, $defense_level, $attack_level) = explode(' ', $troopStr);
            if (($researches_done == 1 && 0 < $this->gameMetadata['troops'][$troopId]['gold_needed'])) {
                $this->troopsUpgrade[$troopId] = $troopId;
            }
        }
        $this->warriorMessage = '';
        if (((is_post('tf') && !$this->isGameTransientStopped()) && !$this->isGameOver())) {
            $cropConsume = 0;
            $totalGoldsNeeded = 0;
            $trop = array();
            foreach ($_POST['tf'] as $troopId => $num) {
                $num = intval($num);
                if (($num <= 0 || !isset($this->troopsUpgrade[$troopId]))) {
                    continue;
                }
                $troopMetadata = $this->gameMetadata['troops'][$troopId];
                $needres = $troopMetadata['training_resources'][1] + $troopMetadata['training_resources'][2] + $troopMetadata['training_resources'][3] + $troopMetadata['training_resources'][4];
                $totalGoldsNeeded += $num;
                $trop[$troopId] = floor(($troopMetadata['gold_needed'] / ($needres / 100)) * $num);
                $cropConsume += $troopMetadata['crop_consumption'] * $trop[$troopId];
                $totalGoldsNeeded = ceil($totalGoldsNeeded);
            }
            if ($totalGoldsNeeded <= 0) {
                return null;
            }
            $canProcess = ($totalGoldsNeeded <= $this->data['gold_num'] and $totalGoldsNeeded <= $this->data['gold_buy']);
            $this->warriorMessage = ($canProcess ? 1 : 2);
            if ($canProcess) {
                $troopsString = '';
                foreach ($this->troops as $tid => $num) {
                    if ($tid == 99) {
                        continue;
                    }
                    $neededNum = ((isset($this->troopsUpgrade[$tid]) && isset($trop[$tid])) ? intval($trop[$tid]) : 0);
                    if ($troopsString != '') {
                        $troopsString .= ',';
                    }
                    $troopsString .= $tid . ' ' . $neededNum;
                }

                $this->load_model('Plus', 'm');
                $this->m->DeletPlayerGold($this->player->playerId, $totalGoldsNeeded);


                $this->data['gold_num'] -= $totalGoldsNeeded;
                $procParams = $troopsString . '|0||||||1';
                $buildingMetadata = $this->gameMetadata['items'][$this->buildProperties['building']['item_id']];
                $bLevel = $this->buildings[$this->buildingIndex]['level'];
                $needed_time = $buildingMetadata['levels'][$bLevel - 1]['value'] * 3600;

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
                    'troops' => NULL,
                    'hasHero' => FALSE,
                    'resources' => NULL,
                    'troopsCropConsume' => $cropConsume
                );
                $this->queueModel->addTask($this->newTask);
            }
        }
    }


    /**
     * handleTroopBuilding Method
     *
     * @return void
     */
    public function handleTroopBuilding()
    {
        $this->load_model('Artefacts', 'A');
        $artLevel = $this->A->Artefacts($this->player->playerId, $this->data['selected_village_id'], 6);
        $this->artPower = ($artLevel == 0) ? 1 : (($artLevel == 1) ? 0.5 : (($artLevel == 2) ? 0.75 : 0.5));
        $artLevel2 = $this->A->Artefacts($this->player->playerId, $this->data['selected_village_id'], 8);
        $this->artPower2 = ($artLevel2 == 0) ? 1 : (($artLevel2 == 1) ? 0.5 : (($artLevel2 == 2) ? 0.75 : 0.5));
        $itemId = $this->buildings[$this->buildingIndex]['item_id'];
        $this->troopsUpgradeType = QS_TROOP_TRAINING;
        $this->troopsUpgrade = array();
        $_arr = explode(',', $this->data['troops_training']);
        foreach ($_arr as $troopStr) {
            list($troopId, $researches_done, $defense_level, $attack_level) = explode(' ', $troopStr);
            if (($researches_done == 1 && $this->_canTrainInBuilding($troopId, $itemId))) {
                $this->troopsUpgrade[$troopId] = $troopId;
                continue;
            }
        }

        if (((is_post('tf') && !$this->isGameTransientStopped()) && !$this->isGameOver())) {
            foreach ($_POST['tf'] as $troopId => $num) {
                $num = intval($num);
                if ((($num <= 0 || !isset($this->troopsUpgrade[$troopId])) || $this->_getMaxTrainNumber($troopId, $itemId) < $num)) {
                    continue;
                }
                $timeFactor = 1;
                if ($this->gameMetadata['troops'][$troopId]['is_cavalry'] == TRUE) {
                    $flvl = $this->_getMaxBuildingLevel(41);
                    if (0 < $flvl) {
                        $timeFactor -= $this->gameMetadata['items'][41]['levels'][$flvl - 1]['value'] / 100;
                    }
                }
                $troopMetadata = $this->gameMetadata['troops'][$troopId];
                $calcConsume = intval($troopMetadata['training_time_consume'] / $this->gameSpeed * (10 / ($this->buildProperties['building']['level'] + 9)) * $timeFactor * $this->artPower);

                $this->load_library('QueueTask', 'newTask1', array(
                    'taskType' => $this->troopsUpgradeType,
                    'playerId' => $this->player->playerId,
                    'executionTime' => $calcConsume
                ));
                $this->newTask1->threads = $num;
                $this->newTask1->villageId = $this->data['selected_village_id'];
                $this->newTask1->buildingId = $this->buildProperties['building']['item_id'];
                $this->newTask1->procParams = $troopId;
                $this->newTask1->tag = array(
                    '1' => $troopMetadata['training_resources'][1] * $this->buildingTribeFactor * $num * $this->artPower2,
                    '2' => $troopMetadata['training_resources'][2] * $this->buildingTribeFactor * $num * $this->artPower2,
                    '3' => $troopMetadata['training_resources'][3] * $this->buildingTribeFactor * $num * $this->artPower2,
                    '4' => $troopMetadata['training_resources'][4] * $this->buildingTribeFactor * $num * $this->artPower2
                );
                $this->queueModel->addTask($this->newTask1);
            }
        }
    }


    /**
     * handleAcademy Method
     *
     * @return void
     */
    public function handleAcademy()
    {
        $this->troopsUpgradeType = QS_TROOP_RESEARCH;
        $this->troopsUpgrade = array(
            'available' => array(),
            'soon' => array()
        );
        $_arr = explode(',', $this->data['troops_training']);
        foreach ($_arr as $troopStr) {
            list($troopId, $researches_done, $defense_level, $attack_level) = explode(' ', $troopStr);
            if ($researches_done == 0) {
                $this->troopsUpgrade[($this->_canDoResearches($troopId) ? 'available' : 'soon')][] = $troopId;
                continue;
            }
        }

        if (((((((is_get('a') && is_get('k')) && get('k') == $this->data['update_key']) && !isset($this->queueModel->tasksInQueue[$this->troopsUpgradeType])) && $this->_canDoResearches(intval(get('a')))) && !$this->isGameTransientStopped()) && !$this->isGameOver())) {
            $troopId = intval(get('a'));
            $buildingMetadata = $this->gameMetadata['troops'][$troopId];
            if (!$this->isResourcesAvailable($buildingMetadata['research_resources'])) {
                return null;
            }
            $calcConsume = intval($buildingMetadata['research_time_consume'] / $this->gameSpeed);

            $this->load_library('QueueTask', 'newTask', array(
                'taskType' => $this->troopsUpgradeType,
                'playerId' => $this->player->playerId,
                'executionTime' => $calcConsume
            ));
            $this->newTask->villageId = $this->data['selected_village_id'];
            $this->newTask->procParams = $troopId;
            $this->newTask->tag = $buildingMetadata['research_resources'];
            $this->queueModel->addTask($this->newTask);
        }
    }


    /**
     * handleTownHall Method
     *
     * @return void
     */
    public function handleTownHall()
    {
        $buildingMetadata = $this->gameMetadata['items'][$this->buildProperties['building']['item_id']];
        $bLevel = $this->buildings[$this->buildingIndex]['level'];

        if ((((((get('a') && get('k')) && get('k') == $this->data['update_key']) && !isset($this->queueModel->tasksInQueue[QS_TOWNHALL_CELEBRATION])) && !$this->isGameTransientStopped()) && !$this->isGameOver())) {
            if ((((intval(get('a')) < 1 || 2 < intval(get('a'))) || (intval(get('a')) == 1 && $bLevel < $buildingMetadata['celebrations']['small']['level'])) || (intval(get('a')) == 2 && $bLevel < $buildingMetadata['celebrations']['large']['level']))) {
                return null;
            }
            $key = (intval(get('a')) == 2 ? 'large' : 'small');
            if (!$this->isResourcesAvailable($buildingMetadata['celebrations'][$key]['resources'])) {
                return null;
            }
            $calcConsume = intval($buildingMetadata['celebrations'][$key]['time_consume'] / $this->gameSpeed * (10 / ($bLevel + 9)));

            $this->load_library('QueueTask', 'newTask', array(
                'taskType' => QS_TOWNHALL_CELEBRATION,
                'playerId' => $this->player->playerId,
                'executionTime' => $calcConsume
            ));
            $this->newTask->villageId = $this->data['selected_village_id'];
            $this->newTask->procParams = intval(get('a'));
            $this->newTask->tag = $buildingMetadata['celebrations'][$key]['resources'];
            $this->queueModel->addTask($this->newTask);
        }
    }


    /**
     * handleResidencePalace Method
     *
     * @return void
     */
    public function handleResidencePalace()
    {
        $this->selectedTabIndex = ((((is_get('t') && is_numeric(get('t'))) && 1 <= intval(get('t'))) && intval(get('t')) <= 3) ? intval(get('t')) : 0);
        $_bid_ = $this->buildings[$this->buildingIndex]['item_id'];
        if ($this->selectedTabIndex == 0) {
            if ((((is_get('mc') && !$this->data['is_capital']) && !$this->data['is_special_village']) && $_bid_ == 26)) {
                $this->data['is_capital'] = TRUE;

                $this->load_model('Build', 'm');
                $this->m->makeVillageAsCapital($this->player->playerId, $this->data['selected_village_id']);

            }
            $this->childVillagesCount = 0;
            if (trim($this->data['child_villages_id']) != '') {
                $this->childVillagesCount = sizeof(explode(',', $this->data['child_villages_id']));
            }

            $this->load_model('Build', 'm');
            $VillagesCountc = $this->m->getPlayerVillagesDataPyId($this->player->playerId);
            foreach ($VillagesCountc as $value) {
                $villagcon = sizeof(explode(',', $value['child_villages_id']));
                if ($villagcon == 3 && $value['id'] != $this->data['selected_village_id'] && $this->data['is_capital']) {
                    $this->data['is_capital'] = 0;
                }
            }
            unset($VillagesCountc);
            $itemId = $this->buildings[$this->buildingIndex]['item_id'];
            $buildingLevel = $this->buildings[$this->buildingIndex]['level'];
            $this->troopsUpgradeType = QS_TROOP_TRAINING;
            $this->_getOnlyMyTroops();
            $this->troopsUpgrade = array();
            $_arr = explode(',', $this->data['troops_training']);
            foreach ($_arr as $troopStr) {
                list($troopId, $researches_done, $defense_level, $attack_level) = explode(' ', $troopStr);
                if (($researches_done == 1 && $this->_canTrainInBuilding($troopId, $itemId))) {
                    $this->troopsUpgrade[] = array(
                        'troopId' => $troopId,
                        'maxvalue' => $this->CurrentNumberResidencePalace($troopId, $itemId),
                        'maxNumber' => $this->_getMaxTrainNumber($troopId, $itemId),
                        'currentNumber' => $this->_getCurrentNumberFor($troopId, $itemId)
                    );
                    continue;
                }
            }
            $this->showBuildingForm = FALSE;
            if (1 == sizeof($this->troopsUpgrade)) {
                if ($this->troopsUpgrade[0]['maxvalue'] >= 1) {
                    $this->showBuildingForm = TRUE;
                }
            } elseif (2 == sizeof($this->troopsUpgrade)) {
                if ($this->troopsUpgrade[1]['maxvalue'] >= 1) {
                    $this->showBuildingForm = TRUE;
                }
            }
            if (((is_post('tf') && !$this->isGameTransientStopped()) && !$this->isGameOver())) {
                foreach ($_POST['tf'] as $troopId => $num) {
                    $num = intval($num);
                    $existsTroop = FALSE;
                    foreach ($this->troopsUpgrade as $troop) {
                        if ($troop['troopId'] == $troopId) {
                            $existsTroop = TRUE;
                            break;
                        }
                    }
                    if ((($num <= 0 || !$existsTroop) || $this->_getMaxTrainNumber($troopId, $itemId) < $num)) {
                        continue;
                    }
                    $troopMetadata = $this->gameMetadata['troops'][$troopId];
                    $calcConsume = intval($troopMetadata['training_time_consume'] / $this->gameSpeed * (10 / ($this->buildProperties['building']['level'] + 9)));

                    $this->load_library('QueueTask', 'newTask', array(
                        'taskType' => $this->troopsUpgradeType,
                        'playerId' => $this->player->playerId,
                        'executionTime' => $calcConsume
                    ));
                    $this->newTask->threads = $num;
                    $this->newTask->villageId = $this->data['selected_village_id'];
                    $this->newTask->buildingId = $this->buildProperties['building']['item_id'];
                    $this->newTask->procParams = $troopId;
                    $this->newTask->tag = array(
                        '1' => $troopMetadata['training_resources'][1] * $this->buildingTribeFactor * $num,
                        '2' => $troopMetadata['training_resources'][2] * $this->buildingTribeFactor * $num,
                        '3' => $troopMetadata['training_resources'][3] * $this->buildingTribeFactor * $num,
                        '4' => $troopMetadata['training_resources'][4] * $this->buildingTribeFactor * $num
                    );
                    $this->queueModel->addTask($this->newTask);
                }
                return null;
            }
        } else {
            if ($this->selectedTabIndex == 1) {
                $this->neededCpValue = $this->totalCpRate = $this->totalCpValue = 0;

                $this->load_model('Build', 'm');
                $result = $this->m->getVillagesCp($this->data['villages_id']);
                foreach ($result as $value) {
                    list($this->cpValue, $cpRate) = explode(" ", $value['cp']);
                    $this->cpValue += $value['elapsedTimeInSeconds'] * ($cpRate / 86400);
                    $this->totalCpRate += $cpRate;
                    $this->totalCpValue += $this->cpValue;
                    $this->neededCpValue += intval($this->gameMetadata['cp_for_new_village'] / $this->gameSpeed);
                }
                $this->totalCpValue = floor($this->totalCpValue);
                unset($result);

                return null;
            }
            if ($this->selectedTabIndex == 3) {
                $this->childVillages = array();
                $this->load_model('Build', 'm');
                $result = $this->m->getChildVillagesFor(trim($this->data['child_villages_id']));

                if ($result) {
                    foreach ($result as $value) {
                        $this->childVillages[$value['id']] = array(
                            'id' => $value['id'],
                            'rel_x' => $value['rel_x'],
                            'rel_y' => $value['rel_y'],
                            'village_name' => $value['village_name'],
                            'people_count' => $value['people_count'],
                            'creation_date' => $value['creation_date']
                        );
                    }
                }
                unset($result);

            }
        }
    }


    /**
     * handleHerosMansion Method
     *
     * @return void
     */
    public function handleHerosMansion()
    {
        $this->selectedTabIndex = (((is_get('t') && is_numeric(get('t'))) && intval(get('t')) == 1) ? intval(get('t')) : 0);
        if ($this->selectedTabIndex == 0) {
            $this->hasHero = 0 < intval($this->data['hero_troop_id']);
            $this->troopsUpgradeType = QS_TROOP_TRAINING_HERO;
            if (!$this->hasHero) {
                $this->_getOnlyMyTroops(TRUE);
                if ((((((((get('a') && get('k')) && get('k') == $this->data['update_key']) && !isset($this->queueModel->tasksInQueue[$this->troopsUpgradeType])) && isset($this->troops[intval(get('a'))])) && 0 < $this->troops[intval(get('a'))]) && !$this->isGameTransientStopped()) && !$this->isGameOver())) {
                    $troopId = intval(get('a'));
                    $troopMetadata = $this->gameMetadata['troops'][$troopId];
                    $nResources = array(
                        '1' => $troopMetadata['training_resources'][1] * 2,
                        '2' => $troopMetadata['training_resources'][2] * 2,
                        '3' => $troopMetadata['training_resources'][3] * 2,
                        '4' => $troopMetadata['training_resources'][4] * 2
                    );
                    if (!$this->isResourcesAvailable($nResources)) {
                        return null;
                    }
                    $calcConsume = intval($troopMetadata['training_time_consume'] / $this->gameSpeed * (10 / ($this->buildProperties['building']['level'] + 9))) * 12;

                    $this->load_library('QueueTask', 'newTask', array(
                        'taskType' => $this->troopsUpgradeType,
                        'playerId' => $this->player->playerId,
                        'executionTime' => $calcConsume
                    ));
                    $this->newTask->procParams = $troopId . ' ' . $this->data['selected_village_id'];
                    $this->newTask->tag = $nResources;
                    $this->queueModel->addTask($this->newTask);
                    return null;
                }
            } else {
                if ((is_post('hname') && trim($_POST['hname']) != '')) {
                    $_POST['hname'] = post('hname');
                    if (trim($_POST['hname']) != '' && strlen($_POST['hname']) < 15) {
                        $this->data['hero_name'] = trim($_POST['hname']);

                        $this->load_model('Build', 'm');
                        $this->m->changeHeroName($this->player->playerId, $this->data['hero_name']);

                        return null;
                    }
                }
            }
        } else {
            if ($this->selectedTabIndex == 1) {
                $this->villageOases = array();

                $this->load_model('Build', 'm');
                $result = $this->m->getVillageOases(trim($this->data['village_oases_id']));

                if ($result) {
                    foreach ($result as $value) {
                        $this->villageOases[$value['id']] = array(
                            'id' => $value['id'],
                            'rel_x' => $value['rel_x'],
                            'rel_y' => $value['rel_y'],
                            'image_num' => $value['image_num'],
                            'allegiance_percent' => $value['allegiance_percent'],
                            'troops' => $value['troops_num']
                        );
                    }
                }
                unset($result);

                if ((((((((is_get('a') && is_get('k')) && get('k') == $this->data['update_key']) && isset($this->villageOases[intval(get('a'))])) && $this->villageOases[intval(get('a'))]['troops'] == null) && !isset($this->queueModel->tasksInQueue[QS_LEAVEOASIS][intval(get('a'))])) && !$this->isGameTransientStopped()) && !$this->isGameOver())) {
                    $oasisId = intval(get('a'));
                    $this->load_model('Queuejob', 'q');
                    $this->q->captureOasis($oasisId, $this->player->playerId, $this->data['selected_village_id'], FALSE);
                    unset($this->villageOases[intval(get('a'))]);
                    /*$this->load_library('QueueTask', 'newTask', array(
                        'taskType' => QS_LEAVEOASIS,
                        'playerId' => $this->player->playerId,
                        'executionTime' => 0
                    ));
                    $this->newTask->villageId  = $this->data['selected_village_id'];
                    $this->newTask->buildingId = $oasisId;
                    $this->newTask->procParams = $this->villageOases[$oasisId]['rel_x'] . ' ' . $this->villageOases[$oasisId]['rel_y'];
                    $this->queueModel->addTask($this->newTask);*/
                    return null;
                }
                if ((is_get('qid') && 0 < intval(get('qid')))) {
                    $this->queueModel->cancelTask($this->player->playerId, intval(get('qid')));
                }
            }
        }
    }


    /**
     * __getCoordInRange Method
     *
     * @param map_size int
     * @param x int
     * @return void
     */
    public function __getCoordInRange($map_size, $x)
    {
        if ($map_size <= $x) {
            $x -= $map_size;
        } else {
            if ($x < 0) {
                $x = $map_size + $x;
            }
        }
        return $x;
    }


    /**
     * __getVillageId Method
     *
     * @param map_size int
     * @param x int
     * @return void
     */
    public function __getVillageId($map_size, $x, $y)
    {

        return $x * $map_size + ($y + 1);
    }


    /**
     * _getOnlyMyOuterTroops Method
     *
     * @param troopId int
     * @return void
     */
    public function _getOnlyMyOuterTroops($troopId)
    {
        $returnTroops = 0;
        if (trim($this->data['troops_out_num']) != '') {
            $t_arr = explode('|', $this->data['troops_out_num']);
            foreach ($t_arr as $t_str) {
                $t2_arr = explode(':', $t_str);
                $t2_arr = explode(',', $t2_arr[1]);
                foreach ($t2_arr as $t2_str) {
                    $t = explode(' ', $t2_str);
                    if ($t[1] == 0 - 1) {
                        continue;
                    }
                    if ($t[0] == $troopId) {
                        $returnTroops += $t[1];
                        continue;
                    }
                }
            }
        }
        if (trim($this->data['troops_out_intrap_num']) != '') {
            $t_arr = explode('|', $this->data['troops_out_intrap_num']);
            foreach ($t_arr as $t_str) {
                $t2_arr = explode(':', $t_str);
                $t2_arr = explode(',', $t2_arr[1]);
                foreach ($t2_arr as $t2_str) {
                    $t = explode(' ', $t2_str);
                    if ($t[1] == 0 - 1) {
                        continue;
                    }
                    if ($t[0] == $troopId) {
                        $returnTroops += $t[1];
                        continue;
                    }
                }
            }
        }
        return $returnTroops;
    }


    /**
     * _getOnlyMyTroops Method
     *
     * @param toBeHero bool (true)
     * @return void
     */
    public function _getOnlyMyTroops($toBeHero = FALSE)
    {
        $t_arr = explode('|', $this->data['troops_num']);
        foreach ($t_arr as $t_str) {
            $t2_arr = explode(':', $t_str);
            if ($t2_arr[0] == 0 - 1) {
                $t2_arr = explode(',', $t2_arr[1]);
                foreach ($t2_arr as $t2_str) {
                    $t = explode(' ', $t2_str);
                    if (($toBeHero && (((((((((((((((((((($t[0] == 99 || $t[0] == 7) || $t[0] == 8) || $t[0] == 9) || $t[0] == 10) || $t[0] == 17) || $t[0] == 18) || $t[0] == 19) || $t[0] == 20) || $t[0] == 27) || $t[0] == 28) || $t[0] == 29) || $t[0] == 30) || $t[0] == 106) || $t[0] == 107) || $t[0] == 108) || $t[0] == 109) || $t[0] == 57) || $t[0] == 58) || $t[0] == 59) || $t[0] == 60))) {
                        continue;
                    }
                    if (isset($this->troops[$t[0]])) {
                        $this->troops[$t[0]] += $t[1];
                        continue;
                    }
                    $this->troops[$t[0]] = $t[1];
                }
                continue;
            }
        }
        if ((!$toBeHero && !isset($this->troops[99]))) {
            $this->troops[99] = 0;
        }
    }


    /**
     * _getMaxBuildingLevel Method
     *
     * @param itemId int
     * @return void
     */
    public function _getMaxBuildingLevel($itemId)
    {
        $result = 0;
        foreach ($this->buildings as $villageBuild) {
            if (($villageBuild['item_id'] == $itemId && $result < $villageBuild['level'])) {
                $result = $villageBuild['level'];
                continue;
            }
        }
        return $result;
    }


    /**
     * _getCurrentNumberFor Method
     *
     * @param troopId int
     * @param item int
     * @return void
     */
    public function _getCurrentNumberFor($troopId, $item)
    {
        $num = 0;
        if (isset($this->troops[$troopId])) {
            $num += $this->troops[$troopId];
        }
        if ((isset($this->queueModel->tasksInQueue[$this->troopsUpgradeType]) && isset($this->queueModel->tasksInQueue[$this->troopsUpgradeType][$item]))) {
            $qts = $this->queueModel->tasksInQueue[$this->troopsUpgradeType][$item];
            foreach ($qts as $qt) {
                if ($qt['proc_params'] == $troopId) {
                    $num += $qt['threads'];
                    continue;
                }
            }
        }
        $num += $this->_getTroopCountInTransfer($troopId, QS_WAR_REINFORCE);
        $num += $this->_getTroopCountInTransfer($troopId, QS_WAR_ATTACK);
        $num += $this->_getTroopCountInTransfer($troopId, QS_WAR_ATTACK_PLUNDER);
        $num += $this->_getTroopCountInTransfer($troopId, QS_WAR_ATTACK_SPY);
        $num += $this->_getTroopCountInTransfer($troopId, QS_CREATEVILLAGE);
        $num += $this->_getOnlyMyOuterTroops($troopId);
        return $num;
    }


    /**
     * _getTroopCountInTransfer Method
     *
     * @param troopId int
     * @param type int
     * @return void
     */
    public function _getTroopCountInTransfer($troopId, $type)
    {
        $num = 0;
        if (isset($this->queueModel->tasksInQueue[$type])) {
            $qts = $this->queueModel->tasksInQueue[$type];
            foreach ($qts as $qt) {
                $arr = explode('|', $qt['proc_params']);
                $arr = explode(',', $arr[0]);
                foreach ($arr as $arrStr) {
                    list($tid, $tnum) = explode(' ', $arrStr);
                    if ($tid == $troopId) {
                        $num += $tnum;
                        continue;
                    }
                }
            }
        }
        return $num;
    }


    /**
     * _getMaxTrainNumber Method
     *
     * @param troopId int
     * @param type int
     * @return void
     */
    public function _getMaxTrainNumber($troopId, $item)
    {
        $this->load_model('Artefacts', 'A');
        $artLevel2 = $this->A->Artefacts($this->player->playerId, $this->data['selected_village_id'], 8);
        $this->artPower2 = ($artLevel2 == 0) ? 1 : (($artLevel2 == 1) ? 0.5 : (($artLevel2 == 2) ? 0.75 : 0.5));
        $max = 0;
        $_f = TRUE;
        foreach ($this->gameMetadata['troops'][$troopId]['training_resources'] as $k => $v) {
            $num = floor($this->resources[$k]['current_value'] / ($v * $this->buildingTribeFactor * $this->artPower2));
            if (($num < $max || $_f)) {
                $_f = FALSE;
                $max = $num;
                continue;
            }
        }
        if ($troopId == 99) {
            $buildingMetadata = $this->gameMetadata['items'][$this->buildings[$this->buildingIndex]['item_id']]['levels'][$this->buildProperties['building']['level'] - 1];
            $_maxValue = $buildingMetadata['value'] - $this->troops[$troopId];
            if ((isset($this->queueModel->tasksInQueue[$this->troopsUpgradeType]) && isset($this->queueModel->tasksInQueue[$this->troopsUpgradeType][$this->buildProperties['building']['item_id']]))) {
                $qts = $this->queueModel->tasksInQueue[$this->troopsUpgradeType][$this->buildProperties['building']['item_id']];
                foreach ($qts as $qt) {
                    if ($qt['proc_params'] == $troopId) {
                        $_maxValue -= $qt['threads'];
                        continue;
                    }
                }
            }
            if ($_maxValue < $max) {
                $max = $_maxValue;
            }
        } else {
            if (($item == 25 || $item == 26)) {
                $_maxValue = $this->CurrentNumberResidencePalace($troopId, $item);
                if ($_maxValue < $max) {
                    $max = $_maxValue;
                }
            }
        }
        return ($max < 0 ? 0 : $max);
    }

    public function CurrentNumberResidencePalace($troopId, $item)
    {
        $this->childVillagesCount = 0;
        $_maxValue = 0;

        if (trim($this->data['child_villages_id']) != '') {
            $this->childVillagesCount = sizeof(explode(',', $this->data['child_villages_id']));
        }

        $this->load_model('Build', 'm');
        $VillagesCountc = $this->m->getPlayerVillagesDataPyId($this->player->playerId);

        foreach ($VillagesCountc as $value) {
            $villagcon = sizeof(explode(',', $value['child_villages_id']));
            if ($villagcon == 3 && $value['id'] != $this->data['selected_village_id'] && $this->data['is_capital']) {
                $this->data['is_capital'] = 0;
            }
        }
        unset($VillagesCountc);
        $buildingLevel = $this->buildings[$this->buildingIndex]['level'];

        if (($buildingLevel < 15 && $buildingLevel >= 10) || ($buildingLevel < 20 && !$this->data['is_capital'] && $this->childVillagesCount == 0 && $buildingLevel >= 10) || ($this->childVillagesCount == 2 && $buildingLevel == 20 && $this->data['is_capital']) || ($this->childVillagesCount == 1 && $buildingLevel < 20 && $this->data['is_capital'] && $buildingLevel >= 10) || ($this->childVillagesCount == 1 && $buildingLevel == 20 && !$this->data['is_capital'])) {
            $_maxValue = ((((($troopId == 9 || $troopId == 19) || $troopId == 29) || $troopId == 108) || $troopId == 59) ? 1 : 3);
            if (((($troopId == 9 || $troopId == 19) || $troopId == 29) || $troopId == 108) || $troopId == 59) {
                $currenm = $this->_getCurrentNumberFor($troopId + 1, $item);
                if ($currenm != 0 && $currenm <= 3) {
                    $_maxValue = 0;
                }
            }
            if (((($troopId == 10 || $troopId == 20) || $troopId == 30) || $troopId == 109) || $troopId == 60) {
                $currenz = $this->_getCurrentNumberFor($troopId - 1, $item);
                if ($currenz >= 1) {
                    $_maxValue = 0;
                }
            }
        }

        if (($buildingLevel < 20 && $buildingLevel >= 15 && $this->data['is_capital'] && $this->childVillagesCount == 0) || ($this->childVillagesCount == 0 && $buildingLevel == 20 && !$this->data['is_capital']) || ($this->childVillagesCount == 1 && $buildingLevel == 20 && $this->data['is_capital'])) {
            $_maxValue = ((((($troopId == 9 || $troopId == 19) || $troopId == 29) || $troopId == 108) || $troopId == 59) ? 2 : 6);
            if (((($troopId == 9 || $troopId == 19) || $troopId == 29) || $troopId == 108) || $troopId == 59) {
                $currenm = $this->_getCurrentNumberFor($troopId + 1, $item);
                if ($currenm != 0 && $currenm <= 3) {
                    $_maxValue = 1;
                }
                if ($currenm >= 4) {
                    $_maxValue = 0;
                }
            }
            if (((($troopId == 10 || $troopId == 20) || $troopId == 30) || $troopId == 109) || $troopId == 60) {
                $currenz = $this->_getCurrentNumberFor($troopId - 1, $item);
                if ($currenz == 1) {
                    $_maxValue = 3;
                }
                if ($currenz == 2) {
                    $_maxValue = 0;
                }
            }
        }

        if ($buildingLevel == 20 && $this->data['is_capital'] && $this->childVillagesCount == 0) {
            $_maxValue = ((((($troopId == 9 || $troopId == 19) || $troopId == 29) || $troopId == 108) || $troopId == 59) ? 3 : 9);
            if (((($troopId == 9 || $troopId == 19) || $troopId == 29) || $troopId == 108) || $troopId == 59) {
                $currenm = $this->_getCurrentNumberFor($troopId + 1, $item);
                if ($currenm != 0 && $currenm <= 3) {
                    $_maxValue = 2;
                }
                if ($currenm >= 4 && $currenm <= 6) {
                    $_maxValue = 1;
                }
                if ($currenm >= 7) {
                    $_maxValue = 0;
                }
            }

            if (((($troopId == 10 || $troopId == 20) || $troopId == 30) || $troopId == 109) || $troopId == 60) {
                $currenz = $this->_getCurrentNumberFor($troopId - 1, $item);
                if ($currenz == 1) {
                    $_maxValue = 6;
                }
                if ($currenz == 2) {
                    $_maxValue = 3;
                }
                if ($currenz == 3) {
                    $_maxValue = 0;
                }
            }
        }

        return $_maxValue -= $this->_getCurrentNumberFor($troopId, $item);
    }

    /**
     * _canTrainInBuilding Method
     *
     * @param troopId int
     * @param itemId int
     * @return void
     */
    public function _canTrainInBuilding($troopId, $itemId)
    {
        foreach ($this->gameMetadata['troops'][$troopId]['trainer_building'] as $buildingId) {
            if ($buildingId == $itemId) {
                return TRUE;
            }
        }
        return FALSE;
    }


    /**
     * _canDoResearches Method
     *
     * @param troopId int
     * @return void
     */
    public function _canDoResearches($troopId)
    {
        foreach ($this->gameMetadata['troops'][$troopId]['pre_requests'] as $req_item_id => $level) {
            $result = FALSE;
            foreach ($this->buildings as $villageBuild) {
                if (($villageBuild['item_id'] == $req_item_id && $level <= $villageBuild['level'])) {
                    $result = TRUE;
                    break;
                    continue;
                }
            }
            if (!$result) {
                return FALSE;
            }
        }
        return TRUE;
    }


    /**
     * getNeededTime Method
     *
     * @param neededResources string
     * @return void
     */
    public function getNeededTime($neededResources)
    {
        $timeInSeconds = 0;
        foreach ($neededResources as $k => $v) {
            if ($this->resources[$k]['current_value'] < $v) {
                if ($this->resources[$k]['calc_prod_rate'] <= 0) {
                    return 0 - 1;
                }
                $time = ($v - $this->resources[$k]['current_value']) / $this->resources[$k]['calc_prod_rate'];
                if ($timeInSeconds < $time) {
                    $timeInSeconds = $time;
                    continue;
                }
                continue;
            }
        }
        return ceil($timeInSeconds * 3600);
    }


    /**
     * getActionText4 Method
     *
     * @param neededResources string
     * @param url string
     * @param text string
     * @param queueTaskType int
     * @param buildLevel int
     * @param troopLevel int
     * @return void
     */
    public function getActionText4($neededResources, $url, $text, $queueTaskType, $buildLevel, $troopLevel)
    {
        if (isset($this->queueModel->tasksInQueue[$queueTaskType])) {
            return '<span class="none">' . buildings_p_plwait . '</span>';
        }
        if ($buildLevel <= $troopLevel) {
            return '<span class="none">' . buildings_p_needmorecapacity . '</span>';
        }
        return (!$this->isResourcesAvailable($neededResources) ? '<span class="none">' . buildings_p_notenoughres . '</span>' : '<a class="build" href="build?id=' . $this->buildingIndex . '&' . $url . '&k=' . $this->data['update_key'] . '">' . $text . '</a>');
    }


    /**
     * getActionText3 Method
     *
     * @param neededResources string
     * @param url string
     * @param text string
     * @param queueTaskType int
     * @return void
     */
    public function getActionText3($neededResources, $url, $text, $queueTaskType)
    {
        if (isset($this->queueModel->tasksInQueue[$queueTaskType])) {
            return '<span class="none">' . buildings_p_plwait . '</span>';
        }
        return (!$this->isResourcesAvailable($neededResources) ? '<span class="none">' . buildings_p_notenoughres . '</span>' : '<a class="build" href="build?id=' . $this->buildingIndex . '&' . $url . '&k=' . $this->data['update_key'] . '">' . $text . '</a>');
    }


    /**
     * getActionText2 Method
     *
     * @param neededResources string
     * @return void
     */
    public function getActionText2($neededResources)
    {
        $needUpgradeType = $this->needMoreUpgrades($neededResources);
        if (0 < $needUpgradeType) {
            switch ($needUpgradeType) {
                case 2:
                    return '<span class="none">' . buildings_p_upg1 . '</span>';
                case 3:
                    return '<span class="none">' . buildings_p_upg2 . '</span>';
                case 4:
                    return '<span class="none">' . buildings_p_upg3 . '</span>';
            }
        }
        if (!$this->isResourcesAvailable($neededResources)) {
            $neededTime = $this->getNeededTime($neededResources);
            return '<span class="none">' . (0 < $neededTime ? buildings_p_willenoughresat . ' ' . secondstostring($neededTime) . ' ' . time_hour_lang : buildings_p_notenoughres2) . '</span>';
        }
        return '';
    }


    /**
     * getActionText Method
     *
     * @param neededResources string
     * @param isField int
     * @param upgrade int
     * @param item_id int
     * @return void
     */
    public function getActionText($neededResources, $isField, $upgrade, $item_id)
    {
        $needUpgradeType = $this->needMoreUpgrades($neededResources, $item_id);
        if (0 < $needUpgradeType) {
            switch ($needUpgradeType) {
                case 1:
                    return '<span class="none">' . buildings_p_upg0 . '</span>';
                case 2:
                    return '<span class="none">' . buildings_p_upg1 . '</span>';
                case 3:
                    return '<span class="none">' . buildings_p_upg2 . '</span>';
                case 4:
                    return '<span class="none">' . buildings_p_upg3 . '</span>';
            }
        } else {
            if ($this->isResourcesAvailable($neededResources)) {
                $pageNamePostfix = ($isField ? '1' : '2');
                $link = ($upgrade ? '<a class="build" href="village' . $pageNamePostfix . '?id=' . $this->buildingIndex . '&k=' . $this->data['update_key'] . '">' . buildings_p_upg_tolevel . ' ' . $this->buildProperties['nextLevel'] . '</a>' : '<a class="build" href="village2?id=' . $this->buildingIndex . '&b=' . $item_id . '&k=' . $this->data['update_key'] . '">' . buildings_p_create_newbuild . '</a>');
                $workerResult = $this->isWorkerBusy($isField);
                return ($workerResult['isBusy'] ? '<span class="none">' . buildings_p_workersbusy . '</span>' : $link . ($workerResult['isPlusUsed'] ? ' <span class="none">(' . buildings_p_wait_buildqueue . ')</span>' : ''));
            }
        }
        $neededTime = $this->getNeededTime($neededResources);
        return '<span class="none">' . (0 < $neededTime ? buildings_p_willenoughresat . ' ' . secondstostring($neededTime) . ' ' . time_hour_lang : buildings_p_notenoughres2) . '</span>';
    }


    /**
     * _canAcceptOffer Method
     *
     * @param neededResources string
     * @param giveResources int
     * @param villageId int
     * @param onlyForAlliance int
     * @param allianceId int
     * @param maxTime int
     * @param distance int
     * @return void
     */
    public function _canAcceptOffer($needResources, $giveResources, $villageId, $onlyForAlliance, $allianceId, $maxTime, $distance)
    {
        if ($villageId == $this->data['selected_village_id']) {
            return 0;
        }
        if (!$this->isResourcesAvailable($giveResources)) {
            return 1;
        }
        $needMerchantCount = ceil(($giveResources[1] + $giveResources[2] + $giveResources[3] + $giveResources[4]) / $this->merchantProperty['capacity']);
        if (($needMerchantCount == 0 || $this->merchantProperty['exits_num'] < $needMerchantCount)) {
            return 2;
        }
        if (($onlyForAlliance && (intval($this->data['alliance_id']) == 0 || $allianceId != intval($this->data['alliance_id'])))) {
            return 3;
        }
        if ((0 < $maxTime && $maxTime < $distance / $this->merchantProperty['speed'])) {
            return 4;
        }
        return 5;
    }


    /**
     * getNextLink Method
     *
     * @return void
     */
    public function getNextLink()
    {
        $text = '»';
        if ($this->pageIndex + 1 == $this->pageCount) {
            return $text;
        }
        $link = '';
        if (0 < $this->selectedTabIndex) {
            $link .= 't=' . $this->selectedTabIndex;
        }
        if ($link != '') {
            $link .= '&';
        }
        $link .= 'p=' . ($this->pageIndex + 1);
        $link = 'build?id=' . $this->buildingIndex . '&' . $link;
        return '<a href="' . $link . '">' . $text . '</a>';
    }


    /**
     * getPreviousLink Method
     *
     * @return void
     */
    public function getPreviousLink()
    {
        $text = '«';
        if ($this->pageIndex == 0) {
            return $text;

            $link = '';
            if (0 < $this->selectedTabIndex) {
                $link .= 't=' . $this->selectedTabIndex;
            }
            if (1 < $this->pageIndex) {
                if ($link != '') {
                    $link .= '&';
                }
                $link .= 'p=' . ($this->pageIndex - 1);
            }
            $link = 'build?id=' . $this->buildingIndex . '&' . $link;
            return '<a href="' . $link . '">' . $text . '</a>';
        }
    }


    /**
     * getResourceGoldExchange Method
     *
     * @param neededResources string
     * @param itemId int
     * @param buildingIndex int
     * @param multiple bool
     * @return void
     */
    public function getResourceGoldExchange($neededResources, $itemId, $buildingIndex, $multiple = FALSE)
    {
        if ((($this->data['gold_num'] < $this->gameMetadata['plusTable'][6]['cost'] || 0 < $this->needMoreUpgrades($neededResources, $itemId)) || ($this->isResourcesAvailable($neededResources) && !$multiple))) {
            return '';
        }
        $s1 = 0;
        $s2 = 0;
        $exchangeResource = '';
        foreach ($neededResources as $k => $v) {
            $s1 += $v;
            $s2 += $this->resources[$k]['current_value'];
            if ($exchangeResource != '') {
                $exchangeResource .= '&';
            }
            $exchangeResource .= 'r' . $k . '=' . $v;
        }
        $canExchange = $s1 <= $s2;
        if (($multiple && $canExchange)) {
            $num = floor($s2 / $s1);
            $exchangeResource = '';
            foreach ($neededResources as $k => $v) {
                if ($exchangeResource != '') {
                    $exchangeResource .= '&';
                }
                $exchangeResource .= 'r' . $k . '=' . $v * $num;
            }
        }
        return ' | <a href="build?bid=17&t=3&rid=' . $buildingIndex . '&' . $exchangeResource . '" title="' . buildings_p_m2m . '"><img class="npc' . ($canExchange ? '' : '_inactive') . '" src="' . URL . '/assets/x.gif" alt="' . buildings_p_m2m . '" title="' . buildings_p_m2m . '"></a>';
    }
}

//end file
?>
<?php

load_game_engine('Village');

class V2v_Controller extends VillageController
{
    public $pageState = null;
    public $artLevel = 0;
    public $targetVillage = array('x' => NULL, 'y' => NULL);
    public $troops = null;
    public $disableFirstTwoAttack = FALSE;
    public $attackWithCatapult = FALSE;
    public $transferType = 2;
    public $errorTable = array();
    public $newVillageResources = array(1 => 750, 2 => 750, 3 => 750, 4 => 750);
    public $rallyPointLevel = 0;
    public $totalCatapultTroopsCount = 0;
    public $catapultCanAttackLastIndex = 0;
    public $availableCatapultTargetsString = '';
    public $catapultCanAttack = array(0 => 0, 1 => 10, 2 => 11, 3 => 9, 4 => 6, 5 => 2, 6 => 4, 7 => 8, 8 => 7, 9 => 3, 10 => 5, 11 => 1, 12 => 22, 13 => 13, 14 => 19, 15 => 12, 16 => 35, 17 => 18, 18 => 29, 19 => 30, 20 => 37, 21 => 41, 22 => 15, 23 => 17, 24 => 26, 25 => 16, 26 => 25, 27 => 20, 28 => 14, 29 => 24, 30 => 28, 31 => 40, 32 => 21, 33 => 27);
    public $onlyOneSpyAction = FALSE;
    public $backTroopsProperty = array();

    public function __construct()
    {
        parent::__construct();
        $this->viewFile                    = 'v2v';
        $this->viewData['contentCssClass'] = 'a2b';
    }

    public function onLoadBuildings($building)
    {
        if (($building['item_id'] == 16 AND $this->rallyPointLevel < $building['level']))
        {
            $this->rallyPointLevel = $building['level'];
        }
    }


    public function index()
    {
        ## Views - passing by reference
        $this->viewData['pageState'] =& $this->pageState;
        $this->viewData['troops'] =& $this->troops;
        $this->viewData['errorTable'] =& $this->errorTable;
        $this->viewData['hasHero'] =& $this->hasHero;
        $this->viewData['transferType'] =& $this->transferType;
        $this->viewData['disableFirstTwoAttack'] =& $this->disableFirstTwoAttack;
        $this->viewData['targetVillage'] =& $this->targetVillage;
        $this->viewData['newVillageResources'] =& $this->newVillageResources;
        $this->viewData['attackWithCatapult'] =& $this->attackWithCatapult;
        $this->viewData['backTroopsProperty'] =& $this->backTroopsProperty;
        $this->viewData['rallyPointLevel'] = $this->rallyPointLevel;
        $this->viewData['totalCatapultTroopsCount'] =& $this->totalCatapultTroopsCount;
        $this->viewData['availableCatapultTargetsString'] =& $this->availableCatapultTargetsString;
        $this->viewData['onlyOneSpyAction'] =& $this->onlyOneSpyAction;


        ## Code ..
        if ($this->rallyPointLevel <= 0)
        {
            $this->is_redirect = TRUE;
            redirect('build?id=39');
            return null;
        }
        if (is_post('captcha'))
        {
            if (post('captcha') != $_SESSION['vercode'])
            {
                $this->is_redirect = TRUE;
                redirect('build?id=39');
                return null;
            }
        }
        $_SESSION['vercode'] = 0;

        if (((is_get('d1') OR is_get('d2')) OR is_get('d3')))
        {
            $this->pageState = 3;
            $this->handleTroopBack();
            return null;
        }

        $this->load_model('War', 'm');
        $this->pageState = 1;
        $map_size        = $this->setupMetadata['map_size'];
        $half_map_size   = floor($map_size / 2);
        $this->hasHero   = $this->data['hero_in_village_id'] == $this->data['selected_village_id'];
        $t_arr           = explode('|', $this->data['troops_num']);
        foreach ($t_arr as $t_str)
        {
            $t2_arr = explode(':', $t_str);
            if ($t2_arr[0] == 0 - 1)
            {
                $t2_arr = explode(',', $t2_arr[1]);
                foreach ($t2_arr as $t2_str)
                {
                    $t = explode(' ', $t2_str);
                    if ($t[0] == 99)
                    {
                        continue;
                    }
                    $this->troops[] = array(
                        'troopId' => $t[0],
                        'number' => $t[1]
                    );
                }
                continue;
            }
        }
        $attackOptions1 = '';
        $sendTroops     = FALSE;
        $playerData     = NULL;
        $playerData2    = NULL;
        $villageRow     = NULL;

        if (!$_POST)
        {
            if ((is_get('id') AND is_numeric(get('id'))))
            {
                $vid = intval(get('id'));
                if ($vid < 1)
                {
                    $vid = 1;
                }
                $villageRow = $this->m->getVillageDataById($vid);
            }
        }
        else
        {
            if (is_post('id'))
            {
                $sendTroops = (!$this->isGameTransientStopped() AND !$this->isGameOver());
                $vid        = intval(post('id'));
                $villageRow = $this->m->getVillageDataById($vid);
            }
            else
            {
                if ((is_post('dname') AND trim(post('dname')) != ''))
                {
                    $villageRow = $this->m->getVillageDataByName(post('dname'));
                }
                else
                {
                    if ((((is_post('x') AND is_post('y')) AND trim(post('x')) != '') AND post('y') != ''))
                    {
                        $vid        = $this->__getVillageId($map_size, $this->__getCoordInRange($map_size, intval(post('x'))), $this->__getCoordInRange($map_size, intval(post('y'))));
                        $villageRow = $this->m->getVillageDataById($vid);
                    }
                }
            }
        }
        if ($villageRow == NULL)
        {
            if ($_POST)
            {
                $this->errorTable = v2v_p_entervillagedata;
            }
            return null;
        }

        $this->disableFirstTwoAttack = (intval($villageRow['player_id']) == 0 AND $villageRow['is_oasis']);
        $this->targetVillage['x']    = floor(($villageRow['id'] - 1) / $map_size);
        $this->targetVillage['y']    = $villageRow['id'] - ($this->targetVillage['x'] * $map_size + 1);
        if ($half_map_size < $this->targetVillage['x'])
        {
            $this->targetVillage['x'] -= $map_size;
        }
        if ($half_map_size < $this->targetVillage['y'])
        {
            $this->targetVillage['y'] -= $map_size;
        }
        if ($villageRow['id'] == $this->data['selected_village_id'])
        {
            return null;
        }
        if ((0 < intval($villageRow['player_id']) AND $this->m->getPlayType($villageRow['player_id']) == PLAYERTYPE_ADMIN))
        {
            return null;
        }
        $spyOnly = FALSE;
        if ((!$villageRow['is_oasis'] AND intval($villageRow['player_id']) == 0))
        {
            $this->transferType = 1;
            $humanTroopId       = 0;
            $renderTroops       = array();
            foreach ($this->troops as $troop)
            {
                $renderTroops[$troop['troopId']] = 0;
                if ((((((($troop['troopId'] == 10 OR $troop['troopId'] == 20) OR $troop['troopId'] == 30) OR $troop['troopId'] == 109) OR $troop['troopId'] == 60) OR $troop['troopId'] == 70) OR $troop['troopId'] == 80))
                {
                    $humanTroopId                = $troop['troopId'];
                    $renderTroops[$humanTroopId] = $troop['number'];
                    if ($renderTroops[$humanTroopId] >= 3)
                    {
                        $renderTroops[$humanTroopId] = 3;
                    }
                    continue;
                }
            }
            $canBuildNewVillage = (isset($renderTroops[$humanTroopId]) AND 3 <= $renderTroops[$humanTroopId]);
            if ($canBuildNewVillage)
            {
                $count = (trim($this->data['child_villages_id']) == '' ? 0 : sizeof(explode(',', $this->data['child_villages_id'])));
                if (1 < $count && !$this->data['is_capital'])
                {
                    $this->errorTable = v2v_p_cannotbuildnewvill;
                    return null;
                }
                if (2 < $count && $this->data['is_capital'])
                {
                    $this->errorTable = v2v_p_cannotbuildnewvill;
                    return null;
                }
                if (!$this->_canBuildNewVillage())
                {
                    $this->errorTable = v2v_p_cannotbuildnewvill1;
                    return null;
                }
                if (!$this->isResourcesAvailable($this->newVillageResources))
                {
                    $this->errorTable = sprintf(v2v_p_cannotbuildnewvill2, $this->newVillageResources['1']);
                    return null;
                }
                $this->load_model('War', 'm');
                if ($this->m->hasNewVillageTask($this->player->playerId))
                {
                    $this->errorTable = v2v_p_cannotbuildnewvill3;
                    return null;
                }
            }
            else
            {
                $this->errorTable = v2v_p_cannotbuildnewvill4;
                return null;
            }
            $this->pageState = 2;
        }
        else
        {
            if ($_POST)
            {
                if ((!$villageRow['is_oasis'] AND intval($villageRow['player_id']) == 0))
                {
                    $this->errorTable = v2v_p_novillagehere;
                    return null;
                }
                if (((!is_post('c') && intval(post('c')) < 1) OR 4 < intval(post('c'))))
                {
                    return null;
                }
                $this->transferType = ($this->disableFirstTwoAttack ? 4 : intval(post('c')));

                $this->load_model('War', 'm');
                $war9 = $this->m->IfVillageHasAttak($this->data['selected_village_id']);
                if ($war9 >= 150)
                {
                    $this->errorTable = v2v_p_cantattac;
                    return null;
                }
                if (0 < intval($villageRow['player_id']))
                {
                    if ($villageRow['player_id'] != $this->player->playerId AND $this->player->isAgent AND intval(post('c')) == 2)
                    {
                        $this->errorTable = v2v_p_isAgent;
                        return null;
                    }
                    $playerData = $this->m->getPlayerDataById(intval($villageRow['player_id']));
                    if ($playerData['blocked_second'] > 0)
                    {
                        $this->errorTable = v2v_p_playerwas_blocked;
                        return null;
                    }
                    $playerData2 = $this->m->getPlayerDataById($this->player->playerId);

                    if ($villageRow['player_id'] != $this->player->playerId && intval ($_POST['c']) != 2)
                    {
                        $ip_his1 = explode(',', $playerData['ip_his']);
                        $ip_his2 = explode(',', $playerData2['ip_his']);
                        foreach ($ip_his1 as $value)
                        {
                            if (preg_match('/A/i',$value))
                            {
                                continue;
                            }
                            if (in_array($value, $ip_his2))
                            {
                                $this->errorTable = LANGUI_PAIN_reason2;
                                break;
                                return null;
                            }
                        }
                    }
					if($villageRow['player_id'] != $this->player->playerId && intval ($_POST['c']) != 2)
					{
						if ( $playerData['alliance_id'] == $playerData2['alliance_id'] && $playerData2['alliance_id'] != "")
					    {
						    $this->errorTable = v2v_p_playerwas_inyouralliance;
                            return null;
					    }
						if($this->m->ifhasContracts($playerData['alliance_id'], $playerData2['alliance_id']))
						{
							$this->errorTable = v2v_p_playerwas_inpeacealliance;
                            return null;
						}
					}
					
                    /*$hispeople = $playerData['total_people_count'];
                    $mypeople = $playerData2['total_people_count'];
                    if ( (($mypeople*10)/100) >= $hispeople AND intval ($_POST['c']) != 2 )
                    {
                    $this->errorTable = v2v_p_playerwas_week;
                    return null;
                    }
                    if ( (($hispeople*10)/100) >= $mypeople AND intval ($_POST['c']) != 2 )
                    {
                    $this->errorTable = v2v_p_playerwas_strong;
                    return null;
                    } */
                    $this->Gsummry = $this->m->GetGsummaryData();
                    if ($this->Gsummry['truce_second'] > 0)
                    {
                        $this->errorTable = $this->Gsummry['truce_reason'];
                        return null;
                    }
                    if ($villageRow['tribe_id'] != 5 AND !$villageRow['is_special_village'])
                    {
                        $protection = explode(',', $playerData2['protection']);
                        if ($protection[0] == 1 AND $protection[1] > time())
                        {
                            if ($this->player->playerId != $villageRow['player_id'])
                            {
                                $this->errorTable = v2v_p_playerwas_youinprotectedperiod;
                                return null;
                            }
                        }
						if(0 < $playerData2['protection_remain_sec'] && !$villageRow['is_oasis'])
						{
							$this->m->UpdatePlayerprotection($this->player->playerId);
						}
                        $protection = explode(',', $playerData['protection']);
                        if (0 < $playerData['protection_remain_sec'] || ($protection[0] == 1 AND $protection[1] > time()))
                        {
                            if ($this->player->playerId != $villageRow['player_id'])
                            {
                                $this->errorTable = v2v_p_playerwas_inprotectedperiod;
                                return null;
                            }
                        }
                    }

                    if ($playerData2['is_blocked'])
                    {
                        $this->errorTable = v2v_p_playerwas_youblocked;
                        return null;
                    }
                    $holiday = explode(',', $playerData['holiday']);
                    if ($holiday[0] == 1)
                    {
                        $this->errorTable = v2v_p_playerwas_holiday;
                        return null;
                    }
                }
                $totalTroopsCount               = 0;
                $totalSpyTroopsCount            = 0;
                $this->totalCatapultTroopsCount = 0;
                $hasTroopsSelected              = FALSE;
                $renderTroops                   = array();
                if (is_post('tro') AND is_post('farm'))
                {
                    $tro_arr = explode(',', $_POST['tro']);
                    foreach ($tro_arr as $tr2_arr)
                    {
                        list($tid, $tnum) = explode(' ', $tr2_arr);
                        $_POST['t'][$tid] = $tnum;
                    }
                }
                if (is_post('t') || is_post('tro'))
                {
                    foreach ($this->troops as $troop)
                    {
                        $num = 0;
                        if ((isset($_POST['t'][$troop['troopId']]) AND 0 < intval($_POST['t'][$troop['troopId']])))
                        {
                            if (preg_match('/^[+-]?[0-9]+$/', $_POST['t'][$troop['troopId']]) == 0)
                            {
                                $this->errorTable = v2v_p_thereisnoattacktroops;
                                return null;
                                exit;
                            }
                            $num = ($troop['number'] < $_POST['t'][$troop['troopId']] ? $troop['number'] : intval($_POST['t'][$troop['troopId']]));
                        }
                        $renderTroops[$troop['troopId']] = $num;
                        $totalTroopsCount += $num;
                        if (0 < $num)
                        {
                            $hasTroopsSelected = TRUE;
                        }
                        if ((((((($troop['troopId'] == 4 OR $troop['troopId'] == 14) OR $troop['troopId'] == 23) OR $troop['troopId'] == 103) OR $troop['troopId'] == 54) OR $troop['troopId'] == 64) OR $troop['troopId'] == 74))
                        {
                            $totalSpyTroopsCount += $num;
                            continue;
                        }
                        else
                        {
                            if ((((((($troop['troopId'] == 8 OR $troop['troopId'] == 18) OR $troop['troopId'] == 28) OR $troop['troopId'] == 107) OR $troop['troopId'] == 58) OR $troop['troopId'] == 68) OR $troop['troopId'] == 78))
                            {
                                $this->totalCatapultTroopsCount = $num;
                                //+= 'totalCatapultTroopsCount';
                                //= $num;
                                continue;
                            }
                            continue;
                        }
                    }
                }
                if ((($this->hasHero AND is_post('_t')) AND intval(post('_t')) == 1))
                {
                    $hasTroopsSelected = TRUE;
                    $totalTroopsCount += 1;
                }
                $spyOnly = (($totalSpyTroopsCount == $totalTroopsCount AND ($this->transferType == 3 OR $this->transferType == 4)) AND 0 < intval($villageRow['player_id']));
                if ($spyOnly)
                {
                    $this->onlyOneSpyAction = $villageRow['is_oasis'];
                }
                $this->attackWithCatapult = (((0 < $this->totalCatapultTroopsCount AND $this->transferType == 3) AND 0 < intval($villageRow['player_id'])) AND !$villageRow['is_oasis']);
                if ($this->attackWithCatapult)
                {
                    /*$this->load_model('Artefacts', 'A');
                    $artLevel = $this->A->Artefacts($villageRow['player_id'], $villageRow['id'], 8);
                    if ($artLevel)
                    {
                        $this->catapultCanAttackLastIndex = 0;
                    }
                    else*/if (10 <= $this->rallyPointLevel)
                    {
                        $this->catapultCanAttackLastIndex = sizeof($this->catapultCanAttack) - 1;
                    }
                    else
                    {
                        if (5 <= $this->rallyPointLevel)
                        {
                            $this->catapultCanAttackLastIndex = 11;
                        }
                        else
                        {
                            if (3 <= $this->rallyPointLevel)
                            {
                                $this->catapultCanAttackLastIndex = 2;
                            }
                            else
                            {
                                $this->catapultCanAttackLastIndex = 0;
                            }
                        }
                    }
                    $attackOptions1 = ((is_post('dtg') AND $this->_containBuildingTarget(post('dtg'))) ? intval(post('dtg')) : 0);
                    if (($this->rallyPointLevel == 20 AND 2000 <= $this->totalCatapultTroopsCount))
                    {
                        $attackOptions1 = '2:' . ($attackOptions1 . ' ' . ((is_post('dtg1') AND $this->_containBuildingTarget(post('dtg1'))) ? intval(post('dtg1')) : 0));
                    }
                    else
                    {
                        $attackOptions1 = '1:' . $attackOptions1;
                    }
                    $this->availableCatapultTargetsString = '';
                    $selectComboTargetOptions             = '';
                    $i                                    = 1;
                    while ($i <= 9)
                    {
                        if ($this->_containBuildingTarget($i))
                        {
                            $selectComboTargetOptions .= sprintf('<option value="%s">%s</option>', $i, constant('item_' . $i));
                        }
                        ++$i;
                    }
                    if ($selectComboTargetOptions != '')
                    {
                        $this->availableCatapultTargetsString .= '<optgroup label="' . v2v_p_catapult_grp1 . '">' . $selectComboTargetOptions . '</optgroup>';
                    }
                    $selectComboTargetOptions = '';
                    $i                        = 10;
                    if ($villageRow['is_special_village'])
                    {
                        $buildid = 40;
                    }
                    else
                    {
                        $buildid = 32;
                    }
                    while ($i <= $buildid)
                    {
                        if ((((((((((((($i == 10 OR $i == 11) OR $i == 15) OR $i == 17) OR $i == 18) OR $i == 24) OR $i == 25) OR $i == 26) OR $i == 27) OR $i == 28) OR $i == 38) OR $i == 39) OR $i == 40))
                        {
                            if ($this->_containBuildingTarget($i))
                            {
                                $selectComboTargetOptions .= sprintf('<option value="%s">%s</option>', $i, constant('item_' . $i));
                            }
                        }
                        ++$i;
                    }
                    if ($selectComboTargetOptions != '')
                    {
                        $this->availableCatapultTargetsString .= '<optgroup label="' . v2v_p_catapult_grp2 . '">' . $selectComboTargetOptions . '</optgroup>';
                    }
                    $selectComboTargetOptions = '';
                    $i                        = 12;
                    while ($i <= 37)
                    {
                        if (((((((((($i == 12 OR $i == 13) OR $i == 14) OR $i == 16) OR $i == 19) OR $i == 20) OR $i == 21) OR $i == 22) OR $i == 35) OR $i == 37))
                        {
                            if ($this->_containBuildingTarget($i))
                            {
                                $selectComboTargetOptions .= sprintf('<option value="%s">%s</option>', $i, constant('item_' . $i));
                            }
                        }
                        ++$i;
                    }
                    if ($selectComboTargetOptions != '')
                    {
                        $this->availableCatapultTargetsString .= '<optgroup label="' . v2v_p_catapult_grp3 . '">' . $selectComboTargetOptions . '</optgroup>';
                    }
                }
                if (!$hasTroopsSelected)
                {
                    $this->errorTable = v2v_p_thereisnoattacktroops;
                    return null;
                }
                $this->pageState = 2;
            }
        }
        if ($this->pageState == 2)
        {
            $this->targetVillage['transferType'] = ($this->transferType == 1 ? v2v_p_attacktyp1 : ($this->transferType == 2 ? v2v_p_attacktyp2 . ' ' : ($this->transferType == 3 ? v2v_p_attacktyp3 : ($this->transferType == 4 ? v2v_p_attacktyp4 : ''))));
            if ($villageRow['is_oasis'])
            {
                $this->targetVillage['villageName'] = ($playerData != NULL ? v2v_p_placetyp1 : v2v_p_placetyp2);
            }
            else
            {
                $this->targetVillage['villageName'] = ($playerData != NULL ? $villageRow['village_name'] : v2v_p_placetyp3);
            }
            $this->targetVillage['villageId']   = $villageRow['id'];
            $this->targetVillage['playerName']  = ($playerData != NULL ? $playerData['name'] : ($villageRow['is_oasis'] ? v2v_p_monster : ''));
            $this->targetVillage['playerId']    = ($playerData != NULL ? $playerData['id'] : 0);
            $this->targetVillage['troops']      = $renderTroops;
            $this->targetVillage['hasHero']     = (((1 < $this->transferType AND $this->hasHero) AND is_post('_t')) AND intval(post('_t')) == 1);
            $distance                           = getdistance($this->data['rel_x'], $this->data['rel_y'], $this->targetVillage['x'], $this->targetVillage['y'], $this->setupMetadata['map_size'] / 2);
            $this->targetVillage['needed_time'] = intval($distance / $this->_getTheSlowestTroopSpeed($renderTroops) * 3600);
            $this->targetVillage['spy']         = $spyOnly;
        }
        if ($sendTroops)
        {
            $taskType = 0;
            switch ($this->transferType)
            {
                case 1:
                {
                    $taskType = QS_CREATEVILLAGE;
                    break;
                }
                case 2:
                {
                    $taskType = QS_WAR_REINFORCE;
                    break;
                }
                case 3:
                {
                    $taskType = QS_WAR_ATTACK;
                    break;
                }
                case 4:
                {
                    $taskType = QS_WAR_ATTACK_PLUNDER;
                    break;
                }
                default:
                {
                }
            }
            //return null;
            $spyAction = 0;
            if ($spyOnly)
            {
                $taskType  = QS_WAR_ATTACK_SPY;
                $spyAction = ((is_post('spy') AND (intval(post('spy')) == 1 OR intval(post('spy')) == 2)) ? intval(post('spy')) : 1);
                if ($this->onlyOneSpyAction)
                {
                    $spyAction = 1;
                }
            }
            $troopsStr = '';
            foreach ($this->targetVillage['troops'] as $tid => $tnum)
            {
                if ($troopsStr != '')
                {
                    $troopsStr .= ',';
                }
                $troopsStr .= $tid . ' ' . $tnum;
            }
            if ($this->targetVillage['hasHero'])
            {
                $troopsStr .= ',' . $this->data['hero_troop_id'] . ' -1';
            }
            $catapultTargets = $attackOptions1;
            $carryResources  = ($taskType == QS_CREATEVILLAGE ? implode(' ', $this->newVillageResources) : '');
            $procParams      = $troopsStr . '|' . ($this->targetVillage['hasHero'] ? 1 : 0) . '|' . $spyAction . '|' . $catapultTargets . '|' . $carryResources . '|||0';

            $this->load_library('QueueTask', 'newTask', array(
                'taskType' => $taskType,
                'playerId' => $this->player->playerId,
                'executionTime' => $this->targetVillage['needed_time']
            ));
            $this->newTask->villageId   = $this->data['selected_village_id'];
            $this->newTask->toPlayerId  = intval($villageRow['player_id']);
            $this->newTask->toVillageId = $villageRow['id'];
            $this->newTask->procParams  = $procParams;
            $this->newTask->tag         = array(
                'troops' => $this->targetVillage['troops'],
                'hasHero' => $this->targetVillage['hasHero'],
                'resources' => ($taskType == QS_CREATEVILLAGE ? $this->newVillageResources : NULL)
            );
            $this->queueModel->addTask($this->newTask);

            
            $this->is_redirect = TRUE;
            redirect('build?id=39');
            //return null;
        }
        
    }


    public function handleTroopBack()
    {
        $qstr          = '';
        $fromVillageId = 0;
        $toVillageId   = 0;
        $action        = 0;

        if (is_get('d1'))
        {
            $action = 1;
            $qstr   = 'd1=' . intval(get('d1'));
            if (is_get('o'))
            {
                $qstr .= '&o=' . intval(get('o'));
                $fromVillageId = intval(get('o'));
            }
            else
            {
                $fromVillageId = $this->data['selected_village_id'];
            }
            $toVillageId = intval(get('d1'));
        }
        else
        {
            if (is_get('d2'))
            {
                $action        = 2;
                $qstr          = 'd2=' . intval(get('d2'));
                $fromVillageId = $this->data['selected_village_id'];
                $toVillageId   = intval(get('d2'));
            }
            else
            {
                if (is_get('d3'))
                {
                    $action        = 3;
                    $qstr          = 'd3=' . intval(get('d3'));
                    $fromVillageId = intval(get('d3'));
                    $toVillageId   = $this->data['selected_village_id'];
                }
                else
                {
                    $this->is_redirect = TRUE;
                    redirect('build?id=39');
                    //return null;
                }
            }
        }

        $this->backTroopsProperty['queryString'] = $qstr;

        $this->load_model('War', 'm');
        $fromVillageData = $this->m->getVillageData2ById($fromVillageId);
        $toVillageData   = $this->m->getVillageData2ById($toVillageId);
        if (($fromVillageData == NULL OR $toVillageData == NULL))
        {
            $this->is_redirect = TRUE;
            
            redirect('build?id=39');
            //return null;
        }

        $vid                                    = $toVillageId;
        $_backTroopsStr                         = '';
        $this->backTroopsProperty['headerText'] = v2v_p_backtroops;
        $this->backTroopsProperty['action1']    = '<a href="village3?id=' . $fromVillageData['id'] . '">' . $fromVillageData['village_name'] . '</a>';
        $this->backTroopsProperty['action2']    = '<a href="profile?uid=' . $fromVillageData['player_id'] . '">' . v2v_p_troopsinvillagenow . '</a>';
        $column1                                = '';
        $column2                                = '';
        if ($action == 1)
        {
            $_backTroopsStr = $fromVillageData['troops_num'];
            $column1        = 'troops_num';
            $column2        = 'troops_out_num';
        }
        else
        {
            if ($action == 2)
            {
                $this->backTroopsProperty['headerText'] = v2v_p_backcaptivitytroops;
                $_backTroopsStr                         = $fromVillageData['troops_intrap_num'];
                $column1                                = 'troops_intrap_num';
                $column2                                = 'troops_out_intrap_num';
            }
            else
            {
                if ($action == 3)
                {
                    $_backTroopsStr = $toVillageData['troops_out_num'];
                    $vid            = $fromVillageId;
                    $column1        = 'troops_num';
                    $column2        = 'troops_out_num';
                }
            }
        }
        $this->backTroopsProperty['backTroops'] = $this->_getTroopsForVillage($_backTroopsStr, $vid);
        if ($this->backTroopsProperty['backTroops'] == NULL)
        {
            
            $this->is_redirect = TRUE;
            redirect('build?id=39');
            //return null;
        }

        $distance = getdistance($fromVillageData['rel_x'], $fromVillageData['rel_y'], $toVillageData['rel_x'], $toVillageData['rel_y'], $this->setupMetadata['map_size'] / 2);

        if ($_POST)
        {
            $canSend      = FALSE;
            $troopsGoBack = array();
            foreach ($this->backTroopsProperty['backTroops']['troops'] as $tid => $tnum)
            {
                if ((is_post('t') && isset($_POST['t'][$tid])))
                {
                    $selNum = intval($_POST['t'][$tid]);
                    if ($selNum < 0)
                    {
                        $selNum = 0;
                    }
                    if ($tnum < $selNum)
                    {
                        $selNum = $tnum;
                    }
                    $troopsGoBack[$tid] = $selNum;
                    if (0 < $selNum)
                    {
                        $canSend = TRUE;
                        continue;
                    }
                    continue;
                }
                else
                {
                    $troopsGoBack[$tid] = 0;
                    continue;
                }
            }
            $sendTroopsArray = array(
                'troops' => $troopsGoBack,
                'hasHero' => FALSE,
                'heroTroopId' => 0
            );
            $hasHeroTroop    = (($this->backTroopsProperty['backTroops']['hasHero'] AND is_post('_t')) AND intval(post('_t')) == 1);
            if ($hasHeroTroop)
            {
                $sendTroopsArray['hasHero']     = TRUE;
                $sendTroopsArray['heroTroopId'] = $this->backTroopsProperty['backTroops']['heroTroopId'];
                $canSend                        = TRUE;
            }
            if (!$canSend)
            {
                $this->is_redirect = TRUE;
                
                redirect('build?id=39');
                //return null;
            }
            if ((!$this->isGameTransientStopped() AND !$this->isGameOver()))
            {
                $troops1 = $this->_getTroopsAfterReduction($fromVillageData[$column1], $toVillageId, $sendTroopsArray);
                $troops2 = $this->_getTroopsAfterReduction($toVillageData[$column2], $fromVillageId, $sendTroopsArray);
                $this->m->backTroopsFrom($fromVillageId, $column1, $troops1, $toVillageId, $column2, $troops2);
                $timeInSeconds = intval($distance / $this->_getTheSlowestTroopSpeed2($sendTroopsArray) * 3600);
                $procParams    = $this->_getTroopAsString($sendTroopsArray) . '|0||||||1';

                $this->load_library('QueueTask', 'newTask', array(
                    'taskType' => QS_WAR_REINFORCE,
                    'playerId' => intval($fromVillageData['player_id']),
                    'executionTime' => $timeInSeconds
                ));
                $this->newTask->villageId   = $fromVillageId;
                $this->newTask->toPlayerId  = intval($toVillageData['player_id']);
                $this->newTask->toVillageId = $toVillageId;
                $this->newTask->procParams  = $procParams;
                $this->newTask->tag         = array(
                    'troops' => NULL,
                    'hasHero' => FALSE,
                    'resources' => NULL
                );
                $affectCropConsumption      = TRUE;
                if (($fromVillageData['is_oasis'] && trim($toVillageData['village_oases_id']) != ''))
                {
                    $oArr = explode(',', trim($toVillageData['village_oases_id']));
                    foreach ($oArr as $oid)
                    {
                        if ($oid == $fromVillageData['id'])
                        {
                            $affectCropConsumption = FALSE;
                            break;
                        }
                    }
                }
                if ($affectCropConsumption)
                {
                    $this->newTask->tag['troopsCropConsume'] = $this->_getTroopCropConsumption($sendTroopsArray);
                }
                $this->queueModel->addTask($this->newTask);
                
                $this->is_redirect = TRUE;
                redirect('build?id=39');
                //return null;
            }
        }
        else
        {
            $this->backTroopsProperty['time'] = intval($distance / $this->_getTheSlowestTroopSpeed2($this->backTroopsProperty['backTroops']) * 3600);
        }
        
    }


    public function _getTroopCropConsumption($troopsArray)
    {
        $consume = 0;
        foreach ($troopsArray['troops'] as $tid => $tnum)
        {
            $consume += $this->gameMetadata['troops'][$tid]['crop_consumption'] * $tnum;
        }
        if ($troopsArray['hasHero'])
        {
            $consume += $this->gameMetadata['troops'][$troopsArray['heroTroopId']]['crop_consumption'];
        }
        return $consume;
    }


    public function _getTroopAsString($troopsArray)
    {
        $str = '';
        foreach ($troopsArray['troops'] as $tid => $num)
        {
            if ($str != '')
            {
                $str .= ',';
            }
            $str .= $tid . ' ' . $num;
        }
        if ($troopsArray['hasHero'])
        {
            if ($str != '')
            {
                $str .= ',';
            }
            $str .= $troopsArray['heroTroopId'] . ' -1';
        }
        return $str;
    }


    public function _getTroopsAfterReduction($troopString, $targetVillageId, $sendTroopsArray)
    {
        if (trim($troopString) == '')
        {
            return '';
        }
        $reductionTroopsString = '';
        $t_arr                 = explode('|', $troopString);
        foreach ($t_arr as $t_str)
        {
            $t2_arr = explode(':', $t_str);
            if ($t2_arr[0] == $targetVillageId)
            {
                $completelyBacked = TRUE;
                $newTroopStr      = '';
                $t2_arr           = explode(',', $t2_arr[1]);
                foreach ($t2_arr as $t2_str)
                {
                    list($tid, $tnum) = explode(' ', $t2_str);
                    if ($tnum == 0 - 1)
                    {
                        if (!$sendTroopsArray['hasHero'])
                        {
                            if ($newTroopStr != '')
                            {
                                $newTroopStr .= ',';
                            }
                            $newTroopStr .= $tid . ' ' . $tnum;
                            $completelyBacked = FALSE;
                            continue;
                        }
                        continue;
                    }
                    else
                    {
                        if (isset($sendTroopsArray['troops'][$tid]))
                        {
                            $n = $sendTroopsArray['troops'][$tid];
                            if ($n < 0)
                            {
                                $n = 0;
                            }
                            if ($tnum < $n)
                            {
                                $n = $tnum;
                            }
                            $tnum -= $n;
                            if (0 < $tnum)
                            {
                                $completelyBacked = FALSE;
                            }
                        }
                        if ($newTroopStr != '')
                        {
                            $newTroopStr .= ',';
                        }
                        $newTroopStr .= $tid . ' ' . $tnum;
                        continue;
                    }
                }
                if (!$completelyBacked)
                {
                    if ($reductionTroopsString != '')
                    {
                        $reductionTroopsString .= '|';
                    }
                    $reductionTroopsString .= $targetVillageId . ':' . $newTroopStr;
                    continue;
                }
                continue;
            }
            else
            {
                if ($reductionTroopsString != '')
                {
                    $reductionTroopsString .= '|';
                }
                $reductionTroopsString .= $t_str;
                continue;
            }
        }
        return $reductionTroopsString;
    }


    public function _getTroopsForVillage($troopString, $villageId)
    {
        if (trim($troopString) == '')
        {
            return NULL;
        }
        $t_arr = explode('|', $troopString);
        foreach ($t_arr as $t_str)
        {
            $t2_arr = explode(':', $t_str);
            if ($t2_arr[0] == $villageId)
            {
                $troopTable = array(
                    'hasHero' => FALSE,
                    'heroTroopId' => 0,
                    'troops' => array()
                );
                $t2_arr     = explode(',', $t2_arr[1]);
                foreach ($t2_arr as $t2_str)
                {
                    list($tid, $tnum) = explode(' ', $t2_str);
                    if ($tid == 99)
                    {
                        continue;
                    }
                    if ($tnum == 0 - 1)
                    {
                        $troopTable['heroTroopId'] = $tid;
                        $troopTable['hasHero']     = TRUE;
                        continue;
                    }
                    $troopTable['troops'][$tid] = $tnum;
                }
                return $troopTable;
            }
        }
        //return NULL;
    }


    public function _getMaxBuildingLevel($itemId)
    {
        $result = 0;
        foreach ($this->buildings as $villageBuild)
        {
            if (($villageBuild['item_id'] == $itemId AND $result < $villageBuild['level']))
            {
                $result = $villageBuild['level'];
                continue;
            }
        }
        return $result;
    }


    public function _getTheSlowestTroopSpeed2($troopsArray)
    {
        $minSpeed = 0 - 1;
        foreach ($troopsArray['troops'] as $tid => $num)
        {
            if (0 < $num)
            {
                $speed = $this->gameMetadata['troops'][$tid]['velocity'];
                if (($minSpeed == 0 - 1 OR $speed < $minSpeed))
                {
                    $minSpeed = $speed;
                    continue;
                }
                continue;
            }
        }

        if ($troopsArray['hasHero'])
        {
            if ($troopsArray['heroTroopId'] == 0)
            {
                $htid = 1;
            }
            else
            {
                $htid = $troopsArray['heroTroopId'];
            }
            $speed = $this->gameMetadata['troops'][$htid]['velocity'];
            if (($minSpeed == 0 - 1 OR $speed < $minSpeed))
            {
                $minSpeed = $speed;
            }
        }
        $blvl   = $this->_getMaxBuildingLevel(14);
        $factor = ($blvl == 0 ? 100 : $this->gameMetadata['items'][14]['levels'][$blvl - 1]['value']);
        $factor *= $this->gameMetadata['game_speed']*$this->Artefacts();
        return $minSpeed * ($factor / 100);
    }


    public function _getTheSlowestTroopSpeed($troopsArray)
    {
        $minSpeed = 0 - 1;
        foreach ($troopsArray as $tid => $num)
        {
            if (0 < $num)
            {
                $speed = $this->gameMetadata['troops'][$tid]['velocity'];
                if (($minSpeed == 0 - 1 OR $speed < $minSpeed))
                {
                    $minSpeed = $speed;
                    continue;
                }
                continue;
            }
        }

        if ((($this->hasHero AND is_post('_t')) AND intval(post('_t')) == 1))
        {
            if ($this->data['hero_troop_id'] == 0)
            {
                $htid = 1;
            }
            else
            {
                $htid = $this->data['hero_troop_id'];
            }
            $speed = $this->gameMetadata['troops'][$htid]['velocity'];
            if (($minSpeed == 0 - 1 OR $speed < $minSpeed))
            {
                $minSpeed = $speed;
            }
        }
        $blvl   = $this->_getMaxBuildingLevel(14);
        $factor = ($blvl == 0 ? 100 : $this->gameMetadata['items'][14]['levels'][$blvl - 1]['value']);
        $factor *= $this->gameMetadata['game_speed']*$this->Artefacts();
        return $minSpeed * ($factor / 100);
    }

    public function Artefacts()
    {
        $this->load_model('Artefacts', 'A');
        $artLevel = $this->A->Artefacts($this->player->playerId, $this->data['selected_village_id'], 3);
        return $artPower = ($artLevel == 0) ? 1 : (($artLevel == 1) ? 2 : (($artLevel == 2) ? 1.5 : 3));
    }

    public function _canBuildNewVillage()
    {
        $neededCpValue = $totalCpRate = $totalCpValue = 0;

        $this->load_model('Build', 'm');
        $result = $this->m->getVillagesCp($this->data['villages_id']);

        foreach ($result as $row)
        {
            list($cpValue, $cpRate) = explode(' ', $row['cp']);
            $cpValue += $row['elapsedTimeInSeconds'] * ($cpRate / 86400);
            $totalCpRate += $cpRate;
            $totalCpValue += $cpValue;
            $neededCpValue += intval($this->gameMetadata['cp_for_new_village'] / $this->gameMetadata['game_speed']);
        }
        $totalCpValue = floor($totalCpValue);
        
        return $neededCpValue <= $totalCpValue;
    }

    public function __getCoordInRange($map_size, $x)
    {
        if ($map_size <= $x)
        {
            $x -= $map_size;
        }
        else
        {
            if ($x < 0)
            {
                $x = $map_size + $x;
            }
        }
        return $x;
    }


    public function __getVillageId($map_size, $x, $y)
    {
        return $x * $map_size + ($y + 1);
    }


    public function _containBuildingTarget($item_id)
    {
        $i = 0;
        while ($i <= $this->catapultCanAttackLastIndex)
        {
            if ($this->catapultCanAttack[$i] == $item_id)
            {
                return TRUE;
            }
            ++$i;
        }
        return FALSE;
    }

}

?>
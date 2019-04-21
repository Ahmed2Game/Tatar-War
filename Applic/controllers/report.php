<?php
load_game_engine('Auth');
load_game_engine('Report', 'Helper');

class Report_Controller extends AuthController
{
    public $showList = null;
    public $selectedTabIndex = null;
    public $reportData = null;
    public $dataList = null;
    public $playerRie = null;
    public $pageSize = 10;
    public $pageCount = null;
    public $pageIndex = null;

    public function __construct()
    {
        parent::__construct();
        $this->viewFile = 'report';
        $this->viewData['contentCssClass'] = 'reports';
    }

    public function index()
    {
        $this->showList = !(is_get('id') && 0 < intval(get('id')));
        $this->selectedTabIndex = ((((($this->showList && is_get('t')) && is_numeric(get('t'))) && 1 <= intval(get('t'))) && intval(get('t')) <= 4) ? intval(get('t')) : 0);

        $this->load_model('Report', 'm');

        if (!$_POST) {
            if (!$this->showList) {
                $this->selectedTabIndex = 0;
                $reportId = intval(get('id'));
                $result = $this->m->getReport($reportId);
                if ($result) {
                    $readStatus = $result['read_status'];
                    $deleteStatus = $result['delete_status'];
                    $this->reportData = array();
                    $this->reportData['messageDate'] = $result['mdate'];
                    $this->reportData['messageTime'] = $result['mtime'];
                    $this->reportData['from_player_id'] = $from_player_id = intval($result['from_player_id']);
                    $this->reportData['to_player_id'] = $to_player_id = intval($result['to_player_id']);
                    $this->reportData['from_village_id'] = intval($result['from_village_id']);
                    $this->reportData['to_village_id'] = intval($result['to_village_id']);
                    $this->reportData['from_player_name'] = $result['from_player_name'];
                    $this->reportData['to_player_name'] = $result['to_player_name'];
                    $this->reportData['to_village_name'] = $result['to_village_name'];
                    $this->reportData['from_village_name'] = $result['from_village_name'];
                    $this->reportData['rpt_body'] = $result['rpt_body'];
                    $this->reportData['rpt_cat'] = $result['rpt_cat'];
                    $this->reportData['mdate'] = $result['mdate'];
                    $this->reportData['mtime'] = $result['mtime'];
                    $this->reportData['to_player_alliance_id'] = $this->m->getPlayerAllianceId($to_player_id);

                    switch ($this->reportData['rpt_cat']) {
                        case 1:
                            $this->reportData['resources'] = explode(' ', $this->reportData['rpt_body']);
                            break;
                        case 2:
                            list($troopsStr, $this->reportData['cropConsume']) = explode('|', $this->reportData['rpt_body']);
                            $this->reportData['troopsTable'] = array(
                                'troops' => array(),
                                'hasHero' => FALSE
                            );
                            $troopsStrArr = explode(',', $troopsStr);
                            foreach ($troopsStrArr as $t) {
                                list($tid, $tnum) = explode(' ', $t);
                                if ($tnum == 0 - 1) {
                                    $this->reportData['troopsTable']['hasHero'] = TRUE;
                                } else {
                                    $this->reportData['troopsTable']['troops'][$tid] = $tnum;
                                }
                            }
                            break;
                        case 3:
                            $bodyArr = explode('|', $this->reportData['rpt_body']);
                            list($attackTroopsStr, $defenseTableTroopsStr, $total_carry_load, $harvestResources) = $bodyArr;
                            $wallDestructionResult = (isset($bodyArr[4]) ? $bodyArr[4] : '');
                            $catapultResult = (isset($bodyArr[5]) ? $bodyArr[5] : '');
                            $oasisResult = (isset($bodyArr[6]) ? $bodyArr[6] : '');
                            $captureResult = (isset($bodyArr[7]) ? $bodyArr[7] : '');
                            $this->reportData['total_carry_load'] = $total_carry_load;
                            $this->reportData['total_harvest_carry_load'] = 0;
                            $this->reportData['harvest_resources'] = array();
                            $res = explode(' ', $harvestResources);
                            foreach ($res as $r) {
                                $this->reportData['total_harvest_carry_load'] += $r;
                                $this->reportData['harvest_resources'][] = $r;
                            }
                            $attackTroopsStrArr = explode(',', $attackTroopsStr);
                            $this->reportData['attackTroopsTable'] = array(
                                'troops' => array(),
                                'heros' => array(
                                    'number' => 0,
                                    'dead_number' => 0
                                )
                            );
                            $totalAttackTroops_live = 0;
                            $totalAttackTroops_dead = 0;
                            $attackWallDestrTroopId = 0;
                            $attackCatapultTroopId = 0;
                            $kingTroopId = 0;
                            foreach ($attackTroopsStrArr as $s) {
                                list($tid, $num, $deadNum) = explode(' ', $s);
                                $totalAttackTroops_live += $num;
                                $totalAttackTroops_dead += $deadNum;
                                if ((((($tid == 7 || $tid == 17) || $tid == 27) || $tid == 106) || $tid == 57)) {
                                    $attackWallDestrTroopId = $tid;
                                } else {
                                    if ((((($tid == 8 || $tid == 18) || $tid == 28) || $tid == 107) || $tid == 58)) {
                                        $attackCatapultTroopId = $tid;
                                    } else {
                                        if ((((($tid == 9 || $tid == 19) || $tid == 29) || $tid == 108) || $tid == 59)) {
                                            $kingTroopId = $tid;
                                        }
                                    }
                                }
                                if ($tid == 0 - 1) {
                                    $this->reportData['attackTroopsTable']['heros']['number'] = $num;
                                    $this->reportData['attackTroopsTable']['heros']['dead_number'] = $deadNum;
                                }
                                $this->reportData['attackTroopsTable']['troops'][$tid] = array(
                                    'number' => $num,
                                    'dead_number' => $deadNum
                                );
                            }
                            $this->reportData['all_attackTroops_dead'] = $totalAttackTroops_live <= $totalAttackTroops_dead;
                            $this->reportData['defenseTroopsTable'] = array();
                            $troopsTableStrArr = (trim($defenseTableTroopsStr) == '' ? array() : explode('#', $defenseTableTroopsStr));
                            $j = 0 - 1;
                            $deadRate = $alldefenseNum = $alldefenseDeadNum = 0;
                            foreach ($troopsTableStrArr as $defenseTableTroopsStr2) {
                                ++$j;
                                $defenseTroopsStrArr = explode(',', $defenseTableTroopsStr2);
                                $this->reportData['defenseTroopsTable'][$j] = array(
                                    'troops' => array(),
                                    'heros' => array(
                                        'number' => 0,
                                        'dead_number' => 0
                                    )
                                );
                                foreach ($defenseTroopsStrArr as $s) {
                                    list($tid, $num, $deadNum) = explode(' ', $s);
                                    if ($tid == 0 - 1) {
                                        $this->reportData['defenseTroopsTable'][$j]['heros']['number'] = $num;
                                        $this->reportData['defenseTroopsTable'][$j]['heros']['dead_number'] = $deadNum;
                                    }
                                    $this->reportData['defenseTroopsTable'][$j]['troops'][$tid] = array(
                                        'number' => $num,
                                        'dead_number' => $deadNum
                                    );
                                    $alldefenseNum += $num;
                                    $alldefenseDeadNum += $deadNum;
                                }
                            }
                            $deadRate = ($alldefenseNum == 0) ? 0 : round($alldefenseDeadNum / $alldefenseNum * 100);
                            $this->reportData['deadRate'] = $deadRate;
                            if ($captureResult != '') {
                                $wstr = '';
                                if ($captureResult == '+') {
                                    $wstr = report_p_villagecaptured;
                                } else {
                                    $warr = explode('-', $captureResult);
                                    $wstr = report_p_allegiancelowered . ' ' . $warr[0] . ' ' . report_p_to . ' ' . $warr[1];
                                }
                                if ($wstr != '') {
                                    $wstr = '<img src="assets/x.gif" class="unit u' . $kingTroopId . '" align="center" /> ' . $wstr;
                                }
                                $this->reportData['captureResult'] = $wstr;
                            }
                            if ($oasisResult != '') {
                                $wstr = '';
                                if ($oasisResult == '+') {
                                    $wstr = report_p_oasiscaptured;
                                } else {
                                    $warr = explode('-', $oasisResult);
                                    $wstr = report_p_allegiancelowered . ' ' . $warr[0] . ' ' . report_p_to . ' ' . $warr[1];
                                }
                                if ($wstr != '') {
                                    $wstr = '<img src="assets/x.gif" class="unit uhero" align="center" /> ' . $wstr;
                                }
                                $this->reportData['oasisResult'] = $wstr;
                            }
                            if ($wallDestructionResult != '') {
                                $wstr = '';
                                if ($wallDestructionResult == '-') {
                                    $wstr = report_p_wallnotdestr;
                                } else if ($wallDestructionResult == '+') {
                                    $wstr = report_p_nowall;
                                } else {
                                    $warr = explode('-', $wallDestructionResult);
                                    if (intval($warr[1]) == 0) {
                                        $wstr = report_p_walldestr;
                                    } else {
                                        $wstr = report_p_walllowered . ' ' . $warr[0] . ' ' . report_p_to . ' ' . $warr[1];
                                    }
                                }
                                if ($wstr != '') {
                                    $wstr = '<img src="assets/x.gif" class="unit u' . $attackWallDestrTroopId . '" align="center" /> ' . $wstr;
                                }
                                $this->reportData['wallDestructionResult'] = $wstr;
                            }
                            if ($catapultResult != '') {
                                $bdestArr = array();
                                if ($catapultResult == '+') {
                                    $bdestArr[] = '<img src="assets/x.gif" class="unit u' . $attackCatapultTroopId . '" align="center" /> ' . report_p_totallydestr;
                                } else {
                                    $catapultResultArr = explode('#', $catapultResult);
                                    foreach ($catapultResultArr as $catapultResultInfo) {
                                        list($itemId, $fromLevel, $toLevel) = explode(' ', $catapultResultInfo);
                                        if ($toLevel == 0 - 1) {
                                            $bdestArr[] = '<img src="assets/x.gif" class="unit u' . $attackCatapultTroopId . '" align="center" /> ' . report_p_notdestr . ' ' . constant('item_' . $itemId);
                                        } elseif ($toLevel == 0) {
                                            $bdestArr[] = '<img src="assets/x.gif" class="unit u' . $attackCatapultTroopId . '" align="center" /> ' . report_p_wasdestr . ' ' . constant('item_' . $itemId);
                                        } else {
                                            $bdestArr[] = '<img src="assets/x.gif" class="unit u' . $attackCatapultTroopId . '" align="center" /> ' . report_p_waslowered . ' ' . constant('item_' . $itemId) . ' ' . report_p_fromlevel . ' ' . $fromLevel . ' ' . report_p_to . ' ' . $toLevel;
                                        }
                                    }
                                }
                                $this->reportData['buildingDestructionResult'] = $bdestArr;
                            }
                            break;
                        case 4:

                            list($attackTroopsStr, $defenseTableTroopsStr, $harvestResources, $harvestInfo, $spyType) = explode('|', $this->reportData['rpt_body']);
                            if ((trim($harvestResources) != '' && $spyType == 1)) {
                                $this->reportData['harvest_resources'] = explode(' ', trim($harvestResources));
                            }
                            if ((trim($harvestInfo) != '' && $spyType == 2)) {
                                list($itemId, $level) = explode(' ', $harvestInfo);
                                $this->reportData['harvest_info'] = constant('item_' . $itemId) . ' ' . level_lang . ' ' . $level;
                            }
                            $this->reportData['all_spy_dead'] = FALSE;
                            if ($spyType == 3) {
                                $this->reportData['all_spy_dead'] = TRUE;
                                $this->reportData['harvest_info'] = report_p_allkilled;
                            }
                            $attackTroopsStrArr = explode(',', $attackTroopsStr);
                            $this->reportData['attackTroopsTable'] = array(
                                'troops' => array(),
                                'heros' => array(
                                    'number' => 0,
                                    'dead_number' => 0
                                )
                            );
                            foreach ($attackTroopsStrArr as $s) {
                                list($tid, $num, $deadNum) = explode(' ', $s);
                                if ($tid == 0 - 1) {
                                    $this->reportData['attackTroopsTable']['heros']['number'] = $num;
                                    $this->reportData['attackTroopsTable']['heros']['dead_number'] = $deadNum;
                                }
                                $this->reportData['attackTroopsTable']['troops'][$tid] = array(
                                    'number' => $num,
                                    'dead_number' => $deadNum
                                );
                            }
                            $this->reportData['defenseTroopsTable'] = array();
                            $troopsTableStrArr = (trim($defenseTableTroopsStr) == '' ? array() : explode('#', $defenseTableTroopsStr));
                            $j = 0 - 1;
                            foreach ($troopsTableStrArr as $defenseTableTroopsStr2) {
                                ++$j;
                                $defenseTroopsStrArr = explode(',', $defenseTableTroopsStr2);
                                $this->reportData['defenseTroopsTable'][$j] = array(
                                    'troops' => array(),
                                    'heros' => array(
                                        'number' => 0,
                                        'dead_number' => 0
                                    )
                                );
                                foreach ($defenseTroopsStrArr as $s) {
                                    list($tid, $num, $deadNum) = explode(' ', $s);
                                    if ($tid == 0 - 1) {
                                        $this->reportData['defenseTroopsTable'][$j]['heros']['number'] = $num;
                                        $this->reportData['defenseTroopsTable'][$j]['heros']['dead_number'] = $deadNum;
                                    }
                                    $this->reportData['defenseTroopsTable'][$j]['troops'][$tid] = array(
                                        'number' => $num,
                                        'dead_number' => $deadNum
                                    );
                                }
                            }
                            break;
                        case 5:
                            {
                                $this->hasHero = FALSE;
                                list($troop, $tovillg) = explode('|', $this->reportData['rpt_body']);
                                $this->tovillg = $tovillg;
                                $this->toorp = $troop;
                                $a_arr = explode(',', $troop);
                                foreach ($a_arr as $a2_arr) {
                                    list($tid, $num, $deadNum) = explode(' ', $a2_arr);
                                    if ($tid = 0 - 1) {
                                        $this->hasHero = TRUE;
                                    }
                                }

                                $this->viewData['tovillg'] = $this->tovillg;
                                $this->viewData['toorp'] = $this->toorp;
                                $this->viewData['hasHero'] = $this->hasHero;
                                $this->viewData['a_arr'] = $a_arr;
                                break;
                            }
                        case 6:
                            {
                                $this->hasHero = FALSE;
                                $a_arr = explode(',', $this->reportData['rpt_body']);
                                foreach ($a_arr as $a2_arr) {
                                    list($tid, $num, $deadNum) = explode(' ', $a2_arr);
                                    if ($tid = 0 - 1) {
                                        $this->hasHero = TRUE;
                                    }
                                }
                                $this->viewData['toorp'] = $this->reportData['rpt_body'];
                                $this->viewData['hasHero'] = $this->hasHero;
                                $this->viewData['a_arr'] = $a_arr;
                            }
                    }

                    ## view
                    $this->viewData['getVillageName'] = $this->getVillageName($this->reportData['from_player_id'], $this->reportData['from_village_name']);
                    $this->viewData['getreportactiontext'] = ReportHelper::getreportactiontext($this->reportData['rpt_cat']);
                    $this->viewData['getVillageName_to'] = $this->getVillageName($this->reportData['to_player_id'], $this->reportData['to_village_name']);
                    $this->viewData['reportData'] = $this->reportData;
                    $this->viewData['player'] = $this->player;
                    $this->viewData['data'] = $this->data;

                    $isDeleted = FALSE;
                    if (!$isDeleted) {
                        $canOpenReport = TRUE;
                        if (($this->player->playerId != $from_player_id && $this->player->playerId != $to_player_id)) {
                            $canOpenReport = ($this->data['player_type'] == PLAYERTYPE_ADMIN || $this->data['player_type'] == PLAYERTYPE_HUNTER || (0 < intval($this->data['alliance_id']) && ($this->data['alliance_id'] == $this->m->getPlayerAllianceId($to_player_id) || $this->data['alliance_id'] == $this->m->getPlayerAllianceId($from_player_id))));
                        }
                        if ($canOpenReport) {
                            if (!$this->player->isSpy) {
                                if ($to_player_id == $this->player->playerId) {
                                    if (($readStatus == 0 || $readStatus == 2)) {
                                        $this->m->markReportAsReaded($this->player->playerId, $to_player_id, $reportId, $readStatus);
                                        --$this->data['new_report_count'];
                                    }
                                } else if ($from_player_id == $this->player->playerId) {
                                    if (($readStatus == 0 || $readStatus == 1)) {
                                        $this->m->markReportAsReaded($this->player->playerId, $to_player_id, $reportId, $readStatus);
                                        --$this->data['new_report_count'];
                                    }
                                }
                            }
                        } else {
                            $this->showList = TRUE;
                        }
                    } else {
                        $this->showList = TRUE;
                    }
                    unset($result);
                } else {
                    $this->showList = TRUE;
                }
            }
        } else {
            if (is_post('dr')) {
                if (is_post('dr')) {
                    foreach ($_POST['dr'] as $reportId) {
                        if ($this->m->deleteReport($this->player->playerId, $reportId)) {
                            --$this->data['new_report_count'];
                        }
                    }
                }
            }
        }

        if ($this->showList) {
            $rowsCount = $this->m->getReportListCount($this->player->playerId, $this->selectedTabIndex, $this->player->isSpy);
            $this->pageCount = (0 < $rowsCount ? ceil($rowsCount / $this->pageSize) : 1);
            $this->pageIndex = (((is_get('p') && is_numeric(get('p'))) && intval(get('p')) < $this->pageCount) ? intval(get('p')) : 0);
            $this->dataList = $this->m->getReportList($this->player->playerId, $this->selectedTabIndex, $this->pageIndex, $this->pageSize, $this->player->isSpy);

            $dataListArray = array();
            foreach ($this->dataList as $key => $res) {
                $isAttack = $res['from_player_id'] == $this->player->playerId;
                $rptRelativeResult = ReportHelper::getreportresultrelative($res['rpt_result'], $isAttack);
                $btext = ReportHelper::getreportresulttext($rptRelativeResult);
                $_rptResultCss = $rptRelativeResult == 100 ? 10 : $rptRelativeResult;

                $dataListArray[$key] = array(
                    'id' => $res['id'],
                    'mdate' => $res['mdate'],
                    'is_readed' => $res['is_readed'],
                    'btext' => $btext,
                    '_rptResultCss' => $_rptResultCss
                );
                if ($res['rpt_cat'] != 5 && $res['rpt_cat'] != 6) {
                    $dataListArray[$key]['rpt_cat'] = $this->getVillageName($res['from_player_id'], $res['from_village_name']) . ' ';
                    $dataListArray[$key]['rpt_cat'] .= ReportHelper::getreportactiontext($res['rpt_cat']);
                    $dataListArray[$key]['rpt_cat'] .= ' ' . $this->getVillageName($res['to_player_id'], $res['to_village_name']);
                } elseif ($res['rpt_cat'] == 6) {
                    $dataListArray[$key]['rpt_cat'] = ReportHelper::getreportactiontext($res['rpt_cat']);
                    $dataListArray[$key]['rpt_cat'] .= ' ' . $this->getVillageName($res['from_player_id'], $res['from_village_name']);
                } else {
                    list($troop, $tovillg) = explode('|', $res['rpt_body']);
                    $dataListArray[$key]['rpt_cat'] = ReportHelper::getreportactiontext($res['rpt_cat']);
                    $dataListArray[$key]['rpt_cat'] .= ' ' . $tovillg;

                    $this->viewData['tovillg'] = $tovillg;
                }
            }
            $this->viewData['dataListArray'] = $dataListArray;

            if (0 < $this->data['new_report_count']) {
                $this->data['new_report_count'] = $this->m->syncReports($this->player->playerId);
            }

            $this->viewData['getPreviousLink'] = $this->getPreviousLink();
            $this->viewData['getNextLink'] = $this->getNextLink();
        }


        ## Pre-rendering
        if (is_get('id')) {
            $this->viewData['villagesLinkPostfix'] .= '&id=' . intval(get('id'));
        }
        if (is_get('p')) {
            $this->viewData['villagesLinkPostfix'] .= '&p=' . intval(get('p'));
        }
        if (0 < $this->selectedTabIndex) {
            $this->viewData['villagesLinkPostfix'] .= '&t=' . $this->selectedTabIndex;
        }

        ## View
        $this->viewData['player'] = $this->player;
        $this->viewData['showList'] = $this->showList;
        $this->viewData['selectedTabIndex'] = $this->selectedTabIndex;
        $this->viewData['reportData'] = $this->reportData;
        $this->viewData['playerRie'] = $this->playerRie;
        $this->viewData['pageCount'] = $this->pageCount;
        $this->viewData['pageIndex'] = $this->pageIndex;
        $this->viewData['dataList'] = $this->dataList;
        unset($this->dataList);
    }


    public function getVillageName($playerId, $villageName)
    {
        return (0 < intval($playerId) ? $villageName : '<span class="none">[?]</span>');
    }

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
        $link = 'report?' . $link;
        return '<a href="' . $link . '">' . $text . '</a>';
    }


    public function getPreviousLink()
    {
        $text = '«';
        if ($this->pageIndex == 0) {
            return $text;
        }
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
        if ($link != '') {
            $link = '?' . $link;
        }
        $link = 'report' . $link;
        return '<a href="' . $link . '">' . $text . '</a>';
    }
}


?>
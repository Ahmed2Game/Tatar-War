<?php
load_game_engine('Auth');

class Analytics_Controller extends AuthController
{
    var $selectedTabIndex = null;
    var $selectedRank = null;
    var $dataList = null;
    var $pageSize = 20;
    var $pageCount = null;
    var $pageIndex = null;
    var $generalData = null;
    var $top10Result = null;
    var $isAdmin = FALSE;
    var $adminActionMessage = '';
    var $_tb = null;
    var $tatarRaised = null;

    public function __construct()
    {
        parent::__construct();
        $this->viewFile = 'statistics';
        $this->viewData['contentCssClass'] = 'statistics';
    }

    public function index()
    {
        $this->load_model('Statistics', 'm');
        $this->selectedTabIndex = ((((is_get('t') && is_numeric(get('t'))) && 0 <= intval(get('t'))) && intval(get('t')) <= 11) ? intval(get('t')) : 0);
        $this->isAdmin = $this->data['player_type'] == PLAYERTYPE_ADMIN;
        $this->_tb = (is_get('tb') ? intval(get('tb')) : 0);
        $this->tatarRaised = $this->m->tatarRaised();
        if (($this->selectedTabIndex == 11 && !$this->tatarRaised)) {
            $this->selectedTabIndex = 0;
        }
        $this->selectedRank = 0;
        if ($this->selectedTabIndex == 0) {
            if (is_post('name') && is_post('rank') || is_post('rank')) {
                if (trim(post('name')) != '') {
                    $this->selectedRank = intval($this->m->getPlayerRankByName(trim(post('name')), $this->_tb));
                } else if (0 < intval(post('rank'))) {
                    $this->selectedRank = intval(post('rank'));
                }
            } else if (!is_get('p')) {
                $this->selectedRank = ((0 < $this->_tb && $this->data['tribe_id'] != $this->_tb) ? 1 : intval($this->m->getPlayerRankById($this->player->playerId, $this->_tb)));
            }
            if ($this->isAdmin) {
                if ((is_get('_jsdue') && 0 < intval(get('_jsdue')))) {
                    $this->m->togglePlayerStatus(intval(get('_jsdue')));
                    $this->adminActionMessage = statistics_p_playerstatusch;
                }
            }
        } else {
            if ($this->selectedTabIndex == 1) {
                if (is_post('rank') && is_post('name')) {
                    if (trim(post('name')) != '') {
                        $this->selectedRank = intval($this->m->getAllianceRankByName(trim(post('name'))));
                    } else if (0 < intval(post('rank'))) {
                        $this->selectedRank = intval(post('rank'));
                    }
                } else {
                    if (!is_get('p')) {
                        $this->selectedRank = intval($this->m->getAllianceRankById(intval($this->data['alliance_id'])));
                    }
                }
            } else {
                if ($this->selectedTabIndex == 2) {
                    if (is_post('rank') && is_post('name')) {
                        if (trim(post('name')) != '') {
                            $this->selectedRank = intval($this->m->getVillageRankByName(trim(post('name'))));
                        } else if (0 < intval(post('rank'))) {
                            $this->selectedRank = intval(post('rank'));
                        }
                    } else if (!is_get('p')) {
                        $this->selectedRank = intval($this->m->getVillageRankById($this->data['selected_village_id']));
                    }
                } else {
                    if ($this->selectedTabIndex == 3) {
                        if (is_post('rank') && is_post('name')) {
                            if (trim(post('name')) != '') {
                                $this->selectedRank = intval($this->m->getHeroRankByName(trim(post('name'))));
                            } else if (0 < intval(post('rank'))) {
                                $this->selectedRank = intval(post('rank'));
                            }
                        } else if (!is_get('p')) {
                            $this->selectedRank = intval($this->m->getHeroRankById($this->player->playerId));
                        }
                    } else {
                        if (($this->selectedTabIndex == 6 || $this->selectedTabIndex == 7)) {
                            if (is_post('rank') && is_post('name')) {
                                if (trim(post('name')) != '') {
                                    $this->selectedRank = intval($this->m->getPlayersPointsByName(trim(post('name')), $this->selectedTabIndex == 6));
                                } else {
                                    if (0 < intval(post('rank'))) {
                                        $this->selectedRank = intval(post('rank'));
                                    }
                                }
                            } else {
                                if (!is_get('p')) {
                                    $this->selectedRank = intval($this->m->getPlayersPointsById($this->player->playerId, $this->selectedTabIndex == 6));
                                }
                            }
                        } else {
                            if (($this->selectedTabIndex == 9 || $this->selectedTabIndex == 10)) {
                                if (is_post('rank') && is_post('name')) {
                                    if (trim(post('name')) != '') {
                                        $this->selectedRank = intval($this->m->getAlliancePointsRankByName(trim(post('name')), $this->selectedTabIndex == 9));
                                    } else if (0 < intval(post('rank'))) {
                                        $this->selectedRank = intval(post('rank'));
                                    }
                                }
                            } else if (!is_get('p')) {
                                $this->selectedRank = intval($this->m->getAlliancePointsRankById(intval($this->data['alliance_id']), $this->selectedTabIndex == 9));
                            }
                        }
                    }
                }
            }
        }
        if ($this->selectedTabIndex == 0) {
            $rowsCount = $this->m->getPlayerListCount($this->_tb);
            $this->pageCount = (0 < $rowsCount ? ceil($rowsCount / $this->pageSize) : 1);
            $this->pageIndex = (0 < $this->selectedRank ? floor(($this->selectedRank - 1) / $this->pageSize) : ((is_get('p') && is_numeric(get('p'))) ? intval(get('p')) : 0));
            if ($this->pageCount <= $this->pageIndex) {
                $this->pageIndex = $this->pageCount - 1;
                $this->selectedRank = 0 - 1;
            }
            $this->dataList = $this->m->getPlayerList($this->pageIndex, $this->pageSize, $this->_tb);
        } else {
            if ($this->selectedTabIndex == 1) {
                $rowsCount = $this->m->getAllianceListCount();
                $this->pageCount = (0 < $rowsCount ? ceil($rowsCount / $this->pageSize) : 1);
                $this->pageIndex = (0 < $this->selectedRank ? floor(($this->selectedRank - 1) / $this->pageSize) : ((is_get('p') && is_numeric(get('p'))) ? intval(get('p')) : 0));
                if ($this->pageCount <= $this->pageIndex) {
                    $this->pageIndex = $this->pageCount - 1;
                    $this->selectedRank = 0 - 1;
                }
                $this->dataList = $this->m->getAlliancesList($this->pageIndex, $this->pageSize);
            } else {
                if ($this->selectedTabIndex == 2) {
                    $rowsCount = $this->m->getVillageListCount();
                    $this->pageCount = (0 < $rowsCount ? ceil($rowsCount / $this->pageSize) : 1);
                    $this->pageIndex = (0 < $this->selectedRank ? floor(($this->selectedRank - 1) / $this->pageSize) : ((is_get('p') && is_numeric(get('p'))) ? intval(get('p')) : 0));
                    if ($this->pageCount <= $this->pageIndex) {
                        $this->pageIndex = $this->pageCount - 1;
                        $this->selectedRank = 0 - 1;
                    }
                    $this->dataList = $this->m->getVillagesList($this->pageIndex, $this->pageSize);
                } else {
                    if ($this->selectedTabIndex == 3) {
                        $rowsCount = $this->m->getHeroListCount();
                        $this->pageCount = (0 < $rowsCount ? ceil($rowsCount / $this->pageSize) : 1);
                        $this->pageIndex = (0 < $this->selectedRank ? floor(($this->selectedRank - 1) / $this->pageSize) : ((is_get('p') && is_numeric(get('p'))) ? intval(get('p')) : 0));
                        if ($this->pageCount <= $this->pageIndex) {
                            $this->pageIndex = $this->pageCount - 1;
                            $this->selectedRank = 0 - 1;
                        }
                        $this->dataList = $this->m->getHerosList($this->pageIndex, $this->pageSize);
                    } else {
                        if ($this->selectedTabIndex == 4) {
                            global $gameConfig;
                            $this->generalData = $this->m->getGeneralSummary();
                            $this->load_model('Global', 'G');
                            $serverstart = $this->G->getServerStartTime();
                            $this->viewData['starttime'] = secondsToString($serverstart['server_start_time']);
                            $this->viewData['tatardate'] = ($gameConfig['settings']['over'] * 3600 * 24) - $serverstart['server_start_time'];
                            $this->viewData['tatartime'] = secondsToString($this->viewData['tatardate']);
                            $this->viewData['Artdate'] = ($gameConfig['settings']['Artefacts'] * 3600 * 24) - $serverstart['server_start_time'];
                            $this->viewData['Arttime'] = secondsToString($this->viewData['Artdate']);
                            $this->viewData['settings'] = $gameConfig['settings'];
                        } else {
                            if (($this->selectedTabIndex == 6 || $this->selectedTabIndex == 7)) {
                                $rowsCount = $this->m->getPlayersPointsListCount();
                                $this->pageCount = (0 < $rowsCount ? ceil($rowsCount / $this->pageSize) : 1);
                                $this->pageIndex = (0 < $this->selectedRank ? floor(($this->selectedRank - 1) / $this->pageSize) : ((is_get('p') && is_numeric(get('p'))) ? intval(get('p')) : 0));
                                if ($this->pageCount <= $this->pageIndex) {
                                    $this->pageIndex = $this->pageCount - 1;
                                    $this->selectedRank = 0 - 1;
                                }
                                $this->dataList = $this->m->getPlayersPointsList($this->pageIndex, $this->pageSize, $this->selectedTabIndex == 6);
                            } else {
                                if (($this->selectedTabIndex == 9 || $this->selectedTabIndex == 10)) {
                                    $rowsCount = $this->m->getAlliancePointsListCount();
                                    $this->pageCount = (0 < $rowsCount ? ceil($rowsCount / $this->pageSize) : 1);
                                    $this->pageIndex = (0 < $this->selectedRank ? floor(($this->selectedRank - 1) / $this->pageSize) : ((is_get('p') && is_numeric(get('p'))) ? intval(get('p')) : 0));
                                    if ($this->pageCount <= $this->pageIndex) {
                                        $this->pageIndex = $this->pageCount - 1;
                                        $this->selectedRank = 0 - 1;
                                    }
                                    $this->dataList = $this->m->getAlliancePointsList($this->pageIndex, $this->pageSize, $this->selectedTabIndex == 9);
                                } else {
                                    if (($this->selectedTabIndex == 5 || $this->selectedTabIndex == 8)) {
                                        $this->top10Result = array(
                                            'URL' => ($this->selectedTabIndex == 5 ? 'profile?uid=' : 'alliance?id='),
                                            'TARGETNAME' => ($this->selectedTabIndex == 5 ? $this->data['name'] : $this->data['alliance_name']),
                                            'TARGETID' => ($this->selectedTabIndex == 5 ? $this->player->playerId : intval($this->data['alliance_id'])),
                                            'TARGEPOINT_ATTACK' => ($this->selectedTabIndex == 5 ? $this->data['week_attack_points'] : $this->m->getAlliancePoint(intval($this->data['alliance_id']), 'week_attack_points')),
                                            'TARGEPOINT_DEFENSE' => ($this->selectedTabIndex == 5 ? $this->data['week_defense_points'] : $this->m->getAlliancePoint(intval($this->data['alliance_id']), 'week_defense_points')),
                                            'TARGEPOINT_DEV' => ($this->selectedTabIndex == 5 ? $this->data['week_dev_points'] : $this->m->getAlliancePoint(intval($this->data['alliance_id']), 'week_dev_points')),
                                            'TARGEPOINT_THIEF' => ($this->selectedTabIndex == 5 ? $this->data['week_thief_points'] : $this->m->getAlliancePoint(intval($this->data['alliance_id']), 'week_thief_points')),
                                            'ATTACK' => $this->m->getTop10($this->selectedTabIndex == 5, 'week_attack_points'),
                                            'DEFENSE' => $this->m->getTop10($this->selectedTabIndex == 5, 'week_defense_points'),
                                            'DEV' => $this->m->getTop10($this->selectedTabIndex == 5, 'week_dev_points'),
                                            'THIEF' => $this->m->getTop10($this->selectedTabIndex == 5, 'week_thief_points')
                                        );
                                    } else if ($this->selectedTabIndex == 11) {
                                        $DataList = $this->m->getTatarVillagesList();
                                        $this->dataList = array();
                                        foreach ($DataList as $value) {
                                            $this->dataList[] = array(
                                                'id' => $value['id'],
                                                'player_id' => $value['player_id'],
                                                'player_name' => $value['player_name'],
                                                'village_name' => $value['village_name'],
                                                'alliance_id' => $value['alliance_id'],
                                                'alliance_name' => $value['alliance_name'],
                                                'buildings' => $this->getWonderLandLevel($value['buildings'])
                                            );
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        ###############View################
        $this->viewData['selectedTabIndex'] = $this->selectedTabIndex;
        $this->viewData['tatarRaised'] = $this->tatarRaised;
        $this->viewData['isAdmin'] = $this->isAdmin;
        $this->viewData['_tb'] = $this->_tb;
        $this->viewData['pageIndex'] = $this->pageIndex;
        $this->viewData['adminActionMessage'] = $this->adminActionMessage;
        $this->viewData['dataList'] = $this->dataList;
        $this->viewData['pageSize'] = $this->pageSize;
        $this->viewData['getPreviousLink'] = $this->getPreviousLink();
        $this->viewData['getNextLink'] = $this->getNextLink();
        $this->viewData['generalData'] = $this->generalData;
        $this->viewData['top10Result'] = $this->top10Result;
        $this->viewData['selectedRank'] = $this->selectedRank;
        unset($this->dataList);
        if (0 <= $this->selectedTabIndex) {
            $this->viewData['villagesLinkPostfix'] .= '&t=' . $this->selectedTabIndex;
        }
    }

    function getNextLink()
    {
        $text = text_nextpage_lang . ' »';
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
        if (0 < $this->_tb) {
            $link .= '&tb=' . $this->_tb;
        }
        $link = 'analytics?' . $link;
        return '<a href="' . $link . '">' . $text . '</a>';
    }

    function getPreviousLink()
    {
        $text = '« ' . text_prevpage_lang;
        if ($this->pageIndex == 0) {
            return $text;
        }
        $link = '';
        if (0 < $this->selectedTabIndex) {
            $link .= 't=' . $this->selectedTabIndex;
        }
        if (0 < $this->pageIndex) {
            if ($link != '') {
                $link .= '&';
            }
            $link .= 'p=' . ($this->pageIndex - 1);
        }
        if (0 < $this->_tb) {
            if ($link != '') {
                $link .= '&';
            }
            $link .= 'tb=' . $this->_tb;
        }
        if ($link != '') {
            $link = '?' . $link;
        }
        $link = 'analytics' . $link;
        return '<a href="' . $link . '">' . $text . '</a>';
    }

    function getWonderLandLevel($builds)
    {
        $b_arr = explode(',', $builds);
        $indx = 0;
        foreach ($b_arr as $b_str) {
            ++$indx;
            $b2 = explode(' ', $b_str);
            $itemId = $b2[0];
            $level = $b2[1];
            if ($itemId == 40) {
                return $level;
            }
        }
        return 0;
    }
}

?>
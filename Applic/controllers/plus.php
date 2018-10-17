<?php
 load_game_engine('Auth');
class Plus_Controller extends AuthController
{

    var $packageIndex = -1;
    var $plusTable = NULL;
    var $pageSize = 15;
    var $pageCount = null;
    var $pageIndex = null;
    var $dataList = null;
    var $errorTable = null;

    public function __construct()
    {
        parent::__construct();
        $this->viewFile = 'plus';
        $this->viewData['contentCssClass'] = 'plus';
        $this->plusTable = $this->gameMetadata['plusTable'];
        $i = 0;
        $c = sizeof( $this->plusTable );
        while ( $i < $c )
        {
            if ( 0 < $this->plusTable[$i]['time'] )
            {
                $this->plusTable[$i]['time'] = ceil( $this->plusTable[$i]['time'] / $this->gameMetadata['game_speed'] );
            }
            ++$i;
        }
        $this->viewData['plusTable'] = $this->plusTable;
    }

    public function index()
    {
        global $gameConfig;
        $this->selectedTabIndex = is_get('t') && is_numeric( get('t') ) && 0 <= intval( get('t') ) && intval( get('t') ) <= 5 ? intval( get('t') ) : 0;
        $this->viewData['selectedTabIndex'] = $this->selectedTabIndex;
        $this->viewData['playerId'] = $this->player->playerId;
        $this->load_model('Plus', 'm');
        $this->load_model('Servers', 'S');


        if ( $this->selectedTabIndex == 0 )
        {
            $id = get('id');
            $this->packageIndex = (is_get('id') && get('id') == "G2A") ? get('id') : '';
            $this->viewData['packageIndex'] = $this->packageIndex;
            $this->viewData['packages'] = $this->S->GetPackages();
			$this->viewData['G2A'] = json_decode($this->S->GetSettings("G2A"), true);
            $this->viewData['Domain'] = URL;
        }
        else if ( $this->selectedTabIndex == 2 && is_get('a') && is_get('k') && get('k') == $this->data['update_key'] && $this->plusTable[intval( get('a') )]['cost'] <= $this->data['gold_num'] && !$this->isGameTransientStopped() && !$this->isGameOver() )
        {
            switch ( intval( get('a') ) )
            {
            case 0 :
            case 1 :
            case 2 :
            case 3 :
            case 4 :
                $taskType = constant( "QS_PLUS".( intval( $_GET['a'] ) + 1 ) );
                $this->load_library('QueueTask', 'newTask',
                    array(  'taskType'      => $taskType,
                            'playerId'      => $this->player->playerId,
                            'executionTime' => $this->plusTable[intval( $_GET['a'] )]['time'] * 86400
                        )
                    );
                if ( 0 < intval( get('a') ) )
                {
                    $this->newTask->villageId = $this->data['selected_village_id'];
                }
                else
                {
                    $this->newTask->villageId = "";
                }
                $this->newTask->tag = $this->plusTable[intval( get('a') )]['cost'];
                $this->queueModel->addTask( $this->newTask );
                break;
            case 5 :
            case 7 :
            case 8 :
                $this->queueModel->finishTasks( $this->player->playerId, $this->plusTable[intval( get('a') )]['cost'], intval( get('a') ) == 7, intval( get('a') ) == 8 );
                if (!is_get('_a1_'))
                {
                    $this->load_model('Queuejob', 'qj');
                    $this->qj->processQueue(2, $this->player->playerId);
                }
                break;
            case 9:
                if (intval(get('gold')) <= $this->data['gold_num'] and intval(get('gold')) > 0)
                {
                    foreach ($this->resources as $key => $value)
                    {
                        $this->resources[$key]['current_value'] += intval(get('gold'))*$this->plusTable[9]['time'];
                        if ($this->resources[$key]['current_value'] > $value['store_max_limit'])
                        {
                            $this->resources[$key]['current_value'] = $value['store_max_limit'];
                        }
                    }
                    $this->queueModel->_updateVillage(FALSE);
                    $this->m->DeletPlayerGold($this->player->playerId, intval(get('gold')));
                    $this->data['gold_num'] -= intval(get('gold'));
                }
                break;
            }
            
        }
        elseif ($this->selectedTabIndex == 3)
        {
            $this->dataList = $this->m->InviteBy($this->player->playerId);
            $found = false;
            if ($this->dataList)
            {
                $found = true;
            }
            $this->viewData['dataList'] = $this->dataList;
            $this->viewData['found'] = $found;
            $userdata = $this->m->getPlayerDataById ($this->player->playerId);
            foreach ($this->dataList as $value)
            {
                $ip_found = FALSE;
                $a_arr = explode(',', preg_replace('/A/', '', $value['ip_his']));
                $u_arr = explode(',', preg_replace('/A/', '', $userdata['ip_his']));
                foreach ($a_arr as $a_atr)
                {
                    if(in_array($a_atr, $u_arr))
                    {
                        $ip_found = TRUE;
                        break;
                    }
                }
                if($value['total_people_count'] >= 1500 && $value['invite_by'] == $this->player->playerId && $value['show_ref'] == 0 && $ip_found == FALSE )
                {
                    $this->m->incrementPlayerGold($this->player->playerId, $gameConfig['settings']['invinteGold']);
                    $this->m->PlayerRef($value['id']);
                }
            }
            unset($this->dataList);
            
        }
        elseif ($this->selectedTabIndex == 4)
        {
            if ( is_post('name') )
            {
                $playerName = trim( post('name') );
                $ifplayer = $this->m->getPlayerDataByName ($playerName);
                if ( $ifplayer == NULL )
                {
                    $this->errorTable = LANGUI_TRANS_T6;
                }
                else
                {
                    $playerdata = $this->m->getPlayerDataById( $this->player->playerId );
                    $passcon = md5 ( post('pass') );
                    if ( $playerdata['pwd'] != $passcon )
                    {
                        $this->errorTable = LANGUI_TRANS_T7;
                    }
                    else
                    {
                        if ( $playerdata['total_people_count'] < $gameConfig['settings']['pepole'] )
                        {
                            $this->errorTable = LANGUI_TRANS_T10;
                        }
                        else
                        {
                            $golds = intval( post('gold') );
                            if ( $golds <= 0 )
                            {
                                $this->errorTable = LANGUI_TRANS_T8;
                            }
                            else
                            {
                                $gold = $this->data['gold_num'];
                                if ($gold < $golds)
                                {
                                    $this->errorTable = LANGUI_TRANS_T9;
                                }
                                else
                                {
                                    $this->data['gold_num'] -= $golds;
                                    $this->data['gold_buy'] -= $golds;
                                    $this->m->DeletPlayerGold( $this->player->playerId, $golds );
                                    $this->m->GivePlayerGold( $playerName, $golds );
                                    $this->m->InsertGoldTransLog($this->data['name'], $playerName, $golds);
                                    $this->errorTable = LANGUI_TRANS_T12;
                                }
                            }
                        }
                    }
                }
            }
            $rowsCount       = $this->m->goldTransPyName( $this->data['name'] );
            $this->pageCount = (0 < $rowsCount ? ceil($rowsCount / $this->pageSize) : 1);
            $this->pageIndex = ((is_get('p') && is_numeric(get('p'))) ? intval(get('p')) : 0);
            if ($this->pageCount <= $this->pageIndex)
            {
                $this->pageIndex    = $this->pageCount - 1;
                $this->selectedRank = 0 - 1;
            }
            $this->dataList = $this->m->GoldTranshis($this->data['name'],$this->pageIndex,$this->pageSize);
            $found = false;
            $rowIndex = 0;
            $dataList = array();
            foreach($this->dataList as $value)
            {
                ++$rowIndex;
                $rank = $rowIndex + $this->pageIndex * $this->pageSize;
                $userid = $this->m->getPlayerDataByName($value['to_player']);
                $fromid = $this->m->getPlayerDataByName($value['from_player']);
                $found = true;
                $dataList[$rank] = array(
                    'userid' => $userid,
                    'date' => $value['gdate'],
                    'usernam' => $value['to_player'],
                    'fromnam' => $value['from_player'],
                    'fromid' => $fromid,
                    'golds' => $value['gold']
                );
            }
            $this->viewData['dataList'] = $dataList;
            $this->viewData['found'] = $found;
            $this->viewData['getPreviousLink'] = $this->getPreviousLink();
            $this->viewData['getNextLink'] = $this->getNextLink();
            unset($dataList);
            unset($this->dataList);
            
            $this->viewData['errorTable'] = $this->errorTable;
            $this->viewData['goldCantrans'] = $this->data['gold_num'];
        }
        elseif ($this->selectedTabIndex == 5)
        {
            $rowsCount       = $this->m->PayhisListByplayerName($this->data['name']);
            $this->pageCount = (0 < $rowsCount ? ceil($rowsCount / $this->pageSize) : 1);
            $this->pageIndex = (is_get('p') && is_numeric(get('p'))) ? intval(get('p')) : 0;
            if ($this->pageCount <= $this->pageIndex)
            {
                $this->pageIndex = $this->pageCount - 1;
            }
            $this->dataList = $this->m->PayhisByplayerName($this->data['name'], $this->pageIndex, $this->pageSize);
            $rowIndex       = 0;
            $found = false;
            $dataList       = array();
            foreach ($this->dataList as $value)
            {
                ++$rowIndex;
                $rank       = $rowIndex + $this->pageIndex * $this->pageSize;
                $found = true;

                $dataList[$rank] = array(
                    'type' => $value['type'],
                    'transID' => $value['transID'],
                    'golds' => $value['golds'],
                    'money' => $value['money'],
                    'currency' => $value['currency'],
                    'time' => $value['mdate']
                );
            }
            $this->viewData['dataList'] = $dataList;
            $this->viewData['found'] = $found;
            $this->viewData['getPreviousLink'] = $this->getPreviousLink();
            $this->viewData['getNextLink'] = $this->getNextLink();
            unset($dataList);
            unset($this->dataList);
            
        }
        if ( $this->selectedTabIndex == 2)
        {
            $this->viewData['data'] = $this->data;
            for ($i=0; $i <= 4; $i++)
            {
                $this->viewData['PlusTime'.$i] = $this->getRemainingPlusTime($i);
            }
            for ($i=0; $i <= 9; $i++)
            {
                $this->viewData['PlusAction'.$i] = $this->getPlusAction($i);
            }
        }

        ## Pre-rendering
        if ( 0 < $this->selectedTabIndex )
        {
            $this->viewData['villagesLinkPostfix'] .= "&t=".$this->selectedTabIndex;
        }
    }

    public function getRemainingPlusTime( $action )
    {
        $time = 0;
        $tasks = $this->queueModel->tasksInQueue;

        if ( isset( $tasks[constant( "QS_PLUS".( $action + 1 ) )] ) )
        {
            $time = $tasks[constant( "QS_PLUS".( $action + 1 ) )][0]['remainingSeconds'];
        }
        return 0 < $time ? time_remain_lang." <span id=\"timer1\">".secondsToString( $time )."</span> ".time_hour_lang : "";
    }

    public function getPlusAction( $action )
    {
        if ( $this->data['gold_num'] < $this->plusTable[$action]['cost'] )
        {
            return "<span class=\"none\">".plus_text_lowgold."</span>";
        }
        if ($action == 5 || $action == 7 || $action == 8)
        {
            return "<a href=\"plus?t=2&a=".$action."&k=".$this->data['update_key']."\">".plus_text_activatefeature."</a>";
        }
        if ($action == 9)
        {
            return "<a href=\"javascript:payResources('gold')\">".plus_text_pay."</a>";
        }
        if ( $action == 6 )
        {
            return $this->hasMarketplace() ? "<a href=\"build?bid=17&t=3\">".plus_text_gotomarket."</a>" : "<span class=\"none\">".plus_text_gotomarket."</span>";
        }
        $tasks = $this->queueModel->tasksInQueue;
        return isset( $tasks[constant( "QS_PLUS".( $action + 1 ) )] ) ? "<a href=\"plus?t=2&a=".$action."&k=".$this->data['update_key']."\">".plus_text_extendfeature."</a>" : "<a href=\"plus?t=2&a=".$action."&k=".$this->data['update_key']."\">".plus_text_activatefeature."</a>";
    }

    public function hasMarketplace()
    {
        $b_arr = explode( ",", $this->data['buildings'] );
        foreach ( $b_arr as $b_str )
        {
            $b2 = explode( " ", $b_str );
            if ( !( $b2[0] == 17 ) )
            {
                continue;
            }
            return TRUE;
        }
        return FALSE;
    }

    function getNextLink()
        {
        $text = text_nextpage_lang . ' »';
        if ($this->pageIndex + 1 == $this->pageCount)
            {
            return $text;
            }
        $link = '';
        if (0 < $this->selectedTabIndex)
            {
            $link .= 't=' . $this->selectedTabIndex;
            }
        if ($link != '')
            {
            $link .= '&';
            }
        $link .= 'p=' . ($this->pageIndex + 1);
        $link = 'plus?' . $link;
        return '<a href="' . $link . '">' . $text . '</a>';
        }
    function getPreviousLink()
        {
        $text = '« ' . text_prevpage_lang;
        if ($this->pageIndex == 0)
            {
            return $text;
            }
        $link = '';
        if (0 < $this->selectedTabIndex)
            {
            $link .= 't=' . $this->selectedTabIndex;
            }
        if (0 < $this->pageIndex)
            {
            if ($link != '')
                {
                $link .= '&';
                }
            $link .= 'p=' . ($this->pageIndex - 1);
            }
        if ($link != '')
            {
            $link = '?' . $link;
            }
        $link = 'plus' . $link;
        return '<a href="' . $link . '">' . $text . '</a>';
        }

}
?>
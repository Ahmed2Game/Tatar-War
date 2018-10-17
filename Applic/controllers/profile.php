<?php
load_game_engine('Auth');

class Profile_Controller extends AuthController
{
    public $err = array(0 => "", 1 => "", 2 => "", 3 => "");

    public $fullView = null;
    public $profileData = null;
    public $selectedTabIndex = null;
    public $villagesCount = null;
    public $villages = null;
    public $birthDate = null;
    public $agentForPlayers = array();
    public $myAgentPlayers = array();
    public $errorText = null;
    public $bbCodeReplacedArray = array();
    public $isAdmin = null;

    public function __construct()
    {
        parent::__construct();
        $this->viewFile                    = 'profile';
        $this->viewData['contentCssClass'] = 'player';
    }

    public function index()
    {
        global $gameConfig;

        ## view
        $this->viewData['active_plus_account'] = $this->data['active_plus_account'];
        $this->viewData['isAdmin'] =& $this->isAdmin;
        $this->viewData['isHunter'] =& $this->isHunter;
        $this->viewData['player'] = $this->player;
        $this->viewData['profileData'] =& $this->profileData;
        $this->viewData['fullView'] =& $this->fullView;
        $this->viewData['selectedTabIndex'] =& $this->selectedTabIndex;
        $this->viewData['errorText'] =& $this->errorText;

        $this->viewData['myAgentPlayers'] =& $this->myAgentPlayers;
        $this->viewData['agentForPlayers'] =& $this->agentForPlayers;

        $this->isAdmin  = $this->data['player_type'] == PLAYERTYPE_ADMIN;
        $this->isHunter = $this->data['player_type'] == PLAYERTYPE_HUNTER;
        $uid            = ((is_get('uid') && 0 < intval(get('uid'))) ? intval(get('uid')) : $this->player->playerId);

        if (($this->isAdmin || $this->isHunter) && is_get('spy') && 1 < $uid && $uid != $this->player->playerId)
        {
            if (is_post('spybass') && post('spybass') == $gameConfig['system']['spybass'])
            {
                $gameStatus = $this->player->gameStatus;
                $previd     = $this->player->playerId;

                $this->load_library('PlayerLibrary');
                $this->PlayerLibrary->playerId     = $uid;
                $this->PlayerLibrary->prevPlayerId = $previd;
                $this->PlayerLibrary->isAgent      = FALSE;
                $this->PlayerLibrary->isSpy        = TRUE;
                $this->PlayerLibrary->gameStatus   = $gameStatus;
                $this->PlayerLibrary->save();
                $this->is_redirect = TRUE;
                redirect('village1');
                return null;
            }
        }
        $this->load_model('Profile', 'm');
        $blockId                   = $this->m->getBlockPlayerById($this->player->playerId);
        $blockId2                  = explode(',', $blockId);
        $this->viewData['isblock'] = ($blockId != '') ? in_array($uid, $blockId2) : FALSE;
        if ($uid != $this->player->playerId && is_get('block'))
        {
            $newblockId = '';
            if (in_array($uid, $blockId2))
            {
                foreach ($blockId2 as $value)
                {
                    if ($value != $uid)
                    {
                        if ($newblockId != '')
                        {
                            $newblockId .= ',';
                        }
                        $newblockId .= $value;
                    }
                }
            }
            else
            {
                if ($blockId != '')
                {
                    $newblockId .= $blockId . ',';
                }
                $newblockId .= $uid;
            }
            $this->m->setBlockPlayerId($newblockId, $this->player->playerId);
            $this->is_redirect = TRUE;
            redirect('profile?uid=' . $uid);
            return null;
        }
        $this->selectedTabIndex = 0;
        $this->fullView         = FALSE;


        if ($uid != $this->player->playerId)
        {
            $this->profileData = $this->m->getPlayerDataById($uid);
            if ($this->profileData == NULL || $this->profileData['player_type'] == PLAYERTYPE_ADMIN || $this->profileData['player_type'] == PLAYERTYPE_HUNTER )
            {
                
                $this->is_redirect = TRUE;
                redirect('village1');
                return null;
            }
        }
        else
        {
            $this->profileData       = $this->data;
            $this->profileData['id'] = $uid;
            $this->fullView          = !$this->player->isAgent;
            $this->selectedTabIndex  = (((((!$this->player->isAgent && is_get('t')) && is_numeric(get('t'))) && 0 <= intval(get('t'))) && intval(get('t')) <= 6) ? intval(get('t')) : 0);
            if (($this->selectedTabIndex == 4 && $this->data['player_type'] == PLAYERTYPE_TATAR))
            {
                $this->selectedTabIndex = 0;
            }
            $agentForPlayers = (trim($this->profileData['agent_for_players']) == '' ? array() : explode(',', $this->profileData['agent_for_players']));
            foreach ($agentForPlayers as $agent)
            {
                list($agentId, $agentName) = explode(' ', $agent);
                $this->agentForPlayers[$agentId] = $agentName;
            }
            $myAgentPlayers = (trim($this->profileData['my_agent_players']) == '' ? array() : explode(',', $this->profileData['my_agent_players']));
            foreach ($myAgentPlayers as $agent)
            {
                list($agentId, $agentName) = explode(' ', $agent);
                $this->myAgentPlayers[$agentId] = $agentName;
            }
        }
        $this->viewData['getProfileDescription']   = $this->getProfileDescription($this->profileData['description1']);
        $this->viewData['getProfileDescription_2'] = $this->getProfileDescription($this->profileData['description2']);
        $this->profileData['rank']                 = $this->m->getPlayerRank($uid, $this->profileData['total_people_count'] * 10 + $this->profileData['villages_count']);

        if ($_POST)
        {
            if (($this->fullView && is_post('e')))
            {
                switch (post('e'))
                {
                case 1:
                    $_y_ = (((is_post('jahr') && 1930 <= intval(post('jahr'))) && intval(post('jahr')) <= 2016) ? intval(post('jahr')) : '');
                    $_m_ = (((is_post('monat') && 1 <= intval(post('monat'))) && intval(post('monat')) <= 12) ? intval(post('monat')) : '');
                    $_d_ = (((is_post('tag') && 1 <= intval(post('tag'))) && intval(post('tag')) <= 31) ? intval(post('tag')) : '');

                    $newData = array(
                        'gender' => ((0 <= intval(post('mw')) && intval(post('mw')) <= 2) ? intval(post('mw')) : 0),
                        'house_name' => (is_post('ort') ? htmlspecialchars(post('ort')) : ''),
                        'village_name' => ((is_post('dname') && trim(stripslashes(post('dname'))) != '' && strlen(post('dname')) < 25 && strlen(post('dname')) > 5) ? htmlspecialchars(post('dname')) : $this->profileData['village_name']),
                        'description1' => (is_post('be1') ? htmlspecialchars(post('be1')) : ''),
                        'description2' => (is_post('be2') ? htmlspecialchars(post('be2')) : ''),
                        'birthData' => $_y_ . '-' . $_m_ . '-' . $_d_,
                        'villages' => $this->data['villages_data']
                    );

            
                    if (strlen(post('dname')) > 25 || strlen(post('dname')) < 5)
                    {
                        $this->viewData['errorTable'] = LANGUI_PROFILE_e8;
                    }
                    elseif($_y_ == '' || $_m_ == '' || $_d_ == '')
					{
						$this->viewData['errorTable'] = LANGUI_PROFILE_e10;
					}
					else
                    {
						$this->m->editPlayerProfile($this->player->playerId, $newData);
                        
                        $this->viewData['errorTable'] = null;
                        $this->is_redirect = TRUE;
                        redirect('profile');
                    }
                    break;
                case 2:
                    if (post('pw1') != '' && is_post('pw2') != '' && is_post('pw3') != '')
                    {
                        if (5 >= strlen(post('pw2')))
                        {
                            $this->viewData['errorTable'] = LANGUI_PROFILE_e1;
                        }
                        elseif (strtolower($this->profileData['pwd']) != strtolower(md5($_POST['pw1'])))
                        {
                            $this->viewData['errorTable'] = LANGUI_PROFILE_e3;
                        }
                        elseif (post('pw2') != post('pw3'))
                        {
                            $this->viewData['errorTable'] = LANGUI_PROFILE_e2;
                        }
                        else
                        {
                            $this->m->changePlayerPassword($this->player->playerId, md5($_POST['pw2']));
                            $this->viewData['errorTable'] = LANGUI_PROFILE_e4;
                        }
                    }
                    else
                    {
                        $this->viewData['errorTable'] = null;
                    }

                    if (post('email_alt') != '' && post('email_neu') != '')
                    {
                        if (strtolower($this->profileData['email']) != strtolower(post('email_alt')))
                        {
                            $this->viewData['erroremail'] = LANGUI_PROFILE_e5;
                        }
                        elseif (!preg_match('/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/', post('email_neu')))
                        {
                            $this->viewData['erroremail'] = LANGUI_PROFILE_e6;
                        }
                        else
                        {
                            $code = $this->m->changePlayerEmail($this->player->playerId, post('email_neu'), $this->profileData['name']);
							if($code != "")
							{
								$link = URL."activate?id=".$code;
								$pwd = '*********';
                                $subject = register_player_txt_regmail_sub;
                                $message = sprintf( register_player_txt_regmail_body, $this->profileData['name'], $this->profileData['name'], $pwd, $link, $link );
                                send_mail( post('email_neu'), $gameConfig['system']['email'], $subject, $message, $gameConfig['page'][$gameConfig['system']['lang'].'_title'], $this->profileData['name'] );
								
							}
                            $this->viewData['erroremail'] = LANGUI_PROFILE_e7;
                        }
                    }
                    else
                    {
                        $this->viewData['erroremail'] = null;
                    }

                    break;
                case 3:
                    if (((is_post('v1') && post('v1') != '') && sizeof($this->myAgentPlayers) < 2))
                    {
                        $aid = $this->m->getPlayerIdByName(post('v1'));
                        if (((0 < intval($aid) && $aid != $this->player->playerId) && !isset($this->myAgentPlayers[$aid])))
                        {
                            $_agentsFor = $this->m->getPlayerAgentForById(intval($aid));
                            if (1 < sizeof(explode(',', $_agentsFor)))
                            {
                                $this->errorText = profile_setagent_err_msg;
                            }
                            else
                            {
                                $this->myAgentPlayers[$aid] = post('v1');
                                $this->m->setMyAgents($this->player->playerId, $this->data['name'], $this->myAgentPlayers, $aid);
                            }
                        }
                    }
                    break;
                case 4:
                    if ((((((is_post('del') && post('del') == 1) && strtolower($this->profileData['pwd']) == strtolower(md5($_POST['del_pw']))) && !$this->isPlayerInDeletionProgress()) && !$this->isGameTransientStopped()) && !$this->isGameOver()))
                    {
                        $this->load_model('Artefacts', 'A');
		                $hasArtefacts = $this->A->GetArtefactsNum($this->player->playerId);
						if ($hasArtefacts > 0)
                        {
						    $this->viewData['erroremail'] = LANGUI_PROFILE_e11;
                            return;
                        }
						else
						{
							$this->load_library('QueueTask', 'newTask', array(
                            'taskType' => QS_ACCOUNT_DELETE,
                            'playerId' => $this->player->playerId,
                            'executionTime' => 259200
                            ));
                            $this->queueModel->addTask($this->newTask);
						}
                    }
                    $this->viewData['erroremail'] = null;
                    break;
                case 5:
                    if (!$this->data['active_plus_account'])
                    {
                        $this->is_redirect = TRUE;
                        redirect('plus?t=2');
                    }
                    else
                    {
                        if ($_POST)
                        {
                            $this->playerLinks = array();
                            $i                 = 0;
                            $c                 = sizeof($_POST['nr']);
                            $nameArrays        = $_POST['linkname'];
                            $urlArrays         = $_POST['linkurl'];
                            while ($i < $c)
                            {
                                $name = trim($nameArrays[$i]);
                                $url  = trim($urlArrays[$i]);
                                if ($url == "" || $name == "" || $_POST['nr'][$i] == "" || !is_numeric($_POST['nr'][$i]))
                                {
                                    ++$i;
                                    continue;
                                }
                                $selfTarget = TRUE;
                                if (substr($url, strlen($url) - 1) == "*")
                                {
                                    $url        = substr($url, 0, strlen($url) - 1);
                                    $selfTarget = FALSE;
                                }
                                if (isset($this->playerLinks[$_POST['nr'][$i]]))
                                {
                                    ++$_POST['nr'][$i];
                                }
                                $this->playerLinks[$_POST['nr'][$i]] = array(
                                    "linkName" => $name,
                                    "linkHref" => $url,
                                    "linkSelfTarget" => $selfTarget
                                );
                                ++$i;
                            }
                            ksort($this->playerLinks);
                            $links = "";
                            foreach ($this->playerLinks as $link)
                            {
                                if ($links != "")
                                {
                                    $links .= "\n\n";
                                }
                                $links .= $link['linkName'] . "\n" . $link['linkHref'] . "\n" . ($link['linkSelfTarget'] ? "?" : "*");
                            }
                            $this->m->changePlayerLinks($this->player->playerId, $links);
                            
                            $this->viewData['playerLinks'] = $this->playerLinks;
                        }
                    }
                    break;
                case 6:
                {
                    if (is_post('protection') AND post('protection') == 1)
                    {
                        $protection = explode(',', $this->data['protection']);
                        if ($protection[0] == 0)
                        {
                            $newtime = time() + ($gameConfig['settings']['protection1'] * 24 * 60 * 60);
                            $newPro  = $this->data['protection'] = '1,' . $newtime;
                            $this->m->UpdatePlayerProtection($this->player->playerId, $newPro);
                        }
                    }
                    if (is_post('holiday') AND post('holiday') == 1)
                    {
                        $holiday = explode(',', $this->data['holiday']);
                        if ($holiday[0] == 0 AND (time() - $holiday[1] > (7 * 24 * 60 * 60)))
                        {
                            $newhol = $this->data['holiday'] = '1,' . time();
                            $this->m->UpdatePlayerHoliday($this->player->playerId, $newhol);
                        }
                    }
                    if (is_post('holiday') AND post('holiday') == 2)
                    {
                        $holiday = explode(',', $this->data['holiday']);
                        if ($holiday[0] == 1 AND (time() - $holiday[1] > ($gameConfig['settings']['holiday'] * 24 * 60 * 60)))
                        {
                            $newhol = $this->data['holiday'] = '0,' . time();
                            $this->m->UpdatePlayerHoliday($this->player->playerId, $newhol);
                        }
                        elseif($this->data['gold_num'] >= $gameConfig['settings']['holidaygold'])
                        {
                            $newhol = $this->data['holiday'] = '0,' . time();
                            $this->m->UpdatePlayerHoliday($this->player->playerId, $newhol, $gameConfig['settings']['holidaygold']);
                        }
                    }
                }
                }
            }
        }
            if ($this->selectedTabIndex == 3)
            {
                if ((is_get('aid') && 0 < intval(get('aid'))))
                {
                    $aid = intval(get('aid'));
                    if (isset($this->myAgentPlayers[$aid]))
                    {
                        unset($this->myAgentPlayers[$aid]);
                        $this->m->removeMyAgents($this->player->playerId, $this->myAgentPlayers, $aid);
                    }
                }
                else
                {
                    if ((is_get('afid') && 0 < intval(get('afid'))))
                    {
                        $aid = intval(get('afid'));
                        if (isset($this->agentForPlayers[$aid]))
                        {
                            unset($this->agentForPlayers[$aid]);
                            $this->m->removeAgentsFor($this->player->playerId, $this->agentForPlayers, $aid);
                        }
                    }
                }
            }
            else if ($this->selectedTabIndex == 4)
            {
                if ((is_get('qid')) && 0 < intval(get('qid')))
                {
                    $this->queueModel->cancelTask($this->player->playerId, intval(get('qid')));
                }

                $this->viewData['isPlayerInDeletionProgress'] = $this->isPlayerInDeletionProgress();
                if ($this->viewData['isPlayerInDeletionProgress'])
                {
                    $this->viewData['canCancelPlayerDeletionProcess'] = $this->canCancelPlayerDeletionProcess();
                    $this->viewData['getPlayerDeletionId']            = $this->getPlayerDeletionId();
                    $this->viewData['getPlayerDeletionTime']          = $this->getPlayerDeletionTime();
                }
            }
            elseif ($this->selectedTabIndex == 5)
            {
                $this->viewData['playerLinks'] = $this->playerLinks;
            }

        if ($this->selectedTabIndex == 0)
        {
            $this->villagesCount = sizeof(explode(',', $this->profileData['villages_id']));
            $this->villages      = $this->m->getVillagesSummary($this->profileData['villages_id']);
            $this->load_model('Friends', 'F');
            $this->viewData['isfriend'] = $this->F->GetIfFriends($this->profileData['id'] , $this->player->playerId);
            $this->viewData['isisend'] = $this->F->GetIfsendRequest($this->player->playerId, $this->profileData['id']);
            $this->viewData['issend'] = $this->F->GetIfsendRequest($this->profileData['id'] , $this->player->playerId);
            $this->viewData['villagesCount'] = $this->villagesCount;
            $this->viewData['villages']      = $this->villages;
            unset($this->villages);
        }
        else
        {
            if ($this->selectedTabIndex == 1)
            {
                $birth_date = $this->profileData['birth_date'];
                if (!$birth_date)
                {
                    $birth_date = '0-0-0';
                }
                list($year, $month, $day) = explode('-', $birth_date);
                $this->birthDate = array(
                    'year' => $year,
                    'month' => $month,
                    'day' => $day
                );

                $medals = explode(",", $this->profileData['medals']);

                foreach ($medals as $k => $medal)
                {
                    list($index, $rank, $week, $points) = explode(":", $medal);
                    if (!isset($this->gameMetadata['medals'][$index]))
                    {
                        continue;
                    }
                    $medalData = $this->gameMetadata['medals'][$index];
                    if ($index == 0)
                    {
                        $rank = 1;
                    }

                    $medals[$k] = array(
                        'textIndex' => constant("medal_row_" . $medalData['textIndex']),
                        'rank' => $rank,
                        'week' => $week,
                        'points' => $points,
                        'bb' => intval($medalData['BBCode']) + intval($week) * 10 + (intval($rank) - 1)
                    );
                }
                $this->viewData['medals'] = $medals;

                $this->viewData['birthDate'] = $this->birthDate;
            }
        }
        // protection and holiday Options
        if ($this->selectedTabIndex == 6)
        {
            $protection = explode(',', $this->data['protection']);
            if ($protection[0] == 0) // if hi unActive
            {
                $this->viewData['ProtectionStatus'] = 1;
            }
            elseif ($protection[0] == 1 AND $protection[1] > time()) // if he Active
            {
                $this->viewData['ProtectionStatus'] = 2;
                $this->viewData['timer']            = secondsToString($protection[1] - time());
            }
            else // if he End
            {
                $this->viewData['ProtectionStatus'] = 3;
            }

            $holiday = explode(',', $this->data['holiday']);
            if ($holiday[0] == 0) // if hi unActive
            {
                $this->viewData['holidayActiv'] = FALSE;
                $holidayEnd                     = (time() - $holiday[1] < (7 * 24 * 60 * 60)) ? TRUE : FALSE;
                if ($holidayEnd)
                {
                    $this->viewData['holidayback'] = secondsToString((7 * 24 * 60 * 60) - (time() - $holiday[1]));
                }
            }
            else // if hi Active
            {
                $this->viewData['holidayActiv'] = TRUE;
                $holidayEnd                     = (time() - $holiday[1] < ($gameConfig['settings']['holiday'] * 24 * 60 * 60)) ? TRUE : FALSE;
                if ($holidayEnd)
                {
                    $this->viewData['holidayback'] = secondsToString(($gameConfig['settings']['holiday'] * 24 * 60 * 60) - (time() - $holiday[1]));
                }
            }
            $this->viewData['holidayEnd'] = $holidayEnd;

        }
        

        // Pre-rendering
        $this->viewData['villagesLinkPostfix'] = '';
        if (is_get('uid'))
        {
            $this->viewData['villagesLinkPostfix'] .= '&uid=' . intval(get('uid'));
        }
        if (0 < $this->selectedTabIndex)
        {
            $this->viewData['villagesLinkPostfix'] .= '&t=' . $this->selectedTabIndex;
        }

    }


    public function canCancelPlayerDeletionProcess()
    {
        if (!QueueTask::iscancelabletask(QS_ACCOUNT_DELETE))
        {
            return FALSE;
        }
        $timeout = QueueTask::getmaxcanceltimeout(QS_ACCOUNT_DELETE);
        if (0 - 1 < $timeout)
        {
            $elapsedTime = $this->queueModel->tasksInQueue[QS_ACCOUNT_DELETE][0]['elapsedTime'];
            if ($timeout < $elapsedTime)
            {
                return FALSE;
            }
        }
        return TRUE;
    }


    public function getProfileDescription($text)
    {
        $img    = '<img class="%s" src="assets/x.gif" onmouseout="med_closeDescription()" onmousemove="med_mouseMoveHandler(arguments[0],\'<p>%s</p>\')">';
        $medals = explode(',', $this->profileData['medals']);
        foreach ($medals as $medal)
        {
            if (trim($medal) == '')
            {
                continue;
            }
            list($index, $rank, $week, $points) = explode(':', $medal);
            if (!isset($this->gameMetadata['medals'][$index]))
            {
                continue;
            }
            $medalData = $this->gameMetadata['medals'][$index];
            $bbCode    = '';
            if ($index == 0)
            {
                $bbCode   = intval($medalData['BBCode']);
                $postfix  = (0 < $this->data['protection_remain_sec'] ? '' : 'd');
                $cssClass = $medalData['cssClass'] . $postfix;
                $altText  = htmlspecialchars(sprintf(constant('medal_' . $medalData['textIndex'] . $postfix), ($postfix == 'd' ? $this->data['registration_date'] : $this->data['protection_remain'])));
            }
            else
            {
                $bbCode   = intval($medalData['BBCode']) + intval($week) * 10 + (intval($rank) - 1);
                $cssClass = 'medal ' . $medalData['cssClass'] . '_' . $rank;
                $altText  = htmlspecialchars(sprintf('<table><tr><th>' . profile_medal_txt_cat . ':</th><td>%s</td></tr><tr><th>' . profile_medal_txt_week . ':</th><td>%s</td></tr><tr><th>' . profile_medal_txt_rank . ':</th><td>%s</td></tr><tr><th>' . profile_medal_txt_points . ':</th><td>%s</td></tr></table>', constant('medal_' . $medalData['textIndex']), $week, $rank, $points));
            }
            if (!isset($this->bbCodeReplacedArray[$bbCode]))
            {
                $count = 0;
                $text  = preg_replace('/\[#' . $bbCode . '\]/', sprintf($img, $cssClass, $altText), $text, 1, $count);
                if (0 < $count)
                {
                    $this->bbCodeReplacedArray[$bbCode] = $count;
                }
            }
        }
        return nl2br($text);
    }
}
?>
<?php
/**
 * Alliance class file.
 *
 * @author Abdulrahman Mohamed <abdokamal15@gmail.com>
 * @copyright Copyright 2013
 * @license http://www.xtatar.com
 */

/**
 * add,edit,delete,invite Players Alliances
 *
 * @author Abdulrahman Mohamed <abdokamal15@gmail.com>
 * @version $Id$
 * @package app.controllers
 * @since 1.0
 */
load_game_engine('Auth');
load_game_engine('Report', 'Helper');
require_once LIBRARY_DIR . 'gameEngine/ReportHelper.php';

class Alliance_Controller extends AuthController
{
    public $selectedTabIndex = null;
    public $fullView = null;
    public $hasAlliance = FALSE;
    public $allianceData = NULL;
    public $lastReports = NULL;
    public $hasErrors = FALSE;
    public $invitesResult = -1;
    public $contracts = null;
    public $bbCodeReplacedArray = array();

    /**
     * Constructor Method
     * This method defines view file && contentCssClass .
     */
    public function __construct()
    {
        parent::__construct();
        $this->viewFile = 'alliance';
        $this->viewData['contentCssClass'] = 'alliance';
    }

    /**
     * Index Method
     *
     * @return void
     */
    public function index()
    {
        $this->load_model('Alliance', 'm');
        $allianceId = 0;
        $this->allianceData = NULL;
        if (is_get('id') && 0 < intval(get('id'))) {
            $allianceId = intval(get('id'));
            $this->allianceData = $this->m->getAllianceData($allianceId);
        }
        if ($this->allianceData == NULL) {
            $allianceId = intval($this->data['alliance_id']);
            if ($allianceId <= 0) {
                $this->hasAlliance = FALSE;
                $this->viewData['hasAlliance'] = $this->hasAlliance;
                return null;
            }
            $this->allianceData = $this->m->getAllianceData($allianceId);
        }
        $this->hasAlliance = TRUE;
        $this->fullView = $allianceId == intval($this->data['alliance_id']);
        $this->selectedTabIndex = 0;
        if ($this->fullView) {
            $this->selectedTabIndex = ((((is_get('t') && is_numeric(get('t'))) && 0 <= intval(get('t'))) && intval(get('t')) <= 3) ? intval(get('t')) : 0);
            if (($this->selectedTabIndex == 1 && !$this->hasAllianceEditRole())) {
                $this->selectedTabIndex = 0;
            }
        }
        if (is_post('aname1')) {
            if ((($this->fullView && $this->selectedTabIndex == 1) && $this->hasAllianceEditRole())) {
                $newData = array(
                    'name' => ((is_post('aname1') && trim(stripslashes(post('aname1'))) != '' && strlen(post('aname1')) < 10) ? strip_tags(post('aname1')) : $this->allianceData['name']),
                    'name2' => ((is_post('aname2') && trim(stripslashes(post('aname2'))) != '' && strlen(post('aname2')) < 25) ? strip_tags(post('aname2')) : $this->allianceData['name2']),
                    'description1' => strip_tags(post('be1')),
                    'description2' => strip_tags(post('be2'))
                );
                $this->m->editAllianceData(intval($this->data['alliance_id']), $newData, $this->allianceData['players_ids']);
                $this->is_redirect = TRUE;
                redirect('alliance');
                return null;
            }
        }
        if (((((($this->selectedTabIndex == 0 && is_get('d')) && 0 < intval(get('d'))) && $this->hasAllianceRemovePlayerRole()) && $this->player->playerId != intval(get('d'))) && $this->isMemberOfAlliance(intval(get('d'))))) {
            $this->allianceData['players_ids'] = $this->m->removeFromAlliance(intval(get('d')), $allianceId, $this->allianceData['players_ids'], $this->allianceData['player_count']);
            --$this->allianceData['player_count'];
        } else {
            if ($this->selectedTabIndex == 2) {
                $lastReportsType = 0;
                if (is_get('ac')) {
                    if (get('ac') == 1) {
                        $lastReportsType = 1;
                    } else if (get('ac') == 2) {
                        $lastReportsType = 2;
                    }
                }
                $this->lastReports = $this->m->getLatestReports($this->allianceData['players_ids'], $lastReportsType);
                $Reports = array();
                foreach ($this->lastReports as $value) {
                    $rptRelativeResult = ReportHelper::getreportresultrelative($value['rpt_result'], $value['isAttack']);
                    $btext = ReportHelper::getreportresulttext($rptRelativeResult);
                    $_rptResultCss = $rptRelativeResult == 100 ? 10 : $rptRelativeResult;
                    $targetPlayerId = $this->isMemberOfAlliance($value['from_player_id']) ? $value['to_player_id'] : $value['from_player_id'];
                    $aData = $this->getAllianceDataFor($targetPlayerId);
                    $Reports[] = array(
                        'btext' => $btext,
                        '_rptResultCss' => $_rptResultCss,
                        'id' => $value['id'],
                        'from_player_name' => $value['from_player_name'],
                        'getreportactiontext' => ReportHelper::getreportactiontext($value['rpt_cat']),
                        'to_player_name' => $value['to_player_name'],
                        'aData' => $aData,
                        'mdate' => $value['mdate']
                    );
                }
                unset($this->lastReports);
                $this->viewData['Reports'] = $Reports;
            } else {
                if ($this->selectedTabIndex == 3) {
                    if (is_get('a')) {
                        switch (get('a')) {
                            case 1:
                                if (!$this->hasAllianceInviteRoles()) {
                                    unset($_GET['a']);
                                    break;
                                }
                                $this->allianceData['players_invites'] = array();
                                if (trim($this->allianceData['invites_player_ids']) != '') {
                                    $invites = explode("\n", trim($this->allianceData['invites_player_ids']));
                                    foreach ($invites as $invite) {
                                        list($pid, $pname) = explode(' ', $invite, 2);
                                        $this->allianceData['players_invites'][$pid] = $pname;
                                    }
                                }
                                if (is_post('a_name')) {
                                    $pid = intval($this->m->getPlayerId(post('a_name')));
                                    if (0 < $pid) {
                                        if (!isset($this->allianceData['players_invites'][$pid])) {
                                            $this->invitesResult = 2;
                                            $this->allianceData['players_invites'][$pid] = post('a_name');
                                            $this->m->addAllianceInvites($pid, $allianceId);
                                        }
                                    } else {
                                        $this->invitesResult = 1;
                                    }
                                }
                                if (((is_get('d') && 0 < intval(get('d'))) && isset($this->allianceData['players_invites'][intval(get('d'))]))) {
                                    unset($this->allianceData['players_invites'][intval(get('d'))]);
                                    $this->m->removeAllianceInvites(intval(get('d')), $allianceId);
                                }
                                break;
                            case 2:
                                if (!$this->hasAllianceEditContractRole()) {
                                    unset($_GET['a']);
                                    break;
                                }
                                $contracts_alliance_id = trim($this->allianceData['contracts_alliance_id']);
                                $contracts = array();
                                if ($contracts_alliance_id != '') {
                                    $contracts_alliance_idArr = explode(',', $contracts_alliance_id);
                                    foreach ($contracts_alliance_idArr as $item) {
                                        list($aid, $pendingStatus) = explode(' ', $item);
                                        $contracts[$aid] = $pendingStatus;
                                    }
                                }
                                $this->hasErrors = TRUE;
                                if (is_get('d') || is_get('c')) {
                                    if (((is_get('d') && 0 < intval(get('d'))) && isset($contracts[get('d')]))) {
                                        unset($contracts[$_GET['a']]);
                                        $this->m->removeAllianceContracts($allianceId, intval(get('d')));
                                    }
                                    if (((is_get('c') && 0 < intval(get('c'))) && isset($contracts[get('c')]))) {
                                        $contracts[get('c')] = 0;
                                        $this->m->acceptAllianceContracts($allianceId, intval(get('c')));
                                    }
                                } else if ((is_post('a_name') && trim(post('a_name')) != '')) {
                                    $caid = intval($this->m->getAllianceId(trim(post('a_name'))));
                                    if ((0 < $caid && !isset($contracts[$caid]))) {
                                        $this->m->addAllianceContracts($allianceId, $caid);
                                        $contracts[$caid] = 1;
                                        $this->hasErrors = FALSE;
                                    }
                                }
                                $this->contracts = $contracts;
                                $Contracts = array();
                                foreach ($this->contracts as $aid => $status) {
                                    $Contracts[$aid] = array(
                                        'name' => $this->getAllianceName($aid),
                                        'status' => $status
                                    );
                                }
                                $this->viewData['Contracts'] = $Contracts;
                                break;
                            case 3:
                                if (is_post('pw')) {
                                    if (is_post('pw') && strtolower($this->data['pwd']) == strtolower(md5(post('pw')))) {
                                        $this->allianceData['players_ids'] = $this->m->removeFromAlliance($this->player->playerId, $allianceId, $this->allianceData['players_ids'], $this->allianceData['player_count']);
                                        --$this->allianceData['player_count'];
                                        $this->is_redirect = TRUE;
                                        redirect('alliance');
                                        return null;
                                    }
                                    $this->hasErrors = TRUE;
                                }
                        }
                    }
                }
            }
        }
        if ($this->selectedTabIndex == 0) {
            $contracts_alliance_id = trim($this->allianceData['contracts_alliance_id']);
            $this->contracts = array();
            if ($contracts_alliance_id != '') {
                $contracts_alliance_idArr = explode(',', $contracts_alliance_id);
                foreach ($contracts_alliance_idArr as $item) {
                    list($aid, $pendingStatus) = explode(' ', $item);
                    if ($pendingStatus == 0) {
                        $this->contracts[$aid] = $this->m->getAllianceName($aid);
                    }
                }
            }
            $this->allianceData['rank'] = $this->m->getAllianceRank($allianceId, $this->allianceData['score']);
            $result = $this->m->getAlliancePlayers($this->allianceData['players_ids']);
            $this->allianceData['players'] = array();
            if ($result != NULL) {
                $_c = 0;
                foreach ($result as $value) {
                    ++$_c;
                    $this->allianceData['players'][] = array(
                        'c' => $_c,
                        'id' => $value['id'],
                        'name' => $value['name'],
                        'total_people_count' => $value['total_people_count'],
                        'alliance_roles' => $value['alliance_roles'],
                        'villages_count' => $value['villages_count'],
                        'lastLoginFromHours' => $this->getOnlineStatus($value['lastLoginFromHours'])
                    );
                }
                $roles = array();
                foreach ($this->allianceData['players'] as $player) {
                    if (trim($player['alliance_roles']) == "") {
                        continue;
                    }
                    list($roleNumber, $roleName) = explode(" ", trim($player['alliance_roles']), 2);
                    $roleName = trim($roleName);
                    if ($roleName == "" || $roleName == ".") {
                        continue;
                    }
                    $roles[] = array
                    (
                        'name' => $player['name'],
                        'id' => $player['id'],
                        'role' => $roleName
                    );
                }
                $this->viewData['roles'] = $roles;
            }
            unset($result);
            $this->viewData['getAllianceDescription1'] = $this->getAllianceDescription($this->allianceData['description1']);
            $this->viewData['getAllianceDescription2'] = $this->getAllianceDescription($this->allianceData['description2']);
        }
        if ($this->selectedTabIndex == 1) {
            $Medals = array();
            if (trim($this->allianceData['medals']) != "") {
                $medals = explode(",", $this->allianceData['medals']);
                foreach ($medals as $medal) {
                    list($index, $rank, $week, $points) = explode(":", $medal);
                    if (!isset($this->gameMetadata['medals'][$index])) {
                        continue;
                    }
                    $medalData = $this->gameMetadata['medals'][$index];
                    if ($index == 0) {
                        $rank = 1;
                    }
                    $Medals[] = array(
                        'rank' => $rank,
                        'points' => $points,
                        'week' => $week,
                        'medalData' => $medalData
                    );
                }
            }
            $this->viewData['Medals'] = $Medals;
        }


        ############View############
        $this->viewData['hasAlliance'] = $this->hasAlliance;
        $this->viewData['allianceData'] = $this->allianceData;
        $this->viewData['fullView'] = $this->fullView;
        $this->viewData['selectedTabIndex'] = $this->selectedTabIndex;
        $this->viewData['hasAllianceEditRole'] = $this->hasAllianceEditRole();
        $this->viewData['RemovePlayerRole'] = $this->hasAllianceRemovePlayerRole();
        $this->viewData['hasAllianceSetRoles'] = $this->hasAllianceSetRoles();
        $this->viewData['playerId'] = $this->player->playerId;
        $this->viewData['hasAllianceInviteRoles'] = $this->hasAllianceInviteRoles();
        $this->viewData['EditContractRole'] = $this->hasAllianceEditContractRole();
        $this->viewData['hasErrors'] = $this->hasErrors;
        $this->viewData['invitesResult'] = $this->invitesResult;

        if (is_get('id')) {
            $this->viewData['villagesLinkPostfix'] .= '&id=' . intval(get('id'));
        }
        if (0 < $this->selectedTabIndex) {
            $this->viewData['villagesLinkPostfix'] .= '&t=' . $this->selectedTabIndex;
        }

    }


    /**
     * _hasAllianceRole Method
     *
     * Get Alliance roleNumber and roleName
     *
     * @param role string
     * @return void
     */
    public function _hasAllianceRole($role)
    {
        $alliance_roles = trim($this->data['alliance_roles']);
        if ($alliance_roles == '') {
            return FALSE;
        }
        list($roleNumber, $roleName) = explode(' ', $alliance_roles, 2);
        return $roleNumber & $role;
    }


    /**
     * hasAllianceEditRole Method
     *
     * @return bool
     */
    public function hasAllianceEditRole()
    {
        return $this->_hasAllianceRole(ALLIANCE_ROLE_EDITNAMES);
    }

    /**
     * hasAllianceRemovePlayerRole Method
     *
     * @return bool
     */
    public function hasAllianceRemovePlayerRole()
    {
        return $this->_hasAllianceRole(ALLIANCE_ROLE_REMOVEPLAYER);
    }

    /**
     * hasAllianceSetRoles Method
     *
     * @return bool
     */
    public function hasAllianceSetRoles()
    {
        return $this->_hasAllianceRole(ALLIANCE_ROLE_SETROLES);
    }


    /**
     * hasAllianceInviteRoles Method
     *
     * @return bool
     */
    public function hasAllianceInviteRoles()
    {
        return $this->_hasAllianceRole(ALLIANCE_ROLE_INVITEPLAYERS);
    }

    /**
     * hasAllianceEditContractRole Method
     *
     * @return bool
     */
    public function hasAllianceEditContractRole()
    {
        return $this->_hasAllianceRole(ALLIANCE_ROLE_EDITCONTRACTS);
    }


    /**
     * getAllianceName Method
     *
     * @param aid int
     * @return string
     */
    public function getAllianceName($aid)
    {
        $this->load_model('Alliance', 'm');
        $n = $this->m->getAllianceName($aid);
        return (trim($n) != '' ? $n : '[?]');
    }


    /**
     * getAllianceDataFor Method
     *
     * @param playerId int
     * @return string
     */
    public function getAllianceDataFor($playerId)
    {
        $this->load_model('Alliance', 'm');
        return $this->m->getAllianceDataFor($playerId);
    }

    /**
     * isMemberOfAlliance Method
     *
     * @param playerId int
     * @return string
     */
    public function isMemberOfAlliance($playerId)
    {
        $players_ids = trim($this->allianceData['players_ids']);
        if ($players_ids == '') {
            return FALSE;
        }
        $arr = explode(',', $players_ids);
        foreach ($arr as $pid) {
            if ($pid == $playerId) {
                return TRUE;
            }
        }
        return FALSE;
    }


    /**
     * getOnlineStatus Method
     *
     * @param lastLoginFromHours int
     * @return string
     */
    public function getOnlineStatus($lastLoginFromHours)
    {
        if ($lastLoginFromHours <= 1) {
            return '<img class="online1" src="assets/x.gif" title="' . alliance_p_status1 . '" alt="' . alliance_p_status1 . '">';
        }
        if ($lastLoginFromHours <= 24) {
            return '<img class="online2" src="assets/x.gif" title="' . alliance_p_status2 . '" alt="' . alliance_p_status2 . '">';
        }
        if ($lastLoginFromHours <= 24 * 3) {
            return '<img class="online3" src="assets/x.gif" title="' . alliance_p_status3 . '" alt="' . alliance_p_status3 . '">';
        }
        if ($lastLoginFromHours <= 24 * 7) {
            return '<img class="online4" src="assets/x.gif" title="' . alliance_p_status4 . '" alt="' . alliance_p_status4 . '">';
        }
        return '<img class="online5" src="assets/x.gif" title="' . alliance_p_status5 . '" alt="' . alliance_p_status5 . '">';
    }


    /**
     * getOnlineStatus Method
     *
     * @param text string
     * @return string
     */
    public function getAllianceDescription($text)
    {
        $img = '<img class="%s" src="assets/x.gif" onmouseout="med_closeDescription()" onmousemove="med_mouseMoveHandler(arguments[0],\'<p>%s</p>\')">';
        $medals = explode(',', $this->allianceData['medals']);
        foreach ($medals as $medal) {
            if (trim($medal) == '') {
                continue;
            }
            list($index, $rank, $week, $points) = explode(':', $medal);
            if (!isset($this->gameMetadata['medals'][$index])) {
                continue;
            }
            $medalData = $this->gameMetadata['medals'][$index];
            $bbCode = intval($medalData['BBCode']) + intval($week) * 10 + (intval($rank) - 1);
            $cssClass = 'medal ' . $medalData['cssClass'] . '_' . $rank;
            $altText = htmlspecialchars(sprintf('<table><tr><th>' . profile_medal_txt_cat . ':</th><td>%s</td></tr><tr><th>' . profile_medal_txt_week . ':</th><td>%s</td></tr><tr><th>' . profile_medal_txt_rank . ':</th><td>%s</td></tr><tr><th>' . profile_medal_txt_points . ':</th><td>%s</td></tr></table>', constant('medal_' . $medalData['textIndex']), $week, $rank, $points));
            if (!isset($this->bbCodeReplacedArray[$bbCode])) {
                $count = 0;
                $text = preg_replace('/\[#' . $bbCode . '\]/', sprintf($img, $cssClass, $altText), $text, 1, $count);
                if (0 < $count) {
                    $this->bbCodeReplacedArray[$bbCode] = $count;
                }
            }
        }
        $contractsStr = '';
        foreach ($this->contracts as $aid => $aname) {
            $contractsStr .= '<a href="alliance?id=' . $aid . '">' . $aname . '</a><br/>';
        }
        if (!isset($this->bbCodeReplacedArray['contracts'])) {
            $count = 0;
            $text = preg_replace('/\[contracts\]/', $contractsStr, $text, 1, $count);
            if (0 < $count) {
                $this->bbCodeReplacedArray['contracts'] = $count;
            }
        }
        return nl2br($text);
    }


}

// end file
?>
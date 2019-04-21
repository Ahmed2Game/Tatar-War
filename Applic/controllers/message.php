<?php
require_once LIBRARY_DIR . 'gameEngine/AuthController.php';

class Message_Controller extends AuthController
{
    public $showList;
    public $selectedTabIndex;
    public $errorText;
    public $receiver;
    public $subject;
    public $body;
    public $messageDate;
    public $messageTime;
    public $showFriendPane;
    public $friendsList;
    public $viewOnly;
    public $isInbox;
    public $sendMail;
    public $dataList;
    public $pageSize = 50;
    public $pageCount;
    public $pageIndex;

    public function __construct()
    {
        parent::__construct();
        $this->viewFile = 'msg';
        $this->viewData['contentCssClass'] = 'messages';
    }

    public function index()
    {
        if ($this->player->isAgent) {
            $this->is_redirect = TRUE;
            redirect('village1');
        }
        $this->sendMail = TRUE;
        $this->isInbox = TRUE;
        $this->viewOnly = FALSE;
        $this->showFriendPane = FALSE;
        $this->errorText = "";
        $this->showList = !(is_get('t') && is_numeric(get('t')) && intval(get('t')) == 1);
        $this->selectedTabIndex = is_get('t') && is_numeric(get('t')) && 1 <= intval(get('t')) && intval(get('t')) <= 4 ? intval(get('t')) : 0;
        $this->load_model('Friends', 'F');
        $this->friendList = array();
        $friends_player = $this->F->GetAllList($this->player->playerId);
        if ($friends_player != null) {
            foreach ($friends_player as $friend) {
                $toname = $this->F->getPlayerName($friend['toid']);
                $this->friendList[$friend['toid']] = $toname;
            }
        }
        if ($this->selectedTabIndex == 3 || $this->selectedTabIndex == 4) {
            $this->showList = FALSE;
        }
        $this->load_model('Message', 'm');
        if (!$_POST) {
            if (is_get('uid') && is_numeric(get('uid')) && 0 < intval(get('uid'))) {
                $this->receiver = $this->m->getPlayerNameById(intval(get('uid')));
                $this->showList = FALSE;
                $this->selectedTabIndex = 1;
            } else if (is_get('id') && is_numeric(get('id')) && 0 < intval(get('id'))) {
                if ($this->data['player_type'] == PLAYERTYPE_ADMIN || $this->data['player_type'] == PLAYERTYPE_HUNTER) {
                    $result = $this->m->getMessageAdmin(intval(get('id')));
                } else {
                    $result = $this->m->getMessage($this->player->playerId, intval(get('id')));
                }
                if ($result == null) {
                    $this->showList = TRUE;
                    $this->selectedTabIndex = 0;
                } else {
                    $this->viewOnly = TRUE;
                    $this->showList = FALSE;
                    $this->isInbox = $result['to_player_id'] == $this->player->playerId;
                    $this->sendMail = !$this->isInbox;
                    $this->receiver = $this->isInbox ? $result['from_player_name'] : $result['to_player_name'];
                    $this->subject = $this->getFilteredText($result['msg_title']);
                    $this->body = $this->getFilteredText($result['msg_body']);
                    $this->messageDate = $result['mdate'];
                    $this->messageTime = $result['mtime'];
                    $this->selectedTabIndex = $this->isInbox ? 0 : 2;
                    if ($this->isInbox && !$result['is_readed'] && !$this->player->isSpy) {
                        $this->m->markMessageAsReaded($this->player->playerId, intval(get('id')));
                        --$this->data['new_mail_count'];
                    }
                    unset($result);
                }
            }
        } else if (is_post('sm')) {
            $this->receiver = (strip_tags(trim(post('anxc'))));
            $this->subject = (strip_tags(trim(post('be'))));
            $this->body = (strip_tags(trim(post('message'))));
            $receiverData = $this->m->getPlayerIdByName($this->receiver);
            $receiverPlayerId = $receiverData['id'];
            $blockId = explode(',', $receiverData['block_player_id']);
            if (trim($this->receiver) == "") {
                $this->showList = FALSE;
                $this->selectedTabIndex = 1;
                $this->errorText = messages_p_noreceiver . "<p></p>";

            } elseif ($this->F->GetIfFriends($receiverPlayerId, $this->player->playerId) < 1) {
                $this->showList = FALSE;
                $this->selectedTabIndex = 1;
                $this->errorText = messages_p_notfriend . "<p></p>";

            } elseif (in_array($this->player->playerId, $blockId)) {
                $this->showList = FALSE;
                $this->selectedTabIndex = 1;
                $this->errorText = messages_p_block . "<p></p>";

            } else if ($this->data['total_people_count'] < 300 AND $this->data['player_type'] != PLAYERTYPE_ADMIN AND $this->data['player_type'] != PLAYERTYPE_HUNTER AND $receiverPlayerId != 1 AND $receiverPlayerId != 2) {
                $this->showList = FALSE;
                $this->selectedTabIndex = 1;
                $this->errorText = messages_p_people . "<p></p>";

            } else if (trim($this->body) == "") {
                $this->showList = FALSE;
                $this->selectedTabIndex = 1;
                $this->errorText = messages_p_nobody . "<p></p>";

            } else if (strtolower(trim($this->receiver)) == "[ally]" && 0 < intval($this->data['alliance_id']) && $this->hasAllianceSendMessageRole()) {
                $pids = trim($this->m->getAlliancePlayersId(intval($this->data['alliance_id'])));
                if ($pids != "") {
                    if ($this->subject == "") {
                        $this->subject = messages_p_emptysub;
                    }
                    $arr = explode(",", $pids);
                    foreach ($arr as $apid) {
                        if ($apid == $this->player->playerId) {
                            continue;
                        }
                        $this->m->sendMessage($this->player->playerId, $this->data['name'], $apid, $this->m->getPlayerNameById($apid), $this->subject, $this->body);
                    }
                    $this->showList = TRUE;
                    $this->selectedTabIndex = 2;
                }
            } else {
                if (0 < intval($receiverPlayerId)) {
                    if ($receiverPlayerId == $this->player->playerId) {
                        $this->showList = FALSE;
                        $this->selectedTabIndex = 1;
                        $this->errorText = "<b>" . messages_p_noloopback . "</b><p></p>";
                    } else {
                        if ($this->subject == "") {
                            $this->subject = messages_p_emptysub;
                        }
                        $this->m->sendMessage($this->player->playerId, $this->data['name'], $receiverPlayerId, $this->receiver, $this->subject, $this->body);
                        $this->showList = TRUE;
                        $this->selectedTabIndex = 2;
                    }
                } else {
                    $this->showList = FALSE;
                    $this->selectedTabIndex = 1;
                    $this->errorText = messages_p_notexists . " <b>" . $this->receiver . "</b><p></p>";
                }
            }
        } else if (is_post('rm')) {
            $this->receiver = (strip_tags(trim(post('anxc'))));
            $this->subject = (strip_tags(trim(post('be'))));
            $this->body = PHP_EOL . PHP_EOL . "___________________________________" . PHP_EOL . text_from_lang . " " . $this->receiver . ":" . PHP_EOL . PHP_EOL . (strip_tags(trim(post('message'))));
            preg_match("/^(re)\\^?([0-9]*):([\\w\\W]*)\$/", $this->subject, $matches);
            if (sizeof($matches) == 4) {
                $Num = is_numeric($matches[2]) ? $matches[2] : 0;
                $this->subject = ("re^" . ($Num + 1)) . ":" . $matches[3];
            } else {
                $this->subject = "re: " . $this->subject;
            }
            $this->showList = FALSE;
            $this->selectedTabIndex = 1;
        } else if (is_post('dm') && is_post('dm')) {
            foreach (post('dm') as $messageId) {
                if ($this->m->deleteMessage($this->player->playerId, $messageId)) {
                    --$this->data['new_mail_count'];
                }
            }
        } elseif ($this->selectedTabIndex == 3) {
            if (!$this->data['active_plus_account']) {
                $this->is_redirect = TRUE;
                redirect('plus?t=2');
            } else {
                $this->showList = FALSE;
                $this->saved = FALSE;
                if (is_post('notes')) {
                    $this->data['notes'] = post('notes');
                    $this->m->changePlayerNotes($this->player->playerId, $this->data['notes']);

                    $this->saved = TRUE;
                }
                $this->viewData['data'] = $this->data;
                $this->viewData['saved'] = $this->saved;
            }
        }

        if ($this->showList) {
            $rowsCount = $this->m->getMessageListCount($this->player->playerId, $this->selectedTabIndex == 0);
            $this->pageCount = 0 < $rowsCount ? ceil($rowsCount / $this->pageSize) : 1;
            $this->pageIndex = is_get('p') && is_numeric(get('p')) && intval(get('p')) < $this->pageCount ? intval(get('p')) : 0;
            $this->dataList = $this->m->getMessageList($this->player->playerId, $this->selectedTabIndex == 0, $this->pageIndex, $this->pageSize);
            if (0 < $this->data['new_mail_count']) {
                $this->data['new_mail_count'] = $this->m->syncMessages($this->player->playerId);
            }
            ###############View#################
            $this->viewData['pageCount'] = $this->pageCount;
            $this->viewData['pageIndex'] = $this->pageIndex;
            $this->viewData['dataList'] = $this->dataList;
            $this->viewData['getPreviousLink'] = $this->getPreviousLink();
            $this->viewData['getNextLink'] = $this->getNextLink();
            unset($this->dataList);
        }
        $this->viewData['showList'] = $this->showList;
        $this->viewData['showFriendPane'] = $this->showFriendPane;
        $this->viewData['viewOnly'] = $this->viewOnly;
        $this->viewData['sendMail'] = $this->sendMail;
        $this->viewData['errorText'] = $this->errorText;
        $this->viewData['isInbox'] = $this->isInbox;
        $this->viewData['receiver'] = $this->receiver;
        $this->viewData['subject'] = $this->subject;
        $this->viewData['body'] = $this->body;
        $this->viewData['messageDate'] = $this->messageDate;
        $this->viewData['messageTime'] = $this->messageTime;
        $this->viewData['selectedTabIndex'] = $this->selectedTabIndex;

        $this->viewData['friendList'] = $this->friendList;


        ## Pre-rendering
        if (is_get('uid')) {
            $this->viewData['villagesLinkPostfix'] .= "&uid=" . intval(get('uid'));
        }
        if (is_get('id')) {
            $this->viewData['villagesLinkPostfix'] .= "&id=" . intval(get('id'));
        }
        if (is_get('p')) {
            $this->viewData['villagesLinkPostfix'] .= "&p=" . intval(get('p'));
        }
        if (0 < $this->selectedTabIndex) {
            $this->viewData['villagesLinkPostfix'] .= "&t=" . $this->selectedTabIndex;
        }

        //End View

    }

    function getFilteredText($text)
    {
        $this->load_model('Wordsfilter', 'filter');
        return $this->filter->FilterWords($text);
    }

    function _hasAllianceRole($role)
    {
        $alliance_roles = trim($this->data['alliance_roles']);
        if ($alliance_roles == "") {
            return FALSE;
        }
        list($roleNumber, $roleName) = explode(' ', $alliance_roles, 2);
        return $roleNumber & $role;
    }

    function hasAllianceSendMessageRole()
    {
        return $this->_hasAllianceRole(ALLIANCE_ROLE_SENDMESSAGE);
    }

    function getNextLink()
    {
        $text = "»";
        if ($this->pageIndex + 1 == $this->pageCount) {
            return $text;
        }
        $link = "";
        if (0 < $this->selectedTabIndex) {
            $link .= "t=" . $this->selectedTabIndex;
        }
        if ($link != "") {
            $link .= "&";
        }
        $link .= "p=" . ($this->pageIndex + 1);
        $link = "message?" . $link;
        return "<a href=\"" . $link . "\">" . $text . "</a>";
    }

    function getPreviousLink()
    {
        $text = "«";
        if ($this->pageIndex == 0) {
            return $text;
        }
        $link = "";
        if (0 < $this->selectedTabIndex) {
            $link .= "t=" . $this->selectedTabIndex;
        }
        if (1 < $this->pageIndex) {
            if ($link != "") {
                $link .= "&";
            }
            $link .= "p=" . ($this->pageIndex - 1);
        }
        if ($link != "") {
            $link = "?" . $link;
        }
        $link = "message" . $link;
        return "<a href=\"" . $link . "\">" . $text . "</a>";
    }
}

?>
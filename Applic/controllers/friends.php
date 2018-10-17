<?php
load_game_engine('Auth');

class Friends_Controller extends AuthController
{

    var $pageSize = 20;
    var $pageCount = null;
    var $selectedTabIndex;
    public function __construct()
    {
        parent::__construct();
        $this->viewFile                    = 'friends';
        $this->viewData['contentCssClass'] = 'player';
    }

    public function index()
    {
        $this->load_model('Friends', 'F');
        $this->selectedTabIndex = is_get('t') && is_numeric(get('t')) && 1 <= intval(get('t')) && intval(get('t')) <= 2 ? intval(get('t')) : 0;
        if($this->selectedTabIndex == 0){
            if(is_get('delete') && is_numeric(get('delete')))
            {
                $this->F->DeleteRequest( $this->player->playerId, get('delete') );
            }
            $rowsCount       = $this->F->GetListcount( $this->player->playerId );
            $this->pageCount = (0 < $rowsCount ? ceil($rowsCount / $this->pageSize) : 1);
            $this->pageIndex = (is_get('p') && is_numeric(get('p'))) ? intval(get('p')) : 0;
            if ($this->pageCount <= $this->pageIndex)
            {
                $this->pageIndex = $this->pageCount - 1;
            }
            $friends_list = $this->F->GetList( $this->player->playerId, $this->pageIndex, $this->pageSize);
            $found = false;
            $rowIndex = 0;
            $dataList = array();
            foreach($friends_list as $value)
            {
                ++$rowIndex;
                $rank = $rowIndex + $this->pageIndex * $this->pageSize;
                $toname = $this->F->getPlayerName($value['toid']);
                $found = true;
                $dataList[$rank] = array(
                    'id' => $value['toid'],
                    'date' => $value['date'],
                    'usernam' => $toname
                );
            }
            
        }
        elseif($this->selectedTabIndex == 1)
        {
            if(is_get('defrom') && is_numeric(get('defrom')))
            {
                $this->F->CancelRequest( $this->player->playerId, get('defrom') );
            }
            elseif(is_get('from') && is_numeric(get('from')))
            {
                $this->F->AcceptRequest( $this->player->playerId, get('from') );
            }
            $rowsCount       = $this->F->GetListcount3( $this->player->playerId );
            $this->pageCount = (0 < $rowsCount ? ceil($rowsCount / $this->pageSize) : 1);
            $this->pageIndex = (is_get('p') && is_numeric(get('p'))) ? intval(get('p')) : 0;
            if ($this->pageCount <= $this->pageIndex)
            {
                $this->pageIndex = $this->pageCount - 1;
            }
            $friends_list = $this->F->GetCome( $this->player->playerId, $this->pageIndex, $this->pageSize);
            $found = false;
            $rowIndex = 0;
            $dataList = array();
            foreach($friends_list as $value)
            {
                ++$rowIndex;
                $rank = $rowIndex + $this->pageIndex * $this->pageSize;
                $fromname = $this->F->getPlayerName($value['fromid']);
                $found = true;
                $dataList[$rank] = array(
                    'id' => $value['fromid'],
                    'date' => $value['date'],
                    'usernam' => $fromname
                );
            }
            
        }
        elseif($this->selectedTabIndex == 2)
        {
            if(is_get('deto') && is_numeric(get('deto')))
            {
                $this->F->CancelRequest( get('deto'), $this->player->playerId );
            }
            elseif(is_post('name') || is_get('id'))
            {
                $to_id = is_get('id') ? get('id') : $this->F->getPlayerid( post('name') );
                if($this->F->RequestNum($this->player->playerId) >= 5)
                {
                    $this->viewData['error'] = LANGUI_FE_T2;
                }
                elseif($this->F->GetIfsendRequest($to_id , $this->player->playerId) > 0 || $this->F->GetIfsendRequest($this->player->playerId, $to_id ) > 0)
                {
                    $this->viewData['error'] = LANGUI_FE_T3;
                }
                elseif($this->F->GetIfFriends($to_id , $this->player->playerId) > 0)
                {
                    $this->viewData['error'] = LANGUI_FE_T4;
                }
                elseif($this->player->playerId == $to_id)
                {
                    $this->viewData['error'] = LANGUI_FE_T5;
                }
                elseif($to_id != null)
                {
                    $this->F->SendRequest( $to_id, $this->player->playerId);
                    $this->viewData['error'] = LANGUI_FE_T1;
                }
                
            }
            $rowsCount       = $this->F->GetListcount2( $this->player->playerId );
            $this->pageCount = (0 < $rowsCount ? ceil($rowsCount / $this->pageSize) : 1);
            $this->pageIndex = (is_get('p') && is_numeric(get('p'))) ? intval(get('p')) : 0;
            if ($this->pageCount <= $this->pageIndex)
            {
                $this->pageIndex = $this->pageCount - 1;
            }
            $friends_list = $this->F->GetSent( $this->player->playerId, $this->pageIndex, $this->pageSize);
            $found = false;
            $rowIndex = 0;
            $dataList = array();
            foreach($friends_list as $value)
            {
                ++$rowIndex;
                $rank = $rowIndex + $this->pageIndex * $this->pageSize;
                $fromname = $this->F->getPlayerName($value['toid']);
                $found = true;
                $dataList[$rank] = array(
                    'id' => $value['toid'],
                    'date' => $value['date'],
                    'usernam' => $fromname
                );
            }
        }
        $this->viewData['dataList'] = $dataList;
        $this->viewData['found'] = $found;
        $this->viewData['getPreviousLink'] = $this->getPreviousLink();
        $this->viewData['getNextLink'] = $this->getNextLink();
        $this->viewData['selectedTabIndex'] = $this->selectedTabIndex;
        unset($dataList);
        unset($friends_list);
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
        $link = 'friends?' . $link;
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
        $link = 'friends' . $link;
        return '<a href="' . $link . '">' . $text . '</a>';
    }
}
?>
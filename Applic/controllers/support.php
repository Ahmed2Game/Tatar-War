<?php
 load_game_engine('Auth');

class Support_Controller extends AuthController
{
	public function __construct()
	{
		parent::__construct();
		$this->viewFile = "support";
		$this->viewData['contentCssClass'] = "messages";
	}

	public function index()
    {
        $this->selectedTabIndex = ((((is_get('t') && is_numeric(get('t'))) && 0 <= intval(get('t'))) && intval(get('t')) <= 2) ? intval(get('t')) : 0);
        $this->load_model('Support', 'm');
        $this->viewData['selectedTabIndex'] = $this->selectedTabIndex;
        if (is_get('id'))
        {
            if (is_post('reply') && post('reply') != '')
            {
                $this->m->sendReply(intval(get('id')), post('reply'));
                $this->m->updateStatus(intval(get('id')), 2);
            }

            if (is_post('close'))
            {
                $this->m->updateStatus(intval(get('id')), 3);
            }

            $this->viewData['ticket'] = $this->m->GetMasegePyId($this->player->playerId, intval(get('id')));
            if ($this->viewData['ticket'] != null)
            {
                $this->viewData['reply'] = $this->m->GetReplaiesPyMasegeId(intval(get('id')));
            }
            else
            {
                $this->is_redirect = TRUE;
                redirect('support');
            }
        }
        elseif ($this->selectedTabIndex <= 1)
        {
            $status = ($this->selectedTabIndex == 1) ? '=3' : '!=3';
            $this->viewData['tickets'] = $this->m->GetMasegesPyplayerId($this->player->playerId, $status);
        }
        elseif ($this->selectedTabIndex == 2)
        {
            if (is_post('title') AND post('title') != '' AND post('content') != '')
            {
                $this->m->sendMasege($this->player->playerId, post('title'), post('content'), post('type'));
                $this->is_redirect = TRUE;
                redirect('support');
            }
        }
    }
}
?>
<?php
load_game_engine('Auth');

class Shownew_Controller extends AuthController
{

    public $saved = NULL;
    public $siteNews = NULL;

    public function __construct()
    {
        parent::__construct();
        $this->viewFile = 'shownew';
        $this->viewData['contentCssClass'] = 'messages';
    }

    public function index()
    {
        if (intval($this->data['new_gnews']) == 0 || $this->player->isSpy) {
            $this->is_redirect = TRUE;
            redirect('village1');
        } else {
            $this->load_model('Global', 'm');
            $this->siteNews = $this->m->getGlobalSiteNews();
            $this->viewData['siteNews'] = nl2br($this->siteNews);

        }
    }

}

?>

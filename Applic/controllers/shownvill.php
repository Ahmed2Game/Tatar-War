<?php
load_game_engine('Auth');

class Shownvill_Controller extends AuthController
{

    public $saved = NULL;
    public $siteNews = NULL;

    public function __construct()
    {
        parent::__construct();
        $this->viewFile = 'shownvill';
        $this->viewData['contentCssClass'] = 'messages';
    }

    public function index()
    {
        if (intval($this->data['create_nvil']) == 0 || $this->player->isSpy) {
            $this->is_redirect = TRUE;
            redirect('village1');
        } else {
            $this->load_model('Global', 'globalModel');
            $this->globalModel->resetNewVillageFlag($this->player->playerId);
            $this->viewData['getFlashContent'] = $this->getFlashContent(ASSETS_DIR . 'anm/war/nvil.swf', 500, 350);
        }
    }

}

?>

<?php
load_game_engine('Public');

class Index_Controller extends PublicController
{
    public function __construct()
    {
        parent::__construct();
        $this->viewFile = 'index';
    }


    public function index()
    {

        $this->viewData['activeStat'] = 0;
        if (isset($_GET['active'])) {
            $this->load_model('Activate', 'm');
            $this->viewData['activeStat'] = $this->m->doActivation($_GET['active']) ? 1 : 0;
        }

    }

}

?>
<?php
load_game_engine('Lite');

class Training_Controller extends LiteController
{

    public function __construct()
    {
        $this->layoutViewFile = 'layout/default';
        $this->viewFile = 'training';
        parent::__construct();
    }

    public function index()
    {
        if (!is_get('t')) {
            $tab = 1;
        } else {
            $this->viewData['tab'] = get('t');
        }
    }

}

?>
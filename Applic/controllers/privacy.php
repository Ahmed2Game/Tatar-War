<?php
load_game_engine('Public');

class Privacy_Controller extends PublicController
{
    public function __construct()
    {
        parent::__construct();
        $this->viewFile = 'privacy';
    }

    public function index()
    {

    }
}

?>
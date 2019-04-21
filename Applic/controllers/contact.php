<?php
load_game_engine('Public');

class Contact_Controller extends PublicController
{
    public function __construct()
    {
        parent::__construct();
        $this->viewFile = 'contact';
    }

    public function index()
    {

    }
}

?>
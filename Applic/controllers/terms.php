<?php
 load_game_engine('Public');

class Terms_Controller extends PublicController
{

    public function __construct()
    {
        parent::__construct();
        $this->viewFile = 'terms';
		$this->viewData['contentCssClass'] = 'login';
    }

    public function index()
    {
    }

}
?>

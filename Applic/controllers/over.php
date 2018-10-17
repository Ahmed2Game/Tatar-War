<?php
 load_game_engine('Public');

class Over_Controller extends PublicController
{
    public $playerData = NULL;

    public function __construct()
    {
        parent::__construct();
        $this->viewFile = "over";
        $this->viewData['contentCssClass'] = "messages";
    }

    public function index()
    {
        if ( !$this->global_model->isGameOver() )
        {
            exit( 0 );
        }
        else
        {
            $this->load_model('Profile', 'm');
            $this->playerData = $this->m->getWinnerPlayer();
            
            $this->viewData['playerData'] = $this->playerData;
        }
    }

}
?>

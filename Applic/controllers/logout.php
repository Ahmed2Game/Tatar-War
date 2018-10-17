<?php
 load_game_engine('Auth');
class Logout_Controller extends AuthController
{

    public function __construct()
    {
        parent::__construct();
        $this->viewFile = "logout";
        $this->viewData['contentCssClass'] = "logout";
    }

    public function index()
    {
        if ( $this->player->isSpy )
        {
            $gameStatus = $this->player->gameStatus;
            $uid = $this->player->prevPlayerId;

            $this->player->playerId = $uid;
            $this->player->isAgent = FALSE;
			$this->player->isSpy = FALSE;
            $this->player->gameStatus = $gameStatus;
            $this->player->save();
            $this->is_redirect = TRUE;
            redirect( "village1" );
        }
        else
        {
            $this->player->logout();
            unset( $this->player );
            $this->player = NULL;
            $this->viewData['player'] = NULL;
        }
    }

}
?>

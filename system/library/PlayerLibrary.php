<?php

class PlayerLibrary
{

    public $prevPlayerId = NULL;
    public $playerId = NULL;
    public $isAgent = NULL;
    public $isSpy = FALSE;
    public $gameStatus = NULL;
    
    public function getInstance()
    {
        session_start();
        $key = getPlayerKey();
        return isset( $_SESSION[$key] ) ? $_SESSION[$key] : NULL;
    }

    public function save()
    {
        $_SESSION[getPlayerKey()] = $this;
    }

    public function logout()
    {
        $_SESSION[getPlayerKey()] = NULL;
        unset( $_SESSION );
        session_destroy();
    }

}

// END

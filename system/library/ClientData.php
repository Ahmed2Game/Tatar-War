<?php
class ClientData
{

    public $uname = NULL;
    public $upwd = NULL;
    public $uiLang = NULL;
    public $showLevels = FALSE;

    public function __construct()
    {
        $this->uiLang = $GLOBALS['gameConfig']['system']['lang'];
    }

    public function getInstance()
    {
        $cookie = new ClientData();
        $key = getPlayerKey();
        if ( isset( $_COOKIE[$key] ) )
        {
            $obj = unserialize( base64_decode( $_COOKIE[$key] ) );
            if ( $obj != NULL && ($obj instanceof ClientData ) )
            {
                $cookie->uname = $obj->uname;
                $cookie->upwd = $obj->upwd;
            }
        }
        if ( isset( $_COOKIE['lvl'] ) )
        {
            $cookie->showLevels = $_COOKIE['lvl'] == "1";
        }
        if ( isset( $_COOKIE['lng'] ) )
        {
            $cookie->uiLang = $_COOKIE['lng'] == "ar" ? "ar" : "en";
        }
        return $cookie;
    }

    public function save()
    {
        
        unset($this->uiLang);
        setcookie( getPlayerKey(), base64_encode( serialize( $this ) ), time() + 5 * 12 * 30 * 24 * 3600 );
        $this->uiLang = $GLOBALS['gameConfig']['system']['lang'];
    }

    public function clear()
    {
        $this->uname = "";
        $this->upwd = "";
        setcookie(getPlayerKey());
        setcookie( "lvl" );
        setcookie( "lng" );
    }

}

?>
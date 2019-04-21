<?php

class BaseController extends Controller
{
    public $is_ajax = FALSE;
    public $layoutViewFile = NULL;
    public $viewFile = NULL;
    public $viewData = array();
    public $is_redirect = FALSE;
    public $player;
    public $setupMetadata;
    public $gameMetadata;
    public $gameSpeed;

    public function __construct()
    {
        global $gameConfig;


        $this->load_library('ClientData', 'cookie');
        $cookie = $this->cookie->getInstance();
        $this->viewData['lang'] = $cookie->uiLang;
        $this->viewData['title'] = $gameConfig['page'][$cookie->uiLang . '_title'];
        $this->viewData['metatag'] = $gameConfig['page'][$cookie->uiLang . '_meta'];


        $this->load_library('PlayerLibrary');
        $this->player = $this->PlayerLibrary->getInstance();

        /*$session_timeout = $this->gameMetadata['session_timeout']; // in minute(s)
        @ini_set ('session.gc_maxlifetime', $session_timeout * 60); // set the session timeout (in seconds)
        @session_cache_expire($session_timeout); // expiretime is the lifetime in minutes*/

        global $serv;
        if ($serv != NULL) {

            $this->setupMetadata = $this->viewData['setupMetadata'] = $GLOBALS['SetupMetadata'];
            $this->gameMetadata = $this->viewData['gameMetadata'] = $GLOBALS['GameMetadata'];
            $this->gameSpeed = $this->viewData['gameSpeed'] = $this->gameMetadata['game_speed'];
            $this->load_model('global', 'global_model');
            $this->viewData['GameOver'] = $this->global_model->isGameOver();
            if ($this->viewData['GameOver']) {
                $siteReset = $this->global_model->SiteReset();
                if (!is_get('_a1_') && $siteReset['remainingTimeInSeconds'] <= 0) {
                    $this->load_model('Queuejob', 'qj');
                    $this->qj->processQueue();
                }
                $this->viewData['siteReset'] = secondsToString($siteReset['remainingTimeInSeconds']);
            }
        }
    }


    public function __destruct()
    {
        if (!$this->is_ajax && !$this->is_redirect) {
            // Output template
            $tpl = new View;

            $tpl->assign($this->viewData);

            if ($this->layoutViewFile != NULL && $this->viewFile != NULL) {
                $tpl->assign('content', $tpl->draw($this->viewFile, $return_string = true));
                $tpl->draw($this->layoutViewFile);
            } else if ($this->layoutViewFile != NULL) {
                $tpl->draw($this->layoutViewFile);
            } else if ($this->viewFile != NULL) {
                $tpl->draw($this->viewFile);
            }
        }
    }

}

?>
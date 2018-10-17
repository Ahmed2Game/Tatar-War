<?php
require_once LIBRARY_DIR.'gameEngine/AuthController.php';
class Warsm_Controller extends AuthController
{

    public $showTroopsTable = FALSE;
    public $showWarResult = FALSE;
    public $errorText = "";
    public $troopsMetadata = NULL;
    public $warResult = NULL;

    public function __construct()
    {
        parent::__construct();
        $this->viewFile = "warsm";
        $this->viewData['contentCssClass'] = "warsim";
    }

    public function index()
    {
        if ( is_post('a1') )
        {
            if ( intval( post('a1') ) != 1 && intval( post('a1') ) != 2 && intval( post('a1') ) != 3
                && intval( post('a1') ) != 7 && intval( post('a1') ) != 6 )
            {
                $this->errorText = war_sim_noattack;
            }
            else if ( intval( post('ktyp') ) != 1 && intval( post('ktyp') ) != 2 )
            {
                $this->errorText = war_sim_nobattletype;
            }
            else if ( !is_post('a2') || sizeof($_POST['a2']) == 0 )
            {
                $this->errorText = war_sim_nodefense;
            }
            else
            {
                foreach ( $_POST['a2'] as $tribeId => $v )
                {
                    if ( $tribeId != 1 && $tribeId != 2 && $tribeId != 3 && $tribeId != 4 && $tribeId != 7 && $tribeId != 6 )
                    {
                        $this->errorText = war_sim_nodefense2;
                    }
                }
                $this->troopsMetadata = $this->gameMetadata['troops'];
                $this->showTroopsTable = TRUE;
                $this->showWarResult = FALSE;
                if ( !is_post('t1') )
                {
                    $this->viewData['showWarResult'] = $this->showWarResult;
                    $this->viewData['errorText'] = $this->errorText;
                    $this->viewData['showTroopsTable'] = $this->showTroopsTable;

                    //
                    $troops = array();
                    $tribeId = isset($_POST['a1']) ? intval($_POST['a1']) : FALSE;
                    foreach ($this->troopsMetadata as $troopId => $troopMetadata)
                    {
                        if ($troopMetadata['for_tribe_id'] != $tribeId) {
                            continue;
                        }
                        $troops[$troopId] = $troopMetadata;
                    }
                    $this->viewData['troops'] = $troops;
                    $this->viewData['for_tribe_id_exists'] = isset($this->gameMetadata['items'][35]['for_tribe_id'][$tribeId]);

                    if(isset($_POST['a2']))
                    {
                        $troops_t2 = array();
                        foreach ($_POST['a2'] as $tribeId => $v)
                        {
                            foreach ($this->troopsMetadata as $troopId => $troopMetadata)
                            {
                                if ($troopMetadata['for_tribe_id'] != $tribeId) {
                                    continue;
                                }
                                $troops_t2[$tribeId][$troopId] = $troopMetadata;
                            }
                        }
                        $this->viewData['troops_t2'] = $troops_t2;
                    }
                    return;
                }

                $this->load_model('Battles_Warbattle', 'm');
                if ( is_post('h_off_bonus1') && 0 < intval( post('h_off_bonus1') ) )
                {
                    $this->showWarResult = TRUE;
                }
                $troops = array();
                $troopsPower = array();
                foreach ( $_POST['t1'] as $tribeId => $troopArray )
                {
                    foreach ( $troopArray as $tid => $tnum )
                    {
                        if ( 0 < $tnum )
                        {
                            $this->showWarResult = TRUE;
                        }
                        $troops[$tid] = intval( $tnum );
                        $troopsPower[$tid] = 0;
                    }
                }
                if ( !$this->showWarResult )
                {
                    $this->viewData['showWarResult'] = $this->showWarResult;
                    $this->viewData['errorText'] = $this->errorText;

                    $this->viewData['showTroopsTable'] = $this->showTroopsTable;
                    $tribeId = isset($_POST['a1']) ? intval($_POST['a1']) : FALSE;

                    $troops = array();
                    $tribeId = isset($_POST['a1']) ? intval($_POST['a1']) : FALSE;
                    foreach ($this->troopsMetadata as $troopId => $troopMetadata)
                    {
                        if ($troopMetadata['for_tribe_id'] != $tribeId) {
                            continue;
                        }
                        $troops[$troopId] = $troopMetadata;
                    }
                    $this->viewData['troops'] = $troops;
                    $this->viewData['for_tribe_id_exists'] = isset($this->gameMetadata['items'][35]['for_tribe_id'][$tribeId]);

                    if(isset($_POST['a2']))
                    {
                        $troops_t2 = array();
                        foreach ($_POST['a2'] as $tribeId => $v)
                        {
                            foreach ($this->troopsMetadata as $troopId => $troopMetadata)
                            {
                                if ($troopMetadata['for_tribe_id'] != $tribeId) {
                                    continue;
                                }
                                $troops_t2[$tribeId][$troopId] = $troopMetadata;
                            }
                        }
                        $this->viewData['troops_t2'] = $troops_t2;
                    }
                    return;
                }
                $peopleCount = is_post('ew1') ? intval( post('ew1') ) : 0;
                $heroLevel = is_post('h_off_bonus1') ? intval( post('h_off_bonus1') ) : 0;
                $wringerPower = is_post('kata') ? intval( post('kata') ) : 0;
                $attackTroops = $this->m->_getTroopWithPower( 1, $troops, $troopsPower, TRUE, $heroLevel, $peopleCount, $wringerPower, 0 );
                $peopleCount = is_post('ew2') ? intval( post('ew2') ) : 0;
                $wallLevel = is_post('wall1') ? intval( post('wall1') ) : 0;
                $totalDefensePower = 0;
                $defenseTroops = array();
                foreach ( $_POST['t2'] as $tribeId => $troopArray )
                {
                    $troops = array();
                    $troopsPower = array();
                    foreach ( $troopArray as $tid => $tnum )
                    {
                        $troops[$tid] = intval( $tnum );
                        $troopsPower[$tid] = is_post('f2') && isset($_POST['f2'][$tribeId]) && isset( $_POST['f2'][$tribeId][$tid] ) ? intval( $_POST['f2'][$tribeId][$tid] ) : 0;
                    }
                    $defenseTroops[$tribeId] = $this->m->_getTroopWithPower( 1, $troops, $troopsPower, FALSE, 0, $peopleCount, 0, $wallLevel );
                    $totalDefensePower += $defenseTroops[$tribeId]['total_power'];
                }
                $this->warResult = $this->m->getWarResult( $attackTroops, $defenseTroops, $totalDefensePower, is_post('ktyp') && intval( post('ktyp') ) == 2 );
                //$m->dispose();
            }
        }

        ## View
        $this->viewData['showWarResult'] = $this->showWarResult;
        if($this->viewData['showWarResult'])
        {
            $this->viewData['warResult'] = $this->warResult;
        }
        $this->viewData['showTroopsTable'] = $this->showTroopsTable;

        $tribeId = is_post('a1') ? intval(post('a1')) : FALSE;
        $this->viewData['for_tribe_id_exists'] = isset($this->gameMetadata['items'][35]['for_tribe_id'][$tribeId]);
        //
        if( is_post('a1') && is_post('ktyp') && is_post('a2') )
        {
            $troops = array();
            foreach ($this->troopsMetadata as $troopId => $troopMetadata)
            {
                if ($troopMetadata['for_tribe_id'] != $tribeId) {
                    continue;
                }
                $troops[$troopId] = $troopMetadata;
            }
            $this->viewData['troops'] = $troops;
        }

        //
        if(is_post('a1') && is_post('ktyp') && is_post('a2'))
        {
            $troops_t2 = array();
            foreach ($_POST['a2'] as $tribeId => $v)
            {
                foreach ($this->troopsMetadata as $troopId => $troopMetadata)
                {
                    if ($troopMetadata['for_tribe_id'] != $tribeId) {
                        continue;
                    }
                    $troops_t2[$tribeId][$troopId] = $troopMetadata;
                }
            }
            $this->viewData['troops_t2'] = $troops_t2;
        }


        $this->viewData['errorText'] = $this->errorText;

    }

}
?>

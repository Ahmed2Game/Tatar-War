<?php
load_game_engine('Auth');

class Resources_Controller extends AuthController
{
    public $isAdmin = NULL;
    public $villageId = NULL;
    public $villageName = NULL;
    public $playerName = NULL;
    public $resources = NULL;
    public $msgText = NULL;

    public function __construct()
    {
        $this->customLogoutAction = TRUE;
        parent::__construct();

        if ($this->player == NULL) {
            exit(0);
        }
        $this->viewFile = "resources";
        $this->layoutViewFile = "layout/popup";
    }

    public function index()
    {
        $this->msgText = "";
        $this->isAdmin = $this->data['player_type'] == PLAYERTYPE_ADMIN;
        if (!$this->isAdmin) {
            exit(0);
        } else {
            $this->villageId = is_get('avid') ? intval(get('avid')) : 0;
            if ($this->villageId <= 0) {
                exit(0);
            } else {
                $this->load_model('Resources', 'm');
                if (is_post('r1')) {
                    $r1 = is_post('r1') && 0 <= intval(post('r1')) ? intval(post('r1')) : 0 - 1;
                    $r2 = is_post('r2') && 0 <= intval(post('r2')) ? intval(post('r2')) : 0 - 1;
                    $r3 = is_post('r3') && 0 <= intval(post('r3')) ? intval(post('r3')) : 0 - 1;
                    $r4 = is_post('r4') && 0 <= intval(post('r4')) ? intval(post('r4')) : 0 - 1;
                    $this->m->updateVillageResources($this->villageId, array(
                        '1' => $r1,
                        '2' => $r2,
                        '3' => $r3,
                        '4' => $r4
                    ));
                    $this->msgText = data_saved;
                }
                $row = $this->m->getVillageData($this->villageId);
                if ($row == NULL || intval($row['player_id']) == 0 || $row['is_oasis']) {
                    exit(0);
                } else {
                    $this->villageName = $row['village_name'];
                    $this->playerName = $row['player_name'];
                    $this->resources = array();
                    $elapsedTimeInSeconds = $row['elapsedTimeInSeconds'];
                    $r_arr = explode(",", $row['resources']);
                    foreach ($r_arr as $r_str) {
                        $r2 = explode(" ", $r_str);
                        $prate = floor($r2[4] * (1 + $r2[5] / 100)) - ($r2[0] == 4 ? $row['crop_consumption'] : 0);
                        $current_value = floor($r2[1] + $elapsedTimeInSeconds * ($prate / 3600));
                        if ($r2[2] < $current_value) {
                            $current_value = $r2[2];
                        }
                        $this->resources[$r2[0]] = array(
                            "current_value" => $current_value,
                            "store_max_limit" => $r2[2],
                            "store_init_limit" => $r2[3],
                            "prod_rate" => $r2[4],
                            "prod_rate_percentage" => $r2[5],
                            "calc_prod_rate" => $prate
                        );
                    }

                }
            }
        }

        $this->viewData['villageId'] = $this->villageId;
        $this->viewData['resources'] = $this->resources;
        $this->viewData['msgText'] = $this->msgText;
        $this->viewData['playerName'] = $this->playerName;
        $this->viewData['villageName'] = $this->villageName;
    }
}

?>
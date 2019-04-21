<?php
load_game_engine('ProgressVillage');

class Farm_Controller extends ProgressVillageController
{
    public function __construct()
    {
        parent::__construct();
        $this->viewFile = 'farm';
        $this->viewData['contentCssClass'] = 'reports';
    }

    public function index()
    {
        if (!$this->data['active_plus_account']) {
            $this->is_redirect = TRUE;
            redirect('plus?t=2');
        }
        $this->load_model('Farm', 'm');
        $this->viewData['selectedTabIndex'] = is_get('t') && is_numeric(get('t')) && 0 <= intval(get('t')) && intval(get('t')) <= 1 ? intval(get('t')) : 0;
        $t_arr = explode('|', $this->data['troops_num']);
        $t2_arr = explode(':', $t_arr[0]);
        $t2_arr = explode(',', $t2_arr[1]);
        $_c = 0;
        $troops = array();
        foreach ($t2_arr as $t2_str) {
            list($tid, $tnum) = explode(' ', $t2_str);
            if ($tid != 4 && $tid != 14 && $tid != 23 && $tid != 103 && $tid != 99) {
                ++$_c;
                $troops[] = $tid;
            }
            if ($_c == 5) {
                break;
            }
        }
        $this->viewData['troops'] = $troops;
        if ($this->viewData['selectedTabIndex'] == 0) {
            /*if (isset($_POST['list']))
            {
                if (is_array($_POST['list']))
                {
                    foreach ($_POST['list'] as $key => $value)
                    {
                        echo $value;
                        echo "<br>";
                    }
                }
            }*/
            if (is_get('del')) {
                if (get('del') >= 1) {
                    $this->m->DeleteThisFarm($_GET['del'], $this->player->playerId);
                }
            }
            $result = $this->m->getFarmList($this->player->playerId, $this->data['selected_village_id']);
            $this->viewData['found'] = 0;
            if ($result != NULL) {
                $listData = array();
                foreach ($result as $value) {
                    $villageData = $this->m->getVillageDataById($value['to_village_id']);
                    $listData[$value['id']] = array(
                        'name' => ($villageData['village_name'] == NULL) ? (($villageData['player_id'] == NULL) ? v2v_p_placetyp2 : v2v_p_placetyp1) : $villageData['village_name'],
                        'time' => $this->getDistance($this->data['rel_x'], $this->data['rel_y'], $villageData['rel_x'], $villageData['rel_y']),
                        'vid' => $value['to_village_id'],
                        'troop' => $value['troops'],
                        'troops' => array()
                    );
                    $tr_arr = explode(',', $value['troops']);
                    foreach ($tr_arr as $tr_str) {
                        list($tid, $tnum) = explode(' ', $tr_str);
                        if ($tnum == 0) {
                            continue;
                        }
                        $listData[$value['id']]['troops'][$tid] = $tnum;
                    }
                    $this->viewData['found'] = 1;
                }
                $this->viewData['listData'] = $listData;
            }
        } elseif ($this->viewData['selectedTabIndex'] == 1) {
            if ($_POST) {
                if (isset($_POST['x']) && isset($_POST['y']) && trim($_POST['x']) != '' && trim($_POST['y']) != '') {
                    $vid = $this->__getVillageId($this->setupMetadata['map_size'], $this->__getCoordInRange($this->setupMetadata['map_size'], intval($_POST['x'])), $this->__getCoordInRange($this->setupMetadata['map_size'], intval($_POST['y'])));
                    $villageRow = $this->m->getVillageDataById($vid);
                } else {
                    $this->viewData['error'] = LANGUI_FARM_T18;
                    return;
                }
                $troopStr = '';
                $tidtrue = TRUE;
                $AllNum = 0;
                foreach ($_POST['t'] as $key => $value) {
                    if ($troopStr != '') {
                        $troopStr .= ',';
                    }
                    if (!in_array($key, $troops)) {
                        $tidtrue = FALSE;
                    }
                    $AllNum += intval($value);
                    $troopStr .= intval($key) . ' ' . intval($value);
                }
                if ($villageRow == NULL || $villageRow['id'] == $this->data['selected_village_id']) {
                    $this->viewData['error'] = LANGUI_FARM_T9;
                    return;
                } else if ($villageRow['player_id'] == 0 && !$villageRow['is_oasis']) {
                    $this->viewData['error'] = LANGUI_FARM_T10;
                    return;
                } else if (!$tidtrue) {
                    $this->viewData['error'] = LANGUI_FARM_T11;
                    return;
                } else if ($AllNum == 0) {
                    $this->viewData['error'] = LANGUI_FARM_T12;
                    return;
                } else if ($this->m->isFarmFull($this->data['selected_village_id'], $this->player->playerId) >= 150) {
                    $this->viewData['error'] = LANGUI_FARM_T13;
                    return;
                } else {
                    $this->m->addFarm($this->player->playerId, $this->data['selected_village_id'], $vid, $troopStr);
                    $this->viewData['error'] = LANGUI_FARM_T14;
                }
            }
        }
    }

    public function __getCoordInRange($map_size, $x)
    {
        if ($x >= $map_size) {
            $x -= $map_size;
        } else if ($x < 0) {
            $x = $map_size + $x;
        }
        return $x;
    }

    public function __getVillageId($map_size, $x, $y)
    {
        return (($x * $map_size) + ($y + 1));
    }

    function getDistance($dx, $dy, $rx, $ry)
    {
        $speed = $this->gameMetadata['troops'][1]['velocity'];
        $factor = $this->gameMetadata['game_speed'];
        $speed = $speed * $factor;
        $distance = getdistance($dx, $dy, $rx, $ry, $this->setupMetadata['map_size'] / 2);
        $dis = intval($distance / $speed * 60);
        return round($dis, 1);
    }
}

?>
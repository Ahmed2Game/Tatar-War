<?php
load_game_engine('Auth');

class Villages_Controller extends AuthController
{
    public $selectedTabIndex = null;
    public $villagesdata = null;
    public $village = null;

    public function __construct()
    {
        parent::__construct();
        $this->viewFile = "villages";
        $this->viewData['contentCssClass'] = "village3";
    }

    public function index()
    {
        $this->selectedTabIndex = ((((is_get('t') && is_numeric(get('t'))) && 0 <= intval(get('t'))) && intval(get('t')) <= 5) ? intval(get('t')) : 0);

        $this->load_model('Villages', 'm');
        if ($this->selectedTabIndex == 0) {
            $this->villagesdata = $this->m->getVillagesData($this->player->playerId);
            $this->village = array();

            foreach ($this->villagesdata as $result) {
                $vid = $result['id'];

                $build = $this->m->getVillagesUpdate($vid, QS_BUILD_CREATEUPGRADE);
                if ($build <= 0) {
                    $this->village[$vid]['build'] = FALSE;
                } else {
                    $this->village[$vid]['build'] = array();
                    while (0 < $build--) {
                        $this->village[$vid]['build'][] = 0;
                    }
                }

                $reinforce = $this->m->getVillagesRein($vid, QS_WAR_REINFORCE);
                if ($reinforce <= 0) {
                    $this->village[$vid]['reinforce'] = FALSE;
                } else {
                    if ($reinforce <= 4) {
                        $this->village[$vid]['reinforce'] = array();
                        while (0 < $reinforce--) {
                            $this->village[$vid]['reinforce'][] = 0;
                        }
                    } else {
                        $this->village[$vid]['reinforce'] = $reinforce;
                    }
                }

                $attack = $this->m->getVillagesAttac($vid);
                $attack2 = $this->m->getVillagesUpdate($vid, QS_WAR_ATTACK_PLUNDER);
                if (($attack + $attack2) <= 0) {
                    $this->village[$vid]['attack_attack2'] = FALSE;
                } else {
                    $this->village[$vid]['attack_attack2'] = TRUE;
                    if (($attack + $attack2) <= 4) {
                        $this->village[$vid]['attack'] = array();
                        $this->village[$vid]['attack2'] = array();
                        while (0 < $attack--) {
                            $this->village[$vid]['attack'][] = 0;
                        }
                        while (0 < $attack2--) {
                            $this->village[$vid]['attack2'][] = 0;
                        }
                    } else {
                        $this->village[$vid]['attack'] = 0;
                        $this->village[$vid]['attack2'] = 0;
                        if ($attack >= 1) {
                            $this->village[$vid]['attack'] = $attack;
                        }
                        if ($attack2 >= 1) {
                            $this->village[$vid]['attack2'] = $attack2;
                        }
                    }
                }

                $merchant = $this->m->getVillagesMar($vid);
                if ($merchant <= 0) {
                    $this->village[$vid]['merchant'] = FALSE;
                } else {
                    if ($merchant <= 2) {
                        $this->village[$vid]['merchant'] = array();
                        while (0 < $merchant--) {
                            $this->village[$vid]['merchant'][] = 0;
                        }
                    } else {
                        $this->village[$vid]['merchant'] = $merchant;
                    }
                }

                $this->village[$vid]['name'] = $result['village_name'];
                $this->village[$vid]['people'] = $result['people_count'];
            }
            $this->viewData['village'] = $this->village;
            unset($this->villagesdata);
            unset($this->village);
        }

        if ($this->selectedTabIndex == 1 || $this->selectedTabIndex == 2 || $this->selectedTabIndex == 3) {
            foreach ($this->playerVillages as $vid => $pvillage) {
                $row = $this->m->getVillageData($vid);
                $elapsedTimeInSeconds = $row['elapsedTimeInSeconds'];
                $r_arr = explode(",", $row['resources']);
                $this->load_model('Artefacts', 'A');
                $crop = $this->A->CropAndRes($row['player_id'], $vid, 5);
                $res = $this->A->CropAndRes($row['player_id'], $vid, 7);
                foreach ($r_arr as $r_str) {
                    $r2 = explode(" ", $r_str);
                    $prate = floor($r2[4] * (1 + ($r2[5] + $res) / 100)) - ($r2[0] == 4 ? $row['crop_consumption'] * $crop : 0);
                    $current_value = floor($r2[1] + $elapsedTimeInSeconds * ($prate / 3600));
                    if ($r2[2] < $current_value) {
                        $current_value = $r2[2];
                    }
                    $this->resourcesv4[$r2[0]] = array(
                        "current_value" => $current_value,
                        "store_max_limit" => $r2[2],
                        "store_init_limit" => $r2[3],
                        "prod_rate" => $r2[4],
                        "prod_rate_percentage" => $r2[5],
                        "calc_prod_rate" => $prate
                    );
                }
                $b_arr = explode(',', $row['buildings']);
                foreach ($b_arr as $b_str) {
                    $b2_arr = explode(' ', $b_str);
                    if ($b2_arr[0] == 17) {
                        $itemLevel = $b2_arr[1];
                        $total_merchants_num = $this->gameMetadata['items'][17]['levels'][$itemLevel - 1]['value'];
                        $exist_num = $total_merchants_num - $this->queueModel->tasksInQueue['out_merchants_num'] - $row['offer_merchants_count'];
                    }
                }

                $total_merchants_num = isset($total_merchants_num) ? $total_merchants_num : 0;
                $exist_num = isset($exist_num) ? $exist_num : 0;
                $this->village[$vid] = $this->resourcesv4;
                $this->village[$vid]['name'] = $row['village_name'];
                $this->village[$vid]['merchant'] = array('total_num' => $total_merchants_num, 'exits_num' => $exist_num);
            }

            $this->viewData['village'] = $this->village;
        }

        if ($this->selectedTabIndex == 4) {
            foreach ($this->playerVillages as $vid => $pvillage) {
                $row = $this->m->getVillageData($vid);
                $t_arr = explode("|", $row['troops_num']);
                $t2_arr = explode(":", $t_arr[0]);
                $n_arr = explode(",", $t2_arr[1]);
                $troop_num = array();
                $troop_num['hero'] = FALSE;
                foreach ($n_arr as $n2_arr) {
                    list($tid, $tnum) = explode(' ', $n2_arr);
                    if ($tnum == 0 - 1) {
                        $troop_num['hero'] = true;
                    }
                    if ($tid != 99) {
                        $troop_num['troop'][$tid] = $tnum;
                    }
                }
                $this->village[$vid] = $troop_num;
                $this->village[$vid]['name'] = $row['village_name'];
            }

            $this->viewData['village'] = $this->village;

            $all_troop = array();
            foreach ($this->village as $vdata) {
                foreach ($vdata['troop'] as $k => $v) {
                    if (isset($all_troop[$k])) {
                        $all_troop[$k] += $v;
                        continue;
                    } else {
                        $all_troop[$k] = $v;
                    }
                }
            }
            ksort($all_troop, SORT_NUMERIC);
            $this->viewData['all_troop'] = $all_troop;
        }

        $this->viewData['selectedTabIndex'] = $this->selectedTabIndex;
        $this->viewData['data'] = $this->data;
    }
}


?>
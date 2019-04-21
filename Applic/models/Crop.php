<?php

class Crop_Model extends Model
{
    public function deleteCrop($task)
    {
        $villages = db::get_all('SELECT v.id, v.player_id, v.crop_consumption, v.resources, v.troops_num, v.village_oases_id, TIME_TO_SEC(TIMEDIFF(NOW(), v.last_update_date)) elapsedTimeInSeconds  FROM p_villages v WHERE v.crop_consumption-v.people_count>0 AND v.crop_consumption>800 AND v.is_oasis=0 AND v.is_special_village=0');
        foreach ($villages as $value) {
            $this->load_model('Artefacts', 'A');
            $crop2 = $this->A->CropAndRes($value['player_id'], $value['id'], 5);
            $res2 = $this->A->CropAndRes($value['player_id'], $value['id'], 7);
            $elapsedTimeInSeconds = $value['elapsedTimeInSeconds'];
            $resources2 = explode(',', $value['resources']);
            if (!isset($resources2[3])) {
                continue;
            }
            $crop = explode(' ', $resources2[3]);
            $prate = floor($crop[4] * (1 + ($crop[5] + $res2) / 100)) - floor($value['crop_consumption'] * $crop2);
            $current_value = floor($crop[1] + $elapsedTimeInSeconds * ($prate / 3600));
            if ($current_value < 0 && $prate < 0) {
                $killCrop = $killCrop2 = ceil(abs($prate) / 10);
                $training_resources = array('1' => 0, '2' => 0, '3' => 0, '4' => 0);
                if ($value['village_oases_id'] != '') {
                    $oasis = explode(',', $value['village_oases_id']);
                    foreach ($oasis as $oasisId) {
                        $oasisTroops = db::get_field('SELECT v.troops_num FROM p_villages v WHERE v.id=:id', array(
                            'id' => $oasisId
                        ));
                        if ($oasisTroops != NULL) {
                            $troops = explode('|', $oasisTroops);
                            krsort($troops);
                            $newtroops = array();
                            foreach ($troops as $value2) {
                                $vill = explode(':', $value2);
                                $tro = explode(',', $vill[1]);
                                $newtroops[$vill[0]] = array(
                                    "hero" => array(
                                        "has" => FALSE,
                                        "id" => 0,
                                        "crop" => 0
                                    ),
                                    "all_de" => FALSE,
                                    "has_de" => FALSE,
                                    "all_crop" => 0,
                                    "troops" => array()
                                );
                                foreach ($tro as $value3) {
                                    list($tid, $tnum) = explode(' ', $value3);
                                    if ($tnum == 0 - 1) {
                                        $newtroops[$vill[0]]['hero']['has'] = TRUE;
                                        $newtroops[$vill[0]]['hero']['id'] = $tid;
                                        $newtroops[$vill[0]]['hero']['crop'] = $GLOBALS['GameMetadata']['troops'][$tid]['crop_consumption'];
                                        $newtroops[$vill[0]]['all_crop'] += $newtroops[$vill[0]]['hero']['crop'];
                                        continue;
                                    }
                                    $tcrop = $tnum * $GLOBALS['GameMetadata']['troops'][$tid]['crop_consumption'];
                                    $newtroops[$vill[0]]['troops'][$tid] = array(
                                        'num' => $tnum,
                                        'dead' => 0,
                                        'crop' => $tcrop
                                    );
                                    $newtroops[$vill[0]]['all_crop'] += $tcrop;
                                }
                            }
                            foreach ($newtroops as $key => $value2) {
                                if ($killCrop > $value2['all_crop']) {
                                    $newtroops[$key]['all_de'] = TRUE;

                                    if ($newtroops[$key]['hero']['has']) {
                                        $killCrop -= $newtroops[$key]['hero']['crop'];
                                    }
                                }
                                if ($killCrop <= 0) {
                                    continue;
                                }
                                foreach ($value2['troops'] as $key2 => $value3) {
                                    $newtroops[$key]['troops'][$key2]['dead'] = ($killCrop > $value3['crop']) ? $value3['num'] : floor($killCrop / $GLOBALS['GameMetadata']['troops'][$key2]['crop_consumption']);
                                    $deCrop = $newtroops[$key]['troops'][$key2]['dead'] * $GLOBALS['GameMetadata']['troops'][$key2]['crop_consumption'];
                                    $training_resources = array(
                                        '1' => $training_resources[1] + ($newtroops[$key]['troops'][$key2]['dead'] * $GLOBALS['GameMetadata']['troops'][$key2]['training_resources'][1]),
                                        '2' => $training_resources[2] + ($newtroops[$key]['troops'][$key2]['dead'] * $GLOBALS['GameMetadata']['troops'][$key2]['training_resources'][2]),
                                        '3' => $training_resources[3] + ($newtroops[$key]['troops'][$key2]['dead'] * $GLOBALS['GameMetadata']['troops'][$key2]['training_resources'][3]),
                                        '4' => $training_resources[4] + ($newtroops[$key]['troops'][$key2]['dead'] * $GLOBALS['GameMetadata']['troops'][$key2]['training_resources'][4])
                                    );
                                    $killCrop -= $deCrop;
                                    if ($deCrop > 0) {
                                        $newtroops[$key]['has_de'] = TRUE;
                                    }
                                    if ($killCrop <= 0) {
                                        break;
                                    }
                                }
                                if ($killCrop <= 0) {
                                    break;
                                }
                            }
                            $keys = array_keys($newtroops);
                            $values = array_values($newtroops);
                            krsort($keys);
                            krsort($values);
                            $newtroops = array_combine($keys, $values);
                            $newtroopStr = '';
                            foreach ($newtroops as $key => $value2) {
                                if (!$newtroops[$key]['all_de'] || $key == 0 - 1) {
                                    if ($newtroopStr != '') {
                                        $newtroopStr .= '|';
                                    }
                                    $newtroopStr .= $key . ":";
                                    $troopStr = '';
                                    foreach ($value2['troops'] as $key2 => $value3) {
                                        if ($troopStr != '') {
                                            $troopStr .= ',';
                                        }
                                        $troopStr .= $key2 . ' ' . ($value3['num'] - $value3['dead']);
                                    }
                                    if ($newtroops[$key]['hero']['has'] AND $key != 0 - 1) {
                                        $troopStr .= ',' . $newtroops[$key]['hero']['id'] . ' -1';
                                    }
                                    $newtroopStr .= $troopStr;
                                }
                                if ($newtroops[$key]['has_de']) {
                                    $reportbody = '';
                                    $troopStr = '';
                                    foreach ($value2['troops'] as $key2 => $value3) {
                                        if ($key2 == 99) {
                                            continue;
                                        }
                                        if ($reportbody != '') {
                                            $reportbody .= ',';
                                        }
                                        $reportbody .= $key2 . ' ' . $value3['num'] . ' ' . $value3['dead'];
                                        if ($troopStr != '') {
                                            $troopStr .= ',';
                                        }
                                        $troopStr .= $key2 . ' ' . ($value3['num'] - $value3['dead']);
                                    }
                                    if ($newtroops[$key]['hero']['has']) {
                                        if ($newtroops[$key]['all_de']) {
                                            $reportbody .= ',-1 1 1';
                                        } else {
                                            $reportbody .= ',-1 1 0';
                                        }
                                        $troopStr .= ',' . $newtroops[$key]['hero']['id'] . ' -1';
                                    }
                                    $this->load_model('Report', 'r');
                                    $vid = ($key == 0 - 1) ? $oasisId : $key;
                                    $toPlayer_id = ($key == 0 - 1) ? $value['player_id'] : db::get_field('SELECT v.player_id FROM p_villages v WHERE v.id=:villageId', array(
                                        'villageId' => $key
                                    ));
                                    if ($key != 0) {
                                        $this->r->createReport(intval($value['player_id']), intval($toPlayer_id), intval($oasisId), intval($vid), 6, 0, $reportbody, $task['remainingTimeInSeconds']);
                                    }
                                    if ($key > 0) {
                                        $this->load_model('Battle', 'm');
                                        $this->m->_updateVillageOutTroops($key, $oasisId, $troopStr, ($newtroops[$key]['all_de'] AND $newtroops[$key]['hero']['has']), $newtroops[$key]['all_de'], $toPlayer_id);
                                    }
                                }
                            }
                            db::query('UPDATE p_villages v SET  v.troops_num=:trop WHERE v.id=:id', array(
                                'trop' => $newtroopStr,
                                'id' => $oasisId
                            ));
                            if ($killCrop <= 0) {
                                break;
                            }
                        }
                    }
                }
                if ($killCrop > 0) {
                    $troops = explode('|', $value['troops_num']);
                    krsort($troops);
                    $newtroops = array();
                    foreach ($troops as $value2) {
                        $vill = explode(':', $value2);
                        $tro = explode(',', $vill[1]);
                        $newtroops[$vill[0]] = array(
                            "hero" => array(
                                "has" => FALSE,
                                "id" => 0,
                                "crop" => 0
                            ),
                            "all_de" => FALSE,
                            "has_de" => FALSE,
                            "all_crop" => 0,
                            "troops" => array()
                        );
                        if ($vill[0] == 0 - 1) {
                            $hero_data = db::get_row('SELECT p.hero_troop_id, p.hero_in_village_id FROM p_players p WHERE p.id=:id', array(
                                'id' => intval($value['player_id'])
                            ));
                            if (0 < $hero_data['hero_in_village_id'] AND $hero_data['hero_in_village_id'] == $value['id']) {
                                $newtroops[$vill[0]]['hero']['has'] = TRUE;
                                $newtroops[$vill[0]]['hero']['crop'] = $GLOBALS['GameMetadata']['troops'][$hero_data['hero_troop_id']]['crop_consumption'];
                                $newtroops[$vill[0]]['all_crop'] += $newtroops[$vill[0]]['hero']['crop'];
                            }
                        }
                        foreach ($tro as $value3) {
                            list($tid, $tnum) = explode(' ', $value3);
                            if ($tnum == 0 - 1) {
                                $newtroops[$vill[0]]['hero']['has'] = TRUE;
                                $newtroops[$vill[0]]['hero']['id'] = $tid;
                                $newtroops[$vill[0]]['hero']['crop'] = $GLOBALS['GameMetadata']['troops'][$tid]['crop_consumption'];
                                $newtroops[$vill[0]]['all_crop'] += $newtroops[$vill[0]]['hero']['crop'];
                                continue;
                            }
                            $tcrop = $tnum * $GLOBALS['GameMetadata']['troops'][$tid]['crop_consumption'];
                            $newtroops[$vill[0]]['troops'][$tid] = array(
                                'num' => $tnum,
                                'dead' => 0,
                                'crop' => $tcrop
                            );
                            $newtroops[$vill[0]]['all_crop'] += $tcrop;
                        }
                    }
                    foreach ($newtroops as $key => $value2) {
                        if ($killCrop > $value2['all_crop']) {
                            $newtroops[$key]['all_de'] = TRUE;

                            if ($newtroops[$key]['hero']['has']) {
                                $killCrop -= $newtroops[$key]['hero']['crop'];
                                if ($key == 0 - 1) {
                                    db::query("UPDATE p_players p SET p.hero_troop_id=NULL, p.hero_in_village_id=NULL WHERE p.id=:id", array(
                                        'id' => intval($value['player_id'])
                                    ));
                                }
                            }
                        }
                        if ($killCrop <= 0) {
                            continue;
                        }
                        foreach ($value2['troops'] as $key2 => $value3) {
                            $newtroops[$key]['troops'][$key2]['dead'] = ($killCrop > $value3['crop']) ? $value3['num'] : floor($killCrop / $GLOBALS['GameMetadata']['troops'][$key2]['crop_consumption']);
                            $deCrop = $newtroops[$key]['troops'][$key2]['dead'] * $GLOBALS['GameMetadata']['troops'][$key2]['crop_consumption'];
                            $training_resources = array(
                                '1' => $training_resources[1] + ($newtroops[$key]['troops'][$key2]['dead'] * $GLOBALS['GameMetadata']['troops'][$key2]['training_resources'][1]),
                                '2' => $training_resources[2] + ($newtroops[$key]['troops'][$key2]['dead'] * $GLOBALS['GameMetadata']['troops'][$key2]['training_resources'][2]),
                                '3' => $training_resources[3] + ($newtroops[$key]['troops'][$key2]['dead'] * $GLOBALS['GameMetadata']['troops'][$key2]['training_resources'][3]),
                                '4' => $training_resources[4] + ($newtroops[$key]['troops'][$key2]['dead'] * $GLOBALS['GameMetadata']['troops'][$key2]['training_resources'][4])
                            );
                            $killCrop -= $deCrop;
                            if ($deCrop > 0) {
                                $newtroops[$key]['has_de'] = TRUE;
                            }
                            if ($killCrop <= 0) {
                                break;
                            }
                        }
                        if ($killCrop <= 0) {
                            break;
                        }
                    }
                    $keys = array_keys($newtroops);
                    $values = array_values($newtroops);
                    krsort($keys);
                    krsort($values);
                    $newtroops = array_combine($keys, $values);
                    $newtroopStr = '';
                    foreach ($newtroops as $key => $value2) {
                        if (!$newtroops[$key]['all_de'] || $key == 0 - 1) {
                            if ($newtroopStr != '') {
                                $newtroopStr .= '|';
                            }
                            $newtroopStr .= $key . ":";
                            $troopStr = '';
                            foreach ($value2['troops'] as $key2 => $value3) {
                                if ($troopStr != '') {
                                    $troopStr .= ',';
                                }
                                $troopStr .= $key2 . ' ' . ($value3['num'] - $value3['dead']);
                            }
                            if ($newtroops[$key]['hero']['has'] AND $key != 0 - 1) {
                                $troopStr .= ',' . $newtroops[$key]['hero']['id'] . ' -1';
                            }
                            $newtroopStr .= $troopStr;
                        }
                        if ($newtroops[$key]['has_de']) {
                            $reportbody = '';
                            $troopStr = '';
                            foreach ($value2['troops'] as $key2 => $value3) {
                                if ($key2 == 99) {
                                    continue;
                                }
                                if ($reportbody != '') {
                                    $reportbody .= ',';
                                }
                                $reportbody .= $key2 . ' ' . $value3['num'] . ' ' . $value3['dead'];
                                if ($troopStr != '') {
                                    $troopStr .= ',';
                                }
                                $troopStr .= $key2 . ' ' . ($value3['num'] - $value3['dead']);
                            }
                            if ($newtroops[$key]['hero']['has']) {
                                if ($newtroops[$key]['all_de']) {
                                    $reportbody .= ',-1 1 1';
                                } else {
                                    $reportbody .= ',-1 1 0';
                                }
                                $troopStr .= ',' . $newtroops[$key]['hero']['id'] . ' -1';
                            }
                            $this->load_model('Report', 'r');
                            $vid = ($key == 0 - 1) ? $value['id'] : $key;
                            $toPlayer_id = ($key == 0 - 1) ? $value['player_id'] : db::get_field('SELECT v.player_id FROM p_villages v WHERE v.id=:villageId', array(
                                'villageId' => $key
                            ));
                            if ($key != 0) {
                                $this->r->createReport(intval($value['player_id']), intval($toPlayer_id), intval($value['id']), intval($vid), 6, 0, $reportbody, $task['remainingTimeInSeconds']);
                            }
                            if ($key > 0) {
                                $this->load_model('Battle', 'm');
                                $this->m->_updateVillageOutTroops($key, $value['id'], $troopStr, ($newtroops[$key]['all_de'] AND $newtroops[$key]['hero']['has']), $newtroops[$key]['all_de'], $toPlayer_id);
                            }
                        }
                    }
                    db::query('UPDATE p_villages v SET  v.troops_num=:trop WHERE v.id=:id', array(
                        'trop' => $newtroopStr,
                        'id' => $value['id']
                    ));
                }
                $k = 1;
                $r_arr = explode(",", $value['resources']);
                $resources = array();
                foreach ($r_arr as $r_str) {
                    $r2 = explode(" ", $r_str);
                    $resources[$r2[0]] = array(
                        "current_value" => $r2[2] < $r2[1] + $training_resources[$k] ? $r2[2] : $r2[1] + $training_resources[$k],
                        "store_max_limit" => $r2[2],
                        "store_init_limit" => $r2[3],
                        "prod_rate" => $r2[4],
                        "prod_rate_percentage" => $r2[5]
                    );
                    ++$k;
                }
                $resourcesStr = "";
                foreach ($resources as $k => $v) {
                    if ($resourcesStr != "") {
                        $resourcesStr .= ",";
                    }
                    $resourcesStr .= sprintf("%s %s %s %s %s %s", $k, $v['current_value'], $v['store_max_limit'], $v['store_init_limit'], $v['prod_rate'], $v['prod_rate_percentage']);
                }
                db::query('UPDATE p_villages v SET v.crop_consumption=v.crop_consumption-:crop, v.resources=:res WHERE v.id=:id', array(
                    'crop' => $killCrop2 - $killCrop,
                    'res' => $resourcesStr,
                    'id' => $value['id']
                ));
            }
        }
        unset($villages);
        db::query("UPDATE p_queue q SET q.end_date=(NOW() + INTERVAL :se SECOND) WHERE q.id=:id", array(
            'se' => $task['execution_time'] + $task['remainingTimeInSeconds'],
            'id' => $task['id']
        ));
        return TRUE;
    }
}

?>
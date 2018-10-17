<?php

class Battle_Model extends Model
{
    public function executeWarResult($taskRow)
    {
        $taskRow['village_id'] = intval($taskRow['village_id']);
        $fromVillageRow        = $this->_getVillageInfo($taskRow['village_id']);
        $toVillageRow          = $this->_getVillageInfo($taskRow['to_village_id']);
        $paramsArray           = explode("|", $taskRow['proc_params']);
        $troopsArrStr          = explode(",", $paramsArray[0]);
        $troopsArray           = array(
            "troops" => array(),
            "onlyHero" => FALSE,
            "heroTroopId" => 0,
            "hasHero" => FALSE,
            "hasKing" => FALSE,
            "hasMostwten" => FALSE,
            "hasWallDest" => FALSE,
            "cropConsumption" => 0
        );
        $_onlyHero             = TRUE;
        foreach ($troopsArrStr as $_t)
        {
            $temp = explode(" ", $_t);
            $tid  = $temp[0];
            $tnum = $temp[1];

            if ($tnum == -1)
            {
                $troopsArray['hasHero']     = TRUE;
                $troopsArray['heroTroopId'] = $tid;
                $tnum                       = 1;
            }
            else
            {
                $troopsArray['troops'][$tid] = $tnum;
                if (0 < $tnum)
                {
                    $_onlyHero = FALSE;
                }
                else
                {
                    continue;
                }
                if ($tid == 9 || $tid == 19 || $tid == 29 || $tid == 108 || $tid == 59)
                {
                    $troopsArray['hasKing'] = TRUE;
                }
                if ($tid == 10 || $tid == 20 || $tid == 30 || $tid == 109 || $tid == 60)
                {
                    $troopsArray['hasMostwten'] = TRUE;
                }
                if ($tid == 7 || $tid == 17 || $tid == 27 || $tid == 106 || $tid == 57)
                {
                    $troopsArray['hasWallDest'] = TRUE;
                }
            }
            $troopsArray['cropConsumption'] += $GLOBALS['GameMetadata']['troops'][$tid]['crop_consumption'] * $tnum;
        }
        if ($_onlyHero && $troopsArray['hasHero'])
        {
            $troopsArray['onlyHero'] = TRUE;
        }
        $procInfo = array(
            "troopsArray" => $troopsArray,
            "hasHero" => $paramsArray[1] == 1,
            "spyAction" => $paramsArray[2],
            "catapultTarget" => $paramsArray[3],
            "harvestResources" => $paramsArray[4],
            "spyInfo" => $paramsArray[5],
            "catapultResult" => $paramsArray[6],
            "troopBack" => $paramsArray[7] == 1
        );
        if ($taskRow['proc_type'] == QS_CREATEVILLAGE && ($toVillageRow['is_oasis'] || 0 < intval($toVillageRow['player_id'])))
        {
            $taskRow['proc_type'] = QS_WAR_ATTACK_PLUNDER;
        }
        switch ($taskRow['proc_type'])
        {
            case QS_WAR_REINFORCE:
                $this->load_model('Battles_Reinforcementbattle', 'Battles_Reinforcementbattle');
                return $this->Battles_Reinforcementbattle->handleReInforcement($taskRow, $toVillageRow, $fromVillageRow, $procInfo, $paramsArray[0]);
            case QS_WAR_ATTACK:
            case QS_WAR_ATTACK_PLUNDER:
                $this->load_model('Battles_WarBattle', 'Warbattle');
                return $this->Warbattle->handleWarAttack($taskRow, $toVillageRow, $fromVillageRow, $procInfo);
            case QS_WAR_ATTACK_SPY:
                $this->load_model('Battles_SpyBattle', 'spyModel');
                return $this->spyModel->handleWarSpy($taskRow, $toVillageRow, $fromVillageRow, $procInfo);
            case QS_CREATEVILLAGE:
                $this->load_model('Battles_NewVillageBattle', 'Battles_Newvillagebattle');
                return $this->Battles_Newvillagebattle->handleCreateNewVillage($taskRow, $toVillageRow, $fromVillageRow, $troopsArray['cropConsumption']);
        }
        return FALSE;
    }

    public function _getTroopWithPower($artPower, $troops, $troopsPower, $isAttacking, $heroLevel, $peopleCount, $wringerPower = 0, $wallPower = 0, $heroId = -1, $spyAction = FALSE)
    {
        $GameMetadata = $GLOBALS['GameMetadata'];
        $returnTroops = array(
            "troops" => array(),
            "total_live_number" => 0,
            "total_spy_live_number" => 0,
            "total_power" => 0,
            "total_defense_power" => 0,
            "total_attack_power" => 0,
            "total_attack_infantry" => 0,
            "total_attack_cavalry" => 0,
            "total_defense_infantry" => 0,
            "total_defense_cavalry" => 0,
            "total_carry_load" => 0,
            "total_dead_consumption" => 0,
            "total_dead_number" => 0,
            "hasHero" => 0 - 1 < $heroId,
            "heroTroopId" => $heroId
        );
        $powerFactor  = !$spyAction ? ($peopleCount / 250) + $wringerPower + $artPower + ($heroLevel / 6) : 0;
        $cavalry      = array(4,5,6,15,16,23,24,25,26,35,36,45,46,55,56,104,105);
        foreach ($troops as $tid => $tnum)
        {
            if ($isAttacking)
            {
                $tpower = $GameMetadata['troops'][$tid]['attack_value'];
                $tpower = floor((isset($troopsPower[$tid]) ? ((((2 * $troopsPower[$tid]) * $tpower) / 100) + $tpower) : $tpower) + (($powerFactor * $tpower) / 100));
            }
            else
            {
                $tpower  = $GameMetadata['troops'][$tid]['defense_infantry'];
                $tpower2 = $GameMetadata['troops'][$tid]['defense_cavalry'];
                $tpower  = floor((isset($troopsPower[$tid]) ? ((((2 * $troopsPower[$tid]) * $tpower) / 100) + $tpower) : $tpower) + (($powerFactor * $tpower) / 100) + (($wallPower * $tpower) / 100));
                $tpower2 = floor((isset($troopsPower[$tid]) ? ((((2 * $troopsPower[$tid]) * $tpower2) / 100) + $tpower2) : $tpower2) + (($powerFactor * $tpower2) / 100) + (($wallPower * $tpower2) / 100));
            }
            $tpower2 = isset($tpower2) ? $tpower2 : 0;
            if ($spyAction)
            {
                $tpower = $tid == 103 || $tid == 54 || $tid == 4 || $tid == 14 || $tid == 23 ? $artPower : 0;
            }
            $num = intval($tnum) > 0 ? intval($tnum) : 0;

            if ($tid != 99)
            {
                $returnTroops['total_live_number'] += $num;
                if ($isAttacking)
                {
                    if (in_array($tid, $cavalry))
                    {
                        $returnTroops['total_attack_cavalry'] += ($tpower * $num);
                    }
                    else
                    {
                        $returnTroops['total_attack_infantry'] += ($tpower * $num);
                    }
                }
                else
                {
                    $returnTroops['total_defense_infantry'] += ($tpower * $num);
                    $returnTroops['total_defense_cavalry'] += ($tpower2 * $num);
                }
                $returnTroops['total_power'] += ($tpower * $num);
                $returnTroops['total_carry_load'] += ($GameMetadata['troops'][$tid]['carry_load'] * $num);
                if ($spyAction && ($tid == 103 || $tid == 54 || $tid == 4 || $tid == 14 || $tid == 23))
                {
                    $returnTroops['total_spy_live_number'] += $num;
                }
            }
            $returnTroops['troops'][$tid] = array(
                "number" => $num,
                "live_number" => $num,
                "single_consumption" => $GameMetadata['troops'][$tid]['crop_consumption'],
                "single_carry_load" => $GameMetadata['troops'][$tid]['carry_load'],
                "single_power" => $tpower,
                "defense_cavalry" => $tpower2
            );
        }
        $returnTroops['total_attack_power'] = ($returnTroops['total_attack_infantry'] + $returnTroops['total_attack_cavalry']);
        return $returnTroops;
    }

    public function _getAttackTroopsForVillage($fromVillageRow, $troopsTrainingStr, $troopsArray, $heroLevel, $peopleCount, $wringerLevel, $spyAction)
    {
        $this->load_model('Artefacts', 'A');
        $troopsPower = array();
        $artPower    = 0;
        if (!$spyAction)
        {
            $artLevel          = $this->A->Artefacts($fromVillageRow['player_id'], $fromVillageRow['id'], 9);
            $artPower          = ($artLevel == 0) ? 0 : (($artLevel == 1) ? 75 : (($artLevel == 2) ? 50 : 100));
            $_c                = 0;
            $troopsTrainingStr = trim($troopsTrainingStr);
            if ($troopsTrainingStr != "")
            {
                $_arr = explode(",", $troopsTrainingStr);
                foreach ($_arr as $troopStr)
                {
                    $_c++;
                    list($troopId, $researches_done, $defense_level, $attack_level) = explode(" ", $troopStr);

                    if ($troopId != 99 && $_c <= 8)
                    {
                        $troopsPower[$troopId] = $attack_level;
                    }
                }
                $peopleCount = $_c;
            }
        }
        else
        {
            $artLevel = $this->A->Artefacts($fromVillageRow['player_id'], $fromVillageRow['id'], 4);
            $artPower = ($artLevel == 0) ? 1 : (($artLevel == 1) ? 5 : (($artLevel == 2) ? 3 : 10));
        }
        return $this->_getTroopWithPower($artPower, $troopsArray, $troopsPower, TRUE, $heroLevel, $peopleCount, $wringerLevel, 0, 0 - 1, $spyAction);
    }

    public function _getDefenseTroopsForVillage($vid, $troopsArray, $hasHero, $peopleCount, $wallPower, $spyAction)
    {
        $this->load_model('Artefacts', 'A');
        $vrow        = db::get_row("SELECT v.player_id, v.troops_training FROM p_villages v WHERE v.id=:id", array(
            'id' => intval($vid)
        ));
        $heroLevel   = 0;
        $heroId      = 0 - 1;
        $troopsPower = array();
        $artPower    = 0;
        if ($vrow != NULL)
        {
            if ($hasHero)
            {
                $_row = db::get_row("SELECT p.hero_level, p.hero_troop_id FROM p_players p WHERE p.id=:id", array(
                    'id' => intval($vrow['player_id'])
                ));
                if ($_row != NULL)
                {
                    $heroLevel = intval($_row['hero_level']);
                    $heroId    = intval($_row['hero_troop_id']);
                }
            }
            if (!$spyAction)
            {
                $artLevel          = $this->A->Artefacts($vrow['player_id'], $vid, 1);
                $artPower          = ($artLevel == 0) ? 0 : (($artLevel == 1) ? 75 : (($artLevel == 2) ? 50 : 100));
                $_c                = 0;
                $troopsTrainingStr = trim($vrow['troops_training']);
                if ($troopsTrainingStr != "")
                {
                    $_arr = explode(",", $troopsTrainingStr);
                    foreach ($_arr as $troopStr)
                    {
                        $_c++;
                        list($troopId, $researches_done, $defense_level, $attack_level) = explode(" ", $troopStr);

                        if ($troopId != 99 && $_c <= 8)
                        {
                            $troopsPower[$troopId] = $defense_level;
                        }
                    }
                    $peopleCount = $_c;
                }
            }
            else
            {
                $artLevel = $this->A->Artefacts($vrow['player_id'], $vid, 4);
                $artPower = ($artLevel == 0) ? 1 : (($artLevel == 1) ? 5 : (($artLevel == 2) ? 3 : 10));
            }
        }
        return $this->_getTroopWithPower($artPower, $troopsArray, $troopsPower, FALSE, $heroLevel, $peopleCount, 0, $wallPower, $heroId, $spyAction);
    }

    public function _getNewTroops($troopsStr, $addTroopsArray, $fromVillageId, $isSamePlayer)
    {
        $newTroopsStr = "";
        $heroAddCond  = $addTroopsArray['hasHero'] && 0 - 1 < $fromVillageId && !$isSamePlayer;
        $troopsStr    = trim($troopsStr);
        if ($troopsStr == "")
        {
            foreach ($addTroopsArray['troops'] as $tid => $tnum)
            {
                if ($newTroopsStr != "")
                {
                    $newTroopsStr .= ",";
                }
                $newTroopsStr .= $tid . " " . $tnum;
            }
            if ($heroAddCond)
            {
                if ($newTroopsStr != "")
                {
                    $newTroopsStr .= ",";
                }
                $newTroopsStr .= $addTroopsArray['heroTroopId'] . " -1";
            }
            $newTroopsStr = $fromVillageId . ":" . $newTroopsStr;
        }
        else
        {
            $hasTroopsIn  = FALSE;
            $troopsStrArr = explode("|", $troopsStr);
            foreach ($troopsStrArr as $tvStr)
            {
                if ($newTroopsStr != "")
                {
                    $newTroopsStr .= "|";
                }
                $vtroopsStr = explode(":", $tvStr);
                $vid        = explode(":", $tvStr);
                list($vid, $vtroopsStr) = $vid;
                if ($vid == $fromVillageId)
                {
                    $hasTroopsIn  = TRUE;
                    $curTroopsStr = explode(",", $vtroopsStr);
                    $curTroops    = array();
                    foreach ($curTroopsStr as $curTroopsStrItem)
                    {
                        $_tnum = explode(" ", $curTroopsStrItem);
                        $_tid  = explode(" ", $curTroopsStrItem);
                        list($_tid, $_tnum) = $_tid;
                        if ($_tnum == 0 - 1)
                        {
                            $curTroops[0 - 1] = $_tid;
                        }
                        else
                        {
                            $curTroops[$_tid] = $_tnum;
                        }
                    }
                    $newtvStr = "";
                    foreach ($addTroopsArray['troops'] as $tid => $tnum)
                    {
                        if ($newtvStr != "")
                        {
                            $newtvStr .= ",";
                        }
                        if (isset($curTroops[$tid]))
                        {
                            $tnum += $curTroops[$tid];
                        }
                        $newtvStr .= $tid . " " . $tnum;
                    }
                    if (isset($curTroops[99]))
                    {
                        if ($newtvStr != "")
                        {
                            $newtvStr .= ",";
                        }
                        $newtvStr .= "99 " . $curTroops[99];
                    }
                    if (isset($curTroops[0 - 1]))
                    {
                        if ($newtvStr != "")
                        {
                            $newtvStr .= ",";
                        }
                        $newtvStr .= $curTroops[0 - 1] . " -1";
                    }
                    else if ($heroAddCond)
                    {
                        if ($newtvStr != "")
                        {
                            $newtvStr .= ",";
                        }
                        $newtvStr .= $addTroopsArray['heroTroopId'] . " -1";
                    }
                    $newTroopsStr .= $vid . ":" . $newtvStr;
                }
                else
                {
                    $newTroopsStr .= $tvStr;
                }
            }
            if (!$hasTroopsIn)
            {
                $newTroopsStr = "";
                foreach ($addTroopsArray['troops'] as $tid => $tnum)
                {
                    if ($newTroopsStr != "")
                    {
                        $newTroopsStr .= ",";
                    }
                    $newTroopsStr .= $tid . " " . $tnum;
                }
                if ($heroAddCond)
                {
                    if ($newTroopsStr != "")
                    {
                        $newTroopsStr .= ",";
                    }
                    $newTroopsStr .= $addTroopsArray['heroTroopId'] . " -1";
                }
                $newTroopsStr = $fromVillageId . ":" . $newTroopsStr;
                if ($troopsStr != "")
                {
                    $newTroopsStr = $troopsStr . "|" . $newTroopsStr;
                }
            }
        }
        return $newTroopsStr;
    }

    public function _updateVillageOutTroops($vid, $invid, $newTroopsStr, $heroKilled, $thisInforcementDied, $uid)
    {
        $pid = $uid;
        $vid = intval($vid);
        if (0 < $vid)
        {
            $row = db::get_row("SELECT v.player_id, v.troops_out_num FROM p_villages v WHERE v.id=:id", array(
                'id' => intval($vid)
            ));
            if ($row == NULL)
            {
            }
            else
            {
                $pid            = $row['player_id'];
                $troops_out_num = "";
                $ts             = trim($row['troops_out_num']);
                if ($ts != "")
                {
                    $tsArr = explode("|", $ts);
                    foreach ($tsArr as $tsArrStr)
                    {
                        list($_vid, $_troops) = explode(":", $tsArrStr);
                        if ($_vid == $invid)
                        {
                            if (!$thisInforcementDied)
                            {
                                if ($troops_out_num != "")
                                {
                                    $troops_out_num .= "|";
                                }
                                $troops_out_num .= $invid . ":" . $newTroopsStr;
                            }
                        }
                        else
                        {
                            if ($troops_out_num != "")
                            {
                                $troops_out_num .= "|";
                            }
                            $troops_out_num .= $tsArrStr;
                        }
                    }
                }
                db::query("UPDATE p_villages v SET v.troops_out_num=:tr WHERE v.id=:id", array(
                    'tr' => $troops_out_num,
                    'id' => intval($vid)
                ));
            }
        }
        if ($heroKilled)
        {
            db::query("UPDATE p_players p SET p.hero_troop_id=NULL, p.hero_in_village_id=NULL WHERE p.id=:id", array(
                'id' => intval($pid)
            ));
        }
    }

    public function _updateVillage($villageRow, $reduceCropConsumption, $heroKilled)
    {
        $this->load_model('Artefacts', 'A');
        $crop = $this->A->CropAndRes($villageRow['player_id'], $villageRow['id'], 5);
        $res  = $this->A->CropAndRes($villageRow['player_id'], $villageRow['id'], 7);
        $elapsedTimeInSeconds = $villageRow['elapsedTimeInSeconds'];
        $resources            = array();
        $r_arr                = explode(",", $villageRow['resources']);
        foreach ($r_arr as $r_str)
        {
            $r2            = explode(" ", $r_str);
            $prate         = floor($r2[4] * (1 + ($r2[5]+$res) / 100)) - ($r2[0] == 4 ? floor($villageRow['crop_consumption']*$crop) : 0);
            $current_value = floor($r2[1] + $elapsedTimeInSeconds * ($prate / 3600));
            if ($r2[2] < $current_value)
            {
                $current_value = $r2[2];
            }
            $resources[$r2[0]] = array(
                "current_value" => $current_value,
                "store_max_limit" => $r2[2],
                "store_init_limit" => $r2[3],
                "prod_rate" => $r2[4],
                "prod_rate_percentage" => $r2[5],
                "calc_prod_rate" => $prate
            );
        }
        list($cpValue, $cpRate) = explode(" ", $villageRow['cp']);
        $cpValue      = round($cpValue + $elapsedTimeInSeconds * ($cpRate / 86400), 4);
        $resourcesStr = "";
        foreach ($resources as $k => $v)
        {
            if ($resourcesStr != "")
            {
                $resourcesStr .= ",";
            }
            $resourcesStr .= sprintf("%s %s %s %s %s %s", $k, $v['current_value'], $v['store_max_limit'], $v['store_init_limit'], $v['prod_rate'], $v['prod_rate_percentage']);
        }
        $cp = $cpValue . " " . $cpRate;
        db::query("UPDATE p_villages v  SET v.resources=:res, v.cp=:cp, v.crop_consumption=v.crop_consumption-:crop, v.last_update_date=NOW() WHERE  v.id=:id", array(
            'res' => $resourcesStr,
            'cp' => $cp,
            'crop' => $reduceCropConsumption,
            'id' => intval($villageRow['id'])
        ));
        if ($heroKilled)
        {
            db::query("UPDATE p_players p SET p.hero_troop_id=NULL, p.hero_in_village_id=NULL WHERE p.id=:id", array(
                'id' => intval($villageRow['player_id'])
            ));
        }
    }

    public function _getVillageInfo($villageId)
    {
        return db::get_row("SELECT  v.id, v.parent_id, v.tribe_id,  v.field_maps_id, v.rel_x, v.rel_y, v.crop_consumption,  v.player_id, v.alliance_id, v.village_oases_id,  v.village_name, v.player_name, v.alliance_name, v.is_capital, v.is_special_village, v.is_oasis, v.people_count, v.resources, v.buildings, v.cp, v.troops_training, v.troops_num, v.troops_out_num, v.troops_intrap_num, v.troops_out_intrap_num, v.allegiance_percent,  v.child_villages_id, TIME_TO_SEC(TIMEDIFF(NOW(), v.last_update_date)) elapsedTimeInSeconds, TIME_TO_SEC(TIMEDIFF(NOW(), v.creation_date)) oasisElapsedTimeInSeconds FROM p_villages v  WHERE v.id=:id", array(
            'id' => intval($villageId)
        ));
    }

}

?>
<?php
require_once MODELS_DIR . 'Battle.php';
class Battles_Warbattle_Model extends Battle_Model
{
    public function handleWarAttack($taskRow, $toVillageRow, $fromVillageRow, $procInfo)
    {
		global $gameConfig;
        $GameMetadata  = $GLOBALS['GameMetadata'];
        $SetupMetadata = $GLOBALS['SetupMetadata'];
        if ((!$toVillageRow['is_oasis'] AND intval($toVillageRow['player_id']) == 0))
        {
            $paramsArray                           = explode('|', $taskRow['proc_params']);
            $paramsArray[sizeof($paramsArray) - 1] = 1;
            $newParams                             = implode('|', $paramsArray);

            db::count('UPDATE p_queue q SET q.player_id=:a1, q.village_id=:a2, q.to_player_id=:a3, q.to_village_id=:a4, q.proc_type=:a5, q.proc_params=:a6, q.end_date=(q.end_date + INTERVAL q.execution_time SECOND) WHERE q.id=:a7', array(
                'a1' => intval($taskRow['to_player_id']),
                'a2' => intval($taskRow['to_village_id']),
                'a3' => intval($taskRow['player_id']),
                'a4' => intval($taskRow['village_id']),
                'a5' => QS_WAR_REINFORCE,
                'a6' => $newParams,
                'a7' => intval($taskRow['id'])
            ));

            return TRUE;
        }

        $heroLevel = 0;
        if ($procInfo['troopsArray']['hasHero'])
        {
            $heroLevel = intval(db::get_field('SELECT p.hero_level FROM p_players p WHERE p.id=:id', array(
                'id' => intval($fromVillageRow['player_id'])
            )));
        }

        $heroBuildingLevel = 0;
        $wringerLevel      = 0;
        $buildings         = array();
        $bStr              = trim($fromVillageRow['buildings']);
        if ($bStr != '')
        {
            $bStrArr = explode(',', $bStr);
            foreach ($bStrArr as $b2Str)
            {
                list($item_id, $level) = explode(' ', $b2Str);
                if ($item_id == 35)
                {
                    $wringerLevel = $level;
                    continue;
                }
                else
                {
                    if ($item_id == 37)
                    {
                        $heroBuildingLevel = $level;
                        continue;
                    }

                    continue;
                }
            }
        }

        $attackTroops           = $this->_getAttackTroopsForVillage($fromVillageRow, $fromVillageRow['troops_training'], $procInfo['troopsArray']['troops'], $heroLevel, $fromVillageRow['people_count'], $wringerLevel, FALSE);
        $buildinStabilityFactor = 1;
        $crannyTotalSize        = 0;
        $wallPower              = 0;
        $wallLevel              = 0;
        $wallBid                = 0;
        $wallItemId             = 0;
        $buildings              = array();
        $bStr                   = trim($toVillageRow['buildings']);
        if ($bStr != '')
        {
            $bStrArr = explode(',', $bStr);
            $fcc     = 0;
            foreach ($bStrArr as $b2Str)
            {
                ++$fcc;
                list($item_id, $level) = explode(' ', $b2Str);
                if ((($item_id == 31 OR $item_id == 32) OR $item_id == 33))
                {
                    $wallBid    = $fcc;
                    $wallItemId = $item_id;
                    $wallLevel  = $level;
                    $wallPower  = (0 < $level ? $GameMetadata['items'][$item_id]['levels'][$level - 1]['value'] : 0);
                    continue;
                }
                else
                {
                    if (($item_id == 23 AND 0 < $level))
                    {
                        /*$this->load_model('Artefacts', 'A');
                        $artLevel = $this->A->Artefacts($toVillageRow['player_id'], $toVillageRow['id'], 8);
                        $artPower = ($artLevel == 0) ? 1 : (($artLevel == 1) ? 200 : (($artLevel == 2) ? 100 : 500));*/
                        $crannyTotalSize += $GameMetadata['items'][$item_id]['levels'][$level - 1]['value'] * $GameMetadata['items'][$item_id]['for_tribe_id'][$toVillageRow['tribe_id']]; //* $artPower;
                        continue;
                    }
                    else
                    {
                        if (($item_id == 34 AND 0 < $level))
                        {
                            $buildinStabilityFactor = $GameMetadata['items'][$item_id]['levels'][$level - 1]['value'] / 100;
                            continue;
                        }

                        continue;
                    }

                    continue;
                }
            }
        }

        $crannyTotalSize   = floor($crannyTotalSize * $GameMetadata['tribes'][$fromVillageRow['tribe_id']]['crannyFactor']);
        $defenseTroops     = array();
        $defense_infantry  = 0;
        $defense_cavalry   = 0;
        $totalDefensePower = array(
            "total_defense_infantry" => 0,
            "total_defense_cavalry" => 0
        );
        $troops_num        = trim($toVillageRow['troops_num']);
        if ($troops_num != '')
        {
            $vtroopsArr = explode('|', $troops_num);
            foreach ($vtroopsArr as $vtroopsStr)
            {
                @list($tvid, $tvtroopsStr) = explode(':', $vtroopsStr);
                $incFactor = ((($toVillageRow['is_oasis'] AND intval($toVillageRow['player_id']) == 0) AND $tvid == 0 - 1) ? floor($toVillageRow['oasisElapsedTimeInSeconds'] / 86400) : 0);
                $_hasHero  = FALSE;
                $vtroops   = array();
                $_arr      = explode(',', $tvtroopsStr);
                foreach ($_arr as $_arrStr)
                {
                    @list($_tid, $_tnum) = explode(' ', $_arrStr);
                    if ($_tnum == 0 - 1)
                    {
                        $_hasHero = TRUE;
                        continue;
                    }
                    else
                    {
                        $vtroops[$_tid] = $_tnum + $incFactor;
                        continue;
                    }
                }

                if ($tvid == 0 - 1)
                {
                    $hero_in_village_id = intval(db::get_field('SELECT p.hero_in_village_id FROM p_players p WHERE p.id=:id', array(
                        'id' => intval($toVillageRow['player_id'])
                    )));
                    if ((0 < $hero_in_village_id AND $hero_in_village_id == $toVillageRow['id']))
                    {
                        $_hasHero = TRUE;
                    }
                }

                $defenseTroops[$tvid] = $this->_getDefenseTroopsForVillage(($tvid == 0 - 1 ? $toVillageRow['id'] : $tvid), $vtroops, $_hasHero, $toVillageRow['people_count'], $wallPower, FALSE);
                $defense_infantry += $defenseTroops[$tvid]['total_defense_infantry'];
                $defense_cavalry += $defenseTroops[$tvid]['total_defense_cavalry'];
                $totalDefensePower = array(
                    "total_defense_infantry" => $defense_infantry,
                    "total_defense_cavalry" => $defense_cavalry
                );
            }
        }

        $warResult         = $this->getWarResult($attackTroops, $defenseTroops, $totalDefensePower, $taskRow['proc_type'] == QS_WAR_ATTACK_PLUNDER);
        $harvestResources  = '0 0 0 0';
        $harvestInfoStruct = array(
            'string' => $harvestResources,
            'sum' => 0
        );
        if (!$warResult['all_attack_killed'])
        {
            $harvestInfoStruct = $this->_harvestTroopsFrom($toVillageRow, $warResult['attackTroops']['total_carry_load'], $crannyTotalSize);
            $harvestResources  = $harvestInfoStruct['string'];
        }

        $reduceConsumption = $warResult['attackTroops']['total_dead_consumption'];
        if (($warResult['all_attack_killed'] AND $procInfo['troopsArray']['hasHero']))
        {
            $reduceConsumption += $GameMetadata['troops'][$procInfo['troopsArray']['heroTroopId']]['crop_consumption'];
        }

        if (0 < $reduceConsumption)
        {
            $this->_updateVillage($fromVillageRow, $reduceConsumption, ($warResult['all_attack_killed'] AND $procInfo['troopsArray']['hasHero']));
        }

        if (($procInfo['troopsArray']['hasHero'] AND 1 <= $warResult['defense_total_dead_number']))
        {
            $heroStatisticPoint = $warResult['defense_total_dead_number'];
            db::query('UPDATE p_players p SET p.hero_points=p.hero_points+:ho, p.hero_level=IF(p.hero_level+floor(p.hero_points/(100*(p.hero_level+1)))>127,127,p.hero_level+floor(p.hero_points/(100*(p.hero_level+1)))) WHERE p.id=:id', array(
                'ho' => $heroStatisticPoint,
                'id' => intval($fromVillageRow['player_id'])
            ));
        }

        $defenseTroopsStr         = '';
        $defenseReduceConsumption = 0;
        $reportTroopTable         = array();
        $tribeId                  = 0;
        foreach ($warResult['defenseTroops'] as $vid => $troopsTable)
        {
            $defenseReduceConsumption += $troopsTable['total_dead_consumption'];
            $newTroops           = '';
            $reportBody          = '';
            $reportDead          = 0;
            $thisInforcementDied = TRUE;
            foreach ($troopsTable['troops'] as $tid => $tprop)
            {
                if ($newTroops != '')
                {
                    $newTroops .= ',';
                }

                $newTroops .= $tid . ' ' . $tprop['live_number'];

                if ($reportBody != '')
                {
                    $reportBody .= ',';
                }
                $reportBody .= $tid . ' ' . $tprop['number'] . ' ' . ($tprop['number'] - $tprop['live_number']);
                $reportDead += $tprop['number'] - $tprop['live_number'];
                if (0 < $tprop['live_number'])
                {
                    $thisInforcementDied = FALSE;
                }

                $tribeId = $GameMetadata['troops'][$tid]['for_tribe_id'];
                if (!isset($reportTroopTable[$tribeId]))
                {
                    $reportTroopTable[$tribeId] = array(
                        'troops' => array(),
                        'hero' => array(
                            'number' => 0,
                            'dead_number' => 0
                        )
                    );
                }

                if ($tid != 99)
                {
                    if (!isset($reportTroopTable[$tribeId]['troops'][$tid]))
                    {
                        $reportTroopTable[$tribeId]['troops'][$tid] = array(
                            'number' => $tprop['number'],
                            'dead_number' => $tprop['number'] - $tprop['live_number']
                        );
                        continue;
                    }
                    else
                    {
                        $reportTroopTable[$tribeId]['troops'][$tid]['number'] += $tprop['number'];
                        $reportTroopTable[$tribeId]['troops'][$tid]['dead_number'] += $tprop['number'] - $tprop['live_number'];
                        continue;
                    }

                    continue;
                }
            }

            if ($troopsTable['hasHero'])
            {
                ++$reportTroopTable[$tribeId]['hero']['number'];
                if (0 < $troopsTable['total_live_number'])
                {
                    $reportBody .= ',-1 1 0';
                }
                else
                {
                    $reportBody .= ',-1 1 1';
                }
            }

            if ((0 < $troopsTable['total_live_number'] AND $troopsTable['hasHero']))
            {
                if ($vid != 0 - 1)
                {
                    if ($newTroops != '')
                    {
                        $newTroops .= ',';
                    }

                    $newTroops .= $troopsTable['heroTroopId'] . ' -1';
                }

                if ((($vid == 0 - 1 AND !$toVillageRow['is_oasis']) AND $warResult['all_attack_killed']))
                {
                    $heroStatisticPoint = 1;
                    db::query('UPDATE p_players p SET p.hero_points=p.hero_points+:ho, p.hero_level=IF(p.hero_level+floor(p.hero_points/(100*(p.hero_level+1)))>127,127,p.hero_level+floor(p.hero_points/(100*(p.hero_level+1)))) WHERE p.id=:id', array(
                        'ho' => $heroStatisticPoint,
                        'id' => intval($toVillageRow['player_id'])
                    ));
                }
                $thisInforcementDied = FALSE;
            }

            if (($troopsTable['hasHero'] AND $troopsTable['total_live_number'] <= 0))
            {
                ++$reportTroopTable[$tribeId]['hero']['dead_number'];
                $defenseReduceConsumption += $GameMetadata['troops'][$troopsTable['heroTroopId']]['crop_consumption'];
            }

            if ($vid != 0 - 1)
            {
                if ($reportDead == 0)
                {
                    $reportResult = 4;
                }
                elseif ($troopsTable['total_live_number'] > 0 && $reportDead > 0)
                {
                    $reportResult = 5;
                }
                else
                {
                    $reportResult = 6;
                }
                $timeInSeconds = $taskRow['remainingTimeInSeconds'];
                $toPlayer_id   = db::get_field('SELECT v.player_id FROM p_villages v WHERE v.id=:id', array(
                    'id' => $vid
                ));

                $this->load_model('Report', 'r');
                if ($fromVillageRow['player_id'] != $toPlayer_id and $toPlayer_id != 0 and $vid != 0)
                {
                    $toVillageName = $this->r->getVillageName($toVillageRow['id']);
                    $reportBody .= '|' . $toVillageName;
                    $this->r->createReport(intval($fromVillageRow['player_id']), intval($toPlayer_id), intval($fromVillageRow['id']), intval($vid), 5, $reportResult, $reportBody, $timeInSeconds);
                }
            }
            $this->_updateVillageOutTroops($vid, $toVillageRow['id'], $newTroops, ($troopsTable['hasHero'] AND $troopsTable['total_live_number'] <= 0), $thisInforcementDied, intval($toVillageRow['player_id']));

            if (($vid == 0 - 1 AND $toVillageRow['is_oasis']))
            {
                db::query('UPDATE p_villages v SET v.creation_date=NOW() WHERE v.id=:id', array(
                    'id' => intval($toVillageRow['id'])
                ));
            }

            if ((!$thisInforcementDied OR $vid == 0 - 1))
            {
                if ($defenseTroopsStr != '')
                {
                    $defenseTroopsStr .= '|';
                }
                $defenseTroopsStr .= $vid . ':' . $newTroops;
                continue;
            }
        }

        if ((($toVillageRow['is_oasis'] AND 0 < intval($toVillageRow['player_id'])) AND isset($reportTroopTable[4])))
        {
            unset($reportTroopTable[4]);
        }

        db::query('UPDATE p_villages v SET v.troops_num=:num WHERE v.id=:id', array(
            'num' => $defenseTroopsStr,
            'id' => $toVillageRow['id']
        ));
        if (!($toVillageRow['is_oasis'] AND intval($toVillageRow['player_id']) == 0))
        {
            $_tovid = ($toVillageRow['is_oasis'] ? intval($toVillageRow['parent_id']) : $toVillageRow['id']);
            db::query('UPDATE p_villages v SET v.crop_consumption=v.crop_consumption-:crop WHERE v.id=:id', array(
                'crop' => $defenseReduceConsumption,
                'id' => intval($_tovid)
            ));
        }

        $villageTotallyDestructed = FALSE;
        $wallDestructionResult    = '';
        $catapultResult           = '';
        if (((!$toVillageRow['is_oasis'] AND !$warResult['all_attack_killed']) AND $taskRow['proc_type'] != QS_WAR_ATTACK_PLUNDER))
        {
            $wallDestrTroopsCount  = 0;
            $buildDestrTroopsCount = 0;
            foreach ($warResult['attackTroops']['troops'] as $tid => $tprop)
            {
                if (((((($tid == 7 OR $tid == 17) OR $tid == 27) OR $tid == 106) OR $tid == 57) OR $tid == 47))
                {
                    $wallDestrTroopsCount = $tprop['live_number'];
                    continue;
                }
                else
                {
                    if (((((($tid == 8 OR $tid == 18) OR $tid == 28) OR $tid == 107) OR $tid == 58) OR $tid == 48))
                    {
                        $buildDestrTroopsCount = $tprop['live_number'];
                        continue;
                    }

                    continue;
                }
            }

            if ($procInfo['troopsArray']['hasWallDest'])
            {
                if (0 < $wallLevel)
                {
                    $dropLevels = 0;
                    if (2 * $wallPower < $wallDestrTroopsCount)
                    {
                        $dropLevels = floor($wallDestrTroopsCount / (2 * $wallPower));
                        if ($wallLevel - $dropLevels < 0)
                        {
                            $dropLevels = $wallLevel;
                        }
                    }

                    if (0 < $dropLevels)
                    {
                        $wallDestructionResult = $wallLevel . '-' . ($wallLevel - $dropLevels);
                        $wallLevel -= $dropLevels;

                        $this->load_model('Queuejob', 'mq');
                        while (0 < $dropLevels--)
                        {
                            $this->mq->upgradeBuilding($toVillageRow['id'], $wallBid, $wallItemId, TRUE);
                        }
                    }
                    else
                    {
                        $wallDestructionResult = '-';
                    }
                }
                else
                {
                    $wallDestructionResult = '+';
                }
            }
			$this->load_model('Global', 'G');
            $serverstart  = $this->G->getServerStartTime();
			$artdate      = ($gameConfig['settings']['Artefacts'] * 3600 * 24) - $serverstart['server_start_time'];
            if (trim($procInfo['catapultTarget']) != '' AND $artdate < 0)
            {
                $catapultTargetArr = explode(':', $procInfo['catapultTarget']);
                $catapultTargetArr = explode(' ', $catapultTargetArr[1]);
                $buildingsInfo     = array();
                $bStr              = trim($toVillageRow['buildings']);
                if ($bStr != '')
                {
                    $bStrArr = explode(',', $bStr);
                    $_i      = 0;
                    foreach ($bStrArr as $b2Str)
                    {
                        ++$_i;
                        list($item_id, $level) = explode(' ', $b2Str);
                        if ((($item_id == 31 OR $item_id == 32) OR $item_id == 33))
                        {
                            continue;
                        }

                        if (0 < $level)
                        {
                            $buildingsInfo[] = array(
                                'id' => $_i,
                                'item_id' => $item_id,
                                'level' => $level
                            );
                            continue;
                        }
                    }
                }

                $catapultTargetInfoArr = array();
                if (0 < sizeof($buildingsInfo))
                {
                    foreach ($catapultTargetArr as $catapultTargetItemId)
                    {
                        $targetExists = FALSE;
                        foreach ($buildingsInfo as $bInfo)
                        {
                            if ($catapultTargetItemId == $bInfo['item_id'])
                            {
                                $catapultTargetInfoArr[] = $bInfo;
                                $targetExists            = TRUE;
                                break;
                            }
                        }

                        if (!$targetExists)
                        {
                            $_randIndex              = mt_rand(0, sizeof($buildingsInfo) - 1);
                            $catapultTargetInfoArr[] = $buildingsInfo[$_randIndex];
                            continue;
                        }
                    }
                }

                if (0 < sizeof($catapultTargetInfoArr))
                {
                    if (1 < sizeof($catapultTargetInfoArr))
                    {
                        if ($catapultTargetInfoArr[0]['id'] == $catapultTargetInfoArr[1]['id'])
                        {
                            $tmp                     = $catapultTargetInfoArr[0];
                            $catapultTargetInfoArr   = array();
                            $catapultTargetInfoArr[] = $tmp;
                        }
                    }
                    $this->load_model('Artefacts', 'A');
                    $artLevel = $this->A->Artefacts($taskRow['to_player_id'], $taskRow['to_village_id'], 2);
                    $artPower = ($artLevel == 0) ? 1 : (($artLevel == 1) ? 4 : (($artLevel == 2) ? 3 : 5));
                    $buildDestrTroopsCount = floor($buildDestrTroopsCount / sizeof($catapultTargetInfoArr));
                    foreach ($catapultTargetInfoArr as $catapultTargetInfoItem)
                    {
                        if ($catapultResult != '')
                        {
                            $catapultResult .= '#';
                        }

                        $canDestructBuilding = $catapultTargetInfoItem['level'] * $buildinStabilityFactor * $artPower * 100 <= $buildDestrTroopsCount;
                        if ($canDestructBuilding)
                        {
                            $dropBuildingLevels = floor($buildDestrTroopsCount / ($catapultTargetInfoItem['level'] * $buildinStabilityFactor * $artPower * 100));
                            if ($catapultTargetInfoItem['level'] - $dropBuildingLevels < 0)
                            {
                                $dropBuildingLevels = $catapultTargetInfoItem['level'];
                            }

                            $catapultResult .= $catapultTargetInfoItem['item_id'] . ' ' . $catapultTargetInfoItem['level'] . ' ' . ($catapultTargetInfoItem['level'] - $dropBuildingLevels);

                            $this->load_model('Queuejob', 'mq');
                            while (0 < $dropBuildingLevels--)
                            {
                                $this->mq->upgradeBuilding($toVillageRow['id'], $catapultTargetInfoItem['id'], $catapultTargetInfoItem['item_id'], TRUE);
                            }
                            continue;
                        }
                        $catapultResult .= $catapultTargetInfoItem['item_id'] . ' ' . $catapultTargetInfoItem['level'] . ' -1';
                    }
                }
                $this->load_model('Artefacts', 'A');
                $haveArtefacts = $this->A->GetMyArtefacts($taskRow['to_village_id'], $taskRow['to_player_id']);
                if (!$toVillageRow['is_special_village'] && !$haveArtefacts)
                {
                    $checkToVillageRow        = $this->_getVillageInfo($taskRow['to_village_id']);
                    $villageTotallyDestructed = TRUE;
                    $bStr                     = trim($checkToVillageRow['buildings']);
                    if ($bStr != '')
                    {
                        $bStrArr = explode(',', $bStr);
                        $_i      = 0;
                        foreach ($bStrArr as $b2Str)
                        {
                            ++$_i;
                            list($item_id, $level) = explode(' ', $b2Str);
                            if (0 < $level)
                            {
                                $villageTotallyDestructed = FALSE;
                                break;
                            }
                        }
                    }

                    if ($villageTotallyDestructed)
                    {
                        $leave = TRUE;
                        if ($toVillageRow['is_capital'])
                        {
                            $playerdata = db::get_row('SELECT p.villages_count FROM p_players p WHERE p.id=:id',array(
                                'id' => $checkToVillageRow['player_id']
                            ));
                            if ($playerdata['villages_count'] >= 2)
                            {
                                $capitlVill = db::get_row('SELECT v.id FROM p_villages v WHERE v.player_id=:pid AND v.id!=:id ORDER BY v.people_count DESC LIMIT 0,1',array(
                                    'id' => $checkToVillageRow['id'],
                                    'pid' => $checkToVillageRow['player_id']
                                ));
                                db::query('UPDATE p_villages v SET v.is_capital=1 WHERE v.id=:id AND v.player_id=:pid',array(
                                    'id' => $capitlVill['id'],
                                    'pid' => $checkToVillageRow['player_id']
                                ));
                                db::query('UPDATE p_villages v SET v.is_capital=0 WHERE v.id=:id AND v.player_id=:pid',array(
                                    'id' => $checkToVillageRow['id'],
                                    'pid' => $checkToVillageRow['player_id']
                                ));
                            }
                            else
                            {
                                $this->DeleteTroopOut($toVillageRow);
                                $leave = FALSE;
                            }
                        }
                        if ($leave)
                        {
                            $catapultResult = '+';
                            $this->DeleteTroopOut($toVillageRow);
                            $this->leaveVillage($toVillageRow['id'], $toVillageRow['player_id'], 0, $toVillageRow['parent_id']);
                        }
                    }
                }
            }
        }

        if (!$toVillageRow['is_oasis'] AND !$warResult['all_attack_killed'] AND $taskRow['proc_type'] != QS_WAR_ATTACK_PLUNDER AND $procInfo['troopsArray']['hasHero'])
        {
            $this->load_model('Artefacts', 'A');
            $DhaveArtefacts = $this->A->GetMyArtefacts($taskRow['to_village_id'], $taskRow['to_player_id']);
            if ($DhaveArtefacts)
            {
                if ($this->A->GetArtefactsNum($taskRow['player_id']) < 3 || $taskRow['to_player_id'] == $taskRow['player_id'])
                {
                    $havepig = FALSE;
                    if ($DhaveArtefacts['size'] > 1)
                    {
                        $havepig = ($this->A->GetArtefactsNumPig($taskRow['player_id']) > 0);
                    }
                    if (!$havepig || $taskRow['to_player_id'] == $taskRow['player_id'])
                    {
                        $AhaveArtefacts = $this->A->GetMyArtefacts($taskRow['village_id'], $taskRow['player_id']);
                        if (!$AhaveArtefacts)
                        {
                            $checkToVillageRow = $this->_getVillageInfo($taskRow['to_village_id']);
                            $b27_exists     = FALSE;
                            $bStr              = trim($checkToVillageRow['buildings']);
                            if ($bStr != '')
                            {
                                $bStrArr = explode(',', $bStr);
                                foreach ($bStrArr as $b2Str)
                                {
                                    list($item_id, $level) = explode(' ', $b2Str);
                                    if (0 < $level AND $item_id == 27)
                                    {
                                        $b27_exists = TRUE;
                                        break;
                                        continue;
                                    }
                                }
                            }
                            if (!$b27_exists)
                            {
                                $checkFromVillageRow = $this->_getVillageInfo($taskRow['village_id']);
                                $bStr              = trim($checkFromVillageRow['buildings']);
                                $b27_level = 0;
                                if ($bStr != '')
                                {
                                    $bStrArr = explode(',', $bStr);
                                    foreach ($bStrArr as $b2Str)
                                    {
                                        @list($item_id, $level) = explode(' ', $b2Str);
                                        if ($item_id == 27)
                                        {
                                            $b27_level = $level;
                                            break;
                                            continue;
                                        }
                                    }
                                }
                                if (($b27_level >= 10 AND $DhaveArtefacts['size'] == 1) || ($b27_level == 20 AND $DhaveArtefacts['size'] > 1))
                                {
                                    $this->A->captureArtefacts($taskRow['to_village_id'], $taskRow['to_player_id'], $taskRow['village_id'], $taskRow['player_id']);
                                }
                            }
                        }
                    }
                }
            }
        }

        $doTroopsBack    = TRUE;
        $villageCaptured = FALSE;
        $captureResult   = '';
        if (((((((($procInfo['troopsArray']['hasKing'] AND !$toVillageRow['is_oasis']) AND !$warResult['all_attack_killed']) AND $taskRow['proc_type'] != QS_WAR_ATTACK_PLUNDER) AND !$toVillageRow['is_capital']) AND !$villageTotallyDestructed) AND $warResult['all_defense_killed']) AND $toVillageRow['player_id'] != $fromVillageRow['player_id']))
        {
            $checkToVillageRow = $this->_getVillageInfo($taskRow['to_village_id']);
            $b25_26_exists     = FALSE;
            $bStr              = trim($checkToVillageRow['buildings']);
            if ($bStr != '')
            {
                $bStrArr = explode(',', $bStr);
                foreach ($bStrArr as $b2Str)
                {
                    @list($item_id, $level) = explode(' ', $b2Str);
                    if ((0 < $level AND ($item_id == 25 OR $item_id == 26)))
                    {
                        $b25_26_exists = TRUE;
                        break;
                        continue;
                    }
                }
            }

            $kingIsLive = FALSE;
            foreach ($warResult['attackTroops']['troops'] as $tid => $tprop)
            {
                if ((((($tid == 9 OR $tid == 19) OR $tid == 29) OR $tid == 108) OR $tid == 59))
                {
                    $kingIsLive = 0 < $tprop['live_number'];
                    break;
                }
            }

            if (($kingIsLive AND !$b25_26_exists))
            {
                $allegiance_percent = $toVillageRow['allegiance_percent'];
                $allegiance_percent -= mt_rand(17, 25) * $tprop['live_number'];
                if (0 < $allegiance_percent)
                {
                    db::query('UPDATE p_villages v SET v.allegiance_percent=:ap WHERE v.id=:id', array(
                        'ap' => $allegiance_percent,
                        'id' => intval($toVillageRow['id'])
                    ));
                    $captureResult = $toVillageRow['allegiance_percent'] . '-' . $allegiance_percent;
                }
                else
                {
                    $allegiance_percent = 0;
                    $captureResult      = '+';
                }

                if ($allegiance_percent == 0)
                {
                    $villageCaptured    = TRUE;
                    $kingCropConumption = 0;
                    $doTroopsBack       = FALSE;
                    foreach ($warResult['attackTroops']['troops'] as $tid => $tprop)
                    {
                        if ((((($tid == 9 OR $tid == 19) OR $tid == 29) OR $tid == 108) OR $tid == 59))
                        {
                            $kingCropConumption = $GLOBALS['GameMetadata']['troops'][$tid]['crop_consumption'];
                            break;
                        }

                        if (0 < $tprop['live_number'])
                        {
                            $doTroopsBack = TRUE;
                            continue;
                        }
                    }
                    if ($doTroopsBack == FALSE && $procInfo['troopsArray']['hasHero'])
                    {
                        db::query("UPDATE p_players p SET p.hero_troop_id=NULL, p.hero_in_village_id=NULL WHERE p.id=:id", array(
                            'id' => intval($fromVillageRow['player_id'])
                        ));
                    }
                    $this->load_model('Artefacts', 'A');
                    $DhaveArtefacts = $this->A->GetMyArtefacts($taskRow['to_village_id'], $taskRow['to_player_id']);
                    if ($DhaveArtefacts)
                    {
                        $this->A->captureArtefacts($taskRow['to_village_id'], $taskRow['to_player_id'], $taskRow['to_village_id'], $taskRow['player_id']);
                    }
                    $this->DeleteTroopOut($toVillageRow);
                    $this->leaveVillage($toVillageRow['id'], $toVillageRow['player_id'], $toVillageRow['people_count'], $toVillageRow['parent_id'], FALSE);
                    $this->captureVillage($toVillageRow, $fromVillageRow, $kingCropConumption);
                }
            }
        }

        $oasisResult = '';
        if (((((($procInfo['troopsArray']['hasHero'] AND $toVillageRow['is_oasis']) AND !$warResult['all_attack_killed']) AND $warResult['all_defense_killed']) AND $toVillageRow['player_id'] != $fromVillageRow['player_id']) AND 10 <= $heroBuildingLevel))
        {
            $canCaptureOasis    = FALSE;
            $numberOfOwnedOases = (trim($fromVillageRow['village_oases_id']) == '' ? 0 : sizeof(explode(',', $fromVillageRow['village_oases_id'])));
            if ($heroBuildingLevel == 20)
            {
                $canCaptureOasis = $numberOfOwnedOases < 9;
            }
            else
            {
                if (15 <= $heroBuildingLevel)
                {
                    $canCaptureOasis = $numberOfOwnedOases < 6;
                }
                else
                {
                    if (1 <= $heroBuildingLevel)
                    {
                        $canCaptureOasis = $numberOfOwnedOases < 3;
                    }
                }
            }

            $oasisInRang = TRUE;
            $rang        = 3;
            $map_size    = $SetupMetadata['map_size'];
            $x           = $fromVillageRow['rel_x'];
            $y           = $fromVillageRow['rel_y'];
            $mi          = 0 - $rang;
            while ($mi <= $rang)
            {
                if ($oasisInRang)
                {
                    break;
                }

                $mj = 0 - $rang;
                while ($mj <= $rang)
                {
                    if ($toVillageRow['id'] == $this->__getVillageId($map_size, $this->__getCoordInRange($map_size, $x + $mi), $this->__getCoordInRange($map_size, $y + $mj)))
                    {
                        $oasisInRang = TRUE;
                        break;
                    }

                    ++$mj;
                }

                ++$mi;
            }

            if (($canCaptureOasis AND $oasisInRang))
            {
                $this->load_model('Queuejob', 'qm');
                if (intval($toVillageRow['player_id']) == 0)
                {
                    $oasisResult = '+';
                    $this->qm->captureOasis($toVillageRow['id'], $fromVillageRow['player_id'], $fromVillageRow['id'], TRUE);
                }
                else
                {
                    $allegiance_percent = $toVillageRow['allegiance_percent'];
                    $allegiance_percent -= 25;
                    if (0 < $allegiance_percent)
                    {
                        $oasisResult = $toVillageRow['allegiance_percent'] . '-' . $allegiance_percent;
                        db::query('UPDATE p_villages v SET v.allegiance_percent=:ap WHERE v.id=:id', array(
                            'ap' => $allegiance_percent,
                            'id' => intval($toVillageRow['id'])
                        ));
                    }
                    else
                    {
                        $allegiance_percent = 0;
                        $oasisResult        = '+';
                    }

                    if ($allegiance_percent == 0)
                    {
                        $this->qm->captureOasis($toVillageRow['id'], $toVillageRow['player_id'], $toVillageRow['parent_id'], FALSE);
                        $this->qm->captureOasis($toVillageRow['id'], $fromVillageRow['player_id'], $fromVillageRow['id'], TRUE);
                    }
                }
            }
        }

        $newTroops = '';
        foreach ($warResult['attackTroops']['troops'] as $tid => $tprop)
        {
            if ($newTroops != '')
            {
                $newTroops .= ',';
            }

            $newTroops .= $tid . ' ' . $tprop['number'] . ' ' . ($tprop['number'] - $tprop['live_number']);
        }

        if ($procInfo['troopsArray']['hasHero'])
        {
            if ($newTroops != '')
            {
                $newTroops .= ',';
            }

            $newTroops .= 0 - 1 . ' ' . 1 . ' ' . ($warResult['all_attack_killed'] ? 1 : 0);
        }

        $attackReportTroops  = $newTroops;
        $defenseReportTroops = '';
        foreach ($reportTroopTable as $tribeId => $defTroops)
        {
            $defenseReportTroops1 = '';
            if ($tribeId == 4)
            {
                $monsterTroops = array();
                foreach ($GLOBALS['GameMetadata']['troops'] as $t4k => $t4v)
                {
                    if ($t4v['for_tribe_id'] == 4)
                    {
                        $monsterTroops[$t4k] = array(
                            'number' => (isset($defTroops['troops'][$t4k]) ? $defTroops['troops'][$t4k]['number'] : 0),
                            'dead_number' => (isset($defTroops['troops'][$t4k]) ? $defTroops['troops'][$t4k]['dead_number'] : 0)
                        );
                        continue;
                    }
                }

                $defTroops['troops'] = $monsterTroops;
            }

            foreach ($defTroops['troops'] as $tid => $tArr)
            {
                if ($defenseReportTroops1 != '')
                {
                    $defenseReportTroops1 .= ',';
                }

                $defenseReportTroops1 .= $tid . ' ' . $tArr['number'] . ' ' . $tArr['dead_number'];
            }

            if (0 < $defTroops['hero']['number'])
            {
                if ($defenseReportTroops1 != '')
                {
                    $defenseReportTroops1 .= ',';
                }

                $defenseReportTroops1 .= 0 - 1 . ' ' . $defTroops['hero']['number'] . ' ' . $defTroops['hero']['dead_number'];
            }

            if ($defenseReportTroops1 != '')
            {
                if ($defenseReportTroops != '')
                {
                    $defenseReportTroops .= '#';
                }

                $defenseReportTroops .= $defenseReportTroops1;
                continue;
            }
        }

        $timeInSeconds = $taskRow['remainingTimeInSeconds'];
        $attackDigit   = 0;
        $defenseDigit  = 0;
        if ($warResult['all_attack_killed'])
        {
            $attackDigit  = 3;
            $defenseDigit = (0 < $warResult['defense_total_dead_number'] ? 5 : 4);
        }
        else
        {
            $attackDigit  = (0 < $warResult['attackTroops']['total_dead_number'] ? 2 : 1);
            $defenseDigit = (0 < $warResult['defense_total_dead_number'] ? 6 : 7);
        }

        $reportResult   = $defenseDigit * 10 + $attackDigit;
        $reportCategory = 3;
        $reportBody     = $attackReportTroops . '|' . $defenseReportTroops . '|' . $warResult['attackTroops']['total_carry_load'] . '|' . $harvestResources . '|' . $wallDestructionResult . '|' . $catapultResult . '|' . $oasisResult . '|' . $captureResult;

        $this->load_model('Report', 'r');
        $this->r->createReport(intval($fromVillageRow['player_id']), intval($toVillageRow['player_id']), intval($fromVillageRow['id']), intval($toVillageRow['id']), $reportCategory, $reportResult, $reportBody, $timeInSeconds);
        if (intval($toVillageRow['player_id']) != intval($fromVillageRow['player_id']))
        {
            $statisticPoint = 0;
            $harvestPoint   = $harvestInfoStruct['sum'];
            if ((0 < intval($toVillageRow['player_id']) AND intval($toVillageRow['tribe_id']) != 5))
            {
                $statisticPoint = $warResult['defense_statisticPoint'] / 10;
                db::query('UPDATE p_players p SET p.defense_points=p.defense_points+:a, p.week_defense_points=p.week_defense_points+:w WHERE p.id=:id', array(
                    'a' => $statisticPoint,
                    'w' => $statisticPoint,
                    'id' => intval($toVillageRow['player_id'])
                ));
            }

            if ((0 < intval($fromVillageRow['player_id']) AND intval($fromVillageRow['tribe_id']) != 5))
            {
                $statisticPoint = $warResult['attack_statisticPoint'] / 10;
                db::query('UPDATE p_players p SET p.attack_points=p.attack_points+:a, p.week_attack_points=p.week_attack_points+:wa, p.week_thief_points=p.week_thief_points+:wt WHERE p.id=:id', array(
                    'a' => $statisticPoint,
                    'wa' => $statisticPoint,
                    'wt' => $harvestPoint,
                    'id' => intval($fromVillageRow['player_id'])
                ));
            }

            if (0 < intval($toVillageRow['alliance_id']))
            {
                $statisticPoint = $warResult['defense_statisticPoint'] / 10;
                $allianceRate   = ($warResult['all_attack_killed'] ? 1 : 0);
                db::query('UPDATE p_alliances p SET p.rating=p.rating+:r, p.defense_points=p.defense_points+:d, p.week_defense_points=p.week_defense_points+:w WHERE p.id=:id', array(
                    'r' => $allianceRate,
                    'd' => $statisticPoint,
                    'w' => $statisticPoint,
                    'id' => intval($toVillageRow['alliance_id'])
                ));
            }

            if (0 < intval($fromVillageRow['alliance_id']))
            {
                $statisticPoint = $warResult['attack_statisticPoint'] / 10;
                $allianceRate   = ((!$warResult['all_attack_killed'] AND 0 < $statisticPoint) ? 1 : 0);
                db::query('UPDATE p_alliances p SET p.rating=p.rating+:r, p.attack_points=p.attack_points+:a, p.week_attack_points=p.week_attack_points+:wa, p.week_thief_points=p.week_thief_points+:wt WHERE p.id=:id', array(
                    'r' => $allianceRate,
                    'a' => $statisticPoint,
                    'wa' => $statisticPoint,
                    'wt' => $harvestPoint,
                    'id' => intval($fromVillageRow['alliance_id'])
                ));
            }
        }

        if ((!$warResult['all_attack_killed'] AND $doTroopsBack))
        {
            $paramsArray                           = explode('|', $taskRow['proc_params']);
            $paramsArray[sizeof($paramsArray) - 1] = 1;
            $newTroops                             = '';
            foreach ($warResult['attackTroops']['troops'] as $tid => $tprop)
            {
                if ($newTroops != '')
                {
                    $newTroops .= ',';
                }

                if (($villageCaptured AND (((($tid == 9 OR $tid == 19) OR $tid == 29) OR $tid == 108) OR $tid == 59)))
                {
                    $tprop['live_number'] -= 1;
                }

                $newTroops .= $tid . ' ' . $tprop['live_number'];
            }

            if ((!$warResult['all_attack_killed'] AND $procInfo['troopsArray']['hasHero']))
            {
                if ($newTroops != '')
                {
                    $newTroops .= ',';
                }

                $newTroops .= $procInfo['troopsArray']['heroTroopId'] . ' -1';
            }

            $paramsArray[0] = $newTroops;
            $paramsArray[4] = $harvestResources;
            $newParams      = implode('|', $paramsArray);
            db::query('UPDATE p_queue q
        SET
          q.player_id=:a1,
          q.village_id=:a2,
          q.to_player_id=:a3,
          q.to_village_id=:a4,
          q.proc_type=:a5,
          q.proc_params=:a6,
          q.end_date=(q.end_date + INTERVAL q.execution_time SECOND)
        WHERE q.id=:a7', array(
                'a1' => intval($taskRow['to_player_id']),
                'a2' => intval($taskRow['to_village_id']),
                'a3' => intval($taskRow['player_id']),
                'a4' => intval($taskRow['village_id']),
                'a5' => QS_WAR_REINFORCE,
                'a6' => $newParams,
                'a7' => intval($taskRow['id'])
            ));
            return TRUE;
        }

        return FALSE;
    }
    //War result function
    public function getWarResult($attackTroops, $defenseTroops, $totalDefensePower, $isPlunderAttack)
    {
        $warResult        = array(
            'all_attack_killed' => FALSE,
            'all_defense_killed' => TRUE,
            'defense_total_dead_number' => 0,
            'attack_statisticPoint' => 0,
            'defense_statisticPoint' => 0
        );
        $attack_infantry  = $attackTroops['total_attack_infantry']; // Total Forsan Attack Power
        $attack_cavalry   = $attackTroops['total_attack_cavalry']; // Total Moshah Attack Power
        $totalAttackPower = $attackTroops['total_attack_power']; // All Attack power after edite
        $totalLiveNumber  = array();
        $defense_infantry = $total_defense = $defense_cavalry = 0;
        // get all defense live number and defense power
        foreach ($defenseTroops as $vid => $troopsTable)
        {
            $total_defense += $defenseTroops[$vid]['total_live_number']; // All defense live number
            $defense_infantry += $defenseTroops[$vid]['total_defense_infantry']; // All moshah defense power
            $defense_cavalry += $defenseTroops[$vid]['total_defense_cavalry']; // All forsan defense power
            $defense_infantry_v    = $defenseTroops[$vid]['total_defense_infantry']; // this village moshah defense power
            $defense_cavalry_v     = $defenseTroops[$vid]['total_defense_cavalry']; // this village forsan defense power
            $totalLiveNumber[$vid] = $defenseTroops[$vid]['total_live_number']; // this village all live number
            // Get all defense power for this village
            if ($totalAttackPower == 0)
            {
                $defenseTroops[$vid]['total_defense_power'] = $defense_cavalry_v;
            }
            else
            {
                // all defense power = this village moshah defense power x Total Moshah Attack Power /  All Troop Attack Power
                // + this village moshah defense power x Total Forsan Attack Power / All Troop Attack Power
                // to get the medle defense betwene moshah defense and forsan defense
                $defenseTroops[$vid]['total_defense_power'] = ($defense_infantry_v * ($attack_infantry / $totalAttackPower)) + ($defense_cavalry_v * ($attack_cavalry / $totalAttackPower));
            }
        }
        // Get all village defense power
        if ($totalAttackPower == 0)
        {
            $totalDefensePower2 = $defense_cavalry;
        }
        else
        {
            // all defense power = All moshah defense power x Total Moshah Attack Power /  All Troop Attack Power
            // + All moshah defense power x Total Forsan Attack Power / All Troop Attack Power
            // to get the medle defense betwene moshah defense and forsan defense
            $totalDefensePower2 = ($defense_infantry * ($attack_infantry / $totalAttackPower)) + ($defense_cavalry * ($attack_cavalry / $totalAttackPower));
        }
        if ($totalAttackPower == $totalDefensePower2)
        {
            $totalDefensePower2 += 1;
        }
        $allTroopNum = $total_defense + $attackTroops['total_live_number'];
        if ($allTroopNum >= 1000)
        {
            $Mfactor = 2 * (1.8592 - pow($allTroopNum, 0.015));
        }
        else
        {
            $Mfactor = 1.5;
        }
        if ($Mfactor < 1.25778)
        {
            $Mfactor = 1.25778;
        }
        elseif ($Mfactor > 1.5)
        {
            $Mfactor = 1.5;
        }
        $attackWin = ($totalAttackPower > $totalDefensePower2);
        if ($isPlunderAttack)
        {
            $holder             = ($attackWin) ? pow(($totalDefensePower2 / $totalAttackPower), $Mfactor) : pow(($totalAttackPower / $totalDefensePower2), $Mfactor);
            $holder             = $holder / (1 + $holder);
            $attack_casualties  = ($attackWin) ? $holder : 1 - $holder;
            $defense_casualties = ($attackWin) ? 1 - $holder : $holder;
        }
        else
        {
            $attack_casualties  = ($attackWin) ? pow(($totalDefensePower2 / $totalAttackPower), $Mfactor) : 1;
            $defense_casualties = ($attackWin) ? 1 : pow(($totalAttackPower / $totalDefensePower2), $Mfactor);
        }
        // Get attack troop dead number
        foreach ($attackTroops['troops'] as $tid => $tProp)
        {
            $tdeadNum = round($attack_casualties * $tProp['live_number']); // soldier dead number
            if ($tdeadNum > $tProp['live_number'])
            {
                $tdeadNum = $tProp['live_number'];
            }
            $warResult['defense_statisticPoint'] += $tdeadNum * $tProp['single_power'];
            $attackTroops['total_attack_power'] -= $tdeadNum * $tProp['single_power']; // all attack power - soldier dead power
            $attackTroops['total_carry_load'] -= $tdeadNum * $tProp['single_carry_load'];
            $attackTroops['total_dead_consumption'] += $tdeadNum * $tProp['single_consumption'];
            $attackTroops['total_dead_number'] += $tdeadNum; // all dead soldier number - soldier dead number
            $attackTroops['total_live_number'] -= $tdeadNum; // all live soldier number - soldier dead number
            $attackTroops['troops'][$tid]['live_number'] -= $tdeadNum; // soldier number - soldier dead number
        }
        if ($attackTroops['total_live_number'] <= 0)
        {
            $warResult['all_attack_killed'] = TRUE;
        }
        // Get defense troop dead number like attack
        foreach ($defenseTroops as $vid => $troopsTable)
        {
            foreach ($troopsTable['troops'] as $tid => $tProp)
            {
                if ($totalAttackPower == 0)
                {
                    $sPower = $tProp['defense_cavalry'];
                }
                else
                {
                    $sPower = ($tProp['single_power'] * ($attack_infantry / $totalAttackPower)) + ($tProp['defense_cavalry'] * ($attack_cavalry / $totalAttackPower));
                }
                $tdeadNum = round($defense_casualties * $tProp['live_number']);
                if ($tdeadNum > $tProp['live_number'])
                {
                    $tdeadNum = $tProp['live_number'];
                }
                $warResult['attack_statisticPoint'] += $tdeadNum * $tProp['single_power'];
                $warResult['defense_total_dead_number'] += $tdeadNum;
                $defenseTroops[$vid]['total_dead_consumption'] += $tdeadNum * $tProp['single_consumption'];
                $defenseTroops[$vid]['total_dead_number'] += $tdeadNum;
                $defenseTroops[$vid]['total_live_number'] -= $tdeadNum;
                $defenseTroops[$vid]['troops'][$tid]['live_number'] -= $tdeadNum;
            }
        }
        $warResult['all_defense_killed'] = $total_defense <= $warResult['defense_total_dead_number'];
        $warResult['attackTroops']       = $attackTroops;
        $warResult['defenseTroops']      = $defenseTroops;
        return $warResult;
    }

    public function captureVillage($toVillageRow, $fromVillageRow, $kingCropConumption)
    {
        $GameMetadata  = $GLOBALS['GameMetadata'];
        $SetupMetadata = $GLOBALS['SetupMetadata'];
        $troops_training = '';
        $troops_num      = '';
        foreach ($GameMetadata['troops'] as $k => $v)
        {
            if (($v['for_tribe_id'] == 0 - 1 OR $v['for_tribe_id'] == $fromVillageRow['tribe_id']))
            {
                if ($troops_training != '')
                {
                    $troops_training .= ',';
                }

                $researching_done = ($v['research_time_consume'] == 0 ? 1 : 0);
                $troops_training .= $k . ' ' . $researching_done . ' 0 0';
                if ($troops_num != '')
                {
                    $troops_num .= ',';
                }

                $troops_num .= $k . ' 0';
                continue;
            }
        }

        $troops_num = '-1:' . $troops_num;
        $buildings  = '';
        $bStr       = trim($toVillageRow['buildings']);
        if ($bStr != '')
        {
            $bStrArr = explode(',', $bStr);

            $this->load_model('Queuejob', 'mq');
            $ccb = 0;
            foreach ($bStrArr as $b2Str)
            {
                ++$ccb;
                if ($buildings != '')
                {
                    $buildings .= ',';
                }

                list($item_id, $level, $update_state) = explode(' ', $b2Str);
                if (!isset($GameMetadata['items'][$item_id]['for_tribe_id'][$fromVillageRow['tribe_id']]))
                {
                    while (0 < $level--)
                    {
                        $this->mq->upgradeBuilding($toVillageRow['id'], $ccb, $item_id, TRUE);
                    }

                    $item_id = $level = $update_state = 0;
                }
                $buildings .= $item_id . ' ' . $level . ' ' . $update_state;
            }
        }

        db::query('UPDATE p_queue q SET q.player_id=:pid WHERE q.player_id=:id AND q.village_id=:vid', array(
            'id' => intval($toVillageRow['player_id']),
            'vid' => intval($toVillageRow['id']),
            'pid' => intval($fromVillageRow['player_id'])
        ));

        db::query('UPDATE p_queue q SET q.to_player_id=:pid WHERE q.to_player_id=:id AND q.to_village_id=:vid', array(
            'id' => intval($toVillageRow['player_id']),
            'vid' => intval($toVillageRow['id']),
            'pid' => intval($fromVillageRow['player_id'])
        ));

		$oasis = explode(',', $toVillageRow['village_oases_id']);
		foreach($oasis as $oasisId)
		{
		    db::query('UPDATE p_queue q SET q.to_player_id=:pid WHERE q.to_player_id=:id AND q.to_village_id=:vid', array(
                'id' => intval($toVillageRow['player_id']),
                'vid' => intval($oasisId),
                'pid' => intval($fromVillageRow['player_id'])
            ));
		}

        db::query('UPDATE p_villages v
        SET
          v.parent_id=:a1,
          v.tribe_id=:a2,
          v.player_id=:a3,
          v.alliance_id=:a4,
          v.player_name=:a5,
          v.alliance_name=:a6,
          v.is_capital=0,
          v.buildings=:a7,
          v.troops_training=:a8,
          v.troops_num=:a9,
          v.child_villages_id=NULL,
          v.allegiance_percent=100,
          v.troops_out_num=NULL,
          v.troops_out_intrap_num=NULL,
          v.creation_date=NOW(),
          v.last_update_date=NOW()
        WHERE v.id=:a10', array(
            'a1' => intval($fromVillageRow['id']),
            'a2' => intval($fromVillageRow['tribe_id']),
            'a3' => intval($fromVillageRow['player_id']),
            'a4' => (0 < intval($fromVillageRow['alliance_id']) ? intval($fromVillageRow['alliance_id']) : NULL),
            'a5' => $fromVillageRow['player_name'],
            'a6' => $fromVillageRow['alliance_name'],
            'a7' => $buildings,
            'a8' => $troops_training,
            'a9' => $troops_num,
            'a10' => intval($toVillageRow['id'])
        ));

        db::query('UPDATE p_villages v
        SET
          v.tribe_id=:a1,
          v.player_id=:a2,
          v.alliance_id=:a3,
          v.player_name=:a4,
          v.alliance_name=:a5,
          v.troops_num=NULL,
          v.troops_out_num=NULL,
          v.troops_out_intrap_num=NULL
        WHERE v.parent_id=:a6 AND v.is_oasis=1', array(
            'a1' => intval($fromVillageRow['tribe_id']),
            'a2' => intval($fromVillageRow['player_id']),
            'a3' => (0 < intval($fromVillageRow['alliance_id']) ? intval($fromVillageRow['alliance_id']) : NULL),
            'a4' => $fromVillageRow['player_name'],
            'a5' => $fromVillageRow['alliance_name'],
            'a6' => intval($toVillageRow['id'])
        ));
        $child_villages_id = trim($fromVillageRow['child_villages_id']);
        if ($child_villages_id != '')
        {
            $child_villages_id .= ',';
        }

        $child_villages_id .= $toVillageRow['id'];
        db::query('UPDATE p_villages v SET v.crop_consumption=v.crop_consumption-:crop, v.child_villages_id=:vid WHERE v.id=:id', array(
            'crop' => $kingCropConumption,
            'vid' => $child_villages_id,
            'id' => intval($fromVillageRow['id'])
        ));
        $prow        = db::get_row('SELECT p.villages_id, p.villages_data FROM p_players p WHERE p.id=:id', array(
            'id' => intval($fromVillageRow['player_id'])
        ));
        $villages_id = trim($prow['villages_id']);
        if ($villages_id != '')
        {
            $villages_id .= ',';
        }

        $villages_id .= $toVillageRow['id'];
        $villages_data = trim($prow['villages_data']);
        if ($villages_data != '')
        {
            $villages_data .= "\n";
        }

        $villages_data .= $toVillageRow['id'] . ' ' . $toVillageRow['rel_x'] . ' ' . $toVillageRow['rel_y'] . ' ' . $toVillageRow['village_name'];
        db::query('UPDATE p_players p
        SET
          p.total_people_count=p.total_people_count+:pe,
          p.villages_count=p.villages_count+1,
          p.selected_village_id=:svi,
          p.villages_id=:vid,
          p.villages_data=:vda
        WHERE
          p.id=:id', array(
            'pe' => intval($toVillageRow['people_count']),
            'svi' => intval($toVillageRow['id']),
            'vid' => $villages_id,
            'vda' => $villages_data,
            'id' => intval($fromVillageRow['player_id'])
        ));
    }

    public function leaveVillage($villageId, $playerId, $village_people_count, $parent_id, $doReset = TRUE)
    {
        $selected_village_id = intval(db::get_field('SELECT v.id FROM p_villages v WHERE v.player_id=:pid AND v.is_capital=1', array(
            'pid' => intval($playerId)
        )));
        $prow                = db::get_row('SELECT p.villages_data, p.villages_id FROM p_players p WHERE p.id=:id', array(
            'id' => intval($playerId)
        ));
        $villages_id         = trim($prow['villages_id']);
        if ($villages_id != '')
        {
            $villages_idArr = explode(',', $villages_id);
            $villages_id    = '';
            foreach ($villages_idArr as $villages_idArrItem)
            {
                if ($villages_idArrItem == $villageId)
                {
                    continue;
                }

                if ($villages_id != '')
                {
                    $villages_id .= ',';
                }

                $villages_id .= $villages_idArrItem;
            }
        }

        $villages_data = trim($prow['villages_data']);
        if ($villages_data != '')
        {
            $villages_dataArr = explode("\n", $villages_data);
            $villages_data    = '';
            foreach ($villages_dataArr as $villages_dataArrItem)
            {
                $_varr = explode(' ', $villages_dataArrItem);
                if ($_varr[0] == $villageId)
                {
                    continue;
                }

                if ($villages_data != '')
                {
                    $villages_data .= "\n";
                }

                $villages_data .= implode(' ', $_varr);
            }
        }

        db::query('DELETE FROM p_merchants WHERE village_id=:id', array(
            'id' => intval($villageId)
        ));
        if (trim($parent_id) != '')
        {
            $prow              = db::get_row('SELECT v.child_villages_id FROM p_villages v WHERE v.id=:id', array(
                'id' => intval($parent_id)
            ));
            $child_villages_id = trim($prow['child_villages_id']);
            if ($child_villages_id != '')
            {
                $villages_idArr    = explode(',', $child_villages_id);
                $child_villages_id = '';
                foreach ($villages_idArr as $villages_idArrItem)
                {
                    if ($villages_idArrItem == $villageId)
                    {
                        continue;
                    }

                    if ($child_villages_id != '')
                    {
                        $child_villages_id .= ',';
                    }

                    $child_villages_id .= $villages_idArrItem;
                }
            }

            db::query('UPDATE p_villages v
        SET
          v.child_villages_id=:vid
        WHERE v.id=:id', array(
                'vid' => $child_villages_id,
                'id' => intval($parent_id)
            ));
        }

        if ($doReset)
        {
            db::query('UPDATE p_villages v
        SET
          v.tribe_id=IF(v.is_oasis=1, 4, 0),
          v.parent_id=NULL,
          v.player_id=NULL,
          v.alliance_id=NULL,
          v.player_name=NULL,
          v.village_name=NULL,
          v.alliance_name=NULL,
          v.is_capital=0,
          v.people_count=2,
          v.crop_consumption=2,
          v.time_consume_percent=100,
          v.offer_merchants_count=0,
          v.resources=IF(v.is_oasis=1, v.resources, NULL),
          v.cp=IF(v.is_oasis=1, \'0 0\', NULL),
          v.buildings=NULL,
          v.troops_training=NULL,
          v.child_villages_id=NULL,
          v.village_oases_id=NULL,
          v.troops_trapped_num=0,
          v.allegiance_percent=100,
          v.troops_num=IF(v.is_oasis=1, \'-1:31 0,34 0,37 0\', NULL),
          v.troops_out_num=NULL,
          v.troops_intrap_num=NULL,
          v.troops_out_intrap_num=NULL,
          v.creation_date=NOW()
        WHERE v.id=:id OR (v.parent_id=:pid AND v.is_oasis=1)', array(
                'id' => intval($villageId),
                'pid' => intval($villageId)
            ));
        }

        db::query('UPDATE p_villages v SET v.parent_id=NULL WHERE v.parent_id=:id AND v.is_oasis=0', array(
            'id' => intval($villageId)
        ));

        db::query('UPDATE p_players p
      SET
        p.total_people_count=IF(p.total_people_count-:a1<0, 0, p.total_people_count-:a1),
        p.villages_count=IF(p.villages_count-1<1, 1, p.villages_count-1),
        p.selected_village_id=:a3,
        p.villages_id=:a4,
        p.villages_data=:a5
      WHERE p.id=:a6', array(
            'a1' => intval($village_people_count),
            'a3' => intval($selected_village_id),
            'a4' => $villages_id,
            'a5' => $villages_data,
            'a6' => intval($playerId)
        ));
    }

    public function DeleteTroopOut($toVillageRow)
    {
        $ty            = QS_BUILD_CREATEUPGRADE . ',' . QS_BUILD_DROP . ',' . QS_TROOP_RESEARCH . ',' . QS_TROOP_UPGRADE_ATTACK . ',' . QS_TROOP_UPGRADE_DEFENSE . ',' . QS_TROOP_TRAINING . ',' . QS_TROOP_TRAINING_HERO . ',' . QS_WAR_ATTACK . ',' . QS_WAR_ATTACK_PLUNDER . ',' . QS_WAR_ATTACK_SPY . ',' . QS_CREATEVILLAGE;
        db::query("DELETE FROM p_queue WHERE player_id=:id AND village_id=:vid AND proc_type IN ($ty)", array(
            'id' => intval($toVillageRow['player_id']),
            'vid' => intval($toVillageRow['id'])
        ));

        $chekre = db::get_all("SELECT * FROM p_queue WHERE player_id=:id AND village_id=:vid AND proc_type=:ty ", array(
            'id' => intval($toVillageRow['player_id']),
            'vid' => intval($toVillageRow['id']),
            'ty' => QS_WAR_REINFORCE
        ));
        foreach ($chekre as $chekreRow)
        {
            $_arrt    = explode("|", $chekreRow['proc_params']);
            $ReinBack = $_arrt[sizeof($_arrt) - 1] == 1;
            if (!$ReinBack)
            {
                db::query('DELETE FROM p_queue WHERE id=:id ', array(
                    'id' => $chekreRow['id']
                ));
            }
        }
        unset($chekre);
        $chekre2 = db::get_all("SELECT * FROM p_queue WHERE to_player_id=:id AND to_village_id=:vid AND proc_type=:ty ", array(
            'id' => intval($toVillageRow['player_id']),
            'vid' => intval($toVillageRow['id']),
            'ty' => QS_WAR_REINFORCE
        ));
        foreach ($chekre2 as $chekre2Row)
        {
            $_arrt    = explode("|", $chekre2Row['proc_params']);
            $ReinBack = $_arrt[sizeof($_arrt) - 1] == 1;
            if ($ReinBack)
            {
                db::query('DELETE FROM p_queue WHERE id=:id ', array(
                    'id' => $chekre2Row['id']
                ));
            }
        }
        unset($chekre2);
        if ($toVillageRow['troops_out_num'] != "")
        {
            $t_arr = explode('|', $toVillageRow['troops_out_num']);
            foreach ($t_arr as $t_str)
            {
                list($_vid1, $_troops1) = explode(":", $t_str);
                $vrow = db::get_row("SELECT v.troops_num FROM p_villages v WHERE v.id=:id", array(
                    'id' => intval($_vid1)
                ));
                if ($vrow != null)
                {
                    $m_arr          = explode('|', $vrow['troops_num']);
                    $new_troops_num = '';
                    foreach ($m_arr as $m_str)
                    {
                        list($_vid2, $_troops2) = explode(":", $m_str);
                        if ($_vid2 != $toVillageRow['id'])
                        {
                            if ($new_troops_num != "")
                            {
                                $new_troops_num .= '|';
                            }
                            $new_troops_num .= $m_str;
                        }
                        $consume      = 0;
                        if ($_vid2 == $toVillageRow['id'])
                        {
                            $n_arr = explode(",", $_troops2);
                            foreach ($n_arr as $n_str)
                            {
                                list($tid, $tnum) = explode(" ", $n_str);
                                if ($tnum == 0 - 1)
                                {
                                    db::query("UPDATE p_players p SET p.hero_in_village_id=NULL, p.hero_troop_id=NULL WHERE p.id=:id", array(
                                            'id' => $toVillageRow['player_id']
                                    ));
                                    $consume += $GLOBALS['GameMetadata']['troops'][$tid]['crop_consumption'];
                                }
                                else
                                {
                                    $consume += $GLOBALS['GameMetadata']['troops'][$tid]['crop_consumption'] * $tnum;
                                }
                            }
                        }
                    }
                    db::query("UPDATE p_villages v SET v.troops_num=:num, v.crop_consumption=v.crop_consumption-:crop WHERE v.id=:id", array(
                        'num' => $new_troops_num,
                        'crop' => $consume,
                        'id' => intval($_vid1)
                    ));
                }
            }
        }
    }

    public function _harvestTroopsFrom($villageRow, $maxCarryLoad, $crannyTotalSize)
    {
        $this->load_model('Artefacts', 'A');
        $crop = $this->A->CropAndRes($villageRow['player_id'], $villageRow['id'], 5);
        $res  = $this->A->CropAndRes($villageRow['player_id'], $villageRow['id'], 7);
        if ($maxCarryLoad <= 0)
        {
            return array(
                'string' => '0 0 0 0',
                'sum' => 0
            );
        }
        $totalresources = 0;
        $resources      = array();
        $r_arr          = explode(',', $villageRow['resources']);
        foreach ($r_arr as $r_str)
        {
            $r2            = explode(' ', $r_str);
            $prate         = floor($r2[4] * (1 + ($r2[5]+$res) / 100)) - ($r2[0] == 4 ? floor($villageRow['crop_consumption']*$crop) : 0);
            $current_value = floor($r2[1] + $villageRow['elapsedTimeInSeconds'] * ($prate / 3600));
            if ($r2[2] < $current_value)
            {
                $current_value = $r2[2];
            }
            $resources[$r2[0]] = array(
                'current_value' => $current_value - $crannyTotalSize,
                'store_max_limit' => $r2[2],
                'store_init_limit' => $r2[3],
                'prod_rate' => $r2[4],
                'prod_rate_percentage' => $r2[5]
            );
            $totalresources += $resources[$r2[0]]['current_value'] < 0 ? 0 : $resources[$r2[0]]['current_value'];
        }

        $harvest     = array(0,0,0,0);
        $sum         = 0;
        $curTotalRes = 0;
        $m           = 0;
        foreach ($resources as $k => $rdata)
        {
            $v = $rdata['current_value'];

            if ($maxCarryLoad >= $totalresources)
            {
                $take = $resources[$k]['current_value'];
            }
            else
            {
                $takerat = $totalresources / ($totalresources - $maxCarryLoad);
                $take    = $resources[$k]['current_value'] - round($resources[$k]['current_value'] / $takerat);
            }

            if (0 < $v)
            {
                $resources[$k]['current_value'] -= $take;
                $harvest[$m] += $take;
                $sum += $take;
                $curTotalRes += $resources[$k]['current_value'];
            }

            ++$m;
        }

        $resourcesStr = '';
        foreach ($resources as $k => $v)
        {
            if ($resourcesStr != '')
            {
                $resourcesStr .= ',';
            }
            $resourcesStr .= sprintf('%s %s %s %s %s %s', $k, $v['current_value'] + $crannyTotalSize, $v['store_max_limit'], $v['store_init_limit'], $v['prod_rate'], $v['prod_rate_percentage']);
        }

        $elapsedTimeInSeconds = $villageRow['elapsedTimeInSeconds'];
        $cps                  = explode(' ', $villageRow['cp']);
        $cpValue              = $cps[0];
        $cpRate               = isset($cps[1]) ? $cps[1] : 0;
        $cpValue              = round($cpValue + $elapsedTimeInSeconds * ($cpRate / 86400), 4);
        $cp                   = $cpValue . ' ' . $cpRate;
        db::query('UPDATE p_villages v
      SET
        v.resources=:res,
        v.cp=:cp,
        v.last_update_date=NOW()
      WHERE
        v.id=:id', array(
            'res' => $resourcesStr,
            'cp' => $cp,
            'id' => intval($villageRow['id'])
        ));

        return array(
            'string' => implode(' ', $harvest),
            'sum' => $sum
        );
    }

    public function __getCoordInRange($map_size, $x)
    {
        if ($map_size <= $x)
        {
            $x -= $map_size;
        }
        else
        {
            if ($x < 0)
            {
                $x = $map_size + $x;
            }
        }

        return $x;
    }

    public function __getVillageId($map_size, $x, $y)
    {

        return $x * $map_size + ($y + 1);
    }

}
?>
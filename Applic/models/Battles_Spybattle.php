<?php
require_once MODELS_DIR.'Battle.php';
class Battles_Spybattle_Model extends Battle_Model
{

    public function handleWarSpy( $taskRow, $toVillageRow, $fromVillageRow, $procInfo )
    {
        if ( intval( $toVillageRow['player_id'] ) == 0 )
        {
            $paramsArray = explode( "|", $taskRow['proc_params'] );
            $paramsArray[sizeof( $paramsArray ) - 1] = 1;
            $newParams = implode( "|", $paramsArray );
            db::query( "UPDATE p_queue q  SET  q.player_id=:a1, q.village_id=:a2, q.to_player_id=:a3, q.to_village_id=:a4, q.proc_type=:a5, q.proc_params=:a6, q.end_date=(q.end_date + INTERVAL q.execution_time SECOND) WHERE q.id=:a7", array(
                'a1' => intval( $taskRow['to_player_id'] ),
                'a2' => intval( $taskRow['to_village_id'] ),
                'a3' => intval( $taskRow['player_id'] ),
                'a4' => intval( $taskRow['village_id'] ),
                'a5' => QS_WAR_REINFORCE,
                'a6' => $newParams,
                'a7' => intval( $taskRow['id'] )
            ) );
            return TRUE;
        }
        $attackTroops = $this->_getAttackTroopsForVillage( $fromVillageRow, "", $procInfo['troopsArray']['troops'], 0, 0, 0, TRUE );
        $defenseTroops = array( );
        $totalDefensePower = 0;
        $troops_num = trim( $toVillageRow['troops_num'] );
        if ( $troops_num != "" )
        {
            $vtroopsArr = explode( "|", $troops_num );
            foreach ( $vtroopsArr as $vtroopsStr )
            {
                list( $tvid, $tvtroopsStr ) = explode( ":", $vtroopsStr );
                $incFactor = $toVillageRow['is_oasis'] && intval( $toVillageRow['player_id'] ) == 0 && $tvid == 0 - 1 ? floor( $toVillageRow['oasisElapsedTimeInSeconds'] / 86400 ) : 0;
                $_hasHero = FALSE;
                $vtroops = array( );
                $_arr = explode( ",", $tvtroopsStr );
                foreach ( $_arr as $_arrStr )
                {
                    list( $_tid, $_tnum ) = explode( " ", $_arrStr );
                    if ( $_tnum == 0 - 1 )
                    {
                        $_hasHero = TRUE;
                    }
                    else
                    {
                        $vtroops[$_tid] = $_tnum + $incFactor;
                    }
                }
                if ( $tvid == 0 - 1 )
                {
                    $hero_in_village_id = intval( db::get_field( "SELECT p.hero_in_village_id FROM p_players p WHERE p.id=:id", array(
                        ':id' => intval( $toVillageRow['player_id'] )
                    ) ) );
                    if ( 0 < $hero_in_village_id && $hero_in_village_id == $toVillageRow['id'] )
                    {
                        $_hasHero = TRUE;
                    }
                }
                $defenseTroops[$tvid] = $this->_getDefenseTroopsForVillage( $tvid == 0 - 1 ? $toVillageRow['id'] : $tvid, $vtroops, $_hasHero, 0, 0, TRUE );
                $totalDefensePower += $defenseTroops[$tvid]['total_power'];
            }
        }
        $warResult = $this->_getSpyResult( $attackTroops, $defenseTroops, $totalDefensePower );
        $reduceConsumption = $warResult['attackTroops']['total_dead_consumption'];
        if ( 0 < $reduceConsumption )
        {
            $this->_updateVillage( $fromVillageRow, $reduceConsumption, FALSE );
        }
        $defenseTroopsStr = "";
        $defenseReduceConsumption = 0;
        $reportTroopTable = array( );
        $tribeId = 0;
        foreach ( $warResult['defenseTroops'] as $vid => $troopsTable )
        {
            $defenseReduceConsumption += $troopsTable['total_dead_consumption'];
            $newTroops = "";
            $thisInforcementDied = TRUE;
            foreach ( $troopsTable['troops'] as $tid => $tprop )
            {
                if ( $newTroops != "" )
                {
                    $newTroops .= ",";
                }
                $newTroops .= $tid." ".$tprop['live_number'];
                if ( 0 < $tprop['live_number'] )
                {
                    $thisInforcementDied = FALSE;
                }
                $tribeId = $GLOBALS['GameMetadata']['troops'][$tid]['for_tribe_id'];
                if ( !isset( $reportTroopTable[$tribeId] ) )
                {
                    $reportTroopTable[$tribeId] = array(
                        "troops" => array( ),
                        "hero" => array( "number" => 0, "dead_number" => 0 )
                    );
                }
                if ( $tid != 99 )
                {
                    if ( !isset( $reportTroopTable[$tribeId]['troops'][$tid] ) )
                    {
                        $reportTroopTable[$tribeId]['troops'][$tid] = array(
                            "number" => $tprop['number'],
                            "dead_number" => $tprop['number'] - $tprop['live_number']
                        );
                    }
                    else
                    {
                        $reportTroopTable[$tribeId]['troops'][$tid]['number'] += $tprop['number'];
                        $reportTroopTable[$tribeId]['troops'][$tid]['dead_number'] += $tprop['number'] - $tprop['live_number'];
                    }
                }
            }
            if ( $troopsTable['hasHero'] )
            {
                ++$reportTroopTable[$tribeId]['hero']['number'];
                if ( $vid != 0 - 1 )
                {
                    if ( $newTroops != "" )
                    {
                        $newTroops .= ",";
                    }
                    $newTroops .= $troopsTable['heroTroopId']." -1";
                }
                $thisInforcementDied = FALSE;
            }
            $this->_updateVillageOutTroops( $vid, $toVillageRow['id'], $newTroops, $troopsTable['hasHero'] && $troopsTable['total_live_number'] <= 0, $thisInforcementDied, intval( $toVillageRow['player_id'] ) );
            if ( $vid == 0 - 1 && $toVillageRow['is_oasis'] )
            {
                db::query( "UPDATE p_villages v SET v.creation_date=NOW() WHERE v.id=:id", array(
                    'id' => intval( $toVillageRow['id'] )
                ) );
            }
            if ( !$thisInforcementDied || $vid == 0 - 1 )
            {
                if ( $defenseTroopsStr != "" )
                {
                    $defenseTroopsStr .= "|";
                }
                $defenseTroopsStr .= $vid.":".$newTroops;
            }
        }
        if ( $toVillageRow['is_oasis'] && 0 < intval( $toVillageRow['player_id'] ) && isset( $reportTroopTable[4] ) )
        {
            unset( $reportTroopTable[4] );
        }
        db::query( "UPDATE p_villages v SET v.troops_num=:num WHERE v.id=:id", array(
            'num' => $defenseTroopsStr,
            'id' => intval( $toVillageRow['id'] )
        ) );
        if ( !( $toVillageRow['is_oasis'] && intval( $toVillageRow['player_id'] ) == 0 ) )
        {
            $_tovid = $toVillageRow['is_oasis'] ? intval( $toVillageRow['parent_id'] ) : $toVillageRow['id'];
            db::query( "UPDATE p_villages v SET v.crop_consumption=v.crop_consumption-:crop WHERE v.id=:id", array(
                'crop' => $defenseReduceConsumption,
                'id' => intval( $_tovid )
            ) );
        }
        $newTroops = "";
        foreach ( $warResult['attackTroops']['troops'] as $tid => $tprop )
        {
            if ( $newTroops != "" )
            {
                $newTroops .= ",";
            }
            $newTroops .= $tid." ".$tprop['number']." ".( $tprop['number'] - $tprop['live_number'] );
        }
        if ( $procInfo['troopsArray']['hasHero'] )
        {
            if ( $newTroops != "" )
            {
                $newTroops .= ",";
            }
            $newTroops .= ( ( 0 - 1 )." ".( 1 ) )." ".( $warResult['all_attack_killed'] ? 1 : 0 );
        }
        $attackReportTroops = $newTroops;
        $defenseReportTroops = "";
        foreach ( $reportTroopTable as $tribeId => $defTroops )
        {
            $defenseReportTroops1 = "";
            foreach ( $defTroops['troops'] as $tid => $tArr )
            {
                if ( $defenseReportTroops1 != "" )
                {
                    $defenseReportTroops1 .= ",";
                }
                $defenseReportTroops1 .= $tid." ".$tArr['number']." ".$tArr['dead_number'];
            }
            if ( 0 < $defTroops['hero']['number'] )
            {
                if ( $defenseReportTroops1 != "" )
                {
                    $defenseReportTroops1 .= ",";
                }
                $defenseReportTroops1 .= ( 0 - 1 )." ".$defTroops['hero']['number']." ".$defTroops['hero']['dead_number'];
            }
            if ( $defenseReportTroops1 != "" )
            {
                if ( $defenseReportTroops != "" )
                {
                    $defenseReportTroops .= "#";
                }
                $defenseReportTroops .= $defenseReportTroops1;
            }
        }
        $harvestInfo = "";
        $harvestResources = "";
        $spyType = $procInfo['spyAction'];
        if ( !$warResult['all_spy_killed'] )
        {
            if ( $spyType == 1 )
            {
                $harvestResources = "0 0 0 0";
                if ( !$toVillageRow['is_oasis'] )
                {
                    $resources_info = array( );
                    $r_arr = explode( ",", $toVillageRow['resources'] );
                    foreach ( $r_arr as $r_str )
                    {
                        $r2 = explode( " ", $r_str );
                        $prate = floor( $r2[4] * ( 1 + $r2[5] / 100 ) ) - ( $r2[0] == 4 ? $toVillageRow['crop_consumption'] : 0 );
                        $current_value = floor( $r2[1] + $toVillageRow['elapsedTimeInSeconds'] * ( $prate / 3600 ) );
                        if ( $r2[2] < $current_value )
                        {
                            $current_value = $r2[2];
                        }
                        $resources_info[] = $current_value;
                    }
                    $harvestResources = implode( " ", $resources_info );
                }
            }
            if ( $spyType == 2 && !$toVillageRow['is_oasis'] )
            {
                $buildingsInfo = array( );
                $bStr = trim( $toVillageRow['buildings'] );
                if ( $bStr != "" )
                {
                    $bStrArr = explode( ",", $bStr );
                    $_i = 0;
                    foreach ( $bStrArr as $b2Str )
                    {
                        ++$_i;
                        if ( $_i < 19 )
                        {
                            continue;
                        }
                        list( $item_id, $level ) = explode( " ", $b2Str );
                        if ( 0 < $level )
                        {
                            $buildingsInfo[] = $item_id." ".$level;
                        }
                    }
                }
                if ( 0 < sizeof( $buildingsInfo ) )
                {
                    $_randIndex = mt_rand( 0, sizeof( $buildingsInfo ) - 1 );
                    $harvestInfo = $buildingsInfo[$_randIndex];
                }
            }
        }
        else
        {
            $spyType = 3;
        }
        $timeInSeconds = $taskRow['remainingTimeInSeconds'];
        if ( !$warResult['defense_has_spytroops'] )
        {
            $reportResult = 100;
        }
        else
        {
            $reportResult = $warResult['all_spy_killed'] ? 9 : 10;
        }
        $reportCategory = 4;
        $reportBody = $attackReportTroops."|".$defenseReportTroops."|".$harvestResources."|".$harvestInfo."|".$spyType;
        $this->load_model('Report', 'r');
        $reportId = $this->r->createReport( intval( $fromVillageRow['player_id'] ), intval( $toVillageRow['player_id'] ), intval( $fromVillageRow['id'] ), intval( $toVillageRow['id'] ), $reportCategory, $reportResult, $reportBody, $timeInSeconds );
        if ( !$warResult['defense_has_spytroops'] && $toVillageRow['player_id'] != $fromVillageRow['player_id'] )
        {
            $this->r->deleteReport( intval( $taskRow['to_player_id'] ), $reportId );
        }
        if ( !$warResult['all_attack_killed'] )
        {
            $paramsArray = explode( "|", $taskRow['proc_params'] );
            $paramsArray[sizeof( $paramsArray ) - 1] = 1;
            $newTroops = "";
            foreach ( $warResult['attackTroops']['troops'] as $tid => $tprop )
            {
                if ( $newTroops != "" )
                {
                    $newTroops .= ",";
                }
                $newTroops .= $tid." ".$tprop['live_number'];
            }
            if ( !$warResult['all_attack_killed'] && $procInfo['troopsArray']['hasHero'] )
            {
                if ( $newTroops != "" )
                {
                    $newTroops .= ",";
                }
                $newTroops .= $procInfo['troopsArray']['heroTroopId']." -1";
            }
            $paramsArray[0] = $newTroops;
            $newParams = implode( "|", $paramsArray );
            db::query( "UPDATE p_queue q  SET  q.player_id=:a1, q.village_id=:a2, q.to_player_id=:a3, q.to_village_id=:a4, q.proc_type=:a5, q.proc_params=:a6, q.end_date=(q.end_date + INTERVAL q.execution_time SECOND) WHERE q.id=:a7", array(
                'a1' => intval( $taskRow['to_player_id'] ),
                'a2' => intval( $taskRow['to_village_id'] ),
                'a3' => intval( $taskRow['player_id'] ),
                'a4' => intval( $taskRow['village_id'] ),
                'a5' => QS_WAR_REINFORCE,
                'a6' => $newParams,
                'a7' => intval( $taskRow['id'] )
            ) );
            return TRUE;
        }
        return FALSE;
    }

    public function _getSpyResult( $attackTroops, $defenseTroops, $totalDefensePower )
    {
        $warResult = array(
            "all_attack_killed" => FALSE,
            "all_spy_killed" => FALSE,
            "defense_has_spytroops" => FALSE
        );
        $attackFactor = 1;
        $totalAttackPower = $attackTroops['total_power'] * $attackFactor;
        $divisionFactor = 10;
        $i = 1;
        while ( $i <= $divisionFactor )
        {
            $curPower = floor( $totalDefensePower * $i / $divisionFactor );
            if ( 0 < $curPower )
            {
                foreach ( $attackTroops['troops'] as $tid => $tProp )
                {
                    if ( $warResult['all_attack_killed'] )
                    {
                        break;
                    }
                    if ( $tid == 99 || $tid != 103 && $tid != 54 && $tid != 4 && $tid != 14 && $tid != 23 )
                    {
                        continue;
                    }
                    $sPower = $tProp['single_power'] <= 0 ? 1 : $tProp['single_power'];
                    $deadNum = floor( $curPower / $sPower );
                    if ( $tProp['live_number'] < $deadNum )
                    {
                        $deadNum = $tProp['live_number'];
                    }
                    $curPower -= $deadNum * $tProp['single_power'];
                    $totalDefensePower -= $deadNum * $tProp['single_power'];
                    $attackTroops['total_power'] -= $deadNum * $tProp['single_power'];
                    $attackTroops['total_carry_load'] -= $deadNum * $tProp['single_carry_load'];
                    $attackTroops['total_dead_consumption'] += $deadNum * $tProp['single_consumption'];
                    $attackTroops['total_live_number'] -= $deadNum;
                    $attackTroops['total_spy_live_number'] -= $deadNum;
                    if ( $attackTroops['total_live_number'] <= 0 )
                    {
                        $warResult['all_attack_killed'] = TRUE;
                    }
                    if ( $attackTroops['total_spy_live_number'] <= 0 )
                    {
                        $warResult['all_spy_killed'] = TRUE;
                    }
                    $attackTroops['troops'][$tid]['live_number'] -= $deadNum;
                    if ( !( $curPower <= 0 ) )
                    {
                        continue;
                    }
                    break;
                    break;
                }
            }
            foreach ( $defenseTroops as $vid => $troopsTable )
            {
                if ( $defenseTroops[$vid]['total_power'] <= 0 )
                {
                    continue;
                }
                $curPower = floor( $totalAttackPower * $i / $divisionFactor );
                if ( 0 < $curPower )
                {
                    foreach ( $troopsTable['troops'] as $tid => $tProp )
                    {
                        if ( $tid == 99 || $tid != 103 && $tid != 54 && $tid != 4 && $tid != 14 && $tid != 23 )
                        {
                            continue;
                        }
                        if ( 0 < $tProp['live_number'] )
                        {
                            $warResult['defense_has_spytroops'] = TRUE;
                        }
                        $sPower = $tProp['single_power'] <= 0 ? 1 : $tProp['single_power'];
                        $deadNum = floor( $curPower / $sPower );
                        if ( $tProp['live_number'] < $deadNum )
                        {
                            $deadNum = $tProp['live_number'];
                        }
                        $curPower -= $deadNum * $tProp['single_power'];
                        $totalAttackPower -= $deadNum * $tProp['single_power'];
                        $defenseTroops[$vid]['total_power'] -= $deadNum * $tProp['single_power'];
                        $defenseTroops[$vid]['total_dead_consumption'] += $deadNum * $tProp['single_consumption'];
                        $defenseTroops[$vid]['total_live_number'] -= $deadNum;
                        $defenseTroops[$vid]['total_spy_live_number'] -= $deadNum;
                        $defenseTroops[$vid]['troops'][$tid]['live_number'] -= $deadNum;
                        if ( !( $curPower <= 0 ) )
                        {
                            continue;
                        }
                        break;
                        break;
                    }
                }
            }
            ++$i;
        }
        $warResult['attackTroops'] = $attackTroops;
        $warResult['defenseTroops'] = $defenseTroops;
        return $warResult;
    }

}

?>

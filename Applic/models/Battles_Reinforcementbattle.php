<?php
require_once MODELS_DIR.'Battle.php';
class Battles_Reinforcementbattle_Model extends Battle_Model
{

    public function handleReInforcement( $taskRow, $toVillageRow, $fromVillageRow, $procInfo, $troopsArrStr )
    {
        if ( $procInfo['troopBack'] )
        {
            if ( 0 < intval( $toVillageRow['player_id'] ) && $taskRow['to_player_id'] == intval( $toVillageRow['player_id'] ) )
            {
                $paramsArray = explode( "|", $taskRow['proc_params'] );
                $res = array( 0, 0, 0, 0 );
                if ( trim( $paramsArray[4] ) != "" )
                {
                    $res = explode( " ", $paramsArray[4] );
                }
                $k = 0;
                $r_arr = explode( ",", $toVillageRow['resources'] );
                foreach ( $r_arr as $r_str )
                {
                    $r2 = explode( " ", $r_str );
                    $resources[$r2[0]] = array(
                        "current_value" => $r2[2] < $r2[1] + $res[$k] ? $r2[2] : $r2[1] + $res[$k],
                        "store_max_limit" => $r2[2],
                        "store_init_limit" => $r2[3],
                        "prod_rate" => $r2[4],
                        "prod_rate_percentage" => $r2[5]
                    );
                    ++$k;
                }
                $resourcesStr = "";
                foreach ( $resources as $k => $v )
                {
                    if ( $resourcesStr != "" )
                    {
                        $resourcesStr .= ",";
                    }
                    $resourcesStr .= sprintf( "%s %s %s %s %s %s", $k, $v['current_value'], $v['store_max_limit'], $v['store_init_limit'], $v['prod_rate'], $v['prod_rate_percentage'] );
                }
				db::query("UPDATE p_villages SET troops_num=:num, resources=:res WHERE id=:id", array(
                    'num' => $this->_getNewTroops( $toVillageRow['troops_num'], $procInfo['troopsArray'], 0 - 1, $toVillageRow['player_id'] == $fromVillageRow['player_id'] && !$toVillageRow['is_oasis'] ),
					'res' => $resourcesStr,
                    'id' => intval($toVillageRow['id'])
                ));

                if ( $procInfo['troopsArray']['hasHero'] )
                {
					db::query("UPDATE p_players SET hero_in_village_id=:hid WHERE id=:id", array(
                        'hid' => intval($toVillageRow['id']),
                        'id' => intval($toVillageRow['player_id'])
                    ));
                }
            }
            else if ( $procInfo['troopsArray']['hasHero'] )
            {
				db::query("UPDATE p_players SET hero_in_village_id=NULL, hero_troop_id=NULL WHERE id=:id", array(
                        'id' => intval($toVillageRow['player_id'])
                    ));
            }
        }
        else
        {
            if ( 0 < intval( $toVillageRow['player_id'] ) )
            {
                $allegiance_percent = intval( $toVillageRow['allegiance_percent'] );
                if ( $toVillageRow['is_oasis'] && $allegiance_percent < 100 )
                {
                    $allegiance_percent += 15;
                    if ( 100 < $allegiance_percent )
                    {
                        $allegiance_percent = 100;
                    }
					db::query("UPDATE p_villages SET allegiance_percent=:all WHERE id=:id", array(
                        'all' => $allegiance_percent,
                        'id' => intval($toVillageRow['id'])
                    ));
                }
                $affectCropConsumption = TRUE;
                if ( $toVillageRow['is_oasis'] && trim( $fromVillageRow['village_oases_id'] ) != "" )
                {
                    $oArr = explode( ",", trim( $fromVillageRow['village_oases_id'] ) );
                    foreach ( $oArr as $oid )
                    {
                        if ( !( $oid == $taskRow['to_village_id'] ) )
                        {
                            continue;
                        }
                        $affectCropConsumption = FALSE;
                        break;
                        break;
                    }
                }
                $this->_addTroopsToVillage( $toVillageRow, $fromVillageRow, $procInfo['troopsArray'], $affectCropConsumption );
                if ( $procInfo['troopsArray']['hasHero'] && $toVillageRow['player_id'] == $fromVillageRow['player_id'] && !$toVillageRow['is_oasis'] )
                {
					db::query("UPDATE p_players SET hero_in_village_id=:hid WHERE id=:id", array(
                        'hid' => intval($toVillageRow['id']),
                        'id' => intval($toVillageRow['player_id'])
                    ));
                }
                $timeInSeconds = $taskRow['remainingTimeInSeconds'];
                $reportResult = 8;
                $reportCategory = 2;
                $troopsCropConsumption = $procInfo['troopsArray']['cropConsumption'];
                $reportBody = $troopsArrStr."|".$troopsCropConsumption;

                $this->load_model('Report', 'r');
                $this->r->createReport( intval( $taskRow['player_id'] ), intval( $taskRow['to_player_id'] ), intval( $taskRow['village_id'] ), intval( $taskRow['to_village_id'] ), $reportCategory, $reportResult, $reportBody, $timeInSeconds );

                return FALSE;
            }
            $paramsArray = explode( "|", $taskRow['proc_params'] );
            $paramsArray[sizeof( $paramsArray ) - 1] = 1;
            $newParams = implode( "|", $paramsArray );

            db::query( "UPDATE p_queue q SET  q.player_id=:pid, q.village_id=:vid, q.to_player_id=:tpid, q.to_village_id=:tvid, q.proc_type=:proc, q.proc_params=:params, q.end_date=(q.end_date + INTERVAL q.execution_time SECOND) WHERE q.id=:id", array(
               'pid' => intval( $taskRow['to_player_id'] ),
               'vid' => intval( $taskRow['to_village_id'] ),
               'tpid' => intval( $taskRow['player_id'] ),
               'tvid' => intval( $taskRow['village_id'] ),
               'proc' => QS_WAR_REINFORCE,
               'params' => $newParams,
               'id' => intval( $taskRow['id'] )
            ) );

            return TRUE;
        }
        return FALSE;
    }

    public function _addTroopsToVillage( $toVillageRow, $fromVillageRow, $addTroopsArray, $affectCropConsumption )
    {
        $troopsCropConsume = $affectCropConsumption ? $addTroopsArray['cropConsumption'] : 0;
        $isSamePlayer = $toVillageRow['player_id'] == $fromVillageRow['player_id'] && !$toVillageRow['is_oasis'];
        $fromVillageId = $isSamePlayer && !$addTroopsArray['hasMostwten'] && !$addTroopsArray['hasKing'] ? 0 - 1 : $fromVillageRow['id'];
        $t = $isSamePlayer && $addTroopsArray['onlyHero'] ? $toVillageRow['troops_num'] : $this->_getNewTroops( $toVillageRow['troops_num'], $addTroopsArray, $fromVillageId, $isSamePlayer );
        if ( !$toVillageRow['is_oasis'] )
        {
            $vrow = $this->_getVillageInfo($toVillageRow['id']);
            if (trim($vrow['resources']) != '')
            {
                $this->_updateVillage($vrow,0,FALSE);
            }
            db::query( "UPDATE p_villages v SET v.troops_num=:tr, v.crop_consumption=v.crop_consumption+:crop WHERE v.id=:id", array(
                'tr' => $t,
                'crop' => $troopsCropConsume,
                'id' => intval( $toVillageRow['id'] )
            ) );
        }
        else
        {
            $vrow = $this->_getVillageInfo($toVillageRow['parent_id']);
            if (trim($vrow['resources']) != '')
            {
                $this->_updateVillage($vrow,0,FALSE);
            }
            db::query( "UPDATE p_villages v SET v.crop_consumption=v.crop_consumption+:consumption WHERE v.id=:id", array(
                'consumption' => $troopsCropConsume,
                'id' => intval( $toVillageRow['parent_id'] )
            ) );
			db::query("UPDATE p_villages SET troops_num=:t WHERE id=:id", array(
                        't' => $t,
                        'id' => intval($toVillageRow['id'])
                    ));
        }
        $t = ($isSamePlayer && $addTroopsArray['onlyHero']) || ($isSamePlayer && !$addTroopsArray['hasMostwten'] && !$addTroopsArray['hasKing']) ? $fromVillageRow['troops_out_num'] : $this->_getNewTroops( $fromVillageRow['troops_out_num'], $addTroopsArray, $toVillageRow['id'], $isSamePlayer);
        $FVrow = $this->_getVillageInfo($fromVillageRow['id']);
        if (trim($FVrow['resources']) != '')
        {
            $this->_updateVillage($FVrow,0,FALSE);
        }
        db::query( "UPDATE p_villages v  SET v.troops_out_num=:tr, v.crop_consumption=v.crop_consumption-:crop WHERE v.id=:id", array(
            'tr' => $t,
            'crop' => $troopsCropConsume,
            'id' => intval( $fromVillageRow['id'] )
        ) );
    }

}

?>

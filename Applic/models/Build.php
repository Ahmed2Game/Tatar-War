<?php

class Build_Model extends Model
{

    public function getVillageOases( $villageOasesid )
    {
        if ( $villageOasesid == "" )
        {
            return NULL;
        }
        return db::get_all("SELECT v.id, v.rel_x, v.rel_y, v.image_num, v.allegiance_percent, v.troops_num FROM p_villages v WHERE v.id IN ($villageOasesid)"
        );
    }

	public function getPlayerVillagesDataPyId ($playerId)
	{
	    return db::get_all("SELECT id, child_villages_id FROM p_villages WHERE player_id=:playerId ", array(
		  'playerId' => $playerId
        ) );
    }

    public function getChildVillagesFor( $villageIds )
    {
        if ( $villageIds == "" )
        {
            return NULL;
        }
        return db::get_all( "SELECT v.id,v.village_name,v.people_count,v.rel_x, v.rel_y,DATE_FORMAT(v.creation_date, '%Y/%m/%d') creation_date FROM p_villages v WHERE v.id IN ($villageIds)");
    }

    public function getVillagesCp( $villages_id )
    {
        return db::get_all("SELECT v.cp,TIME_TO_SEC(TIMEDIFF(NOW(), v.last_update_date)) elapsedTimeInSeconds FROM p_villages v WHERE v.id IN ($villages_id)");
    }

    public function getVillageDataByName( $villagesName )
    {
        return db::get_row("SELECT v.id, v.rel_x, v.rel_y, v.village_name, v.player_id, v.player_name FROM p_villages v WHERE v.is_oasis=0 AND v.village_name=:villagesName", array(
            'villagesName' => $villagesName
        ) );
    }

    public function getVillageDataById( $villagesId )
    {
        return db::get_row("SELECT v.id, v.rel_x, v.is_capital, v.rel_y, v.village_name, v.player_id, v.player_name FROM p_villages v WHERE v.id=:villagesId AND NOT ISNULL(v.player_id) AND v.is_oasis=0", array(
            'villagesId' => $villagesId
        ) );
    }

    public function getVillageName( $villageId )
    {
        return db::get_field( "SELECT v.village_name FROM p_villages v WHERE v.id=:villageId", array(
            'villageId' => $villageId
        ) );
    }

    public function getPlayerName( $playerId )
    {
        return db::get_field( "SELECT p.name FROM p_players p WHERE p.id=:playerId", array(
            'playerId' => $playerId
        ) );
    }

    public function getPlayType( $player_id )
    {
        return db::get_field( "SELECT p.player_type FROM p_players p WHERE p.id=:player_id", array(
            'player_id' => $player_id
        ) );
    }

    public function getPlayerAllianceId( $playerId )
    {
        return db::get_field("SELECT p.alliance_id FROM p_players p WHERE p.id=:playerId", array(
            'playerId'=>$playerId
        ) );
    }

	public function getPlayerIp( $playerId )
    {
        return db::get_field( "SELECT p.ip_his FROM p_players p WHERE p.id=:playerId", array(
            'playerId'=>$playerId
        ) );
    }

	public function UpdatePlayerPainTime( $playerId, $time, $reason )
    {
	    db::query("UPDATE p_players p SET p.blocked_time=:time, p.blocked_reason=:reason WHERE p.id=:playerId", array(
		      'time'      =>  $time,
		      'reason'    =>  $reason,
		      'playerId'  =>  $playerId
		) );
	}

    public function getOffers( $villageId )
    {
        return db::get_all( "SELECT m.* FROM p_merchants m WHERE m.village_id=:villageId ORDER BY m.id ASC", array(
            'villageId' => $villageId
        ) );
    }

    public function getAllOffersCount( $villageId, $x, $y, $radius, $speed )
    {
        $angle = $radius / 180;
        $x /= $angle;
        $y /= $angle;
        return db::get_field("SELECT COUNT(*) FROM p_merchants m WHERE m.village_id!=:villageId AND IF(m.max_time>0, ((ACOS(SIN(:x * PI() / 180) * SIN(m.village_x/:angle * PI() / 180) + COS(:x * PI() / 180) * COS(m.village_x/:angle * PI() / 180) * COS((:y - m.village_y/:angle) * PI() / 180)) * 180 / PI()) * :angle)/:speed*3600<=m.max_time*3600,1)", array(
            'villageId' => $villageId,
            'x'         => $x,
            'angle'     => $angle,
            'y'         => $y,
            'speed'     => $speed
        ) );
    }

    public function getAllOffers( $villageId, $x, $y, $radius, $speed, $pageIndex, $pageSize )
    {
        $angle = $radius / 180;
        $x /= $angle;
        $y /= $angle;

        $pageindexsize = $pageIndex * $pageSize;
        return db::get_all("SELECT m.*,((ACOS(SIN(:x * PI() / 180) * SIN(m.village_x/:angle * PI() / 180) + COS(:x * PI() / 180) * COS(m.village_x/:angle * PI() / 180) * COS((:y - m.village_y/:angle) * PI() / 180)) * 180 / PI()) * :angle)/m.merchants_speed*3600  timeInSeconds FROM p_merchants m HAVING m.village_id!=:villageId AND IF(m.max_time>0, timeInSeconds*m.merchants_speed/:speed<=m.max_time*3600,1)ORDER BY timeInSeconds ASC LIMIT $pageindexsize,$pageSize", array(
            'x'             => $x,
            'angle'         => $angle,
            'y'             => $y,
            'villageId'     => $villageId,
            'speed'         => $speed,
        ) );
    }

    public function getOffer( $offerId, $playerId, $villageId )
    {
        return db::get_row("SELECT m.* FROM p_merchants m WHERE id=:offerId AND player_id=:playerId AND village_id=:villageId", array(
            'offerId'   => $offerId,
            'playerId'  => $playerId,
            'villageId' => $villageId
        ) );
    }


    public function getOffer2( $offerId, $x, $y, $radius )
    {
        $angle = $radius / 180;
        $x /= $angle;
        $y /= $angle;
        return db::get_row( "SELECT m.*,((ACOS(SIN(:x * PI() / 180) * SIN(m.village_x/:angle * PI() / 180) + COS(:x * PI() / 180) * COS(m.village_x/:angle * PI() / 180) * COS((:y - m.village_y/:angle) * PI() / 180)) * 180 / PI()) * :angle)/m.merchants_speed*3600  timeInSeconds FROM p_merchants m WHERE id=:offerId", array(
            'x'         => $x,
            'angle'     => $angle,
            'y'         => $y,
            'offerId'   => $offerId
        ) );
    }


    public function removeMerchantOffer( $offerId, $playerId, $villageId )
    {
        $merchants_num = intval( db::get_field("SELECT merchants_num FROM p_merchants WHERE id=:offerId", array(
            'offerId' => intval( $offerId )
        ) ) );
        if ( $merchants_num <= 0 )
        {
            return;
        }

        db::query("UPDATE p_villages v SET v.offer_merchants_count=IF(v.offer_merchants_count-:merchants_num<0, 0, v.offer_merchants_count-:merchants_num) WHERE v.id=:villageId", array(
            'merchants_num' => $merchants_num,
            'villageId' => $villageId
        ) );
        db::query(" DELETE FROM p_merchants WHERE id=:offerId AND player_id=:playerId AND village_id=:villageId", array(
            'offerId' => intval( $offerId ),
            'playerId' => $playerId,
            'villageId' => $villageId
        ) );
    }

    public function addMerchantOffer( $playerId, $playerName, $villageId, $x, $y, $merchantNum, $offer, $allianceOnly, $maxTime, $merchantsSpeed )
    {
        db::query("INSERT INTO p_merchants SET player_id=:playerId,player_name=:playerName,village_id=:villageId,
            village_x=:x,village_y=:y,offer=:offer,alliance_only=:allianceOnly,max_time=:maxTime,
            merchants_num=:merchantNum,merchants_speed=:merchantsSpeed", array(
                'playerId' => $playerId,
                'playerName' =>$playerName,
                'villageId' => $villageId,
                'x' =>$x,
                'y' => $y,
                'offer' => $offer,
                'allianceOnly' => $allianceOnly ? 1 : 0,
                'maxTime' => $maxTime,
                'merchantNum' => $merchantNum,
                'merchantsSpeed' => $merchantsSpeed
        ) );

        db::query("UPDATE p_villages v SET v.offer_merchants_count=v.offer_merchants_count+:merchantNum WHERE v.id=:villageId", array(
            'merchantNum' => $merchantNum,
            'villageId' => $villageId
        ) );
    }


    public function makeVillageAsCapital( $playerId, $villageId )
    {
        $this->load_model('Queuejob', 'qj');
        $capitalRow = db::get_row("SELECT v.id, v.buildings FROM p_villages v WHERE  v.player_id=:playerid AND v.is_capital=1", array(
            'playerid' => $playerId
        ) );
        $buildingArr = explode(",", $capitalRow['buildings'] );
        $c = 0;
        foreach ( $buildingArr as $buildingItem )
        {
            ++$c;
            list($item_id, $level , $update_state) = explode( " ",$buildingItem );
            if ( $item_id == 0 )
			{
				continue;
			}
            $max_lvl_in_non_capital = $GLOBALS['GameMetadata']['items'][$item_id]['max_lvl_in_non_capital'];
            if ( $max_lvl_in_non_capital == NULL || $level + $update_state <= $max_lvl_in_non_capital )
            {
                continue;
            }
            $dropLevels = $level + $update_state - $max_lvl_in_non_capital;
            while ( 0 < $dropLevels-- )
            {
                $this->qj->upgradeBuilding( $capitalRow['id'], $c, $item_id, TRUE );
            }
        }

        db::query("UPDATE p_villages v SET v.is_capital=0 WHERE v.player_id=:playerid", array(
            'playerid' => $playerId
        ) );
        db::query( "UPDATE p_villages v SET v.is_capital=1 WHERE v.id=:villageId AND v.player_id=:playerId", array(
            'villageId' => $villageId,
            'playerId'  => $playerId
        ) );
    }

    public function changeHeroName( $playerId, $heroName )
    {
        db::query("UPDATE p_players p SET p.hero_name=:heroName WHERE p.id=:playerId", array(
            'heroName' => $heroName,
            'playerId' => $playerId
        ) );
    }

	public function changeHeroVillage( $playerId, $village_id )
    {
        db::query("UPDATE p_players p SET p.hero_in_village_id=:village_id WHERE p.id=:playerId", array(
            'village_id' => $village_id,
            'playerId' => $playerId
        ) );
    }

    public function decreaseGoldNum( $playerId, $goldCost )
    {
        db2::query("UPDATE p_players p SET p.gold_num=p.gold_num-:goldCost WHERE p.id=:playerId", array(
            'goldCost' => $goldCost,
            'playerId' => $playerId
        ) );
    }

    public function allianceExists( $allianceName )
    {
        return 0 < intval( db::get_field("SELECT a.id FROM p_alliances a WHERE a.name=:allianceName", array(
            'allianceName' => $allianceName
        ) ) );
    }

    public function createAlliance( $playerId, $allianceName, $allianceName2, $maxPlayer )
    {
        $allianceRoles = ( ALLIANCE_ROLE_SETROLES | ALLIANCE_ROLE_REMOVEPLAYER | ALLIANCE_ROLE_EDITNAMES | ALLIANCE_ROLE_EDITCONTRACTS | ALLIANCE_ROLE_SENDMESSAGE | ALLIANCE_ROLE_INVITEPLAYERS )." ".alliance_creator;
        db::query( "INSERT INTO p_alliances SET
            name=:allianceName, name2=:allianceName2, creator_player_id=:playerId, rating=0, creation_date=NOW(),player_count=1,
            max_player_count=:maxPlayer,players_ids=:playerId",
            array(
                    'allianceName' => $allianceName,
                    'allianceName2' => $allianceName2,
                    'playerId' => $playerId,
                    'maxPlayer' => $maxPlayer
                )
        );

        $aid = db::get_field( "SELECT LAST_INSERT_ID() FROM p_alliances" );
        db::query("UPDATE p_players p SET p.alliance_id=:aid, p.alliance_name=:allianceName, p.alliance_roles=:allianceRoles WHERE p.id=:playerId", array(
                'aid'           => $aid,
                'allianceName'  => $allianceName,
                'allianceRoles' => $allianceRoles,
                'playerId'      => $playerId
        ) );

        db::query("UPDATE p_villages v SET v.alliance_id=:aid, v.alliance_name=:allianceName WHERE v.player_id=:playerId", array(
            'aid'           => $aid,
            'allianceName'  => $allianceName,
            'playerId'      => $playerId
        ) );
        return $aid;
    }

    public function acceptAllianceJoining( $playerId, $allianceId )
    {
        $row = db::get_row( "SELECT a.* FROM p_alliances a WHERE a.id=:allianceId", array(
            'allianceId' => $allianceId
        ) );

        if (!$row) // no row found (pdo)
        {
            return 0;
        }
        if ( $row['max_player_count'] <= $row['player_count'] )
        {
            return 1;
        }
        $allianceName = $row['name'];
        $players_ids = $row['players_ids'];
        if ( $players_ids != "" )
        {
            $players_ids .= ",";
        }
        $players_ids .= $playerId;

        db::query("UPDATE p_alliances a SET a.player_count=a.player_count+1, a.players_ids=:players_ids WHERE a.id=:allianceId", array(
            'players_ids'   => $players_ids,
            'allianceId'    => $allianceId
        ) );
        db::query( "UPDATE p_players p SET p.alliance_id=:allianceId, p.alliance_name=:allianceName WHERE p.id=:playerId", array(
            'allianceId'   => $allianceId,
            'allianceName' => $allianceName,
            'playerId'     => $playerId
        ) );
        db::query( "UPDATE p_villages v SET v.alliance_id=:allianceId, v.alliance_name=:allianceName WHERE v.player_id=:playerId", array(
            'allianceId' => $allianceId,
            'allianceName' => $allianceName,
            'playerId' => $playerId
        ) );
        return 2;
    }


    public function _getNewInvite( $invitesString, $removeId )
    {
        if ( $invitesString == "" )
        {
            return "";
        }
        $result = "";
        $arr = explode("\n", $invitesString );
        foreach ( $arr as $invite )
        {

            list($id, $name) = explode( " ", $invite, 2 );
            if ( $id == $removeId )

            {
                continue;
            }
            if ( $result != "" )
            {
                $result .= "\n";
            }
            $result .= $id." ".$name;
        }
        return $result;
    }

    public function removeAllianceInvites( $playerId, $allianceId )
    {
        $pRow = db::get_row( "SELECT p.name, p.invites_alliance_ids FROM p_players p WHERE p.id=:playerId", array(
            'playerId' => $playerId
        ) );
        $aRow = db::get_row( "SELECT a.name, a.invites_player_ids FROM p_alliances a WHERE a.id=:allianceId", array(
            'allianceId' => $allianceId
        ) );
        $pInvitesStr = $this->_getNewInvite( trim( $pRow['invites_alliance_ids'] ), $allianceId );
        $aInvitesStr = $this->_getNewInvite( trim( $aRow['invites_player_ids'] ), $playerId );

        db::query("UPDATE p_players p SET p.invites_alliance_ids=:pInvitesStr WHERE p.id=:playerId", array(
            'pInvitesStr' => $pInvitesStr,
            'playerId' => $playerId
        ) );

        db::query("UPDATE p_alliances a SET a.invites_player_ids=:aInvitesStr WHERE a.id=:allianceId", array(
            'aInvitesStr' => $aInvitesStr,
            'allianceId' => $allianceId
        ) );
    }

    public function getVillageData2ById( $villageId )
    {
        return db::get_row( "SELECT v.id, v.tribe_id, v.is_oasis, v.village_name, v.player_id, v.player_name FROM p_villages v WHERE v.id=:villageId", array(
            'villageId' => $villageId
        ) );
    }

    public function getOasesDataById( $villagesId )
    {
        return db::get_all( "SELECT v.id, v.tribe_id, v.rel_x, v.rel_y, v.troops_num, v.player_id, v.player_name FROM p_villages v WHERE v.id IN($villagesId)");
    }

}

?>
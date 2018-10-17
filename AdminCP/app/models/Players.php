<?php
class Players_Model extends Model
{
    public function GetPlayerDataByName($playerName)
    {
        return db::get_all("SELECT p.id, p.name, p.alliance_id, p.alliance_name FROM p_players p WHERE p.name=:playername", array(
            'playername' => $playerName
        ));
    }

    public function GetPlayerDataById($playerID)
    {
        return db::get_row("SELECT * FROM p_players  WHERE id=:playerid", array(
            'playerid' => $playerID
        ));
    }
    public function GetMplayerData($playerID)
    {
        return db2::get_row("SELECT * FROM p_players  WHERE id=:playerid", array(
            'playerid' => $playerID
        ));
    }

    public function GetPlayerPwd($playerID)
    {
        return db2::get_field("SELECT p.pwd FROM p_players p WHERE p.id=:playerid", array(
            'playerid' => $playerID
        ));
    }

    public function GetPlayerDataByIB($playerIB)
    {
        return db::get_all("SELECT p.id, p.name, p.alliance_id, p.alliance_name FROM p_players p WHERE p.ip_his LIKE '%$playerIB%'");
    }

    public function GetPlayerDataByAlliance($allianceName)
    {
        return db::get_all("SELECT p.id, p.name, p.alliance_id, p.alliance_name FROM p_players p WHERE p.alliance_name=:all",array(
            'all' => $allianceName
        ));
    }

    public function GetPlayerDataByType($type)
    {
        return db::get_all("SELECT p.id, p.name, p.alliance_id, p.alliance_name FROM p_players p WHERE p.player_type=:pty",array(
            'pty' => $type
        ));
    }

    public function GetPlayerDataByGold($gold)
    {
        return db2::get_all("SELECT p.id, p.name, p.gold_num FROM p_players p WHERE p.gold_num>:gold",array(
            'gold' => $gold
        ));
    }
    public function GetMPlayerDataByName($playerName)
    {
        return db2::get_all("SELECT p.id, p.name, p.gold_buy FROM p_players p WHERE p.name=:playername", array(
            'playername' => $playerName
        ));
    }

    public function UpdatePlayerData($playerId, $tribe_id, $alliance_id, $alliance_name, $alliance_roles, $name, $is_blocked, $player_type, $active_plus_account, $ip_his, $total_people_count, $villages_count, $villages_id, $hero_troop_id, $hero_level, $hero_points, $hero_name, $hero_in_village_id, $attack_points, $defense_points, $week_attack_points, $week_defense_points, $week_dev_points, $week_thief_points)
    {
        db::query("UPDATE p_players p SET
                    p.id=:playerid, p.tribe_id=:tribe_id, p.alliance_id=:alliance_id,
                    p.alliance_name=:alliance_name, p.alliance_roles=:alliance_roles, p.name=:name,
                    p.is_blocked=:is_blocked, p.player_type=:player_type, p.active_plus_account=:active_plus_account,
                    p.ip_his=:ip_his, 
                    p.total_people_count=:total_people_count, p.villages_count=:villages_count, p.villages_id=:villages_id,
                    p.hero_troop_id=:hero_troop_id, p.hero_level=:hero_level, p.hero_points=:hero_points,
                    p.hero_name=:hero_name, p.hero_in_village_id=:hero_in_village_id, p.attack_points=:attack_points,
                    p.defense_points=:defense_points, p.week_attack_points=:week_attack_points,
                    p.week_defense_points=:week_defense_points, p.week_dev_points=:week_dev_points,
                    p.week_thief_points=:week_thief_points
                    WHERE p.id=:id", array(
            'playerid' => $playerId,
            'tribe_id' => $tribe_id,
            'alliance_id' => $alliance_id,
            'alliance_name' => $alliance_name,
            'alliance_roles' => $alliance_roles,
            'name' => $name,
            'is_blocked' => $is_blocked,
            'player_type' => $player_type,
            'active_plus_account' => $active_plus_account,
            'ip_his' => $ip_his,
            'total_people_count' => $total_people_count,
            'villages_count' => $villages_count,
            'villages_id' => $villages_id,
            'hero_troop_id' => $hero_troop_id,
            'hero_level' => $hero_level,
            'hero_points' => $hero_points,
            'hero_name' => $hero_name,
            'hero_in_village_id' => $hero_in_village_id,
            'attack_points' => $attack_points,
            'defense_points' => $defense_points,
            'week_attack_points' => $week_attack_points,
            'week_defense_points' => $week_defense_points,
            'week_dev_points' => $week_dev_points,
            'week_thief_points' => $week_thief_points,
            'id' => $playerId
        ));
        
    }
    public function UpdateMplayer($Id,$name,$pwd,$email,$is_active,$invite_by,$house_name,$gold_num)
    {
        db2::query("UPDATE p_players p SET p.name=:name, p.pwd=:pwd, p.email=:email, p.is_active=:is_active, p.invite_by=:invite_by, p.house_name=:house_name, p.gold_num=:gold_num WHERE p.id=:id", array(
            'id' => $Id,
            'name' => $name,
            'pwd' => $pwd,
            'email' => $email,
            'is_active' => $is_active,
            'invite_by' => $invite_by,
            'house_name' => $house_name,
            'gold_num' => $gold_num
        ));
    }
    public function deletePlayer($playerId)
    {
        $playerId = intval($playerId);
        if ($playerId <= 0)
        {
            return;
        }
        $row = db::get_row("SELECT p.alliance_id, p.villages_id, p.tribe_id, p.is_active FROM p_players p WHERE id=:id", array(
            'id' => $playerId
        ));
        if ($row == NULL)
        {
            return;
        }
        db::query("UPDATE p_msgs m SET m.to_player_id=IF(m.to_player_id=:id, NULL, m.to_player_id), m.from_player_id=IF(m.from_player_id=:id, NULL, m.from_player_id)", array(
            'id' => $playerId
        ));
        db::query("UPDATE p_rpts r SET r.to_player_id=IF(r.to_player_id=:id, NULL, r.to_player_id), r.from_player_id=IF(r.from_player_id=:id, NULL, r.from_player_id)", array(
            'id' => $playerId
        ));
        if (0 < intval($row['alliance_id']))
        {
            db::query("UPDATE p_alliances SET player_count=player_count-1 WHERE id=:id", array(
                'id' => intval($row['alliance_id'])
            ));
            $_aRow = db::get_row("SELECT a.players_ids, a.player_count FROM p_alliances a WHERE a.id=:id", array(
                'id' => intval($row['alliance_id'])
            ));
            if ($_aRow['player_count'] <= 0)
            {
                db::query("DELETE FROM p_alliances WHERE id=:id", array(
                    'id' => intval($row['alliance_id'])
                ));
            }
            else
            {
                $aplayers_ids = $_aRow['players_ids'];
                if (trim($aplayers_ids) != "")
                {
                    $newPlayers_ids  = "";
                    $aplayers_idsArr = explode(",", $aplayers_ids);
                    foreach ($aplayers_idsArr as $pid)
                    {
                        if ($pid == $playerId)
                        {
                            continue;
                        }
                        if ($newPlayers_ids != "")
                        {
                            $newPlayers_ids .= ",";
                        }
                        $newPlayers_ids .= $pid;
                    }
                    db::query("UPDATE p_alliances SET players_ids=:ids WHERE id=:id", array(
                        'ids' => $newPlayers_ids,
                        'id' => intval($row['alliance_id'])
                    ));
                }
            }
        }
        db::query("DELETE FROM p_merchants WHERE player_id=:id", array(
            'id' => $playerId
        ));
        db::query("UPDATE p_villages v  SET  v.tribe_id=IF(v.is_oasis=1, 4, 0), v.parent_id=NULL, v.player_id=NULL, v.alliance_id=NULL, v.player_name=NULL, v.village_name=NULL, v.alliance_name=NULL, v.is_capital=0, v.people_count=2, v.crop_consumption=2, v.time_consume_percent=100, v.offer_merchants_count=0, v.resources=NULL, v.cp=NULL, v.buildings=NULL, v.troops_training=NULL, v.child_villages_id=NULL, v.village_oases_id=NULL, v.troops_trapped_num=0, v.allegiance_percent=100, v.troops_num=IF(v.is_oasis=1, '-1:31 0,34 0,37 0', NULL), v.troops_out_num=NULL, v.troops_intrap_num=NULL, v.troops_out_intrap_num=NULL, v.creation_date=NOW() WHERE v.player_id=:id", array(
            'id' => $playerId
        ));
        db::query("DELETE FROM p_players WHERE id=:id", array(
            'id' => $playerId
        ));
        db::query("UPDATE g_summary  SET  players_count=players_count-1, active_players_count=active_players_count-:a, Arab_players_count=Arab_players_count-:b, Roman_players_count=Roman_players_count-:r, Teutonic_players_count=Teutonic_players_count-:t, Gallic_players_count=Gallic_players_count-:g", array(
            'a' => $row['is_active'] ? 1 : 0,
            'b' => $row['tribe_id'] == 7 ? 1 : 0,
            'r' => $row['tribe_id'] == 1 ? 1 : 0,
            't' => $row['tribe_id'] == 2 ? 1 : 0,
            'g' => $row['tribe_id'] == 3 ? 1 : 0
        ));
    }

    public function GivePlayerGold( $name, $goldNumber )
    {
        db2::query("INSERT INTO gold_trans (from_player, to_player, trans_date,gold)
                    VALUES (:from_player,:to_player, NOW(), :goldNumber)",
                    array(
                        'from_player' => 'الادارة',
                        'to_player'   => $name,
                        'goldNumber'  => $goldNumber
                ));
        return db2::count("UPDATE p_players p SET p.gold_num=p.gold_num+:goldNumber, p.gold_buy=p.gold_buy+:goldNumber WHERE p.name=:name", array(
            'goldNumber' => $goldNumber,
            'name'   => $name
        ) );
    }

    public function AddEmail($email)
    {
        return db2::count("INSERT INTO email SET uemail=:email", array(
            'email' => $email
        ));
    }

    public function DeleteEmail($email)
    {
        db2::query("DELETE FROM email WHERE uemail=:email", array(
            'email' => $email
        ));
    }

    public function GetAllEmail()
    {
        return db2::get_all("SELECT uemail FROM email");
    }
	
	public function GetPlayerDataByName2($playerName)
    {
        return db2::get_row("SELECT p.id, p.email, p.activation_code FROM p_players p WHERE p.name=:playername", array(
            'playername' => $playerName
        ));
    }
}

?>

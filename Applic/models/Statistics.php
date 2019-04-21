<?php

class Statistics_Model extends Model
{
    public function tatarRaised()
    {
        return db::get_field("SELECT COUNT(*) FROM p_queue q WHERE q.proc_type=:ty", array(
                'ty' => QS_TATAR_RAISE
            )) == 0;
    }

    public function getTatarVillagesList()
    {
        return db::get_all("SELECT  v.id, v.player_id, v.alliance_id, v.player_name, v.village_name, v.alliance_name, v.buildings FROM p_villages v WHERE  v.is_capital=0 AND v.is_special_village=1 ORDER BY v.id ASC");
    }

    public function getPlayerListCount($tribeId)
    {
        return $tribeId == 0 ? db::get_field("SELECT COUNT(*)  FROM p_players p WHERE p.player_type=:ty", array(
            'ty' => PLAYERTYPE_NORMAL
        )) : db::get_field("SELECT COUNT(*)  FROM p_players p WHERE p.player_type=:ty AND p.tribe_id=:id", array(
            'ty' => PLAYERTYPE_NORMAL,
            'id' => $tribeId
        ));
    }

    public function getPlayerList($pageIndex, $pageSize, $tribeId)
    {
        $of = $pageIndex * $pageSize;
        return $tribeId == 0 ? db::get_all("SELECT  p.id, p.player_type, p.is_blocked, p.name, p.alliance_id, p.alliance_name, p.total_people_count, p.villages_count FROM p_players p WHERE  p.player_type=:ty ORDER BY (p.total_people_count*10+p.villages_count) DESC, p.id ASC LIMIT $of,$pageSize", array(
            'ty' => PLAYERTYPE_NORMAL
        )) : db::get_all("SELECT  p.id, p.player_type, p.is_blocked, p.name, p.alliance_id, p.alliance_name, p.total_people_count, p.villages_count FROM p_players p WHERE  p.player_type=:ty AND p.tribe_id=:tid ORDER BY (p.total_people_count*10+p.villages_count) DESC, p.id ASC LIMIT $of,$pageSize", array(
            'ty' => PLAYERTYPE_NORMAL,
            'tid' => $tribeId
        ));
    }

    public function getPlayerRankById($playerId, $tribeId)
    {
        $row = db::get_row("SELECT  p.id, (p.total_people_count*10+p.villages_count) score FROM p_players p WHERE  p.id=:id AND p.player_type=:ty LIMIT 1", array(
            'id' => $playerId,
            'ty' => PLAYERTYPE_NORMAL
        ));
        return $this->getPlayerRank($row['id'], $row['score'], $tribeId);
    }

    public function getPlayerRankByName($playerName, $tribeId)
    {
        $row = db::get_row("SELECT  p.id, (p.total_people_count*10+p.villages_count) score FROM p_players p WHERE  p.player_type=:ty AND p.name LIKE '$playerName%' LIMIT 1", array(
            'ty' => PLAYERTYPE_NORMAL
        ));
        return $this->getPlayerRank($row['id'], $row['score'], $tribeId);
    }

    public function getPlayerRank($playerId, $score, $tribeId)
    {
        $score = intval($score);
        $playerId = intval($playerId);
        $tribeId = intval($tribeId);
        return $tribeId == 0 ? db::get_field("SELECT ( (SELECT COUNT(*) FROM p_players p WHERE p.player_type=:ty AND (p.total_people_count*10+p.villages_count)>:sc)  + (SELECT  COUNT(*) FROM p_players p WHERE p.player_type=:ty  AND p.id<:id  AND (p.total_people_count*10+p.villages_count)=:sc) ) + 1 rank", array(
            'ty' => PLAYERTYPE_NORMAL,
            'sc' => $score,
            'id' => $playerId
        )) : db::get_field("SELECT ( (SELECT COUNT(*) FROM p_players p WHERE p.player_type=:ty AND (p.total_people_count*10+p.villages_count)>:sc AND p.tribe_id=:tid)  + (SELECT  COUNT(*) FROM p_players p WHERE p.player_type=:ty  AND p.id<:id  AND (p.total_people_count*10+p.villages_count)=:sc AND p.tribe_id=:tid) ) + 1 rank", array(
            'ty' => PLAYERTYPE_NORMAL,
            'sc' => $score,
            'tid' => $tribeId,
            'id' => $playerId
        ));
    }

    public function getVillageListCount()
    {
        return db::get_field("SELECT COUNT(*)  FROM p_villages v WHERE NOT ISNULL(v.player_id) AND v.is_oasis=0");
    }

    public function getVillagesList($pageIndex, $pageSize)
    {
        $of = $pageIndex * $pageSize;
        return db::get_all("SELECT  v.id, v.player_id, v.village_name, v.player_name, v.people_count, v.rel_x, v.rel_y FROM p_villages v WHERE NOT ISNULL(v.player_id) AND v.is_oasis=0 ORDER BY v.people_count DESC, v.id DESC LIMIT $of,$pageSize");
    }

    public function getVillageRankByName($villageName)
    {
        $row = db::get_row("SELECT  v.id, (v.people_count) score FROM p_villages v WHERE  NOT ISNULL(v.player_id) AND v.is_oasis=0 AND v.village_name LIKE '$villageName%' LIMIT 1");
        return $this->getVillageRank($row['id'], $row['score']);
    }

    public function getVillageRankById($villageId)
    {
        $row = db::get_row("SELECT  v.id, (v.people_count) score FROM p_villages v WHERE  v.id=:id AND NOT ISNULL(v.player_id) AND v.is_oasis=0 LIMIT 1", array(
            'id' => $villageId
        ));
        return $this->getVillageRank($row['id'], $row['score']);
    }

    public function getVillageRank($villageId, $score)
    {
        $score = intval($score);
        $villageId = intval($villageId);
        return db::get_field("SELECT ( (SELECT COUNT(*) FROM p_villages v WHERE  NOT ISNULL(v.player_id) AND v.is_oasis=0 AND v.people_count>:sc) + (SELECT  COUNT(*) FROM p_villages v WHERE  NOT ISNULL(v.player_id) AND v.is_oasis=0 AND v.people_count=:sc AND v.id>:id) ) + 1 rank", array(
            'sc' => $score,
            'id' => $villageId
        ));
    }

    public function getAllianceListCount()
    {
        return db::get_field("SELECT COUNT(*)  FROM p_alliances a");
    }

    public function getAlliancesList($pageIndex, $pageSize)
    {
        $of = $pageIndex * $pageSize;
        return db::get_all("SELECT a.id, a.name, a.player_count, a.rating, SUM(p.total_people_count) as `points`, AVG(p.total_people_count) AS `average`  FROM p_alliances a  INNER JOIN p_players p ON (p.alliance_id = a.id)  GROUP BY a.id  ORDER BY points DESC, a.player_count DESC, a.id ASC  LIMIT $of,$pageSize");
    }

    public function getAllianceRankByName($allianceName)
    {
        $row = db::get_row("SELECT p.alliance_id, SUM(p.total_people_count) as `points` FROM p_players p WHERE  p.alliance_name LIKE '$allianceName%' LIMIT 1");
        return intval($row['alliance_id']) == 0 ? 0 : $this->getAllianceRank(intval($row['alliance_id']), intval($row['points']));
    }

    public function getAllianceRankById($allianceId)
    {
        $row = db::get_row("SELECT  SUM(p.total_people_count) as `points` FROM p_players p WHERE  p.alliance_id=$allianceId");
        return intval($row['points']) == 0 ? 0 : $this->getAllianceRank(intval($allianceId), intval($row['points']));
    }

    public function getAllianceRank($allianceId, $score)
    {
        $all = db::get_all("SELECT a.id, SUM(p.total_people_count) as `points`  FROM p_alliances a  INNER JOIN p_players p ON (p.alliance_id = a.id)  GROUP BY a.id  ORDER BY points DESC, a.player_count DESC, a.id ASC");
        $_c = 0;
        $rank = 0;
        foreach ($all as $value) {
            ++$_c;
            if ($value['points'] == $score && $value['id'] == $allianceId) {
                $rank = $_c;
                break;
            }
        }
        return $rank;
    }

    public function getHeroListCount()
    {
        return db::get_field("SELECT COUNT(*)  FROM p_players p WHERE p.player_type=:ty AND p.hero_troop_id>0", array(
            'ty' => PLAYERTYPE_NORMAL
        ));
    }

    public function getHerosList($pageIndex, $pageSize)
    {
        $of = $pageIndex * $pageSize;
        return db::get_all("SELECT  p.id, p.name, p.hero_troop_id, p.hero_level, p.hero_points, IFNULL(p.hero_name, p.name) hero_name FROM p_players p WHERE p.player_type=:ty AND p.hero_troop_id>0 ORDER BY (p.hero_points*10+p.hero_level) DESC, p.id ASC LIMIT $of,$pageSize", array(
            'ty' => PLAYERTYPE_NORMAL
        ));
    }

    public function getHeroRankById($playerId)
    {
        $row = db::get_row("SELECT  p.id, p.hero_troop_id, (p.hero_points*10+p.hero_level) score FROM p_players p WHERE  p.id=:id AND p.player_type=:ty AND p.hero_troop_id>0 LIMIT 1", array(
            'id' => $playerId,
            'ty' => PLAYERTYPE_NORMAL
        ));
        return intval($row['hero_troop_id']) == 0 ? 0 : $this->getHeroRank($row['id'], $row['score']);
    }

    public function getHeroRankByName($playerName)
    {
        $row = db::get_row("SELECT  p.id, p.hero_troop_id, (p.hero_points*10+p.hero_level) score FROM p_players p WHERE  p.player_type=:ty AND p.hero_troop_id>0 AND IFNULL(p.hero_name, p.name) LIKE '$playerName%' LIMIT 1", array(
            'ty' => PLAYERTYPE_NORMAL
        ));
        return intval($row['hero_troop_id']) == 0 ? 0 : $this->getHeroRank($row['id'], $row['score']);
    }

    public function getHeroRank($playerId, $score)
    {
        $score = intval($score);
        $playerId = intval($playerId);
        return db::get_field("SELECT ( (SELECT COUNT(*) FROM p_players p WHERE  (p.hero_points*10+p.hero_level)>:sc AND p.player_type=:ty AND p.hero_troop_id>0) + (SELECT  COUNT(*) FROM p_players p WHERE (p.hero_points*10+p.hero_level)=:sc AND p.id<:id AND p.player_type=:ty AND p.hero_troop_id>0) ) + 1 rank", array(
            'sc' => $score,
            'ty' => PLAYERTYPE_NORMAL,
            'id' => $playerId
        ));
    }

    public function getGeneralSummary()
    {
        $sessionTimeoutInSeconds = $GLOBALS['GameMetadata']['session_timeout'] * 60;
        $row = db::get_row("SELECT  gs.players_count, gs.active_players_count, gs.Arab_players_count, gs.Roman_players_count, gs.Teutonic_players_count, gs.Gallic_players_count FROM g_summary gs");
        $row['online_players_count'] = db::get_field("SELECT COUNT(*) FROM p_players p WHERE TIME_TO_SEC(TIMEDIFF(NOW(), p.last_login_date)) <= :se", array(
            'se' => $sessionTimeoutInSeconds
        ));
        return $row;
    }

    public function getPlayersPointsListCount()
    {
        return db::get_field("SELECT COUNT(*)  FROM p_players p WHERE p.player_type=:ty", array(
            'ty' => PLAYERTYPE_NORMAL
        ));
    }

    public function getPlayersPointsList($pageIndex, $pageSize, $isDefense)
    {
        $of = $pageIndex * $pageSize;
        $da = $isDefense ? "defense_points" : "attack_points";
        return db::get_all("SELECT  p.id, p.name, p.total_people_count, p.villages_count, p.$da points FROM p_players p WHERE  p.player_type=:ty ORDER BY (p.$da) DESC, p.id ASC LIMIT $of,$pageSize", array(
            'ty' => PLAYERTYPE_NORMAL
        ));
    }

    public function getPlayersPointsById($playerId, $isDefense)
    {
        $da = $isDefense ? "defense_points" : "attack_points";
        $row = db::get_row("SELECT  p.id, p.$da score FROM p_players p WHERE  p.id=:id AND p.player_type=:ty LIMIT 1", array(
            'id' => $playerId,
            'ty' => PLAYERTYPE_NORMAL
        ));
        return $this->getPlayersPointsRank($row['id'], $row['score'], $isDefense);
    }

    public function getPlayersPointsByName($playerName, $isDefense)
    {
        $da = $isDefense ? "defense_points" : "attack_points";
        $row = db::get_row("SELECT  p.id, p.$da score FROM p_players p WHERE  p.player_type=:ty AND p.name LIKE '$playerName%' LIMIT 1", array(
            'ty' => PLAYERTYPE_NORMAL
        ));
        return $this->getPlayersPointsRank($row['id'], $row['score'], $isDefense);
    }

    public function getPlayersPointsRank($playerId, $score, $isDefense)
    {
        $score = intval($score);
        $playerId = intval($playerId);
        $da = $isDefense ? "defense_points" : "attack_points";
        return db::get_field("SELECT ( (SELECT COUNT(*) FROM p_players p WHERE p.player_type=:ty AND p.$da>:sc) + (SELECT  COUNT(*) FROM p_players p WHERE p.player_type=:ty AND p.id<:id AND p.$da=:sc) ) + 1 rank", array(
            'ty' => PLAYERTYPE_NORMAL,
            'sc' => $score,
            'id' => $playerId
        ));
    }

    public function getAlliancePointsListCount()
    {
        return db::get_field("SELECT COUNT(*)  FROM p_alliances a");
    }

    public function getAlliancePointsList($pageIndex, $pageSize, $isDefense)
    {
        $of = $pageIndex * $pageSize;
        $da = $isDefense ? "defense_points" : "attack_points";
        return db::get_all("SELECT  a.id, a.name, a.player_count, a.$da points FROM p_alliances a ORDER BY a.$da DESC, a.id ASC LIMIT $of,$pageSize");
    }

    public function getAlliancePointsRankByName($allianceName, $isDefense)
    {
        $da = $isDefense ? "defense_points" : "attack_points";
        $row = db::get_row("SELECT  a.id, a.$da score FROM p_alliances a WHERE a.name LIKE '$allianceName%' LIMIT 1", array(
            'name' => $allianceName
        ));
        return intval($row['id']) == 0 ? 0 : $this->getAlliancePointsRank(intval($row['id']), intval($row['score']), $isDefense);
    }

    public function getAlliancePointsRankById($allianceId, $isDefense)
    {
        $da = $isDefense ? "defense_points" : "attack_points";
        $row = db::get_row("SELECT  a.id, a.$da score FROM p_alliances a WHERE  a.id=:id LIMIT 1", array(
            'id' => $allianceId
        ));
        return intval($row['id']) == 0 ? 0 : $this->getAlliancePointsRank(intval($row['id']), intval($row['score']), $isDefense);
    }

    public function getAlliancePointsRank($allianceId, $score, $isDefense)
    {
        $da = $isDefense ? "defense_points" : "attack_points";
        return db::get_field("SELECT ( (SELECT COUNT(*) FROM p_alliances a WHERE  a.$da>:sc) + (SELECT  COUNT(*) FROM p_alliances a WHERE  a.$da=:sc AND a.id<:id) ) + 1 rank", array(
            'sc' => $score,
            'id' => $allianceId
        ));
    }

    public function getTop10($isPlayer, $columnName)
    {
        $pa = $isPlayer ? "p_players" : "p_alliances";
        $co = $columnName;
        return db::get_all("SELECT t.id, t.name, t.$co points FROM $pa t WHERE  t.$co>0 ORDER BY t.$co DESC, t.id ASC LIMIT 10");
    }

    public function getAlliancePoint($id, $columnName)
    {
        $co = $columnName;
        return db::get_field("SELECT t.$co points FROM p_alliances t WHERE t.id=:id", array(
            'id' => $id
        ));
    }

    public function getPlayerType($playerId)
    {
        return db::get_field("SELECT p.player_type FROM p_players p WHERE p.id=:id", array(
            'id' => $playerId
        ));
    }

    public function togglePlayerStatus($playerId)
    {
        db::query("UPDATE p_players p SET p.is_blocked=IF(p.is_blocked=1, 0, 1) WHERE  p.id=:id AND p.player_type=:ty", array(
            'id' => $playerId,
            'ty' => PLAYERTYPE_NORMAL
        ));
    }
}

?>

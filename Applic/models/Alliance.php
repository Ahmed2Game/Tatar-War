<?php

class Alliance_Model extends Model
{

    public function getAllianceData($allianceId)
    {
        return db::get_row("SELECT  a.*, SUM(p.total_people_count) score FROM p_alliances a  INNER JOIN p_players p ON (p.alliance_id = a.id)  WHERE a.id=:allianceid  GROUP BY a.id",
            array('allianceid' => $allianceId));
    }

    public function getAllianceDataFor($playerId)
    {
        return db::get_row("SELECT p.alliance_id,p.alliance_name FROM p_players p WHERE p.id=:playerid",
            array('playerid' => $playerId));
    }

    public function getLatestReports($playerIds, $type)
    {
        $expr = "";
        if ($type == 1) {
            $expr = sprintf("r.from_player_id IN (%s)", $playerIds);
        } else if ($type == 2) {
            $expr = sprintf("(r.to_player_id IN (%s) AND IF(r.rpt_cat=4, r.rpt_result!=100,1))", $playerIds);
        } else {
            $expr = sprintf("(r.from_player_id IN (%s) OR (r.to_player_id IN (%s) AND IF(r.rpt_cat=4, r.rpt_result!=100,1)))", $playerIds, $playerIds);
        }

        return db::get_all("SELECT r.id,r.from_player_id,r.to_player_id,r.from_player_name,r.to_player_name,r.rpt_result,
            r.rpt_cat,DATE_FORMAT(r.creation_date, '%y/%m/%d %H:%i') mdate,(r.from_player_id IN($playerIds)) isAttack
            FROM p_rpts r WHERE (r.rpt_cat=3 OR r.rpt_cat=4) AND $expr ORDER BY r.creation_date DESC LIMIT 20 ");
    }

    public function getAlliancePlayers($players_ids)
    {
        if (trim($players_ids) == "") {
            return NULL;
        }
        return db::get_all("SELECT p.id,p.name,p.total_people_count,p.alliance_roles,p.villages_count,
            floor(TIME_TO_SEC(TIMEDIFF(NOW(), p.last_login_date))/3600) lastLoginFromHours FROM p_players p
            WHERE p.id IN ($players_ids) ORDER BY p.total_people_count DESC, p.villages_count DESC");
    }

    public function getPlayerName($playerId)
    {
        return db::get_field("SELECT p.name FROM p_players p WHERE p.id=:playerid", array('playerid' => $playerId));
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

    public function editAllianceData($allianceId, $data, $playersIds)
    {
        db::query("UPDATE p_alliances a SET a.name=:name, a.name2=:name2, a.description1=:description1, a.description2=:description2 WHERE a.id=:allianceid", array('name' => $data['name'], 'name2' => $data['name2'], 'description1' => $data['description1'], 'description2' => $data['description2'], 'allianceid' => $allianceId));
        db::query("UPDATE p_players p SET p.alliance_name=:name WHERE p.id IN($playersIds)", array('name' => $data['name']));
        db::query("UPDATE p_villages v SET v.alliance_name=:alliance_name WHERE v.player_id IN($playersIds)", array('alliance_name' => $data['name']));
    }

    public function removeFromAlliance($playerId, $allianceId, $playersIds, $playersCount)
    {
        db::query("UPDATE p_players p SET p.alliance_id=NULL, p.alliance_name=NULL, p.alliance_roles=NULL WHERE p.id=:playerid", array('playerid' => $playerId));
        db::query("UPDATE p_villages v SET v.alliance_id=NULL, v.alliance_name=NULL WHERE v.player_id=:playerid", array('playerid' => $playerId));
        if (trim($playersIds) != "") {
            $playersIdsArr = explode(",", $playersIds);
            $playersIds = "";
            $i = 0;
            $c = sizeof($playersIdsArr);
            while ($i < $c) {
                if ($playersIdsArr[$i] != $playerId) {
                    if ($playersIds != "") {
                        $playersIds .= ",";
                    }
                    $playersIds .= $playersIdsArr[$i];

                }
                ++$i;
            }
        }
        db::query("UPDATE p_alliances a SET a.player_count=a.player_count-1, a.players_ids=:playerids WHERE a.id=:allianceid", array('playerids' => $playersIds, 'allianceid' => $allianceId));
        if ($playersCount == 1) {
            db::query("DELETE FROM p_alliances WHERE id=:allianceid", array('allianceid' => $allianceId));
        }
        return $playersIds;
    }

    public function getPlayerAllianceRole($playerId)
    {
        return db::get_row("SELECT p.name, p.alliance_roles FROM p_players p WHERE p.id=:playerid", array('playerid' => $playerId));
    }

    public function setPlayerAllianceRole($playerId, $roleName, $roleNumber)
    {
        return db::query("UPDATE p_players p SET p.alliance_roles=:alliance_roles WHERE p.id=:id", array('alliance_roles' => $roleNumber . " " . $roleName, 'id' => $playerId));
    }

    public function getPlayerId($playerName)
    {
        return db::get_field("SELECT p.id FROM p_players p WHERE p.name=:playername", array('playername' => $playerName));
    }

    public function _getNewInvite($invitesString, $removeId)
    {
        if ($invitesString == "") {
            return "";
        }
        $result = "";
        $arr = explode("\n", $invitesString);
        foreach ($arr as $invite) {
            list($id, $name) = explode(" ", $invite, 2);
            if ($id == $removeId) {
                continue;
            }
            if ($result != "") {
                $result .= "\n";
            }
            $result .= $id . " " . $name;
        }
        return $result;
    }

    public function removeAllianceInvites($playerId, $allianceId)
    {
        $pRow = db::get_row("SELECT p.name, p.invites_alliance_ids FROM p_players p WHERE p.id=:playerid", array('playerid' => $playerId));
        $aRow = db::get_row("SELECT a.name, a.invites_player_ids FROM p_alliances a WHERE a.id=:allianceid", array('allianceid' => $allianceId));
        $pInvitesStr = $this->_getNewInvite(trim($pRow['invites_alliance_ids']), $allianceId);
        $aInvitesStr = $this->_getNewInvite(trim($aRow['invites_player_ids']), $playerId);
        db::query("UPDATE p_players p SET p.invites_alliance_ids=:pInvitesStr WHERE p.id=:playerId", array('pInvitesStr' => $pInvitesStr, 'playerId' => $playerId));
        db::query("UPDATE p_alliances a SET a.invites_player_ids=:aInvitesStr WHERE a.id=:allianceId", array('aInvitesStr' => $aInvitesStr, 'allianceId' => $allianceId));
    }

    public function addAllianceInvites($playerId, $allianceId)
    {
        $pRow = db::get_row("SELECT p.name, p.invites_alliance_ids FROM p_players p WHERE p.id=:playerId", array('playerId' => $playerId));
        $aRow = db::get_row("SELECT a.name, a.invites_player_ids FROM p_alliances a WHERE a.id=:allianceId", array('allianceId' => $allianceId));
        $pInvitesStr = $pRow['invites_alliance_ids'];
        if ($pInvitesStr != "") {
            $pInvitesStr .= "\n";
        }
        $pInvitesStr .= $allianceId . " " . $aRow['name'];
        $aInvitesStr = $aRow['invites_player_ids'];
        if ($aInvitesStr != "") {
            $aInvitesStr .= "\n";
        }
        $aInvitesStr .= $playerId . " " . $pRow['name'];
        db::query("UPDATE p_players p SET p.invites_alliance_ids=:pInvitesStr WHERE p.id=:playerId", array('pInvitesStr' => $pInvitesStr, 'playerId' => $playerId));
        db::query("UPDATE p_alliances a SET a.invites_player_ids=:aInvitesStr WHERE a.id=:allianceId", array('aInvitesStr' => $aInvitesStr, 'allianceId' => $allianceId));
    }

    public function removeAllianceContracts($allianceId1, $allianceId2)
    {
        $contracts_alliance_id1 = db::get_field("SELECT a.contracts_alliance_id FROM p_alliances a WHERE a.id=:allianceId1", array('allianceId1' => $allianceId1));
        $contracts_alliance_id2 = db::get_field("SELECT a.contracts_alliance_id FROM p_alliances a WHERE a.id=:allianceId2", array('allianceId2' => $allianceId2));
        $contracts1 = "";
        if (trim($contracts_alliance_id1) != "") {
            $arr = explode(",", $contracts_alliance_id1);
            foreach ($arr as $arrStr) {
                $aStatus = explode(" ", $arrStr);
                $aid = explode(" ", $arrStr);
                list($aid, $aStatus) = $aid;
                if ($aid == $allianceId2) {
                    continue;
                }
                if ($contracts1 != "") {
                    $contracts1 .= ",";
                }
                $contracts1 .= $arrStr;
            }
        }
        $contracts2 = "";
        if (trim($contracts_alliance_id2) != "") {
            $arr = explode(",", $contracts_alliance_id2);
            foreach ($arr as $arrStr) {
                $aStatus = explode(" ", $arrStr);
                $aid = explode(" ", $arrStr);
                list($aid, $aStatus) = $aid;
                if ($aid == $allianceId1) {
                    continue;
                }
                if ($contracts2 != "") {
                    $contracts2 .= ",";
                }
                $contracts2 .= $arrStr;
            }
        }
        db::query("UPDATE p_alliances a SET a.contracts_alliance_id=:contracts1 WHERE a.id=:allianceId1", array('contracts1' => $contracts1, 'allianceId1' => $allianceId1));
        db::query("UPDATE p_alliances a SET a.contracts_alliance_id=:contracts2 WHERE a.id=:allianceId2", array('contracts2' => $contracts2, 'allianceId2' => $allianceId2));
    }

    public function acceptAllianceContracts($allianceId1, $allianceId2)
    {
        $contracts_alliance_id1 = db::get_field("SELECT a.contracts_alliance_id FROM p_alliances a WHERE a.id=:allianceId1", array('allianceId1' => $allianceId1));
        $contracts_alliance_id2 = db::get_field("SELECT a.contracts_alliance_id FROM p_alliances a WHERE a.id=:allianceId2", array('allianceId2' => $allianceId2));
        $contracts1 = "";
        if (trim($contracts_alliance_id1) != "") {
            $arr = explode(",", $contracts_alliance_id1);
            foreach ($arr as $arrStr) {
                $aStatus = explode(" ", $arrStr);
                $aid = explode(" ", $arrStr);
                list($aid, $aStatus) = $aid;
                if ($aid == $allianceId2) {
                    $aStatus = 0;
                }
                if ($contracts1 != "") {
                    $contracts1 .= ",";
                }
                $contracts1 .= $aid . " " . $aStatus;
            }
        }
        $contracts2 = "";
        if (trim($contracts_alliance_id2) != "") {
            $arr = explode(",", $contracts_alliance_id2);
            foreach ($arr as $arrStr) {
                $aStatus = explode(" ", $arrStr);
                $aid = explode(" ", $arrStr);
                list($aid, $aStatus) = $aid;
                if ($aid == $allianceId1) {
                    $aStatus = 0;
                }
                if ($contracts2 != "") {
                    $contracts2 .= ",";
                }
                $contracts2 .= $aid . " " . $aStatus;
            }
        }
        db::query("UPDATE p_alliances a SET a.contracts_alliance_id=:contracts1 WHERE a.id=:allianceId1", array('contracts1' => $contracts1, 'allianceId1' => $allianceId1));
        db::query("UPDATE p_alliances a SET a.contracts_alliance_id=:contracts2 WHERE a.id=:allianceId2", array('contracts2' => $contracts2, 'allianceId2' => $allianceId2));
    }

    public function addAllianceContracts($allianceId1, $allianceId2)
    {
        $contracts_alliance_id1 = db::get_field("SELECT a.contracts_alliance_id FROM p_alliances a WHERE a.id=:allianceId1", array('allianceId1' => $allianceId1));
        $contracts_alliance_id2 = db::get_field("SELECT a.contracts_alliance_id FROM p_alliances a WHERE a.id=:allianceId2", array('allianceId2' => $allianceId2));
        $contracts1 = $contracts_alliance_id1;
        if ($contracts1 != "") {
            $contracts1 .= ",";
        }
        $contracts1 .= $allianceId2 . " 1";
        $contracts2 = $contracts_alliance_id2;
        if ($contracts2 != "") {
            $contracts2 .= ",";
        }
        $contracts2 .= $allianceId1 . " 2";
        db::query("UPDATE p_alliances a SET a.contracts_alliance_id=:contracts1 WHERE a.id=:allianceId1", array('contracts1' => $contracts1, 'allianceId1' => $allianceId1));
        db::query("UPDATE p_alliances a SET a.contracts_alliance_id=:contracts2 WHERE a.id=:allianceId2", array('contracts2' => $contracts2, 'allianceId2' => $allianceId2));
    }

    public function getAllianceId($allianceName)
    {
        return db::get_field("SELECT a.id FROM p_alliances a WHERE a.name=:allianceName", array('allianceName' => $allianceName));
    }

    public function getAllianceName($allianceId)
    {
        return db::get_field("SELECT a.name FROM p_alliances a WHERE a.id=:allianceId", array('allianceId' => $allianceId));
    }

}

?>
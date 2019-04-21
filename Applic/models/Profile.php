<?php

class Profile_Model extends Model
{

    public function getPlayerIdByName($playerName)
    {
        return db::get_field("SELECT p.id FROM p_players p WHERE p.name=:name", array(
            'name' => $playerName
        ));
    }

    public function getPlayerAgentForById($playerId)
    {
        return db::get_field("SELECT p.agent_for_players FROM p_players p WHERE p.id=:id", array(
            'id' => $playerId
        ));
    }

    public function getPlayerMyAgentById($playerId)
    {
        return db::get_field("SELECT p.my_agent_players FROM p_players p WHERE p.id=:id", array(
            'id' => $playerId
        ));
    }

    public function getBlockPlayerById($playerId)
    {
        return db::get_field("SELECT p.block_player_id FROM p_players p WHERE p.id=:id", array(
            'id' => $playerId
        ));
    }

    public function setBlockPlayerId($blockId, $playerId)
    {
        return db::query("UPDATE p_players p SET p.block_player_id=:bid WHERE p.id=:id", array(
            'bid' => $blockId,
            'id' => $playerId
        ));
    }

    public function setMyAgents($playerId, $playerName, $agents, $newAgentId)
    {
        $agentStr = "";
        foreach ($agents as $agentId => $agentName) {
            if ($agentStr != "") {
                $agentStr .= ",";
            }
            $agentStr .= $agentId . " " . $agentName;
        }
        db::query("UPDATE p_players p SET p.my_agent_players=:my WHERE p.id=:id", array(
            'my' => $agentStr,
            'id' => $playerId
        ));
        $agentFor = $playerId . " " . $playerName;
        db::query("UPDATE p_players p SET p.agent_for_players=IF(ISNULL(p.agent_for_players) OR p.agent_for_players='', :ag, CONCAT_WS(',', p.agent_for_players, :ag)) WHERE p.id=:id", array(
            'ag' => $agentFor,
            'id' => $newAgentId
        ));
    }

    public function removeMyAgents($playerId, $agents, $aid)
    {
        $agentStr = "";
        foreach ($agents as $agentId => $agentName) {
            if ($agentStr != "") {
                $agentStr .= ",";
            }
            $agentStr .= $agentId . " " . $agentName;
        }
        db::query("UPDATE p_players p SET p.my_agent_players=:ag WHERE p.id=:id", array(
            'ag' => $agentStr,
            'id' => $playerId
        ));
        $agentForStr = $this->getPlayerAgentForById($aid);
        $agentForPlayers = trim($agentForStr) == "" ? array() : explode(",", $agentForStr);
        $i = 0;
        $c = sizeof($agentForPlayers);
        while ($i < $c) {
            $agent = $agentForPlayers[$i];
            list($agentId, $agentName) = explode(" ", $agent);
            if ($agentId == $playerId) {
                unset($agentForPlayers[$i]);
            }
            ++$i;
        }
        $agentForStr = implode(",", $agentForPlayers);
        db::query("UPDATE p_players p SET p.agent_for_players=:ag WHERE p.id=:id", array(
            'ag' => $agentForStr,
            'id' => $aid
        ));
    }

    public function removeAgentsFor($playerId, $agents, $aid)
    {
        $agentStr = "";
        foreach ($agents as $agentId => $agentName) {
            if ($agentStr != "") {
                $agentStr .= ",";
            }
            $agentStr .= $agentId . " " . $agentName;
        }
        db::query("UPDATE p_players p SET p.agent_for_players=:ag WHERE p.id=:id", array(
            'ag' => $agentStr,
            'id' => $playerId
        ));
        $agentForStr = $this->getPlayerMyAgentById($aid);
        $agentForPlayers = trim($agentForStr) == "" ? array() : explode(",", $agentForStr);
        $i = 0;
        $c = sizeof($agentForPlayers);
        while ($i < $c) {
            $agent = $agentForPlayers[$i];
            list($agentId, $agentName) = explode(" ", $agent);
            if ($agentId == $playerId) {
                unset($agentForPlayers[$i]);
            }
            ++$i;
        }
        $agentForStr = implode(",", $agentForPlayers);
        db::query("UPDATE p_players p SET p.my_agent_players=:ag WHERE p.id=:id", array(
            'ag' => $agentForStr,
            'id' => $aid
        ));
    }

    public function editPlayerProfile($playerId, $data)
    {
        $selected_village_id = db::get_field("SELECT p.selected_village_id FROM p_players p WHERE p.id=:id", array(
            'id' => $playerId
        ));
        $villages_data_arr = array();
        $villages_id_arr = explode("\n", $data['villages']);
        $i = 0;
        $c = sizeof($villages_id_arr);
        while ($i < $c) {
            list($vid, $x, $y, $vname) = explode(" ", $villages_id_arr[$i], 4);
            if ($vid == $selected_village_id) {
                $vname = $data['village_name'];
                $villages_id_arr[$i] = $vid . " " . $x . " " . $y . " " . $vname;
            }
            $villages_data_arr[$vname][] = $villages_id_arr[$i];
            ++$i;
        }
        ksort($villages_data_arr);
        $villages_data = "";
        foreach ($villages_data_arr as $k => $v) {
            foreach ($villages_data_arr[$k] as $v2) {
                if ($villages_data != "") {
                    $villages_data .= "\n";
                }
                $villages_data .= $v2;
            }
        }

        db::query("UPDATE p_players p SET p.description1=:des, p.description2=:des2, p.villages_data=:vi WHERE p.id=:id", array(
            'des' => $data['description1'],
            'des2' => $data['description2'],
            'vi' => $villages_data,
            'id' => $playerId
        ));
        db2::query("UPDATE p_players p SET p.birth_date=:bi, p.gender=:ge, p.house_name=:hn WHERE p.id=:id", array(
            'bi' => $data['birthData'],
            'ge' => $data['gender'],
            'hn' => $data['house_name'],
            'id' => $playerId
        ));
        $village_name = trim($data['village_name']);
        if ($village_name != "") {
            db::query("UPDATE p_villages v SET v.village_name=:vn WHERE v.id=:id", array(
                'vn' => $village_name,
                'id' => $selected_village_id
            ));
        }
    }

    public function changePlayerPassword($playerId, $newPassword)
    {
        db2::query("UPDATE p_players p SET p.pwd=:pass WHERE p.id=:id", array(
            'pass' => $newPassword,
            'id' => $playerId
        ));
    }

    public function changePlayerEmail($playerId, $newEmail, $playerName)
    {
        $activationCode = substr(md5(dechex($playerName) . dechex(time())), 5, 10);
        if (0 < intval(db2::get_field("SELECT COUNT(*) FROM p_players p WHERE p.email=:em", array(
                'em' => $newEmail
            )))) {
            return;
        }
        db2::query("UPDATE p_players p SET p.email=:em, p.is_active=0, p.activation_code=:s WHERE p.id=:id", array(
            'em' => $newEmail,
            'id' => $playerId,
            's' => $activationCode
        ));
        return $activationCode;
    }

    public function getPlayerRank($playerId, $score)
    {
        return db::get_field("SELECT ( (SELECT COUNT(*) FROM p_players p WHERE p.player_type!=:pt AND (p.total_people_count*10+p.villages_count)> :sc)  + (SELECT  COUNT(*) FROM p_players p WHERE p.player_type!=:pt AND p.id < :id AND (p.total_people_count*10+p.villages_count)=:sc) ) + 1 rank", array(
            'pt' => PLAYERTYPE_TATAR,
            'sc' => $score,
            'id' => $playerId
        ));
    }

    public function getWinnerPlayer()
    {
        $playerId = intval(db::get_field("SELECT gs.win_pid FROM g_settings gs"));
        return $this->getPlayerDataById($playerId);
    }

    public function getPlayerDataById($playerId)
    {
        $protectionPeriod = intval($GLOBALS['GameMetadata']['player_protection_period'] / $GLOBALS['GameMetadata']['game_speed']);
        $data = db::get_row("SELECT p.id, p.tribe_id, p.alliance_id, p.alliance_name, p.player_type, p.is_blocked,  p.protection, TIMESTAMPDIFF(SECOND, NOW(), p.blocked_time) blocked_second, p.description1, p.description2, p.medals, p.total_people_count, p.villages_count, p.name, p.last_login_date, p.villages_id, DATE_FORMAT(registration_date, '%Y/%m/%d %H:%i') registration_date, TIMEDIFF(DATE_ADD(registration_date, INTERVAL :se SECOND), NOW()) protection_remain, TIME_TO_SEC(TIMEDIFF(DATE_ADD(registration_date, INTERVAL :se SECOND), NOW())) protection_remain_sec FROM p_players p WHERE p.id=:id", array(
            'se' => $protectionPeriod,
            'id' => $playerId
        ));

        $data2 = db2::get_row("SELECT p.house_name, p.gender, p.avatar, birth_date, DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(birth_date)), '%Y')+0 age FROM p_players p WHERE p.id=:id", array(
            'id' => $playerId
        ));
        return is_array($data) ? array_merge($data, $data2) : null;
    }

    public function getVillagesSummary($villages_id)
    {
        return db::get_all("SELECT v.id, v.rel_x, v.rel_y, v.village_name, v.people_count, v.is_capital FROM p_villages v WHERE v.id IN ($villages_id) ORDER BY v.people_count DESC");
    }

    public function resetGNewsFlag($playerId)
    {
        db::query("UPDATE p_players p SET p.new_gnews=0 WHERE p.id=:id", array(
            'id' => $playerId
        ));
    }

    public function changePlayerLinks($playerId, $links)
    {
        db::query("UPDATE p_players p SET p.custom_links=:links WHERE p.id=:playerId", array(
            'links' => $links,
            'playerId' => $playerId
        ));
    }

    public function UpdatePlayerProtection($playerId, $protection)
    {
        db::query("UPDATE p_players p SET p.protection=:pro WHERE p.id=:playerId", array(
            'pro' => $protection,
            'playerId' => $playerId
        ));
    }

    public function UpdatePlayerHoliday($playerId, $holiday, $gold = 0)
    {
        db::query("UPDATE p_players p SET p.holiday=:hol WHERE p.id=:playerId", array(
            'hol' => $holiday,
            'playerId' => $playerId
        ));

        db2::query("UPDATE p_players p SET p.gold_num=p.gold_num-:gold WHERE p.id=:playerId", array(
            'gold' => $gold,
            'playerId' => $playerId
        ));
    }

}

?>
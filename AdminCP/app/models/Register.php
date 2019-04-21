<?php

class Register_Model extends Model
{

    public function isPlayerNameExists($playerName, $playerEmail)
    {

        $email2 = 0 < db2::get_field("SELECT COUNT(*) FROM p_players p WHERE p.email=:playerEmail", array(
                'playerEmail' => $playerEmail
            ));

        $name = 0 < db::get_field("SELECT COUNT(*) FROM p_players p WHERE p.name=:playerName", array(
                'playerName' => $playerName
            ));

        $name2 = 0 < db2::get_field("SELECT COUNT(*) FROM p_players p WHERE p.name=:playerName", array(
                'playerName' => $playerName
            ));
        return array(
            'master' => array('name' => $name2, 'email' => $email2),
            'server' => array('name' => $name)
        );

    }

    public function isPlayerMultiReg($playerIp)
    {
        $time_to_reg = 3600;
        return 3 < db::get_field("SELECT COUNT(*) FROM p_players p WHERE p.last_ip=:playerIp AND TIME_TO_SEC(TIMEDIFF(NOW(), p.registration_date)) <= :time_to_reg", array(
                'playerIp' => $playerIp,
                'time_to_reg' => $time_to_reg
            ));
    }

    public function GetServer($playerIp)
    {
        return db2::get_field("SELECT p.servers FROM p_players p WHERE p.id=:id", array(
            'id' => $playerIp
        ));
    }

    public function _getEmptyVillageId($position, $mapSize, $fid)
    {
        $halfMapSize = floor($mapSize / 2);
        $positionArray = array(
            ':x1' => 0 - $halfMapSize,
            ':x2' => $halfMapSize,
            ':y1' => 0 - $halfMapSize,
            ':y2' => $halfMapSize,
            ':fid' => $fid
        );
        switch ($position) {
            case 1 :
                $positionArray = array(
                    ':x1' => 0 - $halfMapSize,
                    ':x2' => 0,
                    ':y1' => 0,
                    ':y2' => $halfMapSize,
                    ':fid' => $fid
                );
                break;
            case 2 :
                $positionArray = array(
                    ':x1' => 0,
                    ':x2' => $halfMapSize,
                    ':y1' => 0,
                    ':y2' => $halfMapSize,
                    ':fid' => $fid
                );
                break;
            case 3 :
                $positionArray = array(
                    ':x1' => 0 - $halfMapSize,
                    ':x2' => 0,
                    ':y1' => 0 - $halfMapSize,
                    ':y2' => 0,
                    ':fid' => $fid
                );
                break;
            case 4 :
                $positionArray = array(
                    ':x1' => 0,
                    ':x2' => $halfMapSize,
                    ':y1' => 0 - $halfMapSize,
                    ':y2' => 0,
                    ':fid' => $fid
                );
        }
        return db::get_row("SELECT v.id, v.rel_x, v.rel_y FROM p_villages v WHERE v.field_maps_id=:fid AND ISNULL(v.player_id) AND (v.rel_x >= :x1 AND v.rel_x <= :x2) AND (v.rel_y >= :y1 AND v.rel_y <= :y2) ORDER BY v.rand_num LIMIT 1", $positionArray);
    }

    public function createVillage($playerId, $tribeId, $villageId, $playerName, $villageName, $playerType)
    {
        $GameMetadata = $GLOBALS['GameMetadata'];
        $isSpecial = $playerType == PLAYERTYPE_TATAR;
        $isartefacts = $villageName == artefacts_tribe_villages;
        $row = db::get_row("SELECT v.player_id,v.field_maps_id FROM p_villages v WHERE v.id=:villageId", array(
            'villageId' => $villageId
        ));
        if (0 < intval($row['player_id'])) {
            return FALSE;
        }
        $update_key = substr(md5($playerId . $tribeId . $villageId . $playerName . $villageName), 2, 5);
        $field_map_id = $row['field_maps_id'];
        $buildings = "";
        foreach ($GLOBALS['SetupMetadata']['field_maps'][$field_map_id] as $v) {
            if ($buildings != "") {
                $buildings .= ",";
            }
            $buildings .= sprintf("%s 0 0", $v);
        }
        $k = 19;
        while ($k <= 40) {
            $buildings .= ($k == 26 && !$isSpecial) ? ",15 1 0" : (($k == 26 && $isartefacts) ? ",27 20 0" : ",0 0 0");
            ++$k;
        }
        $resources = "";
        $farr = explode("-", $GLOBALS['SetupMetadata']['field_maps_summary'][$field_map_id]);
        $i = 1;
        $_c = sizeof($farr);
        while ($i <= $_c) {
            if ($resources != "") {
                $resources .= ",";
            }
            $resources .= sprintf("%s 1300 1500 1500 %s 0", $i, $farr[$i - 1] * 2 * $GameMetadata['game_speed']);
            ++$i;
        }
        $troops_training = "";
        $troops_num = "";
        foreach ($GameMetadata['troops'] as $k => $v) {
            if ($v['for_tribe_id'] == 0 - 1 || $v['for_tribe_id'] == $tribeId) {
                if ($troops_training != "") {
                    $troops_training .= ",";
                }
                $researching_done = $v['research_time_consume'] == 0 ? 1 : 0;
                $troops_training .= $k . " " . $researching_done . " 0 0";
                if ($troops_num != "") {
                    $troops_num .= ",";
                }
                $troops_num .= $k . " 0";
            }
        }
        $troops_num = "-1:" . $troops_num;

        db::query("UPDATE p_villages v SET v.last_update_date=NOW(), v.tribe_id=:tribeId, v.player_id=:playerId,
            v.player_name=:playerName, v.village_name=:villageName, v.is_capital=1, v.is_special_village=:isSpecial,
            v.creation_date=NOW(), v.buildings=:buildings, v.resources=:resources, v.cp='0 2',
            v.troops_training=:troops_training, v.troops_num=:troops_num, v.update_key=:update_key WHERE v.id=:villageId", array(
            'tribeId' => $tribeId,
            'playerId' => $playerId,
            'playerName' => $playerName,
            'villageName' => $villageName,
            'isSpecial' => ($isSpecial && !$isartefacts) ? "1" : "0",
            'buildings' => $buildings,
            'resources' => $resources,
            'troops_training' => $troops_training,
            'troops_num' => $troops_num,
            'update_key' => $update_key,
            'villageId' => $villageId
        ));
        return TRUE;
    }

    public function createMaster($playerName, $playerEmail, $playerPassword, $invite_by = 0, $is_active = 0, $goldnum = 0)
    {
        $activationCode = substr(md5(dechex($playerName) . dechex(time())), 5, 10);
        $result = db2::count("INSERT INTO p_players SET name=:playerName, pwd=:playerPassword,
            email=:playerEmail, is_active=:is_active, activation_code=:activationCode, invite_by=:Invite, gold_num=:goldnum", array(
            'playerName' => $playerName,
            'playerPassword' => md5($playerPassword),
            'playerEmail' => $playerEmail,
            'is_active' => $is_active,
            'activationCode' => $activationCode,
            'Invite' => $invite_by,
            'goldnum' => $goldnum
        ));
        return array('activationCode' => $activationCode, 'result' => $result);
    }

    public function createNewPlayer($playerId, $playerName, $tribeId, $position, $villageName, $mapSize, $playerType, $villageCount = 1, $playerIp = NULL, $playerInvite = 0, $fid = 3)
    {
        db::query("INSERT INTO p_players SET id=:id, tribe_id=:tribeId, name=:playerName, active_plus_account=0, is_blocked=0, last_ip=:playerIp, ip_his=:playerIp,
            invite_by=:playerInvite, registration_date=NOW(), player_type=:playerType, medals='0:::'", array(
            'id' => $playerId,
            'tribeId' => $tribeId,
            'playerName' => $playerName,
            'playerIp' => $playerIp,
            'playerInvite' => intval($playerInvite),
            'playerType' => $playerType
        ));
        if (!$playerId) {
            return array(
                "hasErrors" => TRUE
            );
        }
        $villages = array();
        $i = 0;
        while ($i < $villageCount) {
            $vrow = NULL;
            $rundem = ($villageCount > 13) ? 1 : 10;
            $positionRand = ($villageCount - $rundem) / 4;
            if ($i >= $rundem && $i < $rundem + $positionRand) {
                $position = 1;
            } elseif ($i < $rundem + ($positionRand * 2)) {
                $position = 2;
            } elseif ($i < $rundem + ($positionRand * 3)) {
                $position = 3;
            } elseif ($i < $rundem + ($positionRand * 4)) {
                $position = 4;
            }
            if ($playerType == PLAYERTYPE_ADMIN) {
                $vrow = array("id" => 1, "rel_x" => 0, "rel_y" => 0);
            } else if ($playerType == PLAYERTYPE_HUNTER) {

                $vrow = array("id" => 2, "rel_x" => 0, "rel_y" => 1);
            } else {
                $vrow = $this->_getEmptyVillageId($position, $mapSize, $fid);
            }
            $villageId = $vrow['id'];
            $villages[$villageId] = array(
                $vrow['rel_x'],
                $vrow['rel_y'],
                $villageName
            );
            if (!$villageId) {
                db::query("DELETE FROM p_players WHERE id=:playerId", array(
                    'playerId' => $playerId
                ));
                return array(
                    "hasErrors" => TRUE
                );
            }
            $trialsCount = 1;
            while (!$this->createVillage($playerId, $tribeId, $villageId, $playerName, $villageName, $playerType)) {
                unset($villages[$villageId]);
                if ($trialsCount == 3) {
                    db::query("DELETE FROM p_players WHERE id=:playerId", array(
                        'playerId' => $playerId
                    ));
                    return array(
                        "hasErrors" => TRUE
                    );
                }
                ++$trialsCount;
                $vrow = $this->_getEmptyVillageId($position, $mapSize);
                $villageId = $vrow['id'];
                $villages[$villageId] = array(
                    $vrow['rel_x'],
                    $vrow['rel_y'],
                    $villageName
                );
            }
            ++$i;
        }
        $villages_id = "";
        $villages_data = "";
        foreach ($villages as $k => $v) {
            if ($villages_id != "") {
                $villages_id .= ",";
                $villages_data .= "\n";
            }
            $villages_data .= $k . " " . implode(" ", $v);
            $villages_id .= $k;
        }

        db::query("UPDATE p_players SET selected_village_id=:villageId,
            villages_id=:villages_id, villages_data=:villages_data, villages_count=:villages WHERE id=:playerId", array(
            'villageId' => $villageId,
            'villages_id' => $villages_id,
            'villages_data' => $villages_data,
            'villages' => sizeof($villages),
            'playerId' => $playerId
        ));

        $expr = "";
        switch ($tribeId) {
            case 1 :
                $expr = ",gs.Roman_players_count=gs.Roman_players_count+1";
                break;
            case 2 :
                $expr = ",gs.Teutonic_players_count=gs.Teutonic_players_count+1";
                break;
            case 3 :
                $expr = ",gs.Gallic_players_count=gs.Gallic_players_count+1";
                break;
            case 7 :
                $expr = ",gs.Arab_players_count=gs.Arab_players_count+1";
        }
        if ($playerType == PLAYERTYPE_ADMIN) {
            $expr .= ",gs.active_players_count=gs.active_players_count+1";
        }

        if ($playerType == PLAYERTYPE_HUNTER) {
            $expr .= ",gs.active_players_count=gs.active_players_count+1";
        }
        if ($expr != "") {
            db::query("UPDATE g_summary gs SET gs.players_count=gs.players_count+1 $expr");
            db2::query("UPDATE g_summary gs SET gs.players_count=gs.players_count+1");
        }

        return array(
            "playerId" => $playerId,
            "villages" => $villages,
            "hasErrors" => FALSE
        );
    }

}

?>
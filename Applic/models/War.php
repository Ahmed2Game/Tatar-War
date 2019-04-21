<?php

//v2v
class War_Model extends Model
{

    public function getPlayerDataById($playerId)
    {
        $GameMetadata = $GLOBALS['GameMetadata'];
        $protectionPeriod = intval($GameMetadata['player_protection_period'] / $GameMetadata['game_speed']);
        return db::get_row("SELECT p.id, p.alliance_id, p.ip_his, p.protection, p.holiday, p.is_blocked, TIMESTAMPDIFF(SECOND, NOW(), p.blocked_time) blocked_second, p.total_people_count, p.name, TIME_TO_SEC(TIMEDIFF(DATE_ADD(p.registration_date, INTERVAL :se SECOND), NOW())) protection_remain_sec FROM p_players p WHERE p.id=:id", array(
            'se' => $protectionPeriod,
            'id' => $playerId
        ));
    }

    public function getVillageDataById($villageId)
    {
        return db::get_row("SELECT v.id, v.rel_x, v.rel_y, v.tribe_id, v.village_name, v.player_id, v.player_name, v.is_special_village, v.is_oasis FROM p_villages v WHERE v.id=:id", array(
            'id' => $villageId
        ));
    }

    public function getVillageDataByName($villageName)
    {
        return db::get_row("SELECT v.id, v.rel_x, v.rel_y, v.tribe_id, v.village_name, v.player_id, v.player_name, v.is_special_village, v.is_oasis FROM p_villages v WHERE v.village_name=:name", array(
            'name' => $villageName
        ));
    }

    public function getVillageData2ById($villageId)
    {
        return db::get_row("SELECT v.id, v.rel_x, v.rel_y, v.tribe_id, v.village_name, v.player_id, v.player_name, v.is_special_village, v.is_oasis, v.troops_num, v.troops_out_num, v.troops_intrap_num, v.troops_out_intrap_num, v.village_oases_id FROM p_villages v WHERE v.id=:id", array(
            'id' => $villageId
        ));
    }

    public function backTroopsFrom($fromVillageId, $column1, $troops1, $toVillageId, $column2, $troops2)
    {
        db::query("UPDATE p_villages v SET v.$column1=:tr WHERE v.id=:id", array(
            'tr' => $troops1,
            'id' => $fromVillageId
        ));
        db::query("UPDATE p_villages v SET v.$column2=:tr WHERE v.id=:id", array(
            'tr' => $troops2,
            'id' => $toVillageId
        ));
    }

    public function hasNewVillageTask($playerId)
    {
        return db::get_field("SELECT COUNT(*) FROM p_queue q WHERE q.player_id=:id AND q.proc_type=:ty", array(
            'id' => $playerId,
            'ty' => QS_CREATEVILLAGE
        ));
    }

    public function getPlayType($player_id)
    {
        return db::get_field("SELECT p.player_type FROM p_players p WHERE p.id=:id", array(
            'id' => $player_id
        ));
    }

    public function GetGsummaryData()
    {
        return db::get_row("SELECT gs.truce_reason, TIMESTAMPDIFF(SECOND, NOW(), gs.truce_time) truce_second FROM g_summary gs ");
    }

    public function UpdatePlayerPainTime($playerId, $time, $reason)
    {
        db::query("UPDATE p_players p SET p.blocked_time=:ti, p.blocked_reason=:rea WHERE p.id=:id", array(
            'ti' => $time,
            'rea' => $reason,
            'id' => $playerId
        ));
    }

    public function UpdatePlayerprotection($playerId)
    {
        db::query("UPDATE p_players p SET p.registration_date=NOW() - INTERVAL 1 DAY WHERE p.id=:id", array(
            'id' => $playerId
        ));
    }

    public function IfVillageHasAttak($viid)
    {
        return db::get_field('SELECT COUNT(*) from  p_queue where village_id=:id and proc_type=13  or village_id=:id and proc_type=14', array(
            'id' => $viid
        ));
    }

    public function ifhasContracts($allianceId1, $allianceId2)
    {
        $contracts_alliance_id1 = db::get_field("SELECT a.contracts_alliance_id FROM p_alliances a WHERE a.id=:allianceId1", array('allianceId1' => $allianceId1));
        if (trim($contracts_alliance_id1) != "") {
            $arr = explode(",", $contracts_alliance_id1);
            foreach ($arr as $arrStr) {
                $aid = explode(" ", $arrStr);
                list($aid, $aStatus) = $aid;
                if ($aid == $allianceId2 && $aStatus == 0) {
                    return true;
                }
            }
        }
        return false;
    }

}

?>
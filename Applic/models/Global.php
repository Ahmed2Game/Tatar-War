<?php

class Global_Model extends Model
{
    public function getbuildupdate ($playerId, $villageId, $buildId, $buildIndx)
    {
        return db::get_field("SELECT COUNT(*) FROM p_queue WHERE
            player_id=:playerId and village_id=:villageId and proc_type=2 and building_id=:buildId and proc_params=:buildIndx",
            array(
                    'playerId'  => $playerId,
                    'villageId' => $villageId,
                    'buildId'   => $buildId,
                    'buildIndx' => $buildIndx
            )
        );
    }

    public function SiteReset()
    {
        return db::get_row('SELECT TIMESTAMPDIFF(SECOND, NOW(),q.end_date) remainingTimeInSeconds FROM p_queue q WHERE proc_type=:ty', array('ty' => QS_SITE_RESET));
    }

    public function updatevillagebuild($villageId, $buliding)
    {
        db::query("UPDATE p_villages v SET v.buildings=:buliding WHERE v.id=:villageId", array(
            'buliding'  => $buliding,
            'villageId' => $villageId
        ));
    }

    public function getSiteNews()
    {
        return db::get_field('SELECT gs.news_text FROM g_summary gs');
    }

    public function getServerStartTime()
    {
            return db::get_row('SELECT TIMESTAMPDIFF(SECOND, gs.start_date, NOW()) server_start_time FROM g_settings gs');
    }

    public function IfVillageHasAttak($viid)
    {
        return db::get_field('SELECT COUNT(*) from p_queue where to_village_id=:viid and proc_type=13  or to_village_id=:viid and proc_type=14', array(
            'viid' => $viid,
        ));
    }

    public function getGlobalSiteNews( )
    {
        return db::get_field( "SELECT g.gnews_text FROM g_summary g" );
    }

    public function setSelectedVillage($playerId, $villageId)
    {
        db::query('UPDATE p_players p SET p.selected_village_id=:villageId WHERE  p.id=:playerId', array(
            'villageId' => $villageId,
            'playerId'  => $playerId
        ));
    }

    public function hasVillage($playerId, $villageId)
    {
        return intval(db::get_field('SELECT v.player_id FROM p_villages v WHERE v.id=:villageId', array(
            'villageId' => $villageId
        ))) == $playerId;
    }

    public function getVillageData($playerId)
    {
        $GameMetadata            = $GLOBALS['GameMetadata'];
        $protectionPeriod        = intval($GameMetadata['player_protection_period'] / $GameMetadata['game_speed']);
        $sessionTimeoutInSeconds = $GameMetadata['session_timeout'] * 60;
        $data                    = db::get_row('SELECT
                p.alliance_id,
                p.alliance_name,
                p.alliance_roles,
                p.description1, p.description2,
                p.agent_for_players, p.my_agent_players,
                p.medals,
                p.total_people_count,
                p.villages_count,
                p.player_type,
                p.is_blocked,
                TIMESTAMPDIFF(SECOND, NOW(), p.blocked_time) blocked_second,
                p.blocked_reason,
                p.UserSession,
                p.protection,
                p.holiday,
                p.active_plus_account,
                p.name,
                p.custom_links,
                p.new_report_count, p.new_mail_count,
                p.selected_village_id, p.villages_id, p.villages_data,
                p.last_login_date,
                p.notes,
                p.week_attack_points,
                p.week_defense_points,
                p.week_dev_points,
                p.week_thief_points,
                p.hero_troop_id, p.hero_level, p.hero_points, p.hero_name, p.hero_in_village_id,
                p.invites_alliance_ids,
                p.guide_quiz,
                p.new_gnews,
                p.create_nvil,
                p.registration_date,
                TIMEDIFF(DATE_ADD(p.registration_date, INTERVAL :protectionPeriod SECOND), NOW()) protection_remain,
                TIME_TO_SEC(TIMEDIFF(DATE_ADD(p.registration_date, INTERVAL :protectionPeriod SECOND), NOW())) protection_remain_sec,
                TIME_TO_SEC(TIMEDIFF(NOW(), p.last_login_date)) last_login_sec
            FROM p_players p
            WHERE p.id=:id', array(
            'id' => $playerId,
            'protectionPeriod' => $protectionPeriod
        ));
         $data3  = db2::get_row('SELECT p.house_name, p.gender, p.name, p.pwd, p.email, p.gold_num, p.gold_buy, p.birth_date, DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(p.birth_date)), \'%Y\')+0 age FROM p_players p WHERE p.id=:id', array(
            'id' => $playerId
        ));
        // fix bug date_format PDO
        if ($data == NULL)
        {
            return NULL;
        }
        if ( $sessionTimeoutInSeconds <= $data['last_login_sec'] )
        {
            db::query( "UPDATE p_players p SET p.last_login_date=NOW() WHERE p.id=:s", array(
                's' => $playerId
            ) );
        }
        $data2 = db::get_row('SELECT
                v.rel_x, v.rel_y,
                v.parent_id, v.tribe_id,
                v.field_maps_id,
                v.village_name,
                v.is_capital, v.is_special_village,
                v.people_count,
                v.crop_consumption,
                v.time_consume_percent,
                v.resources, v.buildings, v.cp,
                v.troops_training, v.troops_num,
                v.troops_trapped_num, v.troops_intrap_num, v.troops_out_num, v.troops_out_intrap_num,
                v.allegiance_percent,
                v.child_villages_id, v.village_oases_id,
                v.offer_merchants_count,
                v.update_key,
                TIME_TO_SEC(TIMEDIFF(NOW(), v.last_update_date)) elapsedTimeInSeconds
            FROM p_villages v
            WHERE v.id=:s', array('s' => $data['selected_village_id']));
        if ($data2 == NULL)
        {
            return NULL;
        }
        $data = array_merge($data, $data2, $data3);
        unset($data2);
        $row                = db::get_row('SELECT g.game_over, g.game_transient_stopped FROM g_settings g');
        $data['gameStatus'] = intval($row['game_over']) | intval($row['game_transient_stopped']) << 1;
        return $data;
    }

    public function isGameOver()
    {
        return intval(db::get_field('SELECT g.game_over FROM g_settings g')) == 1;
    }

    public function resetNewVillageFlag($playerId)
    {
        db::query('UPDATE p_players p SET p.create_nvil=0 WHERE p.id=:playerId', array(
            'playerId' => $playerId
        ));
    }

    public function hasOasis( $playerId, $villageId )
    {
        return intval( db::get_field( "SELECT v.player_id FROM p_villages v WHERE v.id=:s AND is_oasis=1", array(
            's' => $villageId
        ) ) ) == $playerId;
    }

}
?>
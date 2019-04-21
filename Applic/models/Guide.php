<?php

class Guide_Model extends Model
{

    public function setGuideTask($playerId, $guideQuiz)
    {
        db::query("UPDATE p_players p SET p.guide_quiz=:guideQuiz WHERE p.id=:playerId", array(
            'guideQuiz' => $guideQuiz,
            'playerId' => $playerId
        ));
    }

    public function increaseGoldNumber($playerId, $golds)
    {
        db2::query("UPDATE p_players p SET p.gold_num=p.gold_num+:golds WHERE p.id=:playerId", array(
            'golds' => $golds,
            'playerId' => $playerId
        ));
    }

    public function getPlayerRank($playerId, $score)
    {
        return db::get_field("SELECT ((SELECT COUNT(*) FROM p_players p WHERE p.player_type!=:PLAYERTYPE_TATAR AND (p.total_people_count*10+p.villages_count)>:score) +(SELECT COUNT(*)FROM p_players p WHERE p.player_type!=:PLAYERTYPE_TATAR AND p.id < :playerId AND (p.total_people_count*10+p.villages_count)=:score)) + 1 rank", array(
            'score' => $score,
            'PLAYERTYPE_TATAR' => PLAYERTYPE_TATAR,
            'playerId' => $playerId
        ));
    }

    public function isOpenedMessage($messageId)
    {
        return true;
    }

    public function addResourcesTo($villageId, $resourcesArray)
    {
        $resourceStr = db::get_field("SELECT v.resources FROM p_villages v WHERE v.id=:villageId", array(
            'villageId' => $villageId
        ));
        $r_arr = explode(",", $resourceStr);
        $resourceStr = "";
        $i = 0;
        foreach ($r_arr as $r_str) {
            $r2 = explode(" ", $r_str);
            $resources[$r2[0]] = array(
                "current_value" => $r2[1] + $resourcesArray[$i++],
                "store_max_limit" => $r2[2],
                "store_init_limit" => $r2[3],
                "prod_rate" => $r2[4],
                "prod_rate_percentage" => $r2[5]
            );
            if ($resources[$r2[0]]['store_max_limit'] < $resources[$r2[0]]['current_value']) {
                $resources[$r2[0]]['current_value'] = $resources[$r2[0]]['store_max_limit'];
            }
            if ($resourceStr != "") {
                $resourceStr .= ",";
            }
            $resourceStr .= $r2[0] . " " . $resources[$r2[0]]['current_value'] . " " . $resources[$r2[0]]['store_max_limit'] . " " . $resources[$r2[0]]['store_init_limit'] . " " . $resources[$r2[0]]['prod_rate'] . " " . $resources[$r2[0]]['prod_rate_percentage'];
        }

        db::query("UPDATE p_villages v SET v.resources=:resourceStr WHERE v.id=:villageId", array(
            'resourceStr' => $resourceStr,
            'villageId' => $villageId
        ));
    }

    public function getVillagesMatrix($matrixStr)
    {
        return db::get_all("SELECT v.id, v.rel_x, v.rel_y, v.village_name, v.is_oasis, v.player_id FROM p_villages v WHERE v.id IN ($matrixStr)");
    }

    public function guideTroopsReached($queueId)
    {
        return db::get_field("SELECT COUNT(*) FROM p_queue q WHERE q.id=:queueId", array(
                'queueId' => $queueId
            )) == 0;
    }

    public function isOpenedReport($playerId)
    {
        return db::get_field("SELECT IF(r.read_status=1 OR r.delete_status>0, 1, 0) FROM p_rpts r WHERE r.from_player_id=0 AND r.from_village_id=0 AND r.rpt_cat=2 AND r.to_player_id=:playerId", array(
                'playerId' => intval($playerId)
            )) == 1 || db::get_field("SELECT COUNT(*) FROM p_rpts r WHERE r.from_player_id=0 AND r.from_village_id=0 AND r.rpt_cat=2 AND r.to_player_id=:playerId", array(
                'playerId' => intval($playerId)
            )) == 0;
    }

}

?>
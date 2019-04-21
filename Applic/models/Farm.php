<?php

class Farm_Model extends Model
{
    function addFarm($playerId, $villageId, $toVillageId, $troops)
    {
        db::query("INSERT INTO p_farm SET player_id=:pid, from_village_id=:vid, to_village_id=:tvid, troops=:troop", array(
            'pid' => $playerId,
            'vid' => $villageId,
            'tvid' => $toVillageId,
            'troop' => $troops
        ));
    }

    function DeleteThisFarm($farmId, $playerId)
    {
        db::query("DELETE FROM p_farm WHERE id=:id AND player_id=:pid", array(
            'id' => $farmId,
            'pid' => $playerId
        ));
    }

    function getFarmList($playerId, $villageId)
    {
        return db::query('SELECT * FROM p_farm f WHERE f.player_id=:pid AND f.from_village_id=:vid AND f.to_village_id>=1', array(
            'pid' => $playerId,
            'vid' => $villageId
        ));
    }

    function isFarmFull($villageId, $playerId)
    {
        return db::get_field('SELECT COUNT(*) FROM p_farm f WHERE f.from_village_id=:id AND f.player_id=:pid', array(
            'id' => $villageId,
            'pid' => $playerId
        ));
    }

    function getVillageDataById($villageId)
    {
        return db::get_row('SELECT v.id, v.rel_x, v.rel_y, v.village_name, v.player_id, v.is_oasis, v.people_count FROM p_villages v WHERE v.id=:id', array(
            'id' => $villageId
        ));
    }

    function getVillageDataByName($villageName)
    {
        return db::get_row('SELECT v.id, v.rel_x, v.rel_y, v.village_name, v.player_id, v.is_oasis, v.people_count FROM p_villages v WHERE v.village_name=:name', array(
            'name' => $villageName
        ));
    }

}

?>
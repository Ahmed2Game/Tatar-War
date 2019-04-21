<?php

class Villages_Model extends Model
{

    public function getVillagesData($PlayerId)
    {
        return db::get_all("SELECT * FROM p_villages WHERE player_id=:PlayerId and is_oasis=0 ", array(
            'PlayerId' => $PlayerId
        ));
    }

    public function getVillagesUpdate($villageId, $proc_type)
    {
        return db::get_field("SELECT COUNT(*) FROM p_queue WHERE village_id=:villageId and proc_type=:proc_type", array(
            'villageId' => $villageId,
            'proc_type' => $proc_type
        ));
    }

    public function getVillagesRein($villageId, $proc_type)
    {
        return db::get_field("SELECT COUNT(*) FROM p_queue WHERE to_village_id=:villageId and proc_type=:proc_type", array(
            'villageId' => $villageId,
            'proc_type' => $proc_type
        ));
    }

    public function getVillagesAttac($villageId)
    {
        return db::get_field("SELECT COUNT(*) FROM p_queue WHERE to_village_id=:villageId and proc_type=13  or to_village_id=:villageId and proc_type=14", array(
            'villageId' => $villageId
        ));
    }

    public function getVillagesMar($villageId)
    {
        return db::get_field("SELECT COUNT(*) FROM p_queue WHERE to_village_id=:villageId and proc_type=10  or to_village_id=:villageId and proc_type=11", array(
            'villageId' => $villageId
        ));
    }

    public function getVillageData($id)
    {
        return db::get_row("SELECT v.id, v.player_id, v.is_oasis, v.player_name, v.village_name, v.resources, v.cp, v.buildings, v.troops_num, v.crop_consumption,v.offer_merchants_count, TIME_TO_SEC(TIMEDIFF(NOW(), v.last_update_date)) elapsedTimeInSeconds FROM p_villages v WHERE v.id = :id", array(
            'id' => $id
        ));
    }

}

?>
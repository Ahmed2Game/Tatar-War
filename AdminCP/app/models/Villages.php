<?php
class Villages_Model extends Model
{
    public function GetVillagesDataByName($playerName)
    {
        return db::get_all("SELECT v.id, v.village_name, v.is_capital, v.is_special_village, v.people_count, v.crop_consumption  FROM p_villages v
            WHERE v.player_name=:playername", array(
            'playername' => $playerName
        ));
    }

    public function GetVillagesDataByCrop($crop)
    {
        $crop = is_numeric($crop) ? '<'.$crop : $crop;
        $ex = 'v.crop_consumption';
        $ex .= $crop;
        return db::get_all("SELECT v.id, v.village_name, v.is_capital, v.is_special_village, v.people_count, v.crop_consumption FROM p_villages v
            WHERE $ex");
    }

    public function GetVillagesDataByTroops($crop)
    {
        return db::get_all("SELECT v.id, v.village_name, v.player_id, v.player_name, v.people_count, v.crop_consumption, v.troops_num, v.troops_out_num  FROM p_villages v
            WHERE v.crop_consumption>:crop",array(
                'crop' => $crop));
    }

    public function GetVillageDataById($VillageId)
    {
        return db::get_row("SELECT * FROM p_villages WHERE id=:villageid", array(
            'villageid' => $VillageId
        ));
    }

    public function UpdateVillageData($id, $rel_x, $rel_y, $tribe_id, $player_id, $alliance_id, $player_name, $village_name, $alliance_name, $is_capital, $is_special_village, $is_oasis, $people_count, $crop_consumption, $resources, $cp, $buildings, $troops_num, $village_oases_id, $vid)
    {
        db::query("UPDATE p_villages v SET
                    v.id=:id, v.rel_x=:rel_x, v.rel_y=:rel_y, v.tribe_id=:tribe_id, v.player_id=:player_id,
                    v.alliance_id=:alliance_id,v.player_name=:player_name, v.village_name=:village_name,
                    v.alliance_name=:alliance_name, v.is_capital=:is_capital,v.is_special_village=:is_special_village,
                    v.is_oasis=:is_oasis, v.people_count=:people_count, v.crop_consumption=:crop_consumption,
                    v.resources=:resources, v.cp=:cp, v.buildings=:buildings, v.troops_num=:troops_num,
                    v.village_oases_id=:village_oases_id
                    WHERE  v.id=:vid", array(
            'id' => $id,
            'rel_x' => $rel_x,
            'rel_y' => $rel_y,
            'tribe_id' => $tribe_id,
            'player_id' => $player_id,
            'alliance_id' => $alliance_id,
            'player_name' => $player_name,
            'village_name' => $village_name,
            'alliance_name' => $alliance_name,
            'is_capital' => $is_capital,
            'is_special_village' => $is_special_village,
            'is_oasis' => $is_oasis,
            'people_count' => $people_count,
            'crop_consumption' => $crop_consumption,
            'resources' => $resources,
            'cp' => $cp,
            'buildings' => $buildings,
            'troops_num' => $troops_num,
            'village_oases_id' => $village_oases_id,
            'vid' => $vid
        ));
    }
}
?>
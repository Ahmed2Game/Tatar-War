<?php

class Map_Model extends Model
{

    public function getVillagesMatrix($matrixStr)
    {
        return db::get_all(" SELECT v.id,v.rel_x, v.rel_y, v.field_maps_id, v.image_num, v.tribe_id, v.player_id, v.alliance_id, v.player_name, v.village_name, v.alliance_name,v.people_count, v.is_oasis FROM p_villages v WHERE v.id IN ($matrixStr)", array(
            'matrixStr' => $matrixStr
        ));
    }

    public function getContractsAllianceId($allianceId)
    {
        return db::get_field(" SELECT a.contracts_alliance_id FROM p_alliances a WHERE a.id=:allianceId", array(
            'allianceId' => $allianceId
        ));
    }

}

?>

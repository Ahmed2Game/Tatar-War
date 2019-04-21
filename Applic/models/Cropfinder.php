<?php

class Cropfinder_Model extends Model
{
    public function getVillagesData($fieldtype)
    {
        if ($fieldtype == 12) {
            return db::get_all("SELECT id, rel_x, rel_y, player_name, field_maps_id  FROM p_villages WHERE image_num=:num AND is_oasis=1", array(
                'num' => $fieldtype
            ));
        } else {
            return db::get_all("SELECT id, rel_x, rel_y, player_name, field_maps_id  FROM p_villages WHERE field_maps_id=:fieldtype ", array(
                'fieldtype' => $fieldtype
            ));
        }
    }
}

?>
<?php
require_once MODELS_DIR.'Battle.php';
class Battles_Newvillagebattle_Model extends Battle_Model
{
    public function handleCreateNewVillage( $taskRow, $toVillageRow, $fromVillageRow, $cropConsumption )
    {
        $GameMetadata = $GLOBALS['GameMetadata'];
        $SetupMetadata = $GLOBALS['SetupMetadata'];
        if ( intval(db::get_field("SELECT p.id FROM p_players p WHERE p.id=:id", array('id' => intval($fromVillageRow['player_id'])))) == 0 )
        {
            return FALSE;
        }
        $villageName = new_village_name;
        $update_key = substr( md5( $fromVillageRow['player_id'].$fromVillageRow['tribe_id'].$toVillageRow['id'].$fromVillageRow['player_name'].$villageName ), 2, 5 );
        $field_map_id = $toVillageRow['field_maps_id'];
        $buildings = "";
        foreach ( $SetupMetadata['field_maps'][$field_map_id] as $v )
        {
            if ( $buildings != "" )
            {
                $buildings .= ",";
            }
            $buildings .= sprintf( "%s 0 0", $v );
        }
        $k = 19;
        while ( $k <= 40 )
        {
            $buildings .= $k == 26 ? ",15 1 0" : ",0 0 0";
            ++$k;
        }
        $resources = "";
        $farr = explode( "-", $SetupMetadata['field_maps_summary'][$field_map_id] );
        $i = 1;
        $_c = sizeof( $farr );
        while ( $i <= $_c )
        {
            if ( $resources != "" )
            {
                $resources .= ",";
            }
            $resources .= sprintf( "%s 1300 1500 1500 %s 0", $i, $farr[$i - 1] * 2 * $GameMetadata['game_speed'] );
            ++$i;
        }
        $troops_training = "";
        $troops_num = "";
        foreach ( $GameMetadata['troops'] as $k => $v )
        {
            if ( $v['for_tribe_id'] == 0 - 1 || $v['for_tribe_id'] == $fromVillageRow['tribe_id'] )
            {
                if ( $troops_training != "" )
                {
                    $troops_training .= ",";
                }
                $researching_done = $v['research_time_consume'] == 0 ? 1 : 0;
                $troops_training .= $k." ".$researching_done." 0 0";
                if ( $troops_num != "" )
                {
                    $troops_num .= ",";
                }
                $troops_num .= $k." 0";
            }
        }
        $troops_num = "-1:".$troops_num;
        db::query("UPDATE p_villages v SET v.parent_id=:v1,v.tribe_id=:v2,v.player_id=:v3,v.alliance_id=:v4,v.player_name=:v5,v.village_name=:v6,v.alliance_name=:v7,v.is_capital=0,v.buildings=:v8,v.resources=:v9,v.cp='0 2',v.troops_training=:v10,v.troops_num=:v11,v.update_key=:v12,v.creation_date=NOW(),v.last_update_date=NOW() WHERE v.id=:v13", array(
            'v1' => intval( $fromVillageRow['id'] ),
            'v2' => intval( $fromVillageRow['tribe_id'] ),
            'v3' => intval( $fromVillageRow['player_id'] ),
            'v4' => 0 < intval( $fromVillageRow['alliance_id'] ) ? intval( $fromVillageRow['alliance_id'] ) : NULL,
            'v5' => $fromVillageRow['player_name'],
            'v6' => $villageName,
            'v7' => $fromVillageRow['alliance_name'],
            'v8' => $buildings,
            'v9' => $resources,
            'v10' => $troops_training,
            'v11' => $troops_num,
            'v12' => $update_key,
            'v13' => intval( $toVillageRow['id'] )
        ) );
            
        $child_villages_id = trim( $fromVillageRow['child_villages_id'] );
        if ( $child_villages_id != "" )
        {
            $child_villages_id .= ",";
        }
        $child_villages_id .= $toVillageRow['id'];
        db::query( "UPDATE p_villages  SET crop_consumption=crop_consumption-:crop, child_villages_id=:cvid WHERE id=:id", array(
            'crop' => $cropConsumption,
            'cvid' => $child_villages_id,
            'id' => intval( $fromVillageRow['id'] )
        ) );
        $prow = db::get_row( "SELECT p.villages_id, p.villages_data FROM p_players p WHERE p.id=:id", array(
           'id' => intval( $fromVillageRow['player_id'] )
        ) );
        $villages_id = trim( $prow['villages_id'] );
        if ( $villages_id != "" )
        {
            $villages_id .= ",";
        }
        $villages_id .= $toVillageRow['id'];
        $villages_data = trim( $prow['villages_data'] );
        if ( $villages_data != "" )
        {
            $villages_data .= "\n";
        }
        $villages_data .= $toVillageRow['id']." ".$toVillageRow['rel_x']." ".$toVillageRow['rel_y']." ".$villageName;
        db::query( "UPDATE p_players SET total_people_count=total_people_count+2, villages_count=villages_count+1, selected_village_id=:svid, villages_id=:vid, villages_data=:vdata, create_nvil=1 WHERE id=:id", array(
            'svid' => intval( $toVillageRow['id'] ),
            'vid' => $villages_id,
            'vdata' => $villages_data,
            'id' => intval( $fromVillageRow['player_id'] )
        ) );
        return FALSE;
    }
}

?>
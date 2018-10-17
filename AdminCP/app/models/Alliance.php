<?php
class Alliance_Model extends Model
{
    public function GetAllianceDataById($ID)
    {
        return db::get_row("SELECT * FROM p_alliances  WHERE id=:id", array(
            'id' => $ID
        ));
    }

    public function UpdateAlliance($name, $name2, $creator_player_id, $max_player_count, $description1, $description2, $attack_points, $defense_points, $week_attack_points, $week_defense_points, $week_dev_points, $week_thief_points)
    {
        db::query('UPDATE p_alliances p SET p.name=:a, p.name2=:b, p.creator_player_id=:c, p.max_player_count=:d, p.description1=:e, p.description2=:f, p.attack_points=:g, p.defense_points=:h, p.week_attack_points=:i, p.week_defense_points=:j, p.week_dev_points=:k, p.week_thief_points=:l WHERE p.name=:a',array(
            'a' => $name,
            'b' => $name2,
            'c' => $creator_player_id,
            'd' => $max_player_count,
            'e' => $description1,
            'f' => $description2,
            'g' => $attack_points,
            'h' => $defense_points,
            'i' => $week_attack_points,
            'j' => $week_defense_points,
            'k' => $week_dev_points,
            'l' => $week_thief_points
        ));
    }
}
?>
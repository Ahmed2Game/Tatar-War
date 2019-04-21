<?php

class Block_Model extends Model
{
    public function UpdatePlayerPainTime($playername, $time, $reason)
    {
        return db::count("UPDATE p_players p SET p.blocked_time=(NOW() + INTERVAL :se SECOND), p.blocked_reason=:blocked_reason WHERE p.name=:name", array(
            'se' => $time,
            'blocked_reason' => $reason,
            'name' => $playername
        ));
    }

    public function getBlockPlayerPyName($name)
    {
        return db::get_row("SELECT p.name, TIMESTAMPDIFF(HOUR, NOW(), p.blocked_time) blocked_hour, p.blocked_reason FROM p_players p WHERE p.name=:name", array(
            'name' => $name
        ));
    }
}

?>
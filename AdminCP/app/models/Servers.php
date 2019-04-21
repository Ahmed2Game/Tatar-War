<?php

class Servers_Model extends Model
{

    public function CreateNewServer($settings, $troops, $plus)
    {
        db2::query("INSERT INTO servers (settings, plus, troop, start_date)
            VALUES(:settings,:plus,:troop,NOW())",
            array(
                'settings' => $settings,
                'plus' => $plus,
                'troop' => $troops
            ));
        return db2::get_field("SELECT LAST_INSERT_ID() FROM servers");
    }

    public function ServersList()
    {
        return db2::get_all("SELECT id, players_count, start_date FROM servers");

    }

    public function Serverdata($id)
    {
        return db2::get_row("SELECT settings, plus, troop, start_date FROM servers WHERE id=:id", array(
            'id' => $id
        ));

    }

    public function GetSettings($name)
    {
        return db2::get_field("SELECT value FROM settings WHERE name=:name", array(
            'name' => $name
        ));

    }

    public function UpdateSetting($name, $value)
    {
        return db2::query("UPDATE settings SET value=:value WHERE name=:name", array(
            'value' => $value,
            'name' => $name
        ));

    }

    public function UpdateSettings($id, $settings)
    {
        return db2::query("UPDATE servers SET settings=:set WHERE id=:id", array(
            'set' => $settings,
            'id' => $id
        ));

    }

    public function UpdateTroop($id, $troop)
    {
        return db2::query("UPDATE servers SET troop=:troop WHERE id=:id", array(
            'troop' => $troop,
            'id' => $id
        ));

    }

    public function UpdatePlus($id, $plus)
    {
        return db2::query("UPDATE servers SET plus=:plus WHERE id=:id", array(
            'plus' => $plus,
            'id' => $id
        ));

    }

    public function restServer($time)
    {
        db::query("UPDATE g_settings SET game_over=1");
        db::query("INSERT INTO p_queue (proc_type, threads, end_date, execution_time)
            VALUES(:type,1,(NOW() + INTERVAL :time SECOND),:time)",
            array(
                'type' => QS_SITE_RESET,
                'time' => $time
            ));
    }


}

?>
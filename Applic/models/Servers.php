<?php

class Servers_Model extends Model
{
    public function ServersList()
    {
        return db2::get_all("SELECT id, players_count, settings, start_date FROM servers");

    }

    public function Serverdata($id)
    {
        return db2::get_row("SELECT players_count, settings, plus, troop, start_date FROM servers WHERE id=:id", array(
            'id' => $id
        ));

    }

    public function GetSettings($name)
    {
        return db2::get_field("SELECT value FROM settings WHERE name=:name", array(
            'name' => $name
        ));

    }

    public function GetPackage($id)
    {
        return db2::get_row("SELECT * FROM packages WHERE id=:id", array(
            'id' => $id
        ));
    }

    public function GetPackages()
    {
        return db2::get_all("SELECT * FROM packages");
    }

}

?>
<?php

class Payment_Model extends Model
{

    public function getPlayerDataByName($playerName)
    {
        return db2::get_field("SELECT p.id FROM p_players p WHERE p.name=:playername", array(
            'playername' => $playerName
        ));
    }

    public function PayhisByType($type, $pageIndex, $pageSize)
    {
        $pageindexsize = $pageIndex * $pageSize;
        return db2::get_all("SELECT m.*, DATE_FORMAT(m.time, '%y/%m/%d %H:%i:%s') mdate, TIMESTAMPDIFF(SECOND, m.time, NOW()) second FROM money_log m WHERE m.status=:type ORDER BY second ASC LIMIT $pageindexsize,$pageSize ",
            array(
                'type' => $type,
            ));
    }

    public function PayhisPyTransid($transID)
    {
        return db2::get_all("SELECT m.*, DATE_FORMAT(m.time, '%y/%m/%d %H:%i:%s') mdate, TIMESTAMPDIFF(SECOND, m.time, NOW()) second FROM money_log m WHERE m.transID=:transID", array(
            'transID' => $transID
        ));
    }

    public function PayhisPyName($usernam)
    {
        return db2::get_all("SELECT m.*, DATE_FORMAT(m.time, '%y/%m/%d %H:%i:%s') mdate, TIMESTAMPDIFF(SECOND, m.time, NOW()) second FROM money_log m WHERE m.usernam=:usernam", array(
            'usernam' => $usernam
        ));
    }

    public function PayhisListByType($type)
    {
        return db2::get_field("SELECT COUNT(*) FROM money_log WHERE status=:type", array(
            'type' => $type
        ));
    }

    public function CreatePackage($name, $gold, $cost, $bonus, $image)
    {
        db2::query("INSERT INTO packages (name, gold, cost, bonus, image) VALUES(:name,:gold,:cost,:bonus,:image)", array(
            'name' => $name,
            'gold' => $gold,
            'cost' => $cost,
            'bonus' => $bonus,
            'image' => $image
        ));
    }

    public function SetPackage($id, $name, $gold, $cost, $bonus, $image)
    {
        db2::query("UPDATE packages SET name=:name, gold=:gold, cost=:cost, bonus=:bonus, image=:image WHERE id=:id", array(
            'id' => $id,
            'name' => $name,
            'gold' => $gold,
            'cost' => $cost,
            'bonus' => $bonus,
            'image' => $image
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
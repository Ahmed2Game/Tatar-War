<?php

class Plus_Model extends Model
{

    public function InviteBy($id)
    {
        return db::get_all("SELECT p.id, p.name, p.ip_his, p.total_people_count, p.villages_count, p.invite_by, p.show_ref FROM p_players p WHERE p.invite_by=:id", array('id' => $id));
    }

    public function incrementPlayerGold($playerId, $invintGold)
    {
        db2::query("UPDATE p_players SET gold_num=gold_num+:invintGold WHERE id=:playerId", array(
            'invintGold' => $invintGold,
            'playerId' => $playerId
        ));
    }

    public function PlayerRef($RefId)
    {
        db::query("UPDATE p_players SET show_ref=1 WHERE id=:RefId", array(
            'RefId' => $RefId
        ));
    }

    public function getPlayerDataById($playerId)
    {
        $data = db::get_row("SELECT p.ip_his, p.total_people_count FROM p_players p WHERE p.id=:playerId", array(
            'playerId' => $playerId
        ));

        $data2 = db2::get_row("SELECT p.pwd FROM p_players p WHERE p.id=:playerId", array(
            'playerId' => $playerId
        ));
        return array_merge($data, $data2);
    }

    public function getPlayerDataByName($playerName)
    {
        return db::get_field("SELECT p.id FROM p_players p WHERE p.name=:playerName", array(
            'playerName' => $playerName
        ));
    }

    public function DeletPlayerGold($playerId, $GoldNum)
    {
        db2::query("UPDATE p_players SET gold_num=gold_num-:GoldNum, gold_buy=gold_buy-:GoldNum WHERE id=:playerId", array(
            'GoldNum' => $GoldNum,
            'playerId' => $playerId
        ));
    }

    public function DeletPlayerGold2($playerId, $GoldNum)
    {
        db2::query("UPDATE p_players SET gold_num=gold_num-:GoldNum WHERE id=:playerId", array(
            'GoldNum' => $GoldNum,
            'playerId' => $playerId
        ));
    }

    public function GivePlayerGold($playerName, $GoldNum)
    {
        db2::query("UPDATE p_players SET gold_num=gold_num+:GoldNum, gold_buy=gold_buy+:GoldNum WHERE name=:playerName", array(
            'GoldNum' => $GoldNum,
            'playerName' => $playerName
        ));
    }

    public function InsertGoldTransLog($from_player, $to_player, $goldNumber)
    {
        db2::query("INSERT INTO gold_trans (from_player, to_player, trans_date,gold)
            VALUES (:from_player,:to_player, NOW(), :goldNumber)",
            array(
                'from_player' => $from_player,
                'to_player' => $to_player,
                'goldNumber' => $goldNumber
            ));
    }

    public function goldTransPyName($from_player)
    {
        return db2::get_field("SELECT COUNT(*) FROM gold_trans WHERE from_player=:from_player OR to_player=:from_player", array(
            'from_player' => $from_player
        ));
    }

    public function GoldTranshis($playerName, $pageIndex, $pageSize)
    {
        $pageindexsize = $pageIndex * $pageSize;
        return db2::get_all("SELECT g.*, DATE_FORMAT(g.trans_date, '%y/%m/%d %H:%i:%s') gdate, TIMESTAMPDIFF(SECOND, g.trans_date, NOW()) second FROM gold_trans g WHERE g.from_player=:playerName OR g.to_player=:playerName ORDER BY second ASC LIMIT $pageindexsize,$pageSize ",
            array('playerName' => $playerName));
    }

    public function PayhisByplayerName($playerName, $pageIndex, $pageSize)
    {
        $pageindexsize = $pageIndex * $pageSize;
        return db2::get_all("SELECT m.*, DATE_FORMAT(m.time, '%y/%m/%d %H:%i:%s') mdate, TIMESTAMPDIFF(SECOND, m.time, NOW()) second FROM money_log m WHERE m.usernam=:playerName AND m.status=1 ORDER BY second ASC LIMIT $pageindexsize,$pageSize ",
            array(
                'playerName' => $playerName,
            ));
    }

    public function PayhisListByplayerName($playerName)
    {
        return db2::get_field("SELECT COUNT(*) FROM money_log WHERE usernam=:usernam", array(
            'usernam' => $playerName
        ));
    }

}

?>
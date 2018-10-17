<?php
require_once LIBRARY_DIR.'Model.php';
class Payment_Model extends Model
{

    public function getPlayerDataById( $playerId )
    {
        return db2::get_field("SELECT p.name FROM p_players p WHERE p.id=:playerId", array(
            'playerId' => $playerId
        ) );
    }

    public function incrementPlayerGold( $playerName, $goldNumber )
    {
        db2::query("UPDATE p_players p SET p.gold_num=p.gold_num+:goldNumber, p.gold_buy=p.gold_buy+:goldNumber WHERE p.name=:name", array(
            'goldNumber' => $goldNumber,
            'name' => $playerName
        ) );
    }

    public function cutPlayerGold( $playerId, $goldNumber )
    {
        db2::query("UPDATE p_players p SET p.gold_num=gold_num-:goldNumber, p.gold_buy=p.gold_buy+:goldNumber WHERE p.id=:playerId", array(
            'goldNumber' => $goldNumber,
            'playerId'  => $playerId
        ));
    }

    public function cutNameGold( $name, $goldNumber )
    {
        db2::query("UPDATE p_players p SET p.gold_num=gold_num-:goldNumber, p.gold_buy=p.gold_buy+:goldNumber WHERE p.name=:name", array(
            'goldNumber' => $goldNumber,
            'name'  => $name
        ));
    }

    public function upMoneyLog( $id, $transID, $status )
    {
        db2::query("UPDATE money_log m SET m.transID=:transID, m.status=:st WHERE m.id=:id", array(
            'transID' => $transID,
            'st' => $status,
            'id'  => $id
        ));
    }
    public function SetType( $id, $type )
    {
        db2::query("UPDATE money_log m SET m.type=:type WHERE m.id=:id", array(
            'type' => $type,
            'id'  => $id
        ));
    }

    public function getMonaydata( $id )
    {
        return db2::get_row( "SELECT * FROM money_log WHERE id=:id", array(
                'id' => $id
        ) );
    }

    public function InsertMoneyLog( $transID, $usernam, $goldNumber, $cost, $currency, $type, $status = 1 )
    {
        db2::query("INSERT INTO money_log (transID, usernam, golds, money, currency, time,type,status)
            VALUES(:transID,:usernam,:goldNumber,:cost,:currency,NOW(),:type,:status)",
            array(
                    'transID'       => $transID,
                    'usernam'       => $usernam,
                    'goldNumber'    => $goldNumber,
                    'cost'          => $cost,
                    'currency'      => $currency,
                    'type'          => $type,
                    'status'        => $status
                ));
        $id = db2::get_field( "SELECT LAST_INSERT_ID() FROM money_log" );
        return $id;
    }

    public function deleteMoneyLog($transID)
    {
        db2::query(" DELETE FROM money_log WHERE transID=:transID", array(
            'transID' => $transID
        ));
    }
    

}

?>
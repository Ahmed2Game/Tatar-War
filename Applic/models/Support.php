<?php
class Support_Model extends Model
{
    public function GetMasegesPyplayerId($playerId, $status)
    {
        return db2::get_all("SELECT s.id, s.title, s.status, s.added_time FROM support_tickets s WHERE s.server_id=:serv AND s.player_id=:id AND s.status$status",array(
            'serv' => $_COOKIE['server'],
            'id' => $playerId
        ));
    }

    public function GetMasegePyId($playerId, $id)
    {
        return db2::get_row("SELECT s.id, s.title, s.content, s.status, s.added_time FROM support_tickets s WHERE s.player_id=:pid AND s.id=:id",array(
            'id' => $id,
            'pid' => $playerId
        ));
    }

    public function GetReplaiesPyMasegeId($id)
    {
        return db2::get_all("SELECT r.is_player, r.replay, r.added_time FROM support_tickets_replaies r WHERE r.ticket_id=:id",array(
            'id' => $id
        ));
    }

    public function sendMasege($playerId, $title, $content, $type)
    {
        db2::query("INSERT INTO support_tickets SET player_id=:pid, server_id=:serv, title=:ti, content=:co, type=:ty, status=0, added_time=NOW()",array(
            'serv' => $_COOKIE['server'],
            'pid' => $playerId,
            'ti' => $title,
            'co' => $content,
            'ty' => $type
        ));
    }

    public function sendReply($ticket_id, $replay)
    {
        db2::query("INSERT INTO support_tickets_replaies SET ticket_id=:tid, is_player=1, replay=:re, added_time=NOW()",array(
            'tid' => $ticket_id,
            're' => $replay
        ));
    }

    public function updateStatus($ticket_id, $status)
    {
        db2::query("UPDATE support_tickets s SET s.status=:st WHERE s.id=:ti",array(
            'st' => $status,
            'ti' => $ticket_id
        ));
    }
}
?>
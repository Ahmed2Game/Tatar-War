<?php

class Message_Model extends Model
{

    public $maxMailBoxSize = 50;

    public function getPlayerIdByName($playerName)
    {
        return db::get_row("SELECT p.id, p.block_player_id FROM p_players p WHERE p.name=:playerName", array(
            'playerName' => $playerName
        ));
    }

    public function getPlayerNameById($playerId)
    {
        return db::get_field("SELECT p.name FROM p_players p WHERE p.id=:playerId", array(
            'playerId' => $playerId
        ));
    }

    public function getMessageListCount($playerId, $inbox)
    {
        return db::get_field($inbox ? "SELECT COUNT(*) FROM p_msgs m WHERE m.to_player_id=:playerId AND m.delete_status!=1" : "SELECT COUNT(*) FROM p_msgs m WHERE m.from_player_id=:playerId AND m.delete_status!=2", array(
            'playerId' => $playerId
        ));
    }

    public function getMessageList($playerId, $inbox, $pageIndex, $pageSize)
    {
        $pageindexsize = $pageIndex * $pageSize;
        return db::get_all($inbox ? "SELECT m.id,m.from_player_id uid,m.from_player_name uname,m.msg_title,m.msg_body,m.is_readed,DATE_FORMAT(m.creation_date, '%y/%m/%d %H:%i') mdate FROM p_msgs m WHERE m.to_player_id=:playerId AND m.delete_status!=1 ORDER BY m.creation_date DESC LIMIT $pageindexsize,$pageSize" : "SELECT m.id,m.to_player_id uid,m.to_player_name uname,m.msg_title,m.msg_body,m.is_readed,DATE_FORMAT(m.creation_date, '%y/%m/%d %H:%i') mdate FROM p_msgs m WHERE m.from_player_id=:playerId AND m.delete_status!=2 ORDER BY m.creation_date DESC LIMIT $pageindexsize,$pageSize", array(
            'playerId' => $playerId
        ));
    }

    public function getMessage($playerId, $messageId)
    {
        return db::get_row("SELECT m.from_player_id,m.to_player_id,m.from_player_name,m.to_player_name,m.msg_title,m.msg_body,m.is_readed,m.delete_status,DATE_FORMAT(m.creation_date, '%y/%m/%d') mdate,DATE_FORMAT(m.creation_date, '%H:%i:%s') mtime FROM p_msgs m WHERE m.id=:messageId AND (m.from_player_id=:playerId OR m.to_player_id=:playerId)AND IF(m.to_player_id=:playerId, m.delete_status!=1, m.delete_status!=2)", array(
            'messageId' => $messageId,
            'playerId' => $playerId,
        ));
    }

    public function getMessageAdmin($messageId)
    {
        return db::get_row("SELECT m.from_player_id,m.to_player_id,m.from_player_name,m.to_player_name,m.msg_title,m.msg_body,m.is_readed,m.delete_status,DATE_FORMAT(m.creation_date, '%y/%m/%d') mdate,DATE_FORMAT(m.creation_date, '%H:%i:%s') mtime FROM p_msgs m WHERE m.id=:messageId", array(
            'messageId' => $messageId
        ));
    }

    public function _getSafeMessage($playerId, $messageId)
    {
        return db::get_row("SELECT m.to_player_id,m.is_readed,m.delete_status FROM p_msgs m WHERE m.id=:messageId AND (m.from_player_id=:playerId OR m.to_player_id=:playerId)", array(
            'messageId' => $messageId,
            'playerId' => $playerId
        ));
    }

    public function deleteMessage($playerId, $messageId)
    {
        $result = $this->_getSafeMessage($playerId, $messageId);
        if (!$result) {
            return FALSE;
        }
        $deleteStatus = $result['delete_status'];
        $toPlayerId = $result['to_player_id'];
        $isReaded = $result['is_readed'];

        if ($deleteStatus != 0) {
            db::query("DELETE FROM p_msgs WHERE id=:messageId AND (from_player_id=:playerId OR to_player_id=:playerId)", array(
                'messageId' => $messageId,
                'playerId' => $playerId
            ));
        } else {
            db::query("UPDATE p_msgs m SET m.delete_status=:toPlayerId WHERE m.id=:messageId AND (m.from_player_id=:playerId OR m.to_player_id=:playerId)", array(
                'toPlayerId' => $toPlayerId == $playerId ? 1 : 2,
                'messageId' => $messageId,
                'playerId' => $playerId
            ));
        }
        if (!$isReaded && $toPlayerId == $playerId) {
            $this->changeUnReadedMessages($playerId, 0 - 1);
            return TRUE;
        }
        return FALSE;
    }

    public function markMessageAsReaded($playerId, $messageId)
    {
        db::query("UPDATE p_msgs m SET m.is_readed=1 WHERE m.id=:messageId", array(
            'messageId' => $messageId
        ));
        $this->changeUnReadedMessages($playerId, 0 - 1);
    }

    public function sendMessage($fromPlayerId, $fromPlayerName, $toPlayerId, $toPlayerName, $subject, $body)
    {
        db::query("INSERT INTO p_msgs (from_player_id,to_player_id,from_player_name,to_player_name,msg_title,msg_body,creation_date,is_readed)
		    VALUES(:fromPlayerId,:toPlayerId,:fromPlayerName,:toPlayerName,:subject,:body,NOW(),0)",
            array(
                'fromPlayerId' => $fromPlayerId,
                'toPlayerId' => $toPlayerId,
                'fromPlayerName' => $fromPlayerName,
                'toPlayerName' => $toPlayerName,
                'subject' => $subject,
                'body' => $body
            ));

        $messageId = db::get_field("SELECT LAST_INSERT_ID() FROM p_msgs");
        db::query("DELETE FROM p_msgs WHERE TIMESTAMPDIFF(SECOND, creation_date, NOW()) >= 172800");
        $this->changeUnReadedMessages($toPlayerId, 1);
        while (0 < ($mid = db::get_field("SELECT MIN(m.id) id FROM p_msgs m WHERE m.delete_status!=2 AND m.from_player_id=:fromPlayerId GROUP BY m.from_player_id HAVING COUNT(*)>:maxMailBoxSize", array(
                'fromPlayerId' => $fromPlayerId,
                'maxMailBoxSize' => $this->maxMailBoxSize
            )))) {
            $this->deleteMessage($fromPlayerId, $mid);
        }
        return $messageId;
    }

    public function changeUnReadedMessages($playerId, $offset)
    {
        db::query("UPDATE p_players p SET p.new_mail_count=IF((p.new_mail_count+:offset)<0, 0, p.new_mail_count+:offset) WHERE p.id=:playerId", array(
            'offset' => $offset,
            'playerId' => $playerId
        ));
    }

    public function getAlliancePlayersId($allianceId)
    {
        return db::get_field("SELECT a.players_ids FROM p_alliances a WHERE a.id=:allianceId", array(
            'allianceId' => $allianceId
        ));
    }

    public function syncMessages($playerId)
    {
        $newCount = intval(db::get_field("SELECT COUNT(*) FROM p_msgs m WHERE m.to_player_id=:playerId AND m.is_readed=0 AND m.delete_status!=1", array(
            'playerId' => $playerId
        )));

        if ($newCount < 0) {
            $newCount = 0;
        }
        db::query("UPDATE p_players p SET p.new_mail_count=:newCount WHERE p.id=:playerId", array(
            'newCount' => $newCount,
            'playerId' => $playerId
        ));
        return $newCount;
    }

    public function changePlayerNotes($playerId, $notes)
    {
        db::query("UPDATE p_players p SET p.notes=:notes WHERE p.id=:playerId", array(
            'notes' => $notes,
            'playerId' => $playerId
        ));
    }

}

?>
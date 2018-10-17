<?php
class Support_Model extends Model
{
    public $maxMailBoxSize = 50;

    public function get_all($status = 'all')
    {
        load_core('Auth', '');
        $Auth = new Auth();

        $sessioninfo = $Auth->sessioninfo($_COOKIE['auth_session']);
        $user = $Auth->getOne($sessioninfo['uid']);
        $permissions = json_decode($user['permissions'], true);

        $allowed_cats = '';

        if(is_null($permissions))
        {
            $allowed_cats = "'1','2','3','4'";
        }
        else
        {
            $permissions['support']['cat_1'] == '1' ? $allowed_cats.= '1,' : '';
            $permissions['support']['cat_2'] == '1' ? $allowed_cats.= '2,' : '';
            $permissions['support']['cat_3'] == '1' ? $allowed_cats.= '3,' : '';
            $permissions['support']['cat_4'] == '1' ? $allowed_cats.= '4,' : '';
            $allowed_cats = substr($allowed_cats, 0, -1);
        }

        if($status == 'all')
        {
            $url = 'support?page=show';
            $total =  db2::count("SELECT support_tickets.*,p_players.name as username
                FROM support_tickets join p_players on support_tickets.player_id=p_players.id
                WHERE support_tickets.type IN($allowed_cats) AND support_tickets.status IN('0','2') ORDER BY id DESC "
            );
        }
        else
        {
            $url = 'support?page=show&status='.$status;
            $total =  db2::count("SELECT support_tickets.*,p_players.name as username
                FROM support_tickets join p_players on support_tickets.player_id=p_players.id WHERE support_tickets.status=:status
                AND support_tickets.type IN($allowed_cats) ORDER BY id DESC ",
                array('status' => $status)
            );
        }

        // How many items to list per page
        $limit = 20;

        // How many pages will there be
        $pages = ceil($total / $limit);

        // What page are we currently on?
        $page = min($pages, filter_input(INPUT_GET, 'p', FILTER_VALIDATE_INT, array(
            'options' => array(
                'default'   => 1,
                'min_range' => 1,
            ),
        )));

        // Calculate the offset for the query
        $offset = abs($page - 1)  * $limit;

        // Some information to display to the user
        $start = $offset + 1;
        $end = min(($offset + $limit), $total);

        // The "back" link
        $prevlink = ($page > 1) ? '<li class="previous"><a href="'.$url.'&p=1" title="الصفحة الاولى">&laquo;</a></li> <li class="previous"><a href="'.$url.'&p=' . ($page - 1) . '" title="الصفحة السابقة">&lsaquo;</a></li>' : '<li class="disabled"><a href="#">&laquo;</a></li> <li class="disabled"><a href="#">&lsaquo;</a></li>';

        // The "forward" link
        $nextlink = ($page < $pages) ? '<li class="next"><a href="'.$url.'&p=' . ($page + 1) . '" title="الصفحة التالية">&rsaquo;</a></li> <li class="next"> <a href="'.$url.'&p=' . $pages . '" title="الصفحة الاخيرة">&raquo;</a></li>' : '<li class="disabled"><a href="#">&rsaquo;</a></li> <li class="disabled"><a href="#">&raquo;</a></li>';

        // Display the paging information
        $pagination = '<ul class="pager">'. $prevlink. ' <li> <span>الصفحة '. $page. ' من '. $pages. ' صفحات, عرض '. $start. '-'. $end. ' من '. $total. ' نتائج </span></li>'. $nextlink. '</ul>';


        if($status == 'all')
        {
            $total_query =  db2::get_all("SELECT support_tickets.*,p_players.name as username
                FROM support_tickets join p_players on support_tickets.player_id=p_players.id
                WHERE support_tickets.type IN($allowed_cats) AND support_tickets.status IN('0','2') ORDER BY id DESC
                LIMIT $limit OFFSET $offset"
            );
        }
        else
        {
            $total_query =  db2::get_all("SELECT support_tickets.*,p_players.name as username
                FROM support_tickets join p_players on support_tickets.player_id=p_players.id WHERE support_tickets.status=:status
                AND support_tickets.type IN($allowed_cats) ORDER BY id DESC LIMIT $limit OFFSET $offset",
                array('status' => $status));
        }

        // Do we have any results?
        if (db2::count() > 0) {
            return array('results' => $total_query, 'pagination' => $pagination);
        } else {
            return FALSE;
        }
    }

    public function get_one($id)
    {
        return db2::get_row("SELECT support_tickets.*,p_players.name as username FROM support_tickets
            join p_players on support_tickets.player_id=p_players.id where support_tickets.id = :id ", array('id' => $id));
    }


    public function delete($id)
    {
        db2::query("DELETE FROM support_tickets WHERE id = :ticket_id" , array('ticket_id' => $id));
        //delete replaies
        db2::query("DELETE FROM support_tickets_replaies WHERE ticket_id = :ticket_id" , array('ticket_id' => $id));
    }

    public function get_replaies($ticket_id)
    {
        return db2::get_all("SELECT * FROM support_tickets_replaies WHERE ticket_id=:ticket_id ORDER BY id ASC",
            array('ticket_id' => $ticket_id));
    }


    public function change_status_and_type($status, $type, $ticket_id)
    {
        // change ticket status
        db2::query("UPDATE support_tickets SET status =:status, type =:type WHERE id = :ticket_id" ,
            array(
                    'status' => $status,
                    'type' => $type,
                    'ticket_id' => $ticket_id
                )
            );
    }


    public function add_replay($replay, $sender_name, $ticket_id, $replaier_id, $replaier_name)
    {
        // insert new replay
        db2::query("INSERT INTO support_tickets_replaies (ticket_id, replaier_id, replaier_name, is_player, replay, added_time)
            VALUES (:ticket_id, :replaier_id, :replaier_name, :is_player, :replay, NOW())",
            array(
                    'ticket_id' => $ticket_id,
                    'replaier_id' => $replaier_id,
                    'replaier_name' => $replaier_name,
                    'is_player' => 0,
                    'replay' => $replay
                ));

        // change ticket status
        db2::query("UPDATE support_tickets SET status = 1 WHERE id = :ticket_id" , array('ticket_id' => $ticket_id));

        // send message to user to annonce that the ticket was replaied
        $ticket = $this->get_one($ticket_id);

        $this->sendMessage(0, 'النظام', $ticket['player_id'],
            $ticket['username'], 'رد على طلبك للدعم',
            'تم الرد على طلبك للدعم بعنوان "'.$ticket['title'].'" برجاء الذهاب لصفحة مراسلة الدعم ومتابعة طلبك');
    }


    public function sendMessage( $fromPlayerId, $fromPlayerName, $toPlayerId, $toPlayerName, $subject, $body )
    {
        db::query("INSERT INTO p_msgs (from_player_id,to_player_id,from_player_name,to_player_name,msg_title,msg_body,creation_date,is_readed)
            VALUES(:fromPlayerId,:toPlayerId,:fromPlayerName,:toPlayerName,:subject,:body,NOW(),0)",
            array(
                'fromPlayerId'      => $fromPlayerId,
                'toPlayerId'        => $toPlayerId,
                'fromPlayerName'    => $fromPlayerName,
                'toPlayerName'      => $toPlayerName,
                'subject'           => $subject,
                'body'              => $body
            ) );

        $messageId = db::get_field( "SELECT LAST_INSERT_ID() FROM p_msgs" );
        $this->changeUnReadedMessages( $toPlayerId, 1 );
        while ( 0 < ( $mid = db::get_field("SELECT MIN(m.id) id FROM p_msgs m WHERE m.delete_status!=2 AND m.from_player_id=:fromPlayerId GROUP BY m.from_player_id HAVING COUNT(*)>:maxMailBoxSize", array(
            'fromPlayerId'      => $fromPlayerId,
            'maxMailBoxSize'    => $this->maxMailBoxSize
        ) ) ) )
        {
            $this->deleteMessage( $fromPlayerId, $mid );
        }
        return $messageId;
    }

    public function changeUnReadedMessages( $playerId, $offset )
    {
        db::query( "UPDATE p_players p SET p.new_mail_count=IF((p.new_mail_count+$offset)<0, 0, p.new_mail_count+$offset) WHERE p.id=:playerId", array(
            'playerId'  => $playerId
        ) );
    }


    public function deleteMessage( $playerId, $messageId )
    {
        $result = $this->_getSafeMessage( $playerId, $messageId );
        if (!$result)
        {
            return FALSE;
        }
        $deleteStatus = $result['delete_status'];
        $toPlayerId = $result['to_player_id'];
        $isReaded = $result['is_readed'];

        if ( $deleteStatus != 0 )
        {
            db::query("DELETE FROM p_msgs WHERE id=:messageId AND (from_player_id=:playerId OR to_player_id=:playerId)", array(
                'messageId' => $messageId,
                'playerId'  => $playerId
            ) );
        }
        else
        {
            db::query("UPDATE p_msgs m SET m.delete_status=:toPlayerId WHERE m.id=:messageId AND (m.from_player_id=:playerId OR m.to_player_id=:playerId)", array(
                'toPlayerId' => $toPlayerId == $playerId ? 1 : 2,
                'messageId'  => $messageId,
                'playerId'   => $playerId
            ) );
        }
        if ( !$isReaded && $toPlayerId == $playerId )
        {
            $this->changeUnReadedMessages( $playerId, 0 - 1 );
            return TRUE;
        }
        return FALSE;
    }

    public function _getSafeMessage( $playerId, $messageId )
    {
        return db::get_row("SELECT m.to_player_id,m.is_readed,m.delete_status FROM p_msgs m WHERE m.id=:messageId AND (m.from_player_id=:playerId OR m.to_player_id=:playerId)", array(
            'messageId' => $messageId,
            'playerId'  => $playerId
        ) );
    }


    public function delete_comment($id)
    {
        db2::query("DELETE FROM support_tickets_replaies WHERE id = :replay_id" , array('replay_id' => $id));
    }

}
?>
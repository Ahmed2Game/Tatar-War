<?php
class Friends_Model extends Model
{
    public function SendRequest( $toid, $fromid )
    {
        db2::query("INSERT INTO friends SET toid=:tid, fromid=:fid, status=0, date=NOW()",array(
			'tid' => $toid,
			'fid' => $fromid
		));
    }
    
    public function AcceptRequest( $toid, $fromid )
    {
        db2::query("UPDATE friends SET status=1 WHERE toid=:tid AND fromid=:fid", array(
            'tid' => $toid,
			'fid' => $fromid
        ));
        db2::query("INSERT INTO friends SET toid=:tid, fromid=:fid, status=1, date=NOW()",array(
			'fid' => $toid,
			'tid' => $fromid
		));
    }
    
    public function DeleteRequest( $toid, $fromid )
    {
        db2::query("DELETE FROM friends WHERE toid=:tid AND fromid=:fid", array(
            'fid' => $toid,
			'tid' => $fromid
        ));
        db2::query("DELETE FROM friends WHERE toid=:tid AND fromid=:fid", array(
            'tid' => $toid,
			'fid' => $fromid
        ));
    }
    
    public function GetList( $fromid, $pageIndex, $pageSize )
    {
        $pageindexsize = $pageIndex * $pageSize;
        return db2::get_all("SELECT *, TIME_TO_SEC(TIMEDIFF(NOW(), date)) second FROM friends WHERE status=1 AND fromid=:fid ORDER BY second ASC LIMIT $pageindexsize,$pageSize", array(
            'fid' => $fromid
        ));
    }
    
    public function GetListcount( $fromid )
    {
        return db2::get_field("SELECT COUNT(*) FROM friends WHERE status=1 AND fromid=:fid", array(
            'fid' => $fromid
        ));
    }
    
    public function GetListcount2( $fromid )
    {
        return db2::get_field("SELECT COUNT(*) FROM friends WHERE status=0 AND fromid=:fid", array(
            'fid' => $fromid
        ));
    }
    
    public function GetListcount3( $toid )
    {
        return db2::get_field("SELECT COUNT(*) FROM friends WHERE status=0 AND toid=:tid", array(
            'tid' => $toid
        ));
    }
    
    public function GetSent( $fromid, $pageIndex, $pageSize )
    {
        $pageindexsize = $pageIndex * $pageSize;
        return db2::get_all("SELECT *, TIME_TO_SEC(TIMEDIFF(NOW(), date)) second FROM friends WHERE status=0 AND fromid=:fid ORDER BY second ASC LIMIT $pageindexsize,$pageSize", array(
            'fid' => $fromid
        ));
    }
    
    public function GetCome( $toid, $pageIndex, $pageSize )
    {
        $pageindexsize = $pageIndex * $pageSize;
        return db2::get_all("SELECT *, TIME_TO_SEC(TIMEDIFF(NOW(), date)) second FROM friends WHERE status=0 AND toid=:tid ORDER BY second ASC LIMIT $pageindexsize,$pageSize", array(
            'tid' => $toid
        ));
    }
    
    public function CancelRequest( $toid, $fromid )
    {
        db2::query("DELETE FROM friends WHERE toid=:tid AND fromid=:fid", array(
            'tid' => $toid,
			'fid' => $fromid
        ));
    }
    public function RequestNum( $fromid )
    {
        return db2::get_field( "SELECT COUNT(*) FROM friends WHERE status=0 AND fromid=:fid AND TIME_TO_SEC(TIMEDIFF(NOW(), date)) <= 86400", array(
            'fid' => $fromid
        ) );

    }
    
    public function getPlayerName( $toid )
    {
        return db2::get_field( "SELECT name FROM p_players WHERE id=:id", array(
            'id' => $toid
        ) );
    }
    
    public function getPlayerid( $name )
    {
        return db2::get_field( "SELECT id FROM p_players WHERE name=:name", array(
            'name' => $name
        ) );
    }
    
    public function GetIfsendRequest( $fromid , $toid )
    {
        return db2::get_field("SELECT COUNT(*) FROM friends WHERE status=0 AND fromid=:fid AND toid=:tid", array(
            'fid' => $fromid,
            'tid' => $toid
        ));
    }
    public function GetIfFriends( $fromid , $toid )
    {
        return db2::get_field("SELECT COUNT(*) FROM friends WHERE status=1 AND fromid=:fid AND toid=:tid", array(
            'fid' => $fromid,
            'tid' => $toid
        ));
    }
    public function GetRequestNum( $toid )
    {
        return db2::get_field("SELECT COUNT(*) FROM friends WHERE status=0 AND toid=:tid", array(
            'tid' => $toid
        ));
    }
    
    public function GetAllList( $fromid)
    {
        return db2::get_all("SELECT toid, TIME_TO_SEC(TIMEDIFF(NOW(), date)) second FROM friends WHERE status=1 AND fromid=:fid ORDER BY second", array(
            'fid' => $fromid
        ));
    }
}
?>
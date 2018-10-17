<?php
class Activate_Model extends Model
{

    public function doActivation( $code )
    {
        return db2::count("UPDATE p_players p SET p.is_active=1 WHERE p.activation_code=:s AND p.is_active=0",array(
            's' => $code
            ));
    }


    public function getPlayerData( $playerId )
    {
        return db2::get_row( "SELECT p.id, p.name, p.pwd, p.email, p.activation_code FROM p_players p WHERE p.id=:playerid AND p.is_active=0",
            array( 'playerid' => $playerId)
        );
    }
	
	 public function changePlayerEmail( $playerId, $newEmail )
    {
        if ( 0 < intval( db2::get_field( "SELECT COUNT(*) FROM p_players p WHERE p.email=:em", array(
            'em' => $newEmail
        ) ) ) )
        {
            return FALSE;
        }
        db2::query( "UPDATE p_players p SET p.email=:em, p.is_active=0 WHERE p.id=:id", array(
            'em' => $newEmail,
            'id' => $playerId
        ) );
		return TRUE;
    }

}

?>

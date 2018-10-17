<?php
class Password_Model extends Model
{

    public function PlayerDataPyEmail( $email )
    {
        return db2::get_row("SELECT p.name, md5(p.email) email, p.pwd FROM p_players p WHERE p.email=:email", array('email' => $email ) );
    }

    public function setPlayerPassword( $email, $password, $newpassword )
    {
        return db2::count("UPDATE p_players p SET p.pwd=:password WHERE md5(p.email)=:email and p.pwd=:pwd", array('password' => md5($newpassword), 'email' => $email, 'pwd' => $password) );
    }

}
?>

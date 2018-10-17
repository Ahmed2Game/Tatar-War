<?php
class Supervisors_Model extends Model
{

    public function get_all()
    {
        return db::get_all( "SELECT * FROM users");
    }

    public function get_row($userid)
    {
        db::query('SELECT * from users where id =:userid', array('userid' => $userid));
    }

    public function update ($username, $password, $email, $permissions, $userid)
    {
    	db::query("UPDATE users SET username=:username,password=:password,email=:email,permissions=:permissions WHERE id=:userid",
    		array(
    			'username' 	=> $username,
    			'password' 	=> $password,
    			'email' 	=> $email,
    			'permissions' =>$permissions,
    			'userid' => $userid)
    		);
    }

    public function delete ($userid)
    {
    	db::query("DELETE FROM users WHERE id=:userid", array('userid' => $userid));
    }

}

?>

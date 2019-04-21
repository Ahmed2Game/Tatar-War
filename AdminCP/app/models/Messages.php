<?php

class Messages_Model extends Model
{

    public function deleteMessagesByUserName($name)
    {
        return db::count('DELETE from p_msgs where from_player_name=:name', array('name' => $name));
    }

}

?>

<?php

class News_Model extends Model
{
    public function get_all($lang)
    {
        return db2::get_all("SELECT * FROM news WHERE language=:language",
            array('language' => $lang)
        );
    }

    public function get_row($id)
    {
        return db2::get_row("SELECT * FROM news WHERE id=:id",
            array('id' => $id)
        );
    }
}

?>
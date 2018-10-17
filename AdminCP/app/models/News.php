<?php

class News_Model extends Model
{

    public function get_all()
    {
        return db2::get_all( "SELECT * FROM news");
    }

    public function get_row($newsid)
    {
        return db2::get_row('SELECT * from news where id=:newsid', array('newsid' => $newsid));
    }

    public function create($subject, $message, $language)
    {
        return db2::count("INSERT INTO news (subject, message, language)
            VALUES(:subject,:message,:language)",
            array(
                'subject' 	=> $subject,
                'message' 	=> $message,
                'language' 	=> $language,
            ));
    }

    public function update ($subject, $message, $language,  $newsid)
    {
        return db2::count("UPDATE news SET subject=:subject,message=:message,language=:language  WHERE id=:newsid",
            array(
                'subject' 	=> $subject,
                'message' 	=> $message,
                'language' 	=> $language,
                'userid' => $newsid
            )
        );
    }

    public function delete ($newsid)
    {
        return db2::count("DELETE FROM news WHERE id=:newsid", array('newsid' => $newsid));
    }

}
?>
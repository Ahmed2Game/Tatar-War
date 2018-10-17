<?php

class Wordsfilter_Model extends Model
{

    public function FilterWords( $text = "", $replace = "***" )
    {
        $patterns = array( "/([A-Z0-9._%+-]+)@([A-Z0-9.-]+)\\.([A-Z]{2,4})(\\((.+?)\\))?/i", "/\\b(?:(?:https?|ftp):\\/\\/|www\\.)[-a-z0-9+&@#\\/%?=~_|!:,.;]*[-a-z0-9+&@#\\/%=~_|]/i" );
        $this->load_model('Servers', 'S');
        $badwor = explode(',', $this->S->GetSettings("bad_words"));

        foreach ( $badwor as $row )
        {
            $patterns[] = sprintf( "/(?<!\\pL)(%s)(?!\\pL)/u", $row );
        }
        unset($badwor);
        return preg_replace( $patterns, $replace, $text );
    }

}

?>

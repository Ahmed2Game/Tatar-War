<?php
class Quicktasks_Model extends Model
{
    public function UpdatePlayergold($goldnum)
    {
        db2::query("UPDATE p_players p SET p.gold_num=p.gold_num+:goldnum", array(
            'goldnum' => $goldnum
        ));
    }

    public function UpdateTruceTime($time, $reason)
    {
        db::query("UPDATE g_summary g SET g.truce_time=(NOW() + INTERVAL :se SECOND), g.truce_reason=:truce_reason", array(
            'se' => $time,
            'truce_reason' => $reason
        ));
    }

    public function GetGsummaryData()
    {
        return db::get_row( "SELECT gs.truce_reason, TIMESTAMPDIFF(HOUR, NOW(), gs.truce_time) truce_hour FROM g_summary gs ");
    }

    public function GetGsummaryData2()
    {
        return db::get_row("SELECT * FROM g_summary ");
    }

    public function UpdateGsummaryData($players_count, $active_players_count, $Arab_players_count, $Roman_players_count, $Teutonic_players_count, $Gallic_players_count)
    {
        db::query("UPDATE g_summary SET
                        players_count=:players_count, active_players_count=:active_players_count,
                        Arab_players_count=:Arab_players_count,
                        Roman_players_count=:Roman_players_count, Teutonic_players_count=:Teutonic_players_count,
                        Gallic_players_count=:Gallic_players_count", array(
            'players_count' => $players_count,
            'active_players_count' => $active_players_count,
            'Arab_players_count' => $Arab_players_count,
            'Roman_players_count' => $Roman_players_count,
            'Teutonic_players_count' => $Teutonic_players_count,
            'Gallic_players_count' => $Gallic_players_count
        ));
    }

    public function setGlobalPlayerNews($news)
    {
        db::query("UPDATE g_summary g SET g.gnews_text=:news", array(
            'news' => $news
        ));

        $flag = trim($news) != "" ? 1 : 0;
        db::query("UPDATE p_players p SET p.new_gnews=:flag", array(
            'flag' => $flag
        ));
    }

    public function getSiteNews()
    {
        return db::get_field("SELECT g.news_text FROM g_summary g");
    }

    public function setSiteNews($news)
    {
        db::query("UPDATE g_summary g SET g.news_text=:news", array(
            'news' => $news
        ));
    }

    public function getGlobalSiteNews()
    {
        return db::get_field("SELECT g.gnews_text FROM g_summary g");
    }
}
?>
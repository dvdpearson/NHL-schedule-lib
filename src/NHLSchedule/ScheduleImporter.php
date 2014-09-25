<?php
namespace NHLSchedule;

use DOMDocument;

/**
 * Class ScheduleImporter
 * @package NHLSchedule
 */
class ScheduleImporter
{
    /**
     * @var string
     */
    private $srcURL = "http://www.nhl.com/ice/schedulebyseason.htm?season=%season";

    /**
     * @var null
     */
    private $xml;

    /**
     *
     */
    public function __construct()
    {
        $this->xml = null;
    }

    /**
     * @param string $season
     * @return string
     */
    public function import($season = "20132014")
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, str_replace('%season', $season, $this->srcURL));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $my_var = curl_exec($ch);
        curl_close($ch);

        preg_match_all('|<td colspan="1" rowspan="1" class="date">(.*?)</span></a></td></tr>|si',
            $my_var,
            $out, PREG_PATTERN_ORDER);

        $dom = new DOMDocument('1.0', 'UTF-8');
        $games = $dom->createElement("games");
        $games = $dom->appendChild($games);

        foreach ($out[0] as $game) {
            // Regex as of August 2014 (NHL.com)
            preg_match('|<div style="display:block;" class="skedStartDateSite">(.*?)</div><div style="display:none;"|si', $game, $gameDate);
            preg_match('|<div class="teamName"><a style="border-bottom:1px dotted;" onclick="loadTeamSpotlight\(jQuery\(this\)\);" rel="(.*?)" shape="rect" href="javascript:void\(0\);">(.*?)</a></div></td><td colspan="1" rowspan="1" class="team"><!-- Home -->|si', $game, $awayTeam);
            preg_match('|<!-- Home -->(.*?)<div class="teamName"><a style="border-bottom:1px dotted;" onclick="loadTeamSpotlight\(jQuery\(this\)\);" rel="(.*?)" shape="rect" href="javascript:void\(0\);">(.*?)</a></div></td>|si', $game, $homeTeam);
            preg_match('|<!-- Time -->(.*?)"skedStartTimeEST">(.*?) ET</div><div style="display:none;" class="skedStartTimeLocal">(.*?)</div></td>|si', $game, $time);

            // Fixed Phoenix link missing issue (because the team was recently bought)
            if (!isset($homeTeam[3])) {
                preg_match('|<!-- Home -->(.*?)<div class="teamName">(.*?)</div></td>|si', $game, $homeTeam2);
                $homeTeam[3] = $homeTeam2[2];
            }
            if (!isset($awayTeam[2])) {
                preg_match('|<div class="teamName">(.*?)</div></td><td colspan="1" rowspan="1" class="team"><!-- Home -->|si', $game, $awayTeam2);
                $awayTeam[2] = $awayTeam2[1];
            }

            $game = $dom->createElement("game");
            $game = $games->appendChild($game);
            $game->appendChild($dom->createElement('date', date("d/m/Y H:i:s", strtotime($gameDate[1]." ".$time[2]))." ET"));
            $game->appendChild($dom->createElement('awayteam', $awayTeam[2]));
            $game->appendChild($dom->createElement('hometeam', $homeTeam[3]));
        }
        $this->setXml($dom);
        return true;
    }

    /**
     * @param $xml
     * @return null
     */
    public function setXml($xml)
    {
        $this->xml = $xml;
        return $this->xml;
    }

    /**
     * @return null
     */
    public function getXml()
    {
        return $this->xml;
    }

    /**
     * @return string
     */
    public function getSrcURL()
    {
        return $this->srcURL;
    }

    /**
     * @param $srcURL
     * @return $this
     */
    public function setSrcURL($srcURL)
    {
        $this->srcURL = $srcURL;
        return $this;
    }

    /**
     * @param $filePath
     */
    public function saveToFile($filePath)
    {
        return $this->xml->save($filePath);
    }
}

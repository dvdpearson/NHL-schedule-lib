<?php
namespace NHLSchedule;

/**
 * Class Schedule
 * @package NHLSchedule
 */
class Schedule
{
    /**
     * @var
     */
    private $date;

    /**
     * @var
     */
    private $awayTeam;

    /**
     * @var
     */
    private $homeTeam;

    /**
     * @return mixed
     */
    public function getAwayTeam()
    {
        return $this->awayTeam;
    }

    /**
     * @param $awayTeam
     * @return $this
     */
    public function setAwayTeam($awayTeam)
    {
        $this->awayTeam = $awayTeam;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param $date
     * @return $this
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHomeTeam()
    {
        return $this->homeTeam;
    }

    /**
     * @param $homeTeam
     * @return $this
     */
    public function setHomeTeam($homeTeam)
    {
        $this->homeTeam = $homeTeam;
        return $this;
    }

}

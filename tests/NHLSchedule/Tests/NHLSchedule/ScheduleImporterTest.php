<?php
namespace NHLSchedule\Tests;

use NHLSchedule\ScheduleImporter;

class ScheduleImporterTest extends \PHPUnit_Framework_TestCase
{
    public function testScheduleImporter()
    {
        $a = new ScheduleImporter();
        $this->assertEquals(1, $a->import('20142015'));
    }

    public function testSaveToFile()
    {
        $a = new ScheduleImporter();
        $a->import('20142015');
        $this->assertNotEquals(0, $a->saveToFile('nhl-season-2014-2015.xml'));
        unlink('nhl-season-2014-2015.xml');
    }
}
?>
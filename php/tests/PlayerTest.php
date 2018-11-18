<?php

require_once ('PHPUnit/Framework/TestCase.php');
require_once (__DIR__ . '/../Player.php');

class PlayerTest extends \PHPUnit\Framework\TestCase
{
    function testGettingOutOfPenaltyBox(){
        $johan = new Player("Johan");
        $johan->gettingOutOfPenaltyBox();
        $this->assertTrue($johan->isGettingOutOfPenaltyBox());
        $johan->stayingAtPenaltyBox();
        $this->assertFalse($johan->isGettingOutOfPenaltyBox());
    }

}

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

    function testDidWin(){
        $johan = new Player("Johan");
        $johan->mineCoin();
        $johan->mineCoin();
        $johan->mineCoin();
        $johan->mineCoin();
        $johan->mineCoin();
        $this->assertFalse($johan->didWin());
        $johan->mineCoin();
        $this->assertTrue($johan->didWin());
    }

}

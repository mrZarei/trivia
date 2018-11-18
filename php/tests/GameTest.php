<?php
require_once ('PHPUnit/Framework/TestCase.php');
class GameTest extends \PHPUnit\Framework\TestCase
{
    function testSetPlayerInPenaltyBox()
    {
        $game = new Game();
        $johan = new Player("Johan");
        $david = new Player("David");
        $game->addPlayer($johan);
        $game->addPlayer($david);
        $game->wrongAnswer();
        $this->assertTrue($game->getCurrentPlayer()->isInPenaltyBox());
        $this->assertFalse($game->getCurrentPlayer()->isAllowToAnswer());
    }

    function testMoveToNextPlayer()
    {
        $game = new Game();
        $johan = new Player("Johan");
        $david = new Player("David");
        $game->addPlayer($johan);
        $game->addPlayer($david);
        $game->moveToNextPlayer();
        $this->assertEquals($game->getCurrentPlayer()->getName() , 'David');
    }

    function testTotalPlayerAndIsPlayable(){
        $game = new Game();
        $game->addPlayer(new Player("Johan"));
        $this->assertFalse($game->isPlayable());
        $game->addPlayer(new Player("David"));
        $game->addPlayer(new Player("Erick"));
        $game->addPlayer(new Player("Samuel"));
        $this->assertEquals($game->totalPlayers(), 4);
        $this->assertTrue($game->isPlayable());

    }

}


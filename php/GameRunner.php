<?php

include __DIR__.'/Game.php';

function runGame(): void
{
    $notAWinner;
    $aGame = new Game();

    $aGame->addPlayer(new Player("Chet"));
    $aGame->addPlayer(new Player("Pat"));
    $aGame->addPlayer(new Player("Sue"));


    do {

        $aGame->roll(rand(0, 5) + 1);

        if (rand(0, 9) == 7) {
            $notAWinner = $aGame->wrongAnswer();
        } else {
            $notAWinner = $aGame->wasCorrectlyAnswered();
        }


    } while ($notAWinner);

}

runGame();
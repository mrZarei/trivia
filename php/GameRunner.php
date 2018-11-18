<?php

include __DIR__.'/Game.php';

function runGame(): void
{
    $aGame = new Game();

    $aGame->addPlayer(new Player("Chet"));
    $aGame->addPlayer(new Player("Pat"));
    $aGame->addPlayer(new Player("Sue"));
    $aGame->run();

}

runGame();
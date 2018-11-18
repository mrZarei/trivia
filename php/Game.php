<?php
function echoln($string) {
  echo $string."\n";
}
require_once __DIR__ . '/Player.php';
class Game
{
    var $players;

    var $popQuestions;
    var $scienceQuestions;
    var $sportsQuestions;
    var $rockQuestions;

    var $currentPlayerIndex = 0;
    var $isGettingOutOfPenaltyBox;

    function __construct()
    {

        $this->players = array();
        $this->places = array(0);
        $this->purses = array(0);
        $this->inPenaltyBox = array(0);

        $this->popQuestions = array();
        $this->scienceQuestions = array();
        $this->sportsQuestions = array();
        $this->rockQuestions = array();

        for ($i = 0; $i < 50; $i++) {
            array_push($this->popQuestions, "Pop Question " . $i);
            array_push($this->scienceQuestions, ("Science Question " . $i));
            array_push($this->sportsQuestions, ("Sports Question " . $i));
            array_push($this->rockQuestions, $this->createRockQuestion($i));
        }
    }
    
    function createRockQuestion(int $index): string
    {
        return "Rock Question " . $index;
    }


    function isPlayable(): bool
    {
        return ($this->howManyPlayers() >= 2);
    }

    function addPlayer(Player $player): bool
    {
        array_push($this->players, $player);

        echoln($player->getName() . " was added");
        echoln("They are player number " . count($this->players));
        return true;
    }

    function howManyPlayers(): int
    {
        return count($this->players);
    }

    function roll(int $roll): void
    {
        $currentPlayer = $this->getCurrentPlayer();
        echoln($currentPlayer->getName() . " is the current player");
        echoln("They have rolled a " . $roll);

        if ($currentPlayer->isInPenaltyBox()) {
            if ($roll % 2 != 0) {
                $currentPlayer->gettingOutOfPenaltyBox();

                echoln($currentPlayer->getName() . " is getting out of the penalty box");
                $currentPlayer->moveToNewPlace($roll);

                echoln($currentPlayer->getName()
                    . "'s new location is "
                    . $currentPlayer->getPlace());
                echoln("The category is " . $this->currentCategory());
                $this->askQuestion();
            } else {
                echoln($currentPlayer->getName() . " is not getting out of the penalty box");
                $currentPlayer->stayingAtPenaltyBox();
            }

        } else {

            $currentPlayer->moveToNewPlace($roll);
            echoln($currentPlayer->getName()
                . "'s new location is "
                . $currentPlayer->getPlace());
            echoln("The category is " . $this->currentCategory());
            $this->askQuestion();
        }

    }

    function askQuestion(): void
    {
        if ($this->currentCategory() == "Pop")
            echoln(array_shift($this->popQuestions));
        if ($this->currentCategory() == "Science")
            echoln(array_shift($this->scienceQuestions));
        if ($this->currentCategory() == "Sports")
            echoln(array_shift($this->sportsQuestions));
        if ($this->currentCategory() == "Rock")
            echoln(array_shift($this->rockQuestions));
    }


    function currentCategory(): string
    {
        $currentPlayer = $this->getCurrentPlayer();
        if ($currentPlayer->getPlace() == 0) return "Pop";
        if ($currentPlayer->getPlace() == 4) return "Pop";
        if ($currentPlayer->getPlace() == 8) return "Pop";
        if ($currentPlayer->getPlace() == 1) return "Science";
        if ($currentPlayer->getPlace() == 5) return "Science";
        if ($currentPlayer->getPlace() == 9) return "Science";
        if ($currentPlayer->getPlace() == 2) return "Sports";
        if ($currentPlayer->getPlace() == 6) return "Sports";
        if ($currentPlayer->getPlace() == 10) return "Sports";
        return "Rock";
    }


    function wasCorrectlyAnswered(): bool
    {
        $currentPlayer = $this->getCurrentPlayer();
        if ($currentPlayer->isInPenaltyBox()) {
            if ($currentPlayer->isGettingOutOfPenaltyBox()) {
                echoln("Answer was correct!!!!");
                $currentPlayer->mineCoin();
                echoln($currentPlayer->getName()
                    . " now has "
                    . $currentPlayer->getPurse()
                    . " Gold Coins.");

                $winner = $currentPlayer->didWin();
                $this->moveToNextPlayer();
                return !$winner;
            } else {
                $this->moveToNextPlayer();
                return true;
            }


        } else {

            echoln("Answer was corrent!!!!");
            $currentPlayer->mineCoin();
            echoln($currentPlayer->getName()
                . " now has "
                . $currentPlayer->getPurse()
                . " Gold Coins.");

            $winner = $currentPlayer->didWin();
            $this->moveToNextPlayer();
            return !$winner;
        }
    }

    function wrongAnswer(): bool
    {
        $currentPlayer = $this->getCurrentPlayer();
        echoln("Question was incorrectly answered");
        echoln($currentPlayer->getName() . " was sent to the penalty box");
        $currentPlayer->setIntoPenaltyBox();
        $this->moveToNextPlayer();
        return true;
    }


    private function getCurrentPlayer(): Player{
        return $this->players[$this->currentPlayerIndex];
    }

    private function moveToNextPlayer(): void
    {

        $this->currentPlayerIndex++;
        if ($this->currentPlayerIndex == count($this->players)){
            $this->currentPlayerIndex = 0;
        }

    }
}
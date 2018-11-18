<?php

require_once __DIR__ . '/Player.php';
class Game
{
    var $players;

    var $popQuestions;
    var $scienceQuestions;
    var $sportsQuestions;
    var $rockQuestions;

    var $currentPlayerIndex = 0;

    function __construct()
    {

        $this->players = array();

        $this->popQuestions = array();
        $this->scienceQuestions = array();
        $this->sportsQuestions = array();
        $this->rockQuestions = array();

        for ($i = 0; $i < 50; $i++) {
            array_push($this->popQuestions, "Pop Question " . $i);
            array_push($this->scienceQuestions, ("Science Question " . $i));
            array_push($this->sportsQuestions, ("Sports Question " . $i));
            array_push($this->rockQuestions, ("Rock Question " . $i ));
        }
    }
    
    function isPlayable(): bool
    {
        return ($this->howManyPlayers() >= 2);
    }

    function addPlayer(Player $player): bool
    {
        array_push($this->players, $player);

        Messenger::printAddUser($player);
        Messenger::printPlayerNumber($this->howManyPlayers());
        return true;
    }

    function howManyPlayers(): int
    {
        return count($this->players);
    }

    function roll(int $roll): void
    {
        $currentPlayer = $this->getCurrentPlayer();
        Messenger::printCurrentPlayer($currentPlayer);
        Messenger::printPlyerDice($roll);

        if ($currentPlayer->isInPenaltyBox()) {
            if ($this->isOdd($roll)) {
                $currentPlayer->gettingOutOfPenaltyBox();

                Messenger::printGettingoutOfPenaltyBox($currentPlayer);
                $currentPlayer->moveToNewPlace($roll);

                Messenger::printPlayerNewLocation($currentPlayer);
                Messenger::printCurrentCategory($this->currentCategory());
                $this->askQuestion();
            } else {
                Messenger::printNotGettingOutOfPenaltyBox($currentPlayer);
                $currentPlayer->stayingAtPenaltyBox();
            }

        } else {

            $currentPlayer->moveToNewPlace($roll);
            Messenger::printPlayerNewLocation($currentPlayer);
            Messenger::printCurrentCategory($this->currentCategory());
            $this->askQuestion();
        }

    }

    function askQuestion(): void
    {
        if ($this->currentCategory() == "Pop")
            Messenger::printQuesttion(array_shift($this->popQuestions));
        if ($this->currentCategory() == "Science")
            Messenger::printQuesttion(array_shift($this->scienceQuestions));
        if ($this->currentCategory() == "Sports")
            Messenger::printQuesttion(array_shift($this->sportsQuestions));
        if ($this->currentCategory() == "Rock")
            Messenger::printQuesttion(array_shift($this->rockQuestions));
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
                Messenger::printAnswerWasCorrect();
                $currentPlayer->mineCoin();
                Messenger::printPlayerPurse($currentPlayer);

                $winner = $currentPlayer->didWin();
                $this->moveToNextPlayer();
                return !$winner;
            } else {
                $this->moveToNextPlayer();
                return true;
            }


        } else {

            Messenger::printAnswerWasCorrect();
            $currentPlayer->mineCoin();
            Messenger::printPlayerPurse($currentPlayer);

            $winner = $currentPlayer->didWin();
            $this->moveToNextPlayer();
            return !$winner;
        }
    }

    function wrongAnswer(): bool
    {
        $currentPlayer = $this->getCurrentPlayer();
        Messenger::printAnswerWasIncorrect();
        Messenger::printPlayerWasSentToPenaltyBox($currentPlayer);
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

    private function isOdd(int $roll){
        return ($roll % 2 == 1);
    }
}



class Messenger
{
    private static function echoln(string $message): void
    {
        echo $message . "\n";
    }

    public static function printAddUser(Player $player): void
    {
        self::echoln($player->getName() . " was added");
    }

    public static function printPlayerNumber(int $playerNumber): void
    {
        self::echoln("They are player number " . $playerNumber);
    }

    public static function printCurrentPlayer(Player $currentPlayer): void
    {
        self::echoln($currentPlayer->getName() . " is the current player");
    }

    public static function printPlyerDice(int $dice): void
    {
        self::echoln("They have rolled a " . $dice);
    }

    public static function printGettingoutOfPenaltyBox(Player $player): void
    {
        self::echoln($player->getName() . " is getting out of the penalty box");
    }

    public static function printNotGettingOutOfPenaltyBox(Player $player): void
    {
        self::echoln($player->getName() . " is not getting out of the penalty box");
    }

    public static function printPlayerNewLocation(Player $player){
        self::echoln($player->getName() . "'s new location is " . $player->getPlace());
    }

    public static function printCurrentCategory(string $category): void
    {
        self::echoln("The category is " . $category);
    }

    public static function printAnswerWasCorrect(): void
    {
        self::echoln("Answer was correct!!!!");
    }

    public static function printPlayerPurse(Player $player){
        self::echoln($player->getName(). " now has " . $player->getPurse() . " Gold Coins.");
    }

    public static function printAnswerWasIncorrect(): void
    {
        self::echoln("Question was incorrectly answered");
    }
    public static function printPlayerWasSentToPenaltyBox(Player $player){
        self::echoln($player->getName() . " was sent to the penalty box");
    }

    public static function printQuesttion(string $question): void
    {
        self::echoln($question);
    }
}
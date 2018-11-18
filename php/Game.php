<?php

require_once __DIR__ . '/Player.php';
class Game
{
    private const MINIMUM_PLAYER = 2;
    private const BOARD_SIZE = 12;
    private const INCORRECT_ANSWER_INDEX = 7;
    private const GOAL = 6;

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
    public function run(): void
    {
        do {

            $this->roll(rand(0, 5) + 1);

            if ($this->getAnswer() == self::INCORRECT_ANSWER_INDEX) {
                $this->wrongAnswer();
            } else {
                $this->wasCorrectlyAnswered();
                $winner = $this->returnWinner();
            }
            $this->moveToNextPlayer();


        } while ($winner == null);
    }
    private function getAnswer(): int
    {
        return rand(0, 9);
    }
    function isPlayable(): bool
    {
        return ($this->totalPlayers() >= self::MINIMUM_PLAYER);
    }

    function addPlayer(Player $player): void
    {
        array_push($this->players, $player);

        Messenger::printAddUser($player);
        Messenger::printPlayerNumber($this->totalPlayers());
    }

    function totalPlayers(): int
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
                $this->movePlayer($currentPlayer,$roll);

                Messenger::printPlayerNewLocation($currentPlayer);
                Messenger::printCurrentCategory($this->currentCategory());
                $this->pickAQuestion();
            } else {
                Messenger::printNotGettingOutOfPenaltyBox($currentPlayer);
                $currentPlayer->stayingAtPenaltyBox();
            }

        } else {

            $this->movePlayer($currentPlayer, $roll);
            Messenger::printPlayerNewLocation($currentPlayer);
            Messenger::printCurrentCategory($this->currentCategory());
            $this->pickAQuestion();
        }

    }

    function pickAQuestion(): void
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
        $categories = array("Pop", "Science", "Sports", "Rock");
        $categoryIndex = $currentPlayer->getPlace() % count($categories);
        return $categories[$categoryIndex];
    }


    function wasCorrectlyAnswered(): void
    {
        $currentPlayer = $this->getCurrentPlayer();
        if ($currentPlayer->isAllowToAnswer()) {
            Messenger::printAnswerWasCorrect();
            $currentPlayer->mineCoin();
            Messenger::printPlayerPurse($currentPlayer);
        }

    }

    private function returnWinner(): ?Player
    {
        foreach ($this->players as $player){
            if($player->getPurse() == self::GOAL){
                return $player;
            }
        }
        return null;
    }

    function wrongAnswer(): void
    {
        $currentPlayer = $this->getCurrentPlayer();
        Messenger::printAnswerWasIncorrect();
        Messenger::printPlayerWasSentToPenaltyBox($currentPlayer);
        $currentPlayer->setIntoPenaltyBox();
    }


    function getCurrentPlayer(): Player{
        return $this->players[$this->currentPlayerIndex];
    }

    function moveToNextPlayer(): void
    {

        $this->currentPlayerIndex++;
        if ($this->currentPlayerIndex == count($this->players)){
            $this->currentPlayerIndex = 0;
        }

    }

    private function isOdd(int $roll){
        return ($roll % 2 == 1);
    }

    private function movePlayer(Player $player, int $roll): void
    {
        $newPlace = $player->getPlace() + $roll;
        if ($newPlace > self::BOARD_SIZE - 1) {
            $newPlace -= self::BOARD_SIZE;
        }
        $player->moveToNewPlace($newPlace);
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
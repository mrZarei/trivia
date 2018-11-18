<?php
final class Player
{

    private $name;
    private $purse;
    private $place;
    private $inPenaltyBox;
    private $isGettingOutOfPenaltyBox;

    function __construct(string $name)
    {
        $this->name = $name;
        $this->purse = 0;
        $this->place = 0;
        $this->inPenaltyBox = false;
    }

    function getName(): string
    {
        return $this->name;
    }

    function setName(string $name): void
    {
        $this->name = $name;
    }

    function getPurse(): int
    {
        return $this->purse;
    }


    function getPlace(): int
    {
        return $this->place;
    }

    function isGettingOutOfPenaltyBox(): bool
    {
        return $this->isGettingOutOfPenaltyBox;
    }
    function gettingOutOfPenaltyBox(): void
    {
        $this->isGettingOutOfPenaltyBox = true;
    }

    function stayingAtPenaltyBox(): void
    {
        $this->isGettingOutOfPenaltyBox = false;
    }


    function isInPenaltyBox(): bool
    {
        return $this->inPenaltyBox;
    }

    public function setIntoPenaltyBox(): void
    {
        $this->inPenaltyBox = true;
    }


    public function moveToNewPlace(int $roll): void
    {
        $this->place += $roll;
        if ($this->place > 11) {
            $this->place -= 12;
        }
    }

    public function mineCoin(): void
    {
        $this->purse++;
    }

    public function didWin(): bool
    {
        return $this->purse >= 6;
    }
}
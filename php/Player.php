<?php
final class Player
{

    private const GOAL = 6;

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


    public function moveToNewPlace(int $newPlace): void
    {
        $this->place = $newPlace;
    }

    public function mineCoin(): void
    {
        $this->purse++;
    }


    public function isNotAllowToAnswer(): bool {
        return $this->isInPenaltyBox() && $this->isGettingOutOfPenaltyBox() == false;
    }
}
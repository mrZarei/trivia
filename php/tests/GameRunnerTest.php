<?php

require_once ('PHPUnit/Framework/TestCase.php');


class GameRunnerTest extends \PHPUnit\Framework\TestCase
{
    protected $snapshot;
    private const SNAPSHOT_PATH = __DIR__ . "/snapshot.txt";
    private const SEED = 3;

    protected function setUp()
    {
        if (!file_exists(self::SNAPSHOT_PATH)) {
            file_put_contents(self::SNAPSHOT_PATH, $this->takeSnapshot(3));
            $this->snapshot = $this->takeSnapshot(self::SEED);
            echo "Snapshot has been created";
        } else {
            $this->snapshot = file_get_contents(self::SNAPSHOT_PATH);
        }
    }

    function testGenerateOutput()
    {
        $this->assertEquals($this->snapshot, $this->takeSnapshot(self::SEED));
    }


    /***
     * Buffer the output of GameRunner.php
     * @param $seed
     * @return false|string
     */
    private function takeSnapshot($seed)
    {
        ob_start();
        srand($seed);
        require_once __DIR__ . '/../GameRunner.php';
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

}

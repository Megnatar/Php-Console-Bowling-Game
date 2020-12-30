<?php

class BowlingGame
{
    private $scoreBoard;
    private $console;
    private $players = [];
    private $round = 0;

    /**
     * Create global console object. And start adding players to the game.
     */
    public function __construct()
    {
        $this->console = new Console();
        $this->console->stdInput(1);
        $this->console->echoInput(" Bowling Game ", "*", 20);
        $this->console->echoInput("  by: Joz Severijnse™️\n");
        $this->console->getInpunt("\n\nPress enter to begin adding users to the game.");
        $this->askPlayerNames();
    }

    /**
     * Add player to the game.
     */
    private function askPlayerNames()
    {
        $this->console->stdInput(1);

        while (trim($playerName = $this->console->getInpunt("Hello, please tell me you're name: ")) == "") {
            $this->console->echoInput("Empty names are not allowed!\n\n");
        }
        $this->addPlayer($playerName);

        while (trim($this->console->getInpunt("Would you like to add more players? y/n: ")) === "y") {
            $this->console->stdInput(1);

            while (trim($playerName = $this->console->getInpunt("Hello, please tell me you're name: ")) == "") {
                $this->console->echoInput("Empty names are not allowed!\n\n");
            }
            $this->addPlayer($playerName);
        }
        // Create scoreboard object.
        $this->scoreBoard = new ScoreBoard($this->players);
    }

    /**
     * @param string $playerName The name of the player.
     */
    private function addPlayer($playerName)
    {
        // Array $property[] with round properties for each player.
        $property = ["ball1", "ball2", "strike", "spare"];

        // Create a new Player object. Add the user object to the $players[] array.
        $this->players[$playerName] = new player($playerName);

        // Create content of $pinsThrown[]. Initialize properties ball1, ball2, strike, and spare to value zero.
        for ($n = 1; $n <= 12; $n++) {
            for ($i = 0; $i < sizeof($property); $i++) {
                $this->players[$playerName]->pinsThrown[$n][$property[$i]] = 0;
            }
        }
    }

    /**
     * Start playing the game.
     */
    public function start()
    {
        $playerList = "";
        $this->console->stdInput(1);
        $this->console->echoInput(" Welcome to the bowling game", "*", 15);

        foreach ($this->players as $player) {
            $playerList .= " Name: " . $player->name . "\n";
        }
        $this->console->echoInput("\n Players for this game are: \n$playerList");
        $this->console->getInpunt("\nPress enter to start the game!");
        $this->playAllRounds();
    }

    /**
     * Start the game and let each player play there round.
     */
    private function playAllRounds()
    {
        $this->round++;

        foreach ($this->players as $player) {
            $this->console->stdInput(1);
            $this->playRound($player);
        }
        // Restart playing rounds as long as round number is between 1-10. Call method displayWinner() at the end of round 10.
        $this->round < 10 ? $this->playAllRounds() : $this->scoreBoard->displayWinner($this->players);
    }

    /**
     * @param object $player The name of the player.
     * Start new round for the current player.
     */
    private function playRound($player)
    {
        $round = $this->round;
        $player->round = $round;
        $strike = 0;
        $spare = 0;

        $this->console->echoInput("Player: $player->name \tRound: $round \n\n");
        $this->playBalls($player);
        $balls = $player->balls;

        // Strike with ball1 when it knocked over all pins.
        if ($balls["ball1"] == 10) {
            $this->console->echoInput("Strike with ball one!\n\n");
            $strike = 1;
        }
        // If ball2 is greater then zero. Then no strike occurred in this round. And when the value of ball1+ball2 is 10. Then the player earned a spare.
        if (($balls["ball2"] > 0) && ($balls["ball1"] + $balls["ball2"]) === 10) {
            $this->console->echoInput("Spare with ball one!\n\n");
            $spare = 1;
        }
        // Call method throwPins, stored in the user object. Pass sum of balls, round number strike and spare.
        $player->thrownPins($balls["ball1"], $balls["ball2"], $round, $strike, $spare);

        // Call method calculatePlayerScore from the scoreboard object.
        $this->scoreBoard->calculatePlayerScore($player);

        // Check in the 10th round, if there was a strike/spare in round 9 or 10. Let players, play there extra rounds.
        if ($round == 10) {
            if ($player->pinsThrown[($round - 1)]["strike"] === 1 || $player->pinsThrown[$round]["spare"] === 1) {
                $this->playLastRound($player, 1);
            }
            if ($strike == 1) {
                $this->playLastRound($player, 2);
            }
        }
    }

    /**
     * private function playBalls($player)
     * @param object $player
     */
    private function playBalls($player)
    {
        $balls = ["ball1" => -1, "ball2" => -1];

        for ($i = 1; $i <= 2; $i++) {
            $pinsDown = $this->getPinsDown($balls["ball1"]);
            $this->console->echoInput("You're " . ($balls["ball1"] === -1 ? "first" : "second") . " ball knocked down " . $pinsDown . ($pinsDown === 1 ? " pin" : " pins") . "\n");

            if ($balls["ball1"] !== 10) {
                while ($this->console->getInpunt("Please fill in you're score: ") != $pinsDown) {
                    $this->console->echoInput("\nINCORRECT NUMBER!\n\nTotal pins knocked over: " . $pinsDown . "\n");
                }
                $this->console->echoInput("\n");
                $balls["ball" . $i] = $pinsDown;
            }
        }
        $player->balls = $balls;
        return $balls;
    }

    /**
     * @param int $pinsLeft The number of pins left over from ball one.
     * @return int The number of pins rolled down by the current ball.
     */
    private function getPinsDown($pinsLeft)
    {
        return $pinsLeft === -1 ? mt_rand(0, 10) : mt_rand(0, (10 - $pinsLeft));
    }

    /**
     * @param object $player The name of the player.
     * @param integer $roundsToPlay The number of extra rounds to play.
     * Let the player play round 11 or 12. Depending on whatever they scored a strike/spare in round 9 or 10.
     */
    private function playLastRound($player, $roundsToPlay)
    {
        // Let the player play, either one or two extra rounds.
        for ($n = 1; $n <= ($roundsToPlay === 1 ? 1 : 2); $n++) {
            $player->round++;
            $this->console->echoInput(" Play BONUS rounds ", "*", 20);
            $balls = $this->playBalls($player);
            $player->thrownPins($balls["ball1"], $balls["ball2"], $player->round);
            $this->scoreBoard->calculatePlayerScore($player);
        }
    }
}
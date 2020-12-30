<?php

class ScoreBoard
{
    private $scores = [];
    private $console;

    /**
     * @param array $players
     * Create scores array for all players in the game. It holds the total player score and individial round scores.
     */
    public function __construct($players)
    {
        // Prepare scores[] array for each player in the game.
        foreach ($players as $name) {
            $this->scores[$name->name]["scoreTotal"] = 0;

            // Create rounds 1-12 and initialize property roundTotal with value zero.
            for ($i = 1; $i <= 12; $i++) {
                $this->scores[$name->name][$i]["roundTotal"] = 0;
            }
        }
        $this->console = new Console();
    }

    /**
     * @param object $player
     * Calculate the current round score for the player. Checks if the points for a strike/spare needs.
     * to be added to the current round score.
     */
    public function calculatePlayerScore($player)
    {
        $round = $player->round;
        $ball1 = $player->pinsThrown[$round]["ball1"];
        $ball2 = $player->pinsThrown[$round]["ball2"];
        $this->scores[$player->name][$round]["roundTotal"] = ($ball1 + $ball2);

        // True if $round is greater then 1. True when player scored a strike/spare in the previous round.
        if (($round > 1) && (($player->pinsThrown[($round - 1)]["strike"]) == 1 || ($player->pinsThrown[($round - 1)]["spare"]) == 1)) {
            // Add the current points to the points of the previous round.
            $this->scores[$player->name][$round - 1]["roundTotal"] += ($ball1 + $ball2);
        }
        // True if $round is above 2. True when player scored a strike two rounds ago.
        if (($round > 2) && (($player->pinsThrown[($round - 2)]["strike"]) == 1)) {
            // Add the current points to the points of the previous two rounds.
            $this->scores[$player->name][$round - 2]["roundTotal"] += ($ball1 + $ball2);
        }
        // Call method calculateAllScores() to calculate the current total score for the player.
        $this->calculateAllScores($player);

        // Show player there current total score.
        $this->displayScores($player);
    }

    /**
     * @param object $player
     * Calculate current total score, earned in each round for the current player.
     */
    private function calculateAllScores($player)
    {
        $scoreTotal = 0;

        // Walktrough all rounds played by player. Add round score to $scoreTotal.
        for ($i = 1; $i <= $player->round; $i++) {
            $scoreTotal += $player->pinsThrown[$i]["ball1"] + $player->pinsThrown[$i]["ball2"];
        }
        // Store total score in the player array.
        $this->scores[$player->name]["scoreTotal"] = $scoreTotal;
    }

    /**
     * @param array $player
     * Inform the player of the current score.
     */
    public function displayScores($player)
    {
        $this->console->echoInput("You're total score so far is: " . $this->scores[$player->name]["scoreTotal"] . "\n");
        $this->console->getInpunt("Press enter, to let the next player start his/her round!");
        $this->console->stdInput(1);
    }

    /**
     * Displays the winner of the game.
     */
    public function displayWinner()
    {
        // Search in array scores, for the username with the highest score.
        $nameWinner = array_search(max($this->scores), $this->scores);
        $scoreWinner = max($this->scores);

        $this->console->stdInput(1);
        $this->console->getInpunt("De winnaar met " . $scoreWinner["scoreTotal"] . " punten is: " . $nameWinner
            . "\nPress enter to end the game!");
        $this->console->stdInput(1);
    }
}
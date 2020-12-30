<?php

class Player
{
    public $name;
    public $pinsThrown = [];

    /**
     * Player constructor.
     * @param string $name The name of the player.
     * Create user object for bowlingGame.class.
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @param int $throw1 The number of pins down for ball1.
     * @param int $throw2 The number of pins down for ball2.
     * @param int $round The current round number.
     * @param int $strike One if the player scored a strike.
     * @param int $spare One if the player scored a spare.
     *
     * Store the number of thrown pins for each ball per round. And whatever the player scored a strike/spare.
     */
    public function thrownPins($throw1, $throw2, $round, $strike = 0, $spare = 0)
    {
        $this->pinsThrown[$round]["ball1"] = $throw1;
        $this->pinsThrown[$round]["ball2"] = $throw2;
        $this->pinsThrown[$round]["strike"] = $strike;
        $this->pinsThrown[$round]["spare"] = $spare;
    }
}
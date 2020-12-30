<?php

require_once "BowlingGame.class.php";
require_once "ScoreBoard.class.php";
require_once "Player.class.php";
require_once "Console.class.php";

/** @var object game */
$game = new BowlingGame();
$game->start();
exit;

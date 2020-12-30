<?php

class Console
{
    /**
     * Console constructor.
     */
    public function __construct()
    {
        // Constructor returns empty console class.
    }

    /**
     * @param string $str Some input text.
     * @param integer $pins Enable autoplay. Input need to be $pinsDown
     * @return string The input from the console.
     */
    public function getInpunt($str = "Type something here: ", $pins = 0)
    {
        if ($pins > 0) {
            echo $str . $pins . "\n";
            return $pins;
        } else {
            echo $str;
            return $this->stdInput();
        }
    }

    /**
     * @param int $clear If set to one. Clear console input.
     * @return string Return the string to the caller.
     */
    public function stdInput($clear = 0)
    {
        if ($clear == 0) {
            return rtrim(fgets(STDIN));
        } else {
            echo ($esc = chr(27)) . ($sqrBrkt = chr(91)) . 'H'
                . ($esc = chr(27)) . ($sqrBrkt = chr(91)) . 'J';
        }
    }

    /**
     * @param string $str The string to show in the console.
     */
    public function echoInput($str = "Echo some string.")
    {
        echo $str;
    }
}

?>
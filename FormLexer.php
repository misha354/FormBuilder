<?php

class FormLexer extends Lexer
{

    const QUOTED_STRING = 1;
    const SINGLE_LINE_TEXT = 2;
    const MULTI_LINE_TEXT = 3;
    const MULTIPLE_CHOICE = 4;
    const CHOICE = 5;
    const SUBMIT = 6;

    public static $tokenNames = array("n/a", "<EOF>", "SINGLE_LINE_TEXT", "MULTI_LINE_TEXT", "MULTIPLE_CHOICE", "CHOICE",
        "QUOTED_STRING", "SUBMIT" );
    public static $keywords = array("SINGLE_LINE_TEXT", "MULTI_LINE_TEXT", "MULTIPLE_CHOICE", "CHOICE", "SUBMIT");

    public function getTokenName($x)
    {
        return $this->tokenNames[$x];
    }

    public function __construct($input)
    {
        parent::__construct($input);
    }

    public function isQUOTE()
    {
        return ($this->c == '"');
    }

    public function nextToken()
    {
        while ($this->c != static::EOF) {
            switch ($this->c) {
                case ' ':
                case "\t":
                case "\n":
                case "\r":
                    $this->WS();
                    continue;
                case '"':
                    $this->QUOTED_STRING();
                default:
                    return $this->KEYWORD();
            }
        }
        return new Token(EOF_TYPE, "<EOF>");
    }

    public function WS()
    {
        while ($this->c == ' ' || $this->c == "\t" || $this->c == "\n" || $this->c == "\r")
            $this->consume();
    }

    public function QUOTED_STRING()
    {
        $this->consume();
        $buf = "";
        while (!$this->isQUOTE()) {
            $buf .= $this->c;
            $this->consume();
            if ($this->c == ($this->isEOL() || $this->isEOF())) {
                throw new Exception("Unterminated string");
            }
            $this->consume();
            return new Token(QUOTED_STRING, $buf);
        }
    }

    public function KEYWORD()
    {
        $buf = "";

        do {
            $buf .= $this->c;

            $this->LETTERorUNDERSCORE();
        } while ($this->isLETTER() || $this->isUNDERSCORE());
        
        if (in_array ( $buf, static::$keywords)){        
            return new Token(array_search($buf, static::$tokenNames), $buf);
        }
        else {
            throw new Exception("Unknown keyword $buf");
        }
    }

    public function isEOF()
    {
        return ($this->c == static::EOF);
    }

    public function isEOL()
    {
        return ($this->c == "\r" || $this->c == "\n");
    }

    public function isWS()
    {
        return $this->c == ' ' ||
                $this->c == "\t" ||
                $this->c == "\n" ||
                $this->c == "\r";
    }

    public function isLETTER()
    {
        $value = ord($this->c);
        return $value >= 65 && $value <= 90 || $value >= 97 && $value <= 122;
    }

    private function isUNDERSCORE()
    {
        return $this->c === "_";
    }

    public function LETTERorUNDERSCORE()
    {
        if ($this->isLETTER() || $this->isUNDERSCORE()) {
            $this->consume();
        } else {
            throw new Exception("expecting LETTER or UNDERSCORE; found $this->c");
        }
    }


}

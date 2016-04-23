<?php

class FormLexer extends Lexer
{

    const EOF_TYPE = 1;
    const SINGLE_LINE_TEXT = 2;
    const MULTI_LINE_TEXT = 3;
    const MULTIPLE_CHOICE = 4;
    const CHOICE = 5;
    const QUOTED_STRING = 6;
    const SUBMIT = 7;


    public static $tokenNames = array("n/a", "<EOF>", "SINGLE_LINE_TEXT", "MULTI_LINE_TEXT", "MULTIPLE_CHOICE", "CHOICE",
        "QUOTED_STRING", "SUBMIT" );
    public static $keywords = array("SINGLE_LINE_TEXT", "MULTI_LINE_TEXT", "MULTIPLE_CHOICE", "CHOICE", "SUBMIT");

    public function getTokenName($x)
    {
        return static::$tokenNames[$x];
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
/*            echo "inside big while nexttoken ord c is ". ord($this->c) . "\n";
            if (ord($this->c) == 13){
                echo "inside if calling ws\n";
                $this->WS();
                continue;
            }*/ 

            switch ($this->c) {
                case ' ':
                case "\t":
                case "\n":
                case "\r":
//                    echo "got here inside whitespace case\n";
                    $this->WS();
                    continue;
                case '"':
                    return $this->QUOTED_STRING();
                default:
                    return $this->KEYWORD();
            }
        }
        return new Token(static::EOF_TYPE, "End-Of-File");
    }

    public function WS()
    {
//        echo "consuming whitespace\n";
        while ($this->c == ' ' || $this->c == "\t" || $this->c == "\n" || $this->c == "\r")
            $this->consume();
    }

    public function QUOTED_STRING()
    {
    //    echo "in quoted_string\n";
        $this->consume();
        $buf = "";
        while (!$this->isQUOTE()) {
            $buf .= $this->c;
            
            $this->consume();
            if ($this->c == ($this->isEOL() || $this->isEOF())) {
                throw $this->throwException("Unterminated string.");
            }            
        }
        $this->consume();
  //      echo "quoted string is returning $buf, ord c is ". ord($this->c)."\n";
        return new Token(static::QUOTED_STRING, $buf);

    }

    public function KEYWORD()
    {
        $buf = "";

        do {
//            echo "keyword() buf is $buf\n";
            $buf .= $this->c;

            $this->LETTERorUNDERSCORE();
        } while ($this->isLETTER() || $this->isUNDERSCORE());
        
        if (in_array ( $buf, static::$keywords)){        
            return new Token(array_search($buf, static::$tokenNames), $buf);
        }
        else {
            $this->columnNumber -= strlen($buf);
            $this->throwException("Unknown keyword $buf.");
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

   /* public function isWS()
    {
        return $this->c == ' ' ||
                $this->c == "\t" ||
                $this->c == "\n" ||
                $this->c == "\r";
    }*/

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
            $this->throwException("expecting LETTER or UNDERSCORE; found $this->c.");
        }
    }


}

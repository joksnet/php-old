<?php

define('DEBUG', true);
define('CONNECTION', 'mysql://root:secret@localhost/dev');
define('TABLES', 'Poll:Choise');

class Poll extends Model
{
    protected $question;
    protected $pubDate;

    public function __construct()
    {
        $this->question = Models::Char()->length(200);
        $this->pubDate  = Models::DateTime();
    }

    public function __toString()
    {
        return $this->question;
    }
}

class Choise extends Model
{
    protected $poll;
    protected $choise;
    protected $votes;

    public function __construct()
    {
        $this->poll   = Models::ForeignKey('Poll');
        $this->choise = Models::Char()->length(200);
        $this->votes  = Models::Integer();
    }

    public function __toString()
    {
        return $this->choise;
    }
}

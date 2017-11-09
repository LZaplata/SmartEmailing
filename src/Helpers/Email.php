<?php

namespace LZaplata\SmartEmailing\Helper;


use Nette\Object;

class Email extends Object
{
    /** @var  string */
    private $senderName;

    public function setSenderName($name = null)
    {
        $this->senderName = $name;
    }

    public function getSenderName()
    {
        return $this->senderName;
    }
}
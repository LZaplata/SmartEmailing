<?php

namespace LZaplata\SmartEmailing\Helper;


use Nette\Object;
use Nette\Utils\Random;
use Nette\InvalidArgumentException;

class Email extends Object
{
    /** @var  string|null */
    private $senderName;

    /** @var  string|int */
    private $customId;

    /**
     * Email constructor.
     */
    public function __construct()
    {
        $this->customId = Random::generate();
    }

    /**
     * @param string|int $id
     * @throws InvalidArgumentException
     * @return void
     */
    public function setCustomId($id)
    {
        if (!isset($id)) {
            throw new InvalidArgumentException("ID must be set.");
        }

        $this->customId = $id;
    }

    /**
     * @return int|string
     */
    public function getCustomId()
    {
        return $this->customId;
    }

    /**
     * @param null|string $name
     * @return void
     */
    public function setSenderName($name = null)
    {
        $this->senderName = $name;
    }

    /**
     * @return null|string
     */
    public function getSenderName()
    {
        return $this->senderName;
    }
}
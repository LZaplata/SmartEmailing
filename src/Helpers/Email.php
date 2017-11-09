<?php

namespace LZaplata\SmartEmailing\Helper;


use Nette\Object;
use Nette\Utils\Random;
use Nette\Utils\Validators;
use Nette\InvalidArgumentException;

class Email extends Object
{
    /** @var  string|int */
    private $customId;

    /** @var  string */
    private $senderName;

    /** @var  string */
    private $senderEmail;

    /** @var  string */
    private $recipientName;

    /** @var  string */
    private $recipientEmail;

    /** @var  string */
    private $subject;

    /** @var  string */
    private $htmlBody;

    /** @var  array */
    private $replacements;

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
     * @param string $email
     * @param string $name
     * @throws InvalidArgumentException
     * @return void
     */
    public function setSender($email, $name)
    {
        if (!Validators::isEmail($email)) {
            throw new InvalidArgumentException("Sender email address must be correct.");
        }

        if (!isset($name)) {
            throw new InvalidArgumentException("Sender name muset be set.");
        }

        $this->senderEmail = $email;
        $this->senderName = $name;
    }

    /**
     * @return string
     */
    public function getSenderName()
    {
        return $this->senderName;
    }

    /**
     * @return string
     */
    public function getSenderEmail()
    {
        return $this->senderEmail;
    }



    /**
     * @param string $email
     * @param string $name
     * @throws InvalidArgumentException
     * @return void
     */
    public function setRecipient($email, $name)
    {
        if (!Validators::isEmail($email)) {
            throw new InvalidArgumentException("Recipient email address must be correct.");
        }

        if (!isset($name)) {
            throw new InvalidArgumentException("Recipient name muset be set.");
        }

        $this->recipientEmail = $email;
        $this->recipientName = $name;
    }

    /**
     * @return string
     */
    public function getRecipientName()
    {
        return $this->recipientName;
    }

    /**
     * @return string
     */
    public function getRecipientEmail()
    {
        return $this->recipientEmail;
    }

    /**
     * @param string $subject
     * @return void
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $html
     * @return void
     */
    public function setHtmlBody($html)
    {
        $this->htmlBody = $html;
    }

    /**
     * @return string
     */
    public function getHtmlBody()
    {
        return $this->htmlBody;
    }

    /**
     * @param array $replacements
     * @return void
     */
    public function setReplacements($replacements)
    {
        $this->replacements = $replacements;
    }

    /**
     * @return array
     */
    public function getReplacements()
    {
        return $this->replacements;
    }
}
<?php

namespace LZaplata\SmartEmailing;


use GuzzleHttp\Exception\RequestException;
use LZaplata\SmartEmailing\Helper\Email;
use Nette\InvalidArgumentException;
use Nette\Object;
use Nette\Utils\Json;
use Nette\Utils\Validators;

class Client extends Object
{
    /** @var  string */
    protected $username;
    
    /** @var  string */
    protected $apiKey;

    /** @var string  */
    private $baseUrl = "https://app.smartemailing.cz/api/v3";

    /** @var  \GuzzleHttp\Client */
    private $client;
    
    /**
     * Client constructor.
     * @param string $username
     * @param string $apiKey
     */
    public function __construct($username, $apiKey)
    {
        $this->username = $username;
        $this->apiKey = $apiKey;

        $this->createClient();
    }

    /**
     * @return void
     */
    private function createClient()
    {
        $client = new \GuzzleHttp\Client([
            "base_url" => $this->baseUrl,
            "defaults" => [
                "auth" => [$this->username, $this->apiKey],
                "headers" => [
                    "Content-Type" => "application/json"
                ]
            ]
        ]);

        $this->client = $client;
    }

    /**
     * @return mixed|string
     */
    public function loginTest()
    {
        $url = $this->baseUrl . "/check-credentials";

        try {
            $request = $this->client->get($url);

            return $request->json();
        } catch (RequestException $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param string $email
     * @param int $contactlistId
     * @return \GuzzleHttp\Message\FutureResponse|\GuzzleHttp\Message\ResponseInterface|\GuzzleHttp\Ring\Future\FutureInterface|null
     */
    public function importContact($email, $contactlistId)
    {
        $this->isEmailInContactlist($email, $contactlistId);

        $url = $this->baseUrl . "/import";
        $body = [
            "data" => [[
                "emailaddress" => $email,
                "contactlists" => [[
                    "id" => $contactlistId,
                    "status" => "confirmed"
                ]]
            ]]
        ];

        return $this->client->post($url, [
            "body" => Json::encode($body)
        ]);
    }

    /**
     * @param int $id
     * @throws InvalidArgumentException
     * @return \GuzzleHttp\Message\FutureResponse|\GuzzleHttp\Message\ResponseInterface|\GuzzleHttp\Ring\Future\FutureInterface|null
     */
    public function getContactlist($id)
    {
        if (!isset($id)) {
            throw new InvalidArgumentException("Contactlist ID must be set.");
        } elseif (!Validators::isNumericInt($id)) {
            throw new InvalidArgumentException("Contactlist ID must be integer.");
        }

        $url = $this->baseUrl . "/contactlists/" . $id;

        return $this->client->get($url);
    }

    /**
     * @param int $id
     * @return \GuzzleHttp\Message\FutureResponse|\GuzzleHttp\Message\ResponseInterface|\GuzzleHttp\Ring\Future\FutureInterface|null
     */
    public function getContactsInList($id)
    {
        $this->getContactlist($id);

        $url = $this->baseUrl . "/contactlists/" . $id . "/contacts";

        return $this->client->get($url);
    }

    /**
     * @param string $email
     * @param int $contactlistId
     * @throws \Exception|InvalidArgumentException
     * @return void
     */
    public function isEmailInContactlist($email, $contactlistId)
    {
        if (!isset($email)) {
            throw new InvalidArgumentException("E-mail address must be set.");
        } elseif (!Validators::isEmail($email)) {
            throw new InvalidArgumentException("E-mail address is not correct.");
        }

        try {
            $request = $this->getContactsInList($contactlistId);

            foreach ($request->json()["data"] as $contact) {
                if ($contact["emailaddress"] == $email) {
                    throw new \Exception("E-mail already exists in contactlist.");
                }
            }
        } catch (RequestException $e) {
            throw new InvalidArgumentException($e->getMessage());
        }
    }

    /**
     * @param int $id
     * @throws InvalidArgumentException
     * @return \GuzzleHttp\Message\FutureResponse|\GuzzleHttp\Message\ResponseInterface|\GuzzleHttp\Ring\Future\FutureInterface|null
     */
    public function getEmail($id)
    {
        if (!isset($id)) {
            throw new InvalidArgumentException("E-mail ID must be set.");
        } elseif (!Validators::isNumericInt($id)) {
            throw new InvalidArgumentException("E-mail ID must be integer.");
        }

        $url = $this->baseUrl . "/emails/" . $id;

        return $this->client->get($url);
    }

    /**
     * @param Email $email
     * @return \GuzzleHttp\Message\FutureResponse|\GuzzleHttp\Message\ResponseInterface|\GuzzleHttp\Ring\Future\FutureInterface|null
     */
    public function sendCustomEmail($email)
    {
        if (!$email instanceof Email) {
            throw new InvalidArgumentException("Parameter must be instance of LZaplata\SmartEmailing\Helpers\Email");
        }

        $url = $this->baseUrl . "/send/custom-emails";
        $body = [
            "batch" => [[
                "custom_id" => $email->getCustomId(),
                "sendername" => $email->getSenderName(),
                "senderemail" => $email->getSenderEmail(),
                "recipientname" => $email->getRecipientName(),
                "recipientemail" => $email->getRecipientEmail(),
                "tag" => $email->getTag(),
                "email" => [
                    "subject" => $email->getSubject(),
                    "htmlbody" => $email->getHtmlBody()
                ]
            ]]
        ];

        foreach ($email->getReplacements() as $key => $replacement) {
            $body["batch"][0]["replace"][] = [
                "key" => $key,
                "content" => $replacement
            ];
        }

        return $this->client->post($url, [
            "body" => Json::encode($body)
        ]);
    }
}
# SmartEmailing
This is small Nette Framework wrapper for SmartEmailing.

## Installation
The easiest way to install library is via Composer.

````sh
$ composer require lzaplata/smartemailing: dev-master
````
or edit `composer.json` in your project

````json
"require": {
        "lzaplata/smartemailing": "dev-master"
}
````

You have to register the library as extension in `config.neon` file.

````neon
extensions:
        smartEmailing: LZaplata\SmartEmailing\DI\Extension
````

Now you can set parameters...

````neon
smartEmailing:
        username           : *
        apiKey             : *
````

...and autowire library to presenter

````php
use LZaplata\SmartEmailing\Client;

/** @var Client @inject */
public $smartEmailingClient;
````
## Usage

### Import contact

````php
try {
    $this->smartEmailingClient->importContact($email, $contactlistId);
} catch (\Exception $e) {
    echo $e->getMessage();
}
````

### Get single email

````php
try {
    $request = $this->smartEmailingClient->getEmail($id);
    
    echo $request->json();
} catch (\Exception $e) {
    echo $e->getMessage();
}
````

### Send custom email

First of all you have to declare `LZaplata\SmartEmailing\Helpers\Email` object and then pass it as parameter to `sendCustomEmail` function.

````php
try {
    $email = new Email();
    $email->setSender($senderEmail, $senderName);
    $email->setRecipient($recipientEmail, $recipientName);
    $email->setSubject($subject);
    $email->setHtmlBody($html);
    $email->setTag($tag);
    $email->setReplacements([
        "key" => "content"
    ]);

    $this->smartEmailingClient->sendCustomEmail($email);
} catch (\Exception $e) {
    echo($e->getMessage());
}
````
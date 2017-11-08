<?php

namespace LZaplata\SmartEmailing\DI;


use Nette\DI\CompilerExtension;

class Extension extends CompilerExtension
{
    /**
     * @return void
     */
    public function loadConfiguration()
    {
        $config = $this->getConfig();
        $builder = $this->getContainerBuilder();

        $builder->addDefinition($this->prefix("client"))
            ->setFactory("LZaplata\SmartEmailing\Client", [$config["username"], $config["apiKey"]]);
    }
}
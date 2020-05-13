<?php


namespace ippey\weglotintegration\factory;


use Weglot\Client\Client;
use Weglot\Parser\ConfigProvider\ServerConfigProvider;
use Weglot\Parser\Parser;

class WeglotParserFactory implements WeglotParserFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function create($apiKey)
    {
        $config = new ServerConfigProvider();
        $client = new Client($apiKey, null);
        return new Parser($client, $config);
    }

}
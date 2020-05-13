<?php


namespace ippey\weglotintegration\factory;


use Weglot\Parser\Parser;

interface WeglotParserFactoryInterface
{
    /**
     * @param $apiKey
     * @return Parser
     */
    public function create($apiKey);
}
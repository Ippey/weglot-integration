<?php
/**
 * weglot-integration plugin for Craft CMS 3.x
 *
 * weglot integration
 *
 * @link      https://unplat.info
 * @copyright Copyright (c) 2020 Ippei Sumida
 */

namespace ippey\weglotintegration\services;

use Craft;
use ippey\weglotintegration\factory\WeglotParserFactoryInterface;

use craft\base\Component;

/**
 * WeglotService Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Ippei Sumida
 * @package   Weglotintegration
 * @since     1.0.0
 */
class WeglotService extends Component
{
    /** @var string */
    public $apiKey;
    /** @var WeglotParserFactoryInterface */
    public $parserFactory;

    // Public Methods
    // =========================================================================

    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    /**
     * This function can literally be anything you want, and you can have as many service
     * functions as you want
     *
     * From any other plugin file, call it like this:
     *
     *     Weglotintegration::$plugin->weglotService->exampleService()
     *
     * @param $text
     * @param $from
     * @param $to
     * @return mixed
     * @throws \Weglot\Client\Api\Exception\ApiError
     * @throws \Weglot\Client\Api\Exception\InputAndOutputCountMatchException
     * @throws \Weglot\Client\Api\Exception\InvalidWordTypeException
     * @throws \Weglot\Client\Api\Exception\MissingRequiredParamException
     * @throws \Weglot\Client\Api\Exception\MissingWordsOutputException
     */
    public function translate($text, $from, $to)
    {
        $parser = $this->parserFactory->create($this->getApiKey());
        return $parser->translate($text, $from, $to);
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return Craft::parseEnv($this->apiKey);
    }
}

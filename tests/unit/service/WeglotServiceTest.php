<?php namespace service;

use Codeception\Util\Stub;
use ippey\weglotintegration\factory\WeglotParserFactoryInterface;
use ippey\weglotintegration\services\WeglotService;
use Weglot\Parser\Parser;

class WeglotServiceTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /** @var WeglotService */
    private $service;
    
    protected function _before()
    {
        $this->service = new WeglotService();
        $this->service->apiKey = '$WEGLOT_API_KEY';
        $this->service->parserFactory = Stub::makeEmpty(WeglotParserFactoryInterface::class, [
            'make' => Stub::make(Parser::class, [
                'translate' => function ($text, $from, $to) {
                    return $text . ',' . $from . ',' . $to;
                },
            ]),
        ]);
    }

    protected function _after()
    {
    }

    // tests
    public function tetGetApiKey()
    {
        $apiKey = $this->service->getApiKey();
        $this->assertEquals(getenv('WEGLOT_API_KEY'), $apiKey);
    }

    public function testTranslate()
    {
        $text = 'test';
        $from = 'from';
        $to = 'to';
        $result = $this->service->translate($text, $from, $to);
        $this->assertEquals('text,from,to', $result);
    }
}
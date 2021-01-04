<?php
/**
 * weglot-integration plugin for Craft CMS 3.x
 *
 * weglot integration
 *
 * @link      https://unplat.info
 * @copyright Copyright (c) 2020 Ippei Sumida
 */

namespace ippey\weglotintegration\twigextensions;

use ippey\weglotintegration\Weglotintegration;

use Craft;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Weglot\Client\Api\Exception\ApiError;
use Weglot\Client\Api\Exception\InputAndOutputCountMatchException;
use Weglot\Client\Api\Exception\InvalidWordTypeException;
use Weglot\Client\Api\Exception\MissingRequiredParamException;
use Weglot\Client\Api\Exception\MissingWordsOutputException;
use yii\base\Exception;

/**
 * Twig can be extended in many ways; you can add extra tags, filters, tests, operators,
 * global variables, and functions. You can even extend the parser itself with
 * node visitors.
 *
 * http://twig.sensiolabs.org/doc/advanced.html
 *
 * @author    Ippei Sumida
 * @package   Weglotintegration
 * @since     1.0.0
 */
class WeglotintegrationTwigExtension extends AbstractExtension
{
    // Public Methods
    // =========================================================================

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'Weglotintegration';
    }

    /**
     * Returns an array of Twig filters, used in Twig templates via:
     *
     *      {{ 'something' | someFilter }}
     *
     * @return array
     */
    public function getFilters()
    {
        return [
            new TwigFilter('weglot_translate', [$this, 'translate']),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('weglot_snipet', [$this, 'snipet'], ['pre_escape' => 'html', 'is_safe' => ['html']]),
        ];
    }


    /**
     * Our function called via Twig; it can do anything you want
     *
     * @param string $text
     * @param string $from
     * @param string $to
     * @return string
     * @throws ApiError
     * @throws InputAndOutputCountMatchException
     * @throws InvalidWordTypeException
     * @throws MissingRequiredParamException
     * @throws MissingWordsOutputException
     */
    public function translate($text, $from = '', $to = '')
    {
        return Weglotintegration::getInstance()->weglotService->translate($text, $from, $to);
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function snipet()
    {
        return '';
//        $service = Weglotintegration::getInstance()->weglotService;
//        return Craft::$app->view->renderTemplate('weglot-integration/snipet', [
//            'apiKey' => $service->getApiKey(),
//        ]);
    }
}

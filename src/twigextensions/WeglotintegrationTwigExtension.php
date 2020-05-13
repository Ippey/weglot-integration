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

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

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
     */
    public function translate($text, $from = '', $to = '')
    {
        $service = Weglotintegration::getInstance()->weglotService;
        return $service->translate($text, $from, $to);
    }

    public function snipet()
    {
        $service = Weglotintegration::getInstance()->weglotService;
        return Craft::$app->view->renderTemplate('weglot-integration/snipet', [
            'apiKey' => $service->getApiKey(),
        ]);
    }
}

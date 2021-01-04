<?php
/**
 * weglot-integration plugin for Craft CMS 3.x
 *
 * weglot integration
 *
 * @link      https://unplat.info
 * @copyright Copyright (c) 2020 Ippei Sumida
 */

namespace ippey\weglotintegration;

use craft\events\RegisterTemplateRootsEvent;
use craft\web\View;
use ippey\weglotintegration\factory\WeglotParserFactory;
use ippey\weglotintegration\services\WeglotService;
use ippey\weglotintegration\twigextensions\WeglotintegrationTwigExtension;
use ippey\weglotintegration\models\Settings;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;

use yii\base\Event;

/**
 * Craft plugins are very much like little applications in and of themselves. We’ve made
 * it as simple as we can, but the training wheels are off. A little prior knowledge is
 * going to be required to write a plugin.
 *
 * For the purposes of the plugin docs, we’re going to assume that you know PHP and SQL,
 * as well as some semi-advanced concepts like object-oriented programming and PHP namespaces.
 *
 * https://docs.craftcms.com/v3/extend/
 *
 * @author    Ippei Sumida
 * @package   Weglotintegration
 * @since     1.0.0
 *
 * @property  WeglotService $weglotService
 * @property  Settings $settings
 * @method    Settings getSettings()
 */
class Weglotintegration extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * Weglotintegration::$plugin
     *
     * @var Weglotintegration
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * To execute your plugin’s migrations, you’ll need to increase its schema version.
     *
     * @var string
     */
    public $schemaVersion = '1.0.0';

    /**
     * Set to `true` if the plugin should have a settings view in the control panel.
     *
     * @var bool
     */
    public $hasCpSettings = true;

    /**
     * Set to `true` if the plugin should have its own section (main nav item) in the control panel.
     *
     * @var bool
     */
    public $hasCpSection = false;

    // Public Methods
    // =========================================================================

    /**
     * Set our $plugin static property to this class so that it can be accessed via
     * Weglotintegration::$plugin
     *
     * Called after the plugin class is instantiated; do any one-time initialization
     * here such as hooks and events.
     *
     * If you have a '/vendor/autoload.php' file, it will be loaded for you automatically;
     * you do not need to load it in your init() method.
     *
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Event::on(
            View::class,
            View::EVENT_REGISTER_SITE_TEMPLATE_ROOTS,
            function (RegisterTemplateRootsEvent $event) {
                $event->roots['weglot-integration'] = __DIR__ . '/templates';
            }
        );

        // Add in our Twig extensions
        Craft::$app->view->registerTwigExtension(new WeglotintegrationTwigExtension());


        $this->setComponents([
            'weglotService' => [
                'class' => WeglotService::class,
                'apiKey' => $this->getSettings()->apiKey,
                'parserFactory' => new WeglotParserFactory(),
            ],
        ]);

        // Do something after we're installed
        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                    // We were just installed
                }
            }
        );

        if (!preg_match('/^\/admin/', Craft::$app->request->url)) {
            Event::on(View::class, View::EVENT_END_BODY, function() {
                $service = Weglotintegration::getInstance()->weglotService;
                echo Craft::$app->view->renderTemplate('weglot-integration/snipet', [
                    'apiKey' => $service->getApiKey(),
                ]);
            });
        }

        /**
         * Logging in Craft involves using one of the following methods:
         *
         * Craft::trace(): record a message to trace how a piece of code runs. This is mainly for development use.
         * Craft::info(): record a message that conveys some useful information.
         * Craft::warning(): record a warning message that indicates something unexpected has happened.
         * Craft::error(): record a fatal error that should be investigated as soon as possible.
         *
         * Unless `devMode` is on, only Craft::warning() & Craft::error() will log to `craft/storage/logs/web.log`
         *
         * It's recommended that you pass in the magic constant `__METHOD__` as the second parameter, which sets
         * the category to the method (prefixed with the fully qualified class name) where the constant appears.
         *
         * To enable the Yii debug toolbar, go to your user account in the AdminCP and check the
         * [] Show the debug toolbar on the front end & [] Show the debug toolbar on the Control Panel
         *
         * http://www.yiiframework.com/doc-2.0/guide-runtime-logging.html
         */
        Craft::info(
            Craft::t(
                'weglot-integration',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

    /**
     * Creates and returns the model used to store the plugin’s settings.
     *
     * @return \craft\base\Model|null
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * Returns the rendered settings HTML, which will be inserted into the content
     * block on the settings page.
     *
     * @return string The rendered settings HTML
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'weglot-integration/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}

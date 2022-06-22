<?php declare(strict_types=1);

namespace Crate\View;

use Citrus\Framework\Application;
use Crate\View\Twig\Extensions\CrateExtension;
use Crate\View\Twig\Extensions\StackExtension;
use Crate\View\Twig\Loaders\TemplateLoader;
use Twig\Environment;

class View
{

    /**
     * Citrus Application
     *
     * @var Application
     */
    protected Application $app;

    /**
     * TWIG Environment
     *
     * @var Environment
     */
    protected Environment $twig;

    /**
     * Global Template values
     *
     * @var array
     */
    protected array $globals;

    /**
     * Create a new View instance.
     *
     * @param Application $citrus
     * @param TemplateLoader $loader
     */
    public function __construct(Application $citrus, TemplateLoader $loader)
    {
        $this->app = $citrus;
        $this->globals = [];

        // Create Twig Environment
        $this->twig = new Environment($loader, [
            'auto_reload'       => true,
            'cache'             => path(':cache/views'),
            'debug'             => !$citrus->configurator->isProduction(),
            'strict_variables'  => true
        ]);

        // Add Debug Extension
        if (!$citrus->configurator->isProduction()) {
            $this->twig->addExtension(new \Twig\Extension\DebugExtension);
        }

        // Add Intl Extension
        $this->twig->addExtension(new \Twig\Extra\Intl\IntlExtension);

        // Add Crate/Backend Extensions
        $this->twig->addExtension(new CrateExtension);
        $this->twig->addExtension(new StackExtension);
    }

    /**
     * Get TWIG Environment
     *
     * @return Environment
     */
    public function getTwigEnvironment(): Environment
    {
        return $this->twig;
    }

    /**
     * Add Global Value
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function addGlobal(string $key, mixed $value): void
    {
        $this->globals[$key] = $value;
    }

    /**
     * Add Global Values
     *
     * @param array $globals
     * @return void
     */
    public function addGlobals(array $globals): void
    {
        $this->globals = array_merge($this->globals, $globals);
    }

    /**
     * Render a Template File.
     *
     * @param string $template
     * @param array $context
     * @return string
     */
    public function render(string $template, array $context = []): string
    {
        return $this->twig->render($template, array_merge($this->globals, $context));
    }

}

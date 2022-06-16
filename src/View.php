<?php declare(strict_types=1);

namespace Crate\View\View;

use Citrus\Framework\Application;
use Crate\Backend\View\Twig\BackendExtension;
use Crate\Backend\View\Twig\StackExtension;
use Crate\Backend\View\Twig\TemplateLoader;
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
     * Create a new View instance.
     *
     * @param Application $citrus
     * @param TemplateLoader $loader
     */
    public function __construct(Application $citrus, TemplateLoader $loader)
    {
        $this->app = $citrus;

        // Create Twig Environment
        $this->twig = new Environment($loader, [
            'auto_reload'       => true,
            //'cache'             => path(':cache/views'),
            'debug'             => !$citrus->configurator->isProduction(),
            'strict_variables'  => true
        ]);

        // Add Debug Extension
        if (!$citrus->configurator->isProduction()) {
            $this->twig->addExtension(new \Twig\Extension\DebugExtension);
        }

        // Add Crate/Backend Extensions
        $this->twig->addExtension(new BackendExtension);
        $this->twig->addExtension(new StackExtension);
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
        return $this->twig->render($template, $context);
    }

}

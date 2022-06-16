<?php declare(strict_types=1);

namespace Crate\View\Twig;

use Citrus\Framework\Application;
use Crate\Core\Modules\ModuleRegistry;
use Twig\Loader\FilesystemLoader;

class TemplateLoader extends FilesystemLoader
{

    public const LAYOUT_NAMESPACE = 'layouts';
    public const PARTIAL_NAMESPACE = 'partials';
    public const COMPONENT_NAMESPACE = 'components';
    public const CUSTOM_NAMESPACE = 'custom';

    /**
     * Constructor
     */
    public function __construct(Application $citrus, ModuleRegistry $modules)
    {
        $backend = $modules->getModule('@crate/backend');

        // Template Paths
        $root = $backend->getPath('/resources/views');
        $paths = [
            self::MAIN_NAMESPACE        => [],
            self::LAYOUT_NAMESPACE      => [],
            self::PARTIAL_NAMESPACE     => [],
            self::COMPONENT_NAMESPACE   => [],
            self::CUSTOM_NAMESPACE      => [],
        ];
        //$citrus->eventManager->dispatch(StackEvent::class, 'backend:template', [&$paths]);

        // Correct Namespacing
        $namespaced = [
            self::MAIN_NAMESPACE        => $paths[self::MAIN_NAMESPACE] ?? [],
            self::LAYOUT_NAMESPACE      => $paths[self::LAYOUT_NAMESPACE] ?? [],
            self::PARTIAL_NAMESPACE     => $paths[self::PARTIAL_NAMESPACE] ?? [],
            self::COMPONENT_NAMESPACE   => $paths[self::COMPONENT_NAMESPACE] ?? [],
            self::CUSTOM_NAMESPACE      => $paths[self::CUSTOM_NAMESPACE] ?? [],            
        ];
        unset($paths[self::MAIN_NAMESPACE]);
        unset($paths[self::LAYOUT_NAMESPACE]);
        unset($paths[self::PARTIAL_NAMESPACE]);
        unset($paths[self::COMPONENT_NAMESPACE]);

        // Loop Custom
        foreach ($paths AS $key => $value) {
            if (!is_array($value)) {
                $value = [$value];
            }

            if (is_numeric($key)) {
                $namespaced[self::CUSTOM_NAMESPACE] = array_merge(
                    $namespaced[self::CUSTOM_NAMESPACE], $value
                );
            } else {
                $namespaced[$key] = $value;
            }
        }

        // Call Parent Contructor
        $namespaced[self::MAIN_NAMESPACE][] = $root . DIRECTORY_SEPARATOR;
        $namespaced[self::LAYOUT_NAMESPACE][] = $root . DIRECTORY_SEPARATOR . '_layouts';
        $namespaced[self::PARTIAL_NAMESPACE][] = $root . DIRECTORY_SEPARATOR . '_partials';
        $namespaced[self::COMPONENT_NAMESPACE][] = $root . DIRECTORY_SEPARATOR . '_components';
        parent::__construct([], $root);

        foreach ($namespaced AS $namespace => $paths) {
            $this->setPaths($paths, $namespace);
        }
    }

    /**
     * Find a template, based on it's name.
     * 
     * @return ?string
     */
    protected function findTemplate(string $name, bool $throw = true)
    {
        if (!str_ends_with($name, '.twig')) {
            $name .= '.twig';
        }

        return parent::findTemplate($name, $throw);
    }

}

<?php declare(strict_types=1);

namespace Crate\View\Twig\Extensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class CrateExtension extends AbstractExtension
{

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return \Twig\TwigFilter[]
     */
    public function getFilters()
    {
        return [
            new TwigFilter('asset', [$this, 'assetFilter']),
            new TwigFilter('path', [$this, 'pathFilter'])
        ];
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return \Twig\TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('assets', [$this, 'assetsFunction'])
        ];
    }

    public function assetFilter(string $path, string $type = 'path', ?string $format = null)
    {

        if ($type === 'path') {
            $path = ltrim($path, '/');

            if ($path[0] === '@') {
                $index = strpos($path, '/', strpos($path, '/')+1);
                $module = substr($path, 0, $index);
                $path = substr($path, $index+1);
            } else {
                $index = strpos($path, '/', strpos($path, '/')+1);
                $module = substr($path, 0, $index);
                $path = substr($path, $index+1);
            }

            $public = path(':public/' . $module . '/' . $path);
            $folder = dirname($public);
            if (!file_exists($folder)) {
                @mkdir($folder, 0666, true);
            }

            $target = path(':modules/' . $module . '/public/' . $path);
            if (file_exists($target)) {
                if (!file_exists($public)) {
                    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                        exec('mklink "'. $public .'" "'. $target .'"');
                    } else {
                        symlink($target, $public);
                    }
                }
            }

            $assetPath = substr($public, strlen(path(':public')));
            if (DIRECTORY_SEPARATOR !== '/') {
                $assetPath = str_replace(DIRECTORY_SEPARATOR, '/', $assetPath);
            }

            return $assetPath;
        }

    }

    public function pathFilter()
    {

    }

    public function assetsFunction()
    {
        
    }

}

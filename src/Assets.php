<?php declare(strict_types=1);

namespace Crate\View;

use Citrus\Contracts\MultitonContract;
use Citrus\Framework\Application;

class Assets implements MultitonContract
{

    /**
     * Create a new Assets instance.
     *
     * @param string $set
     */
    public function __construct(Application $citrus, string $set = 'default')
    {
        $this->app = $citrus;
        $this->set = $set;
    }

    /**
     * Add a new CSS file to the Asset set.
     *
     * @param string $path
     * @param string|null $type
     * @param string|null $rel
     * @param array|null $attributes
     * @return void
     */
    public function cssFile(string $path, ?string $type = 'text/css', ?string $rel = null, ?array $attributes = [])
    {

    }
    
    /**
     * Add new inline CSS content to the Asset set. 
     *
     * @param string $content
     * @param string|null $type
     * @param boolean $serveAsFile
     * @param array|null $attributes
     * @return void
     */
    public function cssInline(string $content, ?string $type = 'text/css', bool $serveAsFile = false, ?array $attributes = [])
    {
        
    }

    /**
     * Add a new JavaScript file to the Asset set.
     *
     * @return void
     */
    public function jsFile()
    {

    }
    
    /**
     * Add a new inline 
     *
     * @return void
     */
    public function jsInline()
    {
        
    }

}

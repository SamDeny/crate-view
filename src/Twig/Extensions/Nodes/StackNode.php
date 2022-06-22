<?php declare(strict_types=1);

namespace Crate\View\Twig\Extensions\Nodes;

use Crate\View\Twig\Extensions\StackExtension;
use Twig\Compiler;
use Twig\Node\Node;

class StackNode extends Node
{

    /**
     * Create a new Stack Node
     *
     * @param string $name
     * @param int $lineno
     * @param string|null $tag
     */
    public function __construct(Node $body, string $name, int $lineno = 0, string $tag = null)
    {
        parent::__construct(['body' => $body], ['name' => $name], $lineno, $tag);
    }

    /**
     * Compile Node
     *
     * @param Compiler $compiler
     */
    public function compile(Compiler $compiler)
    {
        $extension = StackExtension::class;
        $ident = $this->getTemplateName() . ':' . $this->lineno;
        
        $name = $this->getAttribute('name');

        $compiler
            ->write("ob_start();\n")
            ->write("try {\n")
            ->indent()
            ->subcompile($this->getNode('body'))
            ->outdent()
            ->write("} catch (Exception \$e) {\n")
            ->indent()
            ->write("ob_end_clean();\n")
            ->write("throw \$e;\n")
            ->outdent()
            ->write("}\n\n")
            ->write("\$extension = \$this->env->getExtension('{$extension}');\n")
            ->write("\$manager = \$extension->getManager();\n\n")
            ->write("\$manager->addContent('{$name}', ob_get_clean(), 'initial', '{$ident}');\n\n")
            ->write("echo \"{###{$extension}::{$name}###}\n\";");
    }

}

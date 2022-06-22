<?php declare(strict_types=1);

namespace Crate\View\Twig\Extensions\Nodes;

use Crate\View\Twig\Extensions\StackExtension;
use Twig\Compiler;
use Twig\Node\Node;

class StackReferenceNode extends Node
{

    /**
     * Create a new StackReferenceNode
     *
     * @param Node $body
     * @param int $lineno
     * @param string|null $tag
     */
    public function __construct(Node $body, int $lineno = 0, string $tag = null)
    {
        parent::__construct(['body' => $body], [], $lineno, $tag);
    }

    /**
     * Compile Node
     *
     * @param Compiler $compiler
     */
    public function compile(Compiler $compiler)
    {
        $extension = StackExtension::class;

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
            ->write("\$manager = \$extension->getManager();\n")
            ->write("echo \$manager->replaceStacks(ob_get_clean());\n\n");
    }

}

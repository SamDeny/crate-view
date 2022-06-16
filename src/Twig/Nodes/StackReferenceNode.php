<?php declare(strict_types=1);

namespace Crate\View\Twig\Nodes;

use Twig\Compiler;
use Twig\Node\Node;

class StackReferenceNode extends Node
{

    /**
     * Create a new StackReferenceNode
     *
     * @param string $name
     * @param integer $lineno
     * @param string|null $tag
     */
    public function __construct(string $name, int $lineno, string $tag = null)
    {
        parent::__construct([], ['name' => $name], $lineno, $tag);
    }

    /**
     * Compile Stack
     *
     * @param Compiler $compiler
     * @return void
     */
    public function compile(Compiler $compiler): void
    {
        $compiler
            ->addDebugInfo($this)
            ->write(sprintf("\$this->displayBlock('%s', \$context, \$blocks);\n", $this->getAttribute('name')))
        ;
    }

}

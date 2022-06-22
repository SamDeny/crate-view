<?php declare(strict_types=1);

namespace Crate\View\Twig\Extensions\NodeVisitors;

use Crate\View\Twig\Extensions\Nodes\StackReferenceNode;
use Twig\Environment;
use Twig\Node\ModuleNode;
use Twig\Node\Node;
use Twig\NodeVisitor\NodeVisitorInterface;

class StackNodeVisitor implements NodeVisitorInterface
{

    /**
     * Stack Nodes
     *
     * @var StackReferenceNode[]
     */
    protected array $stacks;

    /**
     * Stack Item Nodes
     *
     * @var StackItemNodes[][]
     */
    protected array $nodes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->stacks = [];
        $this->nodes = [];
    }

    /**
     * @inheritDoc
     */
    public function enterNode(Node $node, Environment $env): Node
    {
        return $node;
    }

    /**
     * @inheritDoc
     */
    public function leaveNode(Node $node, Environment $env): ?Node
    {
        if (!($node instanceof ModuleNode)) {
            return $node;
        }

        if ($node->hasNode('body') && !$node->hasNode('parent')) {
            $body = $node->getNode('body');
            $node->setNode('body', new StackReferenceNode($body));
        }

        return $node;
    }

    /**
     * @inheritDoc
     */
    public function getPriority()
    {
        return -10;
    }

}

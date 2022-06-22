<?php declare(strict_types=1);

namespace Crate\View\Twig\Extensions;

use Crate\View\Twig\Extensions\NodeVisitors\StackNodeVisitor;
use Crate\View\Twig\Extensions\TokenParser\StackItemTokenParser;
use Crate\View\Twig\Extensions\TokenParser\StackTokenParser;
use Twig\Extension\AbstractExtension;
use Twig\TokenParser\TokenParserInterface;

class StackExtension extends AbstractExtension
{

    /**
     * Stack Manager
     *
     * @var StackManager
     */
    protected StackManager $manager;

    /**
     * Append Tag Name
     *
     * @var string
     */
    protected string $append;

    /**
     * Prepend Tag Name
     *
     * @var string
     */
    protected string $prepend;

    /**
     * Create a new Stack Extension
     *
     * @param string $append The desired tag name to append.
     * @param string $prepend The desired tag name to prepend.
     */
    public function __construct(string $append = 'push', string $prepend = 'unshift')
    {
        $this->manager = new StackManager();
        $this->append = $append;
        $this->prepend = $prepend;
    }

    /**
     * Get Token Manager
     *
     * @return StackManager
     */
    public function getManager(): StackManager
    {
        return $this->manager;
    }

    /**
     * Returns the token parser instances to add to the existing list.
     *
     * @return TokenParserInterface[]
     */
    public function getTokenParsers()
    {
        return [
            new StackTokenParser,
            new StackItemTokenParser($this->append, 'append', false),
            new StackItemTokenParser($this->append . 'Once', 'append', true),
            new StackItemTokenParser($this->prepend, 'prepend', false),
            new StackItemTokenParser($this->prepend . 'Once', 'prepend', true)
        ];
    }

    /**
     * Get Token Manager
     *
     * @return StacksManager
     */
    public function getNodeVisitors()
    {
        return [
            new StackNodeVisitor
        ];
    }

}

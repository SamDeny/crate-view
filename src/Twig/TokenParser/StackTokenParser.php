<?php declare(strict_types=1);

namespace Crate\View\Twig\TokenParser;

use Crate\Backend\View\Twig\Nodes\StackReferenceNode;
use Twig\Error\SyntaxError;
use Twig\Node\BlockNode;
use Twig\Node\Node;
use Twig\Node\PrintNode;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

class StackTokenParser extends AbstractTokenParser
{

    /**
     * Stacks
     *
     * @var array
     */
    protected array $stacks = [];
    
    /**
     * Parse Token
     *
     * @param Token $token
     * @return void
     */
    public function parse(Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();
        $name = $stream->expect(Token::NAME_TYPE)->getValue();
        $stack = 'stack_' . $name;

        // Check if StackBlock does already exist.
        if (array_key_exists($stack, $this->stacks)) {
            throw new SyntaxError(
                sprintf("The stack '%s' has already been defined on line %d.", $name, $this->stacks[$stack]), 
                $stream->getCurrent()->getLine(), 
                $stream->getSourceContext()
            );
        }
        $this->stacks[$stack] = $lineno;
        $this->parser->setBlock($stack, $block = new BlockNode($stack, new Node([]), $lineno));
        $this->parser->pushLocalScope();
        $this->parser->pushBlockStack($stack);

        // Read
        if ($stream->nextIf(Token::BLOCK_END_TYPE)) {
            $body = $this->parser->subparse([$this, 'decideStackEnd'], true);
            if ($token = $stream->nextIf(Token::NAME_TYPE)) {
                $value = $token->getValue();

                if ($value != $name) {
                    throw new SyntaxError(
                        sprintf('Expected endblock for stack "%s" (but "%s" given).', $name, $value), 
                        $stream->getCurrent()->getLine(), 
                        $stream->getSourceContext()
                    );
                }
            }
        } else {
            $body = new Node([
                new PrintNode($this->parser->getExpressionParser()->parseExpression(), $lineno),
            ]);
        }
        $stream->expect(Token::BLOCK_END_TYPE);

        // Set Data
        $block->setNode('body', $body);
        $this->parser->popBlockStack();
        $this->parser->popLocalScope();
        return new StackReferenceNode($stack, $lineno, $this->getTag());
    }

    /**
     * End Stack Token Tag
     *
     * @param Token $token
     * @return boolean
     */
    public function decideStackEnd(Token $token): bool
    {
        return $token->test('endstack');
    }

    /**
     * Get Token Tag
     *
     * @return string
     */
    public function getTag()
    {
        return 'stack';
    }

}

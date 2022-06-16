<?php declare(strict_types=1);

namespace Crate\View\Twig\TokenParser;

use Twig\Error\SyntaxError;
use Twig\Node\Node;
use Twig\Node\PrintNode;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

class UnshiftTokenParser extends AbstractTokenParser
{

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

        // Check if StackBlock exists.
        if (!$this->parser->hasBlock($stack)) {
            throw new SyntaxError(
                sprintf("The stack '%s' has not been defined yet.", $name), 
                $stream->getCurrent()->getLine(), 
                $stream->getSourceContext()
            );
        }
        $block = $this->parser->getBlock($stack);

        // Read
        if ($stream->nextIf(Token::BLOCK_END_TYPE)) {
            $body = $this->parser->subparse([$this, 'decidePushEnd'], true);
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

        $block->getNode('0')->getNode('body')->setAttribute(
            'data',
            $body->getAttribute('data') . "\n" . 
            $block->getNode('0')->getNode('body')->getAttribute('data')
        );
    }

    /**
     * End Stack Token Tag
     *
     * @param Token $token
     * @return boolean
     */
    public function decidePushEnd(Token $token): bool
    {
        return $token->test('endunshift');
    }

    /**
     * Get Token Tag
     *
     * @return string
     */
    public function getTag()
    {
        return 'unshift';
    }

}

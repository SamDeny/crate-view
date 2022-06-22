<?php declare(strict_types=1);

namespace Crate\View\Twig\Extensions\TokenParser;

use Crate\View\Twig\Extensions\Nodes\StackNode;
use Twig\Node\Node;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

class StackTokenParser extends AbstractTokenParser
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

        // Get Name
        $name = $stream->expect(Token::NAME_TYPE)->getValue();

        // Get Content
        $this->parser->pushLocalScope();
        if ($stream->nextIf(Token::NAME_TYPE)) {
            $stream->expect(Token::BLOCK_END_TYPE);
            $body = new Node([]);
        } else {
            $stream->expect(Token::BLOCK_END_TYPE);
            $body = $this->parser->subparse([$this, 'decideStackEnd'], true);
            $stream->expect(Token::BLOCK_END_TYPE);
        }
        $this->parser->popLocalScope();

        return new StackNode($body, $name, $lineno);
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

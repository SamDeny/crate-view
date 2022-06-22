<?php declare(strict_types=1);

namespace Crate\View\Twig\Extensions\TokenParser;

use Crate\View\Twig\Extensions\Nodes\StackItemNode;
use Twig\Error\SyntaxError;
use Twig\Node\Node;
use Twig\Node\PrintNode;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

class StackItemTokenParser extends AbstractTokenParser
{

    /**
     * Used Token tag
     *
     * @var string
     */
    protected string $tag;

    /**
     * Stack token mode
     *
     * @var string
     */
    protected string $mode;

    /**
     * Stack token once
     *
     * @var boolean
     */
    protected bool $once;

    /**
     * Create a new StackItemTokenParser
     *
     * @param string $tag The desired tag name.
     * @param string $mode The desired tag mode (append or prepend).
     * @param boolean $once Switch if tag can only be added once.
     */
    public function __construct(string $tag, string $mode = 'append', bool $once = false)
    {
        $this->tag = strtolower($tag);
        $this->mode = $mode === 'prepend'? 'prepend': 'append';
        $this->once = $once;
    }

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
        $this->parser->pushLocalScope();

        if ($stream->nextIf(Token::BLOCK_END_TYPE)) {
            $body = $this->parser->subparse([$this, 'decideStackEnd'], true);
            if ($token = $stream->nextIf(Token::NAME_TYPE)) {
                $value = $token->getValue();

                if ($value != $name) {
                    throw new SyntaxError(
                        sprintf("Expected end$this->tag for stack '$name', but got %s", $value),
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
        $this->parser->popLocalScope();
        $stream->expect(Token::BLOCK_END_TYPE);

        return new StackItemNode($body, $name, $this->mode, $this->once, $lineno);
    }

    /**
     * End Stack Token Tag
     *
     * @param Token $token
     * @return boolean
     */
    public function decideStackEnd(Token $token): bool
    {
        return $token->test('end' . $this->tag);
    }

    /**
     * Get Token Tag
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

}

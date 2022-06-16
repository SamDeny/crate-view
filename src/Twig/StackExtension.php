<?php declare(strict_types=1);

namespace Crate\View\Twig;

use Crate\Backend\View\Twig\TokenParser\PushTokenParser;
use Crate\Backend\View\Twig\TokenParser\StackTokenParser;
use Crate\Backend\View\Twig\TokenParser\UnshiftTokenParser;
use Twig\Extension\AbstractExtension;
use Twig\TokenParser\TokenParserInterface;

class StackExtension extends AbstractExtension
{

    /**
     * Returns the token parser instances to add to the existing list.
     *
     * @return TokenParserInterface[]
     */
    public function getTokenParsers()
    {
        return [
            new StackTokenParser,
            new PushTokenParser,
            new UnshiftTokenParser
        ];
    }

}

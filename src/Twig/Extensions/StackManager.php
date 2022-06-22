<?php declare(strict_types=1);

namespace Crate\View\Twig\Extensions;

class StackManager
{

    /**
     * Available Stacks
     *
     * @var Stack[]
     */
    protected array $stacks = [];

    /**
     * Push content into an existing Stack.
     *
     * @param string $name
     * @param string $content
     * @param string $mode
     * @param ?string $once
     * @return void
     */
    public function addContent(string $name, string $content, string $mode = 'append', ?string $once = null): void
    {
        if (!array_key_exists($name, $this->stacks)) {
            $this->stacks[$name] = new Stack($name);
        }
        $stack = $this->stacks[$name];

        if ($mode === 'append') {
            $stack->pushContent($content, $once);
        } else if ($mode === 'initial') {
            $stack->initialContent($content, $once);
        } else {
            $stack->unshiftContent($content, $once);
        }
    }

    /**
     * Get Content by Stack
     *
     * @param string $name
     * @return void
     */
    public function getContent(string $name)
    {
        $this->stack = $this->stacks[$name];
        return implode("\n", array_filter($this->stack->getContent()));
    }

    /**
     * Replace Stacks
     *
     * @param string $content
     * @return void
     */
    public function replaceStacks(string $content)
    {
        $extension = StackExtension::class;
        $regex = '/\{###'.str_replace('\\', '\\\\', $extension).'::(.+)###\}/';

        return preg_replace_callback($regex, fn ($matches) => $this->getContent($matches[1]), $content);
    }

}

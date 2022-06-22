<?php declare(strict_types=1);

namespace Crate\View\Twig\Extensions;

class Stack
{

    /**
     * Unique Stack Name
     *
     * @var string
     */
    protected string $name;

    /**
     * Unique List
     *
     * @var string[]
     */
    protected array $unique = [];

    /**
     * Prepending Stack Content
     *
     * @var string[]
     */
    protected array $prepends = [];

    /**
     * Initial Stack Content
     *
     * @var string[]
     */
    protected array $initial = [];

    /**
     * Appending Stack Content
     *
     * @var string[]
     */
    protected array $appends = [];

    /**
     * Create a new Stack
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get Stack Name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set initial Content.
     *
     * @param string $content
     * @param ?string
     * @return void
     */
    public function initialContent(string $content, ?string $once = null): void
    {
        if (is_null($once) || !in_array($once, $this->unique)) {
            $this->initial = [$content];
        }
        if (!is_null($once)) {
            $this->unique[] = $once;
        }
    }

    /**
     * Push Content into Stack.
     *
     * @param string $content
     * @param ?string
     * @return void
     */
    public function pushContent(string $content, ?string $once = null): void
    {
        if (is_null($once) || !in_array($once, $this->unique)) {
            $this->appends[] = $content;
        }
        if (!is_null($once)) {
            $this->unique[] = $once;
        }
    }

    /**
     * Unshift content into Stack
     *
     * @param string $content
     * @param ?string
     * @return void
     */
    public function unshiftContent(string $content, ?string $once = null): void
    {
        if (is_null($once) || !in_array($once, $this->unique)) {
            $this->prepends[] = $content;
        }
        if (!is_null($once)) {
            $this->unique[] = $once;
        }
    }

    /**
     * Get collected Stack Content.
     *
     * @return string[]
     */
    public function getContent(): array
    {
        return array_merge(
            array_reverse($this->prepends),
            $this->initial,
            $this->appends
        );
    }

}

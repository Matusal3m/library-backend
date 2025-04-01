<?php
namespace App\Util;

class HTMLBuilder
{
    private string $content = '';

    public function __construct(
        private HTMLTableGenerator $tableGenerator
    ) {
    }

    /**
     * Add a new table to the HTML content.
     *
     * @param array $items Table content (arrays or objects)
     * @param array $headers Column headers
     * @param array|null $methods Methods to call on object items (index-aligned with headers)
     * @return self
     * @throws Exception On invalid data structure or method errors
     */
    public function addTable(array $items, array $headers, ?array $methods = null): self
    {
        $this->content .= $this->tableGenerator->generateTable($items, $headers, $methods);
        return $this;
    }

    /**
     * Return the HTML content created previously.
     *
     */
    public function finish(): string
    {
        return '<html><body>' . $this->content . '</body></html>';
    }
}

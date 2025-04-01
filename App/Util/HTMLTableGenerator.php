<?php
namespace App\Util;

use Exception;

class HTMLTableGenerator
{
    /**
     * Generate HTML table from provided data and headers.
     *
     * @param array $items Table content (arrays or objects)
     * @param array $headers Column headers
     * @param array|null $methods Methods to call on object items (index-aligned with headers)
     * @return self
     * @throws Exception On invalid data structure or method errors
     */
    public function generateTable(array $items, array $headers, ?array $methods = null)
    {
        $this->validateInput($headers, $methods);

        $table = $this->buildTableStart($headers);
        $table .= $this->buildTableBody($items, $headers, $methods);
        $table .= '</table>';

        return $table;
    }

    private function validateInput(array $headers, ?array $methods): void
    {
        if ($methods && count($methods) !== count($headers)) {
            throw new Exception('Methods array length must match headers count');
        }
    }

    private function buildTableStart(array $headers): string
    {
        $html = '<table><thead><tr>';
        foreach ($headers as $header) {
            $html .= '<th>' . htmlspecialchars($header) . '</th>';
        }
        return $html . '</tr></thead>';
    }

    private function buildTableBody(array $items, array $headers, ?array $methods): string
    {
        $html = '<tbody>';
        foreach ($items as $item) {
            $html .= '<tr>';
            foreach ($headers as $index => $header) {
                $html .= $this->buildTableCell($item, $index, $methods);
            }
            $html .= '</tr>';
        }
        return $html . '</tbody>';
    }

    private function buildTableCell($item, int $index, ?array $methods): string
    {
        try {
            $value = $methods
            ? $this->getValueFromObject($item, $methods[$index])
            : $item;

            return '<td>' . $value . '</td>';
        } catch (Exception $e) {
            throw new Exception("Error processing item at index $index: " . $e->getMessage());
        }
    }

    private function getValueFromObject($item, string $method)
    {
        if (! is_object($item)) {
            throw new Exception('Expected object when methods are specified');
        }

        if (! method_exists($item, $method)) {
            throw new Exception("Method $method not found on object");
        }

        $value = $item->{$method}();
        if (is_object($value)) {
            throw new Exception("Method $method returned an object");
        }

        return $value;
    }

    private function getValueFromArray($item, int $index)
    {
        if (! array_key_exists($index, $item)) {
            throw new Exception("Index $index not found in array item");
        }

        return $item[$index];
    }
}

<?php

declare(strict_types=1);

namespace DragonCode\Benchmark\View;

use function array_keys;
use function array_map;
use function array_values;
use function implode;
use function max;
use function mb_str_pad;
use function mb_strlen;

class TableView extends View
{
    /**
     * Displays data as a table.
     *
     * @param  array  $data  An array of table rows.
     */
    public function show(array $data): void
    {
        $headers = $this->headers($data);
        $widths  = $this->columnWidths($headers, $data);

        $this->writeLine($this->separator($widths));
        $this->writeLine($this->formatRow($headers, $widths));
        $this->writeLine($this->separator($widths));

        foreach ($data as $row) {
            if ($row === [null]) {
                $this->writeLine($this->separator($widths));

                continue;
            }

            $this->writeLine($this->formatRow(array_values($row), $widths));
        }

        $this->writeLine($this->separator($widths));
    }

    /**
     * Extracts column headers from the data.
     *
     * @param  array  $data  An array of table rows.
     */
    protected function headers(array $data): array
    {
        return array_keys(array_values($data)[0]);
    }

    /**
     * Calculates the width of each column based on headers and data.
     *
     * @param  array  $headers  The column headers.
     * @param  array  $data  An array of table rows.
     */
    protected function columnWidths(array $headers, array $data): array
    {
        $widths = array_map(static fn ($header) => mb_strlen((string) $header), $headers);

        foreach ($data as $row) {
            foreach (array_values($row) as $i => $value) {
                $widths[$i] = max($widths[$i], mb_strlen((string) $value));
            }
        }

        return $widths;
    }

    /**
     * Creates a table separator line.
     *
     * @param  array  $widths  An array of column widths.
     */
    protected function separator(array $widths): string
    {
        $parts = array_map(fn (int $w) => str_repeat('-', $w + 2), $widths);

        return '+' . implode('+', $parts) . '+';
    }

    /**
     * Formats a table row with column width alignment.
     *
     * @param  array  $values  The cell values of the row.
     * @param  array  $widths  An array of column widths.
     */
    protected function formatRow(array $values, array $widths): string
    {
        $cells = [];

        foreach ($values as $i => $value) {
            $cells[] = ' ' . mb_str_pad((string) $value, $widths[$i]) . ' ';
        }

        return '|' . implode('|', $cells) . '|';
    }
}

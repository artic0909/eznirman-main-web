<?php

namespace App\Services;

use Spatie\SimpleExcel\SimpleExcelWriter;

class ExportService
{
    /**
     * Export a given query builder directly to a streamed Excel download.
     *
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $query
     * @param string $filename
     * @param callable $mapCallback
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public static function exportToExcel($query, $filename, callable $mapCallback)
    {
        $writer = SimpleExcelWriter::streamDownload($filename);

        $query->chunk(1000, function ($records) use ($writer, $mapCallback) {
            foreach ($records as $record) {
                $writer->addRow($mapCallback($record));
            }
        });

        return $writer->toBrowser();
    }
}

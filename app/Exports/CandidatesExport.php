<?php

namespace App\Exports;

use App\Models\Candidate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CandidatesExport
{
    protected $query;

    public function __construct($query = null)
    {
        $this->query = $query ?? Candidate::query();
    }

    public function download($filename = 'candidates.xlsx')
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = [
            'Name',
            'Portfolio',
            'Department',
            'Year',
            'Votes',
            'Active Status',
            'Created At'
        ];

        foreach (range('A', chr(65 + count($headers) - 1)) as $i => $column) {
            $sheet->setCellValue($column . '1', $headers[$i]);
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Style the header row
        $headerRange = 'A1:' . chr(65 + count($headers) - 1) . '1';
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'],
            ],
        ]);

        // Add data
        $row = 2;
        $candidates = $this->query
            ->with(['portfolio', 'votes'])
            ->get();

        foreach ($candidates as $candidate) {
            $sheet->fromArray([
                $candidate->name,
                $candidate->portfolio?->name ?? 'N/A',
                $candidate->department ?? 'N/A',
                $candidate->year ?? 'N/A',
                $candidate->votes()->count(),
                $candidate->is_active ? 'Yes' : 'No',
                $candidate->created_at->format('Y-m-d H:i:s'),
            ], null, 'A' . $row);

            // Add zebra striping
            if ($row % 2 == 0) {
                $dataRange = 'A' . $row . ':' . chr(65 + count($headers) - 1) . $row;
                $sheet->getStyle($dataRange)->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F3F4F6'],
                    ],
                ]);
            }

            $row++;
        }

        // Create totals row
        $totalRow = $row;
        $sheet->setCellValue('A' . $totalRow, 'Total');
        $sheet->setCellValue('E' . $totalRow, '=SUM(E2:E' . ($row - 1) . ')');

        $totalRange = 'A' . $totalRow . ':' . chr(65 + count($headers) - 1) . $totalRow;
        $sheet->getStyle($totalRange)->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'EEF2FF'],
            ],
        ]);

        // Create the response
        $writer = new Xlsx($spreadsheet);
        $temp_file = tempnam(sys_get_temp_dir(), 'candidates_');
        $writer->save($temp_file);

        return response()->download($temp_file, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}

<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportStudents extends Command
{
    protected $signature = 'students:import {file}';
    protected $description = 'Import students from Excel file';

    public function handle()
    {
        $file = $this->argument('file');

        if (!file_exists($file)) {
            $this->error("File not found: {$file}");
            return 1;
        }

        $spreadsheet = IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        // Remove header row
        array_shift($rows);

        $bar = $this->output->createProgressBar(count($rows));
        $this->info("Importing students...\n");

        foreach ($rows as $row) {
            [$name, $rollNumber, $email, $mobile] = $row;

            // Skip empty rows
            if (empty($name) || empty($rollNumber)) {
                continue;
            }

            User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'roll_number' => $rollNumber,
                    'mobile' => $mobile,
                    'password' => Hash::make($rollNumber), // Use roll number as default password
                    'role' => 'voter',
                ]
            );

            $bar->advance();
        }

        $bar->finish();
        $this->info("\nStudents imported successfully!");

        return 0;
    }
}

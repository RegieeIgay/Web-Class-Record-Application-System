<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Your code using PhpSpreadsheet

// Example: Create a simple spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Hello World !');

// Save the spreadsheet
$writer = new Xlsx($spreadsheet);
$writer->save('hello_world.xlsx');

echo 'Spreadsheet created successfully!';

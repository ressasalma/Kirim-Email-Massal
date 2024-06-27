<?php
// Set the filename
$filename = 'format.csv';

// Set content type and headers for download
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

// Create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// Define column headers
$columns = array('nama', 'alamat_email');

// Output the column headers
fputcsv($output, $columns);

// Close the file pointer
fclose($output);
?>

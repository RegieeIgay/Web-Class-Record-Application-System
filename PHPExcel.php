<?php
if (isset($_POST['import'])) {
    // Check if a file was uploaded
    if ($_FILES['file']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['file']['tmp_name'])) {
        // Include PHPExcel library
        require 'path/to/your/PHPExcel/PHPExcel.php';

        // Path to the uploaded Excel file
        $excelFilePath = $_FILES['file']['tmp_name'];

        // Create a new PHPExcel object
        $objPHPExcel = PHPExcel_IOFactory::load($excelFilePath);

        // Get the active sheet
        $sheet = $objPHPExcel->getActiveSheet();

        // Get the highest row number and column letter
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // Convert the column letter to a numeric index
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

        // Establish a database connection (modify database credentials as needed)
        $host = "your_database_host";
        $username = "your_database_username";
        $password = "your_database_password";
        $database = "your_database_name";

        $conn = new mysqli($host, $username, $password, $database);

        // Check the database connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Iterate through the rows and columns to get the data
        for ($row = 2; $row <= $highestRow; ++$row) { // Start from row 2 to skip the header row
            // Get the cell values
            $name = $sheet->getCellByColumnAndRow(0, $row)->getValue();
            $rollNumber = $sheet->getCellByColumnAndRow(1, $row)->getValue();

            // Process and insert data into the database
            $sql = "INSERT INTO student_data (name, roll_number) VALUES ('$name', '$rollNumber')";

            if ($conn->query($sql) === TRUE) {
                echo "Record inserted successfully<br>";
            } else {
                echo "Error inserting record: " . $conn->error . '<br>';
            }
        }

        // Close the database connection
        $conn->close();

        // Close the PHPExcel object
        $objPHPExcel->disconnectWorksheets();
        unset($objPHPExcel);

        echo "Data imported successfully.";
    } else {
        echo "Error uploading file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excel File Import</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="file">Select Excel File:</label>
        <input type="file" name="file" id="file" accept=".xlsx">
        <button type="submit" name="import">Import</button>
    </form>
</body>
</html>

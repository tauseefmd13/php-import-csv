<?php 

function importCSVDataInChuncks($pdo, $filePath, $batchSize = 1000)
{
	if (($handle = fopen($filePath, 'r')) !== false) {
		$data = [];
	    $rowCount = 0;
	    $batchCount = 0;

	    while (($row = fgetcsv($handle)) !== false) {
	    	set_time_limit(0);

	        // Skip top 5 rows (assumes these rows are either empty or not needed)
	        if ($rowCount < 6) {
	            $rowCount++;
	            continue;
	        }

	        // Modify the date column (assume date is in the 2nd column, index 1)
	        if (!empty($row[1])) {
	            $row[1] = date("Y-m-d", strtotime($row[1]));
	        }

	        $data[] = [$row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10], $row[11], $row[12], $row[13], $row[14], $row[15], $row[16], $row[17], $row[18], $row[19], $row[20], $row[21], $row[22], $row[23], $row[24], $row[25]];
	        $rowCount++;
	        $batchCount++;

	        if ($batchCount % $batchSize == 0) {
	            insertBatch($pdo, $data);
		        $data = []; // Clear data
	        }
	    }

	    if (count($data)) {
	        insertBatch($pdo, $data);
	    }

	    fclose($handle);

	    return 'CSV file imported successfully.';
	}

	return 'Unable to process the CSV file.';
}

function insertBatch($pdo, $data) 
{
    $placeholders = rtrim(str_repeat("(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?),", count($data)), ",");
    $values = [];
    foreach ($data as $row) {
        $values = array_merge($values, $row);
    }
    $sql = "INSERT INTO `temporary_complete_data` (`date`, `academic_year`, `session`, `alloted_category`, `voucher_type`, `voucher_number`, `roll_number`, `admission_number`, `status`, `fee_category`, `faculty`, `program`, `department`, `batch`, `receipt_number`, `fee_head`, `due_amount`, `paid_amount`, `concession_amount`, `scholarship_amount`, `reverse_concession_amount`, `write_off_amount`, `adjusted_amount`, `refund_amount`, `fund_transfer_amount`) VALUES $placeholders";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($values);
}

?>

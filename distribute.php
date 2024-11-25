<?php  

require 'db_connect.php';

$stmt = $pdo->query("SELECT 
		COUNT(id) AS total_records,
        SUM(due_amount) AS total_due_amount,
        SUM(paid_amount) AS total_paid_amount,
        SUM(concession_amount) AS total_concession_amount,
        SUM(scholarship_amount) AS total_scholarship_amount,
        SUM(refund_amount) AS total_refund_amount
        FROM temporary_complete_data");
    
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
	echo "Total Records: " . $result['total_records'] . "<br>";
	echo "Due Amount: " . $result['total_due_amount'] . "<br>";
	echo "Paid Amount: " . $result['total_paid_amount'] . "<br>";
	echo "Concession: " . $result['total_concession_amount'] . "<br>";
	echo "Scholarship: " . $result['total_scholarship_amount'] . "<br>";
	echo "Refund: " . $result['total_refund_amount'] . "<br>";
} else {
	echo "No records found.";
}

?>

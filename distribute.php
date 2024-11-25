<?php  

require 'db_connect.php';

$totalRecords = $pdo->query("SELECT COUNT(id) AS total FROM temporary_complete_data")->fetch(PDO::FETCH_ASSOC)['total'];
echo 'Total: ' . $totalRecords;

?>

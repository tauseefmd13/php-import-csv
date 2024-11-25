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

// Branches
$count_branch = $pdo->query("SELECT COUNT(id) AS total FROM branches")->fetch(PDO::FETCH_ASSOC)['total'];

if ($count_branch == 0) {
	$branches = $pdo->query("SELECT DISTINCT faculty FROM temporary_complete_data WHERE faculty IS NOT NULL AND TRIM(faculty) != ''")->fetchAll(PDO::FETCH_ASSOC);

	if (!empty($branches)) {
	    $pdo->prepare("INSERT INTO `branches` (`branch_name`) VALUES " . rtrim(str_repeat("(?),", count($branches)), ","))->execute(array_column($branches, "faculty"));
	    echo "Branch added successfully. <br>";
	}
}

// Fee Category
$count_fee_category = $pdo->query("SELECT COUNT(id) AS total FROM fee_category")->fetch(PDO::FETCH_ASSOC)['total'];

if ($count_fee_category == 0) {
	$fee_categories = $pdo->query("SELECT DISTINCT fee_category FROM temporary_complete_data WHERE fee_category IS NOT NULL AND TRIM(fee_category) != ''")->fetchAll(PDO::FETCH_ASSOC);

	if (!empty($fee_categories)) {
		$branches = $pdo->query("SELECT id FROM branches")->fetchAll(PDO::FETCH_ASSOC);

		if (!empty($branches)) {
			$values = [];

			foreach ($fee_categories as $fee) {
			    foreach ($branches as $branch) {
			        $values[] = "('" . $fee['fee_category'] . "', '" . $branch['id'] . "')";
			    }
			}

			$pdo->exec("INSERT INTO `fee_category` (`fee_category`, `branch_id`) VALUES " . implode(", ", $values));
		    echo "Fee category added successfully. <br>";
		}
	}
}

// Fee Collection Types
$count_fee_collection_type = $pdo->query("SELECT COUNT(id) AS total FROM fee_collection_types")->fetch(PDO::FETCH_ASSOC)['total'];

if ($count_fee_collection_type == 0) {
	$fee_collection_types = ["Academic", "Academic Misc", "Hostel", "Hostel Misc", "Transport", "Transport Misc"];
	$branches = $pdo->query("SELECT id FROM branches")->fetchAll(PDO::FETCH_ASSOC);

	if (!empty($branches)) {
		$values = [];

		foreach ($fee_collection_types as $type) {
		    foreach ($branches as $branch) {
		        $values[] = "('" . $type . "', '" . $branch['id'] . "')";
		    }
		}

		$pdo->exec("INSERT INTO `fee_collection_types` (`collection_head`, `branch_id`) VALUES " . implode(", ", $values));
	    echo "Fee collection type added successfully. <br>";
	}
}

// Fee Types
$count_fee_type = $pdo->query("SELECT COUNT(id) AS total FROM fee_types")->fetch(PDO::FETCH_ASSOC)['total'];

if ($count_fee_type == 0) {
	$fee_types = $pdo->query("SELECT DISTINCT fee_head FROM `temporary_complete_data` WHERE fee_head IS NOT NULL AND TRIM(fee_head) != ''")->fetchAll(PDO::FETCH_ASSOC);

	if (!empty($fee_types)) {
		$branches = $pdo->query("SELECT id FROM branches")->fetchAll(PDO::FETCH_ASSOC);

		if (!empty($branches)) {
			$values = [];
			$fee_category_id = 1;
			$fee_collection_type_id = 1;

			foreach ($fee_types as $key => $fee_type) {
				$seq_id = ++$key;

				if (strpos($fee_type['fee_head'], 'Transport') !== false) {
					$module_id = 3;
				} else if (strpos($fee_type['fee_head'], 'Hostel') !== false) {
					$module_id = 2;
				} else {
					$module_id = 1;
				}

			    foreach ($branches as $branch) {
			        $values[] = "('" . $fee_category_id . "', '" . $fee_type['fee_head'] . "', '" . $fee_collection_type_id . "', '" . $branch['id'] . "', '" . $seq_id . "', '" . $fee_type['fee_head'] . "', '" . $module_id . "')";
			    }
			}

			$pdo->exec("INSERT INTO `fee_types` (`fee_category_id`, `f_name`, `fee_collection_type_id`, `branch_id`, `seq_id`, `fee_type_ledger`, `fee_head_type`) VALUES " . implode(", ", $values));
		    echo "Fee types added successfully. <br>";
		}
	}
}

// Entry Mode
$count_entry_mode = $pdo->query("SELECT COUNT(id) AS total FROM entry_mode")->fetch(PDO::FETCH_ASSOC)['total'];

if ($count_entry_mode == 0) {
	$entry_modes = [
		[
			"entry_mode_name" => "DUE",
			"crdr" => "D",
			"entry_mode_number" => 0,
		],
		[
			"entry_mode_name" => "REVDUE",
			"crdr" => "C",
			"entry_mode_number" => 12,
		],
		[
			"entry_mode_name" => "SCHOLARSHIP",
			"crdr" => "C",
			"entry_mode_number" => 15,
		],
		[
			"entry_mode_name" => "SCHOLARSHIPREV/REVCONCESSION",
			"crdr" => "D",
			"entry_mode_number" => 16,
		],
		[
			"entry_mode_name" => "CONCESSION",
			"crdr" => "C",
			"entry_mode_number" => 15,
		],
		[
			"entry_mode_name" => "DUE",
			"crdr" => "D",
			"entry_mode_number" => 0,
		],
		[
			"entry_mode_name" => "RCPT",
			"crdr" => "C",
			"entry_mode_number" => 0,
		],
		[
			"entry_mode_name" => "REVRCPT",
			"crdr" => "D",
			"entry_mode_number" => 0,
		],
		[
			"entry_mode_name" => "JV",
			"crdr" => "C",
			"entry_mode_number" => 14,
		],
		[
			"entry_mode_name" => "REVJV",
			"crdr" => "D",
			"entry_mode_number" => 14,
		],
		[
			"entry_mode_name" => "PMT",
			"crdr" => "D",
			"entry_mode_number" => 1,
		],
		[
			"entry_mode_name" => "REVPMT",
			"crdr" => "C",
			"entry_mode_number" => 1,
		],
		[
			"entry_mode_name" => "Fundtransfer",
			"crdr" => "+ ve and -ve",
			"entry_mode_number" => 1,
		]
	];

	$values = [];

	foreach ($entry_modes as $entry) {
        $values[] = "('" . $entry['entry_mode_name'] . "', '" . $entry['crdr'] . "', '" . $entry['entry_mode_number'] . "')";
    }

	$pdo->exec("INSERT INTO `entry_mode` (`entry_mode_name`, `crdr`, `entry_mode_number`) VALUES " . implode(", ", $values));
    echo "Entry mode added successfully. <br>";
}

// Module
$count_module = $pdo->query("SELECT COUNT(id) AS total FROM module")->fetch(PDO::FETCH_ASSOC)['total'];

if ($count_module == 0) {
	$modules = [
		[
			"module_name" => "Academic",
			"module_id" => 1,
		],
		[
			"module_name" => "Academic Misc",
			"module_id" => 11,
		],
		[
			"module_name" => "Hostel",
			"module_id" => 2,
		],
		[
			"module_name" => "Hostel Misc",
			"module_id" => 22,
		],
		[
			"module_name" => "Transport",
			"module_id" => 3,
		],
		[
			"module_name" => "Transport Misc",
			"module_id" => 33,
		],
	];

	$values = [];

	foreach ($entry_modes as $entry) {
        $values[] = "('" . $entry['module_name'] . "', '" . $entry['module_id'] . "')";
    }

	$pdo->exec("INSERT INTO `module` (`module_name`, `module_id`) VALUES " . implode(", ", $values));
    echo "Module added successfully. <br>";
}

?>

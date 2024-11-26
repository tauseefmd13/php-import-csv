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

// Financial Trans
$count_financial_trans = $pdo->query("SELECT COUNT(id) AS total FROM financial_trans")->fetch(PDO::FETCH_ASSOC)['total'];
if ($count_financial_trans == 0) {
	$financial_trans = $pdo->query("SELECT m.academic_year, m.voucher_type, m.voucher_number, m.admission_number, SUM(m.due_amount) AS total_due_amount, SUM(m.concession_amount) AS total_concession_amount, SUM(m.scholarship_amount) AS total_scholarship_amount, SUM(m.reverse_concession_amount) AS total_reverse_concession_amount, SUM(m.write_off_amount) AS total_write_off_amount, e.crdr, e.entry_mode_number, CASE WHEN m.voucher_type = 'CONCESSION' THEN 1 WHEN m.voucher_type = 'SCHOLARSHIP' THEN 2 ELSE NULL END AS type_of_concession FROM temporary_complete_data m JOIN entry_mode e ON e.entry_mode_name = m.voucher_type WHERE m.voucher_type IN ('DUE', 'REVDUE', 'SCHOLARSHIP', 'SCHOLARSHIPREV/REVCONCESSION', 'CONCESSION') GROUP BY m.voucher_type")->fetchAll(PDO::FETCH_ASSOC);

	if (!empty($financial_trans)) {
		$financial_trans_data = [];

		foreach ($financial_trans as $key => $financial_tran) {
			$financial_trans_detail = $pdo->query("SELECT m.voucher_type, m.fee_head, SUM(m.due_amount) AS total_due_amount, SUM(m.concession_amount) AS total_concession_amount, SUM(m.scholarship_amount) AS total_scholarship_amount, SUM(m.reverse_concession_amount) AS total_reverse_concession_amount, SUM(m.write_off_amount) AS total_write_off_amount, (SELECT f.id FROM fee_types f WHERE f.f_name = m.fee_head LIMIT 1) AS head_id FROM temporary_complete_data m WHERE m.voucher_type = '".$financial_tran['voucher_type']."' GROUP BY m.fee_head")->fetchAll(PDO::FETCH_ASSOC);

			$financial_trans_data[$key] = $financial_tran;
			$financial_trans_data[$key]['financial_trans_detail'] = $financial_trans_detail;
		}

		$module_id = 1;
		$branch_id = 1;
		$trans_id = rand(100000, 999999);

		foreach ($financial_trans_data as $data) {
			$amount = array_sum([$data['total_due_amount'], $data['total_concession_amount'], $data['total_scholarship_amount'], $data['total_reverse_concession_amount'], $data['total_write_off_amount']]);
			$values = "('" . $module_id . "', '" . $trans_id . "', '" . $data['admission_number'] . "', '" . $amount . "', '" . $data['crdr'] . "', '" . date('Y-m-d') . "', '" . $data['academic_year'] . "', '" . $data['entry_mode_number'] . "', '" . $data['voucher_number'] . "', '" . $branch_id . "', '" . $data['type_of_concession'] . "')";

			$pdo->exec("INSERT INTO `financial_trans` (`module_id`, `trans_id`, `admission_number`, `amount`, `crdr`, `trans_date`, `academic_year`, `entry_mode`, `voucher_number`, `branch_id`, `type_of_concession`) VALUES " . $values);
			echo "Financial trans added successfully. <br>";

			if (!empty($data['financial_trans_detail'])) {
				$detail_values = [];

				foreach (array_chunk($data['financial_trans_detail'], 1000) as $chunks) {
					foreach ($chunks as $chunk) {
						$amount = array_sum([$chunk['total_due_amount'], $chunk['total_concession_amount'], $chunk['total_scholarship_amount'], $chunk['total_reverse_concession_amount'], $chunk['total_write_off_amount']]);
						$detail_values[] = "('" . $pdo->lastInsertId() . "', '" . $module_id . "', '" . $amount . "', '" . $chunk['head_id'] . "',  '" . $data['crdr'] . "', '" . $branch_id . "', '" . $chunk['fee_head'] . "')";
					}

					if (!empty($detail_values)) {
						$pdo->exec("INSERT INTO `financial_trans_detail` (`financial_trans_id`, `module_id`, `amount`, `head_id`, `crdr`, `branch_id`, `head_name`) VALUES " . implode(", ", $detail_values));
						echo "Financial trans detail added successfully. <br>";
					}
				}
			}
		}
	}
}

// Common Fee Collection
$count_common_fee_collection = $pdo->query("SELECT COUNT(id) AS total FROM common_fee_collection")->fetch(PDO::FETCH_ASSOC)['total'];
if ($count_common_fee_collection == 0) {
	$common_fee_collections = $pdo->query("SELECT m.academic_year, m.session, m.voucher_type, m.voucher_number, m.roll_number, m.admission_number, m.receipt_number, SUM(m.paid_amount) AS total_paid_amount, SUM(m.adjusted_amount) AS total_adjusted_amount, SUM(m.refund_amount) AS total_refund_amount, SUM(m.fund_transfer_amount) AS total_fund_transfer_amount, e.entry_mode_number, CASE WHEN e.entry_mode_name = 'RCPT' THEN 0 WHEN e.entry_mode_name = 'REVRCPT' THEN 1 WHEN e.entry_mode_name = 'JV' THEN 0 WHEN e.entry_mode_name = 'REVJV' THEN 1 WHEN e.entry_mode_name = 'PMT' THEN 0 WHEN e.entry_mode_name = 'REVPMT' THEN 1 ELSE NULL END AS inactive FROM temporary_complete_data m JOIN entry_mode e ON e.entry_mode_name = m.voucher_type WHERE m.voucher_type IN ('RCPT', 'REVRCPT', 'JV', 'REVJV', 'PMT', 'REVPMT', 'Fundtransfer') GROUP BY m.voucher_type")->fetchAll(PDO::FETCH_ASSOC);

	if (!empty($common_fee_collections)) {
		$common_fee_collections_data = [];

		foreach ($common_fee_collections as $key => $common_fee) {
			$common_fee_collections_detail = $pdo->query("SELECT m.voucher_type, m.fee_head, SUM(m.paid_amount) AS total_paid_amount, SUM(m.adjusted_amount) AS total_adjusted_amount, SUM(m.refund_amount) AS total_refund_amount, SUM(m.fund_transfer_amount) AS total_fund_transfer_amount, (SELECT f.id FROM fee_types f WHERE f.f_name = m.fee_head LIMIT 1) AS head_id FROM temporary_complete_data m WHERE m.voucher_type = '".$common_fee['voucher_type']."' GROUP BY m.fee_head")->fetchAll(PDO::FETCH_ASSOC);

			$common_fee_collections_data[$key] = $common_fee;
			$common_fee_collections_data[$key]['common_fee_collections_detail'] = $common_fee_collections_detail;
		}

		$module_id = 1;
		$branch_id = 1;
		$trans_id = rand(100000, 999999);

		foreach ($common_fee_collections_data as $data) {
			$amount = array_sum([$data['total_paid_amount'], $data['total_adjusted_amount'], $data['total_refund_amount'], $data['total_fund_transfer_amount']]);
			$values = "('" . $module_id . "', '" . $trans_id . "', '" . $data['admission_number'] . "', '" . $data['roll_number'] . "', '" . $amount . "', '" . $branch_id . "', '" . $data['academic_year'] . "', '" . $data['session'] . "', '" . $data['receipt_number'] . "', '" . $data['entry_mode_number'] . "', '" . date('Y-m-d') . "', '" . $data['inactive'] . "')";

			$pdo->exec("INSERT INTO `common_fee_collection` (`module_id`, `trans_id`, `admission_number`, `roll_number`, `amount`, `branch_id`, `academic_year`, `financial_year`, `display_receipt_number`, `entry_mode`, `paid_date`, `inactive`) VALUES " . $values);
			echo "Common fee collection added successfully. <br>";

			if (!empty($data['common_fee_collections_detail'])) {
				$detail_values = [];

				foreach (array_chunk($data['common_fee_collections_detail'], 1000) as $chunks) {
					foreach ($chunks as $chunk) {
						$amount = array_sum([$chunk['total_paid_amount'], $chunk['total_adjusted_amount'], $chunk['total_refund_amount'], $chunk['total_fund_transfer_amount']]);
						$detail_values[] = "('" . $module_id . "', '" . $pdo->lastInsertId() . "', '" . $chunk['head_id'] . "', '" . $chunk['fee_head'] . "', '" . $branch_id . "', '" . $amount . "')";
					}

					if (!empty($detail_values)) {
						$pdo->exec("INSERT INTO `common_fee_collection_headwise` (`module_id`, `receipt_id`, `head_id`, `head_name`, `branch_id`, `amount`) VALUES " . implode(", ", $detail_values));
						echo "Common fee collection detail added successfully. <br>";
					}
				}
			}
		}
	}
}

?>

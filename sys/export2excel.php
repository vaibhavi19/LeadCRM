<?php
function downloadXL($listview_sql,$filename,$header, $htmlready = false) {

	if ($htmlready == false ) {
		
		$obj = new conn();


		$result = $obj->execute($listview_sql, $error_message);
		if ($result) {
			$table_structure = "";
			// search area - top of the grid
			$column_count = mysqli_num_fields($result);

			// serial no. column
			$table_structure .= "<table  width='100%' cellpadding='0' cellspacing='0' border='1'>";
			$table_structure .= "<tr><td colspan=" . ($column_count + 1) . "><b><font size='5'> $header</font></b></td></tr>";
			$table_structure .= "<tr><td>#</td>";

			// table query columns
			while ($property = mysqli_fetch_field($result)) {
				$table_structure .= "<td><b>" . $property->name . "</b></td>";
			}
			$table_structure .= "</tr>";

			// table data - dynamic rows
			$j = 1;
			while ($row = mysqli_fetch_array($result)) {
				//            $row_start++;
				$table_structure .= "<tr>";
				$table_structure .= "<td>$j</td>";

				// table data - dynamic column
				$i = 0;  // ignore first column - pkid
				while ($i < $column_count) {
					$table_structure .= "<td>" . $row[$i] . "</td>";
					$i++;
				}
				$table_structure .= "</tr>";
				$j++;
			}

			mysqli_free_result($result);
		} else {
			$table_structure .= "<tr><td colspan=" . ($column_count + 1) . ">";
			$table_structure .= "Error in running query :" . $error_message;
			$table_structure .= "</td></tr>";
		}
		$table_structure .= "</table>";

		unset($obj);
	} else {
		$table_structure = $listview_sql;
	}

	ob_end_clean();
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$filename");
	echo $table_structure;
	$listview_sql = "";
	$table_structure = "";
	exit;
}


?>
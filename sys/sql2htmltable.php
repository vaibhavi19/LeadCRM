<?php

class GridView {

    public $width;
    public $query;

    function getHTMLTable() {
        global $obj;

        $strfieldarea = "background-color: #c4dcfb;    color: #333;    text-align: center;    border-right: 1px solid #9cbdff;    border-bottom: 1px solid #9cbdff;    border-left: 1px solid #9cbdff;    font-weight: bold;font-family: tahoma,verdana,arial,sans-serif;font-size: 11px;-webkit-print-color-adjust: exact;";
        $strfieldarea_top = "border-top: 1px solid #9cbdff;background-color: #c4dcfb;    color: #333;    text-align: center;    border-right: 1px solid #9cbdff;    border-bottom: 1px solid #9cbdff;    border-left: 1px solid #9cbdff;    font-weight: bold;font-family: tahoma,verdana,arial,sans-serif;font-size: 11px;-webkit-print-color-adjust: exact;";
        $strdatadarea = "color: rgb(102, 102, 102);border-bottom-width: 1px;border-bottom-style: solid;border-bottom-color: #9cbdff;border-right-width: 1px;border-right-style: solid;border-right-color: #9cbdff;font-family: tahoma,verdana,arial,sans-serif;font-size: 11px;-webkit-print-color-adjust: exact;";
        $strdatadarea_top = "border-top-width: 1px;border-top-style: solid;border-top-color: #9cbdff;color: rgb(102, 102, 102);border-bottom-width: 1px;border-bottom-style: solid;border-bottom-color: #9cbdff;border-right-width: 1px;border-right-style: solid;border-right-color: #9cbdff;font-family: tahoma,verdana,arial,sans-serif;font-size: 11px;-webkit-print-color-adjust: exact;";

        $table_stru = "<table border='0' cellspacing='0' cellpadding='5' " . $this->width . ">";

        // -----------------------------------------------------------------------------
        // read listview query data
        // -----------------------------------------------------------------------------
        $error_message = "";
        $result = $obj->execute($this->query, $error_message);
        $row_count = mysqli_num_rows($result);
        if ($row_count > 0) {
            if (($result) || (mysqli_errno() == 0)) {
                $column_count = mysqli_num_fields($result);

                $arr_summary = array();

                // -----------------------------------------------------------------------------
                // table header - dynamic column
                // -----------------------------------------------------------------------------
                $j = 0;
                $table_stru = $table_stru . "<tbody><tr><td style='" . $strfieldarea . "" . $strfieldarea_top . "'>#</td>";
                while ($property = mysqli_fetch_field($result)) {
                    $column_name = $property->name;
                    $column_style = "";
                    if (right($column_name, 2) != "_H") {   // ignore hidden columns
                        if (( right($column_name, 2) == "_I") || (right($column_name, 2) == "_J") ||
                                (right($column_name, 2) == "_N") || (right($column_name, 2) == "_S") ||
                                (right($column_name, 2) == "_D") || (right($column_name, 2) == "_C")) { // numeric column or numeric summary column
                            $column_name = substr($column_name, 0, strlen($column_name) - 2);
                            $column_style = "text-align:center;";
                        }
                        $table_stru = $table_stru . "<td style='" . $strfieldarea . "" . $strfieldarea_top . "" . $column_style . "'>" . $column_name . "</td>";
                    }

                    $arr_summary[$j] = 0;        // initialize array
                    $j++;
                }
                $table_stru = $table_stru . "</tr>";

                // -----------------------------------------------------------------------------
                // table data - dynamic rows
                // -----------------------------------------------------------------------------
                $j = 0;
                while ($row = mysqli_fetch_array($result)) {
                    $table_stru = $table_stru . "<tr><td  style='" . $strfieldarea . "text-align:center;'>" . ($j + 1) . "</td>";
                    // table data - dynamic column
                    $i = 0;
                    while ($i < $column_count) {
                        $column_name = mysqli_fetch_field_direct($result, $i)->name; // get curent column name
                        if (right($column_name, 2) != "_H") {   // ignore hidden columns
                            $column_style = "";
                            $column_value = $row[$i];
                            switch (right($column_name, 2)) {
                                case "_I":      // integer display 41.23 to 41
                                    $arr_summary[$i] = $arr_summary[$i] + $row[$i];
                                    $column_style = "text-align: center;";
                                    $column_value = number_format($row[$i], 0);
                                    break;
                                case "_J":      // integer display with footer total
                                    $arr_summary[$i] = $arr_summary[$i] + $row[$i];
                                    $column_style = "text-align: center;";
                                    $column_value = number_format($row[$i], 2);
                                    break;
                                case "_D":      // decimal display: 40 to 40.00
                                    $arr_summary[$i] = $arr_summary[$i] + $row[$i];
                                    $column_style = "text-align: right;";
                                    $column_value = number_format($row[$i], 2);
                                    break;
                                case "_S":      // decimal display with footer total
                                    $arr_summary[$i] = $arr_summary[$i] + $row[$i];
                                    $column_style = "text-align: right;";
                                    $column_value = number_format($row[$i], 2);
                                    break;
                                case "_N":
                                    $column_style = "text-align: right;";
                                    $column_value = number_format($row[$i], 2);
                                    break;
                                case "_C":
                                    $column_style = "text-align: center;";
                                    $column_value = $row[$i];
                                    break;
                            }
                            if ($i == 0) {
                                $column_style .= " border-left: 1px solid #d2d2d2;";
                            }


                            $table_stru = $table_stru . "<td  style='" . $strdatadarea . "" . $column_style . "' >" . $column_value . "</td>";
                            $i++;
                        }
                    }
                    $table_stru = $table_stru . "</tr>";
                    $j++;
                };
                // -----------------------------------------------------------------------------
                // page footer
                // -----------------------------------------------------------------------------
                $i = 0;
                $table_stru = $table_stru . "<tr><td  style='" . $strfieldarea . "' ></td>";
                while ($i < $column_count) {
                    $column_name = mysqli_fetch_field_direct($result, $i)->name; // get curent column name
                    $value = "";
                    if (right($column_name, 2) != "_H") {   // ignore hidden columns
                        $column_style = "";
                        switch (right($column_name, 2)) {
                            case "_I":      // integer display 41.23 to 41
                                $column_style = "text-align: center;";
                                $value = number_format($arr_summary[$i], 0);
                                break;
                            case "_J":      // integer display with footer total
                                $column_style = "text-align: center;";
                                $value = number_format($arr_summary[$i], 2);
                                break;
                            case "_D":      // decimal display: 40 to 40.00
                                $column_style = "text-align: right;";
                                $value = number_format($arr_summary[$i], 2);
                                break;
                            case "_S":      // decimal display with footer total
                                $column_style = "text-align: right;";
                                $value = number_format($arr_summary[$i], 2);
                                break;
                            case "_N":
                                break;
                            case "_C":
                                break;
                        }


                        $table_stru = $table_stru . "<td  style='" . $strfieldarea . "" . $column_style . "' ><b>$value</b></td>";
                        $i++;
                    }
                }
                $table_stru = $table_stru . "</tr>";
            } else {
                $table_stru = "<tr><td>";
                $table_stru = $table_stru . "Error in running query :" . mysqli_error();
                $table_stru = $table_stru . "</td></tr>";
            }
            $table_stru = $table_stru . "</tbody></table>";

            mysqli_free_result($result);

            return $table_stru;
        }
    }

}

function left($str, $length) {
    return substr($str, 0, $length);
}

function right($str, $length) {
    return substr($str, -$length);
}

?>
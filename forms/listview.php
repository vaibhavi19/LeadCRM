<?php 
include('header.php');

$listview_id = $_GET['id'];
$multiselect = isset($_GET['ms']) ? $_GET['ms'] : "";

// read list view header, query details
$query = "SELECT description, sql_text FROM sys_listviews WHERE view_id = '" . $listview_id . "'";

//SELECT a.company_id as `ID`, a.company_name as `Company`, case when a.status ='A' then 'Enabled' else 'Disabled' end as Status, date_format(a.created_on,'%d-%b-%Y') as 'Created On', b.user_name as 'Created By' FROM mst_company a join gm_user_master b on a.created_by = b.user_id
$obj = new conn();
$result = $obj->execute($query, $error_message);

if (($result) || (mysqli_errno() == 0)) {
    $row = mysqli_fetch_array($result);
    $listview_caption = $row['description'];
    $listview_sql = $row['sql_text'];
    $listview_sql = str_replace("_SESSION_USER_ID", $_SESSION['user_id'], $listview_sql);
     $listview_sql = str_replace("_CLIENT_ID", $_SESSION['client_id'], $listview_sql);
    
    //echo $listview_sql;exit;
   
} else {
    echo "Invalid view ID, no such view found in the table.";
    mysqli_free_result($result);
    unset($obj);
    exit;
}
//echo $listview_sql;exit;
mysqli_free_result($result);
?>
  <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><?php echo $listview_caption;?></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active"><?php echo $listview_caption;?></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
     <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
        <div class="card">
              <div class="card-header">
                  <?php echo getlistviewbuttons();?>
<!--                <h3 class="card-title">DataTable with default features</h3>-->
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                  <tr>
                      <th>SR</th>
                    <?php
                    
                      $result = $obj->execute($listview_sql, $error_message);
                        $column_count = mysqli_num_fields($result);
                        //$column_count = 20;
                if ($result) {
                    // search area - top of the grid
                 $table_heading="";
                                    $j = 0;
                                    while ($property = mysqli_fetch_field($result)) {
                                        if ($j != 0) { // ignore first column pkid
                                            $db_column = $property->name;
                                           // echo ("<option " . (($search_column == $property->name) ? " selected " : "") . ">" . $property->name . "</option>");
                                            //echo "<option>" . $property->name . "</option>";
                                            $table_heading .= "<th>" . $property->name . "</th>";
                                        }
                                        $j = 1;
                                    }
                                    echo $table_heading;
                }
                                    ?>
                  
                    
                  </tr>
                  </thead>
                  <tbody>
                 
                  <?php
                    $j = 0;$row_start=0;
                        while ($row = mysqli_fetch_array($result)) {
                            $row_start++;
                           
                            echo "<td style=\"text-align: center;\" class=\"fieldarea\">$row_start</td>";
                            if ($multiselect == "true") {
                                echo "<td><input type='checkbox' id='checkItem[]' name='checkItem[]' value={$row['ID']}></td>";
                            }

                            // table data - dynamic column
                            $i = 1;  // ignore first column - pkid
                            while ($i < $column_count) {
                                echo "<td>" . $row[$i] . "&nbsp;</td>";
                                $i++;
                            }
                            echo "</tr>";
                            $j++;

                            if ($j == 1)              // by default the first row is selected
                                $txtPKey = $row[0];
                        }
                        ?>
                         <input type="hidden" name="txtPKey" id="txtPKey" value="<?PHP echo $txtPKey; ?>"/>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
      </div><!-- /.container-fluid -->
        </div></div>
      </section>
    <?php 
    function getlistviewbuttons() {
    global $obj;

    $error_message = "";
    $listview_id = $_GET['id'];

    $sql = "SELECT menu_caption, menu_url, target FROM sys_listviews_menus WHERE view_id = '" . $listview_id . "' AND status = 'A' order by display_order";
    $result = $obj->execute($sql, $error_message);
    if (($result) || (mysqli_errno() == 0)) {
        while ($row = mysqli_fetch_array($result)) {
            echo "<a class='btn bg-gradient-info' href=".$row['menu_url']." onclick=\"javascript:redirectToURL('" . $row['menu_url'] . "','" . $row['target'] . "',$listview_id);\"><b>" . $row['menu_caption'] . "</b></a> "; //onclick=\"javascript:redirectToURL('" . $row['menu_url'] . "','" . $row['target'] . "');\"
        }
    } else {
        echo "Invalid view ID, no such view found in the table.";
    }
    mysqli_free_result($result);
}
    ?>
    <!-- /.content -->
  </div>
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true,
      "autoWidth": false,
    });
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
  
  
     function redirectToURL(URL, target, viewID) {
                    var str = txtPKey.value;
                //alert(str);
                if (URL.indexOf("action=NEW") == -1)
                    URL = URL + "&pkeyid=" + str + "&viewid=" + viewID;
                else
                    URL = URL + "&viewid=" + viewID;

                //alert(URL);
                //        alert(target);
                if (target == "_blank")
                    window.open(URL);
                else
                    self.location = URL;
            }
</script>
<?php 
include('footer.php');
?>  
<?php
include 'db_connection.php';
include 'admin/time_zone.php';

    // $staff_id = "230629406";

    if(isset($_GET['sd_code']) && !empty($_GET['sd_code'])){
        $sd_code = $_GET['sd_code'];
        $get_ID = "SELECT
        a.staff_id
        FROM owners a 
        INNER JOIN salary_deduction b ON b.owner_id = a.staff_id
        WHERE b.sd_code = '$sd_code'";
        //Get Owner_ID
        
       $result_get_ID = mysqli_query($conn, $get_ID);
       $row_getID = $result_get_ID->fetch_assoc();

       $staff_id = $row_getID["staff_id"];
        // Get the current year and month
        $current_year = date('Y');
        $current_month = date('m');
        $sql_getcutoff = "SELECT
        a.sd_code,
        a.sd_credits,
        a.first_cut_start,
        a.first_cut_end,
        a.second_cut_start,
        a.second_cut_end,
        b.owner_name,
        c.department_name
    FROM
        salary_deduction a
    INNER JOIN owners b ON b.staff_id = a.owner_id
    INNER JOIN department c ON c.id = b.owner_department
    WHERE
        a.owner_id = '$staff_id'";
        $result_getcutoff = mysqli_query($conn, $sql_getcutoff);

        if($result_getcutoff->num_rows > 0) {
            $row_Details = $result_getcutoff->fetch_assoc();
            //Get Owner Details
            $owner_code = $row_Details["sd_code"];
            $owner_name = $row_Details["owner_name"];
            $owner_department = $row_Details["department_name"];

            //Get Cut-Off
            $first_cut_start = $row_Details["first_cut_start"];
            $first_cut_end = $row_Details["first_cut_end"];
            $second_cut_start = $row_Details["second_cut_start"];
            $second_cut_end = $row_Details["second_cut_end"];
            $max_credits = $row_Details['sd_credits'];

            // Convert day values to specific dates
            $first_cut_start_date = date('Y-m-d', strtotime("$current_year-$current_month-$first_cut_start"));
            $first_cut_end_date = date('Y-m-d', strtotime("$current_year-$current_month-$first_cut_end"));
            // Convert day values to specific dates
            $second_cut_start_date = date('Y-m-d', strtotime("$current_year-$current_month-$second_cut_start"));
            $second_cut_end_date = date('Y-m-d', strtotime("$current_year-$current_month-$second_cut_end"));

            //Retrieve Deduction Data within the specified cut-off periods
            $sql_compute_deduction = "SELECT amount_sd FROM balance_deducted WHERE created_at BETWEEN '$first_cut_start_date' AND '$first_cut_end_date' AND void = '0'";
            $result_compute_deduction = $conn->query($sql_compute_deduction);
            $total_deduction_first_cut = 0;
            while ($row = $result_compute_deduction->fetch_assoc()) {
                $total_deduction_first_cut += $row['amount_sd'];
            }
            $query_Cut_Off_LOGS = "SELECT DISTINCT DATE(created_at) AS transaction_date FROM balance_deducted
            WHERE owner_id= '$staff_id' AND created_at BETWEEN '$first_cut_start_date' AND '$first_cut_end_date'";
            $result_Cut_Off_LOGS = mysqli_query($conn, $query_Cut_Off_LOGS);    
        }        

    }else{
        echo "No Result Found";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>sdowner</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="description" content="" />
  <link rel="icon" href="favicon.png">
   <link href="assets/bootstrap533.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/toastr.min.css">
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <script src="assets/jquery.min.js"></script>
    <script src="assets/bootstrap.min.js"></script>

</head>
<body>
  <div class="container">
        <h1 class="text-center">SD CREDIT LOGS</h1><br><br>
        <div class="row">
            <div class="col-md-12">
                <ul class="list-group">
                    <li class="list-group-item text-uppercase">SD CODE: <span class="text-muted fw-semibold"><?php echo $owner_code;?></span></li>
                    <li class="list-group-item text-uppercase">OWNER NAME: <span class="text-muted fw-semibold"><?php echo $owner_name;?></span></li>
                    <li class="list-group-item text-uppercase">DEPARTMENT: <span class="text-muted fw-semibold"><?php echo $owner_department;?></span></li>
                    <li class="list-group-item text-uppercase">Balance Remaining: <span class="text-muted fw-semibold"><?php echo $max_credits - $total_deduction_first_cut?></span></li>
                </ul>
            </div>
        </div>
    </div>
    <!--  --><br><br>
    <div class="container">
        <h1 class="text-left">TRASACTION DATE</h1>
        <div class="row">
            <div class="col-md-12">
                <div class="report-container">
                  <table class="table table-hover">
                          <thead>
                              <tr> 
                              <th scope="col">DATE</th>
                              <th scope="col">ACTION</th>
                              </tr>
                          </thead>
                          <tbody id="tableReport">
                            <?php 
                          if($result_Cut_Off_LOGS->num_rows >0){
                            while($row_Transaction = $result_Cut_Off_LOGS->fetch_assoc()){
                            ?>
                            <tr>
                              <td><?php echo $row_Transaction['transaction_date'];?></td>
                              <td><button type="button" class="btn btn-primary viewTransaction" sd-code="<?php echo $owner_code;?>" date-selected='<?php echo $row_Transaction['transaction_date'];?>' data-toggle="modal" data-target="#view_transactionbtn">VIEW</button></td>
                            </tr>
                            <?php
                            }  
                            }else{
                                echo "No Transaction Found";
                            }
                            ?>
                          </tbody>
                  </table>

               </div>
            </div>
            <div class="col-12">
                <div class="d-flex">
                  <div class="col">
                  <span class="fw-bold text-uppercase">1st cut-off total deducted: <?php echo $total_deduction_first_cut?></span>
                  </div>
                  <!-- <div class="col">
                  <span class="fw-bold ">2ND CUT-OFF TOTAL :</span>
                  </div> -->
                </div>
            </div>
        </div>
    </div>

<!-- Modal -->
<div class="modal fade" id="view_transactionbtn" tabindex="-1" role="dialog" aria-labelledby="viewmdl_title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewmdl_title">TRANSACTIONS</h5>
        <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <!-- -->
        
        <div class="row">
            <div class="col-md-12">
                <div class="report-container">
<table class="table table-hover">
        <thead>
            <tr> 
            <th scope="col">DATE /TIME</th>
            <th scope="col">SD AMOUNT</th>
            <th scope="col">RECEIPT</th>
            <th scope="col">ACTION</th>
            </tr>
        </thead>
        <tbody id="transactionTable">

        <!-- PANG POP UP SA VOID PIN -->
        <div class="modal fade" id="void_inputmdl" tabindex="-1" role="dialog" aria-labelledby="voidpn_ttl" aria-hidden="true" style="overflow-y: hidden;">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="voidpn_ttl">Scan Void Pin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <input type="password" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
<!--  -->
                  <!-- <tr>
                    <td>00 / 00 / 00 10:59:00</td>
                  <td>100</td>
                  <td>ESKPS0002</td> 
                  <td>VOID APPROVED</td> 
                  </tr>   -->

        </tbody>
</table>
</div>
            </div>
        </div>
 
      <!--  -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
       
      </div>
    </div>
  </div>
</div>
<script>
  $(document).ready(function(){
    $(".viewTransaction").click(function(){
      var sd_Code = $(this).attr("sd-code");
      var date_selected = $(this).attr("date-selected");
      console.log(sd_Code);
      console.log(date_selected);
      $.ajax({
          url: "admin/process/fetchData.php",
          type: "POST",
          cache: false,
          data:{
              action: "fetchdate_Transaction",
              sd_code: sd_Code,
              date: date_selected,
              },
          success:function(data){
              // alert(data);
              // toastr.success("Record Retrieve Successful");
              $('#transactionTable').html(data);
          }
        });
    });
  });
</script>
</body>
</html>

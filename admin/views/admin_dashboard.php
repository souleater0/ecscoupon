<style>
      .report-container{
        max-height: 600px;
        overflow-y: scroll;
        width: 100%;
      }
      .report-container table {
        width: 100%;
      }

      .report-container thead {
        position: sticky;
        top: 0px;
        background-color: white;
      }
</style>
<div class="container">
<div class="input-daterange row align-items-end">
   <div class="col-3">
    From<input type="date" id="startDatePicker" name="fromDate" class="form-control" value="<?php echo $current_date ?>" />
   </div>
   <div class="col-3">
    To<input type="date" id="endDatePicker" name="toDate" class="form-control" value="<?php echo $current_date ?>"/>
   </div>
   <div class="col-3">
    Department
   <select class="form-select" aria-label="Default select example" name="departmentID" id="selectDepartment" onchange="updateCouponPrefix()">
                <option value="" selected>All</option>
                <?php 
                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                $sql = "SELECT * FROM department";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                ?>
                <option value="<?php echo $row["id"];?>"><?php echo $row["department_name"];?></option>
                <?php
                }}?>
              </select>
   </div>
   <div class="col-3">
   <button type="button" id="generateReport" class="btn btn-primary px-2">Generate</button>
   <button type="button" id="addBarcode" class="btn btn-success px-2">Export CSV</button>
   </div>
</div>
<h1 class="text-uppercase">Reports</h1>
<div class="report-container">
<table class="table table-hover">
        <thead>
            <tr> 
            <th scope="col">Coupon Code</th>
            <th scope="col">Coupon Value</th>
            <th scope="col">Full Name</th>
            <th scope="col">Department Name</th>
            <th scope="col">Claimed Date</th>
            <th scope="col">Remarks</th>
            </tr>
        </thead>
        <tbody id="tableReport">
                
        </tbody>
</table>
</div>
</div>
<script>
$(document).ready(function(){
    toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": true,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
    $('#generateReport').click(function(){
        var start_date = $("#startDatePicker").val();
        var end_date = $("#endDatePicker").val();
        var department = $("#selectDepartment").val();
        $.ajax({
                url: "../process/report_table.php",
                type: "POST",
                cache: false,
                data:{
                    start_date:start_date,
                    end_date: end_date,
                    department: department
                    },
                success:function(data){
                    // alert(data);
                    toastr.success("Report Generated Successfully");
                    $('#tableReport').html(data);
                }
        });
    });
});
    
</script>
<?php
include 'db_connection.php';
include 'admin/time_zone.php';
session_start();
if (!isset($_SESSION['admin_session_id'])) {
    header("Location: admin/index.php");
    die();
}
?>
<!doctype html>
<html lang="en">
  <head>
    <title>Sidebar 09</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="assetlibrary/css/style.css">
        <script src="assets/jquery.min.js"></script>
        <script src="assetlibrary/js/bootstrap.min.js"></script>
    <script src="assetlibrary/js/main.js"></script>
    <link rel="stylesheet" href="assets/toastr.min.css">
    <script src="assets/toastr.min.js"></script>


  </head>
  <body>
        
        <div class="wrapper d-flex align-items-stretch">
            <nav id="sidebar">
                <div class="custom-menu">
                    <button type="button" id="sidebarCollapse" class="btn btn-primary">
            </button>
        </div>
        <div class="text-black border px-3 py-4">
          <?php echo $day_name.', '.$current_date_format.'<br>'.$current_time_format?>
          
                    <span class="float-right">Current User: <b><?php echo $_SESSION['admin_session_name']?></b>&nbsp;&nbsp;&nbsp;Location: <b><?php echo $_SESSION['admin_session_location']?></b></span>
        </div>
            
        <ul class="list-unstyled components mb-5">
          <li class="active">
            <a href="admin/views/index.php?route=dashboard"><span class="fa fa-home mr-3"></span> Dashboard</a>
          </li>
          <li>
            <a href="#"><span class="fa fa-sign-out mr-3"></span> Sign Out</a>
          </li>
        </ul>

        </nav>

        <!-- Page Content  -->
      <div id="content" class="p-4 p-md-5 pt-5">
        <!--  -->
      <div class="container">
  

  <ul class="nav nav-tabs justify-content-around">
    <li><a data-toggle="tab" href="#home"><h3>FOOD STUB</h3></a></li>
    <li><a data-toggle="tab" href="#menu1"><h3>SALARY DEDUCTION</h3></a></li>
  </ul>
<br>
  <div class="tab-content">
    <div id="home" class="tab-pane fade in active">
       <form id="barcode_scan" class="text-center">
                                <label for="coupon">Scan Coupon Barcode:</label>
                                <input type="text" id="coupon" name="coupon" autofocus
                                    oninput="moveToNextInput(this, 'id')">
                                <br><br>
                                <label for="id">Scan Owner ID Barcode:</label>
                                <input type="text" id="id" name="id">
                                <br><br>
                                <button type="button" style="display: none;" id="addBarcode"
                                    class="btn btn-primary">Submit</button>
                            </form>
                            <input type="text" class="form-control search" id="live_search" autocomplete="off" placeholder="Type Stub Code" style="border:2px solid black;">
</br>
                                <div class="table-container">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <!-- <th scope="col">#</th> -->
                                    <th scope="col">Full Name</th>
                                    <th scope="col">Department</th>
                                    <th scope="col">Coupon Code</th>
                                    <th scope="col">Coupon Value</th>
                                    <th scope="col">Claimed Date</th>
                                    <th scope="col">Clerk</th>
                                    <th scope="col">Remarks</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">

                            </tbody>
                        </table>
                    </div>

    </div>

    <div id="menu1" class="tab-pane fade">
       <form id="barcode_scan" class="text-center">
                                <label for="coupon">SD Barcode:</label>
                                <input type="text" id="sd_coupon" name="sd_coupon" autofocus
                                    oninput="moveToNextInput(this, 'id')">
                                <br><br>
                                <label for="id">ID Barcode:</label>
                                <input type="text" id="s_id" name="s_id">
                                                                <br><br>
                                <label for="id">AMOUNT SD:</label>
                                <input type="text" id="amount_sd" name="amount_sd">
                                                                <br><br>
                                <label for="id">RECEIPT #:</label>
                                <input type="text" id="receipt_no" name="receipt_no">
                                <br><br>
                                <button type="button" id="addBarcode"
                                    class="btn btn-primary">Submit</button>
                            </form>
                            <input type="text" class="form-control search" id="live_search2" autocomplete="off"
                                placeholder="Type Stub Code">
                                <br>
                                <table class="table table-hover">
        <thead>
            <tr> 
            <th scope="col">FULL NAME</th>
            <th scope="col">DEPARTMENT</th>
            <th scope="col">SD CODE</th>
            <th scope="col">REMAINING BALANCE</th>
            <th scope="col">ACTION</th>
            </tr>
        </thead>
        <tbody id="tableReport">
                <!-- <td>dsa</td>
                 <td>dsa</td>
                  <td>ESKSD20240001</td>
                  <td>1000</td>
                   <td><a class="btn btn-primary" href="sdowner_view.php?sd_code=ECSTXSD2024006">view</a></td> -->
        </tbody>
</table>

    </div>
  
   
  </div>
</div>
        <!--  -->
           </div>
       </div>
       <script language="JavaScript" type="text/javascript">
                    $(document).ready(function () {
                        $("#live_search").keyup(function () {
                            var search = $(this).val();
                        });
                        $("#live_search2").keyup(function () {
                            var search2 = $(this).val();
                            // alert(search2);
                        });
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
                        $('#coupon').keypress(function (event) {
                            if (event.keyCode === 13) { // Check if Enter key is pressed
                                event.preventDefault(); // Prevent form submission
                                $('#id').focus(); // Focus on the second input
                            }
                        });
                        $('#id').keypress(function (event) {
                            if (event.keyCode === 13) {
                                event.preventDefault();
                                $('#addBarcode').click();
                            }
                        });
                        $('#addBarcode').click(function () {
                            var formData = $('#barcode_scan').serialize()
                            $.ajax({
                                url: "process_scan.php",
                                method: "POST",
                                data: formData +
                                    "&action=addBarcode&date=<?php echo $current_date;?>",
                                dataType: "json",
                                success: function (response) {
                                    if (response.success == true) {
                                        toastr.success(response.message);
                                        // setTimeout(() => {
                                        //     location.reload();
                                        // }, 2000);
                                        $('#coupon').val("");
                                        $('#id').val("");
                                        $('#coupon').focus();
                                    } else {
                                        toastr.error(response.message);
                                        $('#coupon').val("");
                                        $('#id').val("");
                                        $('#coupon').focus();
                                    }
                                }
                            });
                        });
                        autorefresh();

                        function autorefresh() {
                            setInterval(function () {
                                LoadTable();
                                LoadTable2();
                            }, 1000);
                        }

                        function LoadTable() {
                            var search = $('#live_search').val();
                            if (search == "") {
                                $.ajax({
                                    url: "datatable.php",
                                    type: "POST",
                                    cache: false,
                                    data: {
                                        search: search
                                    },
                                    success: function (data) {
                                        // alert(data);
                                        // toastr.success("Record Retrieve Successful");
                                        $('#tableBody').html(data);
                                    }
                                });
                            } else {
                                $.ajax({
                                    url: "datatable.php",
                                    type: "POST",
                                    cache: false,
                                    data: {
                                        search: search
                                    },
                                    success: function (data) {
                                        // alert(data);
                                        $('#tableBody').html(data);
                                    }
                                });
                            }
                        }
                        function LoadTable2() {
                            var search2 = $('#live_search2').val();
                            
                            if (search2 == "") {
                                $.ajax({
                                    url: "admin/process/sd_table.php",
                                    type: "POST",
                                    cache: false,
                                    data: {
                                        search: search2
                                    },
                                    success: function (data) {
                                        // alert(data);
                                        // toastr.success("Record Retrieve Successful");
                                        $('#tableReport').html(data);
                                    }
                                });
                            } else {
                                $.ajax({
                                    url: "admin/process/sd_table.php",
                                    type: "POST",
                                    cache: false,
                                    data: {
                                        search: search2
                                    },
                                    success: function (data) {
                                        // alert(data);
                                        
                                        $('#tableReport').html(data);
                                    }
                                });
                            }
                        }
                    });
                    // document.onkeydown = function (e) {
                    // return false;
                    // }
                </script>
    
  </body>
</html>
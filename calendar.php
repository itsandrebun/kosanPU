<!doctype html>
<html lang="en">
  <head>
    <?php
        $page_title = "Calendar";
        include "templates/header.php";
    ?>
  </head>
  <body>
      <?php
        include "templates/navbar.php";
        $logged_in_user = !empty($_SESSION['user']) ? $_SESSION['user'] : null;
        if($logged_in_user == null || ($logged_in_user != null && $logged_in_user['tenant_id'] == null)){
            session_destroy();
            header("Location:index");
        }
      ?>
    <div class="container-fluid">
        <?php
            include "DB_connection.php";
            $database = new Database();
            $con = $database->getConnection();
            $price_array = array();
            
            $price_sql = "SELECT * FROM internal_parameter";
            $room_sql = "SELECT * FROM room WHERE room_id = ".$_GET['room'];

            $price_detail = $con->query($price_sql);
            $room_detail = $con->query($room_sql)->fetch_assoc();

            if($price_detail->num_rows > 0){
                while($row = $price_detail->fetch_assoc()) {
                    array_push($price_array, $row);
                }
            }
        ?>
        <div class="available-room-div">
            <span>Available Dates for Room <?= $room_detail['room_name'];?></span>
        </div>
        <div class="calendar-arrow" style="text-align:center;font-size:25px">
            <span id="calendar-prev-month"><</span>
            <span id="calendar-selected-month-name"></span>
            <span id="calendar-next-month">></span>
        </div>
        <table class="table">
            <thead>
                <tr class="table-light calendar-header" style="text-align:center">
                    <th>Mon</th>
                    <th>Tue</th>
                    <th>Wed</th>
                    <th>Thu</th>
                    <th>Fri</th>
                    <th>Sat</th>
                    <th>Sun</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                </tr>
                <tr>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                </tr>
                <tr>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                </tr>
                <tr>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                </tr>
                <tr>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                </tr>
                <tr>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                    <td class="calendar-dates">
                        <div></div>
                    </td>
                </tr>
            </tbody>
        </table>
        <form class="pb-1" method="POST" action="booking_validation.php">
            <div class="row">
                <div class="col-md-6">
                    <label class="col-sm-label">Start Date</label>
                    <input type="text" readonly class="form-control <?= (!empty($_SESSION['booking_start_date_validation']) ? ('is-invalid') : '') ;?>" name="booking_start_date" id="booking_start_date">
                    <?= (!empty($_SESSION['booking_start_date_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['booking_start_date_validation'].'</div>') : '') ;?>
                </div>
                <div class="col-md-6">
                    <label class="col-sm-label">End Date</label>
                    <input type="text" readonly class="form-control <?= (!empty($_SESSION['booking_end_date_validation']) ? ('is-invalid') : '') ;?>" name="booking_end_date">
                    <?= (!empty($_SESSION['booking_end_date_validation']) ? ('<div class="invalid-feedback">'.$_SESSION['booking_end_date_validation'].'</div>') : '') ;?>
                    <input type="hidden" readonly class="form-control" name="total_payment" value="<?= $price_array[0]['parameter_value'] + $price_array[1]['parameter_value'];?>">
                    <input type="hidden" name="rent_cost" value="<?= $price_array[0]['parameter_value'];?>">
                    <input type="hidden" name="deposit" value="<?= $price_array[1]['parameter_value'];?>">
                    <input type="hidden" name="company_name" value="<?= $price_array[2]['parameter_value'] ?>">
                    <input type="hidden" name="company_address" value="<?= $price_array[3]['parameter_value'] ?>">
                    <input type="hidden" name="room_name" value="<?= $room_detail['room_name'];?>">
                </div>
            </div>
            <?php if(!empty($_SESSION['booking_validation'])):?>
                <span style="font-size:12px;color:red"><?= $_SESSION['booking_validation'];?></span>
            <?php endif;?>
            <div>
                <input type="hidden" name="room_id" value="<?= $_GET['room'];?>">
                <input type="hidden" name="total_price" value="<?= $price_array[0]['parameter_value'] + $price_array[1]['parameter_value'];?>">
                <span id="total_payment" style="display:none">Total Payment: <?= $price_array[0]['parameter_value'] + $price_array[1]['parameter_value'];?>,-</span>
            </div>
            <input type="submit" class="btn btn-primary mt-2" name="bookingButton" value="Book">
            <?php
                unset($_SESSION['booking_start_date_validation']);
                unset($_SESSION['booking_end_date_validation']);
                unset($_SESSION['booking_validation']);
            ?>
        </form>
    </div>

    <?php include "templates/js_list.php";?>
    <script>
        var booking_start_date = "";
        var booking_end_date = "";
        var month_list = [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December"
        ];
        var today = new Date();
        var current_month = today.getMonth()+1;
        if(current_month < 10){
            current_month = "0"+current_month;
        }
        console.log(current_month);
        var current_year = today.getFullYear();

        showCalendar(current_month, current_year);

        document.getElementById('calendar-prev-month').onclick = function(){
            // console.log('aaa');
            // current_month = ;
            current_month = parseInt(current_month);
            if(current_month == 1){
                current_month = 12;
                current_year = current_year - 1;
            }else{
                current_month = current_month - 1;
                if(current_month < 10){
                    current_month = "0"+current_month;
                }
            }
            console.log(current_month);
            showCalendar(current_month, current_year);
        }

        // document.getElementById('booking_start_date').onkeyup = function(){
        //     document.getElementById('total_payment').style.display = "block";
        // }

        // document.querySelector('.calendar-dates').onclick = function(){
        //     console.log('blablabla');
        // };
        // var calendar_dates = document.getElementsByClassName('calendar-dates').length;

        // for(var g = 0; g < calendar_dates; g++){
        //     document.getElementsByClassName('calendar-dates')[g].onclick = function(){
        //         // console.log('bbb');
        //         booking_start_date = this.getAttribute('data-dates');
        //         document.getElementsByName('booking_start_date')[0].value = booking_start_date;
        //         console.log(booking_start_date);
        //     }
        // }

        document.getElementById('calendar-next-month').onclick = function(){
            // console.log('aaa');
            // current_month = ;
            current_month = parseInt(current_month);
            if(current_month == 12){
                current_month = 1;
                current_year = current_year + 1;
            }else{
                current_month = current_month + 1;
                if(current_month < 10){
                    current_month = "0"+current_month;
                }
            }
            console.log(current_month);
            showCalendar(current_month, current_year);
        }

        function showCalendar(selected_month, selected_year){
            // var today = new Date();
            // var current_month = today.getMonth()+1;
            // if(current_month < 10){
            //     current_month = "0"+current_month;
            // }
            // console.log(current_month);
            // var current_year = today.getFullYear();
            var first_day_of_selected_month = new Date(selected_year+"-"+selected_month+"-"+"01 00:00:00");
            console.log(selected_year+"-"+selected_month+"-"+"01 00:00:00");
            first_day_of_selected_month = first_day_of_selected_month.getDay();
            var total_days = function(month,year) {
                return new Date(year, month, 0).getDate();
            };
            console.log("first day in index: "+first_day_of_selected_month);
            var prev_month = parseInt(selected_month) - 1;
            var prev_year = selected_year;
            if(prev_month < 1){
                prev_month = 12;
                prev_year = prev_year - 1;
            }
            // if(prev_month)
            total_previous_days = total_days(prev_month,prev_year);
            total_days = total_days(selected_month,selected_year);
            console.log(total_days);
            console.log(total_previous_days);
            // console.log();
            total_rows = document.getElementsByClassName('calendar-dates').length;

            var days_index = 1;
            for(var k = 0; k < total_rows; k++){
                document.getElementsByClassName('calendar-dates')[k].innerHTML = "<div></div>";
                document.getElementsByClassName('calendar-dates')[k].classList.remove('disabled-dates');
            }
            if(first_day_of_selected_month == 0){
                first_day_of_selected_month = 7;
            }
            for(var k = 0; k < total_rows; k++){
                if(k >= (first_day_of_selected_month - 1)){
                    document.getElementsByClassName('calendar-dates')[k].innerHTML = "<div>"+(days_index)+"</div>";
                    document.getElementsByClassName('calendar-dates')[k].setAttribute('data-dates',(selected_year+'-'+selected_month+'-'+(days_index < 10 ? "0"+(days_index) : (days_index))));
                    days_index += 1;

                    document.getElementsByClassName('calendar-dates')[k].onclick = function(){
                        // console.log("aaaa");
                        booking_start_date = this.getAttribute('data-dates');
                        document.getElementsByName('booking_start_date')[0].value = booking_start_date;
                        var full_end_date = new Date(month_list[parseInt(selected_month) - 1]+' '+(parseInt(this.getAttribute('data-dates').split('-')[2]) < 10 ? ("0"+(parseInt(this.getAttribute('data-dates').split('-')[2]))) : parseInt(this.getAttribute('data-dates').split('-')[2]))+', '+selected_year);
                        // console.log();
                        full_end_date.setMonth(full_end_date.getMonth() + 1);
                        booking_end_date = full_end_date.toLocaleDateString();
                        console.log("end date: "+full_end_date);
                        booking_end_date = booking_end_date.split('/');
                        console.log(booking_end_date);
                        booking_end_date = booking_end_date[2]+'-'+(parseInt(booking_end_date[1]) < 10 ? ("0" + parseInt(booking_end_date[1])) : booking_end_date[1])+'-'+(parseInt(booking_end_date[0]) < 10 ? ("0" + parseInt(booking_end_date[0])) : booking_end_date[0]);
                        document.getElementsByName('booking_end_date')[0].value = booking_end_date;
                        console.log(booking_start_date);
                        console.log(booking_end_date);
                        document.getElementById('total_payment').style.display = "block";
                    }
                }
                if(k < (first_day_of_selected_month - 1)){
                    document.getElementsByClassName('calendar-dates')[k].classList.add('disabled-dates');
                    document.getElementsByClassName('calendar-dates')[k].setAttribute('data-dates',(prev_year+'-'+prev_month+'-'+(total_previous_days - (first_day_of_selected_month - 1) + k + 1)));
                    document.getElementsByClassName('calendar-dates')[k].innerHTML = (total_previous_days - (first_day_of_selected_month - 1) + k + 1);
                }
                if(days_index >= (total_days + 2)){
                    // break;
                    document.getElementsByClassName('calendar-dates')[k].classList.add('disabled-dates');
                    document.getElementsByClassName('calendar-dates')[k].innerHTML = "<div></div>";
                }
                // if(days_index == (total_days + 1)){
                //     break;
                // }
                // document.getElementsByClassName('calendar-dates')[k].innerHTML = "<div></div>";
            }
            document.getElementById('calendar-selected-month-name').innerHTML = month_list[selected_month - 1]+' '+selected_year;
            console.log("booking start date: "+booking_start_date);
        }
    </script>
  </body>
</html>
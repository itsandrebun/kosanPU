<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

    <!-- Nav Item - Search Dropdown (Visible Only XS) -->
    <li class="nav-item dropdown no-arrow d-sm-none">
        <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-search fa-fw"></i>
        </a>
        <!-- Dropdown - Messages -->
        <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
        <form class="form-inline mr-auto w-100 navbar-search">
            <div class="input-group">
            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
            <div class="input-group-append">
                <button class="btn btn-primary mykosan-signature-button-color" type="button">
                <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
            </div>
        </form>
        </div>
    </li>

    <!-- Nav Item - Alerts -->
    <?php
        include isset($inside_folder) ? "../../DB_connection.php" : "../DB_connection.php";

        $database = new Database();
        $con = $database->getConnection();
        
        $notification_data = array();
        $total_unread_notifications = 0;

        $notification_sql = "SELECT nt.notification_id, nt.user_id, us.first_name, us.last_name, nt.description, nt.created_date, tr.transaction_id, tr.transaction_code, inv.invoice_id, inv.invoice_number, nt.read_by_admin FROM notification AS nt JOIN user AS us ON us.user_id = nt.user_id LEFT JOIN transaction AS tr ON tr.transaction_id = nt.transaction_id LEFT JOIN invoice AS inv ON inv.invoice_id = nt.invoice_id ORDER BY nt.created_date DESC";

        $notifications = $con->query($notification_sql);

        // echo $room_sql;
        // print_r($rooms['num_rows']);
        if($notifications->num_rows > 0){
            // $total_notification = $notifications->num_rows;
            while($row = $notifications->fetch_assoc()) {
                array_push($notification_data, $row);

                if($row['read_by_admin'] == 0){
                    $total_unread_notifications += 1;
                }
            }
        }

        $logged_in_user = !empty($_SESSION['user']) ? $_SESSION['user'] : null;
    ?>
    <li class="nav-item dropdown no-arrow mx-1">
        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-bell fa-fw"></i>
            <!-- Counter - Alerts -->
            <span class="badge badge-danger badge-counter" id="totalUnreadNotifications"><?= $total_unread_notifications > 10 ? ($total_unread_notifications."+") : $total_unread_notifications;?></span>
        </a>
        <!-- Dropdown - Alerts -->
        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown" id="notificationDropdown">
            <h6 class="dropdown-header mykosan-alert-header">
                Notifications Center
            </h6>
            <?php if(count($notification_data) == 0):?>
            <a class="dropdown-item d-flex align-items-center" href="#">
                <div class="mr-3">
                    <div class="icon-circle bg-primary mykosan-icon-background-color">
                        <i class="fas fa-file-alt text-white"></i>
                    </div>
                </div>
                <div>
                    <div class="small text-gray-500">No data found!</div>
                </div>
            </a>
            <?php else:?>
            <?php for($k = 0; $k < count($notification_data); $k++):?>
            <a class="dropdown-item d-flex align-items-center" href="#">
                <div class="mr-3">
                    <div class="icon-circle bg-primary">
                        <i class="fas fa-file-alt text-white"></i>
                    </div>
                </div>
                <div>
                    <?php
                        $notification_msg = str_replace("[user]",($notification_data[$k]['first_name'].' '.$notification_data[$k]['last_name']),$notification_data[$k]['description']);
                        $notification_msg = str_replace("[invoice_code]",($notification_data[$k]['invoice_number']),$notification_msg);
                        $notification_msg = str_replace("[transaction_code]",($notification_data[$k]['transaction_code']),$notification_msg);
                    ?>
                    <div class="small text-gray-500"><?= date("F d, Y",strtotime($notification_data[$k]['created_date']));?></div>
                    <span class="font-weight-bold"><?= $notification_msg;?></span>
                </div>
            </a>
            <?php endfor;?>
            <?php endif;?>
            <!-- <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a> -->
        </div>
    </li>

    <div class="topbar-divider d-none d-sm-block"></div>

    <!-- Nav Item - User Information -->
    <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $logged_in_user['first_name'] . ' ' . $logged_in_user['last_name'];?></span>
            <img class="img-profile rounded-circle" src="https://source.unsplash.com/QAB-WJcbgJk/60x60">
        </a>
        <!-- Dropdown - User Information -->
        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
        <a class="dropdown-item" href="<?= (isset($inside_folder) ? '../profile' : 'profile');?>">
            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
            Profile
        </a>
        <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                Logout
            </a>
        </div>
    </li>

    </ul>

</nav>
<!-- End of Topbar -->
<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    include "../DB_connection.php";
    
    class Notification{

        protected $con;
        public function __construct(){
            $database = new Database();
            $con = $database->getConnection();
            $this->con = $con;
        }

        public function get(){
            $notification_data = array();
            $request = json_decode(file_get_contents("php://input"));
            $notification_query = "SELECT nt.notification_id, nt.description, nt.read_by_admin, nt.read_by_tenant, nt.user_id, us.first_name, us.last_name, nt.created_date, inv.invoice_id, inv.invoice_number, tr.transaction_id, tr.transaction_code from notification AS nt JOIN user AS us ON us.user_id = nt.user_id LEFT JOIN invoice AS inv ON inv.invoice_id = nt.invoice_id LEFT JOIN transaction AS tr ON tr.transaction_id = nt.transaction_id";

            if(isset($request->tenant_id)){
                $tenant_id = $request->tenant_id;
                $notification_query .= " WHERE nt.user_id = ".$tenant_id;
            }

            $notification_query .= " ORDER BY nt.created_date DESC";

            $total_unread_messages = 0;
            $status = 200;
            $message = "Successfully get notification data";
            $response = array();
            $notifications = $this->con->query($notification_query);
            if($notifications->num_rows > 0){
                while($row = $notifications->fetch_assoc()) {
                    array_push($notification_data, $row);

                    if(isset($request->tenant_id)){
                        if($row['read_by_tenant'] == 0){
                            $total_unread_messages += 1;
                        }
                    }else{
                        if($row['read_by_admin'] == 0){
                            $total_unread_messages += 1;
                        }
                    }
                }
            }
            $this->con->close();

            $response = array(
                "status" => $status,
                "message" => $message,
                "total_unread_messages" => $total_unread_messages,
                "data" => count($notification_data) > 0 ? $notification_data : null
            );
            return $response;
        }

        public function read(){
            $request = json_decode(file_get_contents("php://input"));
            $message = "Internal Server Error";
            $status = 500;

            $read_notification_query = "UPDATE notification SET read_by_admin_date = CURRENT_TIMESTAMP(), read_by_admin = 1";
            if(isset($request->tenant_id)){
                $read_notification_query = "UPDATE notification SET read_by_tenant_date = CURRENT_TIMESTAMP(), read_by_tenant = 1 WHERE tenant_id = ".$request->tenant_id;
            }

            $read_notification = $this->con->query($read_notification_query);
            
            if($read_notification === TRUE){
                $message = "Successfully read all unread notifications";
                $status = 200;
            }
            $this->con->close();

            $response = array(
                "status" => $status,
                "message" => $message
            );

            return $response;
        }

        public function send(){
            $request = json_decode(file_get_contents("php://input"));

            $message = "";
            $status = 200;
            
            $current_date = date('Y-m-d H:i:s');
            $send_notification_query = "INSERT INTO notification(user_id, payment_status_id, invoice_id, description)";
            $send_notification_query .= " SELECT tenant_id, 5, invoice_id, '[user] should pay the bill with the code: [invoice_code]. [user] didnot pay the bill on the specified due dates.' FROM invoice WHERE '".$current_date."' > due_end_date AND invoice_id NOT IN (SELECT nt.invoice_id FROM notification AS nt WHERE nt.invoice_id = invoice.invoice_id AND nt.payment_status_id = 5)";
            $send_notification = $this->con->query($send_notification_query);

                // print_r($update_notification);
                // exit;
            if($send_notification === TRUE){
                $message = "Successfully send notification to user";
            }
            $this->con->close();

            $response = array(
                "status" => $status,
                "message" => $message
            );

            return $response;
        }

        public function action(){
            $request = json_decode(file_get_contents("php://input"));

            $response = array();
            if($request->action == "get"){
                $response = $this->get();
            }elseif($request->action == "send"){
                $response = $this->send();
            }elseif($request->action == "read"){
                $response = $this->read();
            }else{
                http_response_code(404);
                $response = array(
                    "status" => 404,
                    "message" => "Data not found!"
                );
            }

            echo json_encode($response);
        }
    }

    $notification = new Notification();

    $notification->action();
?>
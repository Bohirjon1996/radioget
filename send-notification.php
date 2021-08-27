<?php include('session.php'); ?>
<?php include('public/menubar.php'); ?>

<?php

    class GCM {

        function __construct(){}
            
        public function send_notification($registatoin_ids, $data) {
            
            include "includes/config.php";
            $setting_qry    = "SELECT * FROM tbl_settings where id = '1'";
            $setting_result = mysqli_query($connect, $setting_qry);
            $settings_row   = mysqli_fetch_assoc($setting_result);
            $app_fcm_key    = $settings_row['app_fcm_key']; 
            define("APP_FCM_KEY", $app_fcm_key);

            $url = 'https://fcm.googleapis.com/fcm/send';
            $fields = array(
                'registration_ids' => $registatoin_ids,
                'notification' => array(
                    'title' => $data['title'],
                    'sound' => "default",
                    'body' => $data['description'],
                    'click_action' => "OPEN_MAIN_1",
                    'icon' => 'ic_launcher'),
                'data' => array(
                    'link' => $data['link']
                    )
            );

            $headers = array(
                'Authorization:key ='.APP_FCM_KEY.'',
                'Content-Type: application/json'
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            if($result===FALSE) {
                die("Curl failed: ".curl_error($ch));
            }
            curl_close($ch);
        }
    }


    $result = $connect->query("SELECT * FROM tbl_fcm_token WHERE user_android_token IS NOT NULL AND user_android_token <> ''");

    $android_tokens = array();
    $x = 0;
    $i = 0;
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {

            $android_tokens[$i][] = $row["user_android_token"];
            $x++;
            // I need divide the array for 1000 push limit send in one time
            if ($x % 800 == 0) {
                $i++;
            }     
        }
        
    } else {
        echo "0 results";
    }
    
    // $ip = $_SERVER['REMOTE_ADDR'];
    // $result_check = $connect->query("SELECT * FROM `notifications` WHERE notification_sender_ip = '$ip' && notification_date > DATE_SUB(NOW(),INTERVAL 5 MINUTE)");
    // if ($result_check->num_rows > 2) {
    //         die('Anti flood protection. You can send only 3 notifications every 5 minutes!.');
    // }

    $title = $_POST['title'];
    $msg = $_POST['message'];
    $link = $_POST['link'];

    if ($android_tokens != array()) {
        $gcm=new GCM();
        $data=array("title"=>$title,"description"=>$msg,"link"=>$link);
        foreach ($android_tokens as $tokens) {
          $result_android = $gcm->send_notification($tokens,$data);
          sleep(1);
        }
        
        $sql = "INSERT INTO notifications (notification_title, notification_text, notification_extra, notification_sender_ip) VALUES ('$title', '$msg', '$link', '{$_SERVER['REMOTE_ADDR']}')";
        mysqli_query($connect, $sql);
    }

    $connect->close();

?>

    <section class="content">

        <ol class="breadcrumb">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="push-notification.php">Push Notification</a></li>
            <li class="active">Send Notification</a></li>
        </ol>

       <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    
                    <div class="card">
                        <div class="header">
                            <h2>PUSH NOTIFICATION</h2>
                        </div>
                        <div class="body">

                            <div class="row clearfix">

                                <div class="col-sm-12">
                                    <h3>Congratulations!</h3>
                                    <p>You have sent <?php echo $x;?> push notification.</p>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
            
        </div>

    </section>

<?php include('public/footer.php'); ?>
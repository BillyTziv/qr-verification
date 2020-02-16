<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Citystage QR</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js" integrity="sha384-NaWTHo/8YCBYJ59830LTz/P4aQZK1sS0SneOgAvhsIl3zBu8r9RevNg5lHCHAuQ/" crossorigin="anonymous"></script>

    <?php
        header('Access-Control-Allow-Origin: *');
        header("Content-type: text/html; charset=utf-8");
        
        require_once('../../../wp-load.php');
        
        session_start();

        // Check if the user email was specified.
        if( isset( $_GET['user_email'] ) ) {
            $umail = $_GET['user_email'];
        }else {
            die("Error #001: User email not found.");
        }
    ?>

    <style>
        * {
            box-sizing: border-box;
        }

        body { 
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
        }

        #logo_outter {
            text-align: center;
            padding: 15px;
        }

        .card-header {
            font-size: 16px;
            font-family: Arial, Helvetica, sans-serif;
        }

        .active_section {
            color: green;
        }

        .inactive_section {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="logo_outter">
                    <img width="75px" src="https://www.citystage.gr/wp-content/uploads/2019/09/logo-citystage.png"> <br />
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <?php
                    // Try to insert a new record to the database.
                    try{
                        global $wpdb;

                        // Get the user ID by the email
                        $user = get_user_by( 'email', $umail );

                        // Check if the requested email exists or not (registered wordpress user)
                        if( $user ) {
                            

                            // User exists
                            $uid = $user->ID;


                            // Check if there is an already record inthe database or not
                            $data_tbl = $wpdb->prefix . 'data_log';
                            $sql = "SELECT * FROM {$data_tbl} WHERE `uid`=" . $uid;
                            //echo $sql;
                            $results = $wpdb->get_results( $sql );


                            // User has a record so update the counter.
                            if( $results != null) {
                                // At least one record was found in the database with a QR code.

                                if( !isset($_GET['user_email'] ) ) { ?>
                                    <div class="alert alert-danger" role="alert">
                                        <i class="fa fa-warning fa-lg"></i> Ουπς, το email δεν βρέθηκε!
                                        <?php mail("vtzivaras@gmail.com", "Error #4222: QR function - Έγινε σκανάρισμα χωρίς να έχει οριστεί το εμαιλ." . "ο κωδικός QR: " . $_GET['user_code'] . " Input email: " . $_GET['user_email']); die(); ?>
                                    </div> <?php
                                }
            
                                if( !isset($_GET['user_code'] ) ) { ?>
                                    <div class="alert alert-danger" role="alert">
                                        <i class="fa fa-warning fa-lg"></i> Ουπς, το QR code δεν βρέθηκε!
                                        <?php mail("vtzivaras@gmail.com", "Error #4233: QR function - Έγινε σκανάρισμα χωρίς να έχει οριστεί" . "ο κωδικός QR: " . $_GET['user_code'] . " Input email: " . $_GET['user_email']); die(); ?>
                                    </div> <?php
                                }


                                $u_code = $_GET['user_code'];
                                $u_mail = $_GET['user_mail'];

                                $value = $results[0]->status + 1;

                                $selected_email = $user->user_email;


                                $num = $wpdb->update('ok8p1K0_data_log', array('status'=>$value), array('email'=>$selected_email), array('%s'), array( '%d') );

                                if ( $num > 0) {
                                    if( $value > 3 ) { ?>

<?php
                                        // Check if there is an already record inthe database or not
                                        $data_tbl = $wpdb->prefix . 'data_log';

                                        $sql = "SELECT * FROM {$data_tbl} WHERE `code`='" . $u_code . "'";
                                        $qr_res = $wpdb->get_results( $sql );


                                        // User has a record so update the counter.
                                        if( $qr_res != null) {
                                        $text = "https://www.citystage.gr/wp-content/plugins/lostark/getData.php?user_email=" . $selected_email . "&user_code=" . $qr_res[0]->code;
                                
                                        // Generate a new QR Code (as image)
                                        include "./phpqrcode/qrlib.php";
                                    
                                        $path = 'images/'; 
                                        
                                        $file = $path . $u_code . ".png"; 
                                        
                                        
                                        // Displaying the stored QR code from directory 
                                        //echo "<center><img src='" . $file . "'></center>"; 

                                        ?> <br />
                                        <div class="card text-center">
                                    <div class="card-header">
                                        <div class="alert alert-info" style="max-width: 500px; margin: auto;" role="info">
                                            <i class="fa fa-info fa-lg"></i> Ο κωδικός αυτός έχει ήδη χρησιμοποιηθεί.
                                            
                                            <?php //mail("vtzivaras@gmail.com", "Error #4203: QR function - Given email is not associated with any user. Input email: " . $_GET['user_email']); ?>
                                        </div>
                                    </div> <br /> <br />
                                    <div class="card-body">
                                        <?php echo "<center><img src='" . $file . "'></center>"; ?><br />

                                        <div class="inactive_section"> <i class="fa fa-circle fa-lg"></i> <strong>Ανενεργός</strong> </div>

                                        <!--<h5 class="card-title">Special title treatment</h5>
                                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p> -->
                                        <br /> <br /> <!-- <a href="#" class="btn btn-primary"><i class="fa fa-download fa-lg" aria-hidden="true"></i> Αποθήκευση στον υπολογιστή σας</a> -->
                                    </div>
                                    <div class="card-footer text-muted">
                                        <br />
                                        <i>Μάθετε πως μπορείτε να σκανάρετε ένα QR κωδικό <a href="#">εδώ</a>.</i>
                                    </div>
                                </div>


                                        <?php

                                        }
                                        }else { ?>
                                      <?php
                                        // Check if there is an already record inthe database or not
                                        $data_tbl = $wpdb->prefix . 'data_log';

                                        $sql = "SELECT * FROM {$data_tbl} WHERE `code`='" . $u_code . "'";
                                        $qr_res = $wpdb->get_results( $sql );


                                        // User has a record so update the counter.
                                        if( $qr_res != null) {
                                        $text = "https://www.citystage.gr/wp-content/plugins/lostark/getData.php?user_email=" . $selected_email . "&user_code=" . $qr_res[0]->code;
                                
                                        // Generate a new QR Code (as image)
                                        include "./phpqrcode/qrlib.php";
                                    
                                        $path = 'images/'; 
                                        
                                        $file = $path . $u_code . ".png"; 
                                        
                                        
                                        // Displaying the stored QR code from directory 
                                        //echo "<center><img src='" . $file . "'></center>"; 

                                        ?> <br />
                                        <div class="card text-center">
                                    <div class="card-header">
                                        <div class="alert alert-info" style="max-width: 500px; margin: auto;" role="info">
                                            <i class="fa fa-info fa-lg"></i> Ενεργός κωδικός προσφοράς City Stage.
                                            <?php //mail("vtzivaras@gmail.com", "Error #4203: QR function - Given email is not associated with any user. Input email: " . $_GET['user_email']); ?>
                                        </div>
                                    </div> <br /> <br />
                                    <div class="card-body">
                                        <?php echo "<center><img src='" . $file . "'></center>"; ?><br />

                                        <div class="active_section"> <i class="fa fa-circle fa-lg"></i> <strong>Ενεργός
                                        <!--<h5 class="card-title">Special title treatment</h5>
                                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p> -->
                                        <br /> <br /> <!-- <a href="#" class="btn btn-primary"><i class="fa fa-download fa-lg" aria-hidden="true"></i> Αποθήκευση στον υπολογιστή σας</a> -->
                                    </div>
                                    <div class="card-footer text-muted">
                                        <br />
                                        <i>Μάθετε πως μπορείτε να σκανάρετε ένα QR κωδικό <a href="#">εδώ</a>.</i>
                                    </div>
                                </div>


                                        <?php

                                        }

                                    }

                                } else {
                                // echo "Error updating record: " . $wpdb->error;
                                }

                            
                            }else { ?>

                                <?php
                                    include "./phpqrcode/qrlib.php";
                    
                                    $path = 'images/'; 
                                    $uniq_id = uniqid();

                                    //echo "Creating unique id for QR code..." . "<br />";
                                    //echo "Unique ID: " . $uniq_id . "<br />";

                                    $file = $path . $uniq_id . ".png"; 

                                    //echo "Creating path to save the photos..." . "<br />";
                                    //echo "PATH: " . $file . "<br />";

                                    $display_msg = "https://www.citystage.gr/wp-content/plugins/lostark/getData.php?user_email=" . $user->user_email . "&user_code=" . $uniq_id;               
                                    
                                    // $ecc stores error correction capability('L') 
                                    $ecc = 'L'; 
                                    $pixel_Size = 3;
                                    $frame_Size = 3;
                                    
                                    // Generates QR Code and Stores it in directory given 
                                    QRcode::png($display_msg, $file, $ecc, $pixel_Size, $frame_size); 

                                    // Insert new QR code in the database.
                                    $data = array('uid' => $uid, 'email' => $user->user_email, 'code' =>  $uniq_id, 'status' => '1' );
                                    $format = array('%d','%s','%s','%d');
                                    $ins_result = $wpdb->insert('ok8p1K0_data_log', $data, $format);

                                    // Check for insertion errors
                                    $ins_row_id = $wpdb->insert_id;
                                    if( $ins_row_id <= 0 ) {
                                        die("Error 03: New QR code was not inserted into the database. Reprot this incident.");
                                    }
                                ?>

                                <div class="card text-center">
                                    <div class="card-header">
                                        <div class="alert alert-info" style="max-width: 500px; margin: auto;" role="info">
                                            <i class="fa fa-info fa-lg"></i> Συγχαρητήρια για την εγγραφή σου στο City Stage! Παρακάτω θα βρεις το barcode με τον μοναδικό κωδικό σου για την ενεργοποίηση των προσφορών του City Stage. Δείξε το barcode στα συνεργαζόμενα καταστήματα για να λάβεις την προσφορά.
                                            <?php //mail("vtzivaras@gmail.com", "Error #4203: QR function - Given email is not associated with any user. Input email: " . $_GET['user_email']); ?>
                                        </div>
                                    </div> <br /> <br />
                                    <div class="card-body">
                                        <?php echo "<center><img src='" . $file . "'></center>"; ?>

                                        <br />
                                            
                                        <div class="active_section"> <i class="fa fa-circle fa-lg"></i> <strong> Ενεργός
                                        <!--<h5 class="card-title">Special title treatment</h5>
                                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p> -->
                                        <br /> <br /><!-- <a href="#" class="btn btn-primary"><i class="fa fa-download fa-lg" aria-hidden="true"></i> Αποθήκευση στον υπολογιστή σας</a> -->
                                    </div>
                                    <div class="card-footer text-muted">
                                        <br />
                                        <i>Μάθετε πως μπορείτε να σκανάρετε έναν QR κωδικό <a href="#">εδώ</a>.</i>
                                    </div>
                                </div> <?php
                            }
                        }else { ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="fa fa-warning fa-lg"></i> Το συγκεκριμένο email δεν ανήκει σε εγγεγραμένο χρήστη του Citystage. Παρακαλώ προχωρήστε σε νέα εγγραφή πατώντας <a href="https://www.citystage.gr/my-profile/" class="alert-link">εδώ</a>.
                                <?php mail("vtzivaras@gmail.com", "Error #4203: QR function - Given email is not associated with any user. Input email: " . $_GET['user_email']); ?>
                            </div> <?php
                        }
                    }catch(Throwable $e){
                        die("Oups, something went wrong on contact btn.");
                    }
                ?>
            </div>
        </div>
    </div>
</body>
</html>

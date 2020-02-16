<html>
    <head></head>

    <body>

        <?php
            /*
            * Get the contact details request and save the action to the database.
            */

            header('Access-Control-Allow-Origin: *');
            header("Content-type: text/html; charset=utf-8");
            
            require_once('../../../wp-load.php');
            
            session_start();

            //$uid = $_GET['uid'];

            try{
                // Try insert a new record to the database
                global $wpdb;

                $data_tbl = $wpdb->prefix . 'data_log';
                $sql = "SELECT * FROM {$data_tbl}";

                $results = $wpdb->get_results($sql) or die(mysql_error());

                echo "Database contains: 
                        <table border='1'>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Code</th>
                                <th>Status</th>
                            </tr>";
                foreach( $results as $row ) {

                    echo "<tr><td>" . $row->id. "</td><td>" .  $row->uid. "</td><td>".  $row->code. "</td><td>" . $row->status. "</td></tr>";

                }
                echo "</table>";                
            

                //$subject = 'New Record Insertion'; // In case you want tot call him just <a href=\"tel:+" . $_GET['phone'] . "\">click here</a><br /> <br />Have a nice day Demetris ;)
                //$body = 'Someone made a new insertion. UID: ' . $uid;
                //$headers = array('Content-Type: text/html; charset=UTF-8');
                

                //wp_mail( "vtzivaras@gmail.com", $subject, $body, $headers );

                //array_push($u, array(
                //   'status' => "OK",
                //));

                //echo json_encode($u);
            
            }catch(Throwable $e){
                die("Oups, something went wrong on contact btn.");
            }
        ?>

       
            


    </body>
</html>


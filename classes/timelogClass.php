<?php
    require_once("../includes/definitions.php");
    require_once("dbClass.php");

    class timelogClass{

        // Assumes it was unsuccessfull.
        // Can only be one status message.
        public $response = array('successful' => false, 'errors' => array(), 'statusmessage' => "", 'variables' => array());


        function __construct(){
            $this->db = new dbClass();

            $createTableQuery = "CREATE TABLE timelogs (
                id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user int(11) NOT NULL,
                starttime DATETIME NOT NULL,
                endtime DATETIME NOT NULL,
                finished BIT NOT NULL,
                INDEX user (user),
                CONSTRAINT user FOREIGN KEY (user) REFERENCES users (id) ON UPDATE CASCADE
            )";

            // Checks if table exists. If it does not: create it.
            $this->db->query("SHOW TABLES LIKE 'timelogs'");
            $this->db->execute();
            if($this->db->rowCount() == 1) {
                $this->addError("Table 'timelogs' already exist!");
            }
            else {
                $this->db->query($createTableQuery);
                $this->db->execute();
            }

            $createTableQuery = "CREATE TABLE users (
              	id INT(11) NOT NULL AUTO_INCREMENT,
                fullname VARCHAR(50) NOT NULL,
                username VARCHAR(100) NOT NULL,
                password VARCHAR(150) NOT NULL,
                email VARCHAR(100) NOT NULL,
                text TEXT NOT NULL,
                admin INT(3) NOT NULL,
                profilepic VARCHAR(50) NOT NULL,
                PRIMARY KEY (id)
            )";

            // Checks if table exists. If it does not: create it.
            $this->db->query("SHOW TABLES LIKE 'users'");
            $this->db->execute();
            if($this->db->rowCount() == 1) {
                $this->addError("Table 'users' already exist!");
            }
            else {
                $this->db->query($createTableQuery);
                $this->db->execute();
            }
        }

        public function addError($error = ""){
            $this->response['errors'][] = $error;
        }
        public function returnErrors(){
            return $this->response['errors'];
        }

        public function setSuccessful($successful = true){
            $this->response['successful'] = $successful;
        }

        public function setStatusMessage($message = "Something happened"){
            $this->response['statusmessage'] = $message;
        }

        public function addVariable($key = "", $value = ""){
            $this->response['variables'][$key] = $value;
        }

        function login($username, $password){
            $sqlusers = "SELECT * FROM ".DB_USERTABLE." WHERE username = :username";

            $this->db->query($sqlusers);
            $this->db->execute(['username' => $username]);

            $user = $this->db->resultSingle();

            if($this->db->rowCount() != 0){
                // We found the user.
                $hash = $user['password'];
                if (password_verify($password, $hash)){
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_username'] = $user['username'];
                    $_SESSION['user_admin'] = $user['admin'];

                    $this->setSuccessful(true);
                    $this->setStatusMessage("Successfully logged in!");

                }else{
                    $this->addError("User put in wrong password (but it does exist)!");
                    $this->setSuccessful(false);
                    $this->setStatusMessage("Wrong info on login. Username or password is incorrect.");
                }
            }else {
                // no user was found.
                $this->addError("User was not found!");
                $this->setSuccessful(false);
                $this->setStatusMessage("Wrong info on login. Username or password is incorrect.");
            }
        }

        function logout(){
            session_unset();

            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }
            session_destroy();
            $this->setStatusMessage("User has been logged out.");
        }

        function toggleTimer(){
            if(isset($_SESSION['user_id'])){
                $userid = $_SESSION['user_id'];
                $running = false;
                // Either start a new timer or stop the one already running.

                // Gets all not finished timers for user.
                $sql = "SELECT * FROM timelogs WHERE user = :userid AND finished = 0";

                $this->db->query($sql);
                $this->db->execute(['userid' => $userid]);
                if($this->db->rowCount() == 1) {
                    $this->addError("Found a running timer.");
                    $running = true;
                }
                else {
                    $this->addError("Found no running timer.");
                    $running = false;
                }

                $currentDateTime = new DateTime("now");
                $currentDateTime = $currentDateTime->format('Y:m:d H:i:s');
                
                if($running){
                    $sql = "UPDATE timelogs SET endtime = :endtime, finished=1 WHERE user=:userid AND finished=0";
                    $this->db->query($sql);
                    $this->db->execute(['userid' => $userid, 'endtime' => $currentDateTime]);
                    $this->setSuccessful(false);
                    $this->setStatusMessage("Stopped a timer.");
                }else{
                    $sql = "INSERT INTO timelogs (user, starttime, finished) VALUES (:userid, :starttime, 0)";
                    $this->db->query($sql);
                    $this->db->execute(['userid' => $userid, 'starttime' => $currentDateTime]);
                    $this->setSuccessful(true);
                    $this->setStatusMessage("Started a timer.");
                }

                $this->addVariable($currentDateTime);


            }
        }

        function getActiveTimer(){
            $userid = $_SESSION['user_id'];

            if (isset($userid)){

                try{
                    // Gets all not finished timers for user.
                    $sql = "SELECT * FROM timelogs WHERE user = :userid AND finished = 0";

                    $this->db->query($sql);
                    $this->db->execute(['userid' => $userid]);
                    $result = $this->db->resultSingle();
                    $startDateTime = new DateTime($result['starttime']);
                    $currentDateTime = new DateTime("now");

                    $timediff = $currentDateTime->getTimestamp() - $startDateTime->getTimestamp();
                    $this->addVariable($timediff);

                    if($this->db->rowCount() == 1) {
                        $this->setStatusMessage("timer was found.");
                        $this->addVariable("timervalue", $timediff);
                        $this->setSuccessful(true);
                    }
                    else {
                        $this->setStatusMessage("no timer was found.");
                        $this->setSuccessful(false);
                    }

                }catch (Exception $exception){
                    $this->addError($exception->getMessage());
                }


            }
        }

        function getTimelogs(){
            $output = "<table class='table'>";

            $sql = "SELECT logs.id, logs.user, users.username, logs.starttime, logs.endtime, logs.finished FROM timelogs logs JOIN users users ON logs.user = users.id ORDER BY logs.starttime DESC";

            $this->db->query($sql);
            $this->db->execute();
            $logs = $this->db->resultAssoc();


            $output .= "<thead><tr>";
            foreach(array_keys($logs[0]) as $key){
                $output .= "<th>$key</th>";
            }
            $output .= "</tr></thead>";

            $output .= "<tbody>";
            foreach($logs as $log){
                $output .= "<tr>";
                foreach($log as $key => $value){
                    $output .= "<td>$value</td>";
                }
                $output .= "</tr>";
            }
            $output .= "</tbody>";
            $output .= "</table>";

            $this->setSuccessful(true);
            $this->addVariable("output", $output);
        }

        function getUserStats(){
            $userid = $_SESSION['user_id'];
            $output = "<table class='table'>";

            $sql = "SELECT logs.id, logs.user, users.username, logs.starttime, logs.endtime, logs.finished FROM timelogs logs JOIN users users ON logs.user = users.id WHERE logs.user = :userid ORDER BY logs.starttime DESC";

            $this->db->query($sql);
            $this->db->execute(['userid' => $userid]);
            $logs = $this->db->resultAssoc();


            $output .= "<thead><tr>";

            $output .= "<th>Start Time</th>";
            $output .= "<th>End Time</th>";
            $output .= "<th>Total Time</th>";

            $output .= "</tr></thead>";

            $output .= "<tbody>";
            foreach($logs as $log){
                $output .= "<tr>";

                $starttime = $log['starttime'];
                $endtime = $log['endtime'];
                $startDateTime = new DateTime($starttime);
                $endDateTime = new DateTime($endtime);
                if($log['finished']){
                    $timediff = $endDateTime->getTimestamp() - $startDateTime->getTimestamp();
                    $timediff .= ""." seconds";
                }else{
                    $timediff = "Not Finished.";
                    $endtime = "Not Finished.";
                }


                $output .= "<td>$starttime</td>";
                $output .= "<td>$endtime</td>";
                $output .= "<td>$timediff</td>";

                $output .= "</tr>";
            }
            $output .= "</tbody>";
            $output .= "</table>";

            $this->setSuccessful(true);
            $this->addVariable("output", $output);

        }



    }
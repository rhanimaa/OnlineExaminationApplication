<?php 
    $Uusername = $_POST["username"];
    $Ppassword = $_POST["password"];
    $found = "false";

    $servername = "dbprojects.eecs.qmul.ac.uk";
    $username = "as396";
    $password = "nPLVi52BQXSMS";
    $dbname = "as396";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }else{
        $sql = "SELECT * FROM UserInfo u, MarkerUser m 
        WHERE u.UserID = m.UserID AND '$Uusername' = u.Username AND '$Ppassword' = u.UPassword;
        " ;

        $result = $conn->query($sql);

        if ($result->num_rows > 0){
            $found = "true";
        }
    }

    if($found == "true"){
        session_start();
        $_SESSION['username'] = $Uusername;
        $conn->close();
        header("Location: MarkerLandingPage.php");
    }else{
        $conn->close();
        header("Location: UserNotFound.html");
    }

?>
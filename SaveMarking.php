<?php 
    session_start();
    $Uusername = $_SESSION["username"];
    $AnsID = $_SESSION["AnsID"];
    $Mark = $_POST["Mark"];
    $MaxMark = $_SESSION["MaxMark"];
    $User = 'Teacher';


    $servername = "dbprojects.eecs.qmul.ac.uk";
    $username = "as396";
    $password = "nPLVi52BQXSMS";
    $dbname = "as396";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }else{
        $sql = "SELECT * FROM UserInfo u, TeacherUser t 
        WHERE u.UserID = t.UserID AND '$Uusername' = u.Username;
        " ;

        $result = $conn->query($sql);

        if ($result->num_rows > 0){
            $row = $result->fetch_assoc();
            $name = $row["UName"];
            $MarkerID = $row["MarkerID"];
        }else{
            $User = 'Marker';
        }

        if( ((int) $Mark) > $MaxMark ){
            $sql = "UPDATE Answer 
            SET Marks = $MaxMark
            WHERE AnswerID = $AnsID";
            $conn->query($sql);
        }else{
            $sql = "UPDATE Answer 
            SET Marks = $Mark
            WHERE AnswerID = $AnsID";
            $conn->query($sql);
        }

        $sql = "UPDATE EssayAnswer
        SET Marked = 'True'
        WHERE AnswerID = $AnsID";
        $conn->query($sql);
    }

    $conn->close();
    if($User == 'Teacher'){
        header("Location: TeacherLandingPage.php");
    }else{
        header("Location: MarkerLandingPage.php");
    }
?>
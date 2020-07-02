<?php 
    $UserUsername = $_POST["username"];
    $UserPassword = $_POST["password"];
    $UserName = $_POST['name'];
    $UserEmail = $_POST['email'];
    $UserAccount = $_POST['account'];

    $servername = "dbprojects.eecs.qmul.ac.uk";
    $usernamedb = "as396";
    $password = "nPLVi52BQXSMS";
    $dbname = "as396";
    
    $conn = new mysqli($servername, $usernamedb, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }else{

        $sql = "
        SELECT * FROM UserInfo WHERE
        Username = '$UserUsername';
        " ;

        $result = $conn->query($sql);

        if ($result->num_rows > 0){
            session_start();
            $_SESSION['reason'] = 'Username';
            $conn->close();
            header("Location: InvalidLogin.php");
        }

        $sql = "
        SELECT * FROM UserInfo WHERE
        UEmail = '$UserEmail';
        " ;

        $result = $conn->query($sql);

        if ($result->num_rows > 0){
            session_start();
            $_SESSION['reason'] = 'Email';
            $conn->close();
            header("Location: InvalidLogin.php");
        }

        $sql = "
        SELECT MAX(UserID) as biggest FROM UserInfo;
        " ;

        $result = $conn->query($sql);
        $UserID = 0;

        $row = $result->fetch_assoc();
        $UserID = $row['biggest'] + 1;
        echo $UserID;

        $sql = "
        INSERT INTO UserInfo VALUES($UserID, '$UserUsername', '$UserPassword', '$UserEmail', '$UserName');
        " ;
        if($conn->query($sql)=== TRUE){
            echo "doneeeeee<br>";
        }else{
            echo "Error: " . $sql . "<br>" . $conn->error;
        }


        if($UserAccount == "student"){
            $OtherID = 0;
            $sql = "
            SELECT MAX(StudentID) as biggest FROM StudentUser;
            " ;
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $OtherID = $row['biggest'] + 1;
            $sql = "
            INSERT INTO StudentUser VALUES($OtherID, $UserID);
            " ;
            $conn->query($sql);
            echo"done";
        }else if($UserAccount == "marker"){
            $OtherID = 0;
            $sql = "
            SELECT MAX(MarkerID) as biggest FROM MarkerUser;
            " ;
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $OtherID = $row['biggest'] + 1;
            $sql = "
            INSERT INTO MarkerUser VALUES($OtherID, $UserID);
            " ;
            $conn->query($sql);
        }else if($UserAccount == "teacher"){
            $MID = 0;
            $sql = "
            SELECT MAX(MarkerID) as biggest FROM MarkerUser;
            " ;
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $MID = $row['biggest'] + 1;
            $TID = 0;
            $sql = "
            SELECT MAX(TeacherID) as biggest FROM TeacherUser;
            " ;
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $TID = $row['biggest'] + 1;
            $sql = "INSERT INTO MarkerUser VALUES($MID, $UserID);";
            $conn->query($sql);
            $sql = "
            INSERT INTO TeacherUser VALUES($TID, $UserID, $MID);
            " ;
            $conn->query($sql);
        }
            
        $conn->close();
        header("Location: index.html");
    }
?>
<?php 

session_start();
$Uusername = $_SESSION["username"];
$SID = $_SESSION["QID"];

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
    }
    $i = 1;
    foreach($SID as $Question){
        $sql = "SELECT * FROM Question q, MCQuestion m WHERE
            q.QuestionID = m.QuestionID AND q.QuestionID = $Question";
        $result = $conn->query($sql);
                    
        if ($result->num_rows > 0){
            $QName = $_POST["Name$i"];
            $Qmark = $_POST["Mark$i"];
            $QPAns = $_POST["PAns$i"];
            $Ans = $_POST["Answer$i"];
            $sql = "UPDATE Question 
            SET Question = '$QName', Marks = '$Qmark'
            WHERE QuestionID = '$Question'";
            $conn->query($sql);
            $sql = "UPDATE MCQuestion
            SET PAnswer = '$QPAns', Answer = '$Ans' 
            WHERE QuestionID = '$Question'";
            $conn->query($sql);
        }else{
            $QName = $_POST["Name$i"];
            $Qmark = $_POST["Mark$i"];
            $Ans = $_POST["Answer$i"];
            $MID = $_POST["Marker$i"];
            $sql = "UPDATE Question 
            SET Question = '$QName', Marks = '$Qmark'
            WHERE QuestionID = '$Question'";
            $conn->query($sql);
            $sql = "UPDATE EssayQuestion 
            SET Answer = '$Ans', MarkerID = '$MID'
            WHERE QuestionID = '$Question'";
            $conn->query($sql);
        }
        $i++;
    }

}
$conn->close();
header("Location: TeacherLandingPage.php");

?>
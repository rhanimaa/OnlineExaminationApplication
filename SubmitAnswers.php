<?php 

session_start();
$Uusername = $_SESSION["username"];
$QID = $_SESSION["QID"];

$servername = "dbprojects.eecs.qmul.ac.uk";
$username = "as396";
$password = "nPLVi52BQXSMS";
$dbname = "as396";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}else{
    $sql = "SELECT * FROM UserInfo u, StudentUser s 
    WHERE u.UserID = s.UserID AND '$Uusername' = u.Username;
    " ;

    $result = $conn->query($sql);

    if ($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $name = $row["UName"];
        $SID = $row["StudentID"];
    }

    $sql = "SELECT MAX(AnswerID) as biggest FROM Answer;";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $biggest = $row["biggest"] + 1;

    $i = 1;
    foreach($QID as $ID){
        $sql = "SELECT * FROM Question q, MCQuestion m WHERE
            q.QuestionID = m.QuestionID AND q.QuestionID = $ID";
        $result = $conn->query($sql);

        if ($result->num_rows > 0){
            $Answer = $_POST["Question$i"];
            $FANS = '';
            if(sizeof($Answer > 1)){
                foreach($Answer as $ANS){
                    $FANS = $FANS."-".$ANS;
                }
            }else{
                $FANS = array_pop($Answer);
            }
            $ANS = $result->fetch_assoc();
            $AccANS = $ANS["Answer"];
            if($AccANS == $FANS){
                $Mark = $ANS["Marks"];
                $sql ="INSERT INTO Answer VALUES($biggest, $SID, $ID, $Mark);";
                $conn->query($sql);
                $sql="INSERT INTO MCAnswer VALUES($biggest, '$FANS');";
                $conn->query($sql);
            }else{
                $sql ="INSERT INTO Answer VALUES($biggest, $SID, $ID, 0);";
                $conn->query($sql);
                $sql="INSERT INTO MCAnswer VALUES($biggest, '$FANS');";
                $conn->query($sql);
            }
            $biggest++;
            $i++;
        }
        else{
            $sql = "SELECT * FROM Question q, EssayQuestion e WHERE
            q.QuestionID = e.QuestionID AND q.QuestionID = $ID";
            $result = $conn->query($sql);

            $Answer = $_POST["Question$i"];

            $sql ="INSERT INTO Answer VALUES($biggest, $SID, $ID, 0);";
            $conn->query($sql);
            $sql="INSERT INTO EssayAnswer VALUES($biggest, '$Answer','False');";
            if ($conn->query($sql) === TRUE) {
                        echo "Sucsess";
                    //Your code
                    } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                    }

            $biggest++;
            $i++;
        }
    }
    $EID = $_SESSION["ExamID"];
    $sql = "UPDATE ExamToDo 
    SET Done = 'True' WHERE StudentID = $SID AND ExamID = $EID ";
    //$conn->query($sql);
    if ($conn->query($sql) === TRUE) {
            echo "Sucsess";
        //Your code
        } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        }

}
$conn->close();
header("Location: StudentLandingPage.php");

?>
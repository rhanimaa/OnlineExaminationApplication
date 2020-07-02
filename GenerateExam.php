<?php 

session_start();
$Uusername = $_SESSION["username"];
$ExamName = $_POST["ExamName"];
$Length = $_POST["Length"];
$Date = $_POST["Date"];
$StartTime = $_POST["StartTime"];
$StayTime  = $_POST["StayTime"];
$EndTime  =$_POST["EndTime"];
$MCQ = $_POST["MCQ"];
$Essay = $_POST["Essay"];
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
    $sql = "SELECT MAX(ExamID) as biggest From Exam;";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $biggest = $row["biggest"] + 1;
    $date = date('d-m-Y', strtotime($Date) );

    $sql = "INSERT INTO Exam VALUES($biggest, '$ExamName', $Length, '$date', $StartTime, $StayTime, $EndTime );";
    if ($conn->query($sql) === TRUE) {
        echo "Sucsess";
    //Your code
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        header("Location: TeacherLandingPage.php");
    }
    $QID = array();
    $sql = "SELECT MAX(QuestionID) as biggest FROM Question;";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $QuestionID1 = $row["biggest"] + 1;
    for($i = 1; $i <= $MCQ ; $i ++){
        $sql = "INSERT INTO Question VALUES($QuestionID1, 'Question $i', $i, 5, $biggest);";
        $conn->query($sql);
        $sql = "INSERT INTO MCQuestion VALUES($QuestionID1, 'Option1-Option2-Option3-Option4', '-Option2');";
        $conn->query($sql);
        array_push($QID, $QuestionID1);
        $QuestionID1 ++;
        $QuestionNo = $i + 1;
    }
    for($i = 1; $i <= $Essay ; $i ++){
        $sql = "INSERT INTO Question VALUES($QuestionID1, 'Question $QuestionNo', $QuestionNo, 5, $biggest);";
        $conn->query($sql);
        $sql = "INSERT INTO EssayQuestion VALUES($QuestionID1, 'This is the Mark Scheme', $MarkerID);";
        $conn->query($sql);
        array_push($QID, $QuestionID1);
        $QuestionID1 ++;
        $QuestionNo ++;
    }
}
$conn->close();
$_SESSION["QID"] = $QID;
$_SESSION["ExamID"] = $biggest;
header("Location: EditQuestions.php");
?>
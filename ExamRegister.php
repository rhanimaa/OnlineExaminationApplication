<?php 

$StudentID = $_POST["StudentID"];
$ExamID = $_POST["ExamID"];

$servername = "dbprojects.eecs.qmul.ac.uk";
$username = "as396";
$password = "nPLVi52BQXSMS";
$dbname = "as396";
// Creates connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Checks connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}else {
    
    $sql = "INSERT INTO ExamToDo VALUES($StudentID, $ExamID,'False');";

    if ($conn->query($sql) === TRUE) {
        echo "Sucsess";
    //Your code
    } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
    }
    header("Location: StudentLandingPage.php");

    $conn->close();
}

?>
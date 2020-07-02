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

}

?>

<!DOCTYPE html>
<html>
    <head lang="en">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <meta charset="utf-8">
        <style 
        type="text/css">
            .middle {
              margin-left: 10%;
              margin-right: 10%;
              margin-top: 5%;
            }
        </style>
        <title>Teacher Page</title>
    </head>
    <body>

          <div class="card middle">
          <h5 class="card-header"><?php echo "Exam ID : ".$_SESSION["ExamID"]."" ; ?></h5>
            <div class="card-body">
                <form action="http://webprojects.eecs.qmul.ac.uk/as396/se19/SaveQuestions.php" method="POST">
                    

                    <?php 
                        
                        foreach($SID as $Question){
                            $sql = "SELECT * FROM Question q, MCQuestion m WHERE
                            q.QuestionID = m.QuestionID AND q.QuestionID = $Question";
                            $result = $conn->query($sql);
                    
                            if ($result->num_rows > 0){
                                $row = $result->fetch_assoc();
                                $QQuestion = $row["Question"];
                                $QuestionNo = $row["QuestionNo"];
                                $Marks= $row["Marks"];
                                $PAnswer = $row["PAnswer"];
                                $Answer = $row["Answer"];
                                echo'
                                <div class="card middle">
                                <div class="card-body">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Question</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1" name="Name'.$QuestionNo.'" Value="'.$QQuestion.'" >
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Marks</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1" name="Mark'.$QuestionNo.'" Value="'.$Marks.'" >
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Possible Answers</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1" name="PAns'.$QuestionNo.'" Value="'.$PAnswer.'" >
                                    <small id="emailHelp" class="form-text text-muted">Seperate options with a dash(-)</small>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Answer</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1" name="Answer'.$QuestionNo.'" Value="'.$Answer.'" >
                                    <small id="emailHelp" class="form-text text-muted">Start Every Ans With a -</small>
                                </div>
                                </div>
                                </div>
                                ';
                                
                            }else{
                                $sql = "SELECT * FROM Question q, EssayQuestion e WHERE
                                q.QuestionID = e.QuestionID AND q.QuestionID = $Question";
                                $result = $conn->query($sql);
                                $row = $result->fetch_assoc();
                                $QQuestion = $row["Question"];
                                $QuestionNo = $row["QuestionNo"];
                                $Marks= $row["Marks"];
                                $Answer = $row["Answer"];
                                $QMarkerID = $row["MarkerID"];
                                echo'
                                
                                <div class="card middle">
                                <div class="card-body">

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Question</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1" name="Name'.$QuestionNo.'" Value="'.$QQuestion.'" >
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Marks</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1" name="Mark'.$QuestionNo.'" Value="'.$Marks.'" >
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlTextarea1">Mark Scheme for Markers</label>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name = "Answer'.$QuestionNo.'">'.$Answer.'</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Marker ID</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1" name="Marker'.$QuestionNo.'" Value="'.$QMarkerID.'" >
                                    <small id="emailHelp" class="form-text text-muted">This is the marker that will mark all these questions</small>
                                </div>

                                </div>
                                </div>
                                ';

                            }
                        }
                    
                    
                    ?>


                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>



        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    </body>
</html>

<?php 
    $conn->close();
?>
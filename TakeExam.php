<?php 

session_start();
$Uusername = $_SESSION['username'];
$ExamID = $_POST["ExamID"];
$_SESSION["ExamID"] = $ExamID;

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

    $sql = "SELECT * FROM UserInfo u, StudentUser s 
    WHERE u.UserID = s.UserID AND '$Uusername' = u.Username;
    " ;

    $result = $conn->query($sql);

    if ($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $name = $row["UName"];
        $ID = $row["StudentID"];
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
                <form action="http://webprojects.eecs.qmul.ac.uk/as396/se19/SubmitAnswers.php" method="POST">
                    

                    <?php 

                        $QID = array();
                        
                        $sql = "SELECT * FROM Question q, MCQuestion m
                        WHERE q.QuestionID = m.QuestionID AND q.ExamID = $ExamID;";
                        $result = $conn->query($sql);
                        if($result->num_rows > 0){
                            foreach($result as $row){
                                $Question = $row["Question"];
                                $Options = explode("-", $row["PAnswer"]);
                                $Qnum = $row["QuestionNo"];
                                echo '

                                <div class="card middle">
                                <div class="card-body">
                                
                                <div class="form-group">
                                <label for="exampleInputEmail1">'.$Qnum.') '.$Question.'</label><br>
                                ';

                                foreach($Options as $Option){
                                    echo'<input type="checkbox" name="Question'.$Qnum.'[]" value="'.$Option.'" ><label>'.$Option.'</label><br/>';
                                }

                                echo '
                                </div>

                                </div>
                                </div>
                                ';
                                array_push($QID,$row["QuestionID"]);
                            }
                        }

                        $sql = "SELECT * FROM Question q, EssayQuestion e 
                        WHERE q.QuestionID = e.QuestionID AND q.ExamID = $ExamID;";
                        $result = $conn->query($sql);
                        if($result->num_rows > 0){
                            foreach($result as $row){
                                $Question = $row["Question"];
                                $Qnum = $row["QuestionNo"];

                                echo'
                                <div class="card middle">
                                <div class="card-body">
                                
                                <div class="form-group">
                                <label for="exampleInputEmail1">'.$Qnum.') '.$Question.'</label><br>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="Question'.$Qnum.'"></textarea>
                                </div>

                                </div>
                                </div>
                                ';
                                array_push($QID,$row["QuestionID"]);
                            }
                        }
                    
                    
                    ?>


                    <button type="submit" class="btn btn-primary">Submit</button>
                



        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    </body>
</html>

<?php 
$conn->close();
?>
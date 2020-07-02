<?php 
    session_start();
    $Uusername = $_SESSION['username'];
    $EID = $_POST["ExamID"];
    $servername = "dbprojects.eecs.qmul.ac.uk";
    $username = "as396";
    $password = "nPLVi52BQXSMS";
    $dbname = "as396";
    $StudentScore = 0;
    $MaxScore = 0;

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
        <title>Results Page</title>
    </head>
    <body>

        <div class="card middle">
          <h5 class="card-header"><?php echo "Exam ID : ".$EID."" ; ?></h5>
            <div class="card-body">

            <?php 

            $sql = "SELECT q.Marks as Max_Mark, a.Marks as Student_Mark, q.Question, a.AnswerID, q.QuestionID 
            FROM Question q, Answer a WHERE q.QuestionID = a.QuestionID AND q.ExamID = $EID";

            foreach($conn->query($sql) as $row){
                $QID = $row["QuestionID"] ;
                $Question = $row["Question"];
                $MaxMarks = $row["Max_Mark"];
                $StudentMark = $row["Student_Mark"];
                $MaxScore += $MaxMarks;
                $StudentScore += $StudentMark;
                $sql2 ="SELECT * FROM Question q, MCQuestion m WHERE q.QuestionID = m.QuestionID AND q.QuestionID = $QID";
                $result = $conn->query($sql2);
                if($result->num_rows > 0){
                    $MC = $result->fetch_assoc();
                    $ANS = $MC["Answer"];
                    $sql3 = "SELECT * FROM Answer a, MCAnswer m WHERE a.AnswerID = m.AnswerID AND a.QuestionID = $QID AND a.StudentID = $ID";
                    $result3 = $conn->query($sql3);
                    $SMC = $result3->fetch_assoc();
                    $SANS = $SMC["Answer"];
                    
                    echo'
                    
                    <div class="card middle">
                    <div class="card-body">

                    <h5 class="card-title">'.$Question.'</h5>
                    <h4 class="card-title">Answer:</h4>
                    <p class="card-text">'.$ANS.'</p>
                    <h4 class="card-title">Your Answer:</h4>
                    <p class="card-text">'.$SANS.'</p>
                    <br/> <br/>
                    <p class="card-text"> '.$StudentMark.'/'.$MaxMarks.' </p>

                    </div>
                    </div>

                    ';
                }else{
                    $sql2 ="SELECT * FROM Question q, EssayQuestion e WHERE q.QuestionID = e.QuestionID AND q.QuestionID = $QID";
                    $result = $conn->query($sql2);
                    $MC = $result->fetch_assoc();
                    $ANS = $MC["Answer"];
                    $sql3 = "SELECT * FROM Answer a, EssayAnswer e WHERE a.AnswerID = e.AnswerID AND a.QuestionID = $QID AND a.StudentID = $ID";
                    $result3 = $conn->query($sql3);
                    $SMC = $result3->fetch_assoc();
                    $SANS = $SMC["Answer"];
                    $Marked = $SMC["Marked"];

                    if($Marked == "True"){
                        echo'
                    
                        <div class="card middle">
                        <div class="card-body">
    
                        <h5 class="card-title">'.$Question.'</h5>
                        <h4 class="card-title">Answer:</h4>
                        <p class="card-text">'.$ANS.'</p>
                        <h4 class="card-title">Your Answer:</h4>
                        <p class="card-text">'.$SANS.'</p>
                        <br/> <br/>
                        
                        <p class="card-text"> '.$StudentMark.'/'.$MaxMarks.' </p>
    
                        </div>
                        </div>
    
                        ';
                    }else{
                        echo'
                        
                        <div class="card middle">
                        <div class="card-body">
                        <h5 class="card-title">'.$Question.'</h5>
                        <h5 class="card-title">This question has not been marked yet</h5>
    
                        </div>
                        </div>';
                    }

                    
                }
            }
            
            ?>

                <div class="card middle">
                    <div class="card-body">
    
                    <h5 class="card-title">Total:</h5>
                    <p class="card-text"><?php echo "$StudentScore/$MaxScore"; ?> </p>
    
                    </div>
                </div>

                <form action="ExamResults.php">
                    <button type="submit" class="btn btn-primary">Go Back</button>
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
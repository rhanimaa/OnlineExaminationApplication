<?php 
    session_start();
    $Uusername = $_SESSION["username"];

    $servername = "dbprojects.eecs.qmul.ac.uk";
    $username = "as396";
    $password = "nPLVi52BQXSMS";
    $dbname = "as396";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }else{
        $sql = "SELECT * FROM UserInfo u, MarkerUser m 
        WHERE u.UserID = m.UserID AND '$Uusername' = u.Username;
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
            .left{ 
                margin-left: 2%;
            }
        </style>
        <title>Teacher Page</title>
    </head>
    <body>
        
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#"><?php echo $name; ?></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Mark Exams <span class="sr-only">(current)</span></a>
                    </li>
                </ul>
                <span class="navbar-text">
                    Marker ID : <?php echo $MarkerID ; ?>
                </span>
            </div>
            <form class="form-inline left" action="http://webprojects.eecs.qmul.ac.uk/as396/se19/Logout.php" method="POST">
                <button  class="btn btn-outline-success my-2 my-sm-0" type="submit">Logout</button>
            </form>
          </nav>


          <?php 

            $sql ="SELECT * FROM Question q, EssayQuestion e WHERE
            q.QuestionID = e.QuestionID AND e.MarkerID = $MarkerID";

            $result = $conn->query($sql);

            if ($result->num_rows > 0){
                foreach($result as $row){
                    $QID = $row["QuestionID"];
                    $ExamID = $row["ExamID"];
                    $sql3 = "SELECT * FROM Answer a , EssayAnswer e WHERE 
                    a.AnswerID = e.AnswerID AND a.QuestionID = $QID AND e.Marked = 'False';";
                    $result3 = $conn->query($sql3);
                    foreach($result3 as $row3){
                        $Question = $row["Question"];
                    
                        $sql2 = "SELECT * FROM Exam e, Question q WHERE e.ExamID = $ExamID AND e.ExamID = q.ExamID";
                        $result2 = $conn->query($sql2);
                        $row2 = $result2->fetch_assoc();
                        $ExamName = $row2["ExamName"];
                        $AnsID = $row3["AnswerID"];
                        $StudentID = $row3["StudentID"];

                        echo'<div class="card middle">
                            <form action="http://webprojects.eecs.qmul.ac.uk/as396/se19/MarkerMarkQuestion.php" method="POST">
                                <div class="card-header">
                                    '.$ExamName.'
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">Student : '.$StudentID.'</h5>
                                    <p class="card-text">'.$Question.'</p>
                                    <button type="submit" value="'.$AnsID.'" name = "AnsID" class="btn btn-primary">Mark Question</button>
                                </div>
                                </form>
                            </div>';
                        $Display = $Display + 1;
                    }

                }
            }
            if($Display == 0){
                echo '<div class="card middle">
                        <div class="card-header">
                            Whoops..
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Looks Like you have nothing to mark!</h5>
                            <p class="card-text">Come Back Later!</p>
                        </div>
                    </div>';
            }

            ?>

            
            
          

        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    </body>
</html>

<?php 
    $conn->close();
?>
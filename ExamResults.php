<?php 
    session_start();
    $Uusername = $_SESSION['username'];
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
        <title>Student Page</title>
    </head>
    <body>
        
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#"><?php echo $name; ?></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
              <div class="navbar-nav">
                <a class="nav-item nav-link " href="StudentLandingPage.php">Exams <span class="sr-only">(current)</span></a>
                <a class="nav-item nav-link" href="EnterExam.php">Enter Exam</a>
                <a class="nav-item nav-link active" href="ExamResults.php">Previous Results</a>
              </div>
            </div>
            <form class="form-inline" action="http://webprojects.eecs.qmul.ac.uk/as396/se19/Logout.php" method="POST">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Logout</button>
            </form>
          </nav>

            <?php 
                $Exams = array();
                $sql =$sql = "SELECT * FROM ExamToDo WHERE StudentID = $ID AND Done = 'True'";
                foreach( $conn->query($sql) as $row){
                    if(in_array($row["ExamID"],$Exams)){
                        //Do Nothing
                    }else{
                        array_push($Exams,$row["ExamID"] );
                    }
                }

                if(sizeof($Exams) == 0){
                    echo'<div class="card middle">
                    <div class="card-body">
                      You dont have any results yet!
                    </div>
                  </div>';
                }else{

                    foreach($Exams as $Exam){
                        $sql = "SELECT * FROM Exam WHERE ExamID = $Exam;";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
                        $EName = $row["ExamName"];
                        $ELength = $row["ExamLength"];
                        $EDate = $row["ExamDate"];

                        echo'
                        
                        <div class="card middle">
                            <div class="card-header">
                            '.$EName.'
                            </div>
                            <div class="card-body">
                                <form action="http://webprojects.eecs.qmul.ac.uk/as396/se19/ViewResults.php" method="POST">
                                <h5 class="card-title">Exam Date : '.$EDate.'</h5>
                                    <p class="card-text">This exam was '.$ELength.' min long</p> <br>
                                    
                                    <button  class="btn btn-primary" type = "submit" Value = "'.$Exam.'" name = "ExamID">View Results</button>
                                    
                                    ';
                                    
                                    
                                echo'</form>
                            </div>
                        </div>

                        ';

                    }
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
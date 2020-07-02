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
        <style>
            .middle {
              margin-left: 10%;
              margin-right: 10%;
              margin-top: 5%;
            }
            .buttonclass{
                background-color:#4d0000;
             }
             Button{
                 background-color:#4d0000;
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
                <a class="nav-item nav-link active" href="#">Exams <span class="sr-only">(current)</span></a>
                <a class="nav-item nav-link" href="EnterExam.php">Enter Exam</a>
                <a class="nav-item nav-link" href="ExamResults.php">Previous Results</a>
              </div>
            </div>
            <form class="form-inline" action="http://webprojects.eecs.qmul.ac.uk/as396/se19/Logout.php" method="POST">
                <button style="background-color:#4d0000;" class="buttonclass" type="submit">Logout</button>
                <!-- class="btn btn-outline-success my-2 my-sm-0" -->
            </form>
          </nav>

            <?php 
                $Exams = array();
                $sql ="SELECT * FROM ExamToDo WHERE StudentID = $ID AND Done = 'False'";
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
                      You dont have any exams yet!
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
                        $StartTime = $row["StartTime"];
                        $MinStay = $row["MinStay"];
                        $EndTime =  $row["EndTime"];
                        $STime = ((String)$StartTime[0]).((String)$StartTime[1]).":".((String)$StartTime[2]).((String)$StartTime[3]);
                        $addTime = (intdiv($ELength , 60) * 100) + ($ELength - (intdiv($ELength , 60) * 60));

                        echo'
                        
                        <div class="card middle">
                            <div class="card-header">
                            '.$EName.'
                            </div>
                            <div class="card-body">
                                <form action="http://webprojects.eecs.qmul.ac.uk/as396/se19/TakeExam.php" method="POST">
                                <h5 class="card-title">Exam Date : '.$EDate.'</h5>
                                    <p class="card-text">The exam will start at '.$STime.'. <br> It will last '.$ELength.' min 
                                    <br>You can not leave in the first '.$MinStay.' and you can not leave in the last '.$EndTime.'</p> <br>';
                                    if(date("d-m-Y") == $EDate){
                                        if((int)date("Hi") >= $StartTime){
                                            if((int)date("Hi")  <= $StartTime + $addTime){
                                                echo'<button  class="btn btn-primary" type = "submit" Value = "'.$Exam.'" name = "ExamID">Start Attempt</button>';
                                            }else{
                                                echo'Exam not open';
                                            }
                                        }else{
                                            echo'Exam not open';
                                        }
                                    }else{
                                        echo'Exam not open';
                                    }
                                    
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
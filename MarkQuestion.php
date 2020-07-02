<?php 
    session_start();
    $Uusername = $_SESSION["username"];
    $_SESSION["AnsID"] = $_POST["AnsID"];

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
        <title>Mark Question</title>
    </head>
    <body>
        
        
    <?php 
    
            $AnswerID = $_POST["AnsID"];
            $sql = "SELECT * FROM Answer a, EssayAnswer e WHERE
            a.AnswerID = e.AnswerID AND a.AnswerID = $AnswerID;" ;
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $SAnswer = $row["Answer"];
            $QuestionID = $row["QuestionID"];
            
            $sql = "SELECT * FROM Question q, EssayQuestion e WHERE
            e.QuestionID = q.QuestionID AND q.QuestionID = $QuestionID";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $Question = $row["Question"];
            $Marks = $row["Marks"];
            $_SESSION["MaxMark"] = $Marks;
            $MS = $row["Answer"];

            echo'
            <div class="card middle">
            <form action="http://webprojects.eecs.qmul.ac.uk/as396/se19/SaveMarking.php" method="POST">
                <div class="card-header">
                    '.$Question.'
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="staticEmail" class="col-sm-2 col-form-label">Mark Scheme:</label><br></br>
                        <div class="col-sm-10">
                            <p><font color="Blue"> '.$MS.' </font></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="staticEmail" class="col-sm-2 col-form-label">Student Answer:</label><br></br>
                        <div class="col-sm-10">
                        <p><font color="Blue"> '.$SAnswer.' </font></p><br>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Enter Marks Awarded:</label><br></br>
                        <input type="text" class="form-control col-md-1" id="Mark" name="Mark"><label for="exampleInputEmail1" class="col-md-4">/'.$Marks.'</label><br></br>
                        <small id="emailHelp" class="form-text text-muted">Please enter a value smaller than the max.</small>
                    </div>
                    <button type="submit" value="'.$AnsID.'" name = "AnsID" class="btn btn-primary">Mark Question</button>
                </div>
                </form>
            </div>
            ';


    ?>
            

            
            
          

        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    </body>
</html>

<?php 
    $conn->close();
?>
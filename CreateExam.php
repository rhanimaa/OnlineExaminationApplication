<?php 
    session_start();
    $Uusername = $_SESSION["username"];
    $Display = 0;

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
                    <li class="nav-item">
                        <a class="nav-link" href="TeacherLandingPage.php">Mark Exams <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Create Exam</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Search for Student</a>
                    </li>
                </ul>
                <span class="navbar-text">
                    Marker ID : <?php echo $MarkerID ; ?>     
                </span>
            </div>
            <form class="form-inline left" action="http://webprojects.eecs.qmul.ac.uk/as396/se19/Logout.php" method="POST">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Logout</button>
            </form>
          </nav>

          <div class="card middle">
            <div class="card-body">
                <form action="http://webprojects.eecs.qmul.ac.uk/as396/se19/GenerateExam.php" method="POST">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Exam Name</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" name="ExamName" >
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Exam Length</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" name="Length" >
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Exam Date</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" name="Date" >
                        <small id="emailHelp" class="form-text text-muted">Use the format "Day-Month-Year".</small>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Start Time</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" name="StartTime" >
                        <small id="emailHelp" class="form-text text-muted">Please enter a numerical value.(08:00 = 0800)</small>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Minimum Stay Time</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" name="StayTime" >
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">End Stay Time</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" name="EndTime" >
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Number of MCQ</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" name="MCQ" >
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Number of Essay Questions</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" name="Essay" >
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
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
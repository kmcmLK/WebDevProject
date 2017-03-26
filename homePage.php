<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <link href="https://fonts.googleapis.com/css?family=Lobster+Two:400,700|Roboto:400,700" rel="stylesheet">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link href="styles/ihover.css" rel="stylesheet">
  <title>UL Review</title>
</head>
<body id="body">
  <script src="js/functions.js"></script>
  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script> 
  <?php 
     session_start();
     if (!isset($_SESSION['username'])) {
       header("Location:/index.php");					
     }
  ?>
  <!-- Nav Bar -->
  <nav class="navbar navbar-default">
    <div class="container">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a href="#body"><img src="images/ULlogo-azul.png" alt="UL Review logo" style="width: 182px; height: 50px;"></a>
        <!-- <a class="navbar-brand" href="#">UL Review</a> -->
      </div>

      <ul class="nav navbar-nav navbar-right">
     <?php
        if($_SESSION['moderator'] == 1){
      ?>
         <ul class="nav navbar-nav navbar-right">
            <li><a href="flaggedTasks.php">Flagged Tasks</a></li>   
     <?php
            }
     ?>

       <!--   Dropdown Tasks -->
       <li class="dropdown btn-stickyNav">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Tasks <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="#myTasks">My tasks</a></li>
          <li role="separator" class="divider"></li>
          <li><a href="#createTask">Create Task</a></li>
          <li role="separator" class="divider"></li>
          <li><a href="#claimedTasks">Claimed Tasks</a></li>
          <li role="separator" class="divider"></li>
          <li><a href="#availableTasks">Available Tasks</a></li>
        </ul>
      </li>

      <!--     Search bar
      <form class="navbar-form navbar-left">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Search">
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
      </form> -->

      <!--   Dropdown Languages -->
      <li class="dropdown btn-stickyNav">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Language <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="#">Español</a></li>
          <li role="separator" class="divider"></li>
          <li><a href="#">Deutsche</a></li>
          <li role="separator" class="divider"></li>
          <li><a href="#">Gaeilge</a></li>
        </ul>
      </li>

      <ul class="nav navbar-nav btn-stickyNav">
        <li><a href="#LogOut">My rating</a></li>
      </ul>

       <ul class="nav navbar-nav navbar-right">
            <li><a href="logout.php">Log out</a></li>
       </ul>

    </ul>
  </div><!-- /.container -->
</nav>

<!-- Welcome panel -->
<div class="container">
  <!-- Tasks Bottons -->
  <div class="panel panel-default">
    <div class="panel-body">
      <div class="index col-xs-12 col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3 col-xl-6 col-xl-offset-3">
        <?php
            $username = $_SESSION['username'];
            printf("<h1>Welcome, %s!</h1>", $username);
           ?>
      </div>
      <div class="index col-xs-12 col-sm-6 col-sm-offset-3 col-md-9 col-md-offset-3 col-lg-9 col-lg-offset-3 col-xl-9 col-xl-offset-3">
        <p>Here you can add tasks, review your tasks, claim a task and view your settings.</p>
      </div>

      <div class=" col-xs-12 col-sm-6  col-md-3 col-lg-3  col-xl-3 ">
        <a href="#myTasks" type="button" class="btn btn-lg btn-TasksIndex"><div class="icon"><img src="images/circle-01.png" alt=""></div> <h2>My Tasks</h2></a>
      </div>


      <div class=" col-xs-12 col-sm-6 col-md-3 col-lg-3 col-xl-3">
        <a href="#createTask" type="button" class="btn btn-lg btn-TasksIndex"><div class="icon"><img src="images/circle-02.png" alt=""></div> <h2>Create Task</h2></a>
      </div>

      <div class=" col-xs-12 col-sm-6 col-md-3 col-lg-3 col-xl-3">
        <a href="#claimedTasks" type="button" class="btn btn-lg btn-TasksIndex"><div class="icon"><img src="images/circle-03.png" alt=""></div> <h2>Claimed Task</h2></a>
      </div>

      <div class=" col-xs-12 col-sm-6 col-md-3 col-lg-3 col-xl-3">
        <a href="#availableTasks" type="button" class="btn btn-lg btn-TasksIndex"><div class="icon"><img src="images/circle-04.png" alt=""></div> <h2>Available Tasks</h2></a>
      </div>
    </div> <!-- panel body -->
  </div> <!-- panel panel-default -->
</div> <!-- container -->


<!-- My tasks -->
<div class="container" id="myTasks">
  <div class="panel panel-info">
    <div class="panel-heading"><h2>My Tasks</h2></div>
    <div class="panel-body">
     <?php
            
        try{ 
            $dbh = new PDO("mysql:host=localhost;dbname=Project", "root", "");
		    $counter = 0;
            $username = $_SESSION['username'];          
            $stmt = $dbh->prepare("SELECT task_Id, title, flagged_count, type, page_no, word_Count, file_format, description, claim_deadline, submission_deadline, status_Id FROM tasks JOIN task_status USING(task_Id) WHERE username = ?");
            $stmt->execute(array($username));
            if($stmt->rowCount() == 0){
                printf("<h2 class='description-of-page'>You have not created any tasks yet.</h2>", $username); 
            }else{
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                   $taskID = $row['task_Id']; 
                   $title = $row['title'];              
                   $flagCount = $row['flagged_count'];
                   $type = $row['type'];
                   $pageNo = $row['page_no'];
                   $wordCount = $row['word_Count'];
                   $fileFormat = $row['file_format'];
                   $description = $row['description'];
                   $claimDeadline = $row['claim_deadline'];
                   $submissionDeadline = $row['submission_deadline'];
                   $status = $row['status_Id'];
                   $targetIdentifier = "#myModel";
                   $target = "myModel";
                   $buttonIdentifier = "button";
                   $buttonID = $buttonIdentifier.$counter;
                   $targetID  = $targetIdentifier.$counter;               
                   $target = $target.$counter;
                
                    
                    //need to add code for submit rating
                    
                   //This switch works off the ststud id's and are a s follows
                   //1 - Pending Claim
                   //2 - Claimed
                   //3 - Expired
                   //4 - Cancelled By Claiment
                   //5 - Completed
                   switch($status){                    
                     case "1":
                        //Pending Claim
                        printf('<button type= %s class="btn btn-MyTasksPending btn-lg" data-toggle="modal" data-target= %s >Title: %s </br> Status: Pending Claim</br> Date: %s</button>

                        <!-- Modal -->
                        <div class="modal fade" id= %s role="dialog">
                            <div class="modal-dialog">
                            <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type= %s class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title title">Title: %s</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="type">
                                           Type: %s
                                        </div>
                                        <div class="tags">
                                            Tags: Need to work on this
                                        </div>
                                        <div class="no-of-pages">
                                            No of pages: %s
                                        </div>
                                        <div class="no-of-words">
                                            No of word: %s
                                        </div>
                                        <div class="file-Format">
                                            File Format: %s
                                        </div>
                                        <div class="description">
                                            Description: %s
                                        </div>
                                        <div class="claimed-deadline">
                                            Claim Deadline: %s
                                        </div>
                                        <div class="completion-deadline">
                                            Completion Deadline: %s
                                        </div>
                                        <div class="modal-footer">
                                           <form method="post">
                                              <button type="submit" class="btn btn-default" name="delete" value= %s>Delete</button>
                                           </form>
                                           <p>Status: Pending Claim</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- finish modal -->', $buttonID, $targetID, $title, $claimDeadline, $target, $buttonID, $title, $type, $pageNo, $wordCount, $fileFormat, $description, $claimDeadline, $submissionDeadline, $taskID);
                     break;
                     
                     case "2":
                        //Claimed
                        printf('<button type= %s class="btn btn-MyTasksClaimed btn-lg" data-toggle="modal" data-target= %s >Title: %s</br> Status: Claimed </br> Date: %s</button>

                        <!-- Modal -->
                        <div class="modal fade" id= %s role="dialog">
                            <div class="modal-dialog">
                            <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type= %s class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title title">Title: %s</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="type">
                                           Type: %s
                                        </div>
                                        <div class="tags">
                                            Tags: Need to work on this
                                        </div>
                                        <div class="no-of-pages">
                                            No of pages: %s
                                        </div>
                                        <div class="no-of-words">
                                            No of word: %s
                                        </div>
                                        <div class="file-Format">
                                            File Format: %s
                                        </div>
                                        <div class="description">
                                            Description: %s
                                        </div>
                                        <div class="claimed-deadline">
                                            Claim Deadline: %s
                                        </div>
                                        <div class="completion-deadline">
                                            Completion Deadline: %s
                                        </div>
                                        <div class="modal-footer">
                                           <form method="post">
                                              <button type="submit" class="btn btn-default" name="cancel" value= %s>Cancel</button>
                                           </form>
                                           <p>Status: Claimed</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- finish modal -->', $buttonID, $targetID, $title, $claimDeadline, $target, $buttonID, $title, $type, $pageNo, $wordCount, $fileFormat, $description, $claimDeadline, $submissionDeadline, $taskID);
                     break;
                         
                     case "3":
                        //Expired Claim - may need to get different deadline
                        printf('<button type= %s class="btn btn-MyTasksExpired btn-lg" data-toggle="modal" data-target= %s >Title: %s</br> Status: Expired </br> Date: %s</button>
                        <!-- Modal -->
                        <div class="modal fade" id= %s role="dialog">
                            <div class="modal-dialog">
                            <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type= %s class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title title">Title: %s</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="type">
                                           Type: %s
                                        </div>
                                        <div class="tags">
                                            Tags: Need to work on this
                                        </div>
                                        <div class="no-of-pages">
                                            No of pages: %s
                                        </div>
                                        <div class="no-of-words">
                                            No of word: %s
                                        </div>
                                        <div class="file-Format">
                                            File Format: %s
                                        </div>
                                        <div class="description">
                                            Description: %s
                                        </div>
                                        <div class="claimed-deadline">
                                            Claim Deadline: %s
                                        </div>
                                        <div class="completion-deadline">
                                            Completion Deadline: %s
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <form method="post">
                                           <button type="submit" class="btn btn-default" name="delete" value= %s>Delete</button>
                                           <button type="submit" class="btn btn-default" name="reupload" value= %s>Re-Upload</button>
                                        </form>
                                        <p>Status: Expired</p>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- finish modal -->', $buttonID, $targetID, $title, $claimDeadline, $target, $buttonID, $title, $type, $pageNo, $wordCount, $fileFormat, $description, $claimDeadline, $submissionDeadline, $taskID, $taskID);
                     break;
                         
                     case "4":
                        //Cancelled Claim
                        printf('<button type= %s class="btn btn-MyTasksCancelled btn-lg" data-toggle="modal" data-target= %s >Title: %s</br> Status: Cancelled By Claiment </br> Date: %s</button>
                        <!-- Modal -->
                        <div class="modal fade" id= %s role="dialog">
                            <div class="modal-dialog">
                            <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type= %s class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title title">Title: %s</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="type">
                                           Type: %s
                                        </div>
                                        <div class="tags">
                                            Tags: Need to work on this
                                        </div>
                                        <div class="no-of-pages">
                                            No of pages: %s
                                        </div>
                                        <div class="no-of-words">
                                            No of word: %s
                                        </div>
                                        <div class="file-Format">
                                            File Format: %s
                                        </div>
                                        <div class="description">
                                            Description: %s
                                        </div>
                                        <div class="claimed-deadline">
                                            Claim Deadline: %s
                                        </div>
                                        <div class="completion-deadline">
                                            Completion Deadline: %s
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <form method="post">
                                           <button type="submit" class="btn btn-default" name="delete" value= %s>Delete</button>
                                           <button type="submit" class="btn btn-default" name="reupload" value= %s>Re-Upload</button>
                                        </form>
                                        <p>Status: Cancelled by Claiment</p>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- finish modal -->', $buttonID, $targetID, $title, $claimDeadline, $target, $buttonID, $title, $type, $pageNo, $wordCount, $fileFormat, $description, $claimDeadline, $submissionDeadline, $taskID, $taskID);
                     break;
                         
                     case "5":
                        //Completed
                        printf('<button type= %s class="btn btn-MyTasksCompleted btn-lg" data-toggle="modal" data-target= %s >Title: %s </br> Status: Completed </br> Date: %s</button>

                        <div class="modal fade" id= %s role="dialog">
                             <div class="modal-dialog">

                             <!-- Modal content-->
                               <div class="modal-content">
                                 <div class="modal-header">
                                   <button type= %s class="close" data-dismiss="modal">&times;</button>
                                   <h4 class="modal-title">%s</h4>
                                 </div>
                                 <div class="modal-body">
                                   <button type="submit" class="btn btn-lg btn-info">
                                   <img src="images/happy.jpg" alt="submit" width="120px" height="120px"> </button>
                                   <button type="submit" class="btn btn-lg btn-info">
                                   <img src="images/neutral.jpg" alt="submit" width="120px" height="120px"> </button>
                                   <button type="submit" class="btn btn-lg btn-info">
                                   <img src="images/sad.jpg" alt="submit" width="120px" height="120px"> </button>
                                 </div>
                                 <div class="modal-footer">
                                    <p>Status: Completed</p>
                                 </div>
                              </div>
                            </div>
                         </div>', $buttonID, $targetID, $title, $deadline, $target, $buttonID, $title);
                    }
                  $counter++;
                }
             }
           } catch (PDOException $exception) {
                printf("Connection error: %s", $exception->getMessage());
          }
    
          if(isset($_POST['cancel'])){         
            $taskID = $_POST['cancel'];
            $stmt = $dbh->prepare("DELETE FROM claimed_tasks WHERE task_Id = ?");
            $stmt->execute(array($taskID));
            $stmt = $dbh->prepare("UPDATE task_status SET status_Id = 1 WHERE task_Id = ?");
            $stmt->execute(array($taskID));
          }else if(isset($_POST['delete'])){         
            $taskID = $_POST['delete'];
            $stmt = $dbh->prepare("DELETE FROM tasks WHERE task_Id = ?");
            $stmt->execute(array($taskID));
            $stmt = $dbh->prepare("DELETE FROM task_status WHERE task_Id = ?");
            $stmt->execute(array($taskID));
            $stmt = $dbh->prepare("DELETE FROM assigned_tags WHERE task_Id = ?");
            $stmt->execute(array($taskID));
          } 
    ?>
    </div> <!-- panel-body -->
  </div> <!-- panel panel-default -->
</div> <!-- container -->

<!-- Create task -->
<div class="container" id="createTask">
  <div class="panel panel-info">
    <div class="panel-heading"><h2>Create Task</h2></div>
    <div class="panel-body">
      <h4>Create a task to get a peer to review it.</h4>
    </br>
      <?php
        if (isset($_POST['createTaskSubmit'])) {
           $title = htmlspecialchars(ucfirst(trim($_POST["title"])));
           $type = htmlspecialchars(ucfirst(trim($_POST["type"])));
           $tags = htmlspecialchars(ucfirst(trim($_POST["tags"])));
           $pageNum = htmlspecialchars(trim($_POST["pageNum"]));
           $wordNum = htmlspecialchars(trim($_POST["wordNum"]));
           $fileFormat = htmlspecialchars(trim($_POST["fileFormat"]));
           $description = htmlspecialchars(trim($_POST["description"]));
	       $claimDeadline = str_ireplace("/","-",$_POST["claimDeadline"]);
           $submissionDeadline = str_ireplace("/","-",$_POST["submissionDeadline"]);
           $username = $_SESSION['username'];
            
           $tagArray = explode(",", $tags);
           try{
	         $dbh = new PDO("mysql:host=localhost;dbname=Project", "root", "");
             $query = "INSERT INTO tasks VALUES(:username, NULL, :title, :type, :pageNum, :wordCount, :fileFormat, :description, :claimDeadline, :submissionDeadline, 0)";
             $stmt = $dbh->prepare($query);
             $affectedRows = $stmt->execute(array(':username' => $username, ':title' => $title, ':type' => $type, ':pageNum' => $pageNum, ':wordCount' => $wordNum, ':fileFormat' => $fileFormat, ':description' => $description, ':claimDeadline' => $claimDeadline, ':submissionDeadline' => $submissionDeadline));
               
             $query = "SELECT task_Id FROM tasks WHERE username = :username AND title = :title";
             $stmt = $dbh->prepare($query);
             $stmt->execute(array(':username' => $username, ':title' => $title));
             $taskID = $stmt->fetchColumn(0);
             
             $stmt = $dbh->prepare("INSERT INTO task_status VALUES (?, 1)");
             $stmt->execute(array($taskID));
             
             foreach($tagArray as $tag){
                $tag = htmlspecialchars(trim($tag));
                $stmt = $dbh->prepare("SELECT COUNT(*) FROM tag_ids WHERE tag_Name = ?" );
                $stmt->execute(array($tag));
                if ($stmt->fetchColumn(0) == 0 ){
                   $stmt = $dbh->prepare("INSERT INTO tag_ids (tag_Name, tag_Id) VALUES (?, NULL)");
                   $stmt->execute(array($tag));
                }
                $stmt = $dbh->prepare("SELECT tag_Id FROM tag_ids WHERE tag_Name = ?");
                $stmt->execute(array($tag));
                $tagID = $stmt->fetchColumn(0);
                $stmt = $dbh->prepare("INSERT INTO user_tags VALUES (:tagID, :username)");
                $affectedRows = $stmt->execute(array(':tagID' => $tagID, ':username' => $username));
                if($affectedRows == 0){
                    printf("<h2>ERROR</h2>");
                }else{
                    //header("Refresh:0");
                    //need a way to refresh the page so that the new task will appear in my tasks straight away
                }        
             }
           }catch(PDOException $exception){
              print("<h2> Uh Oh1</h2>"); 
           }            
        }
      ?>   
    <form method="post">
      <div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
        <label for="title">Title</label>
        <input class=" form-control" type="text" id="title" name="title"  placeholder="World War II">
      </div>

      <div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
        <label for="type">Type</label>
        <input class=" form-control" type="text" id="Type" name="type" placeholder="FYP, essay, notes">
      </div>

      <div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
        <label for="Tags">Tags</label>
        <input class=" form-control" data-role="tagsinput" type="text" id="Tags" name="tags"  placeholder="Amsterdam,Washington,Sydney,Beijing,Cairo">
      </div>

      <div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
        <label for="no-of-pages">No of pages</label>
        <input class=" form-control" type="number" id="No-of-pages" minlength="1" maxlength="50" name="pageNum" placeholder="20">
      </div>

      <div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
        <label for="no-of-words">No of words</label>
        <input class=" form-control" type="number" id="No-of-words" minlength="100" maxlength="15000" name="wordNum" placeholder="12456">
      </div>

      <div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
        <label for="file-Format">File Format</label>
        <select class="form-control" id="sel2" name="fileFormat" >
          <option>.doc</option>
          <option>.docx</option>
          <option>.pdf</option>
          <option>.ppt</option>
          <option>.pptx</option>
          <option>.txt</option>
        </select>
      </div>

      <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <label for="description">Description</label>
        <input class=" form-control" type="text" maxlength="500" id="Description" name="description"  placeholder="Max 100 words">
      </div>

      <script>
          $(function() {
             $( "#datepicker" ).datepicker();
          });
          function show_dp(){
             $( "#datepicker" ).datepicker('show'); //Show on click of button
          }
        </script>
              
        <div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
          <label for="re-password">Claimed deadline</label>
             <div>
                 <p> Date: <input type="text" id="datepicker" name="claimDeadline" ></p>              
             </div>
        </div>

        <div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
           <label for="re-password">Completion deadline</label>
           <div>
              <p> Date: <input type="text" id="datepicker" name="submissionDeadline" ></p>  
           </div>
         </div>
         <br>
         <button class="btn btn-primary center-block" type="submit" name="createTaskSubmit">Create</button>
         <br>
         <br>
     </form>
  </div> <!-- panel-body -->
</div> <!-- panel panel-default -->
</div> <!-- container -->

<!-- Claimed tasks -->
<div class="container" id="claimedTasks">
  <div class="panel panel-info">
    <div class="panel-heading"><h2>Claimed Tasks</h2></div>
    <div class="panel-body">
      <?php
        try {
            $dbh = new PDO("mysql:host=localhost;dbname=Project", "root", "");
		    $counter = 0;
            $username = $_SESSION['username'];
            $stmt = $dbh->prepare("SELECT task_Id, status_Id, title, type, page_no, word_Count, file_format, description, claim_deadline, submission_deadline FROM tasks JOIN task_status USING(task_Id) JOIN claimed_tasks USING(task_Id) WHERE claimed_tasks.username = ?");
            $stmt->execute(array($username));
            if($stmt->rowCount() == 0){
                printf("<h2 class='description-of-page'> You have not claimed any tasks. </h2>", $username); 
            }else{ 
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $taskID = $row['task_Id']; 
                $status = $row['status_Id']; 
                $title = $row['title'];              
                $type = $row['type'];
                $pageNo = $row['page_no'];
                $wordCount = $row['word_Count'];
                $fileFormat = $row['file_format'];
                $description = $row['description'];
                $claimDeadline = $row['claim_deadline'];
                $submissionDeadline = $row['submission_deadline'];
                $targetIdentifier = "#myModelClaimed";
                $target = "myModelClaimed";
                $buttonIdentifier = "button";
                $buttonID = $buttonIdentifier.$counter;
                $targetID  = $targetIdentifier.$counter;               
                $target = $target.$counter;
                
                //this switch works off the status id's and are as follows
                //2 - Claimed ( awaiting completion)
                //3 - Expired
                //5 - Completed
                switch($status){
                    case "2":
                       printf('<button type= %s class="btn btn-MyTasksClaimed btn-lg" data-toggle="modal" data-target= %s >Title: %s </br> Status: Claimed </br> Date: %s</button>
                               <!-- Modal -->
                               <div class="modal fade" id= %s role="dialog">
                                  <div class="modal-dialog">
                                  <!-- Modal content-->
                                     <div class="modal-content">
                                        <div class="modal-header">
                                           <button type= %s class="close" data-dismiss="modal">&times;</button>
                                           <h4 class="modal-title title">Title: %s</h4>
                                        </div>
                                        <div class="modal-body">
                                           <div class="type">
                                              Type: %s
                                           </div>
                                           <div class="tags">
                                              Tags: Need to work on this
                                           </div>
                                           <div class="no-of-pages">
                                              No of pages: %s
                                           </div>
                                           <div class="no-of-words">
                                              No of word: %s
                                           </div>
                                           <div class="file-Format">
                                              File Format: %s
                                           </div>
                                           <div class="description">
                                              Description: %s
                                           </div>
                                           <div class="claimed-deadline">
                                              Claim Deadline: %s
                                           </div>
                                           <div class="completion-deadline">
                                              Completion Deadline: %s
                                           </div>
                                        </div>
                                        <div class="modal-footer">
                                           <form method="post">
                                              <button type="submit" class="btn btn-default" name="cancel" value= %s>Cancel</button>
                                              <button type="submit" class="btn btn-default" name="complete" value= %s>Complete</button>
                                           </form>
                                           <p>Status: Claimed</p>
                                       </div>
                                    </div>
                                </div>
                             </div> <!-- finish modal -->', $buttonID, $targetID, $title, $claimDeadline, $target, $buttonID, $title, $type, $pageNo, $wordCount, $fileFormat, $description, $claimDeadline, $submissionDeadline, $taskID, $taskID);
                    break;
                    
                    case"3":
                        printf('<button type= %s class="btn btn-MyTasksExpired btn-lg" data-toggle="modal" data-target= %s >Title: %s </br> Status: Expired </br> Date: %s </button>

                               <!-- Modal -->
                               <div class="modal fade" id= %s role="dialog">
                                  <div class="modal-dialog">
                                     <!-- Modal content-->
                                     <div class="modal-content">
                                        <div class="modal-header">
                                           <button type= %s class="close" data-dismiss="modal">&times;</button>
                                           <h4 class="modal-title title">Title: %s</h4>
                                        </div>
                                        <div class="modal-body">
                                           <div class="type">
                                              Type: %s
                                           </div>
                                           <div class="tags">
                                              Tags: Need to work on this
                                           </div>
                                           <div class="no-of-pages">
                                              No of pages: %s
                                           </div>
                                           <div class="no-of-words">
                                              No of word: %s
                                           </div>
                                           <div class="file-Format">
                                              File Format: %s
                                           </div>
                                           <div class="description">
                                              Description: %s
                                           </div>
                                           <div class="claimed-deadline">
                                              Claim Deadline: %s
                                           </div>
                                           <div class="completion-deadline">
                                              Completion Deadline: %s
                                           </div>
                                       </div>
                                    </div>
                                  </div>
                               </div> <!-- finish modal -->', $buttonID, $targetID, $title, $claimDeadline, $target, $buttonID, $title, $type, $pageNo, $wordCount, $fileFormat, $description, $claimDeadline, $submissionDeadline);
                    break;
                        
                    case"5":
                         printf('<button type= %s class="btn btn-MyTasksCompleted btn-lg" data-toggle="modal" data-target= %s >Title: %s </br> Status: Completed </br> Date: %s </button>

                               <div class="modal fade" id= %s role="dialog">
                                  <div class="modal-dialog">

                                  <!-- Modal content-->
                                      <div class="modal-content">
                                         <div class="modal-header">
                                            <button type= %s class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Rate this review</h4>
                                         </div>
                                         <div class="modal-body">
                                            <button type="submit" class="btn btn-lg btn-info">
                                            <img src="images/happy.jpg" alt="submit" width="120px" height="120px"> </button>
                                            <button type="submit" class="btn btn-lg btn-info">
                                            <img src="images/neutral.jpg" alt="submit" width="120px" height="120px"> </button>
                                            <button type="submit" class="btn btn-lg btn-info">
                                            <img src="images/sad.jpg" alt="submit" width="120px" height="120px"> </button>
                                         </div>
                                         <div class="modal-footer">
                                           <p>Status: Claimed</p>
                                       </div>
                                       </div>
                                    </div>
                                </div>', $buttonID, $targetID, $title, $submissionDeadline, $target, $buttonID);
                    break;
                }
                $counter++;
             }
          }
        }catch(PDOException $exception){
            printf("Connection error: %s", $exception->getMessage());
        }
    
    if(isset($_POST['cancel'])){         
            $taskID = $_POST['cancel'];
            $stmt = $dbh->prepare("DELETE FROM claimed_tasks WHERE task_Id = ?");
            $stmt->execute(array($taskID));
            $stmt = $dbh->prepare("UPDATE task_status SET status_Id = 1 WHERE task_Id = ?");
            $stmt->execute(array($taskID));
    }else if(isset($_POST['complete'])){         
            $taskID = $_POST['complete'];
            $stmt = $dbh->prepare("UPDATE task_status SET status_Id = 5 WHERE task_Id = ?");
            $stmt->execute(array($taskID));
    } 
?>

    </div> <!-- panel-body -->
  </div> <!-- panel panel-default -->
</div> <!-- container -->

<!-- Available tasks -->
<div class="container" id="availableTasks">
  <div class="panel panel-info">
    <div class="panel-heading"><h2>Available Tasks</h2></div>

    <!-- Panel body -->
    <div class="panel-body">
      <?php try {
            $dbh = new PDO("mysql:host=localhost;dbname=Project", "root", "");
		    $counter = 0;
            $stmt = $dbh->prepare("SELECT DISTINCT(task_Id), title, type, page_no, word_Count, file_format, description, claim_deadline, submission_deadline FROM tasks JOIN task_status USING(task_Id) JOIN assigned_tags USING(task_Id) WHERE username != ? AND status_Id = 1");
            $stmt->execute(array($username));
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $taskID = $row['task_Id'];  
                $title = $row['title'];              
                $type = $row['type'];
                $pageNo = $row['page_no'];
                $wordCount = $row['word_Count'];
                $fileFormat = $row['file_format'];
                $description = $row['description'];
                $claimDeadline = $row['claim_deadline'];
                $submissionDeadline = $row['submission_deadline'];
                $targetIdentifier = "#myModelAvailable";
                $target = "myModelAvailable";
                $buttonIdentifier = "button";
                $buttonID = $buttonIdentifier.$counter;
                $targetID  = $targetIdentifier.$counter;               
                $target = $target.$counter;
                printf('<button type= %s class="btn btn-MyTasksAvailable btn-lg" data-toggle="modal" data-target= %s>Title: %s</br> Status: Available </br> Date: %s</button>

                        <!-- Modal -->
                        <div class="modal fade" id= %s role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type= %s class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title title">Title: %s</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="type">
                                            Type: %s
                                        </div>
                                        <div class="tags">
                                            Tags: Need to work on this
                                        </div>
                                        <div class="no-of-pages">
                                            No of pages: %s
                                        </div>
                                        <div class="no-of-words">
                                            No of word: %s
                                        </div>
                                        <div class="file-Format">
                                            File Format: %s
                                        </div>
                                        <div class="description">
                                            Description: %s
                                        </div>
                                        <div class="claimed-deadline">
                                            Claim Deadline: %s
                                        </div>
                                        <div class="completion-deadline">
                                            Completion Deadline: %s
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <form method="post">
                                            <button type="submit" class="btn btn-default" name="claim" value="%s">Claim</button>
                                            <button type="submit" class="btn btn-default" name="flag" value="%s">Flag</button>
                                        </form>
                                        <p>Status: Available</p>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- finish modal -->', $buttonID, $targetID, $title, $claimDeadline, $target, $buttonID, $title, $type, $pageNo, $wordCount, $fileFormat, $description, $claimDeadline, $submissionDeadline, $taskID, $taskID);
                $counter++;
            }
        }catch(PDOException $exception){
             printf("Connection error: %s", $exception->getMessage());       
        }
        if(isset($_POST['claim'])){         
           $taskID = $_POST['claim'];
           $stmt = $dbh->prepare("UPDATE task_status SET status_Id = 2 WHERE task_Id = ?");
           $stmt->execute(array($taskID));
           $stmt = $dbh->prepare("INSERT INTO claimed_tasks VALUES(:taskID, 2017-00-00, :username)");
           $stmt->execute(array(':taskID' => $taskID, ':username' => $username));
        }else if(isset($_POST['flag'])){
            
           $taskID = $_POST['flag'];
           $stmt = $dbh->prepare("SELECT flagged_count FROM tasks WHERE task_Id = ?");
           $stmt->execute(array($taskID));
           $flaggedCount = $stmt->fetchColumn(0) + 1;
             
           $stmt = $dbh->prepare("UPDATE tasks SET flagged_count = :flaggedCount WHERE task_Id = :taskID");
           $stmt->execute(array(':flaggedCount' => $flaggedCount, ':taskID' => $taskID));
        }
?>
    </div> <!-- panel-body -->
  </div> <!-- panel panel-default -->
</div> <!-- container -->

</div>
<script src="js.functions.js"></script>
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
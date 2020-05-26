<?php
   // Include the init config file
   include("functions/init.php");

   // Redirect if not logged in
   if(!logged_in()){
   header("Location:login.php");
   }

   // Redirect if variables not present
   if(!$email){
   header("Location:logout.php");
   }

   include "functions/home.php";
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>ToDo App</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link href="assets/css/app.min.css" rel="stylesheet">
      <link href="https://pro.fontawesome.com/releases/v5.13.0/css/all.css" rel="stylesheet">
   </head>
   <body>
      <div id="app" class="app">
         <?php include "includes/header.php"; ?>
         <div id="content" class="app-content">
           <?php if(!isset($_GET["completed"])) { ?>
            <h1 class="page-header mb-3">
             Hello <?php echo $first_name ?> 👋<br>
             <small>What will you accomplish today?</small>
            </h1>
          <?php } ?>

            <div class="row mt-4">
              <?php if(!isset($_GET["completed"])) { ?>
               <div class="col-md-12">
                  <div class="card mb-4">
                     <div class="card-body">
                        <form method="post">
                           <div class="row">
                              <div class="col-md-6">
                                 <input type="text" name="name" class="form-control" placeholder="What would you like to accomplish?" required>
                              </div>
                              <div class="col-md-3">
                                <?php if(isset($_GET["label"])) { ?>
                                  <select name="label" class="custom-select">
                                     <option value="0">All Tasks</option>
                                     <?php
                                        $conn = mysqli_connect($host, $username, $password, $dbname);
                                        $sql = "SELECT * FROM labels WHERE user='$userId'";
                                        $result = mysqli_query($conn, $sql);

                                        if (mysqli_num_rows($result) > 0) {
                                          while($row = mysqli_fetch_assoc($result)) { ?>
                                     <option value="<?php echo $row["id"]; ?>" <?php if($_GET["label"] == $row["id"]) { echo "selected"; } ?>><?php echo $row["name"]; ?></option>
                                     <?php }
                                        }

                                         ?>
                                  </select>
                                <?php } else { ?>
                                 <select name="label" class="custom-select">
                                    <option value="0">All Tasks</option>
                                    <?php
                                       $conn = mysqli_connect($host, $username, $password, $dbname);
                                       $sql = "SELECT * FROM labels WHERE user='$userId'";
                                       $result = mysqli_query($conn, $sql);

                                       if (mysqli_num_rows($result) > 0) {
                                         while($row = mysqli_fetch_assoc($result)) { ?>
                                    <option value="<?php echo $row["id"]; ?>"><?php echo $row["name"]; ?></option>
                                    <?php }
                                  } }

                                        ?>
                                 </select>
                              </div>
                              <div class="col-md-3">
                                 <button type="submit" name="createTask" value="1" class="btn btn-primary w-100">Add task</button>
                              </div>
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
             <?php } ?>
               <div class="col-md-9">
                  <div class="card">
                    <?php if(isset($_GET["label"])) { ?>

                      <?php
                         $conn = mysqli_connect($host, $username, $password, $dbname);
                         $sql = "SELECT * FROM labels WHERE id='$_GET[label]' AND user='$userId'";
                         $result = mysqli_query($conn, $sql);
                         if (!mysqli_num_rows($result) > 0) { header("Location:index");
                         } else {
                         while($row = mysqli_fetch_assoc($result)) {
                           $labelName = $row["name"];
                          }
                          mysqli_close($conn);
                        }
                      ?>

                      <div class="card-body">
                         <h5 class="mb-3 d-inline"><i class="far fa-tag mr-2"></i> <?php echo $labelName ?> </h5>

                         <div class="table-responsive mt-3">
                            <table class="table table-borderless mb-0">
                               <tbody>
                                  <?php
                                     $conn = mysqli_connect($host, $username, $password, $dbname);
                                     $sql = "SELECT * FROM tasks WHERE label='$_GET[label]' AND userId='$userId' AND status='' ORDER BY id DESC";
                                     $result = mysqli_query($conn, $sql);

                                     if (mysqli_num_rows($result) > 0) {
                                       while($row = mysqli_fetch_assoc($result)) { ?>
                                  <tr>
                                     <td style="width:5%">
                                        <form method="post">
                                           <div class="custom-control custom-checkbox custom-control-inline">
                                              <input class="custom-control-input" type="checkbox" name="checkbox" value="<?php echo date("mdY"); ?>" onchange="this.form.submit()" id="customCheck<?php echo $row["id"]; ?>" <?php if($row["status"]) { echo "checked"; } ?>>
                                              <label class="custom-control-label" for="customCheck<?php echo $row["id"]; ?>"></label>
                                           </div>
                                           <input type="hidden" name="taskStatus" value="1">
                                           <input type="hidden" name="task" value="<?php echo $row["id"]; ?>">
                                        </form>
                                     <td>
                                        <div class="d-flex align-items-center">
                                           <div>
                                              <h6 class="font-weight-500 text-dark mb-0"><?php echo $row["name"]; ?></h6>
                                              <?php
                                                 $sql1 = "SELECT * FROM labels WHERE id=$row[label]";
                                                 $result1 = mysqli_query($conn, $sql1);

                                                 if (mysqli_num_rows($result1) > 0) {
                                                     while($row1 = mysqli_fetch_assoc($result1)) { ?>
                                              <a href="?label=<?php echo $row1["id"]; ?>" class="badge text-gray-dark pl-0"><i class="fas fa-circle mr-1" style="color:<?php echo $row1["color"]; ?>"></i> <?php echo $row1["name"]; ?></a>
                                              <?php }
                                                 } else { ?>
                                              <div class="badge text-gray-dark pl-0"><i class="fas fa-circle text-secondary mr-1"></i> All tasks</div>
                                              <?php } ?>
                                           </div>
                                        </div>
                                     </td>
                                     <td class="float-right">
                                        <div class="row">
                                           <a href="#" data-toggle="modal" data-target="#modal<?php echo $row["id"]; ?>" class="btn btn-light btn-sm mr-2"><i class="fas fa-pencil mr-2"></i> Edit</a>
                                        </div>
                         </div>
                         </tr>
                         <div class="modal fade" id="modal<?php echo $row["id"]; ?>">
                         <div class="modal-dialog">
                         <div class="modal-content">
                         <div class="modal-header">
                         <h5 class="modal-title"><i class="far fa-pencil mr-2"></i> Edit Task</h5>
                         <button type="button" class="close" data-dismiss="modal">
                         <span>×</span>
                         </button>
                         </div>
                         <div class="modal-body">
                         <form method="post">
                         <div class="form-group">
                         <input type="text" name="name" class="form-control" value="<?php echo $row["name"]; ?>" required>
                         </div>
                         <div class="form-group">
                         <select name="label" class="custom-select">
                         <option value="0">All Tasks</option>
                         <?php
                            $sql1 = "SELECT * FROM labels WHERE user='$userId'";
                            $result1 = mysqli_query($conn, $sql1);

                            if (mysqli_num_rows($result1) > 0) {
                              while($row1 = mysqli_fetch_assoc($result1)) { ?>
                         <option value="<?php echo $row1["id"]; ?>" <?php if($row1["id"] == $row["label"]) { echo "selected"; } ?>><?php echo $row1["name"]; ?></option>
                         <?php }
                            }

                             ?>
                         </select>
                         </div>
                         <div class="row">
                         <div class="col-md-6">
                         <div class="form-group">
                         <input type="hidden" name="task" value="<?php echo $row["id"]; ?>">
                         <button type="submit" name="editTask" value="1" class="btn btn-gray-400 text-white btn-block"><i class="far fa-save mr-2"></i> Save</button>
                         </div>
                         </div>
                         </form>
                         <div class="col-md-6">
                         <div class="form-group">
                         <form method="post" onsubmit="return confirm('Are you sure you\'d like to delete this task?');">
                         <input type="hidden" name="taskId" value="<?php echo $row["id"]; ?>">
                         <button type="submit" name="deleteTask" value="1" class="btn btn-danger btn-block"><i class="fas fa-trash mr-2"></i> Delete</button>
                         </form>
                         </div>
                         </div>
                         </div>
                         </div>
                         </div>
                         </div>
                         <?php }
                            } else { ?>
                         <div class="text-center">
                         <i class="fas fa-check text-success fa-3x mt-3"></i>
                         <h5 class='text-muted text-center mt-4 mb-4'>Looks like you're all caught up</h5>
                           <form method="post" onsubmit="return confirm('Are you sure you\'d like to delete this label? All tasks in this label will also be deleted.');">
                           <input type="hidden" name="labelId" value="<?php echo $_GET["label"]; ?>">
                           <button type="submit" name="deleteLabel" value="1" class="btn btn-gray-600 w-25 btn-sm mb-4"><i class="fas fa-trash mr-2"></i> Delete label</button>
                           </form>
                         </div>
                         <?php }
                            ?>
                         </tbody>
                         </table>
                         </div>
                      </div>

                    <?php } if(isset($_GET["completed"])) { ?>

                      <div class="card-body">
                         <h5 class="mb-3 text-success"><i class="far fa-check mr-2"></i> Completed </h5>
                         <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                               <tbody>
                                  <?php
                                     $conn = mysqli_connect($host, $username, $password, $dbname);
                                     $sql = "SELECT * FROM tasks WHERE userId='$userId' AND status!='' ORDER BY id DESC";
                                     $result = mysqli_query($conn, $sql);

                                     if (mysqli_num_rows($result) > 0) {
                                       while($row = mysqli_fetch_assoc($result)) { ?>
                                  <tr>
                                     <td style="width:5%">
                                        <form method="post">
                                           <div class="custom-control custom-checkbox custom-control-inline">
                                              <input class="custom-control-input" type="checkbox" name="checkbox" value="<?php echo date("mdY"); ?>" onchange="this.form.submit()" id="customCheck<?php echo $row["id"]; ?>" <?php if($row["status"]) { echo "checked"; } ?>>
                                              <label class="custom-control-label" for="customCheck<?php echo $row["id"]; ?>"></label>
                                           </div>
                                           <input type="hidden" name="taskStatus" value="1">
                                           <input type="hidden" name="task" value="<?php echo $row["id"]; ?>">
                                        </form>
                                     <td>
                                        <div class="d-flex align-items-center">
                                           <div>
                                              <h6 class="font-weight-500 text-dark mb-0"><?php echo $row["name"]; ?></h6>
                                              <?php
                                                 $sql1 = "SELECT * FROM labels WHERE id=$row[label]";
                                                 $result1 = mysqli_query($conn, $sql1);

                                                 if (mysqli_num_rows($result1) > 0) {
                                                     while($row1 = mysqli_fetch_assoc($result1)) { ?>
                                              <a href="?label=<?php echo $row1["id"]; ?>" class="badge text-gray-dark pl-0"><i class="fas fa-circle mr-1" style="color:<?php echo $row1["color"]; ?>"></i> <?php echo $row1["name"]; ?></a>
                                              <?php }
                                                 } else { ?>
                                              <div class="badge text-gray-dark pl-0"><i class="fas fa-circle text-secondary mr-1"></i> All tasks</div>
                                              <?php } ?>
                                           </div>
                                        </div>
                                     </td>
                                     <td class="float-right">
                                        <div class="row">
                                           <a href="#" data-toggle="modal" data-target="#modal<?php echo $row["id"]; ?>" class="btn btn-light btn-sm mr-2"><i class="fas fa-pencil mr-2"></i> Edit</a>
                                        </div>
                         </div>
                         </tr>
                         <div class="modal fade" id="modal<?php echo $row["id"]; ?>">
                         <div class="modal-dialog">
                         <div class="modal-content">
                         <div class="modal-header">
                         <h5 class="modal-title"><i class="far fa-pencil mr-2"></i> Edit Task</h5>
                         <button type="button" class="close" data-dismiss="modal">
                         <span>×</span>
                         </button>
                         </div>
                         <div class="modal-body">
                         <form method="post">
                         <div class="form-group">
                         <input type="text" name="name" class="form-control" value="<?php echo $row["name"]; ?>" required>
                         </div>
                         <div class="form-group">
                         <select name="label" class="custom-select">
                         <option value="0">All Tasks</option>
                         <?php
                            $sql1 = "SELECT * FROM labels WHERE user='$userId'";
                            $result1 = mysqli_query($conn, $sql1);

                            if (mysqli_num_rows($result1) > 0) {
                              while($row1 = mysqli_fetch_assoc($result1)) { ?>
                         <option value="<?php echo $row1["id"]; ?>" <?php if($row1["id"] == $row["label"]) { echo "selected"; } ?>><?php echo $row1["name"]; ?></option>
                         <?php }
                            }

                             ?>
                         </select>
                         </div>
                         <div class="row">
                         <div class="col-md-6">
                         <div class="form-group">
                         <input type="hidden" name="task" value="<?php echo $row["id"]; ?>">
                         <button type="submit" name="editTask" value="1" class="btn btn-gray-400 text-white btn-block"><i class="far fa-save mr-2"></i> Save</button>
                         </div>
                         </div>
                         </form>
                         <div class="col-md-6">
                         <div class="form-group">
                         <form method="post" onsubmit="return confirm('Are you sure you\'d like to delete this task?');">
                         <input type="hidden" name="taskId" value="<?php echo $row["id"]; ?>">
                         <button type="submit" name="deleteTask" value="1" class="btn btn-danger btn-block"><i class="fas fa-trash mr-2"></i> Delete</button>
                         </form>
                         </div>
                         </div>
                         </div>
                         </div>
                         </div>
                         </div>
                         <?php }
                            } else { ?>
                         <div class="text-center mt-3">
                         <i class="fas fa-frown text-muted fa-3x mt-1"></i>
                         <h5 class='text-muted mt-4 mb-3'>There's nothing here, yet.</h5>
                         <p class="text-gray mb-4">Once you complete tasks, they'll start to appear here.</p>
                         </div>
                         <?php }
                            ?>
                         </tbody>
                         </table>
                         </div>
                      </div>

                  <?php } if(isset($_GET["q"])) { ?>

                    <div class="card-body">
                       <h5 class="mb-3"><i class="far fa-search mr-2"></i> Search results for "<?php echo $_GET["q"]; ?>"</h5>
                       <div class="table-responsive">
                          <table class="table table-borderless mb-0">
                             <tbody>

<?php $con=new mysqli($host, $username, $password, $dbname);
        $sql="SELECT * FROM tasks WHERE name LIKE '%$_GET[q]%' AND userId='$userId' ORDER BY id DESC";

        $res=$con->query($sql);

        if (mysqli_num_rows($res) > 0) {
        while($row=$res->fetch_assoc()){ ?>

          <tr>
             <td style="width:5%">
                <form method="post">
                   <div class="custom-control custom-checkbox custom-control-inline">
                      <input class="custom-control-input" type="checkbox" name="checkbox" value="<?php echo date("mdY"); ?>" onchange="this.form.submit()" id="customCheck<?php echo $row["id"]; ?>" <?php if($row["status"]) { echo "checked"; } ?>>
                      <label class="custom-control-label" for="customCheck<?php echo $row["id"]; ?>"></label>
                   </div>
                   <input type="hidden" name="taskStatus" value="1">
                   <input type="hidden" name="task" value="<?php echo $row["id"]; ?>">
                </form>
             <td>
                <div class="d-flex align-items-center">
                   <div>
                      <h6 class="font-weight-500 text-dark mb-0"><?php echo $row["name"]; ?></h6>
                      <?php
                         $sql1 = "SELECT * FROM labels WHERE id=$row[label]";
                         $result1 = mysqli_query($conn, $sql1);

                         if (mysqli_num_rows($result1) > 0) {
                             while($row1 = mysqli_fetch_assoc($result1)) { ?>
                      <a href="?label=<?php echo $row1["id"]; ?>" class="badge text-gray-dark pl-0"><i class="fas fa-circle mr-1" style="color:<?php echo $row1["color"]; ?>"></i> <?php echo $row1["name"]; ?></a>
                      <?php }
                         } else { ?>
                      <div class="badge text-gray-dark pl-0"><i class="fas fa-circle text-secondary mr-1"></i> All tasks</div>
                      <?php } ?>
                   </div>
                </div>
             </td>
             <td class="float-right">
                <div class="row">
                   <a href="#" data-toggle="modal" data-target="#modal<?php echo $row["id"]; ?>" class="btn btn-light btn-sm mr-2"><i class="fas fa-pencil mr-2"></i> Edit</a>
                </div>
 </div>
 </tr>
 <div class="modal fade" id="modal<?php echo $row["id"]; ?>">
 <div class="modal-dialog">
 <div class="modal-content">
 <div class="modal-header">
 <h5 class="modal-title"><i class="far fa-pencil mr-2"></i> Edit Task</h5>
 <button type="button" class="close" data-dismiss="modal">
 <span>×</span>
 </button>
 </div>
 <div class="modal-body">
 <form method="post">
 <div class="form-group">
 <input type="text" name="name" class="form-control" value="<?php echo $row["name"]; ?>" required>
 </div>
 <div class="form-group">
 <select name="label" class="custom-select">
 <option value="0">All Tasks</option>
 <?php
    $sql1 = "SELECT * FROM labels WHERE user='$userId'";
    $result1 = mysqli_query($conn, $sql1);

    if (mysqli_num_rows($result1) > 0) {
      while($row1 = mysqli_fetch_assoc($result1)) { ?>
 <option value="<?php echo $row1["id"]; ?>" <?php if($row1["id"] == $row["label"]) { echo "selected"; } ?>><?php echo $row1["name"]; ?></option>
 <?php }
    }

     ?>
 </select>
 </div>
 <div class="row">
 <div class="col-md-6">
 <div class="form-group">
 <input type="hidden" name="task" value="<?php echo $row["id"]; ?>">
 <button type="submit" name="editTask" value="1" class="btn btn-gray-400 text-white btn-block"><i class="far fa-save mr-2"></i> Save</button>
 </div>
 </div>
 </form>
 <div class="col-md-6">
 <div class="form-group">
 <form method="post" onsubmit="return confirm('Are you sure you\'d like to delete this task?');">
 <input type="hidden" name="taskId" value="<?php echo $row["id"]; ?>">
 <button type="submit" name="deleteTask" value="1" class="btn btn-danger btn-block"><i class="fas fa-trash mr-2"></i> Delete</button>
 </form>
 </div>
 </div>
 </div>
 </div>
 </div>
 </div>



<?php } } else { ?>
  <div class="text-center mt-3">
  <i class="fas fa-times text-secondary fa-3x"></i>
  <h5 class='text-muted text-center mt-4 mb-4'>No search results found.</h5>
  </div>
<?php }?>

</tbody>
</table>
</div>
</div>




<?php  } if (empty($_GET)) { ?>
                     <div class="card-body">
                        <h5 class="mb-3"><i class="far fa-inbox mr-2"></i> Inbox</h5>
                        <div class="table-responsive">
                           <table class="table table-borderless mb-0">
                              <tbody>
                                 <?php
                                    $conn = mysqli_connect($host, $username, $password, $dbname);
                                    $sql = "SELECT * FROM tasks WHERE userId='$userId' AND status='' ORDER BY id DESC";
                                    $result = mysqli_query($conn, $sql);

                                    if (mysqli_num_rows($result) > 0) {
                                      while($row = mysqli_fetch_assoc($result)) { ?>
                                 <tr>
                                    <td style="width:5%">
                                       <form method="post">
                                          <div class="custom-control custom-checkbox custom-control-inline">
                                             <input class="custom-control-input" type="checkbox" name="checkbox" value="<?php echo date("mdY"); ?>" onchange="this.form.submit()" id="customCheck<?php echo $row["id"]; ?>" <?php if($row["status"]) { echo "checked"; } ?>>
                                             <label class="custom-control-label" for="customCheck<?php echo $row["id"]; ?>"></label>
                                          </div>
                                          <input type="hidden" name="taskStatus" value="1">
                                          <input type="hidden" name="task" value="<?php echo $row["id"]; ?>">
                                       </form>
                                    <td>
                                       <div class="d-flex align-items-center">
                                          <div>
                                             <h6 class="font-weight-500 text-dark mb-0"><?php echo $row["name"]; ?></h6>
                                             <?php
                                                $sql1 = "SELECT * FROM labels WHERE id=$row[label]";
                                                $result1 = mysqli_query($conn, $sql1);

                                                if (mysqli_num_rows($result1) > 0) {
                                                    while($row1 = mysqli_fetch_assoc($result1)) { ?>
                                             <a href="?label=<?php echo $row1["id"]; ?>" class="badge text-gray-dark pl-0"><i class="fas fa-circle mr-1" style="color:<?php echo $row1["color"]; ?>"></i> <?php echo $row1["name"]; ?></a>
                                             <?php }
                                                } else { ?>
                                             <div class="badge text-gray-dark pl-0"><i class="fas fa-circle text-secondary mr-1"></i> All tasks</div>
                                             <?php } ?>
                                          </div>
                                       </div>
                                    </td>
                                    <td class="float-right">
                                       <div class="row">
                                          <a href="#" data-toggle="modal" data-target="#modal<?php echo $row["id"]; ?>" class="btn btn-light btn-sm mr-2"><i class="fas fa-pencil mr-2"></i> Edit</a>
                                       </div>
                        </div>
                        </tr>
                        <div class="modal fade" id="modal<?php echo $row["id"]; ?>">
                        <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title"><i class="far fa-pencil mr-2"></i> Edit Task</h5>
                        <button type="button" class="close" data-dismiss="modal">
                        <span>×</span>
                        </button>
                        </div>
                        <div class="modal-body">
                        <form method="post">
                        <div class="form-group">
                        <input type="text" name="name" class="form-control" value="<?php echo $row["name"]; ?>" required>
                        </div>
                        <div class="form-group">
                        <select name="label" class="custom-select">
                        <option value="0">All Tasks</option>
                        <?php
                           $sql1 = "SELECT * FROM labels WHERE user='$userId'";
                           $result1 = mysqli_query($conn, $sql1);

                           if (mysqli_num_rows($result1) > 0) {
                             while($row1 = mysqli_fetch_assoc($result1)) { ?>
                        <option value="<?php echo $row1["id"]; ?>" <?php if($row1["id"] == $row["label"]) { echo "selected"; } ?>><?php echo $row1["name"]; ?></option>
                        <?php }
                           }

                            ?>
                        </select>
                        </div>
                        <div class="row">
                        <div class="col-md-6">
                        <div class="form-group">
                        <input type="hidden" name="task" value="<?php echo $row["id"]; ?>">
                        <button type="submit" name="editTask" value="1" class="btn btn-gray-400 text-white btn-block"><i class="far fa-save mr-2"></i> Save</button>
                        </div>
                        </div>
                        </form>
                        <div class="col-md-6">
                        <div class="form-group">
                        <form method="post" onsubmit="return confirm('Are you sure you\'d like to delete this task?');">
                        <input type="hidden" name="taskId" value="<?php echo $row["id"]; ?>">
                        <button type="submit" name="deleteTask" value="1" class="btn btn-danger btn-block"><i class="fas fa-trash mr-2"></i> Delete</button>
                        </form>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
                        <?php }
                           } else { ?>
                        <div class="text-center">
                        <i class="fas fa-check text-success fa-3x"></i>
                        <h5 class='text-muted text-center mt-4 mb-4'>Looks like you're all caught up</h5>
                        </div>
                        <?php }
                           ?>
                        </tbody>
                        </table>
                        </div>
                     </div>
                   <?php } ?>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="list-group mb-2">
                     <div class="list-group-item d-flex align-items-center">
                        <div class="width-40 height-40 d-flex align-items-center justify-content-center bg-warning text-white rounded ml-n1">
                           <i class="fas fa-tasks fa-lg"></i>
                        </div>
                        <div class="flex-fill pl-3 pr-3">
                           <div class="font-weight-600">
                              <?php
                                 $conn = mysqli_connect($host, $username, $password, $dbname);
                                 $query = "SELECT id FROM tasks WHERE userId='$userId' AND status=''";

                                 $result = mysqli_query($conn, $query);

                                 if ($result) {

                                 $row = mysqli_num_rows($result);

                                 if ($row)
                                 { echo $row; } else { echo "0"; }

                                 mysqli_free_result($result);
                                 }

                                 mysqli_close($conn);

                                 ?>
                           </div>
                           <div class="fs-13px">Open Tasks</div>
                        </div>
                     </div>
                  </div>
                  <div class="list-group mt-2">
                     <div class="list-group-item d-flex align-items-center">
                        <div class="width-40 height-40 d-flex align-items-center justify-content-center bg-success text-white rounded ml-n1">
                           <i class="fas fa-check fa-lg"></i>
                        </div>
                        <div class="flex-fill pl-3 pr-3">
                           <div class="font-weight-600">
                              <?php
                                 $conn = mysqli_connect($host, $username, $password, $dbname);
                                 $query = "SELECT id FROM tasks WHERE userId='$userId' AND status!=''";

                                 $result = mysqli_query($conn, $query);

                                 if ($result) {

                                 $row = mysqli_num_rows($result);

                                 if ($row)
                                 { echo $row; } else { echo "0"; }

                                 mysqli_free_result($result);
                                 }

                                 mysqli_close($conn);

                                 ?>
                           </div>
                           <div class="fs-13px">Completed Tasks</div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="modal fade" id="modalLabel">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title"><i class="far fa-plus mr-2"></i> New Label</h5>
                  <button type="button" class="close" data-dismiss="modal">
                  <span>×</span>
                  </button>
               </div>
               <div class="modal-body">
                  <form method="post">
                     <div class="form-group">
                        <input type="text" name="name" class="form-control" placeholder="Label Name" required>
                     </div>
                     <div class="form-group">
                        <input type="text" name="color" class="form-control jscolor {hash:true}" value="#8caac3" placeholder="Color" required>
                     </div>
                     <div class="text-center">
                        <button type="submit" name="createLabel" value="1" class="btn btn-primary w-50">Create</button>
                     </div>
               </div>
               </form>
            </div>
         </div>
      </div>
      <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
      <script src="assets/js/jscolor.min.js"></script>
      <script>
         if ( window.history.replaceState ) {
             window.history.replaceState( null, null, window.location.href );
         }

         (function timeAgo(selector) {

         var templates = {
             prefix: "",
             suffix: " ago",
             seconds: "less than a minute",
             minute: "about a minute",
             minutes: "%d minutes",
             hour: "about an hour",
             hours: "about %d hours",
             day: "a day",
             days: "%d days",
             month: "about a month",
             months: "%d months",
             year: "about a year",
             years: "%d years"
         };
         var template = function (t, n) {
             return templates[t] && templates[t].replace(/%d/i, Math.abs(Math.round(n)));
         };

         var timer = function (time) {
             if (!time) return;
             time = time.replace(/\.\d+/, ""); // remove milliseconds
             time = time.replace(/-/, "/").replace(/-/, "/");
             time = time.replace(/T/, " ").replace(/Z/, " UTC");
             time = time.replace(/([\+\-]\d\d)\:?(\d\d)/, " $1$2"); // -04:00 -> -0400
             time = new Date(time * 1000 || time);

             var now = new Date();
             var seconds = ((now.getTime() - time) * .001) >> 0;
             var minutes = seconds / 60;
             var hours = minutes / 60;
             var days = hours / 24;
             var years = days / 365;

             return templates.prefix + (
             seconds < 45 && template('seconds', seconds) || seconds < 90 && template('minute', 1) || minutes < 45 && template('minutes', minutes) || minutes < 90 && template('hour', 1) || hours < 24 && template('hours', hours) || hours < 42 && template('day', 1) || days < 30 && template('days', days) || days < 45 && template('month', 1) || days < 365 && template('months', days / 30) || years < 1.5 && template('year', 1) || template('years', years)) + templates.suffix;
         };

         var elements = document.getElementsByClassName('timeago');
         for (var i in elements) {
             var $this = elements[i];
             if (typeof $this === 'object') {
                 $this.innerHTML = timer($this.getAttribute('title') || $this.getAttribute('datetime'));
             }
         }
         // update time every minute
         setTimeout(timeAgo, 60000);

         })();

      </script>
</html>

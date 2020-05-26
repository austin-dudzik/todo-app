<div class="app-header">
   <div class="brand">
      <a href="index" class="brand-logo ml-4">
      ToDoApp
      </a>
   </div>
   <style>
   .app-sidebar .menu .menu-item .menu-icon .menu-icon-label {
     background:none;
     background:lightgray;
     color:gray;
   }
   </style>
   <div class="menu">
      <form class="menu-search" method="get">
         <div class="menu-search-icon"><i class="fa fa-search"></i></div>
         <div class="menu-search-input">
           <input type="text" name="q" class="form-control" placeholder="Search tasks..." <?php if(isset($_GET["q"])) { ?>value="<?php echo $_GET["q"]; ?>"<?php } ?>required>
         </div>
      </form>
      <div class="menu-item dropdown">
         <a href="#" data-toggle="dropdown" data-display="static" class="menu-link">
            <div class="menu-icon"><i class="<?php if(isset($doneToday)) { echo "far"; } else { echo "fas"; } ?> fa-calendar-day nav-icon"></i></div>
            <?php if(isset($doneToday)) { ?><div class="menu-label bg-success"><?php echo $doneToday; ?></div><?php } ?>
         </a>
         <div class="dropdown-menu dropdown-menu-right dropdown-notification">
            <h6 class="dropdown-header text-gray-900 mb-1"><?php echo date("l M j") ?></h6>

            <?php

            $conn = mysqli_connect($host, $username, $password, $dbname);
            $sql = "SELECT * FROM tasks WHERE userId='$userId' AND status='$date'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
              while($row = mysqli_fetch_assoc($result)) { ?>

                <div class="dropdown-notification-item">
                   <div class="dropdown-notification-icon">
                      <i class="fa fa-check fa-lg fa-fw text-success"></i>
                   </div>
                   <div class="dropdown-notification-info">
                      <div class="title mb-0"><?php echo $row["name"]; ?></div>
                      <div class="time mt-0">Created <span class="timeago" title="<?php echo $row["created"]; ?>"><?php echo $row["created"]; ?></span></div>

                   </div>
                 </div>
              <?php } ?>
              <div class="p-2 text-center mt-2 mb-n1">
                 <a href="?completed=1" class="text-gray-800 text-decoration-none">View all</a>
              </div>
            <?php } else { ?>
              <div class="text-center pt-3">
              <i class="fas fa-sun fa-3x text-yellow"></i>
               <p class='text-muted text-center mt-4'>No tasks completed, yet.</p>
             </div>
            <?php }

             ?>
         </div>
      </div>
      <div class="menu-item dropdown">
         <a href="#" data-toggle="dropdown" data-display="static" class="menu-link">
            <div class="menu-img">
               <img src="https://gravatar.com/avatar/<?php echo md5($email) ?>" alt="" class="mw-100 mh-100 rounded-circle">
            </div>
            <div class="menu-text"><?php echo $first_name ?> <?php echo $last_name ?></div>
         </a>
         <div class="dropdown-menu dropdown-menu-right mr-lg-3">
            <a class="dropdown-item d-flex align-items-center" href="logout">Log Out <i class="fas fa-key fa-fw ml-auto text-gray-400 f-s-16"></i></a>
         </div>
      </div>
   </div>
</div>
<div id="sidebar" class="app-sidebar">
   <div class="app-sidebar-content" data-scrollbar="true" data-height="100%">
      <div class="menu">
         <div class="menu-header">Navigation</div>
         <div class="menu-item <?php if(isset($_GET["label"]) || isset($_GET["completed"]) || isset($_GET["q"])) { } else { echo "active"; } ?>">
            <a href="index" class="menu-link">
            <span class="menu-icon"><i class="fa fa-inbox"></i></span>
            <span class="menu-text">Inbox</span>
            </a>
         </div>
         <div class="menu-divider"></div>
         <div class="menu-header">
            Labels <a href="#" data-toggle="modal" data-target="#modalLabel" class="text-muted float-right"><i class="far fa-plus"></i></a>
         </div>

         <?php

         $conn = mysqli_connect($host, $username, $password, $dbname);
         $sql = "SELECT * FROM labels WHERE user='$userId'";
         $result = mysqli_query($conn, $sql);

         if (mysqli_num_rows($result) > 0) {
           while($row = mysqli_fetch_assoc($result)) { ?>
             <div class="menu-item <?php if(isset($_GET["label"])) { if($row["id"] == $_GET["label"]) { echo "active"; } } ?>">
                <a href="?label=<?php echo $row["id"]; ?>" class="menu-link">
                <span class="menu-icon"><i class="fa fa-circle" style="color:<?php echo $row["color"]; ?>"></i>
                  <?php
                  $query = "SELECT id FROM tasks WHERE userId='$userId' AND status='' AND label='$row[id]'";
                  $result1 = mysqli_query($conn, $query);
                  if ($result1) {
                  $row1 = mysqli_num_rows($result1);
                  if ($row1)
                  { ?><span class="menu-icon-label"><?php echo $row1 ?></span><?php }
                  mysqli_free_result($result1);
                  }
                  ?>
                </span>
                <span class="menu-text"><?php echo $row["name"]; ?></span>
                </a>
             </div>
           <?php }
         } else {
           echo "<p class='text-muted text-center mt-4'>No labels, yet.</p>";
         }

          ?>

         <div class="p-3 px-4 mt-auto hide-on-minified">
            <a href="?completed=1" class="btn btn-block btn-success font-weight-600">
            <span class="opacity-9"><i class="fas fa-check mr-1 ml-n1"></i> Completed</span>
            </a>
         </div>
      </div>
   </div>
</div>

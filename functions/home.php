<?php
error_reporting(0);

   if(isset($_POST["createLabel"])) {
   $conn = mysqli_connect($host, $username, $password, $dbname);

   $sql = "INSERT INTO labels (name, user, color)
   VALUES ('$_POST[name]', '$userId', '$_POST[color]')";

   if (mysqli_query($conn, $sql)) {
   }

   mysqli_close($conn);
   }

   if(isset($_POST["createTask"])) {
   $conn = mysqli_connect($host, $username, $password, $dbname);

   $date = date("F j, Y, g:i a");
   $sql = "INSERT INTO tasks (name, userId, label, created)
   VALUES ('$_POST[name]', '$userId', '$_POST[label]', '$date')";

   if (mysqli_query($conn, $sql)) {
   }

   mysqli_close($conn);
   }
   if(isset($_POST["taskStatus"])) {
   $conn = mysqli_connect($host, $username, $password, $dbname);

   $sql = "UPDATE tasks SET status='$_POST[checkbox]' WHERE id='$_POST[task]'";

if (mysqli_query($conn, $sql)) {
}

   mysqli_close($conn);
   }

   if(isset($_POST["editTask"])) {
   $conn = mysqli_connect($host, $username, $password, $dbname);

   $sql = "UPDATE tasks SET name='$_POST[name]', label='$_POST[label]' WHERE id='$_POST[task]'";

   if (mysqli_query($conn, $sql)) {
   }

   mysqli_close($conn);
   }


if(isset($_POST["deleteTask"])) {
  $conn = mysqli_connect($host, $username, $password, $dbname);

  $sql = "DELETE FROM tasks WHERE id=$_POST[taskId]";

  if (mysqli_query($conn, $sql)) {
  }

mysqli_close($conn);
}

if(isset($_POST["deleteLabel"])) {
  $conn = mysqli_connect($host, $username, $password, $dbname);

  $sql = "DELETE FROM labels WHERE id=$_POST[labelId]";

  if (mysqli_query($conn, $sql)) {

    $sql1 = "DELETE FROM tasks WHERE label=$_POST[labelId]";

    if (mysqli_query($conn, $sql1)) {
    }


  }

mysqli_close($conn);
}


    $conn = mysqli_connect($host, $username, $password, $dbname);

    $date = date("mdY");
    $query = "SELECT * FROM tasks WHERE status='$date' AND userId='$userId'";

    $result = mysqli_query($conn, $query);

    if ($result)
    {
        $row = mysqli_num_rows($result);

           if ($row) {
              $doneToday = $row;
            }
        mysqli_free_result($result);
    }

    mysqli_close($conn);

     ?>

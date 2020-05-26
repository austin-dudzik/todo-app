<?php
function clean($string) {
    return htmlentities($string);
}
function redirect($location) {
    return header("Location: {$location}");
}
function set_message($message) {
    if (!empty($message)) {
        $_SESSION['message'] = $message;
    } else {
        $message = "";
    }
}
function display_message() {
    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
}
function validation_errors($error_message) {
    $error_message = <<<DELIMITER

<div class="alert alert-danger alert-dismissible" role="alert">
  	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  	$error_message
 </div>
DELIMITER;
    return $error_message;
}
function email_exists($email) {
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $result = query($sql);
    if (row_count($result) == 1) {
        return true;
    } else {
        return false;
    }
}
function send_email($email, $subject, $msg, $headers) {
    return mail($email, $subject, $msg, $headers);
}


/****************Validation functions ********************/
function validate_user_registration() {
    $errors = [];
    $min = 3;
    $max = 20;
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $first_name = clean($_POST['first_name']);
        $last_name = clean($_POST['last_name']);
        $email = clean($_POST['email']);
        $password = clean($_POST['password']);
        if (email_exists($email)) {
            $errors[] = "Sorry that email already is registered";
        }
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo validation_errors($error);
            }
        } else {
            if (register_user($first_name, $last_name, $email, $password)) {
                set_message("<p class='alert alert-success'>Success! Please log in to continue...</p>");
                redirect("index.php");
            } else {
                set_message("<p class='bg-danger text-center'>Sorry we could not register the user</p>");
                redirect("index.php");
            }
        }
    } // post request

} // function


/****************Register user functions ********************/
function register_user($first_name, $last_name, $email, $password) {
    $first_name = escape($first_name);
    $last_name = escape($last_name);
    $email = escape($email);
    $password = escape($password);
    if (email_exists($email)) {
        return false;
    } else {
        $password = md5($password);
        $validation_code = md5(microtime());
        $sql = "INSERT INTO users(first_name, last_name, email, password)";
        $sql.= " VALUES('$first_name','$last_name','$email','$password')";
        $result = query($sql);
        confirm($result);
    }
}


/****************Validate user login functions ********************/
function validate_user_login() {
    $errors = [];
    $min = 3;
    $max = 20;
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $email = clean($_POST['email']);
        $password = clean($_POST['password']);
        if (empty($email)) {
            $errors[] = "Email field cannot be empty";
        }
        if (empty($password)) {
            $errors[] = "Password field cannot be empty";
        }
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo validation_errors($error);
            }
        } else {
            if (login_user($email, $password)) {
                redirect("index.php");
            } else {
                echo validation_errors("Your email or password is incorrect. Please try again.");
            }
        }
    }
} // function


/**************** User login functions ********************/
function login_user($email, $password) {
    $sql = "SELECT password, id FROM users WHERE email = '" . escape($email) . "'";
    $result = query($sql);
    if (row_count($result) == 1) {
        $row = fetch_array($result);
        $db_password = $row['password'];
        if (md5($password) === $db_password) {
                // Set login cookie
                setcookie('email', $email, time() + 315569260, "/");
            // Create the session
            $_SESSION['email'] = $email;
            return true;
        } else {
            return false;
        }
        return true;
    } else {
        return false;
    }
} // end of function


/****************logged in function ********************/
function logged_in() {
    if (isset($_SESSION['email']) || isset($_COOKIE['email'])) {
        return true;
    } else {
        return false;
    }
} // functions

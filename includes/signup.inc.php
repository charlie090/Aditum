<?php
if (isset($_POST['submit'])) {
  if (isset($_POST['g-recaptcha-response'])) {
    include_once 'dbh.inc.php';
    $first = mysqli_real_escape_string($conn, $_POST['first']);
    $last = mysqli_real_escape_string($conn, $_POST['last']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $uid = mysqli_real_escape_string($conn, $_POST['uid']);
    $pwd = mysqli_real_escape_string($conn, $_POST['pwd']);
    $response = $_POST['g-recaptcha-response'];
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
      'secret' => '6LfBQGEUAAAAAHSn4FnsziA1HiEGCOLIH_q7U0mA',
      'response' => $_POST["g-recaptcha-response"]
    );
    $options = array(
      'http' => array (
        'header' => "Content-Type: application/x-www-form-urlencoded\r\n".
                    "Content-Length: ".strlen(http_build_query($data))."\r\n",
        'method' => 'POST',
        'content' => http_build_query($data)
      )
    );
    $context  = stream_context_create($options);
    $verify = file_get_contents($url, false, $context);
    $captcha_success=json_decode($verify);

    //Error handlers
    //Check for empty fields

    if(empty($first) || empty($last) || empty($email) || empty($uid) || empty($pwd)) {
      header("Location: ../signup.php?signup=empty");
      exit();
    } else {
      //Check if input characters are valid
      if (!preg_match("/^[a-zA-z]*$/", $first) || !preg_match("/^[a-zA-z]*$/", $last)) {
        header("Location: ../signup.php?signup=invalid");
        exit();
      } else {
        //Check if email is invalid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          header("Location: ../signup.php?signup=email");
          exit();
        } else {
          $sql = "SELECT * FROM users WHERE user_uid='$uid'";
          $result = mysqli_query($conn, $sql);
          $resultCheck = mysqli_num_rows($result);

          if ($resultCheck > 0) {
            header("Location: ../signup.php?signup=usertaken");
            exit();
          } else {
            //Hashing the password
            $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
            //Insert the user into the database
            $sql = "INSERT INTO users (user_first, user_last, user_email, user_uid, user_pwd) VALUES ('$first', '$last', '$email', '$uid', '$hashedPwd');";
            mysqli_query($conn, $sql);
            header("Location: ../signup.php?signup=success");
            exit();
          }
        }
      }
    }
  }
} else {
  header("Location: ../signup.php");
  exit();
}
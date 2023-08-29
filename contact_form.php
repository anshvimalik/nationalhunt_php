<?php
include('conn.php');
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


$data = array(
  'name' => '',
  'number' => '',
  'email' => '',
  'subject' => '',
  'message' => ''
);

$error = array();
if (isset($_POST['submit'])) {
  $data['name'] = $_POST['name'];
  $data['number'] = $_POST['number'];
  $data['email'] = $_POST['email'];
  $data['subject'] = $_POST['subject'];
  $data['message'] = $_POST['message'];
  //validating input fields using php
  if (empty($_POST['name'])) {
    $error['nameErr'] = "*Name field can't be empty";
  } elseif (!preg_match("/^[a-zA-Z ]*$/", $_POST['name'])) {
    $error['nameErr'] = "*Only letters and white space allowed";
  }
  if (empty($_POST['number'])) {
    $error['numErr'] = "*Number field can't be empty";
  } elseif (!preg_match('/^[7-9][0-9]{9}+$/', $_POST['number'])) {
    $error['numErr'] = "*The phone number you are using has an incorrect format.";
  }
  if (empty($_POST['email'])) {
    $error['mailErr'] = "*email field can't be empty";
  } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $error['mailErr'] = "*Email is not in correct format.";
  }
  if (empty($_POST['subject'])) {
    $error['subErr'] = "*subject field can't be empty";
  }
  if (empty($errors)) {
    $ip = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      $ip = $_SERVER['REMOTE_ADDR'];
    }
    //generate recaptcha to avoid spam entries
    $recaptchaSecretKey = '6LfOI-MnAAAAABArP6Z-4cQhlchC-2zMUG9X5U-h';
    $recaptchaResponse = $_POST['g-recaptcha-response'];
    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $recaptchaSecretKey . '&response=' . $recaptchaResponse);
    $responseData = json_decode($verifyResponse);
    if ($responseData->success) {
      sendMail($conn, $data, $ip);
    } else {
      $data['recaptchaErr'] = "reCAPTCHA verification failed. Please try again.";
    }
  }
}
function sendMail($conn, $data, $ip)
{ 
  // Query to avoid duplicate email entries
  $selectQuery = "SELECT * FROM contact_form WHERE email LIKE ' {$data['email']}'";
  $res = $conn->query($selectQuery);
  if ($res->num_rows > 0) {
    echo "*This user has been aleady inserted in the database.";
  } else {
    $sql = "INSERT INTO contact_form(name, number, email, subject, message,ip_address) VALUES ('$data[name]', ' $data[number]', ' $data[email]',  '$data[subject]', '$data[message]', '$ip')";
    if ($conn->query($sql)) {
      echo " inserted";
    }
    //to send email notification with contact information
    $mail = new PHPMailer();
    $mail->SMTPDebug = 2;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'anshvimalik626@gmail.com';
    $mail->Password = 'dqlveanvtphfyvmp';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->setFrom('anshvimalik626@gmail.com', 'Anshvi');
    $mail->addAddress($data['email'], $data['name']);
    $mail->addAddress('anshvimalik626@gmail.com', 'Anshvi');
    $mail->Subject = 'New Form Submission';
    $mail->Body = "A new form submission has been received:\n\nName: $data[name]\nNumber: $data[number]\nEmail: $data[email]\nSubject: $data[subject]\nMessage: $data[message]";
    if ($mail->send()) {
      header("Location: thank_you.php");
    } else {
      echo "email could not be sent";
    }
  }
}
?>
<html>
<head>
  <title> Registration Form </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <script src="https://www.google.com/recaptcha/api.js"></script>

  <style>
    .error {
      color: #FF0001;
    }

    #message {
      margin: 15px 0;
    }
  </style>
</head>

<body>
  <div>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="border border-5 p-5">

      <h2 class="heading text-align-center">Entry form</h2>
      <br>
      <div class="row">
        <label class="form-label">Full name:</label><br>
        <input type="text" placeholder="Enter your name" class="user form-control" name="name" value="<?php if (isset($data['name'])) {
          echo $data['name'];
        } ?>">
        <span class="error" id="username">
          <?php if (isset($error['nameErr'])) {
            echo $error['nameErr'];
          } ?>
        </span>

        <br>
        <label class="form-label"> Phone number: </label><br>
        <input type="text" placeholder="Enter your phone number" class="user form-control" name="number" value="<?php if (isset($data['number'])) {
          echo $data['number'];
        } ?>">
        <span class="error">
          <?php if (isset($error['numErr'])) {
            echo $error['numErr'];
          } ?>
        </span>
        <br>
        <label class="form-label"> Email </label><br>
        <input type="text" placeholder="Enter email" class="user form-control" name="email"
          value="<?php echo isset($data['email']) ? $data['email'] : ''; ?>">
        <span class="error">
          <?php if (isset($error['mailErr'])) {
            echo $error['mailErr'];
          } ?>
        </span>
        <br>
        <label class="form-label"> subject </label><br>
        <input type="text" placeholder="Enter subject" class="user form-control" name="subject"
          value="<?php echo isset($data['subject']) ? $data['subject'] : ''; ?>">
        <span class="error">
          <?php if (isset($error['subErr'])) {
            echo $error['subErr'];
          } ?>
        </span>
        <br>
        <label class="form-label"> Message(optional) </label><br>
        <textarea id="message" placeholder="Enter your message here" class="user form-control"
          name="message"><?php echo isset($data['message']) ? $data['message'] : ''; ?></textarea>
        <br>
        <div class="g-recaptcha" data-sitekey="6LfOI-MnAAAAAIvnqhtJUh5dTcoEs9XoPT_DgY1i"></div>
        <span class="error">
          <?php echo isset($data['recapcthaErr']) ? $data['recapcthaErr'] : ''; ?>
        </span>
        <input type="submit" name="submit" value="Submit" class="btn btn-primary">

    </form>
  </div>
  </div>
</body>

</html>
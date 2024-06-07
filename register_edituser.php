

<?php

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

if (isset($_POST["register"])) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        // Enable verbose debug output
        $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Enable verbose debug output

        // Send using SMTP
        $mail->isSMTP();

        // Set the SMTP server to send through
        $mail->Host = 'smtp.gmail.com';

        // Enable SMTP authentication
        $mail->SMTPAuth = true;

        // SMTP username
        $mail->Username = 'revillasharlene92816@gmail.com'; // Replace with your email address

        // SMTP password
        $mail->Password = 'jmgi anbj cdvl jpqu'; // Replace with your app-specific password

        // Enable TLS encryption;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('izakhaleyhernandez@gmail.com', 'kandyland');

        // Add a recipient
        $mail->addAddress($email, $name);

        // Set email format to HTML
        $mail->isHTML(true);

        $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);

        $mail->Subject = 'Email verification';
        $mail->Body    = '<p>Your verification code is: <b style="font-size: 30px;">' . $verification_code . '</b></p>';

        $mail->send();
        // echo 'Message has been sent';

        $encrypted_password = password_hash($password, PASSWORD_DEFAULT);

        // Connect with database
        $conn = mysqli_connect("localhost", "root", "", "verfication");

        // Insert in users table
        $sql = "INSERT INTO verify(name, email, password, verification_code, email_verified_at) VALUES ('" . $name . "', '" . $email . "', '" . $encrypted_password . "', '" . $verification_code . "', NULL)";
        mysqli_query($conn, $sql);

        header("Location: otp_edituser.php?id=" . $_GET['id'] . "&email=" . $email);
        exit();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
   
   <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: white;
        }
        .container {
            margin-top: 50px;
            max-width: 500px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            font-weight: bold;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center">Verify Your Email</h2>
        <form method="POST">
           
            <div class="form-group">
                <label for="email">Email:</label>
                <select name="email" id="email" class="form-control" required>
                    <option value="amandadomdom247@gmail.com">amandadomdom247@gmail.com</option>
                    <option value="izakhaleyhernandez@gmail.com">izakhaleyhernandez@gmail.com</option>
                    <option value="ralph.ace59@gmail.com">ralph.ace59@gmail.com</option>
                    <option value="xivanxvargasx21@gmail.com">xivanvargasx21@gmail.com</option>
                </select>
            </div>
           
            <button type="submit" name="register" class="btn btn-primary btn-block">Verify Email</button>
        </form>
    </div>
</body>
</html>

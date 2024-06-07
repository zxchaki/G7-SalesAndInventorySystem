<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email</title>
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
        <h2 class="text-center">Email Verification</h2>
        <form method="POST">
            <input type="hidden" name="email" value="<?php echo $_GET['email']; ?>" required>
            <div class="form-group">
                <label for="verification_code">Verification Code:</label>
                <input type="text" class="form-control" id="verification_code" name="verification_code" placeholder="Enter verification code" required>
            </div>
            <button type="submit" name="verify_email" class="btn btn-primary btn-block">Verify</button>
        </form>
    </div>
</body>
</html>

<?php


if (isset($_POST["verify_email"])) {
    $email = $_POST["email"];
    $verification_code = $_POST["verification_code"];

    // Connect with database
    $conn = new mysqli("localhost", "root", "", "verfication");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Mark email as verified
    $stmt = $conn->prepare("UPDATE verify SET email_verified_at = NOW() WHERE email = ? AND verification_code = ?");
    $stmt->bind_param("ss", $email, $verification_code);
    $stmt->execute();

    if ($stmt->affected_rows == 0) {
        echo '
        <div class="container mt-5">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Warning!</strong> Verification code failed.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>';
        die();
    }
    $stmt->close();
    $conn->close();

    header("Location: edit_user.php?id=" . $_GET['id']);
    exit();
}
?>

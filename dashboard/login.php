<?php
// Start session and check if the user is logged in
session_start();

if (isset($_SESSION['logged_in'])) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login in</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="icon" href="../images/logo.png">
    <link rel="stylesheet" href="css/style.css">
</head>


<div class="container mt-5 col-12 col-md-6" dir="rtl">
    <div class="card">
        
        <div class="card-body">
            
<div class="d-flex align-items-center justify-content-center">
    <img src="../images/logo.png" alt="" class="col-4 text-center" style="width: 100px;">
</div>
            <h2 class="card-title text-center">تسجيل الدخول</h2>
            <form method="post" action="">
                <div class="mb-3">
                    <label for="username" class="form-label">اسم المستخدم</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">كلمة المرور</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary">دخول</button>
            </form>

            <?php
            // Handle login form submission
            if (isset($_POST['login'])) {
                include '../includes/db.php';
                $username = $_POST['username'];
                $password = $_POST['password'];

                // Query the users table to verify credentials
                $query = "SELECT * FROM users WHERE username = ?";
                $user = getResults($query, [$username]);

                if ($user && password_verify($password, $user[0]['password'])) {
                    // Successful login
                    session_start();
                    $_SESSION['logged_in'] = true;
                    $_SESSION['user_id'] = $user[0]['id']; // Store user ID in the session
                    $_SESSION['username'] = $username; // Store the username in the session


                    header('Location: index.php');
                    exit;
                } else {
                    echo "<p class='text-danger'>اسم المستخدم أو كلمة المرور غير صحيحة.</p>";
                }
            }
            ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</body>

</html>
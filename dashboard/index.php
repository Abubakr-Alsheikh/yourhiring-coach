<?php
// Include necessary files
include '../includes/db.php';
include 'dashboard_header.php';

if (isset($_POST['change_password'])) {
    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    $userId = $_SESSION['user_id'];

    // Get the user's current password hash
    $query = "SELECT password FROM users WHERE id = ?";
    $user = getResults($query, [$userId])[0];

    if (password_verify($oldPassword, $user['password'])) {
        if ($newPassword === $confirmPassword) {
            // Hash the new password
            $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update the user's password in the database
            $query = "UPDATE users SET password = ? WHERE id = ?";
            $params = [$hashedNewPassword, $userId];

            if (executeQuery($query, $params)) {
                $_SESSION['flash_password'] =  '<p class="text-success text-center lead">تم تغيير كلمة المرور بنجاح.</p>';
            } else {
                $_SESSION['flash_password'] =  '<p class="text-danger">حدث خطأ أثناء تغيير كلمة المرور.</p>';
            }
        } else {
            $_SESSION['flash_password'] =  '<p class="text-danger">كلمتا المرور الجديدتين غير متطابقتين.</p>';
        }
    } else {
        $_SESSION['flash_password'] =  '<p class="text-danger">كلمة المرور الحالية غير صحيحة.</p>';
    }

    header("Location: index.php");
    exit;
}


if (isset($_POST['update_hero'])) {
    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'];
    $buttonText = $_POST['button_text'];
    $buttonLink = $_POST['button_link'];

    // Handle background image upload
    if (isset($_FILES['background_image']) && $_FILES['background_image']['error'] == 0) {
        $targetDir = "../images/"; 
        $targetFile = $targetDir . "background-image-hero.jpg"; // Fixed filename

        // Check if the file is an image
        $imageFileType = strtolower(pathinfo($_FILES['background_image']['name'], PATHINFO_EXTENSION));
        if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png") {
            $_SESSION["error"] = "<p class='text-danger'>آسف، يُسمح فقط بملفات JPG و JPEG و PNG.</p>";
        } else {
            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES["background_image"]["tmp_name"], $targetFile)) {
                $_SESSION['flash_message'] = "<p class='text-success text-center lead'>تم تحديث محتوى الصفحة الرئيسية بنجاح.</p>"; 
            } else {
                $_SESSION["error"] = "<p class='text-danger'>آسف، حدث خطأ أثناء تحميل ملفك.</p>";
            }
        }
    }

    $query = "UPDATE hero_content SET title = ?, subtitle = ?, button_text = ?, button_link = ? WHERE id = 1";
    $params = [$title, $subtitle, $buttonText, $buttonLink];
    if (executeQuery($query, $params)) {
        $_SESSION['flash_message'] = "<p class='text-success text-center lead'>تم تحديث محتوى الصفحة الرئيسية بنجاح.</p>";
    } else {
        $_SESSION["error"] = "<p class='text-danger'>حدث خطأ أثناء تحديث محتوى الصفحة الرئيسية.</p>";
    }
    header("Location: index.php");
    exit;
}

$heroContent = getResults("SELECT * FROM hero_content WHERE id = 1")[0]; // Assuming only one row

?>

<div class="container my-3">
    <h1 class="text-center display-3 mb-4">لوحة التحكم</h1>
    <div class="d-flex align-items-start justify-content-between flex-column flex-md-row">
        <div class="col-md-6 col-12 p-3 border me-3 rounded-3 mb-3" dir="rtl">
            <h3 class="display-6">تغيير كلمة المرور</h3>
            <form method="post" action="">
                <div class="mb-3">
                    <label for="old_password" class="form-label">كلمة المرور الحالية</label>
                    <input type="password" class="form-control" id="old_password" name="old_password" required>
                </div>
                <div class="mb-3">
                    <label for="new_password" class="form-label">كلمة المرور الجديدة</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">تأكيد كلمة المرور الجديدة</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <?php
                if (isset($_SESSION['flash_password'])) {
                    $message = $_SESSION['flash_password'];
                    unset($_SESSION['flash_password']);
                    echo $message;
                }
                ?>
                <button type="submit" name="change_password" class="btn btn-primary">تغيير كلمة المرور</button>
            </form>
        </div>

        <div class="col-md-6 col-12  p-3 border me-3 rounded-3" dir="rtl">
            <h3 class="display-6">تعديل محتوى الصفحة الرئيسية</h3>
            <form method="post" action="" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">العنوان</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo $heroContent['title']; ?>">
                </div>
                <div class="mb-3">
                    <label for="subtitle" class="form-label">النص الفرعي</label>
                    <textarea class="form-control" id="subtitle" name="subtitle" rows="3"><?php echo $heroContent['subtitle']; ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="button_text" class="form-label">نص الزر</label>
                    <input type="text" class="form-control" id="button_text" name="button_text" value="<?php echo $heroContent['button_text']; ?>">
                </div>
                <div class="mb-3">
                    <label for="background_image" class="form-label">صورة الخلفية (JPG, JPEG, PNG)</label>
                    <input type="file" class="form-control" id="background_image" name="background_image">
                </div>
                <?php

                if (isset($_SESSION['flash_message'])) {
                    $message = $_SESSION['flash_message'];
                    unset($_SESSION['flash_message']);
                    echo $message;
                } elseif (isset($_SESSION["error"])) {
                    echo $_SESSION["error"];
                    unset($_SESSION["error"]);
                }

                ?>
                <button type="submit" name="update_hero" class="btn btn-primary">تحديث</button>
            </form>
        </div>
    </div>

    <?php
    include 'dashboard_footer.php';
    ?>
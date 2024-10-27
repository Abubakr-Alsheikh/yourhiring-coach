<?php
include '../includes/db.php';
include 'dashboard_header.php';

$messageId = isset($_GET['id']) ? $_GET['id'] : null;

if ($messageId) {
    $message = getResults("SELECT * FROM messages WHERE id = ?", [$messageId])[0];

    if ($message) {
        // Delete the message from the database
        $query = "DELETE FROM messages WHERE id = ?";
        $params = [$messageId];

        if (executeQuery($query, $params)) {
            $_SESSION['flash_message'] = "<p class='text-success text-center'>تم حذف الرسالة بنجاح.</p>";
        } else {
            $_SESSION['flash_message'] = "<p class='text-danger'>حدث خطأ أثناء حذف الرسالة.</p>";
        }
        header("Location: all_messages.php"); // Redirect back to the all_messages page
        exit;
    } else {
        echo "<p class='text-danger'>لا توجد رسالة بهذا الرقم.</p>";
    }
} else {
    echo "<p class='text-danger'>الرجاء اختيار رسالة.</p>";
}

include 'dashboard_footer.php';
?>
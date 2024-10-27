<?php
include '../includes/db.php';
include 'dashboard_header.php';

$serviceId = isset($_GET['id']) ? $_GET['id'] : null;

if ($serviceId) {
    $service = getResults("SELECT * FROM services WHERE id = ?", [$serviceId])[0];

    if ($service) {
        // Delete the service from the database
        $query = "DELETE FROM services WHERE id = ?";
        $params = [$serviceId];

        if (executeQuery($query, $params)) {
            // Delete the associated image file
            $imagePath = "../images/" . $service['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            $_SESSION['flash_message'] = "<p class='text-success text-center'>تم حذف الخدمة بنجاح.</p>";
        } else {
            $_SESSION['flash_message'] = "<p class='text-danger'>حدث خطأ أثناء حذف الخدمة.</p>";
        }
    } else {
        $_SESSION['flash_message'] = "<p class='text-danger'>لا توجد خدمة بهذا الرقم.</p>";
    }
} else {
    $_SESSION['flash_message'] = "<p class='text-danger'>الرجاء اختيار خدمة.</p>";
}

header("Location: services.php"); // Redirect back to the dashboard
exit;
?>
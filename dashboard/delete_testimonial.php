<?php
include '../includes/db.php';
include 'dashboard_header.php';

$testimonialId = isset($_GET['id']) ? $_GET['id'] : null;

if ($testimonialId) {
    $testimonial = getResults("SELECT * FROM testimonials WHERE id = ?", [$testimonialId])[0];

    if ($testimonial) {
        // Delete the testimonial from the database
        $query = "DELETE FROM testimonials WHERE id = ?";
        $params = [$testimonialId];

        if (executeQuery($query, $params)) {
            $_SESSION['flash_message'] = "<p class='text-success text-center lead'>تم حذف الرأي بنجاح.</p>";
        } else {
            $_SESSION['flash_message'] = "<p class='text-danger'>حدث خطأ أثناء حذف الرأي.</p>";
        }
        header("Location: testimonials.php");
        exit;
    } else {
        echo "<p class='text-danger'>لا يوجد رأي بهذا الرقم.</p>";
    }
} else {
    echo "<p class='text-danger'>الرجاء اختيار رأي.</p>";
}

?>
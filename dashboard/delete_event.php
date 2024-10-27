<?php
include '../includes/db.php';
include 'dashboard_header.php';

$eventId = isset($_GET['id']) ? $_GET['id'] : null;

if ($eventId) {
    $event = getResults("SELECT * FROM events WHERE id = ?", [$eventId])[0];

    if ($event) {
        // Delete the event from the database
        $query = "DELETE FROM events WHERE id = ?";
        $params = [$eventId];

        if (executeQuery($query, $params)) {
            // Delete the associated image file
            $imagePath = "../images/" . $event['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            $_SESSION['flash_message'] = "<p class='text-succes text-center lead'>تم حذف الاعلان بنجاح.</p>";
        } else {
            $_SESSION['flash_message'] = "<p class='text-danger'>حدث خطأ أثناء حذف الاعلان.</p>";
        }
    } else {
        echo "<p class='text-danger'>لا يوجد حدث بهذا الرقم.</p>";
    }
} else {
    echo "<p class='text-danger'>الرجاء اختيار حدث.</p>";
}
header("Location: events.php"); // Redirect back to the dashboard
exit;

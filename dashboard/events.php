<?php
include '../includes/db.php';
include 'dashboard_header.php';


// Handle toggle event visibility
if (isset($_POST['toggle_event_visibility'])) {
    $eventId = $_POST['event_id'];
    $isHidden = getResults("SELECT is_hidden FROM events WHERE id = ?", [$eventId])[0]['is_hidden'];
    $newIsHidden = ($isHidden == 1) ? 0 : 1;

    $query = "UPDATE events SET is_hidden = ? WHERE id = ?";
    $params = [$newIsHidden, $eventId];

    if (executeQuery($query, $params)) {
        // Optionally, you can add a success message or redirect
        $_SESSION['flash_message'] = "<p class='text-success text-center lead'>تم تحديث حالة الرؤية</p>";
        header("Location: events.php");
        exit;
    } else {
        $_SESSION['flash_message'] = "<p class='text-danger'>حدث خطأ أثناء تحديث حالة الرؤية.</p>";
    }
}

?>

<div class="container mt-3">
    <h1 class="text-center display-3 mb-4">إدارة الأعلانات</h1>
    <?php
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        echo $message;
    }
    ?>
    <a href="add_event.php" class="btn btn-secondary lead mb-3 col-12 col-md-2">إضافة إعلان</a>
    <div class="overflow-auto">
        <table class="table table-bordered" dir="rtl">
            <thead>
                <tr>
                    <th>الصورة</th>
                    <th>نص</th>
                    <th>الخدمة</th>  <!-- Added service column -->
                    <th>مخفي</th>
                    <th>التعديل</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $events = getResults("SELECT e.*, s.title AS service_title FROM events e LEFT JOIN services s ON e.service_id = s.id ORDER BY e.is_hidden"); 
                if ($events) {
                    foreach ($events as $event) {
                ?>
                        <tr>
                            <td><img src="../images/<?php echo $event['image']; ?>" alt="Event Image" width="50"></td>
                            <td><?php echo $event['text']; ?></td>
                            <td><?php echo $event['service_title'] ?? 'لا يوجد خدمة'; ?></td>  <!-- Display service title or "لا يوجد خدمة" -->
                            <td>
                                <form method="post" action="">
                                    <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                    <button type="submit" name="toggle_event_visibility" class="btn btn-sm <?php echo ($event['is_hidden'] == 1) ? 'btn-success' : 'btn-warning'; ?>">
                                        <?php echo ($event['is_hidden'] == 1) ? 'إظهار' : 'إخفاء'; ?>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <a href="edit_event.php?id=<?php echo $event['id']; ?>" class="btn btn-sm btn-primary">تعديل</a>
                                <a href="delete_event.php?id=<?php echo $event['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا الاعلان')">حذف</a>
                            </td>
                        </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include 'dashboard_footer.php';
?>
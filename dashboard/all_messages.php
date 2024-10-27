<?php
include '../includes/db.php';
include 'dashboard_header.php';

// Handle message deletion
if (isset($_POST['delete_selected'])) {
    if (isset($_POST['selected_messages']) && is_array($_POST['selected_messages'])) {
        $messageIds = array_map('intval', $_POST['selected_messages']);
        $placeholders = implode(',', array_fill(0, count($messageIds), '?'));
        $query = "DELETE FROM messages WHERE id IN ($placeholders)";

        if (executeQuery($query, $messageIds)) {
            $_SESSION['flash_message'] = "<p class='text-success text-center'>تم حذف الرسائل المحددة بنجاح.</p>";
        } else {
            $_SESSION['flash_message'] = "<p class='text-danger'>حدث خطأ أثناء حذف الرسائل.</p>";
        }
    } else {
        $_SESSION['flash_message'] = "<p class='text-warning text-center'>الرجاء تحديد رسالة واحدة على الأقل لحذفها.</p>";
    }
    header("Location: all_messages.php"); // Redirect back to the all_messages page
    exit;
}

// Get sorting parameters from URL
$orderBy = isset($_GET['order_by']) ? $_GET['order_by'] : 'experience';
$orderDir = isset($_GET['order_dir']) ? $_GET['order_dir'] : 'DESC';

// Build the SQL query for sorting
$query = "SELECT * FROM messages ORDER BY is_handled ASC, $orderBy $orderDir";

// Fetch messages based on sorting parameters
$messages = getResults($query);

?>

<div class="container mt-3" dir="rtl">
    <h1 class="text-center display-3 mb-4">جميع الرسائل</h1>
    <?php
    if (isset($_SESSION['flash_message'])) {
        echo $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
    }
    ?>


    <form method="post" action="all_messages.php">
        <div class="d-flex justify-content-end my-2">
            <button type="submit" name="delete_selected" class="btn btn-danger mx-2 col-md-2 col-6" onclick="return confirm('هل أنت متأكد من حذف الرسائل المحددة؟')">
                حذف المحدد
            </button>
            <a href="messages.php" class="btn btn-secondary col-md-2 col-6">الرسائل الحالية</a>
        </div>
        <div class="overflow-auto">
            <table class="table table-bordered" id="messagesTable">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select_all"></th>
                        <th>الاسم <a href="?order_by=name&order_dir=<?php echo ($orderBy == 'name' && $orderDir == 'ASC') ? 'DESC' : 'ASC'; ?>"><i class="fas fa-sort"></i></a></th>
                        <th>سنوات الخبرة <a href="?order_by=experience&order_dir=<?php echo ($orderBy == 'experience' && $orderDir == 'ASC') ? 'DESC' : 'ASC'; ?>"><i class="fas fa-sort"></i></a></th>
                        <th>التخصص <a href="?order_by=specialization&order_dir=<?php echo ($orderBy == 'specialization' && $orderDir == 'ASC') ? 'DESC' : 'ASC'; ?>"><i class="fas fa-sort"></i></a></th>
                        <th>المؤهل <a href="?order_by=qualification&order_dir=<?php echo ($orderBy == 'qualification' && $orderDir == 'ASC') ? 'DESC' : 'ASC'; ?>"><i class="fas fa-sort"></i></a></th>
                        <th>تاريخ الإرسال <a href="?order_by=created_at&order_dir=<?php echo ($orderBy == 'created_at' && $orderDir == 'ASC') ? 'DESC' : 'ASC'; ?>"><i class="fas fa-sort"></i></a></th>
                        <th>كود الخصم <a href="?order_by=discount_code&order_dir=<?php echo ($orderBy == 'discount_code' && $orderDir == 'ASC') ? 'DESC' : 'ASC'; ?>"><i class="fas fa-sort"></i></a></th>
                        <th>التفاصيل</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($messages) {
                        foreach ($messages as $message) {
                    ?>
                            <tr data-message-id="<?php echo $message['id']; ?>">
                                <td>
                                    <input type="checkbox" name="selected_messages[]" value="<?php echo $message['id']; ?>">
                                </td>
                                <td><?php echo htmlentities($message['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlentities($message['experience'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlentities($message['specialization'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlentities($message['qualification'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo date('d-m-Y / H:i', strtotime($message['created_at'])); ?></td>

                                <td>
                                    <?php if ($message['discount_code']): ?>
                                        <span class="badge bg-success">يوجد كود خصم</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">لا يوجد كود خصم</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="message_details.php?id=<?php echo $message['id']; ?>" class="btn btn-primary btn-sm">عرض التفاصيل</a>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = ($message['is_handled'] == 1) ? 'badge bg-success' : 'badge bg-warning';
                                    $statusText = ($message['is_handled'] == 1) ? 'مُعالَج' : 'غير مُعالَج';
                                    ?>
                                    <span class="<?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                                </td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </form>
</div>


<script>
    // JavaScript for Select All checkbox
    document.getElementById('select_all').addEventListener('change', function() {
        var checkboxes = document.querySelectorAll('input[name="selected_messages[]"]');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = this.checked;
        }, this);
    });
</script>

<?php
include 'dashboard_footer.php';
?>
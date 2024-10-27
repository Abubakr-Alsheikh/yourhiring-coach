<?php
include '../includes/db.php';
include 'dashboard_header.php';

// Handle marking messages as handled
if (isset($_POST['mark_as_handled'])) {
    $messageId = $_POST['message_id'];
    $query = "UPDATE messages SET is_handled = 1 WHERE id = ?";
    $params = [$messageId];

    if (executeQuery($query, $params)) {
        // Optionally, you can add a success message or redirect
        $_SESSION['flash_message'] = "<p class='text-success text-center lead'>تم تحديث حالة التعامل</p>";
        header("Location: messages.php");
        exit;
    } else {
        echo "<p class='text-danger'>حدث خطأ أثناء تحديث حالة الرسالة.</p>";
    }
}

// Get sorting parameters from URL
$orderBy = isset($_GET['order_by']) ? $_GET['order_by'] : 'experience'; // Default order by experience
$orderDir = isset($_GET['order_dir']) ? $_GET['order_dir'] : 'DESC';

// Build the SQL query for sorting
$query = "SELECT * FROM messages WHERE is_handled = 0 ORDER BY $orderBy $orderDir";

// Fetch messages based on sorting parameters
$messages = getResults($query);

?>

<div class="container mt-3" dir="rtl">
    <h1 class="text-center display-3 mb-4">رسائل المستخدمين</h1>

    <div class="d-flex justify-content-end my-2">
        <a href="all_messages.php" class="btn btn-secondary col-md-2 col-12">عرض جميع الرسائل</a>
    </div>

    <div class="overflow-auto">
        <table class="table table-bordered" id="messagesTable">
            <thead>
                <tr>
                    <th scope="col">الاسم <a href="?order_by=name&order_dir=<?php echo ($orderBy == 'name' && $orderDir == 'ASC') ? 'DESC' : 'ASC'; ?>"><i class="fas fa-sort"></i></a></th>
                    <th scope="col">سنوات الخبرة <a href="?order_by=experience&order_dir=<?php echo ($orderBy == 'experience' && $orderDir == 'ASC') ? 'DESC' : 'ASC'; ?>"><i class="fas fa-sort"></i></a></th>
                    <th scope="col">التخصص <a href="?order_by=specialization&order_dir=<?php echo ($orderBy == 'specialization' && $orderDir == 'ASC') ? 'DESC' : 'ASC'; ?>"><i class="fas fa-sort"></i></a></th>
                    <th scope="col">المؤهل <a href="?order_by=qualification&order_dir=<?php echo ($orderBy == 'qualification' && $orderDir == 'ASC') ? 'DESC' : 'ASC'; ?>"><i class="fas fa-sort"></i></a></th>
                    <th>تاريخ الإرسال <a href="?order_by=created_at&order_dir=<?php echo ($orderBy == 'created_at' && $orderDir == 'ASC') ? 'DESC' : 'ASC'; ?>"><i class="fas fa-sort">

                    <th>كود الخصم <a href="?order_by=discount_code&order_dir=<?php echo ($orderBy == 'discount_code' && $orderDir == 'ASC') ? 'DESC' : 'ASC'; ?>"><i class="fas fa-sort"></i></a></th>
                    <th scope="col">التفاصيل</th>
                    <th scope="col">إخفاء الرسالة</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($messages) {
                    foreach ($messages as $message) {
                ?>
                        <tr data-message-id="<?php echo $message['id']; ?>">
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
                                <form method="post" action="">
                                    <input type="hidden" name="message_id" value="<?php echo htmlentities($message['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <button type="submit" name="mark_as_handled" class="btn btn-success btn-sm">تم التعامل معه</button>
                                </form>
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
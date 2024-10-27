<?php
include '../includes/db.php';
include 'dashboard_header.php';

// Handle toggle visibility
if (isset($_POST['toggle_visibility'])) {
    $serviceId = $_POST['service_id'];
    $isHidden = getResults("SELECT is_hidden FROM services WHERE id = ?", [$serviceId])[0]['is_hidden'];
    $newIsHidden = ($isHidden == 1) ? 0 : 1;

    $query = "UPDATE services SET is_hidden = ? WHERE id = ?";
    $params = [$newIsHidden, $serviceId];

    if (executeQuery($query, $params)) {
        // Optionally, you can add a success message or redirect
        $_SESSION['flash_message'] = "<p class='text-success text-center lead'>تم تحديث حالة الرؤية</p>";
        header("Location: services.php");
        exit;
    } else {
        $_SESSION['flash_message'] = "<p class='text-danger'>حدث خطأ أثناء تحديث حالة الرؤية.</p>";
    }
}
?>

<div class="container mt-3" >
    <h1 class="text-center display-3 mb-4">إدارة الخدمات</h1>
    <?php
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        echo $message;
    }
    ?>
    <a href="add_service.php" class="btn btn-secondary lead mb-3 col-12 col-md-2">إضافة خدمة</a>
    <div class="overflow-auto">
    <table class="table table-bordered" dir="rtl">
        <thead>
            <tr>
                <th>العنوان</th>
                <th>الصورة</th>
                <th>الوصف</th>
                <th>السعر</th>
                <th>الخصم</th>
                <th>مخفي</th>
                <th>التعديل</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $services = getResults("SELECT * FROM services order by is_hidden");
            if ($services) {
                foreach ($services as $service) {
            ?>
                    <tr>
                        <td><?php echo $service['title']; ?></td>
                        <td><img src="../images/<?php echo $service['image']; ?>" alt="Event Image" width="50"></td>
                        <td><?php echo substr($service['description'], 0, 50) . ( strlen($service['description']) > 50 ? "..." : ""); ?></td>
                        <td><?php echo $service['price']; ?></td>
                        <td><?php echo $service['discount']; ?></td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
                                <button type="submit" name="toggle_visibility" class="btn btn-sm <?php echo ($service['is_hidden'] == 1) ? 'btn-success' : 'btn-warning'; ?>">
                                    <?php echo ($service['is_hidden'] == 1) ? 'إظهار' : 'إخفاء'; ?>
                                </button>
                            </form>
                        </td>
                        <td>
                            <a href="edit_service.php?id=<?php echo $service['id']; ?>" class="btn btn-sm btn-primary">تعديل</a>
                            <a href="delete_service.php?id=<?php echo $service['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذه الخدمة؟')">حذف</a>
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
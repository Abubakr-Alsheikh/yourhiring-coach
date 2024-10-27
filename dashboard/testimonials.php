<?php
include '../includes/db.php';
include 'dashboard_header.php';
?>


<div class="container mt-3">
    <div class="col-md-12">
        <h1 class="text-center display-3 mb-4">إدارة أراء العملاء</h1>
        <?php
        if (isset($_SESSION['flash_message'])) {
            $message = $_SESSION['flash_message'];
            unset($_SESSION['flash_message']);
            echo $message;
        }
        ?>
        <a href="add_testimonial.php" class="btn btn-secondary lead mb-3 col-12 col-md-2">إضافة رأي</a>
        <div class="overflow-auto">
            <table class="table table-bordered" dir="rtl">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>الرأي</th>
                        <th>التعديل</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $testimonials = getResults("SELECT * FROM testimonials");
                    if ($testimonials) {
                        foreach ($testimonials as $testimonial) {
                    ?>
                            <tr>
                                <td><?php echo $testimonial['name']; ?></td>
                                <td><?php echo substr($testimonial['testimonial'], 0, 50) . ( strlen($testimonial['testimonial']) > 50 ? "..." : ""); ?></td>
                                <td>
                                    <a href="edit_testimonial.php?id=<?php echo $testimonial['id']; ?>" class="btn btn-sm btn-primary">تعديل</a>
                                    <a href="delete_testimonial.php?id=<?php echo $testimonial['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا الرأي؟')">حذف</a>
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
</div>


<?php
include 'dashboard_footer.php';
?>
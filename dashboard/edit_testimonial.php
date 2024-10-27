<?php

include '../includes/db.php';
include 'dashboard_header.php';

$testimonialId = isset($_GET['id']) ? $_GET['id'] : null;

if ($testimonialId) {
    $testimonial = getResults("SELECT * FROM testimonials WHERE id = ?", [$testimonialId])[0];

    if ($testimonial) {
        if (isset($_POST['update_testimonial'])) {
            $name = $_POST['name'];
            $testimonialText = $_POST['testimonial'];

            // Update the testimonial details in the database
            $query = "UPDATE testimonials SET name = ?, testimonial = ? WHERE id = ?";
            $params = [$name, $testimonialText, $testimonialId];

            if (executeQuery($query, $params)) {
                $_SESSION['flash_message'] = "<p class='text-success text-center lead'>تم تحديث الرأي بنجاح.</p>";
            } else {
                $_SESSION['flash_message'] = "<p class='text-danger'>حدث خطأ أثناء تحديث الرأي.</p>";
            }
            header("Location: testimonials.php");
            exit;
        }
        ?>

        <div class="container mt-5 col-12 col-md-6">
            <h2 class="text-center display-4 mb-4">تعديل الرأي</h2>
            <form method="post" dir="rtl">
                <div class="mb-3">
                    <label for="name" class="form-label">الاسم</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $testimonial['name']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="testimonial" class="form-label">الرأي</label>
                    <textarea class="form-control" id="testimonial" name="testimonial" rows="3" required><?php echo $testimonial['testimonial']; ?></textarea>
                </div>
                <button type="submit" name="update_testimonial" class="btn btn-primary">تحديث</button>
            </form>
        </div>

        <?php
    } else {
        echo "<p class='text-danger'>لا يوجد رأي بهذا الرقم.</p>";
    }
} else {
    echo "<p class='text-danger'>الرجاء اختيار رأي.</p>";
}

include 'dashboard_footer.php';
?>
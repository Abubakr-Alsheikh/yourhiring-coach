<?php

include '../includes/db.php';
include 'dashboard_header.php';

if (isset($_POST['add_testimonial'])) {
    $name = $_POST['name'];
    $testimonial = $_POST['testimonial'];

    // Insert the new testimonial into the database
    $query = "INSERT INTO testimonials (name, testimonial) VALUES (?, ?)";
    $params = [$name, $testimonial];

    if (executeQuery($query, $params)) {
        $_SESSION['flash_message'] = "<p class='text-success text-center lead'>تم إضافة الرأي بنجاح.</p>";
    } else {
        $_SESSION['flash_message'] = "<p class='text-danger'>حدث خطأ أثناء إضافة الرأي.</p>";
    }
    header("Location: testimonials.php");
    exit;
}
?>

<div class="container mt-5 col-12 col-md-6">
    <h2 class="text-center display-4 mb-4">إضافة رأي جديد</h2>
    <form method="post" dir="rtl">
        <div class="mb-3">
            <label for="name" class="form-label">الاسم</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="testimonial" class="form-label">الرأي</label>
            <textarea class="form-control" id="testimonial" name="testimonial" rows="3" required></textarea>
        </div>
        <button type="submit" name="add_testimonial" class="btn btn-primary">إضافة</button>
    </form>
</div>

<?php

include 'dashboard_footer.php';
?>
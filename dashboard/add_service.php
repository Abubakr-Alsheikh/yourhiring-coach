<?php
include '../includes/db.php';
include 'dashboard_header.php';

if (isset($_POST['add_service'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $discount = $_POST['discount'];
    $image = $_FILES['image']['name'];
    $imagePath = "../images/" . $image; // Assuming images are stored in the 'images' folder
    
    // Move the uploaded image to the images folder
    if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
        // Insert the new service into the database
        $query = "INSERT INTO services (title, description, image, price, discount) VALUES (?, ?, ?, ?, ?)";
        $params = [$title, $description, $image, $price, $discount];

        if (executeQuery($query, $params)) {
            $_SESSION['flash_message'] =  "<p class='text-success text-center lead'>تم إضافة الخدمة بنجاح.</p>";
        } else {
            $_SESSION['flash_message'] =  "<p class='text-danger'>حدث خطأ أثناء إضافة الخدمة.</p>";
        }
    } else {
        $_SESSION['flash_message'] =  "<p class='text-danger'>حدث خطأ أثناء تحميل الصورة.</p>";
    }
    header("Location: services.php");
    exit;
}
?>

<div class="container mt-5 col-md-6 col-12" dir="rtl">
    <h2>إضافة خدمة جديدة</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">عنوان الخدمة</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">وصف الخدمة</label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">صورة الخدمة</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">سعر الخدمة</label>
            <input type="number" class="form-control" id="price" name="price" required>
        </div>
        <div class="mb-3">
            <label for="discount" class="form-label">خصم الخدمة</label>
            <input type="number" class="form-control" id="discount" name="discount">
        </div>
        <button type="submit" name="add_service" class="btn btn-primary">إضافة</button>
        <a type="submit" href="services.php" class="btn btn-secondary">الرجوع</a>
    </form>
</div>

<?php
include 'dashboard_footer.php';
?>
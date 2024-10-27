<?php
include '../includes/db.php';
include 'dashboard_header.php';

if (isset($_POST['add_event'])) {
    $text = $_POST['htmlcontent'];
    $serviceId = $_POST['service_id'];
    $image = null; // Initialize image to null

    // Check if an image was uploaded
    if (isset($_FILES['image']) && $_FILES['image']['name'] != "") {
        $image = $_FILES['image']['name'];
        $imagePath = "../images/" . $image;

        // Move the uploaded image to the images folder
        if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            // Image upload successful
        } else {
            $_SESSION['flash_message'] = "<p class='text-danger'>حدث خطأ أثناء تحميل الصورة.</p>";
        }
    }

    // Insert the new event into the database (with or without image)
    $query = "INSERT INTO events (image, text, service_id) VALUES (?, ?, ?)";
    $params = [$image, $text, $serviceId];

    if (executeQuery($query, $params)) {
        $_SESSION['flash_message'] = "<p class='text-success text-center lead'>تم إضافة الاعلان بنجاح.</p>";
    } else {
        $_SESSION['flash_message'] = "<p class='text-danger'>حدث خطأ أثناء إضافة الاعلان.</p>";
    }
    header("Location: events.php");
    exit;
}

// Fetch services from the database to populate the dropdown
$servicesQuery = "SELECT id, title FROM services";
$services = executeQuery($servicesQuery); 
?>

<div class="container mt-5 col-12 col-md-6">
    <h2 class="text-center display-4 mb-4">إضافة الاعلان جديد</h2>
    <form method="post" enctype="multipart/form-data" dir="rtl">
        <div class="mb-3 border p-3 rounded-4">
            <label for="service_id" class="form-label lead">خدمة الاعلان</label>
            <select class="form-control" id="service_id" name="service_id">
                <option value="">اختر الخدمة</option>
                <?php foreach ($services as $service) : ?>
                    <option value="<?php echo $service['id']; ?>"><?php echo $service['title']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3 border p-3 rounded-4">
            <label for="image" class="form-label lead">صورة الاعلان</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
        </div>
        <div class="mb-3 border p-3 rounded-4">
            <label for="text" class="form-label lead">نص الاعلان</label>
            <div ng-app="textAngularTest" ng-controller="wysiwygeditor" class="container app" id="text">
                <div text-angular="text-angular" name="htmlcontent" ng-model="htmlcontent" ta-disabled='disabled'></div>
            </div>
        </div>

        <button type="submit" name="add_event" class="btn btn-primary mb-5">إضافة</button>
    </form>
</div>


<?php
include 'dashboard_footer.php';
?>
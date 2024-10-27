<?php
include '../includes/db.php';
include 'dashboard_header.php';

$serviceId = isset($_GET['id']) ? $_GET['id'] : null;

if ($serviceId) {
    $service = getResults("SELECT * FROM services WHERE id = ?", [$serviceId])[0];

    if ($service) {
        if (isset($_POST['update_service'])) {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $discount = $_POST['discount'];
            $oldImage = $service['image']; // Store the old image name

            // Handle image upload (if a new image is provided)
            if (isset($_FILES['image']) && $_FILES['image']['name'] != "") {
                $image = $_FILES['image']['name'];
                $imagePath = "../images/" . $image; // Same path as in move_uploaded_file()

                // Move the uploaded image to the images folder
                if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                    // Delete the old image if a new one is uploaded
                    if ($oldImage != "" && file_exists("../images/" . $oldImage)) {
                        unlink("../images/" . $oldImage);  // Use the same $imagePath variable 
                    }
                } else {
                    $_SESSION['flash_message'] = "<p class='text-danger'>حدث خطأ أثناء تحميل الصورة.</p>";
                    $image = $oldImage; // Use the old image if the upload fails
                }
            } else {
                $image = $oldImage; // Use the old image if no new image is uploaded
            }

            // Update the service details in the database
            $query = "UPDATE services SET title = ?, description = ?, image = ?, price = ?, discount = ? WHERE id = ?";
            $params = [$title, $description, $image, $price, $discount, $serviceId];

            if (executeQuery($query, $params)) {
                $_SESSION['flash_message'] = "<p class='text-success text-center lead'>تم تحديث الخدمة بنجاح.</p>";
            } else {
                $_SESSION['flash_message'] = "<p class='text-danger'>حدث خطأ أثناء تحديث الخدمة.</p>";
            }

            header("Location: services.php");
            exit;
        }
?>

        <div class="container mt-5 col-md-6 col-12" dir="rtl">
            <h2>تعديل الخدمة</h2>
            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">عنوان الخدمة</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo $service['title']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">وصف الخدمة</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required><?php echo $service['description']; ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">صورة الخدمة</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    <?php if ($service['image'] != "") { ?>
                        <img src="../images/<?php echo $service['image']; ?>" alt="Image" class="mt-3 text-center" width="300">
                    <?php } ?>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">سعر الخدمة</label>
                    <input type="number" class="form-control" id="price" name="price" value="<?php echo $service['price']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="discount" class="form-label">خصم الخدمة</label>
                    <input type="number" class="form-control" id="discount" name="discount" value="<?php echo $service['discount']; ?>">
                </div>
                <button type="submit" name="update_service" class="btn btn-primary mb-5">تحديث</button>
                <a href="services.php" class="btn btn-secondary mb-5">الرجوع</a>
            </form>
        </div>

<?php
    } else {
        $_SESSION['flash_message'] = "<p class='text-danger'>لا توجد خدمة بهذا الرقم.</p>";
        header("Location: services.php");
        exit;
    }
} else {
    $_SESSION['flash_message'] = "<p class='text-danger'>الرجاء اختيار خدمة.</p>";
    header("Location: services.php");
    exit;
}

include 'dashboard_footer.php';
?>
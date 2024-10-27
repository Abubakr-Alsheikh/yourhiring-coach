<?php
include 'includes/db.php'; // Include the database connection file
include 'includes/header.php'; // Include the header file

// Start the session
session_start();

// Handle form submission
if (isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $experience = trim($_POST['experience']);
    $specialization = trim($_POST['specialization']);
    $qualification = trim($_POST['qualification']);
    $message = trim($_POST['message']);

    echo "something";
    // Validation
    $errors = [];

    if (empty($name)) {
        $errors[] = "الرجاء إدخال اسمك";
    }
    if (empty($phone)) {
        $errors[] = "الرجاء إدخال رقم هاتفك";
    } else if (!preg_match('/^[0-9]+$/', $phone)) { // Basic phone number validation
        $errors[] = "رقم الهاتف غير صالح";
    }
    if (empty($experience)) {
        $errors[] = "الرجاء إدخال عدد سنوات الخبرة";
    } else if (!is_numeric($experience)) {
        $errors[] = "عدد سنوات الخبرة يجب أن يكون رقمًا";
    }
    if (empty($specialization)) {
        $errors[] = "الرجاء إدخال تخصصك";
    }
    if (empty($qualification) || $qualification == "اختر مؤهلك") {
        $errors[] = "الرجاء إدخال مؤهلك";
    }
    if (empty($message)) {
        $errors[] = "الرجاء إدخال الرسالة";
    }

    $discountCode = trim($_POST['discount']);
    $serviceId = isset($_GET['service_id']) ? $_GET['service_id'] : null;

    // If there are no errors, insert the data
    if (empty($errors)) {
        // Get service_id from the URL

        // Insert data into the messages table
        $query = "INSERT INTO messages (name, phone, experience, specialization, qualification, message, service_id, discount_code) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $params = [$name, $phone, $experience, $specialization, $qualification, $message, $serviceId, $discountCode];

        if (executeQuery($query, $params)) {
            // Redirect to the success page
            header("Location: message-sent-success.php");
            exit;
        } else {
            $_SESSION['flash_message'] = "<p class='text-danger text-center lead'>حدث خطأ أثناء إرسال الرسالة. يرجى المحاولة مرة أخرى.</p>";
        }
    } else {
        // Display error messages
        $_SESSION['flash_errors'] = $errors;
    }

    header("Location: send-information.php?service_id=$serviceId");
    exit;
}

?>

<div class="container col-md-6 col-12 min-vh-100 d-flex justify-content-center align-items-center" dir="rtl">
    <div class="card" style="flex: 1;">
        <div class="card-body">
            <h2 class="card-title text-center">إرسال معلوماتك لتواصل معك</h2>
            <form method="post">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">الاسم <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="أدخل اسمك" required>
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label">رقم الجوال <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="أدخل رقم جوالك" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="experience" class="form-label">عدد سنوات الخبرة <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="experience" name="experience" placeholder="أدخل عدد سنوات خبرتك" required>
                </div>
                <div class="mb-3">
                    <label for="specialization" class="form-label">التخصص <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="specialization" name="specialization" placeholder="أدخل تخصصك" required>
                </div>
                <div class="mb-3">
                    <label for="qualification" class="form-label">المؤهل <span class="text-danger">*</span></label>
                    <select class="form-control" id="qualification" name="qualification" required>
                        <option value="">اختر مؤهلك</option>
                        <option value="ثانوي">ثانوي</option>
                        <option value="بكالوريوس">بكالوريوس</option>
                        <option value="ماجستير">ماجستير</option>
                        <option value="دكتوراه او اعلى">دكتوراه او اعلى</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="discount" class="form-label">كود الخصم</label>
                    <input type="text" class="form-control" id="discount" name="discount" placeholder="أدخل كود الخصم إن وجد">
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">معلومات إضافية <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="message" name="message" rows="3" placeholder="أدخل معلوماتك الإضافية" required></textarea>
                </div>
                <?php
                if (isset($_SESSION['flash_message'])) {
                    $message = $_SESSION['flash_message'];
                    unset($_SESSION['flash_message']);
                    echo $message;
                }

                if (isset($_SESSION['flash_errors'])) {
                    $errors = $_SESSION['flash_errors'];
                    unset($_SESSION['flash_errors']);
                    foreach ($errors as $error) {
                        echo "<p class='text-danger text-center lead'>$error</p>";
                    }
                }
                ?>
                <button type="submit" name="submit" class="btn btn-dark">إرسال</button>
            </form>

        </div>
    </div>
</div>

<?php
include 'includes/footer.php'; // Include the footer file
?>
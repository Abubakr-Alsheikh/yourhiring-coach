<?php
include 'includes/db.php'; // Include the database connection file
include 'includes/header.php'; // Include the header file
?>

<div class="container min-vh-100 d-flex justify-content-center align-items-center mt-3" >
    <?php
    // Get the service ID from the URL
    $serviceId = isset($_GET['id']) ? $_GET['id'] : null;

    if ($serviceId) {
        // Fetch service details from the database
        $service = getResults("SELECT * FROM services WHERE id = ?", [$serviceId])[0];

        if ($service) {
            ?>
            <div class="row" >
                <div class="col-md-6">
                    <img src="images/<?php echo $service['image']; ?>" class="img-fluid rounded-4" alt="<?php echo $service['title']; ?>" style="width: 1000px; max-height: 600px;" >
                </div>
                <div class="col-md-6 mt-4" dir="rtl">
                    <h2 class="text-center display-3"><?php echo $service['title']; ?></h2>
                    <?php
                    // Display price and discount information
                    if ($service['discount'] > 0) {
                        ?>
                        <p class="lead">
                            السعر الأصلي: <del><?php echo $service['price'] - 0.01; ?> ريال</del>
                            <br>
                            السعر المخفض: <?php echo $service['price'] - $service['discount'] - 0.01; ?> ريال
                        </p>
                        <?php
                    } else {
                        ?>
                        <p class="lead">السعر: <?php echo $service['price']; ?> ريال</p>
                        <?php
                    }
                    ?>
                    <p class="lead"><?php echo $service['description']; ?></p>
                    
                <a href="send-information.php?service_id=<?php echo $service['id']; ?>" class="btn btn-dark mt-3 ">تواصل معنا</a> 
                </div>
            </div>
            <?php
        } else {
            echo "<p>لا توجد خدمة بهذا الرقم.</p>";
        }
    } else {
        echo "<p>الرجاء اختيار خدمة.</p>";
    }
    ?>
</div>

<?php
include 'includes/footer.php'; // Include the footer file
?>
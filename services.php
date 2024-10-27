<?php
include 'includes/db.php'; // Include the database connection file
include 'includes/header.php'; // Include the header file
?>

<div class="container">
    <!-- Services Section -->
    <h2 class="text-center mt-5 display-5" id="services">صفحة الخدمات</h2>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mt-4" dir="rtl">
        <?php
        $services = getResults("SELECT * FROM services WHERE is_hidden = 0 ORDER BY id DESC");
        if ($services) {
            foreach ($services as $service) {
        ?>
                <div class="col">
                    <div class="card">
                        <img src="images/<?php echo $service['image']; ?>" class="card-img-top" alt="<?php echo $service['title']; ?>" style="height: 400px;">
                        <div class="card-body">
                            <h5 class="card-title display-6"><?php echo $service['title']; ?></h5>
                            <p class="card-text lead"><?php echo substr($service['description'], 0, 100) . (strlen($service['description']) > 100 ? "..." : ""); ?></p>
                            <a href="service-detail.php?id=<?php echo $service['id']; ?>" class="btn btn-dark">طلب الخدمة</a>
                        </div>
                    </div>
                </div>
        <?php
            }
        }
        ?>
    </div>
</div>


<?php
include 'includes/footer.php'; // Include the footer file
?>
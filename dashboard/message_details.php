<?php
include '../includes/db.php';
include 'dashboard_header.php';

$messageId = isset($_GET['id']) ? $_GET['id'] : null;

if ($messageId) {
    $message = getResults("SELECT * FROM messages WHERE id = ?", [$messageId])[0];
    if ($message) {
        
        // Get the service name for the message
        $serviceId = $message['service_id']; // Assuming you have a 'service_id' column in the 'messages' table
        $service = getResults("SELECT title FROM services WHERE id = ?", [$serviceId]);
        if (!empty($service)) {
            $serviceName = $service[0]['title']; // Fetch the service title
        } else {
            $serviceName = "";
        }
        ?>
        <div class="container mt-3 " dir="rtl">
            <h2 class="display-3 mb-5 text-center">تفاصيل الرسالة</h2>
            <p class="lead"><strong>الاسم:</strong> <?php echo htmlentities($message['name'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p class="lead"><strong>تاريخ الارسال:</strong> <?php echo date('d-m-Y / H:i', strtotime($message['created_at'])); ?></p>
            <?php if ($serviceName) { ?>
                <p class="lead"><strong>الخدمة المطلوبة:</strong> <?php echo htmlentities($serviceName, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php } else {?>
                <p class="lead"><strong>الخدمة المطلوبة:</strong> <span class='text-danger'>لا توجد هذه الخدمة.</span></p>
            <?php } ?>
            <p class="lead"><strong>سنوات الخبرة:</strong> <?php echo htmlentities($message['experience'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p class="lead"><strong>التخصص:</strong> <?php echo htmlentities($message['specialization'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p class="lead"><strong>المؤهل:</strong> <?php echo htmlentities($message['qualification'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p class="lead"><strong>رقم الجوال:</strong> <?php echo htmlentities($message['phone'], ENT_QUOTES, 'UTF-8'); ?></p>
            <?php if ($message['discount_code']) : ?>
                <p class="lead"><strong>كود الخصم:</strong> <?php echo htmlentities($message['discount_code'], ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>
            <p class="lead"><strong>الرسالة:</strong> <?php echo htmlentities($message['message'], ENT_QUOTES, 'UTF-8'); ?></p>
            <a href="messages.php" class="btn btn-secondary col-md-2 col-12 lead">الرجوع</a> 
        </div>
        <?php
    } else {
        echo "<p class='text-danger'>لا توجد رسالة بهذا الرقم.</p>";
    }
} else {
    echo "<p class='text-danger'>الرجاء اختيار رسالة.</p>";
}

include 'dashboard_footer.php';
?>
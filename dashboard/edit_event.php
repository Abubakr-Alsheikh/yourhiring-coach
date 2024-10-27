<?php
include '../includes/db.php';
include 'dashboard_header.php';

$eventId = isset($_GET['id']) ? $_GET['id'] : null;

if ($eventId) {
  $event = getResults("SELECT * FROM events WHERE id = ?", [$eventId])[0];

  if ($event) {
    if (isset($_POST['update_event'])) {
      $oldImage = $event['image']; // Store the old image name
      $text = $_POST['htmlcontent'];
      $serviceId = $_POST['service_id'];

      // Handle image upload (if a new image is provided)
      if (isset($_FILES['image']) && $_FILES['image']['name'] != "") {
        $image = $_FILES['image']['name'];
        $imagePath = "../images/" . $image;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
          // Delete the old image if a new one is uploaded 
          if ($oldImage != "" && file_exists("../images/" . $oldImage)) { 
            unlink("../images/" . $oldImage); 
          }
        } else {
          $_SESSION['flash_message'] = "<p class='text-danger'>حدث خطأ أثناء تحميل الصورة.</p>";
          $image = $oldImage; // Use the old image if the upload fails
        }
      } else {
        $image = $oldImage; // Use the old image if no new image is uploaded
      }

      // Update the event details in the database
      $query = "UPDATE events SET image = ?, text = ?, service_id = ? WHERE id = ?";
      $params = [$image, $text, $serviceId, $eventId];


      if (executeQuery($query, $params)) {
        $_SESSION['flash_message'] = "<p class='text-success text-center lead'>تم تحديث الاعلان بنجاح.</p>";
      } else {
        $_SESSION['flash_message'] = "<p class='text-danger'>حدث خطأ أثناء تحديث الاعلان.</p>";
      }
      header("Location: events.php");
      exit;
    }


    // Fetch services from the database to populate the dropdown
    $servicesQuery = "SELECT id, title FROM services";
    $services = executeQuery($servicesQuery);

?>

    <div class="container mt-5 col-12 col-md-6">
      <h2 class="text-center display-4 mb-4">تعديل الاعلان</h2>
      <form method="post" enctype="multipart/form-data" dir="rtl">

        <div class="mb-3 border p-3 rounded-4">
          <label for="service_id" class="form-label lead">خدمة الاعلان</label>
          <select class="form-control" id="service_id" name="service_id">
            <option value="">اختر الخدمة</option>
            <?php foreach ($services as $service) : ?>
              <option value="<?php echo $service['id']; ?>" <?php if ($service['id'] == $event['service_id']) echo 'selected'; ?>><?php echo $service['title']; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3 border p-3 rounded-4">
          <label for="image" class="form-label lead">صورة الاعلان</label>
          <input type="file" class="form-control mb-3" id="image" name="image" accept="image/*">
          <?php if ($event['image'] != "") { ?>
            <img src="../images/<?php echo $event['image']; ?>" alt="Image" width="300">
          <?php } ?>
        </div>
        <div class="mb-3 border p-3 rounded-4">
          <label for="text" class="form-label lead">نص الاعلان</label>
          <div ng-app="textAngularTest" ng-controller="wysiwygeditor" class="container app" id="text">
            <div text-angular="text-angular" name="htmlcontent" ng-model="htmlcontent" ta-disabled='disabled'></div>
          </div>
        </div>
        <button type="submit" name="update_event" class="btn btn-primary mb-5">تحديث</button>
      </form>

  <?php
  } else {
    $_SESSION['flash_message'] = "<p class='text-danger'>لا يوجد حدث بهذا الرقم.</p>";
    header("Location: events.php");
    exit;
  }
} else {
  $_SESSION['flash_message'] = "<p class='text-danger'>الرجاء اختيار حدث.</p>";
  header("Location: events.php");
  exit;
}

// include 'dashboard_footer.php';
  ?>

  <script src='https://ajax.googleapis.com/ajax/libs/angularjs/1.2.4/angular.min.js'></script>
  <script src='https://ajax.googleapis.com/ajax/libs/angularjs/1.2.4/angular-sanitize.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/textAngular/1.1.2/textAngular.min.js'></script>
  <script>
    angular.module("textAngularTest", ['textAngular']);

    function wysiwygeditor($scope) {
      $scope.orightml = '<?php echo $event['text']; ?>';
      $scope.htmlcontent = $scope.orightml;
      $scope.disabled = false;
    };
  </script>
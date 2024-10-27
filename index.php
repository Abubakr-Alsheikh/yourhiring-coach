<?php
include 'includes/db.php'; // Include the database connection file
include 'includes/header.php'; // Include the header file
?>

<div class="overflow-hidden">
  <?php
  $heroContent = getResults("SELECT * FROM hero_content WHERE id = 1")[0]; // Assuming only one row for hero content
  ?>
  <div class="jumbotron jumbotron-fluid mb-0" id="home">
    <a class="" href="services.php" role="button">
      <img src="images/background-image-hero.jpg" alt="" class="w-100">
    </a>
    <div class="container d-none">
      <div class="row justify-content-center align-items-start">
        <div class="col-md-10 col-lg-11 mt-5 text-end">
          <h1 class="display-3 mb-2 fw-bolder"><?php echo $heroContent['title']; ?></h1>
          <p class="lead mb-4 my-4 fw-bold" style="font-size: 24px;"><?php echo $heroContent['subtitle']; ?></p>
          <?php if ($heroContent['button_text']) : ?>
            <p class="lead">
              <a class="btn btn-dark btn-lg border-white rounded-pill px-4 py-2" href="<?php echo $heroContent['button_link']; ?>" role="button"><?php echo $heroContent['button_text']; ?></a>
            </p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <!-- Events Swiper -->
  <section id="events">
    <div class="swiper mySwiper" style="height: 300px;">
      <div class="swiper-wrapper">
        <?php
        // $events = getResults("SELECT e.*, s.title AS service_title FROM events e LEFT JOIN services s ON e.id = s.id WHERE e.is_hidden = 0");
        $events = getResults("SELECT e.*, s.title AS service_title FROM events e LEFT JOIN services s ON e.service_id = s.id WHERE e.is_hidden = 0 ORDER BY e.id DESC");
        if ($events) {
          foreach ($events as $event) {
        ?>
            <div class="swiper-slide ">
              <a href="service-detail.php?id=<?php echo $event['service_id']; ?>" style="text-decoration: none;" class="text-dark">
                <?php if ($event['image']) : ?>
                  <img src="images/<?php echo $event['image']; ?>" class="img-fluid rounded-3" style="height: 200px;">
                <?php endif; ?>
                <?php if ($event['text']) : ?>
                  <p class="text-center mt-2">
                    <small><?php echo $event['text']; ?></small>
                  </p>
                <?php endif; ?>
              </a>
            </div>
        <?php
          }
        }
        ?>
      </div>
      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
    </div>
  </section>
  <div class="container">



    <!-- Services Section -->
    <h2 class="text-center mt-5 display-5" id="services">أفضل الخدمات</h2>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mt-4" dir="rtl">
      <?php
      $services = getResults("SELECT * FROM services WHERE is_hidden = 0 ORDER BY id DESC");
      if ($services) {
        foreach ($services as $service) {
      ?>
          <div class="col">
            <div class="card">
              <img src="images/<?php echo $service['image']; ?>" class="card-img-top" alt="<?php echo $service['title']; ?>" style="height: 350px;">
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



    <!-- testimonials Carousel -->
    <section id="sec-testimonials" class="sec-testimonials my-5 py-5" dir="rtl">
      <h1 class="h2 mb-5 text-center display-5" id="testimonials">أراء العملاء</h1>

      <div id="testimonialseDark" class="carousel carousel-dark slide" data-bs-ride="carousel">
        <div class="carousel-inner" role="listbox">
          <?php
          $testimonials = getResults("SELECT * FROM testimonials");
          if ($testimonials) {
            $i = 0;
            foreach ($testimonials as $testimonial) {
              $activeClass = ($i == 0) ? 'active' : '';
          ?>
              <div class="carousel-item <?php echo $activeClass; ?>">
                <div class="row justify-content-center">
                  <div class="col-md-6 col-8">
                    <blockquote class="blockquote">
                      <small><?php echo $testimonial['testimonial']; ?></small>
                      <footer class="blockquote-footer mt-2"><?php echo $testimonial['name']; ?></footer>
                    </blockquote>
                  </div>
                </div>
              </div>
          <?php
              $i++;
            }
          }
          ?>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#testimonialseDark" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#testimonialseDark" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
    </section>
  </div>
</div>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Initialize Swiper -->
<script>
  var swiper = new Swiper(".mySwiper", {
    slidesPerView: 1,
    spaceBetween: 0,
    loop: true, // Enable looping
    autoplay: {
      delay: 3000, // Set the delay to 1 second (1000 milliseconds)
      disableOnInteraction: false, // Keep autoplay running even after user interactions
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
  });
</script>
<?php
include 'includes/footer.php'; // Include the footer file
?>
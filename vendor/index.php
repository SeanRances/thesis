<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> Home </title>

  <link rel="stylesheet" href="assets/css/homepage.css">
  <!-- FOR ANIMATION AOS -->
  <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
  </script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700|Yellowtail&display=swap" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
  </script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
  </script>

  <!-- CSS FOR PAGE -->

  <link rel="stylesheet" href="assets/css/index.css">

</head>

<body>
  <section>
    <nav class="navbar sticky navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light " id="ftco-navbar">
      <div class="container">
        <a class="navbar-brand" href="index.html"> <img src="assets/img/logo1.png" style="height:2.5rem;"> </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="fa fa-bars"></span> Menu
        </button>
        <div class="collapse navbar-collapse" id="ftco-nav">
          <ul class="navbar-nav m-auto">
            <li class="nav-item active"><a href="index.php" class="nav-link">Home</a></li>
            <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>


          </ul>
        </div>

      </div>
      <div>
        <ul class="navbar-nav mr-4">
          <li class="nav-item"><a href="login.php" class="nav-link">Login</a></li>
        </ul>
      </div>
    </nav>
    <!-- END nav -->
  </section>

  <!-- SECTION INTRO -->
  <section>
    <div class="overlay"></div>

    <div class="video-container">
      <video autoplay muted loop>
        <source src="assets/vid/vid-bg.mp4" />
      </video>
      <div class="caption m-5">
        <p class="video-caption text-center" data-aos="fade-up" data-aos-duration="3000">
          <br>
          We're paving the way <br>
          for a <strong> better </strong>, <br>
          more <strong> sustainable </strong> <br>
          healthcare system.
        </p>
      </div>
    </div>

  </section>
  <!-- ABOUT SECTION -->
  <section class="value-section py-5">

    <div class="container">

      <h6 class="value-title text-center"> Why Choose Change Healthcare?</h6>

      <div class="row py-4 text-center">
        <div class="col-sm-4">
          <img class="img-responsive" src="assets/img/1.jpg" alt="services" style="height:15rem;">
          <h3 class="value-sub py-4"> Unparalleled </h3>
          <p class="description"> Unique position at the center of the healthcare ecosystem </p>
        </div>
        <div class="col-sm-4 ">
          <img class="img-responsive" src="assets/img/comprehensive.jpg" alt="services" style="height:15rem; ">
          <h3 class="value-sub py-4"> Comprehensive </h3>
          <p class="description"> Serving payers, providers, labs, pharmacies, others.</p>
        </div>
        <div class="col-sm-4">
          <img class="img-responsive" src="assets/img/partnership.jpg" alt="services" style="height:15rem;">
          <h3 class="value-sub py-4"> Partnership </h3>
          <p class="description"> 700 industry-leading partners to drive innovation </p>
        </div>
      </div>

      <div class="row py-5">
        <div class="col text-center">
          <span class="value-text text-center" data-aos="fade-up" data-aos-duration="2000"> We provide creative
            solutions that aid in the improvement of the
            healthcare journey.</span>
          <div class="py-5">
            <form action="about.php" method="POST">
              <input type="submit" class="btn btn-danger" value="LEARN MORE" data-aos="fade-up" data-aos-duration="2000">
            </form>
          </div>
        </div>

      </div>
  </section>


  <section class="trivia-section">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <div class="card1" data-aos="fade-right" data-aos-duration="2000">
            <div class="wrapper" style="display: inline-flex;">
              <h2 class="card1-text"> 15 </h2>
              <h2 class="card1-text2"> Billion </h2>
            </div>
            <h2 class="card1-text3"> Change Healthcare operates a blockchain that handles 30 million transactions each day.
              The firm serves as a clearinghouse for insurance claims in the United States, and its clientele include both healthcare providers and insurers. </h2>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="card2" data-aos="fade-left" data-aos-duration="2000">
            <div class="wrapper" style="display: inline-flex;">
              <h2 class="card2-text"> 1 </h2>
              <h2 class="card2-text2"> Trillion </h2>
            </div>
            <h2 class="card2-text3"> In 2017, the company handled $1 trillion in claims. In comparison, healthcare spending in the United States was predicted to be $3.5 trillion in the same year,
              accounting for 18% of GDP. </h2>
          </div>
        </div>
      </div>
    </div>

    </div>

  </section>

  <section class="bg2-section">
    <section class="overlay2">
    </section>

  </section>

  <section class="contact-section">
    <div class="container">
      <div class="row">
        <div class="card card" data-aos="fade-up" data-aos-duration="2000">
          <div class="card-body">
            <h2 class="text py-6"> Through our single platform, we advocate innovation in order to create a more coordinated, more efficient, and increasingly collaborative healthcare system—one that enables operational efficiency,
              optimizes financial performance, and improves the healthcare experience. </h2>
          </div>
        </div>

      </div>
      <div class="row">
        <h2 class="text-title2"> Join our team and help inspire the future of healthcare technology. </h2>
      </div>
    </div>
  </section>


  <!-- Footer -->
  <footer class="text-center text-lg-start bg-light text-muted">
    <!-- Section: Social media -->
    <section class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom">
    </section>
    <!-- Section: Links  -->
    <section class="">
      <div class="container text-center text-md-start mt-5">
        <!-- Grid row -->
        <div class="row mt-3">
          <!-- Grid column -->
          <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
            <!-- Content -->
            <h6 class="text-uppercase fw-bold mb-4">
              <img src="assets/img/logo.png" style="height:2.5rem;">
            </h6>
          </div>
        </div>
      </div>
  </footer>
  <!-- Footer -->

  <script>
    window.onscroll = function() {
      scrollFunction()
    };

    function scrollFunction() {
      if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 60) {
        document.getElementById("ftco-navbar").style.top = "0";
      } else {

      }
    }
  </script>

  <!-- FOR AOS -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

  <!-- INITIALIZE AOS -->
  <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
  <script>
    AOS.init();
  </script>

</body>

</html>
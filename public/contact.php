<?php
session_start(); // Mulai sesi

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: index.html"); // Redirect ke halaman login jika belum login
    exit();
}

$username = $_SESSION['username']; // Ambil username dari sesi
?>

<!DOCTYPE html>
<html>

<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />

  <title>DWI JULIANISA</title>

  <!-- slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

  <!-- fonts style -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Poppins:400,600,700&display=swap" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />
</head>

<body class="sub_page">
  <div class="hero_area">
    <!-- header section strats -->
    <header class="header_section">
      <div class="container-fluid">
        <nav class="navbar navbar-expand-lg custom_nav-container">
        <a class="navbar-brand" href="dashboard.php">
           <span>
             <?php echo $username; ?>
            </span>
        </a>

          <div class="navbar-collapse" id="">
            <div class="user_option">
              <a href="">
                Login
              </a>
            </div>
            <div class="custom_menu-btn">
              <button onclick="openNav()">
                <span class="s-1"> </span>
                <span class="s-2"> </span>
                <span class="s-3"> </span>
              </button>
            </div>
            <div id="myNav" class="overlay">
              <div class="overlay-content">
                <a href="dashboard.php">Home</a>
                <a href="car.php">Cars</a>
                <a href="contact.php">Contact Us</a>
                <a href="logout.php">Log out</a>
            </div>
            </div>
          </div>
        </nav>
      </div>
    </header>
    <!-- end header section -->
  </div>

  <!-- contact section -->

  <section class="contact_section layout_padding">
    <div class="container">
        <div class="heading_container">
            <h2>Request A Call Back</h2>
        </div>
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="form_container">
                    <form action="submit_form.php" method="POST">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <input type="text" class="form-control" name="name" placeholder="Name" required>
                            </div>
                            <div class="form-group col-md-6">
                                <input type="text" class="form-control" name="phone" placeholder="Phone" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col">
                                <input type="email" class="form-control" name="email" placeholder="Email" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" name="message" placeholder="Message" rows="4" required></textarea>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>


        <a href="">
          <div class="img-box">
            <img src="images/location.png" alt="">
          </div>
          <h6>
            BOJANEGARA
          </h6>
        </a>
        <a href="">
          <div class="img-box">
            <img src="images/call.png" alt="">
          </div>
          <h6>
           085712262168
          </h6>
        </a>
        <a href="">
          <div class="img-box">
            <img src="images/mail.png" alt="">
          </div>
          <h6>
            dwijulianisa.com
          </h6>
        </a>

      </div>
      <div class="social_container">
        <div class="social-box">
          <div>
            <a href="">
              <img src="images/fb.png" alt="">
            </a>
          </div>
          <div>
            <a href="">
              <img src="images/twitter.png" alt="">
            </a>
          </div>
          <div>
            <a href="">
              <img src="images/linkedin.png" alt="">
            </a>
          </div>
          <div>
            <a href="">
              <img src="images/insta.png" alt="">
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- end contact section -->


  <script src="js/jquery-3.4.1.min.js"></script>
  <script src="js/bootstrap.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js">
  </script>
  <script src="js/custom.js"></script>

</body>

</html>
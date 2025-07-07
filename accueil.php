


<!DOCTYPE html>
<html lang="en">

  <head>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <title>accueil</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-574-mexant.css">
    <link rel="stylesheet" href="assets/css/owl.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">

  </head>

<body>


  <!-- ***** Header Area Start ***** -->
  <header class="header-area header-sticky">
      <div class="container">
          <div class="row">
              <div class="col-12">
                  <nav class="main-nav">
                      <!-- ***** Logo Start ***** -->
                      <a href="index.html" class="logo">
                          <img src="assets/images/logo1.png" alt="">
                      </a>


                      <!-- ***** Logo End ***** -->
                      <!-- ***** Menu Start ***** -->
                      <ul class="nav">
                          <li class="scroll-to-section"><a href="#top" class="active">Home</a></li>
                          <li class="scroll-to-section"><a href="calendar.php">Calendar</a></li>
                          <li class="scroll-to-section"><a href="form.php">Reservation</a></li>
                          <li class="scroll-to-section"><a href="contact-us.php">Contact-us</a></li>
                          <li class="scroll-to-section"><a href="liste_salles.php">room lists</a></li>
                          <li class="scroll-to-section"><a href="logout.php">Logout</a></li>
                      </ul>        
                      <a class='menu-trigger'>
                          <span>Menu</span>
                      </a>
                      <!-- ***** Menu End ***** -->
                  </nav>
              </div>
          </div>
      </div>
  </header>
  <!-- ***** Header Area End ***** -->
<?php
session_start();
include('dbConfig.php');
if(isset($_SESSION['id'])) {
$id=$_SESSION['id'];
$check_query = mysqli_query($connt, "SELECT * FROM members WHERE id='$id'");
$user_data = mysqli_fetch_assoc($check_query);
}

?>
  <!-- ***** Main Banner Area Start ***** -->
  <div class="swiper-container" id="top">
    <div class="swiper-wrapper">
      <div class="swiper-slide">
        <div class="slide-inner" style="background-image:url(assets/images/slide-01.jpg)">
          <div class="container">
            <div class="row">
              <div class="col-lg-8">
                <div class="header-text">
                  <h2>Hello <em><?php echo $user_data['username']; ?></em><br /></h2>
                  <div class="div-dec"></div>
                  <p>Streamline Your Workspace With Seamless Booking Solutions!</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
 
   
  </body>
</html>
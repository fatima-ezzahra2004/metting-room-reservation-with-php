<?php
session_start();
if(isset($_POST['send'])){
  extract($_POST);
  if(isset($name) && $name !="" &&
  isset($phone) && $phone !="" &&
  isset($email) && $email !="" &&
  isset($subject) && $subject !="" &&
  isset($message) && $message !="" )
{
  
  $to = "fatimaezzahrachibane1gmail.com";
  $subject = "vous avez recu un message de :".$email;
  
  $message = "<p>vous avez recu un message de <strong> ".$email."</strong> </p>
  <p><strong>Nom :</strong>".$name."</p>
  <p><strong>Telephone :</strong>".$phone."</p>
  <p><strong>Nom :</strong>".$message."</p>";
  
  // Always set content-type when sending HTML email
  $headers = "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
  
  // More headers
  $headers .= 'From: <'.$email.'>' . "\r\n";
 
  $send=mail($to,$subject,$message,$headers);
  
  if($send){
    $_SESSION['succes_message']="message envoyer";
  
  }else{
    $info="message non envoyer";
   
  }
}
else{
  $info="Please fill in all the field";
 
}
}
?>
<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <title>contact us</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-574-mexant.css">
    <link rel="stylesheet" href="assets/css/owl.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">
<!--

TemplateMo 574 Mexant

https://templatemo.com/tm-574-mexant
-->

<style>
  .tout{
   display:flex;
   flex-direction:row;

  }
  .btn-primary {
    background-color: #ff7f0e;
    border: none;
    border-radius: 5px;
    padding: 8px 20px;
    font-weight: bold;
    transition: background-color 0.3s;
    margin:50px;
    margin-top:100px;
    margin-bottom:-150px;
}

.btn-primary:hover {
    background-color: #ff9500;
}
  </style>
  </head>

<body>


  <!-- ***** Header Area End ***** -->

                <a class="btn btn-primary" href="accueil.php" role="button">back</a>
                <br />
                <br />
                <br />  

  <!-- ***** Main Banner Area End ***** -->

  

  <section class="contact-us-form">
    <div class="container">
      <div class="row">
        <div class="col-lg-3 ">
          <div class="section-heading">
            <h6>Contact Us</h6>
            <?php
if(isset($info)){ ?>
<p class="request_message" style="color:red">
  <?=$info?>
</p>
<?php
}
?>

<?php
if(isset($_SESSION['succes_message'])){ ?>
<p class="request_message" style="color:green">
  <?=$_SESSION['succes_message']?>
</p>
<?php
}
?>
          </div>
        </div>
        <div class="col-lg-7 " >
          <form id="contact" action="" method="post">
            <div class="row">
              <div class="col-lg-6">
                <fieldset>
                  <input type="name" name="name" id="name" placeholder="Your Name..." autocomplete="on" >
                </fieldset>
              </div>
              <div class="col-lg-6">
                <fieldset>
                  <input type="phone" name="phone" id="phone" placeholder="Your Phone..." autocomplete="on" >
                </fieldset>
              </div>
              <div class="col-lg-6">
                <fieldset>
                  <input type="text" name="email" id="email" pattern="[^ @]*@[^ @]*" placeholder="Your E-mail..." >
                </fieldset>
              </div>
              <div class="col-lg-6">
                <fieldset>
                  <input type="subject" name="subject" id="subject" placeholder="Subject..." autocomplete="on" >
                </fieldset>
              </div>
              <div class="col-lg-12">
                <fieldset>
                  <textarea name="message" id="message" placeholder="Your Message"></textarea>
                </fieldset>
              </div>
              <div class="col-lg-12">
                <fieldset>
                  <button type="submit" id="form-submit" class="orange-button"  name='send'>Send Message</button>
                </fieldset>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>

</div>
  <footer>
    <div class="container" >
      <div class="row">
        <div class="col-lg-12">
          <p>Copyright © 2024
          
          <br>Designed by FATIMA EZZHRA CHIBANE</p>
        </div>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="assets/js/isotope.min.js"></script>
    <script src="assets/js/owl-carousel.js"></script>

    <script src="assets/js/tabs.js"></script>
    <script src="assets/js/swiper.js"></script>
    <script src="assets/js/custom.js"></script>
    <script>
      var interleaveOffset = 0.5;

      var swiperOptions = {
        loop: true,
        speed: 1000,
        grabCursor: true,
        watchSlidesProgress: true,
        mousewheelControl: true,
        keyboardControl: true,
        navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev"
        },
        on: {
          progress: function() {
            var swiper = this;
            for (var i = 0; i < swiper.slides.length; i++) {
              var slideProgress = swiper.slides[i].progress;
              var innerOffset = swiper.width * interleaveOffset;
              var innerTranslate = slideProgress * innerOffset;
              swiper.slides[i].querySelector(".slide-inner").style.transform =
                "translate3d(" + innerTranslate + "px, 0, 0)";
            }      
          },
          touchStart: function() {
            var swiper = this;
            for (var i = 0; i < swiper.slides.length; i++) {
              swiper.slides[i].style.transition = "";
            }
          },
          setTransition: function(speed) {
            var swiper = this;
            for (var i = 0; i < swiper.slides.length; i++) {
              swiper.slides[i].style.transition = speed + "ms";
              swiper.slides[i].querySelector(".slide-inner").style.transition =
                speed + "ms";
            }
          }
        }
      };

      var swiper = new Swiper(".swiper-container", swiperOptions);
    </script>

  </body>
</html>
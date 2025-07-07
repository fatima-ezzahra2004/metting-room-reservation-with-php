<?php
session_start();
require_once('dbConfig.php');
$errors = [];

if (isset($_POST["submit"])) {
    $email = mysqli_real_escape_string($connt, $_POST['email']);
    $password = $_POST['password'];

    $query = mysqli_query($connt, "SELECT * FROM members WHERE email = '$email'");
    $row = mysqli_fetch_assoc($query);

    if ($row) {
        if (password_verify($password, $row['password'])) {
            $_SESSION["index"] = true;
            $_SESSION["id"] = $row["id"];
            $_SESSION["username"] = $row["username"];
            $_SESSION["role"] = $row["role"];

            // Redirection selon le rôle
            if ($row["role"] === "admin") {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: accueil.php");
            }
            exit();
        } else {
            $errors[] = "Mot de passe incorrect.";
        }
    } else {
        $errors[] = "Aucun utilisateur trouvé avec cet email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="login.css">
    <title>login</title>
</head>
<body>
    <section class="h-100 gradient-form" style="background-color: #eee;">
        <div class="container py-5 h-100">
          <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-xl-10">
              <div class="card rounded-3 text-black">
                <div class="row g-0">
                  <div class="col-lg-6">
                    <div class="card-body p-md-5 mx-md-4">
      
                      <div class="text-center">
                      <a href="index.html" class="logo">
                          <img src="assets/images/logo1.png" alt="">
                      </a>
                        
                      </div>
                      <?php 
				if(isset($errors) && count($errors) > 0)
				{
					foreach($errors as $error_msg)
					{
						echo '<div class="alert alert-danger">'.$error_msg.'</div>';
					}
				}
			?>
                      <form action="" method="POST">
                        <div class="form-outline mb-4">
                          <input type="email" name="email" class="form-control"
                            placeholder="email" required />
                          <label class="form-label" for="">Email</label>
                        </div>
      
                        <div class="form-outline mb-4">
                          <input type="password" name="password" class="form-control" 
                          placeholder="Password" required/>
                          <label class="form-label" for="">Password</label>
                        </div>
      
                        <div class="text-center pt-1 mb-5 pb-1">
                          <button class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3"  type="submit" name="submit" >login</button>
                        </div>
      
                        <div class="d-flex align-items-center justify-content-center pb-4">
                          <p class="mb-0 me-2">Don't have an account?</p>
                          <a class="text-muted" href="register.php">Sign Up Now</a>
                        </div>
      
                      </form>
      
                    </div>
                  </div>
                  <div class="col-lg-6 d-flex align-items-center gradient-custom-2">
                    <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                      <h4 class="mb-4">Effortless Meeting Room Reservations</h4>
                     
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
</body>
</html>
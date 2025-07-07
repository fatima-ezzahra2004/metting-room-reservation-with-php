<?php
session_start();
require_once('dbConfig.php');
$message = "";

if(isset($_POST["submit"])) {
    $username = mysqli_real_escape_string($connt, $_POST['username']);
    $email = mysqli_real_escape_string($connt, $_POST['email']);
    $password = $_POST['password'];
    $confirmpassword = $_POST['confirmpassword'];
    $role = mysqli_real_escape_string($connt, $_POST['role']);

    $check = mysqli_query($connt, "SELECT * FROM members WHERE username = '$username' OR email = '$email'");
    if(mysqli_num_rows($check) > 0) {
        $message = "<div class='alert alert-danger'>Nom d'utilisateur ou email déjà utilisé.</div>";
    } else {
        if($password === $confirmpassword) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO members (username, email, password, role) 
                      VALUES ('$username', '$email', '$hashedPassword', '$role')";
            if(mysqli_query($connt, $query)) {
                $message = "<div class='alert alert-success'>Inscription réussie ! <a href='index.php'>Connecte-toi ici</a></div>";
            } else {
                $message = "<div class='alert alert-danger'>Erreur lors de l'inscription.</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>Les mots de passe ne correspondent pas.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="login.css">
    <title>register</title>
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
                    
                      <form action="" method="POST">
                        <p>Create account</p>
      
                        <div class="form-outline mb-4">
                          <input type="text" name="username" class="form-control"
                            placeholder="username"  />
                          <label class="form-label" for="form2Example11">Username</label>
                        </div>

                        <div class="form-outline mb-4">
                            <input type="email" name="email" class="form-control"
                              placeholder="email" />
                            <label class="form-label" for="">Email</label>
                          </div>

                        <div class="form-outline mb-4">
                          <input type="password" name="password" class="form-control" 
                          placeholder="Password" />
                          <label class="form-label" for="">Password</label>
                        </div>

                        <div class="form-outline mb-4">
                        <input type="password" name="confirmpassword" class="form-control" 
                        placeholder="Password" />
                        <label class="form-label" for="">confirm Password</label>
                      </div>
                      <?php if(!empty($message)) echo $message; ?>

                      <div class="mb-4">
                      <label class="form-label d-block">Choisir un rôle</label>

                          <div class="form-check form-check-inline">
                             <input class="form-check-input" type="radio" name="role" id="utilisateur" value="utilisateur" checked>
                             <label class="form-check-label" for="utilisateur">Utilisateur</label>
                          </div>

                          <div class="form-check form-check-inline">
                             <input class="form-check-input" type="radio" name="role" id="admin" value="admin">
                             <label class="form-check-label" for="admin">Administrateur</label>
                          </div>
                          </div>
                        <div class="text-center pt-1 mb-5 pb-1">
                          <button class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3" name="submit" type="submit" >register</button>
                           
                          
                        </div>
      
                       
                      </form>
      
                    </div>
                  </div>
                  <div class="col-lg-6 d-flex align-items-center gradient-custom-2">
                    <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                      <h4 class="mb-4">Effortless Metting Room Reservations</h4>
                     
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
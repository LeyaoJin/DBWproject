<?php
    include 'config.php';
	if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        // Here get the username and password given by the user.
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $error = "";

        // Here we prepare and perform the SQL query.
        $userQuery = $conn -> prepare("SELECT password FROM users WHERE email = ?");
        $userQuery -> bind_param("s", $email);
        $userQuery -> execute();
        $userResult = $userQuery -> get_result();

        // First we will check that the query was done properly. 
        if (!$userResult){
            $error = "Query failed: " . $conn->error;
        }
        // Then we check that there is someone with this username. 
        elseif ($userResult -> num_rows > 0){
            $row = $userResult -> fetch_assoc();
            $hashed_password = $row['password'];
            // If the user exists, we will check the password is correct. 
            if(password_verify($password, $hashed_password)){
                // Redirect to user page 
                session_start();
                $_SESSION['email'] = $email; 
                header("Location: personal.php");
                exit();
            }
            else{
                $error = "Username or password is incorrect.";
            }
        }
        // If not we will print an error. 
        else{
            $error = "Username or password is incorrect.";
        }

    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>InDiMoMap</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Free HTML5 Template by FREEHTML5.CO" />
        <meta name="keywords" content="free html5, free template, free bootstrap, html5, css3, mobile first, responsive" />
        <meta name="author" content="FREEHTML5.CO" />
        
        <meta property="og:title" content="" />
        <meta property="og:image" content="" />
        <meta property="og:url" content="" />
        <meta property="og:site_name" content="" />
        <meta property="og:description" content="" />
        <meta name="twitter:title" content="" />
        <meta name="twitter:image" content="" />
        <meta name="twitter:url" content="" />
        <meta name="twitter:card" content="" />
        
        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        <link rel="shortcut icon" href="favicon.ico">
        
        <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,600,400italic,700' rel='stylesheet'
            type='text/css'>
        
        <!-- Animate.css -->
        <link rel="stylesheet" href="css/animate.css">
        <!-- Icomoon Icon Fonts-->
        <link rel="stylesheet" href="css/icomoon.css">
        <!-- Simple Line Icons -->
        <link rel="stylesheet" href="css/simple-line-icons.css">
        <!-- Magnific Popup -->
        <link rel="stylesheet" href="css/magnific-popup.css">
        <!-- Bootstrap  -->
        <link rel="stylesheet" href="css/bootstrap.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="css/style.css">
        <!-- Modernizr JS -->
        <script src="js/modernizr-2.6.2.min.js"></script>
        <title>Login - InDiMoMap</title>
    </head>

    <body>
        <header role="banner" id="fh5co-header">
            <div class="container">
                <!-- <div class="row"> -->
                <nav class="navbar navbar-default">
                    <div class="navbar-header">
                        <!-- Mobile Toggle Menu Button -->
                        <a href="#" class="js-fh5co-nav-toggle fh5co-nav-toggle" data-toggle="collapse" data-target="#navbar"
                            aria-expanded="false" aria-controls="navbar"><i></i></a>
                        <a class="navbar-brand" href="index.php">InDiMoMap</a>
                    </div>
                    <div id="navbar" class="navbar-collapse collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="index.php" data-nav-section="home" class="external"><span>Home</span></a></li>
                            <li><a href="introquiz.php" data-nav-section="quiz" class="external"><span>Quiz</span></a></li>
                            <li><a href="index.php#fh5co-team" data-nav-section="team" class="external"><span>Team</span></a></li>
                            <li><a href="index.php#fh5co-contact" data-nav-section="contact" class="external"><span>Contact</span></a></li>
                            <li class="active"><a href="login.php" data-nav-section="login" class="external"><span>Login</span></a></li>
                        </ul>
                    </div>
                </nav>
                <!-- </div> -->
            </div>
        </header>
        
        <section id="login" data-section="login" data-stellar-background-ratio="0.5">
            <div class="gradient-overlay"></div>
            <div class="login-container">
                <h1>Login to Infectious Disease Mortality Map</h1>
                <form id="loginForm" action="login.php" method="POST" enctype="multipart/form-data">
                    <label for="email">Email</label>
                    <input type="text" name="email" placeholder="Email" required>

                    <label for="password">Password</label>
                    <input type="password" name="password" placeholder="Password" required>

                    <?php if (!empty($error)): ?>
                        <p style = "color: red;">
                            <?php echo $error; ?>
                        </p>
                    <?php endif; ?>	

                    <button class="btn btn-primary" type="submit">Login</button>
                </form>
                <div class="signup-link">
                    <p>Don't have an account? <a href="signup.php">Sign up</a></p>
                </div>
            </div>
        </section>

    <!-- jQuery -->
		<script src="js/jquery.min.js"></script>
		<!-- jQuery Easing -->
		<script src="js/jquery.easing.1.3.js"></script>
		<!-- Bootstrap -->
		<script src="js/bootstrap.min.js"></script>
		<!-- Waypoints -->
		<script src="js/jquery.waypoints.min.js"></script>
		<!-- Stellar Parallax -->
		<script src="js/jquery.stellar.min.js"></script>
		<!-- Counter -->
		<script src="js/jquery.countTo.js"></script>
		<!-- Magnific Popup -->
		<script src="js/jquery.magnific-popup.min.js"></script>
		<script src="js/magnific-popup-options.js"></script>
		<!-- Main JS (Do not remove) -->
		<script src="js/main.js"></script>
    </body>
</html>

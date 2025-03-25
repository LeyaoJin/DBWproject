<?php
    include 'config.php';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Here we get all the information from the user. 
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $birth = $_POST['birthdate'];
        $education = $_POST['education'];
        $gender = $_POST['gender']; 
        $role = "user";
        $error = "";

        // Here we will check if the email is already on our database.
        $emailQuery = $conn -> prepare("SELECT * FROM users WHERE email = ?");
		$emailQuery -> bind_param("s", $email);
		$emailQuery -> execute();
		$emailResult = $emailQuery -> get_result();

		// We will then check that there were no errors in connecting to the database.
		if (!$emailResult) {
			$error = "Query failed: " . $conn->error;
		}

        // If we get 0 rows it means we don't have this email registered and we can proceed.
        elseif ($emailResult->num_rows == 0){
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $userInfo = $conn -> prepare("INSERT INTO users (username, email, password, first_name, surname, education_level, gender, date_of_birth, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            if (!$userInfo) {
                die("Prepare failed: " . $conn->error);
            }

            $userInfo->bind_param("sssssssss", $username, $email, $hashedPassword, $name, $surname, $education, $gender, $birth, $role);
            
            if ($userInfo->execute()) {
                // To user page 
                session_start();
                $_SESSION['email'] = $email; 
                header("Location: personal.php");
                exit();
            } else {
                $error = "Error: " . $stmt->error;
            }
            
            $userInfo->close();
        }
        // If we already have the email registered, we will return an error. 
        else{
            $error = "This email is already registered. \nPlease try another email.";
        }

        $emailQuery->close();
    }
    $conn -> close(); 
    
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
                            <li><a href="login.php" data-nav-section="login" class="external"><span>Login</span></a></li>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>

        <section id="signup" data-section="signup" data-stellar-background-ratio="0.5">
            <div class="gradient-overlay"></div>
            <div class="signup-container">
                <h1>Sign Up for Infectious Disease Mortality Map</h1>
                <?php if (!empty($error)): ?>
                    <p style = "color: red;">
                        <?php echo $error; ?>
                    </p>
                <?php endif; ?>	
                <form id="signupForm" action="signup.php" method="POST" enctype="multipart/form-data"
                    onsubmit="return validateForm();" novalidate>
                    <div class="form-group">
                        <label for="name">First Name</label>
                        <input type="text" id="name" name="name" placeholder="First Name">
                        <small class="error-msg" id="nameError"></small>
                    </div>
        
                    <div class="form-group">
                        <label for="surname">Surname</label>
                        <input type="text" id="surname" name="surname" placeholder="Surname">
                        <small class="error-msg" id="surnameError"></small>
                    </div>
        
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Username" required>
                        <small class="error-msg" id="usernameError"></small>
                    </div>
        
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Email">
                        <small class="error-msg" id="emailError"></small>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Password">
                        <small class="error-msg" id="passwordError"></small>
                    </div>
        
                    <div class="form-group">
                        <label for="confirmPassword">Confirm Password</label>
                        <input type="password" id="confirmPassword" placeholder="Confirm Password">
                        <small class="error-msg" id="confirmPasswordError"></small>
                    </div>
        
                    <div class="form-group">
                        <label for="birthdate">Birth date</label>
                        <input type="date" id="birthdate" name="birthdate" required>
                        <small class="error-msg" id="birthdateError"></small>
                    </div>

                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender">
                            <option value="" disabled selected>Select your gender</option>
                            <option value="Female">Female</option>
                            <option value="Male">Male</option>
                            <option value="Other">Other</option>
                        </select>
                        <small class="error-msg" id="genderError"></small>
                    </div>
        
                    <div class="form-group">
                        <label for="education">Education Level</label>
                        <select id="education" name="education">
                            <option value="" disabled selected>Education level</option>
                            <option value="High School">High School</option>
                            <option value="Associate Degree">Associate Degree</option>
                            <option value="Bachelor's Degree">Bachelor's Degree</option>
                            <option value="Master's Degree">Master's Degree</option>
                            <option value="Doctorate">PhD or Doctorate</option>
                            <option value="Other">Other</option>
                        </select>
                        <small class="error-msg" id="educationError"></small>
                    </div>
                    <button type="submit" id="submitButton" class="btn btn-primary" disabled>Register</button>
                </form>
        
                <div class="login-link">
                    <p>Already have an account? <a href="login.html">Login here</a></p>
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

<?php
    include 'config.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $total_score = 0;
        foreach ($_POST as $answer_id){
            $stmt = $conn->prepare("SELECT score FROM answers WHERE idAnswer = ?");
            $stmt->bind_param("i", $answer_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()){
                $total_score += $row['score'];
            }
        }
    }
    else {
        die("No questions submited.");
    }
    $stmt = $conn->prepare("SELECT ncbi_taxon_id, result_description FROM results_mapping WHERE ? BETWEEN min_score AND max_score");
    $stmt->bind_param("i", $total_score);
    $stmt->execute();
    $result = $stmt->get_result();
    $pathogen_data = null;
    if ($row = $result->fetch_assoc()) {
        $ncbi_taxon_id = $row['ncbi_taxon_id'];
        $result_description = $row['result_description'];

        // Fetch pathogen details using ncbi_taxon_id
        $stmt = $conn->prepare("SELECT pathogen_name FROM pathogen WHERE ncbi_taxon_id = ?");
        $stmt->bind_param("s", $ncbi_taxon_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($pathogen = $result->fetch_assoc()) {
            $pathogen_data = [
                "name" => $pathogen['pathogen_name'],
                "description" => $result_description
            ];
        }
    }

    $conn->close();

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Which Disease are you?</title>
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
        <header role="banner" id="fh5co-header"  class="navbar-fixed-top fh5co-animated slideInDown">
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
                            <li class="active"><a href="introquiz.php" data-nav-section="quiz" class="external"><span>Quiz</span></a></li>
                            <li><a href="index.php#fh5co-team" data-nav-section="team" class="external"><span>Team</span></a></li>
                            <li><a href="index.php#fh5co-team" data-nav-section="contact" class="external"><span>Contact</span></a>
                            </li>
                            <?php
								session_start();
								include 'config.php'; 
								
								// Check if user is logged in by verifying the session
								if (isset($_SESSION['email'])) {
									
									// echo '<li><a href="personal.php" class="external"><span>User</span></a></li>';
									$email = $_SESSION['email'];

									// Now we get the name from the user 
									$userQuery = $conn->prepare("SELECT first_name FROM users WHERE email = ?");
									$userQuery->bind_param("s", $email);
									$userQuery->execute();
									$userResult = $userQuery->get_result();

									if ($userResult->num_rows > 0) {
										$user = $userResult->fetch_assoc();
										$name = $user['first_name'];
										
										// Display user's name and link to personal.php
										echo '<li><a href="personal.php"  class="external"><span>' . $name . '</span></a></li>';
									} 
									// In case something didn't work we will show the log in page. 
									else {
										echo '<li><a href="login.php" data-nav-section="login" class="external"><span>Login</span></a></li>';
									}

									$userQuery->close();
									$conn->close();
								} else {
									// If not logged in, show the login option
									echo '<li><a href="login.php" data-nav-section="login" class="external"><span>Login</span></a></li>';
								}
							?>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>
        <section id="quiz-result" data-section="quiz" data-stellar-background-ratio="0.5">
            <div class="quiz-result-container">
                <h1>Your Quiz Results</h1>
                <p><strong>Total Score:</strong> <?php echo $total_score; ?></p>
                <?php if ($pathogen_data): ?>
                    <h2>You are associated with:</h2>
                    <p><strong><?php echo $pathogen_data['name']; ?></strong></p>
                    <p><?php echo $pathogen_data['description']; ?></p>
                <?php else: ?>
                    <p>No matching pathogen found for your score.</p>
                <?php endif; ?>
            </div>
        </section>
    </body>
</html>

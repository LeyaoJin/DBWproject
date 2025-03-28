<?php
    include 'config.php';
	if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['query'])){
		$query = htmlspecialchars($_GET['query']);
		$searchTerm = "%$query%";
		$error = "";

		// First we will check if the input corresponds to a disease.
		$diseaseQuery = $conn -> prepare("SELECT * FROM diseases WHERE disease_name LIKE ?");
		$diseaseQuery -> bind_param("s", $searchTerm);
		$diseaseQuery -> execute();
		$diseaseResult = $diseaseQuery -> get_result();
		
		// We will then check that there were no errors in connecting to the database.
		if (!$diseaseResult) {
			$error = "Query failed: " . $conn->error;
		}
		
		// If our query doesn't return any results (rows = 0), then we will check if the input corresponds to a pathogen.
		elseif($diseaseResult->num_rows == 0){
			$pathogenQuery = $conn -> prepare("SELECT * FROM pathogen WHERE pathogen_name LIKE ?");
			$pathogenQuery -> bind_param("s", $searchTerm);
			$pathogenQuery -> execute();
			$pathogenResult = $pathogenQuery -> get_result();
			// As before, we will check that there were no errors in connceting to the database.
			if (!$pathogenResult) {
				$error = "Query failed: " . $conn->error;
			}
			// If the query is empty, it means the input is not found on our database. 
			elseif($pathogenResult->num_rows != 1){
				$error = "$query is not found in our database. Please try again.";
			}
			else{
				// Here we get the associated diseases to our pathogen. 
				$row = $pathogenResult->fetch_assoc(); 
				$pathogen_id = $row['ncbi_taxon_id'];
				$path_diseaseQuery = $conn -> prepare("SELECT idDisease FROM pathogen_has_disease WHERE ncbi_taxon_id = ?");
				$path_diseaseQuery -> bind_param("s", $pathogen_id);
				$path_diseaseQuery -> execute();
				$path_diseaseResult = $path_diseaseQuery -> get_result();

				// Then we check that the pathogen has an associated disease. 
				if($path_diseaseResult -> num_rows != 1){
					$error = "This pathogen has multiple associated diseases. Please search a specific disease.";
				}

				else{
					$row = $path_diseaseResult->fetch_assoc();
					$diseaseID = $row['idDisease'];
					$diseaseQuery = $conn -> prepare("SELECT disease_name FROM diseases WHERE idDisease = ?");
					$diseaseQuery -> bind_param("s", $diseaseID);
					$diseaseQuery -> execute();
					$diseaseResult = $diseaseQuery -> get_result();
					$diseaseRow = $diseaseResult->fetch_assoc();
					$diseaseName = $diseaseRow['disease_name'];
					header("Location: heres_a_map.html?disease=" . urlencode($diseaseName));
                	exit();
				}
				
			}
		}
		
		//In reality we only want to have one result, if the input is too broad and the LIKE command returns more than 1 disease, we won't accept it
		else if ($diseaseResult->num_rows == 1){
			$row = $diseaseResult->fetch_assoc(); 
			$diseaseName = $row['disease_name'];
			header("Location: heres_a_map.html?disease=" . urlencode($diseaseName));
            exit();
		}
		else{
			$error = "$query is not found in our database. Please try again.";
		}
	}
?> 

<!DOCTYPE html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>InDiMoMap</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="Free HTML5 Template by FREEHTML5.CO" />
		<meta name="keywords" content="free html5, free template, free bootstrap, html5, css3, mobile first, responsive" />
		<meta name="author" content="FREEHTML5.CO" />

		<meta property="og:title" content=""/>
		<meta property="og:image" content=""/>
		<meta property="og:url" content=""/>
		<meta property="og:site_name" content=""/>
		<meta property="og:description" content=""/>
		<meta name="twitter:title" content="" />
		<meta name="twitter:image" content="" />
		<meta name="twitter:url" content="" />
		<meta name="twitter:card" content="" />

		<!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
		<link rel="shortcut icon" href="favicon.ico">

		<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,600,400italic,700' rel='stylesheet' type='text/css'>
		
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
	</head>
	<body>
		<header role="banner" id="fh5co-header">
			<div class="container">
				<!-- <div class="row"> -->
				<nav class="navbar navbar-default">
					<div class="navbar-header">
						<!-- Mobile Toggle Menu Button -->
						<a href="#" class="js-fh5co-nav-toggle fh5co-nav-toggle" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"><i></i></a>
						<a class="navbar-brand" href="index.php">InDiMoMap</a> 
					</div>
					<div id="navbar" class="navbar-collapse collapse">
						<ul class="nav navbar-nav navbar-right">
							<li class="active"><a href="index.php" data-nav-section="home" class="external"><span>Home</span></a></li>
							<li><a href="introquiz.php" data-nav-section="quiz" class="external"><span>Quiz</span></a></li>
							<li><a href="#" data-nav-section="team"><span>Team</span></a></li>
							<li><a href="#" data-nav-section="contact"><span>Contact</span></a></li>

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
				<!-- </div> -->
			</div>
		</header>

		<section id="fh5co-home" data-section="home" style="background-image: url(images/full_image_2.jpg);" data-stellar-background-ratio="0.5">
			<div class="gradient"></div>
			<div class="container">
				<div class="text-wrap">
					<div class="text-inner">
						<div class="row">
							<div class="col-md-8 col-md-offset-2">
								<h1 class="to-animate">Infectious Disease Mortality Map</h1>
								<h2 class="to-animate">Infectious Disease mortality map developed using data retrieved at <a href="https://www.who.int/data/data-collection-tools/who-mortality-database" target="_blank">World Health Organization (WHO) Mortality Database</a></h2>
							</div>
						</div>
						<form action="index.php" method="GET">
							<div class="search_container to-animate">
								<div class="search_bar">
									<i class="icon-search"></i>
									<input type="text" id="search-bar" class="form-control" placeholder="Search diseases or pathogens">
									<div class="submit-btn">
										<button id="submit-btn" class="btn btn-primary" type="button" onclick="applyFilter(event)">Search</button>
									</div>
								</div>
								
								<?php if (!empty($error)): ?>
									<p style = "color: red;">
										<?php echo $error; ?>
									</p>
								<?php endif; ?>	

								<div class="filter-btn">
									<button id="filter-btn" class="filter-btn btn btn-primary" type="button">Advanced Filters</button>
									<div id="filter-dropdown" class="filter-dropdown" style="display: none;">
										<label for="search-year">Year:</label>
										<input type="number" id="search-year" name="year" class="form-control">
								
										<label for="search-gender">Gender:</label>
										<select id="search-gender" name="gender" class="form-control">
											<option value="">Any</option>
											<option value="male">Male</option>
											<option value="female">Female</option>
										</select>
								
										<label for="search-continent">Continent:</label>
										<select id="search-continent" name="continent" class="form-control">
											<option value="">Select Continent</option>
											<option value="africa">Africa</option>
											<option value="asia">Asia</option>
											<option value="europe">Europe</option>
											<option value="middle-east">Middle East</option>
											<option value="north-america">North America</option>
											<option value="south-america">South America</option>
											<option value="oceania">Oceania</option>
										</select>
								
										<label for="search-country">Country:</label>
										<input type="text" id="search-country" name="country" class="form-control" placeholder="Enter country name">
										<button class="btn btn-primary" type="button" onclick="applyFilter(event)">Apply</button>
									</div> 
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="slant"></div>
		</section>

		<section id="fh5co-work" data-section="work">
			<div class="container">
				<div class="row">
					<div class="col-md-12 section-heading text-center">
						<h2 class="to-animate">Projects</h2>
					</div>
				</div>
		
				<div class="row row-bottom-padded-sm">
					<div class="col-md-6 col-sm-6 col-xxs-12">
						<a href="map.html" class="fh5co-project-item to-animate">
							<img src="images/map.png" alt="Image" class="img-responsive">
							<div class="fh5co-text" style="text-align: center;">
								<h2>Mortality Map</h2>
								<span>Interactive map that shows infectious disease mortality</span>
							</div>
						</a>
					</div>
					<div class="col-md-6 col-sm-6 col-xxs-12">
						<a href="quiz.html" class="fh5co-project-item to-animate">
							<img src="images/quiz.png" alt="Image" class="img-responsive">
							<div class="fh5co-text" style="text-align: center;">
								<h2>Quiz</h2>
								<span>Interactive quiz that tells you which pathogen you are!</span>
							</div>
						</a>
					</div>
				</div>
			</div>
		</section>

		<section id="fh5co-counters" style="background-image: url(images/full_image_1.jpg);"
			data-stellar-background-ratio="0.5">
			<div class="fh5co-overlay"></div>
			<div class="container">
				<div class="row">
					<div class="col-md-12 section-heading text-center to-animate">
						<h2>Fun Facts</h2>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 col-sm-6 col-xs-12">
						<div class="fh5co-counter to-animate">
							<i class="fh5co-counter-icon icon-bed to-animate-2"></i>
							<span class="fh5co-counter-number js-counter" data-from="680000" data-to="700000" data-speed="3000"
								data-refresh-interval="5">700000</span>
							<span class="fh5co-counter-label">Annual deaths worldwide due to antibiotic resistance</span>
						</div>
					</div><div class="col-md-4 col-sm-6 col-xs-12">
						<div class="fh5co-counter to-animate">
							<i class="fh5co-counter-icon icon-heartbeat to-animate-2"></i>
							<span class="fh5co-counter-number js-counter" data-from="2688000" data-to="2713000" data-speed="3000"
								data-refresh-interval="5">2713000</span>
							<span class="fh5co-counter-label">Lives saved each year through vaccination programs</span>
						</div>
					</div>
					<div class="col-md-4 col-sm-6 col-xs-12">
						<div class="fh5co-counter to-animate">
							<i class="fh5co-counter-icon icon-drop2 to-animate-2"></i>
							<span class="fh5co-counter-number js-counter" data-from="460000" data-to="485000" data-speed="3000"
								data-refresh-interval="5">485000</span>
							<span class="fh5co-counter-label">Annual deaths caused by diarrhea from contaminated water</span>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section id="fh5co-team" data-section="team">
			<div class="container">
				<div class="row">
					<div class="col-md-12 section-heading text-center">
						<div class="team_taital">
							<h2 class="to-animate">Team</h2>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="fh5co-person text-center to-animate">
							<figure><img src="images/anna.jpeg" alt="Image"></figure>
							<h3>Anna Korda</h3>
							<span class="fh5co-position">Web Developer</span>
							<ul class="social social-circle">
								<li><a href="https://formacio.bq.ub.edu/~u254616/"><i class="icon-globe2"></i></a></li>
								<li><a href="https://www.linkedin.com/in/anna-korda-462b1a2a4/"><i class="icon-linkedin"></i></a></li>
								<li><a href="https://github.com/annakorda"><i class="icon-github"></i></a></li>
							</ul>
						</div>
					</div>
					<div class="col-md-6">
						<div class="fh5co-person text-center to-animate">
							<figure><img src="images/liam.jpeg" alt="Image"></figure>
							<h3>Liam McBride</h3>
							<span class="fh5co-position">Web Designer</span>
							<ul class="social social-circle">
								<li><a href="https://formacio.bq.ub.edu/~u254819/"><i class="icon-globe2"></i></a></li>
								<li><a href="https://www.linkedin.com/in/liam-mcbride-2b413019b"><i class="icon-linkedin"></i></a></li>
								<li><a href="https://github.com/lmcbride053"><i class="icon-github"></i></a></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="fh5co-person text-center to-animate">
							<figure><img src="images/montse.jpeg" alt="Image"></figure>
							<h3>Montse Garcia</h3>
							<span class="fh5co-position">Web Developer</span>
							<ul class="social social-circle">
								<li><a href="https://formacio.bq.ub.edu/~u254754/"><i class="icon-globe2"></i></a></li>
								<li><a href="https://www.linkedin.com/in/montse-garc%C3%ADa-cerqueda-685b80328/"><i class="icon-linkedin"></i></a></li>
								<li><a href="https://github.com/MGC29"><i class="icon-github"></i></a></li>
							</ul>
						</div>
					</div>
					<div class="col-md-6">
						<div class="fh5co-person text-center to-animate">
							<figure><img src="images/leyao.jpeg" alt="Image"></figure>
							<h3>Leyao Jin</h3>
							<span class="fh5co-position">Web Designer</span>
							<ul class="social social-circle">
								<li><a href="https://formacio.bq.ub.edu/~u255024/"><i class="icon-globe2"></i></a></li>
								<li><a href="https://www.linkedin.com/in/leyao-jin-223913226/"><i class="icon-linkedin"></i></a></li>
								<li><a href="https://github.com/LeyaoJin"><i class="icon-github"></i></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</section>
		

		<section id="fh5co-contact" data-section="contact">
			<div class="container">
				<div class="row">
					<div class="col-md-12 section-heading text-center">
						<h2 class="to-animate">Get In Touch</h2>
					</div>
				</div>
				<div class="row contact_form">
						<h3>Contact Form</h3>
						<div class="form-group ">
							<label for="name" class="sr-only">Name</label>
							<input id="name" class="form-control" placeholder="Name" type="text">
						</div>
						<div class="form-group ">
							<label for="email" class="sr-only">Email</label>
							<input id="email" class="form-control" placeholder="Email" type="email">
						</div>
						<div class="form-group ">
							<label for="phone" class="sr-only">Phone</label>
							<input id="phone" class="form-control" placeholder="Phone" type="text">
						</div>
						<div class="form-group ">
							<label for="message" class="sr-only">Message</label>
							<textarea name="" id="message" cols="30" rows="5" class="form-control" placeholder="Message"></textarea>
						</div>
						<div class="form-group ">
							<input class="btn btn-primary btn-lg" value="Send Message" type="submit">
						</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		
		
		<footer id="footer" role="contentinfo">
			<a href="#" class="gotop js-gotop"><i class="icon-arrow-up2"></i></a>
			<div class="container">
				<div class="">
					<div class="col-md-12 text-center">
						<p>Data Bases and Web Development Project</p>
					</div>
				</div>

			</div>
		</footer>
		

		
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


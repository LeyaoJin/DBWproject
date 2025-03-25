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
                        <a class="navbar-brand" href="index.html">InDiMoMap</a>
                    </div>
                    <div id="navbar" class="navbar-collapse collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li class="active"><a href="index.html" data-nav-section="home" class="external"><span>Home</span></a></li>
                            <li><a href="map.html" data-nav-section="map" class="external"><span>Map</span></a></li>
                            <li><a href="quiz.html" data-nav-section="quiz" class="external"><span>Quiz</span></a></li>
                            <li><a href="index.html#team"class="external"><span>Team</span></a></li>
                            <li><a href="index.html#contact" data-nav-section="contact" class="external"><span>Contact</span></a></li>
                            <li><a href="login.html" data-nav-section="login" class="external"><span>Login</span></a></li>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>

        <section id="personal-account" data-section="personal" data-stellar-background-ratio="0.5">
            <div class="gradient-overlay"></div>
            <div class="personal-container">
                <div class="row">
                    <nav class="col-md-4 col-lg-3 d-md-block bg-dark sidebar text-white vh-100 p-3">
                        <h2 class="text-white">My Account</h2>
                        <ul class="personal-menu nav flex-column mt-4">
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#personal-info" onclick="showSection('personal-info')">Personal Info</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#search-history" onclick="showSection('search-history')">Search History</a>
                            </li>
                        </ul>
                    </nav>

                    <main class="col-md-8 ml-sm-auto col-lg-9 px-4 py-4">
                        <div id="personal-info" class="account-section">
                            <h1>Personal Information</h1>
                            <p>Username: John Doe</p>
                            <p>Name: John Doe</p>
                            <p>Email: john@example.com</p>
                            <p>Birthday: Jeje</p>
                            <p>Educational level: Jojo</p>
                        </div>

                        <div id="search-history" class="account-section" style="display:none;">
                            <h1>Search History</h1>
                            <div id="query-container" class="scrollable-query-list mt-3">
                                <!-- Aquí se generarán dinámicamente los botones con JS o PHP -->
                                <button class="btn btn-outline-primary text-start" onclick="goToQuery('VIH')">
                                    Search: “VIH” – 2025-03-22
                                </button>
                                <button class="btn btn-outline-primary text-start" onclick="goToQuery('VIH')">
                                    Search: “VIH” – 2025-03-22
                                </button>
                                <button class="btn btn-outline-primary text-start" onclick="goToQuery('VIH')">
                                    Search: “VIH” – 2025-03-22
                                </button>
                                <button class="btn btn-outline-primary text-start" onclick="goToQuery('VIH')">
                                    Search: “VIH” – 2025-03-22
                                </button>
                                <button class="btn btn-outline-primary text-start" onclick="goToQuery('VIH')">
                                    Search: “VIH” – 2025-03-22
                                </button>
                                <button class="btn btn-outline-primary text-start" onclick="goToQuery('VIH')">
                                    Search: “VIH” – 2025-03-22
                                </button>
                                <button class="btn btn-outline-primary text-start" onclick="goToQuery('VIH')">
                                    Search: “VIH” – 2025-03-22
                                </button>
        
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <button class="btn btn-secondary" id="prev-page-btn" onclick="prevPage()"
                                    style="display: none;">Previous</button>
                                <button class="btn btn-secondary ms-auto" id="next-page-btn" onclick="nextPage()"
                                    style="display: none;">Next</button>
                            </div>
                        </div>
                    </main>
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

;(function () {
	
	'use strict';



	// iPad and iPod detection	
	var isiPad = function(){
		return (navigator.platform.indexOf("iPad") != -1);
	};

	var isiPhone = function(){
	    return (
			(navigator.platform.indexOf("iPhone") != -1) || 
			(navigator.platform.indexOf("iPod") != -1)
	    );
	};

	// Parallax
	var parallax = function() {
		$(window).stellar();
	};



	// Burger Menu
	var burgerMenu = function() {

		$('body').on('click', '.js-fh5co-nav-toggle', function(event){

			event.preventDefault();

			if ( $('#navbar').is(':visible') ) {
				$(this).removeClass('active');
			} else {
				$(this).addClass('active');	
			}

			
			
		});

	};


	var goToTop = function() {

		$('.js-gotop').on('click', function(event){
			
			event.preventDefault();

			$('html, body').animate({
				scrollTop: $('html').offset().top
			}, 500);
			
			return false;
		});
	
	};


	// Page Nav
	var clickMenu = function() {

		$('#navbar a:not([class="external"])').click(function(event){
			var section = $(this).data('nav-section'),
				navbar = $('#navbar');

				if ( $('[data-section="' + section + '"]').length ) {
			    	$('html, body').animate({
			        	scrollTop: $('[data-section="' + section + '"]').offset().top
			    	}, 500);
			   }

		    if ( navbar.is(':visible')) {
		    	navbar.removeClass('in');
		    	navbar.attr('aria-expanded', 'false');
		    	$('.js-fh5co-nav-toggle').removeClass('active');
		    }

		    event.preventDefault();
		    return false;
		});


	};

	// Reflect scrolling in navigation
	var navActive = function(section) {

		var $el = $('#navbar > ul');
		$el.find('li').removeClass('active');
		$el.each(function(){
			$(this).find('a[data-nav-section="'+section+'"]').closest('li').addClass('active');
		});

	};

	var navigationSection = function() {

		var $section = $('section[data-section]');
		
		$section.waypoint(function(direction) {
		  	
		  	if (direction === 'down') {
		    	navActive($(this.element).data('section'));
		  	}
		}, {
	  		offset: '150px'
		});

		$section.waypoint(function(direction) {
		  	if (direction === 'up') {
		    	navActive($(this.element).data('section'));
		  	}
		}, {
		  	offset: function() { return -$(this.element).height() + 155; }
		});

	};


	


	// Window Scroll
	var windowScroll = function() {
		var lastScrollTop = 0;

		$(window).scroll(function(event){

		   	var header = $('#fh5co-header'),
				scrlTop = $(this).scrollTop();

			if ( scrlTop > 500 && scrlTop <= 2000 ) {
				header.addClass('navbar-fixed-top fh5co-animated slideInDown');
			} else if ( scrlTop <= 500) {
				if ( header.hasClass('navbar-fixed-top') ) {
					header.addClass('navbar-fixed-top fh5co-animated slideOutUp');
					setTimeout(function(){
						header.removeClass('navbar-fixed-top fh5co-animated slideInDown slideOutUp');
					}, 100 );
				}
			} 
			
		});
	};



	// Animations
	// Home

	var homeAnimate = function() {
		if ( $('#fh5co-home').length > 0 ) {	

			$('#fh5co-home').waypoint( function( direction ) {
										
				if( direction === 'down' && !$(this.element).hasClass('animated') ) {


					setTimeout(function() {
						$('#fh5co-home .to-animate').each(function( k ) {
							var el = $(this);
							
							setTimeout ( function () {
								el.addClass('fadeInUp animated');
							},  k * 200, 'easeInOutExpo' );
							
						});
					}, 200);

					
					$(this.element).addClass('animated');
						
				}
			} , { offset: '80%' } );

		}
	};


	var introAnimate = function() {
		if ( $('#fh5co-intro').length > 0 ) {	

			$('#fh5co-intro').waypoint( function( direction ) {
										
				if( direction === 'down' && !$(this.element).hasClass('animated') ) {


					setTimeout(function() {
						$('#fh5co-intro .to-animate').each(function( k ) {
							var el = $(this);
							
							setTimeout ( function () {
								el.addClass('fadeInRight animated');
							},  k * 200, 'easeInOutExpo' );
							
						});
					}, 1000);

					
					$(this.element).addClass('animated');
						
				}
			} , { offset: '80%' } );

		}
	};

	var workAnimate = function() {
		if ( $('#fh5co-work').length > 0 ) {	

			$('#fh5co-work').waypoint( function( direction ) {
										
				if( direction === 'down' && !$(this.element).hasClass('animated') ) {


					setTimeout(function() {
						$('#fh5co-work .to-animate').each(function( k ) {
							var el = $(this);
							
							setTimeout ( function () {
								el.addClass('fadeInUp animated');
							},  k * 200, 'easeInOutExpo' );
							
						});
					}, 200);

					
					$(this.element).addClass('animated');
						
				}
			} , { offset: '80%' } );

		}
	};

	var servicesAnimate = function() {
		var services = $('#fh5co-services');
		if ( services.length > 0 ) {	

			services.waypoint( function( direction ) {
										
				if( direction === 'down' && !$(this.element).hasClass('animated') ) {

					var sec = services.find('.to-animate').length,
						sec = parseInt((sec * 200) + 400);

					setTimeout(function() {
						services.find('.to-animate').each(function( k ) {
							var el = $(this);
							
							setTimeout ( function () {
								el.addClass('fadeInUp animated');
							},  k * 200, 'easeInOutExpo' );
							
						});
					}, 200);

					setTimeout(function() {
						services.find('.to-animate-2').each(function( k ) {
							var el = $(this);
							
							setTimeout ( function () {
								el.addClass('bounceIn animated');
							},  k * 200, 'easeInOutExpo' );
							
						});
					}, sec);


					
					$(this.element).addClass('animated');
						
				}
			} , { offset: '80%' } );

		}
	};

	var teamAnimate = function() {
		var team = $('#fh5co-team');
		if ( team.length > 0 ) {	

			team.waypoint( function( direction ) {
										
				if( direction === 'down' && !$(this.element).hasClass('animated') ) {


					setTimeout(function() {
						team.find('.to-animate').each(function( k ) {
							var el = $(this);
							
							setTimeout ( function () {
								el.addClass('fadeInUp animated');
							},  k * 200, 'easeInOutExpo' );
							
						});
					}, 200);

					

					$(this.element).addClass('animated');
						
				}
			} , { offset: '80%' } );

		}
	};

	var countersAnimate = function() {
		var counters = $('#fh5co-counters');
		if ( counters.length > 0 ) {	

			counters.waypoint( function( direction ) {
										
				if( direction === 'down' && !$(this.element).hasClass('animated') ) {

					var sec = counters.find('.to-animate').length,
						sec = parseInt((sec * 200) + 400);

					setTimeout(function() {
						counters.find('.to-animate').each(function( k ) {
							var el = $(this);
							
							setTimeout ( function () {
								el.addClass('fadeInUp animated');
							},  k * 200, 'easeInOutExpo' );
							
						});
					}, 200);

					setTimeout(function() {
						counters.find('.js-counter').countTo({
						 	formatter: function (value, options) {
				      		return value.toFixed(options.decimals);
				   		},
						});
					}, 400);

					setTimeout(function() {
						counters.find('.to-animate-2').each(function( k ) {
							var el = $(this);
							
							setTimeout ( function () {
								el.addClass('bounceIn animated');
							},  k * 200, 'easeInOutExpo' );
							
						});
					}, sec);

					

					

					$(this.element).addClass('animated');
						
				}
			} , { offset: '80%' } );

		}
	};


	var contactAnimate = function() {
		var contact = $('#fh5co-contact');
		if ( contact.length > 0 ) {	

			contact.waypoint( function( direction ) {
										
				if( direction === 'down' && !$(this.element).hasClass('animated') ) {

					setTimeout(function() {
						contact.find('.to-animate').each(function( k ) {
							var el = $(this);
							
							setTimeout ( function () {
								el.addClass('fadeInUp animated');
							},  k * 200, 'easeInOutExpo' );
							
						});
					}, 200);

					$(this.element).addClass('animated');
						
				}
			} , { offset: '80%' } );

		}
	};
	
	var introquizAnimate = function() {
		var quiz = $('#introquiz');
		if ( quiz.length > 0 ) {	

			quiz.waypoint( function( direction ) {
										
				if( direction === 'down' && !$(this.element).hasClass('animated') ) {


					setTimeout(function() {
						quiz.find('.to-animate').each(function( k ) {
							var el = $(this);
							
							setTimeout ( function () {
								el.addClass('fadeInUp animated');
							},  k * 200, 'easeInOutExpo' );
							
						});
					}, 200);

					

					$(this.element).addClass('animated');
						
				}
			} , { offset: '80%' } );

		}
	};
	
	// Document on load.
	$(function(){

		parallax();

		burgerMenu();

		clickMenu();

		windowScroll();

		navigationSection();

		goToTop();
	
		// Animations
		homeAnimate();
		introAnimate();
		workAnimate();
		servicesAnimate();
		teamAnimate();
		countersAnimate();
		contactAnimate();
		introquizAnimate();
		

	});


}());


document.addEventListener("DOMContentLoaded", function() {
    console.log("✅ JS loaded correctly");

    const urlParams = new URLSearchParams(window.location.search);
    const errorMessage = urlParams.get('error');

    if (errorMessage) {
        showError(decodeURIComponent(errorMessage));
    }

    // Rellenar los campos de filtro si vienen por URL
    const filters = ["year", "gender", "continent", "country"];
    filters.forEach(filter => {
        const value = urlParams.get(filter);
        if (value !== null) {
            const input = document.getElementById(`search-${filter}`);
            if (input) input.value = decodeURIComponent(value);
        }
    });

    window.applyFilter = function(event) {
        if (event) event.preventDefault();

        const searchQuery = document.getElementById('search-bar').value.trim();
        if (!searchQuery) return showError('Please enter a search term.');

        const filterValues = {
            year: document.getElementById('search-year').value.trim(),
            gender: document.getElementById('search-gender').value,
            continent: document.getElementById('search-continent').value,
            country: document.getElementById('search-country').value.trim(),
        };

        const queryParams = new URLSearchParams({ query: searchQuery });
        Object.keys(filterValues).forEach(key => {
            if (filterValues[key]) {
                queryParams.set(key, filterValues[key]);
            }
        });

        window.location.href = `index.php?${queryParams.toString()}`;
    };

    const filterBtn = document.getElementById('filter-btn');
    const dropdown = document.getElementById('filter-dropdown');
    const searchBar = document.getElementById('search-bar');
    
    if (filterBtn && dropdown && searchBar) {
        filterBtn.addEventListener('click', toggleFilter);
        
        
        function showError(message) {
            const phpErrorMessage = document.querySelector('.search-bar-error-message');

            if (phpErrorMessage) {
                phpErrorMessage.textContent = message;  // Optional - update the message if needed

                // Apply shake effect to the search bar
                searchBar.classList.add('shake');
                setTimeout(() => searchBar.classList.remove('shake'), 300);
            }
        }


        function toggleFilter(event) {
            event.stopPropagation();
            const isVisible = dropdown.style.display === 'block';
            isVisible ? hideDropdown() : showDropdown();
        }

       function showDropdown() {
            dropdown.style.display = 'block'; // Mostrar el dropdown
            filterBtn.classList.add('is-open');
        }

        function hideDropdown() {
            dropdown.style.display = 'none'; // Ocultar el dropdown
            filterBtn.classList.remove('is-open');
        }


        if (filterBtn) {
            filterBtn.addEventListener('click', toggleFilter);
        }

        document.addEventListener('click', hideDropdown);
        dropdown.addEventListener('click', function(event) {
            event.stopPropagation();
        });

        const searchZone = document.querySelector('.search_zone');
        if (searchZone) {
            Object.assign(searchZone.style, {
                display: 'flex',
                flexDirection: 'row',
                alignItems: 'center',
                gap: '10px',
            });
        }

        if (filterBtn) filterBtn.style.marginLeft = '10px';
    } else {
        console.log("🔔 Filter elements not found - skipping filter initialization.");
    }

    function login() {
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        if (username === "" || password === "") {
            alert("Please enter both username and password.");
            return;
            }

        // Here you would typically send the data to your backend for verification
        // For now, we are just checking hardcoded values for demonstration
        if (username === "user" && password === "password123") {
            alert("Login successful!");
            window.location.href = "map.html";  // Redirect to the map page after successful login
        } else {
            alert("Invalid credentials. Please try again.");
        }
    }
    
    
    const isSignupPage = document.getElementById('signupForm') !== null;
    const submitButton = document.getElementById("submitButton");

    if (isSignupPage) {
        console.log("📄 Running registration field validation");

        const fields = ["name", "surname", "username", "email", "password", "confirmPassword",  "birthdate", "gender", "education"];
        
         function validateField(field) {
            const value = document.getElementById(field).value.trim();
            const errorMsg = document.getElementById(field + "Error");
            errorMsg.textContent = "";

            if (value === "") {
                errorMsg.textContent = "This field cannot be empty.";
                return false;
            }

            if (field === "email") {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    errorMsg.textContent = "Please enter a valid email address.";
                    return false;
                }
            }

            if (field === "password") {
                if (value.length < 8 || !/\d/.test(value)) {
                    errorMsg.textContent = "Minimum of 8 characters and contain one number.";
                    return false;
                }
            }

            if (field === "confirmPassword") {
                const password = document.getElementById("password").value;
                if (value !== password) {
                    errorMsg.textContent = "Passwords do not match.";
                    return false;
                }
            }

			// Montse -> added check for the birth date
			if (field === "birthdate") {
				const birthdate = new Date(value);
				const today = new Date();
				if (birthdate > today) {
					errorMsg.textContent = "Birthdate cannot be in the future.";
					return false;
				}

			}

			// Montse -> added check for the gender
			if (field === "gender") {
				const selectElement = document.getElementById(field);
				if (selectElement.selectedIndex === 0) {
					errorMsg.textContent = "Please select your gender.";
					return false;
				}
			}

			// Montse -> added check for the education
			if (field === "education") {
				const selectElement = document.getElementById(field);
				if (selectElement.selectedIndex === 0) {
					errorMsg.textContent = "Please select your education level.";
					return false;
				}
			}
			
            return true;
        }

        fields.forEach(field => {
            const input = document.getElementById(field);
            if (input) {
                console.log("Attaching blur event for", field);
                input.addEventListener("blur", () => validateField(field));
            } else {
                console.warn(`Field ${field} not found in the form.`);
            }
        });

		signupForm.addEventListener("input", function () {
            let allValid = true;

            fields.forEach(field => {
                const valid = validateField(field);
                if (!valid) allValid = false;
            });

			submitButton.disabled = !allValid;
        });
    }
    
    function showNextButton(index) {
        document.getElementById('next' + index).style.display = "block";
    }

    function nextQuestion(currentIndex) {
        document.getElementById('question' + currentIndex).classList.remove('active');
        document.getElementById('question' + (currentIndex + 1)).classList.add('active');
    }
});


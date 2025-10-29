<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TravHub Global Limited - Your Trusted Travel Partner</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1A2039;
            --secondary-color: #50BC81;
            --title-font: 700 25px 'Poppins', sans-serif;
            --subtitle-font: 600 15px 'Poppins', sans-serif;
            --paragraph-font: 400 10px 'Poppins', sans-serif;
            --light-bg: #f8f9fa;
            --white: #ffffff;
            --gray-light: #e9ecef;
            --gray: #6c757d;
            --danger: #e74c3c;
            --warning: #f39c12;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--primary-color);
            line-height: 1.6;
            background-color: var(--white);
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header Styles */
        header {
            background-color: var(--white);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
        }

        .logo {
            font: var(--title-font);
            color: var(--primary-color);
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .logo span {
            color: var(--secondary-color);
        }

        .logo-icon {
            margin-right: 10px;
            font-size: 28px;
        }

        nav ul {
            display: flex;
            list-style: none;
        }

        nav ul li {
            margin-left: 25px;
        }

        nav ul li a {
            font: var(--subtitle-font);
            color: var(--primary-color);
            text-decoration: none;
            transition: color 0.3s;
        }

        nav ul li a:hover {
            color: var(--secondary-color);
        }

        .auth-buttons {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 8px 20px;
            border-radius: 4px;
            font: var(--subtitle-font);
            cursor: pointer;
            transition: all 0.3s;
            border: none;
        }

        .btn-login {
            background-color: transparent;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }

        .btn-signup {
            background-color: var(--secondary-color);
            color: var(--white);
        }

        .btn-login:hover {
            background-color: var(--primary-color);
            color: var(--white);
        }

        .btn-signup:hover {
            background-color: #3e9c6a;
        }

        .mobile-menu {
            display: none;
            font-size: 24px;
            cursor: pointer;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(26, 32, 57, 0.8), rgba(26, 32, 57, 0.8)), url('https://images.unsplash.com/photo-1488646953014-85cb44e25828?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: var(--white);
            padding: 100px 0;
            text-align: center;
        }

        .hero h1 {
            font: var(--title-font);
            font-size: 48px;
            margin-bottom: 20px;
        }

        .hero p {
            font: var(--subtitle-font);
            font-size: 18px;
            max-width: 700px;
            margin: 0 auto 30px;
            opacity: 0.9;
        }

        .search-container {
            background-color: var(--white);
            border-radius: 8px;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .search-form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .form-group {
            flex: 1;
            min-width: 200px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font: var(--subtitle-font);
            color: var(--primary-color);
            margin-bottom: 8px;
        }

        .form-group select,
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--gray-light);
            border-radius: 4px;
            font: var(--paragraph-font);
            font-size: 14px;
        }

        .search-btn {
            background-color: var(--secondary-color);
            color: var(--white);
            border: none;
            padding: 12px 30px;
            border-radius: 4px;
            font: var(--subtitle-font);
            cursor: pointer;
            transition: background-color 0.3s;
            align-self: flex-end;
        }

        .search-btn:hover {
            background-color: #3e9c6a;
        }

        /* Services Section */
        .services {
            padding: 80px 0;
            background-color: var(--light-bg);
        }

        .section-title {
            font: var(--title-font);
            text-align: center;
            margin-bottom: 50px;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background-color: var(--secondary-color);
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }

        .service-card {
            background-color: var(--white);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .service-icon {
            background-color: var(--primary-color);
            color: var(--white);
            font-size: 40px;
            padding: 20px;
            text-align: center;
        }

        .service-content {
            padding: 20px;
        }

        .service-content h3 {
            font: var(--subtitle-font);
            margin-bottom: 10px;
            color: var(--primary-color);
        }

        .service-content p {
            font: var(--paragraph-font);
            color: var(--gray);
            margin-bottom: 15px;
        }

        .service-link {
            font: var(--paragraph-font);
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
        }

        .service-link i {
            margin-left: 5px;
            transition: transform 0.3s;
        }

        .service-link:hover i {
            transform: translateX(5px);
        }

        /* Packages Section */
        .packages {
            padding: 80px 0;
        }

        .packages-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .package-card {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
            cursor: pointer;
        }

        .package-card:hover {
            transform: translateY(-5px);
        }

        .package-image {
            height: 200px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .package-price {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: var(--secondary-color);
            color: var(--white);
            padding: 5px 10px;
            border-radius: 4px;
            font: var(--subtitle-font);
        }

        .package-content {
            padding: 20px;
        }

        .package-content h3 {
            font: var(--subtitle-font);
            margin-bottom: 10px;
            color: var(--primary-color);
        }

        .package-meta {
            display: flex;
            margin-bottom: 15px;
            font: var(--paragraph-font);
            color: var(--gray);
        }

        .package-meta span {
            margin-right: 15px;
            display: flex;
            align-items: center;
        }

        .package-meta i {
            margin-right: 5px;
        }

        .package-content p {
            font: var(--paragraph-font);
            color: var(--gray);
            margin-bottom: 15px;
        }

        .package-btn {
            display: inline-block;
            background-color: var(--primary-color);
            color: var(--white);
            padding: 8px 15px;
            border-radius: 4px;
            font: var(--paragraph-font);
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .package-btn:hover {
            background-color: var(--secondary-color);
        }

        /* Package Details Modal */
        .package-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
            padding: 20px;
            overflow-y: auto;
        }

        .package-modal.active {
            opacity: 1;
            visibility: visible;
        }

        .package-details {
            background-color: var(--white);
            border-radius: 8px;
            width: 100%;
            max-width: 1000px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            box-shadow: 0 5px 30px rgba(0, 0, 0, 0.3);
        }

        .close-modal {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: var(--white);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            cursor: pointer;
            z-index: 10;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .package-hero {
            height: 400px;
            background-size: cover;
            background-position: center;
            position: relative;
            border-radius: 8px 8px 0 0;
        }

        .package-hero-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
            color: var(--white);
            padding: 30px;
        }

        .package-hero-content h1 {
            font: var(--title-font);
            font-size: 32px;
            margin-bottom: 10px;
        }

        .package-hero-content .location {
            display: flex;
            align-items: center;
            font: var(--subtitle-font);
            margin-bottom: 15px;
        }

        .package-hero-content .location i {
            margin-right: 8px;
        }

        .package-hero-content .price {
            font: var(--title-font);
            font-size: 28px;
            color: var(--secondary-color);
        }

        .package-body {
            padding: 30px;
        }

        .package-tabs {
            display: flex;
            border-bottom: 1px solid var(--gray-light);
            margin-bottom: 25px;
        }

        .tab {
            padding: 12px 20px;
            font: var(--subtitle-font);
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }

        .tab.active {
            border-bottom: 3px solid var(--secondary-color);
            color: var(--secondary-color);
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .overview-content h2 {
            font: var(--title-font);
            font-size: 22px;
            margin-bottom: 15px;
        }

        .overview-content p {
            font: var(--paragraph-font);
            margin-bottom: 15px;
            line-height: 1.8;
        }

        .highlights {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }

        .highlight-item {
            display: flex;
            align-items: center;
        }

        .highlight-icon {
            width: 40px;
            height: 40px;
            background-color: rgba(80, 188, 129, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: var(--secondary-color);
        }

        .highlight-text h4 {
            font: var(--subtitle-font);
            margin-bottom: 5px;
        }

        .highlight-text p {
            font: var(--paragraph-font);
            color: var(--gray);
            margin: 0;
        }

        .itinerary-day {
            margin-bottom: 30px;
            border-left: 3px solid var(--secondary-color);
            padding-left: 20px;
            position: relative;
        }

        .itinerary-day::before {
            content: '';
            position: absolute;
            left: -8px;
            top: 0;
            width: 13px;
            height: 13px;
            border-radius: 50%;
            background-color: var(--secondary-color);
        }

        .itinerary-day h3 {
            font: var(--subtitle-font);
            margin-bottom: 10px;
        }

        .itinerary-day p {
            font: var(--paragraph-font);
            margin-bottom: 10px;
        }

        .inclusion-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .inclusion-item i {
            margin-right: 10px;
            color: var(--secondary-color);
        }

        .exclusion-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .exclusion-item i {
            margin-right: 10px;
            color: var(--danger);
        }

        .booking-card {
            background-color: var(--light-bg);
            border-radius: 8px;
            padding: 25px;
            margin-top: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .booking-card h3 {
            font: var(--subtitle-font);
            margin-bottom: 15px;
        }

        .booking-price {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--gray-light);
        }

        .booking-price .price {
            font: var(--title-font);
            font-size: 28px;
            color: var(--secondary-color);
        }

        .booking-price .per-person {
            font: var(--paragraph-font);
            color: var(--gray);
        }

        .booking-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .booking-form .form-group {
            min-width: auto;
        }

        .booking-form .full-width {
            grid-column: 1 / -1;
        }

        .book-now-btn {
            background-color: var(--secondary-color);
            color: var(--white);
            border: none;
            padding: 12px;
            border-radius: 4px;
            font: var(--subtitle-font);
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
            margin-top: 10px;
        }

        .book-now-btn:hover {
            background-color: #3e9c6a;
        }

        /* Testimonials Section */
        .testimonials {
            padding: 80px 0;
            background-color: var(--light-bg);
        }

        .testimonials-container {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
        }

        .testimonial-slide {
            background-color: var(--white);
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .testimonial-text {
            font: var(--paragraph-font);
            font-size: 16px;
            font-style: italic;
            margin-bottom: 20px;
            color: var(--gray);
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .author-image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 15px;
        }

        .author-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .author-info h4 {
            font: var(--subtitle-font);
            margin-bottom: 5px;
        }

        .author-info p {
            font: var(--paragraph-font);
            color: var(--gray);
        }

        .testimonial-nav {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        .testimonial-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: var(--gray-light);
            margin: 0 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .testimonial-dot.active {
            background-color: var(--secondary-color);
        }

        /* Newsletter Section */
        .newsletter {
            padding: 60px 0;
            background-color: var(--primary-color);
            color: var(--white);
            text-align: center;
        }

        .newsletter h2 {
            font: var(--title-font);
            margin-bottom: 15px;
        }

        .newsletter p {
            font: var(--paragraph-font);
            font-size: 16px;
            margin-bottom: 30px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .newsletter-form {
            display: flex;
            max-width: 500px;
            margin: 0 auto;
        }

        .newsletter-form input {
            flex: 1;
            padding: 12px 15px;
            border: none;
            border-radius: 4px 0 0 4px;
            font: var(--paragraph-font);
            font-size: 14px;
        }

        .newsletter-form button {
            background-color: var(--secondary-color);
            color: var(--white);
            border: none;
            padding: 12px 25px;
            border-radius: 0 4px 4px 0;
            font: var(--subtitle-font);
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .newsletter-form button:hover {
            background-color: #3e9c6a;
        }

        /* Footer */
        footer {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 60px 0 30px;
        }

        .footer-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-col h3 {
            font: var(--subtitle-font);
            margin-bottom: 20px;
            position: relative;
        }

        .footer-col h3::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 30px;
            height: 2px;
            background-color: var(--secondary-color);
        }

        .footer-col p {
            font: var(--paragraph-font);
            margin-bottom: 15px;
            opacity: 0.8;
        }

        .footer-col ul {
            list-style: none;
        }

        .footer-col ul li {
            margin-bottom: 10px;
        }

        .footer-col ul li a {
            font: var(--paragraph-font);
            color: var(--white);
            text-decoration: none;
            opacity: 0.8;
            transition: opacity 0.3s;
        }

        .footer-col ul li a:hover {
            opacity: 1;
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: var(--white);
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .social-links a:hover {
            background-color: var(--secondary-color);
        }

        .copyright {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font: var(--paragraph-font);
            opacity: 0.7;
        }

        /* Responsive Styles */
        @media (max-width: 992px) {
            .hero h1 {
                font-size: 36px;
            }

            .hero p {
                font-size: 16px;
            }
        }

        @media (max-width: 768px) {
            nav ul {
                display: none;
            }

            .mobile-menu {
                display: block;
            }

            .auth-buttons {
                display: none;
            }

            .hero {
                padding: 80px 0;
            }

            .hero h1 {
                font-size: 32px;
            }

            .search-form {
                flex-direction: column;
            }

            .search-btn {
                align-self: stretch;
            }

            .package-hero {
                height: 300px;
            }

            .booking-form {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .hero h1 {
                font-size: 28px;
            }

            .section-title {
                font-size: 22px;
            }

            .package-tabs {
                flex-wrap: wrap;
            }

            .tab {
                flex: 1;
                min-width: 120px;
                text-align: center;
            }

            .newsletter-form {
                flex-direction: column;
            }

            .newsletter-form input {
                border-radius: 4px;
                margin-bottom: 10px;
            }

            .newsletter-form button {
                border-radius: 4px;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header>
        <div class="container header-container">
            <a href="#" class="logo">
                <div class="logo-icon">✈</div>
                TravHub<span>Global</span>
            </a>

            <nav>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Packages</a></li>
                    <li><a href="#">Visa</a></li>
                    <li><a href="#">Activities</a></li>
                    <li><a href="#">Umrah</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </nav>

            <div class="auth-buttons">
                @guest
                    <!-- Only show if NOT logged in -->
                    <a href="{{ route('login') }}" class="btn btn-login">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-signup">Sign Up</a>
                @endguest

                @auth
                    <!-- Only show if logged in -->
                    <a href="{{ route('dashboard') }}" class="btn btn-signup">Dashboard</a>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-logout">Logout</button>
                    </form>
                @endauth
            </div>

            <div class="mobile-menu">☰</div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Discover Amazing Destinations</h1>
            <p>Explore the world with our curated travel packages, visa services, and exciting activities. Your perfect
                journey starts here.</p>

            <div class="search-container">
                <form class="search-form">
                    <div class="form-group">
                        <label for="destination">Destination</label>
                        <select id="destination">
                            <option value="">Select Destination</option>
                            <option value="thailand">Thailand</option>
                            <option value="malaysia">Malaysia</option>
                            <option value="dubai">Dubai</option>
                            <option value="singapore">Singapore</option>
                            <option value="bali">Bali</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="service-type">Service Type</label>
                        <select id="service-type">
                            <option value="">Select Service</option>
                            <option value="package">Package Tour</option>
                            <option value="visa">Visa Service</option>
                            <option value="activities">Activities</option>
                            <option value="umrah">Umrah Package</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="travel-date">Travel Date</label>
                        <input type="date" id="travel-date">
                    </div>

                    <button type="submit" class="search-btn">Search</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services">
        <div class="container">
            <h2 class="section-title">Our Services</h2>

            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">🏝️</div>
                    <div class="service-content">
                        <h3>Package Tours</h3>
                        <p>All-inclusive travel packages to exotic destinations with accommodation, transfers, and
                            guided tours.</p>
                        <a href="#" class="service-link">Explore Packages <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <div class="service-card">
                    <div class="service-icon">🛂</div>
                    <div class="service-content">
                        <h3>Visa Services</h3>
                        <p>Hassle-free visa processing with expert guidance and high success rate for various countries.
                        </p>
                        <a href="#" class="service-link">Apply Now <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <div class="service-card">
                    <div class="service-icon">🎯</div>
                    <div class="service-content">
                        <h3>Activities</h3>
                        <p>Book exciting activities and experiences to make your trip memorable and adventurous.</p>
                        <a href="#" class="service-link">Find Activities <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <div class="service-card">
                    <div class="service-icon">🕌</div>
                    <div class="service-content">
                        <h3>Umrah Packages</h3>
                        <p>Spiritual journeys with comprehensive Umrah packages including flights, hotels, and guidance.
                        </p>
                        <a href="#" class="service-link">View Packages <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Packages Section -->
    <section class="packages">
        <div class="container">
            <h2 class="section-title">Popular Packages</h2>

            <div class="packages-grid">
                <div class="package-card" data-package="bangkok-phuket">
                    <div class="package-image"
                        style="background-image: url('https://images.unsplash.com/photo-1552465011-b4e21bf6e79a?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');">
                        <div class="package-price">BDT ‌‌44999</div>
                    </div>
                    <div class="package-content">
                        <h3>5 Nights 6 Days Bangkok & Phuket Honeymoon Tour</h3>
                        <div class="package-meta">
                            <span><i class="fas fa-clock"></i> 6 Days</span>
                            <span><i class="fas fa-map-marker-alt"></i> Thailand</span>
                            <span><i class="fas fa-star"></i> 4.8</span>
                        </div>
                        <p>Experience the perfect blend of vibrant city life and tropical paradise on this romantic
                            Thailand honeymoon.</p>
                        <a href="#" class="package-btn view-details">View Details</a>
                    </div>
                </div>

                <div class="package-card" data-package="dubai-luxury">
                    <div class="package-image"
                        style="background-image: url('https://images.unsplash.com/photo-1534008897995-27a23e859048?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');">
                        <div class="package-price">BDT 59999</div>
                    </div>
                    <div class="package-content">
                        <h3>Dubai Luxury Experience</h3>
                        <div class="package-meta">
                            <span><i class="fas fa-clock"></i> 5 Days</span>
                            <span><i class="fas fa-map-marker-alt"></i> Dubai</span>
                            <span><i class="fas fa-star"></i> 4.9</span>
                        </div>
                        <p>Indulge in luxury with desert safaris, Burj Khalifa visits, and premium shopping experiences.
                        </p>
                        <a href="#" class="package-btn view-details">View Details</a>
                    </div>
                </div>

                <div class="package-card" data-package="bali-paradise">
                    <div class="package-image"
                        style="background-image: url('https://images.unsplash.com/photo-1528164344705-47542687000d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');">
                        <div class="package-price">BDT 29999</div>
                    </div>
                    <div class="package-content">
                        <h3>Bali Tropical Paradise</h3>
                        <div class="package-meta">
                            <span><i class="fas fa-clock"></i> 6 Days</span>
                            <span><i class="fas fa-map-marker-alt"></i> Bali</span>
                            <span><i class="fas fa-star"></i> 4.7</span>
                        </div>
                        <p>Relax in Bali's beautiful beaches, rice terraces, and spiritual temples in this tropical
                            getaway.</p>
                        <a href="#" class="package-btn view-details">View Details</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Package Details Modal -->
    <div class="package-modal" id="packageModal">
        <div class="package-details">
            <div class="close-modal" id="closeModal">
                <i class="fas fa-times"></i>
            </div>

            <div class="package-hero" id="packageHero">
                <div class="package-hero-content">
                    <h1 id="packageTitle">5 Nights 6 Days Bangkok & Phuket Honeymoon Tour</h1>
                    <div class="location">
                        <i class="fas fa-map-marker-alt"></i>
                        <span id="packageLocation">Thailand</span>
                    </div>
                    <div class="price" id="packagePrice">BDT ‌‌44999</div>
                </div>
            </div>

            <div class="package-body">
                <div class="package-tabs">
                    <div class="tab active" data-tab="overview">Overview</div>
                    <div class="tab" data-tab="itinerary">Itinerary</div>
                    <div class="tab" data-tab="inclusions">Inclusions/Exclusions</div>
                    <div class="tab" data-tab="policies">Policies</div>
                </div>

                <div class="tab-content active" id="overview">
                    <div class="overview-content">
                        <h2>About the Tour</h2>
                        <p>This 5 nights 6 days Thailand honeymoon package offers the perfect blend of vibrant city life
                            and tropical paradise. Experience the bustling streets of Bangkok and the serene beaches of
                            Phuket on this romantic journey designed for newlyweds.</p>

                        <h2>Tour Highlights</h2>
                        <div class="highlights">
                            <div class="highlight-item">
                                <div class="highlight-icon">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div class="highlight-text">
                                    <h4>Romantic Experiences</h4>
                                    <p>Candlelight dinners & couple activities</p>
                                </div>
                            </div>
                            <div class="highlight-item">
                                <div class="highlight-icon">
                                    <i class="fas fa-umbrella-beach"></i>
                                </div>
                                <div class="highlight-text">
                                    <h4>Beach Paradise</h4>
                                    <p>Relax on Phuket's stunning beaches</p>
                                </div>
                            </div>
                            <div class="highlight-item">
                                <div class="highlight-icon">
                                    <i class="fas fa-monument"></i>
                                </div>
                                <div class="highlight-text">
                                    <h4>Cultural Immersion</h4>
                                    <p>Explore Bangkok's temples and palaces</p>
                                </div>
                            </div>
                            <div class="highlight-item">
                                <div class="highlight-icon">
                                    <i class="fas fa-ship"></i>
                                </div>
                                <div class="highlight-text">
                                    <h4>Island Hopping</h4>
                                    <p>Visit Phi Phi Islands & James Bond Island</p>
                                </div>
                            </div>
                        </div>

                        <h2>Quick Facts</h2>
                        <div class="highlights">
                            <div class="highlight-item">
                                <div class="highlight-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="highlight-text">
                                    <h4>Duration</h4>
                                    <p>5 Nights / 6 Days</p>
                                </div>
                            </div>
                            <div class="highlight-item">
                                <div class="highlight-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="highlight-text">
                                    <h4>Group Size</h4>
                                    <p>2 People (Customizable)</p>
                                </div>
                            </div>
                            <div class="highlight-item">
                                <div class="highlight-icon">
                                    <i class="fas fa-plane"></i>
                                </div>
                                <div class="highlight-text">
                                    <h4>Flights</h4>
                                    <p>International flights included</p>
                                </div>
                            </div>
                            <div class="highlight-item">
                                <div class="highlight-icon">
                                    <i class="fas fa-hotel"></i>
                                </div>
                                <div class="highlight-text">
                                    <h4>Accommodation</h4>
                                    <p>4-star hotels & resorts</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-content" id="itinerary">
                    <h2>Detailed Itinerary</h2>

                    <div class="itinerary-day">
                        <h3>Day 1: Arrival in Bangkok</h3>
                        <p>Arrive at Bangkok airport, meet our representative and transfer to your hotel. Check-in and
                            relax. In the evening, enjoy a romantic dinner cruise along the Chao Phraya River with
                            stunning views of Bangkok's illuminated temples and skyline.</p>
                    </div>

                    <div class="itinerary-day">
                        <h3>Day 2: Bangkok City Tour</h3>
                        <p>After breakfast, visit the Grand Palace and Wat Phra Kaew (Temple of the Emerald Buddha).
                            Continue to Wat Arun (Temple of Dawn) and enjoy a traditional Thai lunch. In the evening,
                            explore the vibrant Khao San Road or enjoy a couple's spa treatment.</p>
                    </div>

                    <div class="itinerary-day">
                        <h3>Day 3: Flight to Phuket</h3>
                        <p>After breakfast, transfer to the airport for your flight to Phuket. Upon arrival, check into
                            your beachfront resort. Spend the rest of the day relaxing on the beach or by the pool.
                            Enjoy a romantic seaside dinner in the evening.</p>
                    </div>

                    <div class="itinerary-day">
                        <h3>Day 4: Phi Phi Island Tour</h3>
                        <p>Embark on a full-day speedboat tour to the stunning Phi Phi Islands. Visit Maya Bay (featured
                            in the movie "The Beach"), go snorkeling in crystal-clear waters, and enjoy a beachside
                            lunch. Return to Phuket in the evening.</p>
                    </div>

                    <div class="itinerary-day">
                        <h3>Day 5: James Bond Island & Phang Nga Bay</h3>
                        <p>Take a canoe tour through the limestone caves and mangroves of Phang Nga Bay. Visit James
                            Bond Island, famous for its appearance in "The Man with the Golden Gun." Enjoy a sunset view
                            from Promthep Cape in the evening.</p>
                    </div>

                    <div class="itinerary-day">
                        <h3>Day 6: Departure</h3>
                        <p>After breakfast, check out from your hotel. Depending on your flight schedule, you may have
                            time for some last-minute shopping or beach time. Transfer to Phuket International Airport
                            for your departure flight.</p>
                    </div>
                </div>

                <div class="tab-content" id="inclusions">
                    <div class="highlights">
                        <div style="flex: 1;">
                            <h2>Inclusions</h2>
                            <div class="inclusion-item">
                                <i class="fas fa-check"></i>
                                <span>Accommodation in 4-star hotels</span>
                            </div>
                            <div class="inclusion-item">
                                <i class="fas fa-check"></i>
                                <span>Daily breakfast at the hotel</span>
                            </div>
                            <div class="inclusion-item">
                                <i class="fas fa-check"></i>
                                <span>All airport and inter-city transfers</span>
                            </div>
                            <div class="inclusion-item">
                                <i class="fas fa-check"></i>
                                <span>Sightseeing tours as per itinerary</span>
                            </div>
                            <div class="inclusion-item">
                                <i class="fas fa-check"></i>
                                <span>English speaking guide</span>
                            </div>
                            <div class="inclusion-item">
                                <i class="fas fa-check"></i>
                                <span>Entrance fees to monuments</span>
                            </div>
                            <div class="inclusion-item">
                                <i class="fas fa-check"></i>
                                <span>Bangkok to Phuket flight</span>
                            </div>
                            <div class="inclusion-item">
                                <i class="fas fa-check"></i>
                                <span>All taxes and service charges</span>
                            </div>
                        </div>

                        <div style="flex: 1;">
                            <h2>Exclusions</h2>
                            <div class="exclusion-item">
                                <i class="fas fa-times"></i>
                                <span>International airfare</span>
                            </div>
                            <div class="exclusion-item">
                                <i class="fas fa-times"></i>
                                <span>Visa fees</span>
                            </div>
                            <div class="exclusion-item">
                                <i class="fas fa-times"></i>
                                <span>Travel insurance</span>
                            </div>
                            <div class="exclusion-item">
                                <i class="fas fa-times"></i>
                                <span>Lunch and dinner (unless specified)</span>
                            </div>
                            <div class="exclusion-item">
                                <i class="fas fa-times"></i>
                                <span>Personal expenses</span>
                            </div>
                            <div class="exclusion-item">
                                <i class="fas fa-times"></i>
                                <span>Optional tours and activities</span>
                            </div>
                            <div class="exclusion-item">
                                <i class="fas fa-times"></i>
                                <span>Tips and gratuities</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-content" id="policies">
                    <h2>Cancellation Policy</h2>
                    <p>Cancellations made 30 days or more before departure: 90% refund</p>
                    <p>Cancellations made 15-29 days before departure: 50% refund</p>
                    <p>Cancellations made 8-14 days before departure: 25% refund</p>
                    <p>Cancellations made 7 days or less before departure: No refund</p>

                    <h2>Payment Policy</h2>
                    <p>25% of the total package cost to be paid at the time of booking</p>
                    <p>Remaining 75% to be paid 30 days before departure</p>

                    <h2>Other Policies</h2>
                    <p>Rates are subject to change based on hotel availability and flight fares</p>
                    <p>Check-in time at hotels is 2:00 PM, check-out time is 12:00 PM</p>
                    <p>Early check-in and late check-out are subject to availability and additional charges</p>
                </div>

                <div class="booking-card">
                    <h3>Book This Package</h3>
                    <div class="booking-price">
                        <div class="price">BDT ‌‌44999</div>
                        <div class="per-person">per person</div>
                    </div>

                    <form class="booking-form">
                        <div class="form-group">
                            <label for="checkin">Check-in Date</label>
                            <input type="date" id="checkin" required>
                        </div>

                        <div class="form-group">
                            <label for="checkout">Check-out Date</label>
                            <input type="date" id="checkout" required>
                        </div>

                        <div class="form-group">
                            <label for="travelers">Number of Travelers</label>
                            <select id="travelers" required>
                                <option value="1">1 Traveler</option>
                                <option value="2" selected>2 Travelers</option>
                                <option value="3">3 Travelers</option>
                                <option value="4">4 Travelers</option>
                                <option value="5">5 Travelers</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="room-type">Room Type</label>
                            <select id="room-type" required>
                                <option value="standard">Standard Room</option>
                                <option value="deluxe" selected>Deluxe Room</option>
                                <option value="suite">Suite</option>
                            </select>
                        </div>

                        <div class="form-group full-width">
                            <label for="special-requests">Special Requests</label>
                            <textarea id="special-requests" rows="3" placeholder="Any special requests or requirements..."></textarea>
                        </div>

                        <button type="submit" class="book-now-btn">Book Now</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials Section -->
    <section class="testimonials">
        <div class="container">
            <h2 class="section-title">What Our Customers Say</h2>

            <div class="testimonials-container">
                <div class="testimonial-slide">
                    <p class="testimonial-text">"TravHub made our Thailand trip absolutely seamless. The package was
                        well-organized, and their visa service was quick and efficient. Highly recommended!"</p>
                    <div class="testimonial-author">
                        <div class="author-image">
                            <img src="https://randomuser.me/api/portraits/women/45.jpg" alt="Sarah Johnson">
                        </div>
                        <div class="author-info">
                            <h4>Sarah Johnson</h4>
                            <p>Thailand Tour</p>
                        </div>
                    </div>
                </div>

                <div class="testimonial-nav">
                    <div class="testimonial-dot active"></div>
                    <div class="testimonial-dot"></div>
                    <div class="testimonial-dot"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="newsletter">
        <div class="container">
            <h2>Subscribe to Our Newsletter</h2>
            <p>Get the latest travel deals, visa updates, and exclusive offers directly in your inbox.</p>

            <form class="newsletter-form">
                <input type="email" placeholder="Enter your email address" required>
                <button type="submit">Subscribe</button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-container">
                <div class="footer-col">
                    <h3>TravHub Global Limited</h3>
                    <p>Your trusted partner for memorable travel experiences, visa services, and customized tour
                        packages.</p>
                    <div class="social-links">
                        <a href="https://www.facebook.com/travhubxyz" target="_blank" rel="noopener noreferrer"><i
                                class="fab fa-facebook-f"></i></a>
                        <a href="https://www.facebook.com/travhubxyz"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.facebook.com/travhubxyz"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.facebook.com/travhubxyz"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                <div class="footer-col">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="#">Home</a></li>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Packages</a></li>
                        <li><a href="#">Visa Services</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h3>Services</h3>
                    <ul>
                        <li><a href="#">Package Tours</a></li>
                        <li><a href="#">Visa Processing</a></li>
                        <li><a href="#">Travel Activities</a></li>
                        <li><a href="#">Umrah Packages</a></li>
                        <li><a href="#">Group Tours</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h3>Contact Us</h3>
                    <p><i class="fas fa-phone"></i> +8801611482773</p>
                    <p><i class="fas fa-envelope"></i> info@travhub.xyz</p>
                    <p><i class="fas fa-map-marker-alt"></i> House# 01, Road# 06, Sector# 03, Uttara, Dhaka-1230</p>
                </div>
            </div>

            <div class="copyright">
                <p>&copy; 2025 TravHub Global Limited. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Package data
        const packages = {
            'bangkok-phuket': {
                title: '5 Nights 6 Days Bangkok & Phuket Honeymoon Tour',
                location: 'Thailand',
                price: 'BDT ‌‌44999',
                image: 'https://images.unsplash.com/photo-1552465011-b4e21bf6e79a?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                description: 'This 5 nights 6 days Thailand honeymoon package offers the perfect blend of vibrant city life and tropical paradise. Experience the bustling streets of Bangkok and the serene beaches of Phuket on this romantic journey designed for newlyweds.'
            },
            'dubai-luxury': {
                title: 'Dubai Luxury Experience',
                location: 'Dubai, UAE',
                price: 'BDT 59999',
                image: 'https://images.unsplash.com/photo-1534008897995-27a23e859048?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                description: 'Indulge in luxury with desert safaris, Burj Khalifa visits, and premium shopping experiences in the dazzling city of Dubai.'
            },
            'bali-paradise': {
                title: 'Bali Tropical Paradise',
                location: 'Bali, Indonesia',
                price: 'BDT 299999',
                image: 'https://images.unsplash.com/photo-1528164344705-47542687000d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                description: 'Relax in Bali\'s beautiful beaches, rice terraces, and spiritual temples in this tropical getaway perfect for couples and families.'
            }
        };

        // DOM Elements
        const packageModal = document.getElementById('packageModal');
        const closeModalBtn = document.getElementById('closeModal');
        const packageHero = document.getElementById('packageHero');
        const packageTitle = document.getElementById('packageTitle');
        const packageLocation = document.getElementById('packageLocation');
        const packagePrice = document.getElementById('packagePrice');
        const viewDetailsButtons = document.querySelectorAll('.view-details');
        const tabs = document.querySelectorAll('.tab');
        const tabContents = document.querySelectorAll('.tab-content');

        // Open package details modal
        viewDetailsButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const packageCard = button.closest('.package-card');
                const packageId = packageCard.getAttribute('data-package');
                const packageData = packages[packageId];

                // Update modal content
                packageTitle.textContent = packageData.title;
                packageLocation.textContent = packageData.location;
                packagePrice.textContent = packageData.price;
                packageHero.style.backgroundImage = `url('${packageData.image}')`;

                // Show modal
                packageModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        });

        // Close modal
        closeModalBtn.addEventListener('click', () => {
            packageModal.classList.remove('active');
            document.body.style.overflow = 'auto';
        });

        // Close modal when clicking outside
        packageModal.addEventListener('click', (e) => {
            if (e.target === packageModal) {
                packageModal.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        });

        // Tab switching
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove active class from all tabs and contents
                tabs.forEach(t => t.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));

                // Add active class to clicked tab and corresponding content
                tab.classList.add('active');
                const tabId = tab.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });

        // Booking form submission
        document.querySelector('.booking-form').addEventListener('submit', (e) => {
            e.preventDefault();
            alert(
                'Thank you for your booking! Our travel consultant will contact you shortly to confirm your reservation.');
            packageModal.classList.remove('active');
            document.body.style.overflow = 'auto';
        });

        // Mobile menu toggle
        document.querySelector('.mobile-menu').addEventListener('click', function() {
            const nav = document.querySelector('nav ul');
            nav.style.display = nav.style.display === 'flex' ? 'none' : 'flex';
        });

        // Testimonial slider
        const dots = document.querySelectorAll('.testimonial-dot');
        dots.forEach((dot, index) => {
            dot.addEventListener('click', function() {
                // Remove active class from all dots
                dots.forEach(d => d.classList.remove('active'));
                // Add active class to clicked dot
                this.classList.add('active');
                // In a real implementation, you would change the testimonial content here
            });
        });

        // Newsletter form submission
        document.querySelector('.newsletter-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            alert(`Thank you for subscribing with ${email}! You'll receive our latest travel updates soon.`);
            this.reset();
        });

        // Search form submission
        document.querySelector('.search-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const destination = document.getElementById('destination').value;
            const serviceType = document.getElementById('service-type').value;
            const travelDate = document.getElementById('travel-date').value;

            if (!destination || !serviceType) {
                alert('Please select destination and service type to search.');
                return;
            }

            // In a real implementation, you would redirect to search results page
            alert(`Searching for ${serviceType} in ${destination} on ${travelDate || 'any date'}...`);
        });
    </script>
</body>

</html>

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

        nav ul li a:hover,
        nav ul li a.active {
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
            text-decoration: none;
            display: inline-block;
            text-align: center;
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
            font: 700 48px 'Poppins', sans-serif;
            margin-bottom: 20px;
        }

        .hero p {
            font: 600 18px 'Poppins', sans-serif;
            max-width: 700px;
            margin: 0 auto 30px;
            opacity: 0.9;
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(rgba(26, 32, 57, 0.8), rgba(26, 32, 57, 0.8)), url('https://images.unsplash.com/photo-1488646953014-85cb44e25828?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: var(--white);
            padding: 80px 0;
            text-align: center;
        }

        .page-header h1 {
            font: 700 48px 'Poppins', sans-serif;
            margin-bottom: 20px;
        }

        .page-header p {
            font: 600 18px 'Poppins', sans-serif;
            max-width: 700px;
            margin: 0 auto;
            opacity: 0.9;
        }

        /* Search & Filter Containers */
        .search-container,
        .filter-container {
            background-color: var(--white);
            border-radius: 8px;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .search-form,
        .filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .search-form {
            align-items: flex-end;
        }

        .filter-form {
            align-items: flex-end;
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
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--gray-light);
            border-radius: 4px;
            font: 400 14px 'Poppins', sans-serif;
        }

        .search-btn,
        .filter-btn {
            background-color: var(--secondary-color);
            color: var(--white);
            border: none;
            padding: 12px 30px;
            border-radius: 4px;
            font: var(--subtitle-font);
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .search-btn:hover,
        .filter-btn:hover {
            background-color: #3e9c6a;
        }

        /* Packages Filter Section */
        .packages-filter {
            padding: 40px 0;
            background-color: var(--light-bg);
        }

        /* Services Section */
        .services {
            padding: 80px 0;
            background-color: var(--light-bg);
        }

        .section-title {
            font: 700 25px 'Poppins', sans-serif;
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
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
        }

        .package-card {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            background-color: var(--white);
        }

        .package-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
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

        /* Package Hero Section */
        .package-hero-section {
            position: relative;
            margin-bottom: 40px;
        }

        .package-hero-image {
            height: 500px;
            background-size: cover;
            background-position: center;
            position: relative;
            display: flex;
            align-items: flex-end;
        }

        .package-hero-content {
            color: var(--white);
            padding: 40px 0;
            width: 100%;
        }

        .breadcrumb {
            font: 400 16px 'Poppins', sans-serif;
            margin-bottom: 15px;
            opacity: 0.9;
        }

        .breadcrumb a {
            color: var(--white);
            text-decoration: none;
            transition: opacity 0.3s;
        }

        .breadcrumb a:hover {
            opacity: 0.8;
        }

        .breadcrumb span {
            opacity: 0.7;
        }

        .package-hero-content h1 {
            font: 700 42px 'Poppins', sans-serif;
            margin-bottom: 15px;
            line-height: 1.2;
        }

        .package-location {
            display: flex;
            align-items: center;
            font: 600 18px 'Poppins', sans-serif;
            margin-bottom: 15px;
        }

        .package-location i {
            margin-right: 8px;
            opacity: 0.9;
        }

        .package-rating {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .stars {
            color: var(--warning);
        }

        .package-rating span {
            font: 400 16px 'Poppins', sans-serif;
            opacity: 0.9;
        }

        /* Package Details Layout */
        .package-details-section {
            padding: 40px 0;
        }

        .package-details-layout {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 40px;
            align-items: start;
        }

        /* Package Tabs */
        .package-tabs {
            display: flex;
            border-bottom: 1px solid var(--gray-light);
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .package-tabs .tab {
            padding: 15px 25px;
            font: 600 15px 'Poppins', sans-serif;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
            white-space: nowrap;
        }

        .package-tabs .tab.active {
            border-bottom: 3px solid var(--secondary-color);
            color: var(--secondary-color);
        }

        .package-tabs .tab:hover {
            color: var(--secondary-color);
        }

        /* Tab Contents */
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .tab-content h2 {
            font: 700 28px 'Poppins', sans-serif;
            margin-bottom: 20px;
            color: var(--primary-color);
        }

        .tab-content p {
            font: 400 16px 'Poppins', sans-serif;
            line-height: 1.8;
            margin-bottom: 20px;
            color: var(--gray);
        }

        /* Highlights Grid */
        .highlights {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin: 30px 0;
        }

        .highlight-item {
            display: flex;
            align-items: flex-start;
            padding: 20px;
            background-color: var(--light-bg);
            border-radius: 8px;
            transition: transform 0.3s;
        }

        .highlight-item:hover {
            transform: translateY(-5px);
        }

        .highlight-icon {
            width: 50px;
            height: 50px;
            background-color: rgba(80, 188, 129, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: var(--secondary-color);
            font-size: 20px;
            flex-shrink: 0;
        }

        .highlight-text h4 {
            font: 600 15px 'Poppins', sans-serif;
            margin-bottom: 8px;
            color: var(--primary-color);
        }

        .highlight-text p {
            font: 400 10px 'Poppins', sans-serif;
            color: var(--gray);
            margin: 0;
            line-height: 1.5;
        }

        /* Quick Facts */
        .quick-facts {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }

        .fact-item {
            display: flex;
            align-items: center;
            padding: 15px;
            background-color: var(--light-bg);
            border-radius: 8px;
        }

        .fact-item i {
            font-size: 24px;
            color: var(--secondary-color);
            margin-right: 15px;
            width: 30px;
            text-align: center;
        }

        .fact-item h4 {
            font: 600 15px 'Poppins', sans-serif;
            margin-bottom: 5px;
            color: var(--primary-color);
        }

        .fact-item p {
            font: 400 10px 'Poppins', sans-serif;
            color: var(--gray);
            margin: 0;
        }

        /* Gallery */
        .package-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 30px 0;
        }

        .gallery-item {
            border-radius: 8px;
            overflow: hidden;
            height: 150px;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .gallery-item:hover img {
            transform: scale(1.05);
        }

        /* Itinerary */
        .itinerary-day {
            margin-bottom: 40px;
            border-left: 3px solid var(--secondary-color);
            padding-left: 25px;
            position: relative;
            padding-bottom: 20px;
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
            font: 600 15px 'Poppins', sans-serif;
            margin-bottom: 15px;
            color: var(--primary-color);
        }

        .itinerary-day p {
            font: 400 16px 'Poppins', sans-serif;
            margin-bottom: 15px;
            line-height: 1.7;
        }

        .itinerary-highlights {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .itinerary-highlights span {
            display: flex;
            align-items: center;
            font: 400 16px 'Poppins', sans-serif;
            color: var(--secondary-color);
        }

        .itinerary-highlights i {
            margin-right: 8px;
        }

        /* Inclusions & Exclusions */
        .inclusions-exclusions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin: 30px 0;
        }

        .inclusions,
        .exclusions {
            padding: 25px;
            background-color: var(--light-bg);
            border-radius: 8px;
        }

        .inclusions h2,
        .exclusions h2 {
            font: 700 22px 'Poppins', sans-serif;
            margin-bottom: 20px;
            color: var(--primary-color);
        }

        .inclusion-item,
        .exclusion-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            font: 400 16px 'Poppins', sans-serif;
        }

        .inclusion-item i {
            margin-right: 12px;
            color: var(--secondary-color);
        }

        .exclusion-item i {
            margin-right: 12px;
            color: var(--danger);
        }

        /* Policies */
        .policy-item {
            background-color: var(--light-bg);
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .policy-item p {
            font: 400 16px 'Poppins', sans-serif;
            margin-bottom: 10px;
            padding-left: 15px;
            position: relative;
        }

        .policy-item p:before {
            content: '•';
            position: absolute;
            left: 0;
            color: var(--secondary-color);
        }

        /* Reviews */
        .reviews-summary {
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 40px;
            margin: 30px 0;
            padding: 30px;
            background-color: var(--light-bg);
            border-radius: 8px;
        }

        .overall-rating {
            text-align: center;
        }

        .rating-number {
            font: 700 48px 'Poppins', sans-serif;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .overall-rating .stars {
            margin-bottom: 10px;
            font-size: 18px;
        }

        .overall-rating p {
            font: 400 16px 'Poppins', sans-serif;
            color: var(--gray);
            margin: 0;
        }

        .rating-breakdown {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .rating-item {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .rating-item span:first-child {
            font: 400 16px 'Poppins', sans-serif;
            width: 60px;
        }

        .rating-bar {
            flex: 1;
            height: 8px;
            background-color: var(--gray-light);
            border-radius: 4px;
            overflow: hidden;
        }

        .rating-fill {
            height: 100%;
            background-color: var(--warning);
            border-radius: 4px;
        }

        .rating-item span:last-child {
            font: 400 16px 'Poppins', sans-serif;
            width: 40px;
            text-align: right;
        }

        .review-list {
            margin: 40px 0;
        }

        .review-item {
            padding: 25px;
            border: 1px solid var(--gray-light);
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .reviewer-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .reviewer-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
        }

        .reviewer-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .reviewer-info h4 {
            font: 600 15px 'Poppins', sans-serif;
            margin-bottom: 5px;
            color: var(--primary-color);
        }

        .review-rating {
            color: var(--warning);
        }

        .review-date {
            font: 400 16px 'Poppins', sans-serif;
            color: var(--gray);
        }

        .review-content p {
            font: 400 16px 'Poppins', sans-serif;
            line-height: 1.7;
            margin: 0;
            color: var(--gray);
        }

        .load-more-reviews {
            display: block;
            margin: 0 auto;
            padding: 12px 30px;
            background-color: var(--primary-color);
            color: var(--white);
            border: none;
            border-radius: 4px;
            font: 600 15px 'Poppins', sans-serif;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .load-more-reviews:hover {
            background-color: var(--secondary-color);
        }

        /* Booking Sidebar */
        .booking-sidebar {
            position: sticky;
            top: 100px;
        }

        .booking-card {
            background-color: var(--white);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--gray-light);
        }

        .booking-price {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--gray-light);
        }

        .booking-price .price {
            font: 700 36px 'Poppins', sans-serif;
            color: var(--secondary-color);
            margin-bottom: 5px;
        }

        .booking-price .per-person {
            font: 400 16px 'Poppins', sans-serif;
            color: var(--gray);
        }

        .booking-details {
            margin-bottom: 25px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            font: 400 16px 'Poppins', sans-serif;
            color: var(--gray);
        }

        .detail-item i {
            margin-right: 10px;
            color: var(--secondary-color);
            width: 20px;
            text-align: center;
        }

        .booking-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .booking-form .form-group {
            min-width: auto;
        }

        .booking-form .form-group label {
            font: 400 16px 'Poppins', sans-serif;
            margin-bottom: 8px;
            color: var(--primary-color);
        }

        .booking-form .form-group input,
        .booking-form .form-group select,
        .booking-form .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--gray-light);
            border-radius: 4px;
            font: 400 14px 'Poppins', sans-serif;
        }

        .book-now-btn {
            background-color: var(--secondary-color);
            color: var(--white);
            border: none;
            padding: 15px;
            border-radius: 4px;
            font: 600 15px 'Poppins', sans-serif;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }

        .book-now-btn:hover {
            background-color: #3e9c6a;
        }

        .booking-features {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid var(--gray-light);
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            font: 400 16px 'Poppins', sans-serif;
            color: var(--gray);
        }

        .feature-item i {
            margin-right: 10px;
            color: var(--secondary-color);
            width: 20px;
            text-align: center;
        }

        /* Similar Packages */
        .similar-packages {
            padding: 80px 0;
            background-color: var(--light-bg);
        }

        .similar-packages .section-title {
            margin-bottom: 50px;
        }

        .similar-packages .packages-grid {
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        }

        /* Newsletter */
        .newsletter {
            padding: 80px 0;
            background-color: var(--primary-color);
            color: var(--white);
            text-align: center;
        }

        .newsletter h2 {
            font: 700 25px 'Poppins', sans-serif;
            margin-bottom: 15px;
        }

        .newsletter p {
            font: 400 16px 'Poppins', sans-serif;
            margin-bottom: 30px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            opacity: 0.9;
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
            font: 400 14px 'Poppins', sans-serif;
        }

        .newsletter-form button {
            background-color: var(--secondary-color);
            color: var(--white);
            border: none;
            padding: 12px 25px;
            border-radius: 0 4px 4px 0;
            font: 600 15px 'Poppins', sans-serif;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .newsletter-form button:hover {
            background-color: #3e9c6a;
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
            font: 700 32px 'Poppins', sans-serif;
            margin-bottom: 10px;
        }

        .package-hero-content .location {
            display: flex;
            align-items: center;
            font: 600 15px 'Poppins', sans-serif;
            margin-bottom: 15px;
        }

        .package-hero-content .location i {
            margin-right: 8px;
        }

        .package-hero-content .price {
            font: 700 28px 'Poppins', sans-serif;
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
            font: 600 15px 'Poppins', sans-serif;
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
            font: 700 22px 'Poppins', sans-serif;
            margin-bottom: 15px;
        }

        .overview-content p {
            font: 400 16px 'Poppins', sans-serif;
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
            font: 600 15px 'Poppins', sans-serif;
            margin-bottom: 5px;
        }

        .highlight-text p {
            font: 400 10px 'Poppins', sans-serif;
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
            font: 600 15px 'Poppins', sans-serif;
            margin-bottom: 10px;
        }

        .itinerary-day p {
            font: 400 16px 'Poppins', sans-serif;
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
            font: 600 15px 'Poppins', sans-serif;
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
            font: 700 28px 'Poppins', sans-serif;
            color: var(--secondary-color);
        }

        .booking-price .per-person {
            font: 400 16px 'Poppins', sans-serif;
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
            font: 600 15px 'Poppins', sans-serif;
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
            font: 400 16px 'Poppins', sans-serif;
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
            font: 600 15px 'Poppins', sans-serif;
            margin-bottom: 5px;
        }

        .author-info p {
            font: 400 10px 'Poppins', sans-serif;
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
            font: 600 15px 'Poppins', sans-serif;
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
            font: 400 10px 'Poppins', sans-serif;
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
            font: 400 10px 'Poppins', sans-serif;
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
            font: 400 10px 'Poppins', sans-serif;
            opacity: 0.7;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .package-details-layout {
                grid-template-columns: 1fr 350px;
                gap: 30px;
            }
        }

        @media (max-width: 992px) {
            .package-details-layout {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .booking-sidebar {
                position: static;
            }

            .package-hero-content h1 {
                font-size: 36px;
            }

            .inclusions-exclusions {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .reviews-summary {
                grid-template-columns: 1fr;
                gap: 30px;
                text-align: center;
            }

            .hero h1,
            .page-header h1 {
                font-size: 36px;
            }

            .hero p,
            .page-header p {
                font-size: 16px;
            }
        }

        @media (max-width: 768px) {
            .package-hero-image {
                height: 400px;
            }

            .package-hero-content h1 {
                font-size: 32px;
            }

            .package-tabs {
                overflow-x: auto;
                flex-wrap: nowrap;
            }

            .package-tabs .tab {
                padding: 12px 20px;
            }

            .highlights {
                grid-template-columns: 1fr;
            }

            .quick-facts {
                grid-template-columns: 1fr;
            }

            .package-gallery {
                grid-template-columns: repeat(2, 1fr);
            }

            .review-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
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

            .page-header {
                padding: 60px 0;
            }

            .hero h1,
            .page-header h1 {
                font-size: 32px;
            }

            .search-form,
            .filter-form {
                flex-direction: column;
            }

            .search-btn,
            .filter-btn {
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
            .package-hero-image {
                height: 350px;
            }

            .package-hero-content h1 {
                font-size: 28px;
            }

            .package-gallery {
                grid-template-columns: 1fr;
            }

            .similar-packages .packages-grid {
                grid-template-columns: 1fr;
            }

            .hero h1,
            .page-header h1 {
                font-size: 28px;
            }

            .section-title {
                font-size: 22px;
            }

            .packages-grid {
                grid-template-columns: 1fr;
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

    @stack('css')
</head>

<body>
    <!-- Header -->
    <header>
        <div class="container header-container">
            <a href="#" class="logo">
                <div class="logo-icon">✈</div>
                TravHub<span>&nbsp;Global</span>
            </a>

            <nav>
                <ul>
                    {{-- <li><a href="#" style="color:var(--secondary)">Home</a></li> --}}
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('fn.packages') }}">Packages</a></li>
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

    {{ $slot }}

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

    @stack('js')
</body>

</html>

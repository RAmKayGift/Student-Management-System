<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/favicon.png" type="image/png">
    <title>Mpandeli Secondary School</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-blue: #1a4b8c;
            --secondary-blue: #2a6fba;
            --light-blue: #e6f0fa;
            --accent-blue: #4a90e2;
            --white: #ffffff;
            --dark-gray: #333333;
            --light-gray: #f5f5f5;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            color: var(--dark-gray);
            line-height: 1.6;
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
            padding: 1rem 5%;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .logo {
            display: flex;
            align-items: center;
        }
        
        .logo img {
            height: 60px;
            margin-right: 15px;
        }
        
        .logo-text h1 {
            color: var(--primary-blue);
            font-size: 1.8rem;
            font-weight: 700;
        }
        
        .logo-text p {
            color: var(--secondary-blue);
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        nav ul {
            display: flex;
            list-style: none;
        }
        
        nav ul li {
            margin-left: 2rem;
        }
        
        nav ul li a {
            text-decoration: none;
            color: var(--primary-blue);
            font-weight: 600;
            font-size: 1.1rem;
            transition: color 0.3s;
            position: relative;
        }
        
        nav ul li a:hover {
            color: var(--accent-blue);
        }
        
        nav ul li a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background: var(--accent-blue);
            bottom: -5px;
            left: 0;
            transition: width 0.3s;
        }
        
        nav ul li a:hover::after {
            width: 100%;
        }
        
        .mobile-menu {
            display: none;
            font-size: 1.5rem;
            color: var(--primary-blue);
            cursor: pointer;
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            color: var(--white);
            padding: 5rem 5% 7rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            bottom: -50px;
            left: 0;
            right: 0;
            height: 100px;
            background-color: var(--white);
            transform: skewY(-3deg);
            z-index: 1;
        }
        
        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }
        
        .hero h2 {
            font-size: 2.8rem;
            margin-bottom: 1.5rem;
            font-weight: 700;
        }
        
        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2.5rem;
            opacity: 0.9;
        }
        
        .cta-button {
            display: inline-block;
            background-color: var(--white);
            color: var(--primary-blue);
            padding: 0.8rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }
        
        /* Features Section */
        .features {
            padding: 5rem 5%;
            background-color: var(--white);
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .section-title h3 {
            font-size: 2rem;
            color: var(--primary-blue);
            margin-bottom: 1rem;
        }
        
        .section-title p {
            color: var(--dark-gray);
            max-width: 700px;
            margin: 0 auto;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .feature-card {
            background-color: var(--light-blue);
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary-blue);
            margin-bottom: 1.5rem;
        }
        
        .feature-card h4 {
            font-size: 1.4rem;
            margin-bottom: 1rem;
            color: var(--primary-blue);
        }
        
        /* News Section */
        .news {
            padding: 5rem 5%;
            background-color: var(--light-gray);
        }
        
        .news-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        
        .news-card {
            background-color: var(--white);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
        }
        
        .news-card:hover {
            transform: translateY(-5px);
        }
        
        .news-image {
            height: 200px;
            overflow: hidden;
        }
        
        .news-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }
        
        .news-card:hover .news-image img {
            transform: scale(1.05);
        }
        
        .news-content {
            padding: 1.5rem;
        }
        
        .news-date {
            color: var(--secondary-blue);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        
        .news-card h4 {
            font-size: 1.2rem;
            margin-bottom: 0.8rem;
            color: var(--primary-blue);
        }
        
        .news-card p {
            font-size: 0.95rem;
            margin-bottom: 1.2rem;
        }
        
        .read-more {
            color: var(--accent-blue);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
        }
        
        .read-more i {
            margin-left: 5px;
            transition: transform 0.3s;
        }
        
        .read-more:hover i {
            transform: translateX(3px);
        }
        
        /* Quick Links */
        .quick-links {
            padding: 4rem 5%;
            background-color: var(--white);
        }
        
        .links-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
        }
        
        .link-column h4 {
            color: var(--primary-blue);
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
            position: relative;
            padding-bottom: 10px;
        }
        
        .link-column h4::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 2px;
            background-color: var(--accent-blue);
        }
        
        .link-column ul {
            list-style: none;
        }
        
        .link-column ul li {
            margin-bottom: 0.8rem;
        }
        
        .link-column ul li a {
            text-decoration: none;
            color: var(--dark-gray);
            transition: color 0.3s;
            display: flex;
            align-items: center;
        }
        
        .link-column ul li a:hover {
            color: var(--accent-blue);
        }
        
        .link-column ul li a i {
            margin-right: 8px;
            color: var(--accent-blue);
            font-size: 0.8rem;
        }
        
        /* Footer */
        footer {
            background-color: var(--primary-blue);
            color: var(--white);
            padding: 3rem 5% 1.5rem;
        }
        
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .footer-column h4 {
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
            position: relative;
            padding-bottom: 10px;
        }
        
        .footer-column h4::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 2px;
            background-color: var(--accent-blue);
        }
        
        .footer-column p {
            opacity: 0.8;
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }
        
        .social-links {
            display: flex;
            gap: 1rem;
        }
        
        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: var(--white);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .social-links a:hover {
            background-color: var(--accent-blue);
            transform: translateY(-3px);
        }
        
        .copyright {
            text-align: center;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.9rem;
            opacity: 0.7;
        }
        
        /* Responsive Design */
        @media (max-width: 992px) {
            .hero h2 {
                font-size: 2.4rem;
            }
        }
        
        @media (max-width: 768px) {
            nav ul {
                display: none;
            }
            
            .mobile-menu {
                display: block;
            }
            
            .hero h2 {
                font-size: 2rem;
            }
            
            .hero p {
                font-size: 1rem;
            }
        }
        
        @media (max-width: 576px) {
            .logo-text h1 {
                font-size: 1.5rem;
            }
            
            .hero {
                padding: 4rem 5% 6rem;
            }
            
            .hero h2 {
                font-size: 1.8rem;
            }
            
            .section-title h3 {
                font-size: 1.6rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-container">
            <div class="logo">
                <img src="images/logo.png" alt="Mpandeli secondary Logo">
                <div class="logo-text">
                    <h1>Mpandeli</h1>
                    <p>Secondary School</p>
                </div>
            </div>
            <nav>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#contacts">About</a></li>
                    <li><a href="#news">News</a></li>
                    <li><a href="apply.html" target="_blank">Apply</a></li>
                    <li><a href="login.html" target="_blank">Login</a></li>
                </ul>
            </nav>
            <div class="mobile-menu">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h2>Empowering Students for Success</h2>
            <p>At Mpandeli Secondary School, we provide a challenging academic environment that fosters intellectual curiosity, creativity, and personal growth.</p>
            <a href="#" class="cta-button">Explore Our Programs</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="section-title">
            <h3>Why Choose Mpandeli Secondary?</h3>
            <p>We are committed to providing an exceptional educational experience that prepares students for college, career, and life.</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h4>Academic Excellence</h4>
                <p>98% graduation rate with 85% of graduates attending 4-year colleges and universities.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <h4>Championship Athletics</h4>
                <p>32 varsity teams with state championships in 8 different sports.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-music"></i>
                </div>
                <h4>Arts Programs</h4>
                <p>Recognized nationally for our music, theater, and visual arts programs.</p>
            </div>
        </div>
    </section>

    <?php
require 'db_connect.php'; // Use your connection file

$stmt = $conn->query("SELECT * FROM announcements ORDER BY date_posted DESC");
$announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="news" id="news">
    <div class="news-container">
        <div class="section-title">
            <h3>Latest News & Events</h3>
            <p>Stay updated with what's happening at Mpandeli Secondary School.</p>
        </div>
        <div class="news-grid">
            <?php foreach ($announcements as $news): ?>
                <div class="news-card">
                    <div class="news-image">
                        <img src="<?= htmlspecialchars($news['image_url'] ?? 'https://images.unsplash.com/photo-1588072432836-e10032774350?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80') ?>" 
                             alt="<?= htmlspecialchars($news['title']) ?>"
                             onerror="this.src='https://images.unsplash.com/photo-1588072432836-e10032774350?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'">
                    </div>
                    <div class="news-content">
                        <div class="news-date">
                            <i class="far fa-calendar-alt me-2"></i><?= date("F d, Y", strtotime($news['date_posted'])) ?>
                        </div>
                        <h4><?= htmlspecialchars($news['title']) ?></h4>
                        <p class="news-excerpt"><?= htmlspecialchars($news['content']) ?></p>
                        <a href="#" class="read-more">
                            Read More <i class="fas fa-chevron-right ms-1"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<style>
    .news {
        padding: 4rem 0;
        background-color: #f8f9fa;
    }
    
    .section-title {
        text-align: center;
        margin-bottom: 3rem;
    }
    
    .section-title h3 {
        color: #1a73e8;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .section-title p {
        color: #5f6368;
        font-size: 1.1rem;
    }
    
    .news-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .news-card {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .news-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
    }
    
    .news-image {
        height: 200px;
        overflow: hidden;
    }
    
    .news-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .news-card:hover .news-image img {
        transform: scale(1.05);
    }
    
    .news-content {
        padding: 1.5rem;
    }
    
    .news-date {
        color: #5f6368;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    
    .news-content h4 {
        color: #202124;
        font-size: 1.25rem;
        margin-bottom: 1rem;
        line-height: 1.4;
    }
    
    .news-excerpt {
        color: #5f6368;
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }
    
    .read-more {
        color: #1a73e8;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: color 0.3s ease;
    }
    
    .read-more:hover {
        color: #0d47a1;
    }
    
    @media (max-width: 768px) {
        .news-grid {
            grid-template-columns: 1fr;
        }
    }
</style>


    <!-- Quick Links Section -->
    <section class="quick-links">
        <div class="links-container">
            <div class="link-column">
                <h4>For Students</h4>
                <ul>
                    <li><a href="login.html"><i class="fas fa-chevron-right"></i> Student Portal</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Class Schedule</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Lunch Menu</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Clubs & Activities</a></li>
                </ul>
            </div>
            <div class="link-column">
                <h4>For Parents</h4>
                <ul>
                   <li><a href="#"><i class="fas fa-chevron-right"></i> Calendar</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Volunteer Opportunities</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> School Policies</a></li>
                </ul>
            </div>
            <div class="link-column">
                <h4>Academics</h4>
                <ul>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Subjects Catalog</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> College Prep</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> AP Courses</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Library Resources</a></li>
                </ul>
            </div>
            <div class="link-column">
                <h4>Athletics</h4>
                <ul>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Sports Calendar</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Team Schedules</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Athletic Forms</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Facilities</a></li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-container" id="contacts">
            <div class="footer-column">
                <h4>About Us</h4>
                <p>Mpandeli Secondary School is a premier educational institution committed to academic excellence, character development, and preparing students for the challenges of tomorrow.</p>
                <div class="social-links">
                    <a href="https://www.facebook.com/profile.php?id=100063984007662" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="footer-column">
                <h4>Contact Info</h4>
                <p><i class="fas fa-map-marker-alt"></i> Tshifudi, Thondoni, Box 90210</p>
                <p><i class="fas fa-phone"></i> (081) 866-6961</p>
                <p><i class="fas fa-envelope"></i> info@mpandelisec.edu</p>
                <p><i class="fas fa-clock"></i> School Hours: 7:30 AM - 3:30 PM</p>
            </div>
            <div class="footer-column">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Employment</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> School Board</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> District Info</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Emergency Info</a></li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; 2025 Mpandeli Secondary School. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>

<?php
/* Fetch current registration status */
$stmt = $conn->prepare("SELECT registration_status FROM system_settings LIMIT 1");
$stmt->execute();
$setting = $stmt->fetch(PDO::FETCH_ASSOC);
$registrationStatus = $setting['registration_status'] ?? 'closed';

/* Fetch banner data from database */
$bannerQuery = $conn->query("SELECT * FROM banners LIMIT 1");
$bannerData = $bannerQuery->fetch(PDO::FETCH_ASSOC);

/* Fetch about us content from database */
$aboutQuery = $conn->query("SELECT * FROM about_us LIMIT 1");
$aboutData = $aboutQuery->fetch(PDO::FETCH_ASSOC);
?>

<style>

/* ===== CAROUSEL ===== */
#homeCarousel {
    max-width: 1700px; /* Adjust width as needed */
    margin: 0 auto; /* Center it */
}

#homeCarousel .item img {
    height: 600px; /* Set fixed height */
    object-fit: cover; /* Crop images nicely */
}
.carousel-inner img {
    width: 100%;
    height: 300px;
    object-fit: cover;
}

.carousel-caption {
    background: rgba(0,0,0,0.6);
    padding: 20px;
    border-radius: 10px;
}

/* Carousel Indicators Enhancement */
.carousel-indicators {
    bottom: 20px;
}

.carousel-indicators li {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin: 0 5px;
    background-color: rgba(255, 255, 255, 0.5);
    border: 2px solid rgba(255, 255, 255, 0.8);
    transition: all 0.3s ease;
}

.carousel-indicators li.active {
    width: 14px;
    height: 14px;
    background-color: #ffffff;
    box-shadow: 0 0 10px rgba(255, 255, 255, 0.8);
}

/* Carousel Controls Enhancement */
.carousel-control {
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

.carousel-control:hover {
    opacity: 1;
}

.carousel-control .glyphicon {
    font-size: 40px;
}

.about-section {
    padding: 100px 0;
    background: #f8f9fa;
    position: relative;
}

.about-section::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 50%;
    height: 100%;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.03) 0%, rgba(118, 75, 162, 0.03) 100%);
    z-index: 0;
}

.about-section .container {
    position: relative;
    z-index: 1;
}

.about-image-col {
    margin-bottom: 40px;
}

.about-image-wrapper {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    transition: transform 0.4s ease;
}

.about-image-wrapper:hover {
    transform: translateY(-10px);
}

.about-image {
    width: 100%;
    height: auto;
    display: block;
    border-radius: 20px;
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.about-image-wrapper:hover .image-overlay {
    opacity: 1;
}

.about-content {
    padding-left: 30px;
}

.section-label {
    display: inline-block;
    color: #667eea;
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-bottom: 15px;
}

.section-title {
    font-size: 36px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 15px;
    line-height: 1.3;
}

.title-underline {
    width: 60px;
    height: 4px;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    margin-bottom: 30px;
    border-radius: 2px;
}

.about-text {
    font-size: 16px;
    line-height: 1.8;
    color: #5a6c7d;
    margin-bottom: 20px;
}

.about-stats {
    display: flex;
    gap: 30px;
    margin: 40px 0;
    padding: 30px 0;
    border-top: 1px solid #e0e6ed;
    border-bottom: 1px solid #e0e6ed;
}

.stat-item {
    text-align: center;
    flex: 1;
}

.stat-number {
    font-size: 32px;
    font-weight: 700;
    color: #667eea;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 14px;
    color: #7f8c8d;
    margin: 0;
    font-weight: 500;
}

.btn-outline-primary {
    border: 2px solid #667eea;
    color: #667eea;
    padding: 12px 35px;
    border-radius: 50px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
    margin-top: 20px;
}

.btn-outline-primary:hover {
    background: #667eea;
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    text-decoration: none;
}

/* Responsive */
@media (max-width: 991px) {
    .about-section {
        padding: 60px 0;
    }

    .about-content {
        padding-left: 0;
        margin-top: 30px;
    }

    .section-title {
        font-size: 28px;
    }

    .about-stats {
        gap: 20px;
    }

    .stat-number {
        font-size: 24px;
    }
}
/* Add to your existing CSS */

/* Fly-in Animation Keyframes */
@keyframes flyInLeft {
    0% {
        opacity: 0;
        transform: translateX(-100px);
    }
    100% {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes flyInRight {
    0% {
        opacity: 0;
        transform: translateX(100px);
    }
    100% {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes flyInUp {
    0% {
        opacity: 0;
        transform: translateY(50px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    0% {
        opacity: 0;
    }
    100% {
        opacity: 1;
    }
}

/* Initial hidden state */
.about-image-col,
.about-content-col {
    opacity: 0;
}

/* Animated state */
.about-image-col.animate {
    animation: flyInLeft 0.8s ease-out forwards;
}

.about-content-col.animate {
    animation: flyInRight 0.8s ease-out 0.2s forwards;
}

.about-content .section-label {
    opacity: 0;
}

.about-content.animate .section-label {
    animation: flyInUp 0.6s ease-out 0.4s forwards;
}

.about-content .section-title {
    opacity: 0;
}

.about-content.animate .section-title {
    animation: flyInUp 0.6s ease-out 0.5s forwards;
}

.about-content .title-underline {
    opacity: 0;
    transform: scaleX(0);
    transform-origin: left;
}

.about-content.animate .title-underline {
    animation: scaleIn 0.6s ease-out 0.6s forwards;
}

@keyframes scaleIn {
    0% {
        opacity: 0;
        transform: scaleX(0);
    }
    100% {
        opacity: 1;
        transform: scaleX(1);
    }
}

.about-stats {
    opacity: 0;
}

.about-content.animate .about-stats {
    animation: flyInUp 0.6s ease-out 0.9s forwards;
}

.about-content .btn-outline-primary {
    opacity: 0;
}

.about-content.animate .btn-outline-primary {
    animation: flyInUp 0.6s ease-out 1.3s forwards;
}

/* Individual stat items animation */
.stat-item {
    opacity: 0;
}

.about-content.animate .stat-item:nth-child(1) {
    animation: flyInUp 0.5s ease-out 1.3s forwards;
}

.about-content.animate .stat-item:nth-child(2) {
    animation: flyInUp 0.5s ease-out 1.4s forwards;
}

.about-content.animate .stat-item:nth-child(3) {
    animation: flyInUp 0.5s ease-out 1.5s forwards;
}
@media (max-width: 576px) {
    .about-stats {
        flex-direction: column;
        gap: 20px;
    }

    .stat-item {
        padding: 10px 0;
        border-bottom: 1px solid #e0e6ed;
    }

    .stat-item:last-child {
        border-bottom: none;
    }
}

/* ===== REGISTRATION SECTION - ENHANCED ===== */
.registration-section {
    background: linear-gradient(135deg, #a86eeb 30%, #da4fec 70%, #91128a 100%);
    padding: 70px 20px;
    position: relative;
    color: #fff;
}

.registration-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
    opacity: 0.3;
}

.registration-section .container {
    position: relative;
    z-index: 1;
}

/* Status Indicators - Enhanced */
.status-indicator {
    display: inline-flex;
    align-items: center;
    gap: 1rem;
    padding: 1.2rem 3rem;
    border-radius: 60px;
    font-weight: 700;
    font-size: 1.4rem;
    margin-bottom: 2rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    position: relative;
    overflow: hidden;
    transition: all 0.4s ease;
    animation: float 3s ease-in-out infinite;
}

.status-indicator::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%);
    animation: rotate-gradient 4s linear infinite;
}

@keyframes rotate-gradient {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-8px);
    }
}

.status-indicator span {
    position: relative;
    z-index: 1;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
}

.status-open {
    background: linear-gradient(135deg, #d4fc79 0%, #96e6a1 100%);
    color: #1b5e20;
    box-shadow: 0 8px 30px rgba(150, 230, 161, 0.6), 
                0 0 40px rgba(150, 230, 161, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.5);
    border: 2px solid rgba(255, 255, 255, 0.8);
}

.status-open:hover {
    transform: translateY(-10px) scale(1.05);
    box-shadow: 0 12px 40px rgba(150, 230, 161, 0.7), 
                0 0 60px rgba(150, 230, 161, 0.5);
}

.status-closed {
    background: linear-gradient(135deg, #fbc2eb 0%, #fa709a 100%);
    color: #b71c1c;
    box-shadow: 0 8px 30px rgba(250, 112, 154, 0.6), 
                0 0 40px rgba(250, 112, 154, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.5);
    border: 2px solid rgba(255, 255, 255, 0.8);
}

.status-closed:hover {
    transform: translateY(-10px) scale(1.05);
    box-shadow: 0 12px 40px rgba(250, 112, 154, 0.7), 
                0 0 60px rgba(250, 112, 154, 0.5);
}

.pulse-dot {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: currentColor;
    animation: pulse-animation 1.5s ease-in-out infinite;
    box-shadow: 0 0 15px currentColor, 
                0 0 30px currentColor;
    position: relative;
    z-index: 1;
}

.pulse-dot::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: currentColor;
    animation: pulse-ring 1.5s ease-out infinite;
}

.pulse-dot::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: currentColor;
    animation: pulse-ring 1.5s ease-out infinite 0.75s;
}

@keyframes pulse-animation {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.8;
        transform: scale(1.15);
    }
}

@keyframes pulse-ring {
    0% {
        width: 100%;
        height: 100%;
        opacity: 0.8;
    }
    100% {
        width: 250%;
        height: 250%;
        opacity: 0;
    }
}

.registration-title {
    font-weight: 700;
    font-size: 42px;
    color: #ffffff;
    margin-bottom: 15px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
}

.registration-subtitle {
    font-size: 18px;
    color: rgba(255, 255, 255, 0.95);
    margin-bottom: 40px;
    font-weight: 300;
}

.btn-register {
    background: #ffffff;
    color: #e51414;
    padding: 18px 45px;
    border-radius: 50px;
    font-size: 18px;
    font-weight: 600;
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
    display: inline-block;
    text-decoration: none;
}

.btn-register:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.3);
    background: #f8f9ff;
    color: #667eea;
    text-decoration: none;
}

.btn-register:active {
    transform: translateY(-1px);
}

.btn-text {
    margin-right: 10px;
}

.btn-price {
    font-weight: 700;
    color: #764ba2;
}

/* Closed State Styling */
.registration-closed .registration-title {
    color: rgba(255, 255, 255, 0.9);
}

.registration-closed .registration-subtitle {
    color: rgba(255, 255, 255, 0.8);
}

.notify-box {
    margin-top: 2.5rem;
    padding: 3rem 2rem;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 20px;
    border: 2px dashed rgba(255, 255, 255, 0.3);
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
    backdrop-filter: blur(10px);
}

.closed-icon {
    color: #ffd700;
    margin-bottom: 1.5rem;
    filter: drop-shadow(0 4px 8px rgba(255, 215, 0, 0.5));
}

.notify-box h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 1rem;
}

.notify-box p {
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.btn-contact {
    display: inline-block;
    padding: 0.875rem 2rem;
    background: rgba(255, 255, 255, 0.95);
    color: #764ba2;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.btn-contact:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    background: #ffffff;
    color: #764ba2;
    text-decoration: none;
}

/* Animation for content */
.registration-open-content,
.registration-closed-content {
    animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .registration-section {
        padding: 60px 0;
    }
    
    .registration-title {
        font-size: 32px;
    }
    
    .registration-subtitle {
        font-size: 16px;
    }
    
    .btn-register {
        padding: 15px 35px;
        font-size: 16px;
    }
    
    .notify-box {
        padding: 2rem 1.5rem;
    }
    
    /* Status Indicator Mobile */
    .status-indicator {
        padding: 1rem 2rem;
        font-size: 1.1rem;
        gap: 0.75rem;
    }
    
    .pulse-dot {
        width: 12px;
        height: 12px;
    }
}

@media (max-width: 480px) {
    .status-indicator {
        padding: 0.9rem 1.5rem;
        font-size: 0.95rem;
    }
}

/* ===== STATS ===== */
.stats-section {
    background: #f8f9fa;
    padding: 60px 0;
}

.stat-box h2 {
    font-size: 40px;
    font-weight: 700;
    color: #28a745;
}

/* ===== MOBILE ===== */
@media (max-width: 768px) {
    .carousel-inner img {
        height: 260px;
    }

    .btn-register {
        font-size: 16px;
        padding: 14px 30px;
    }
}

/* ===================== ABOUT SECTION ===================== */
.about-section {
    padding: 100px 0 80px;
    background: #ffffff;
    position: relative;
    overflow: hidden;
}

.about-section::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 500px;
    height: 500px;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
    border-radius: 50%;
    z-index: 0;
}

.about-section .container {
    position: relative;
    z-index: 1;
}

.section-spacing {
    margin-bottom: 80px;
}

/* Image Column */
.about-image-col {
    margin-bottom: 40px;
    opacity: 0;
}

.about-image-wrapper {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    transition: transform 0.4s ease;
}

.about-image-wrapper:hover {
    transform: translateY(-10px);
}

.about-image {
    width: 100%;
    height: auto;
    display: block;
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.2) 0%, rgba(118, 75, 162, 0.2) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.about-image-wrapper:hover .image-overlay {
    opacity: 1;
}

.image-badge {
    position: absolute;
    bottom: 20px;
    left: 20px;
    background: rgba(255, 255, 255, 0.95);
    padding: 12px 25px;
    border-radius: 50px;
    backdrop-filter: blur(10px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.badge-year {
    font-weight: 700;
    font-size: 14px;
    color: #667eea;
    letter-spacing: 1px;
}

/* Content Column */
.about-content-col {
    opacity: 0;
}

.about-content {
    padding-left: 30px;
}

.section-label {
    display: inline-block;
    color: #667eea;
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-bottom: 15px;
    opacity: 0;
}

.section-title {
    font-size: 38px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 15px;
    line-height: 1.3;
    opacity: 0;
}

.title-underline {
    width: 60px;
    height: 4px;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    margin-bottom: 30px;
    border-radius: 2px;
    opacity: 0;
    transform: scaleX(0);
    transform-origin: left;
}

.about-text {
    font-size: 18px;
    line-height: 2;
    color: #5a6c7d;
    margin-bottom: 25px;
    opacity: 0;
    text-align: justify;
    text-justify: inter-word;
    font-weight: 400;
    letter-spacing: 0.3px;
}

/* Enhanced text animations */
.about-content.animate .about-text {
    animation: fadeInSlideUp 0.8s ease-out forwards;
}

@keyframes fadeInSlideUp {
    0% {
        opacity: 0;
        transform: translateY(30px) scale(0.95);
    }
    100% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Staggered animation delays for multiple paragraphs */
.about-content.animate .about-text:nth-of-type(1),
.about-content.animate .about-text > p:nth-child(1) {
    animation: fadeInSlideUp 0.8s ease-out 0.7s forwards;
}

.about-content.animate .about-text:nth-of-type(2),
.about-content.animate .about-text > p:nth-child(2) {
    animation: fadeInSlideUp 0.8s ease-out 0.9s forwards;
}

.about-content.animate .about-text:nth-of-type(3),
.about-content.animate .about-text > p:nth-child(3) {
    animation: fadeInSlideUp 0.8s ease-out 1.1s forwards;
}

/* Add subtle glow effect on hover */
.about-text:hover {
    text-shadow: 0 0 20px rgba(102, 126, 234, 0.1);
    transition: text-shadow 0.3s ease;
}

/* First letter styling for fancy effect */
.about-text::first-letter {
    font-size: 1.8em;
    font-weight: 700;
    color: #667eea;
    float: left;
    line-height: 1;
    margin-right: 8px;
    margin-top: 4px;
}

.btn-outline-primary {
    border: 2px solid #667eea;
    color: #667eea;
    padding: 12px 35px;
    border-radius: 50px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
    margin-top: 20px;
    opacity: 0;
}

.btn-outline-primary:hover {
    background: #667eea;
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    text-decoration: none;
}

/* ===================== STATS SECTION ===================== */
.stats-row {
    margin-top: 60px;
    opacity: 0;
}

.stats-container {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 30px;
    padding: 50px 30px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(102, 126, 234, 0.3);
    position: relative;
    overflow: hidden;
}

.stats-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
    opacity: 0.3;
}

.stat-card {
    text-align: center;
    padding: 20px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 15px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
    position: relative;
    z-index: 1;
    opacity: 0;
    transform: translateY(30px);
}

.stat-card:hover {
    background: rgba(255, 255, 255, 0.15);
    transform: translateY(-5px);
}

.stat-icon {
    width: 60px;
    height: 60px;
    margin: 0 auto 20px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: #ffffff;
}

.stat-number {
    font-size: 42px;
    font-weight: 700;
    color: #ffffff;
    margin: 15px 0 10px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
}

.stat-label {
    font-size: 14px;
    color: rgba(255, 255, 255, 0.95);
    margin: 0;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-bar {
    width: 0;
    height: 3px;
    background: #ffffff;
    margin: 15px auto 0;
    border-radius: 2px;
    transition: width 1s ease;
}

.stat-card.animate .stat-bar {
    width: 60px;
}

/* ===================== ANIMATIONS ===================== */
@keyframes countUp {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* Apply animations */
.about-image-col.animate {
    animation: flyInLeft 0.8s ease-out forwards;
}

.about-content-col.animate {
    animation: flyInRight 0.8s ease-out 0.2s forwards;
}

.about-content.animate .section-label {
    animation: flyInUp 0.6s ease-out 0.4s forwards;
}

.about-content.animate .section-title {
    animation: flyInUp 0.6s ease-out 0.5s forwards;
}

.about-content.animate .title-underline {
    animation: scaleIn 0.6s ease-out 0.6s forwards;
}

.about-content.animate .about-text:nth-of-type(1) {
    animation: flyInUp 0.6s ease-out 0.7s forwards;
}

.about-content.animate .about-text:nth-of-type(2) {
    animation: flyInUp 0.6s ease-out 0.8s forwards;
}

.about-content.animate .btn-outline-primary {
    animation: flyInUp 0.6s ease-out 0.9s forwards;
}

.stats-row.animate {
    animation: flyInUp 0.8s ease-out 0.3s forwards;
}

.stats-row.animate .stat-card:nth-child(1) {
    animation: flyInUp 0.6s ease-out 0.5s forwards;
}

.stats-row.animate .stat-card:nth-child(2) {
    animation: flyInUp 0.6s ease-out 0.6s forwards;
}

.stats-row.animate .stat-card:nth-child(3) {
    animation: flyInUp 0.6s ease-out 0.7s forwards;
}

.stats-row.animate .stat-card:nth-child(4) {
    animation: flyInUp 0.6s ease-out 0.8s forwards;
}

/* ===================== RESPONSIVE ===================== */
@media (max-width: 991px) {
    .about-section {
        padding: 60px 0 50px;
    }

    .section-spacing {
        margin-bottom: 50px;
    }

    .about-content {
        padding-left: 0;
        margin-top: 30px;
    }

    .section-title {
        font-size: 30px;
    }

    .stats-container {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        padding: 40px 20px;
    }
}

@media (max-width: 576px) {
    .section-title {
        font-size: 26px;
    }

    .stats-container {
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .stat-number {
        font-size: 36px;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        font-size: 24px;
    }
    
    /* Responsive about text */
    .about-text {
        font-size: 16px;
        line-height: 1.9;
        text-align: left;
    }
    
    .about-text::first-letter {
        font-size: 1.5em;
    }
}

/* ===================== BOTTOM FIXED IMAGE SECTION ===================== */
.bottom-fixed-image {
    width: 100%;
    position: relative;
    background: #0a0a0a;
    overflow: hidden;
}

.bottom-image-wrapper {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.bottom-scalable-image {
    width: 100%;
    height: auto;
    max-width: 100%;
    display: block;
    object-fit: contain;
    transition: transform 0.3s ease;
}

/* Ensures image scales fully to bottom space */
.bottom-fixed-image {
    min-height: 200px;
}

/* For larger screens, image takes full width */
@media (min-width: 1200px) {
    .bottom-scalable-image {
        width: 100%;
        height: auto;
    }
}

/* For smaller screens, maintain aspect ratio */
@media (max-width: 768px) {
    .bottom-scalable-image {
        width: 100%;
        height: auto;
    }
}

/* Hover effect for better UX */
.bottom-scalable-image:hover {
    transform: scale(1.01);
}

/* Ensure no overflow issues */
.bottom-fixed-image {
    margin: 0;
    padding: 0;
    line-height: 0;
}
</style>


<?php
/* Fetch slides from database */
$slidesQuery = $conn->query("SELECT * FROM slides ORDER BY id ASC");
$slidesData = $slidesQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- ===================== SLIDER FIRST ===================== -->
<div id="homeCarousel" class="carousel slide" data-ride="carousel" data-interval="5000">

    <div class="carousel-inner">
        <?php if (count($slidesData) > 0): ?>
            <?php foreach ($slidesData as $index => $slide): ?>
                <div class="item <?= $index === 0 ? 'active' : '' ?>">
                    <img src="uploads/slides/<?= htmlspecialchars($slide['image']) ?>" 
                         alt="<?= htmlspecialchars($slide['title']) ?>">
                    <div class="carousel-caption">
                        <h2><?= htmlspecialchars($slide['title']) ?></h2>
                        <?php if (!empty($slide['subtitle'])): ?>
                            <p><?= htmlspecialchars($slide['subtitle']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Default slide if no slides in database -->
            <div class="item active">
                <img src="img/placeholder-slide.jpg" alt="Welcome to ISF">
                <div class="carousel-caption">
                    <h2>Welcome to Iyekhei Sport Festival 2026</h2>
                    <p>Upload slides from the admin panel</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php if (count($slidesData) > 1): ?>
        <!-- Only show controls if there's more than one slide -->
        <a class="left carousel-control" href="#homeCarousel" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
        </a>

        <a class="right carousel-control" href="#homeCarousel" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
        </a>
        
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <?php foreach ($slidesData as $index => $slide): ?>
                <li data-target="#homeCarousel" 
                    data-slide-to="<?= $index ?>" 
                    class="<?= $index === 0 ? 'active' : '' ?>"></li>
            <?php endforeach; ?>
        </ol>
    <?php endif; ?>

</div>
<!-- ===================== END SLIDER ===================== -->


<!-- ===================== REGISTRATION SECTION - ADVANCED ===================== -->
<section class="registration-section <?= $registrationStatus === 'closed' ? 'registration-closed' : '' ?>">
    <div class="container text-center">
        <?php if ($registrationStatus === 'open'): ?>
            <!-- REGISTRATION OPEN -->
            <div class="registration-open-content">
                <div class="status-indicator status-open">
                    <span class="pulse-dot"></span>
                    <span>Registration in Progress...</span>
                </div>
                <h2 class="registration-title">Register for ISF Marathon 2026</h2>
                <p class="registration-subtitle">Secure your participation in this year's sporting excellence.</p>

                <a href="apply.php" class="btn btn-register">
                    <span class="btn-text">Register Now</span>
                    <span class="btn-price">₦3,000 Only</span>
                </a>
            </div>
        <?php else: ?>
            <!-- REGISTRATION CLOSED -->
            <div class="registration-closed-content">
                <div class="status-indicator status-closed">
                    <span class="pulse-dot"></span>
                    <span>Registration Closed - No Active Registration Yet</span>
                </div>
                <h2 class="registration-title">Registration Currently Closed</h2>
                <p class="registration-subtitle">Registration for ISF 2026 is temporarily closed.!</p>
                
             
            </div>
        <?php endif; ?>
    </div>
</section>
<!-- ===================== END REGISTRATION ===================== -->

<!-- ===================== ABOUT & STATS SECTION ===================== -->
<section class="about-section">
    <div class="container">
        <!-- About Content -->
        <div class="row align-items-center section-spacing">
            <div class="col-md-6 about-image-col">
                <div class="about-image-wrapper">
                    <?php if ($bannerData && !empty($bannerData['image']) && file_exists("uploads/banners/" . $bannerData['image'])): ?>
                        <!-- Banner from database -->
                        <img src="uploads/banners/<?= htmlspecialchars($bannerData['image']) ?>" 
                             class="img-responsive about-image"
                             alt="Iyekhei Sport Festival">
                    <?php else: ?>
                        <!-- Fallback to default image if no banner uploaded -->
                        <img src="img/placeholders/screenshots/isf2024advert.jpg" 
                             class="img-responsive about-image"
                             alt="Iyekhei Sport Festival 2024">
                    <?php endif; ?>
                    <div class="image-overlay"></div>
                    <div class="image-badge">
                        <span class="badge-year">EST. 2018</span>
                    </div>
                </div>
            </div>

            <div class="col-md-6 about-content-col">
                <div class="about-content">
                    <span class="section-label">Who We Are</span>
                    <h2 class="section-title">About Iyekhei Sport Festival</h2>
                    <div class="title-underline"></div>
                    
                    <?php if ($aboutData && !empty($aboutData['content'])): ?>
                        <!-- About content from database -->
                        <div class="about-text">
                            <?= nl2br(htmlspecialchars($aboutData['content'])) ?>
                        </div>
                    <?php else: ?>
                        <!-- Default content if no database content -->
                        <p class="about-text">
                            The Iyekhei Sport Festival (ISF) is an annual sporting event 
                            organized under the Auchi Dynamic Youth Association (Zone E).
                        </p>
                        <p class="about-text">
                            Since 2018, ISF has promoted unity, youth empowerment, and 
                            sportsmanship among Iyekhei sons and daughters, bringing together 
                            communities through the power of sports.
                        </p>
                    <?php endif; ?>

                    <a href="#events" class="btn btn-outline-primary">Explore Events</a>
                </div>
            </div>
        </div>

        <!-- Integrated Stats -->
        <div class="row stats-row">
            <div class="col-12">
                <div class="stats-container">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="glyphicon glyphicon-user"></i>
                        </div>
                        <h3 class="stat-number" id="athletes" data-target="500">0</h3>
                        <p class="stat-label">Expected Athletes</p>
                        <div class="stat-bar"></div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="glyphicon glyphicon-heart"></i>
                        </div>
                        <h3 class="stat-number" id="supporters" data-target="1000">0</h3>
                        <p class="stat-label">Supporters</p>
                        <div class="stat-bar"></div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="glyphicon glyphicon-calendar"></i>
                        </div>
                        <h3 class="stat-number">7+</h3>
                        <p class="stat-label">Years Running</p>
                        <div class="stat-bar"></div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="glyphicon glyphicon-flag"></i>
                        </div>
                        <h3 class="stat-number">10+</h3>
                        <p class="stat-label">Sports Events</p>
                        <div class="stat-bar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===================== BOTTOM FIXED SCALABLE IMAGE ===================== -->
<div class="bottom-fixed-image">
    <div class="bottom-image-wrapper">
        <img src="img/isfmobile_img.png" 
             class="bottom-scalable-image"
             alt="ISF Mobile Image"
             style="width: 100%; height: auto; display: block;">
    </div>
</div>
<!-- ===================== END BOTTOM IMAGE ===================== -->

<script>
function animateCounter(id, target) {
    let count = 0;
    let speed = target / 100;

    let interval = setInterval(function() {
        count += speed;
        if (count >= target) {
            count = target;
            clearInterval(interval);
        }
        document.getElementById(id).innerText = Math.floor(count) + "+";
    }, 20);
}

window.onload = function() {
    animateCounter("athletes", 3000);
    animateCounter("supporters", 20000);
};
document.addEventListener('DOMContentLoaded', function() {
    // Create intersection observer
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate');
                // Optional: Stop observing after animation
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.2, // Trigger when 20% of element is visible
        rootMargin: '0px 0px -100px 0px' // Start animation slightly before element enters viewport
    });

    // Observe elements
    const imageCol = document.querySelector('.about-image-col');
    const contentCol = document.querySelector('.about-content-col');
    const aboutContent = document.querySelector('.about-content');

    if (imageCol) observer.observe(imageCol);
    if (contentCol) observer.observe(contentCol);
    if (aboutContent) observer.observe(aboutContent);
});
document.addEventListener('DOMContentLoaded', function() {
    // Intersection Observer for scroll animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate');
                
                // Trigger counter animation for stats
                if (entry.target.classList.contains('stats-row')) {
                    animateCounters();
                }
                
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.2,
        rootMargin: '0px 0px -100px 0px'
    });

    // Observe elements
    const imageCol = document.querySelector('.about-image-col');
    const contentCol = document.querySelector('.about-content-col');
    const aboutContent = document.querySelector('.about-content');
    const statsRow = document.querySelector('.stats-row');

    if (imageCol) observer.observe(imageCol);
    if (contentCol) observer.observe(contentCol);
    if (aboutContent) observer.observe(aboutContent);
    if (statsRow) observer.observe(statsRow);

    // Counter animation function
    function animateCounters() {
        const counters = document.querySelectorAll('.stat-number[data-target]');
        
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            const duration = 2000; // 2 seconds
            const increment = target / (duration / 16); // 60fps
            let current = 0;

            const updateCounter = () => {
                current += increment;
                if (current < target) {
                    counter.textContent = Math.floor(current);
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.textContent = target + '+';
                }
            };

            updateCounter();
        });
    }
});
</script>

<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>
<?php include 'inc/template_end.php'; ?>
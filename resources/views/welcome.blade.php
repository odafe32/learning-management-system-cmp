<x-guest-layout>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent position-fixed w-100 top-0 z-index-1000" id="mainNavbar">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="{{ url('/') }}">
                <i class="ph ph-graduation-cap me-2 text-primary"></i>
                <span class="text-white">Edu Mate</span><span class="text-primary"></span>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link text-white-90 fw-medium px-3" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-90 fw-medium px-3" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-90 fw-medium px-3" href="#contact">Contact</a>
                    </li>
                    <li class="nav-item ms-3">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm px-4 py-2 rounded-pill fw-semibold">
                            <i class="ph ph-sign-in me-2"></i>
                            Login
                        </a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item ms-2">
                            <a href="{{ route('register') }}" class="btn btn-outline-light btn-sm px-4 py-2 rounded-pill fw-semibold">
                                <i class="ph ph-user-plus me-2"></i>
                                Sign Up
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section bg-gradient-blue position-relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="hero-pattern position-absolute top-0 start-0 w-100 h-100 opacity-10"></div>
        
        <!-- Floating Elements -->
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>

        <div class="container position-relative z-2">
            <div class="row align-items-center min-vh-100 py-5">
                <div class="col-lg-6 order-2 order-lg-1">
                    <div class="hero-content text-center text-lg-start">
                        <!-- Badge -->
                        <div class="hero-badge bg-white bg-opacity-15 text-white px-4 py-2 rounded-pill d-inline-flex align-items-center mb-4">
                            <i class="ph ph-star me-2"></i>
                            <span class="fw-medium">Next Generation Learning Platform</span>
                        </div>

                        <!-- Main Title -->
                        <h1 class="hero-title display-1 fw-bold text-white mb-4 lh-1">
                            Welcome to
                            <br>
                            <span class="text-gradient-gold position-relative">
                                EduMate
                                <svg class="hero-underline position-absolute" viewBox="0 0 200 20" fill="none">
                                    <path d="M5 15C50 5, 150 5, 195 15" stroke="#fbbf24" stroke-width="3" fill="none"/>
                                </svg>
                            </span>
                        </h1>

                        <!-- Subtitle -->
                        <p class="hero-subtitle text-white-85 fs-4 mb-5 lh-lg pe-lg-4">
                            Transform your learning experience with our comprehensive Learning Management System. 
                            Designed for students, educators, and institutions to excel in the digital age.
                        </p>

                        <!-- CTA Buttons -->
                        <div class="hero-buttons d-flex flex-column flex-sm-row gap-3 mb-5">
                            <a href="{{ route('login') }}" class="btn btn-dark btn-lg px-8 py-11 rounded-pill fw-semibold shadow-lg">
                                <i class="ph ph-rocket-launch me-2 text-primary"></i>
                                Get Started Now
                            </a>
                            <a href="#features" class="btn btn-outline-light btn-lg px-8  py-11 rounded-pill fw-semibold">
                                <i class="ph ph-play-circle me-2"></i>
                                Watch Demo
                            </a>
                        </div>

                        <!-- Trust Indicators -->
                        <div class="hero-trust d-flex flex-wrap align-items-center gap-4 text-white-75">
                            <div class="trust-item d-flex align-items-center">
                                <i class="ph ph-shield-check text-success me-2 fs-5"></i>
                                <span class="fw-medium">Secure & Trusted</span>
                            </div>
                            <div class="trust-item d-flex align-items-center">
                                <i class="ph ph-users text-info me-2 fs-5"></i>
                                <span class="fw-medium">10K+ Students</span>
                            </div>
                            <div class="trust-item d-flex align-items-center">
                                <i class="ph ph-star text-warning me-2 fs-5"></i>
                                <span class="fw-medium">4.9/5 Rating</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 order-1 order-lg-2 mb-5 mb-lg-0">
                    <div class="hero-image text-center position-relative">
                        <!-- Main Image -->
                        <div class="hero-image-container position-relative">
                            <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                 alt="Students Learning Online" 
                                 class="img-fluid rounded-4 shadow-2xl main-hero-image">
                            
                            <!-- Overlay Cards -->
                            <div class="floating-card card-1 position-absolute bg-white p-3 rounded-3 shadow-lg">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper bg-success-light me-3">
                                        <i class="ph ph-trophy text-success"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark small">Achievement</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">Course Completed!</div>
                                    </div>
                                </div>
                            </div>

                            <div class="floating-card card-2 position-absolute bg-white p-3 rounded-3 shadow-lg">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper bg-primary-light me-3">
                                        <i class="ph ph-video text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark small">Live Class</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">25 participants</div>
                                    </div>
                                </div>
                            </div>

                            <div class="floating-card card-3 position-absolute bg-white p-3 rounded-3 shadow-lg">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper bg-warning-light me-3">
                                        <i class="ph ph-chart-line text-warning"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark small">Progress</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">85% Complete</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="scroll-indicator position-absolute bottom-0 start-50 translate-middle-x mb-4">
            <a href="#features" class="text-white-75 text-decoration-none">
                <div class="scroll-mouse mx-auto mb-2"></div>
                <small class="fw-medium">Scroll to explore</small>
            </a>
        </div>
    </section>

    <style>
        :root {
            --primary-color: #2563eb;
            --primary-light: #dbeafe;
            --primary-dark: #1d4ed8;
            --success-color: #10b981;
            --success-light: #d1fae5;
            --warning-color: #f59e0b;
            --warning-light: #fef3c7;
            --info-color: #06b6d4;
            --info-light: #cffafe;
        }

        /* Navigation Styles */
        .z-index-1000 {
            z-index: 1000;
        }

        .navbar {
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .navbar.scrolled {
            background-color: rgba(37, 99, 235, 0.95) !important;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-size: 1.5rem;
        }

        .nav-link {
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            color: white !important;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--primary-color);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after {
            width: 80%;
        }

        .text-white-90 {
            color: rgba(255, 255, 255, 0.9) !important;
        }

        /* Hero Section Styles */
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
        }

        .hero-pattern {
            background-image: 
                radial-gradient(circle at 20% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 60%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
        }

        .floating-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .shape-1 {
            width: 100px;
            height: 100px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape-2 {
            width: 150px;
            height: 150px;
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }

        .shape-3 {
            width: 80px;
            height: 80px;
            top: 80%;
            left: 20%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-20px) rotate(120deg); }
            66% { transform: translateY(10px) rotate(240deg); }
        }

        .text-white-85 {
            color: rgba(255, 255, 255, 0.85) !important;
        }

        .text-white-75 {
            color: rgba(255, 255, 255, 0.75) !important;
        }

        .text-gradient-gold {
            background: linear-gradient(45deg, #fbbf24, #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-underline {
            width: 100%;
            height: 20px;
            bottom: -10px;
            left: 0;
        }

        .hero-badge {
            backdrop-filter: blur(10px);
        }

        .btn {
            transition: all 0.3s ease;
            border-width: 2px;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-light:hover {
            background-color: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .btn-outline-light:hover {
            background-color: white;
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-2px);
        }

        /* Hero Image Styles */
        .hero-image-container {
            max-width: 500px;
            margin: 0 auto;
        }

        .main-hero-image {
            width: 100%;
            height: auto;
            border-radius: 1rem;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
        }

        .floating-card {
            animation: cardFloat 3s ease-in-out infinite;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .card-1 {
            top: 10%;
            left: -10%;
            animation-delay: 0s;
        }

        .card-2 {
            top: 50%;
            right: -15%;
            animation-delay: 1s;
        }

        .card-3 {
            bottom: 15%;
            left: -5%;
            animation-delay: 2s;
        }

        @keyframes cardFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }

        .icon-wrapper {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bg-primary-light {
            background-color: var(--primary-light) !important;
        }

        .bg-success-light {
            background-color: var(--success-light) !important;
        }

        .bg-warning-light {
            background-color: var(--warning-light) !important;
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .text-success {
            color: var(--success-color) !important;
        }

        .text-warning {
            color: var(--warning-color) !important;
        }

        .text-info {
            color: var(--info-color) !important;
        }

        /* Scroll Indicator */
        .scroll-indicator {
            animation: bounce 2s infinite;
        }

        .scroll-mouse {
            width: 20px;
            height: 30px;
            border: 2px solid rgba(255, 255, 255, 0.5);
            border-radius: 15px;
            position: relative;
        }

        .scroll-mouse::before {
            content: '';
            position: absolute;
            top: 6px;
            left: 50%;
            width: 2px;
            height: 6px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 1px;
            transform: translateX(-50%);
            animation: scroll 2s infinite;
        }

        @keyframes scroll {
            0% { opacity: 1; transform: translateX(-50%) translateY(0); }
            100% { opacity: 0; transform: translateX(-50%) translateY(10px); }
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-section {
                padding: 100px 0 50px;
                min-height: auto;
            }

            .hero-title {
                font-size: 2.5rem !important;
            }

            .floating-card {
                position: relative !important;
                top: auto !important;
                left: auto !important;
                right: auto !important;
                bottom: auto !important;
                margin: 10px;
                display: inline-block;
            }

            .hero-image-container {
                margin-top: 2rem;
            }

            .navbar-nav {
                text-align: center;
                padding: 1rem 0;
            }

            .nav-item {
                margin: 0.5rem 0;
            }
        }

        .shadow-2xl {
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15) !important;
        }

        .shadow-lg {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        }

        .min-vh-100 {
            min-height: 100vh;
        }

        .z-2 {
            z-index: 2;
        }
    </style>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('mainNavbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const offsetTop = target.offsetTop - 80; // Account for fixed navbar
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Parallax effect for hero elements
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const heroImage = document.querySelector('.main-hero-image');
            const floatingShapes = document.querySelectorAll('.shape');
            
            if (heroImage) {
                heroImage.style.transform = `translateY(${scrolled * 0.1}px)`;
            }
            
            floatingShapes.forEach((shape, index) => {
                const speed = 0.05 + (index * 0.02);
                shape.style.transform = `translateY(${scrolled * speed}px) rotate(${scrolled * 0.1}deg)`;
            });
        });

        // Add loading animation
        document.addEventListener('DOMContentLoaded', function() {
            const heroContent = document.querySelector('.hero-content');
            const heroImage = document.querySelector('.hero-image');
            
            heroContent.style.opacity = '0';
            heroContent.style.transform = 'translateY(30px)';
            heroImage.style.opacity = '0';
            heroImage.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                heroContent.style.transition = 'all 0.8s ease';
                heroImage.style.transition = 'all 0.8s ease 0.2s';
                
                heroContent.style.opacity = '1';
                heroContent.style.transform = 'translateY(0)';
                heroImage.style.opacity = '1';
                heroImage.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</x-guest-layout>
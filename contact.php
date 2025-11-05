<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | AI-Solutions - Get in Touch for AI Transformation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/contact.css">
</head>
<body class="bg-gray-50">
    <?php include 'navbar.php'; ?>

    <!-- Hero Section -->
    <section class="hero-bg pt-32 pb-20">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <div class="fade-in">
                <div class="inline-flex items-center bg-blue-500/20 px-4 py-2 rounded-full text-blue-300 text-sm font-medium mb-6">
                    💬 Get in Touch
                </div>
                <h1 class="text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
                    Let's Transform Your <span class="text-gradient">Business Together</span>
                </h1>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto leading-relaxed mb-8">
                    Ready to revolutionize your employee experience with AI? Share your project requirements 
                    and let our experts design a custom solution that drives real results for your organization.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#contact-form" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-xl font-semibold hover:shadow-xl transition-all">
                        Start Your Project
                    </a>
                    <a href="#contact-info" class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-blue-600 transition-all">
                        Contact Information
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section id="contact-form" class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-6">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Tell Us About Your Project</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Fill out the form below and our team will get back to you within 24 hours with a customized proposal.
                </p>
            </div>
            
            <!-- Success Message -->
            <div id="success-message" class="success-message">
                <div class="flex items-center mb-4">
                    <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                    <h3 class="text-xl font-bold">Thank You for Your Interest!</h3>
                </div>
                <p class="mb-4">
                    We've received your project details and will review them carefully. Our team will contact you within 24 hours to discuss your requirements and next steps.
                </p>
                <p class="text-sm opacity-90">
                    In the meantime, feel free to explore our portfolio or check out our latest articles on AI innovation.
                </p>
            </div>
            
            <?php
            // Display success or error messages
            if (isset($_GET['success']) && $_GET['success'] == 1) {
                echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        <strong>Success!</strong> Your message has been sent. We\'ll get back to you within 24 hours.
                      </div>';
            }
            
            if (isset($_GET['error'])) {
                $errorMessages = [
                    'missing_fields' => 'Please fill in all required fields.',
                    'invalid_email' => 'Please enter a valid email address.',
                    'db_error' => 'There was a database error. Please try again.',
                    'submit_failed' => 'Failed to submit your message. Please try again.'
                ];
                
                $errorMessage = isset($errorMessages[$_GET['error']]) ? $errorMessages[$_GET['error']] : 'An unknown error occurred.';
                echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <strong>Error!</strong> ' . $errorMessage . '
                      </div>';
            }
            ?>
            
            <form id="contact-form-element" class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100" action="admin/contact_submission/contact_submissions.php" method="POST">
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Full Name -->
                    <div>
                        <label for="fullName" class="block text-sm font-semibold text-gray-700 mb-2">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="fullName" 
                            name="fullName" 
                            required
                            class="form-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 focus:outline-none"
                            placeholder="Enter your full name"
                        >
                        <div class="error-message" id="fullName-error"></div>
                    </div>
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            required
                            class="form-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 focus:outline-none"
                            placeholder="your.email@company.com"
                        >
                        <div class="error-message" id="email-error"></div>
                    </div>
                    
                    <!-- Phone Number -->
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                            Phone Number <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            required
                            class="form-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 focus:outline-none"
                            placeholder="+44 7XXX XXXXXX"
                        >
                        <div class="error-message" id="phone-error"></div>
                    </div>
                    
                    <!-- Company Name -->
                    <div>
                        <label for="company" class="block text-sm font-semibold text-gray-700 mb-2">
                            Company Name <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="company" 
                            name="company" 
                            required
                            class="form-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 focus:outline-none"
                            placeholder="Your company name"
                        >
                        <div class="error-message" id="company-error"></div>
                    </div>
                    
                    <!-- Country -->
                    <div>
                        <label for="country" class="block text-sm font-semibold text-gray-700 mb-2">
                            Country <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="country" 
                            name="country" 
                            required
                            class="form-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 focus:outline-none"
                        >
                            <option value="">Select your country</option>
                            <option value="UK">United Kingdom</option>
                            <option value="US">United States</option>
                            <option value="CA">Canada</option>
                            <option value="AU">Australia</option>
                            <option value="DE">Germany</option>
                            <option value="FR">France</option>
                            <option value="NL">Netherlands</option>
                            <option value="SE">Sweden</option>
                            <option value="NO">Norway</option>
                            <option value="DK">Denmark</option>
                            <option value="IE">Ireland</option>
                            <option value="CH">Switzerland</option>
                            <option value="BE">Belgium</option>
                            <option value="AT">Austria</option>
                            <option value="FI">Finland</option>
                            <option value="other">Other</option>
                        </select>
                        <div class="error-message" id="country-error"></div>
                    </div>
                    
                    <!-- Job Title -->
                    <div>
                        <label for="jobTitle" class="block text-sm font-semibold text-gray-700 mb-2">
                            Job Title <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="jobTitle" 
                            name="jobTitle" 
                            required
                            class="form-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 focus:outline-none"
                            placeholder="e.g., CTO, HR Director, Operations Manager"
                        >
                        <div class="error-message" id="jobTitle-error"></div>
                    </div>
                </div>
                
                <!-- Job Details -->
                <div class="mt-6">
                    <label for="jobDetails" class="block text-sm font-semibold text-gray-700 mb-2">
                        Project Details <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        id="jobDetails" 
                        name="jobDetails" 
                        required
                        rows="6"
                        class="form-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 focus:outline-none resize-none"
                        placeholder="Please describe your project requirements, current challenges, and what you hope to achieve with AI solutions. Include details about your organization size, industry, and timeline if possible."
                    ></textarea>
                    <div class="error-message" id="jobDetails-error"></div>
                    <p class="text-sm text-gray-500 mt-2">
                        The more details you provide, the better we can tailor our proposal to your needs.
                    </p>
                </div>
                
                <!-- Submit Button -->
                <div class="mt-8 text-center">
                    <button 
                        type="submit" 
                        id="submit-btn"
                        class="submit-btn bg-gradient-to-r from-blue-600 to-purple-600 text-white px-12 py-4 rounded-xl font-semibold text-lg hover:shadow-xl transition-all inline-flex items-center"
                    >
                        <span id="submit-text">Send Project Details</span>
                        <div class="loading-spinner ml-3"></div>
                    </button>
                    <p class="text-sm text-gray-500 mt-4">
                        We'll respond within 24 hours with a customized proposal
                    </p>
                </div>
            </form>
        </div>
    </section>

    <!-- Contact Information -->
    <section id="contact-info" class="py-20 bg-gray-50">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Get in Touch</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Multiple ways to connect with our team. We're here to help you transform your business with AI.
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                <!-- Office Address -->
                <div class="contact-info-card p-8 rounded-2xl text-center">
                    <div class="contact-icon mx-auto">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Office Address</h3>
                    <p class="text-gray-600 leading-relaxed">
                        AI-Solutions Ltd<br>
                        Innovation Centre<br>
                        Sunderland, SR1 3SD<br>
                        United Kingdom
                    </p>
                </div>
                
                <!-- Phone & Email -->
                <div class="contact-info-card p-8 rounded-2xl text-center">
                    <div class="contact-icon mx-auto">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Contact Details</h3>
                    <p class="text-gray-600 leading-relaxed mb-2">
                        <strong>Phone:</strong><br>
                        +44 191 XXX XXXX
                    </p>
                    <p class="text-gray-600 leading-relaxed">
                        <strong>Email:</strong><br>
                        hello@ai-solutions.co.uk
                    </p>
                </div>
                
                <!-- Business Hours -->
                <div class="contact-info-card p-8 rounded-2xl text-center">
                    <div class="contact-icon mx-auto">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Business Hours</h3>
                    <p class="text-gray-600 leading-relaxed">
                        <strong>Monday - Friday:</strong><br>
                        9:00 AM - 6:00 PM GMT<br><br>
                        <strong>Response Time:</strong><br>
                        Within 24 hours
                    </p>
                </div>
            </div>
            
            <!-- Google Maps -->
            <div class="fade-in">
                <h3 class="text-2xl font-bold text-gray-900 text-center mb-8">Find Us</h3>
                <div class="map-container">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2290.8947!2d-1.3838!3d54.9069!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x487e7c5c5c5c5c5c%3A0x5c5c5c5c5c5c5c5c!2sSunderland%2C%20UK!5e0!3m2!1sen!2suk!4v1234567890"
                        width="100%" 
                        height="400" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"
                        title="AI-Solutions Office Location in Sunderland, UK">
                    </iframe>
                </div>
                <p class="text-center text-gray-600 mt-4">
                    Located in the heart of Sunderland's innovation district, easily accessible by public transport and with parking available.
                </p>
            </div>
        </div>
    </section>

    <!-- Social Media & Additional Contact -->
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <div class="fade-in">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Connect With Us</h2>
                <p class="text-xl text-gray-600 mb-8">
                    Follow our journey and stay updated with the latest AI innovations
                </p>
                
                <div class="flex justify-center space-x-6">
                    <a href="#" class="social-btn bg-blue-600 text-white p-4 rounded-xl hover:bg-blue-700 transition-all">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                        </svg>
                    </a>
                    <a href="#" class="social-btn bg-blue-700 text-white p-4 rounded-xl hover:bg-blue-800 transition-all">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </a>
                    <a href="#" class="social-btn bg-gray-800 text-white p-4 rounded-xl hover:bg-gray-900 transition-all">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                        </svg>
                    </a>
                </div>
                
                <div class="mt-12 p-8 bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl border border-blue-100">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Prefer to Talk?</h3>
                    <p class="text-gray-600 mb-6">
                        Schedule a free 30-minute consultation to discuss your AI transformation goals
                    </p>
                    <a href="#contact-form" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-3 rounded-xl font-semibold hover:shadow-lg transition-all">
                        Schedule a Call
                    </a>
                </div>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Form validation
        const form = document.getElementById('contact-form-element');
        const submitBtn = document.getElementById('submit-btn');
        const submitText = document.getElementById('submit-text');
        const loadingSpinner = document.querySelector('.loading-spinner');
        const successMessage = document.getElementById('success-message');

        // Validation rules
        const validationRules = {
            fullName: {
                required: true,
                minLength: 2,
                message: 'Please enter your full name (at least 2 characters)'
            },
            email: {
                required: true,
                pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                message: 'Please enter a valid email address'
            },
            phone: {
                required: true,
                pattern: /^[\+]?[0-9\s\-\(\)]{10,}$/,
                message: 'Please enter a valid phone number'
            },
            company: {
                required: true,
                minLength: 2,
                message: 'Please enter your company name'
            },
            country: {
                required: true,
                message: 'Please select your country'
            },
            jobTitle: {
                required: true,
                minLength: 2,
                message: 'Please enter your job title'
            },
            jobDetails: {
                required: true,
                minLength: 20,
                message: 'Please provide more details about your project (at least 20 characters)'
            }
        };

        // Validate individual field
        function validateField(fieldName, value) {
            const rules = validationRules[fieldName];
            const field = document.getElementById(fieldName);
            const errorElement = document.getElementById(`${fieldName}-error`);

            // Reset field state
            field.classList.remove('error', 'valid');
            errorElement.classList.remove('show');

            if (rules.required && !value.trim()) {
                showFieldError(field, errorElement, rules.message);
                return false;
            }

            if (rules.minLength && value.trim().length < rules.minLength) {
                showFieldError(field, errorElement, rules.message);
                return false;
            }

            if (rules.pattern && !rules.pattern.test(value.trim())) {
                showFieldError(field, errorElement, rules.message);
                return false;
            }

            // Field is valid
            field.classList.add('valid');
            return true;
        }

        function showFieldError(field, errorElement, message) {
            field.classList.add('error');
            errorElement.textContent = message;
            errorElement.classList.add('show');
        }

        // Real-time validation
        Object.keys(validationRules).forEach(fieldName => {
            const field = document.getElementById(fieldName);
            
            field.addEventListener('blur', () => {
                validateField(fieldName, field.value);
            });
            
            field.addEventListener('input', () => {
                // Clear error state on input
                if (field.classList.contains('error')) {
                    field.classList.remove('error');
                    document.getElementById(`${fieldName}-error`).classList.remove('show');
                }
            });
        });

        // Form submission
        // Removed JavaScript form submission handler to allow normal form submission

        // Fade in animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Auto-resize textarea
        const textarea = document.getElementById('jobDetails');
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'967dba6514a44f3c',t:'MTc1Mzk3MTUzOS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>

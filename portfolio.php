<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio | AI-Solutions - Our Work in Action</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/portfolio.css">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white/95 backdrop-blur-sm shadow-sm fixed w-full top-0 z-50 border-b border-gray-100">
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">AI-Solutions</h1>
                        <p class="text-xs text-gray-500">Sunderland, UK</p>
                    </div>
                </div>
                
                <div class="hidden lg:flex items-center space-x-8">
                    <a href="index.php" class="text-gray-700 hover:text-blue-600 transition-colors">Home</a>
                    <a href="solutions.php" class="text-gray-700 hover:text-blue-600 transition-colors">Solutions</a>
                    <a href="#portfolio" class="text-blue-600 font-medium">Portfolio</a>
                    <a href="feedback.php" class="text-gray-700 hover:text-blue-600 transition-colors">Feedback</a>
                    <a href="events.php" class="text-gray-700 hover:text-blue-600 transition-colors">Events</a>
                    <a href="articles.php" class="text-gray-700 hover:text-blue-600 transition-colors">Articles</a>
                    <a href="contact.php" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-xl font-medium hover:shadow-lg transition-all">
                        Contact Us
                    </a>
                </div>
                
                <button id="mobile-menu-btn" class="lg:hidden p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="lg:hidden hidden bg-white border-t">
            <div class="px-6 py-4 space-y-3">
                <a href="index.php" class="block text-gray-700">Home</a>
                <a href="solutions.php" class="block text-gray-700">Solutions</a>
                <a href="#portfolio" class="block text-blue-600 font-medium">Portfolio</a>
                <a href="feedback.php" class="block text-gray-700">Feedback</a>
                <a href="events.php" class="block text-gray-700">Events</a>
                <a href="articles.php" class="block text-gray-700">Articles</a>
                <a href="contact.php" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 rounded-xl font-medium text-center block">
                    Contact Us
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-bg pt-32 pb-20">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <div class="fade-in">
                <div class="inline-flex items-center bg-blue-500/20 px-4 py-2 rounded-full text-blue-300 text-sm font-medium mb-6">
                    🏆 Our Work in Action
                </div>
                <h1 class="text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
                    Success <span class="text-gradient">Stories</span>
                </h1>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto leading-relaxed">
                    Explore our portfolio of successful AI implementations that have transformed businesses across industries, delivering measurable results and exceptional value.
                </p>
                
                <div class="flex justify-center space-x-8 mt-12">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-blue-400">150+</div>
                        <div class="text-sm text-gray-400">Projects Completed</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-purple-400">12</div>
                        <div class="text-sm text-gray-400">Industries Served</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-green-400">98%</div>
                        <div class="text-sm text-gray-400">Client Satisfaction</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="py-8 bg-white border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex flex-wrap justify-center gap-4">
                <button class="filter-btn active px-6 py-3 rounded-xl font-medium bg-gray-200 text-gray-700 hover:bg-gray-300" data-filter="all">
                    All Projects
                </button>
                <button class="filter-btn px-6 py-3 rounded-xl font-medium bg-gray-200 text-gray-700 hover:bg-gray-300" data-filter="healthcare">
                    Healthcare
                </button>
                <button class="filter-btn px-6 py-3 rounded-xl font-medium bg-gray-200 text-gray-700 hover:bg-gray-300" data-filter="education">
                    Education
                </button>
                <button class="filter-btn px-6 py-3 rounded-xl font-medium bg-gray-200 text-gray-700 hover:bg-gray-300" data-filter="manufacturing">
                    Manufacturing
                </button>
                <button class="filter-btn px-6 py-3 rounded-xl font-medium bg-gray-200 text-gray-700 hover:bg-gray-300" data-filter="finance">
                    Finance
                </button>
                <button class="filter-btn px-6 py-3 rounded-xl font-medium bg-gray-200 text-gray-700 hover:bg-gray-300" data-filter="2024">
                    2024
                </button>
                <button class="filter-btn px-6 py-3 rounded-xl font-medium bg-gray-200 text-gray-700 hover:bg-gray-300" data-filter="2023">
                    2023
                </button>
            </div>
        </div>
    </section>

    <!-- Portfolio Grid -->
    <section id="portfolio" class="py-20 bg-gray-50">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8" id="portfolio-grid">
                
                <!-- Project 1: NHS Digital Assistant -->
                <div class="project-card bg-white rounded-2xl shadow-lg overflow-hidden card-hover fade-in" data-category="healthcare 2024">
                    <div class="h-48 bg-gradient-to-br from-red-500 to-pink-600 flex items-center justify-center relative">
                        <svg class="w-20 h-20 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-white text-sm font-medium">
                            2024
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-xl font-bold text-gray-900">NHS Digital Assistant</h3>
                            <span class="text-sm text-gray-500">Healthcare</span>
                        </div>
                        <p class="text-gray-600 mb-4 text-sm leading-relaxed">
                            AI-powered patient support system reducing wait times by 40% and improving patient satisfaction across 15 NHS trusts.
                        </p>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="tech-badge px-3 py-1 rounded-full text-xs font-medium">Python</span>
                            <span class="tech-badge px-3 py-1 rounded-full text-xs font-medium">TensorFlow</span>
                            <span class="tech-badge px-3 py-1 rounded-full text-xs font-medium">NLP</span>
                        </div>
                        <button class="view-project-btn w-full bg-gradient-to-r from-red-500 to-pink-600 text-white py-3 px-4 rounded-xl font-medium hover:shadow-lg transition-all text-sm" 
                                data-project="nhs-assistant">
                            View Case Study
                        </button>
                    </div>
                </div>

                <!-- Project 2: University Learning Platform -->
                <div class="project-card bg-white rounded-2xl shadow-lg overflow-hidden card-hover fade-in" data-category="education 2024">
                    <div class="h-48 bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center relative">
                        <svg class="w-20 h-20 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-white text-sm font-medium">
                            2024
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-xl font-bold text-gray-900">Smart Learning Platform</h3>
                            <span class="text-sm text-gray-500">Education</span>
                        </div>
                        <p class="text-gray-600 mb-4 text-sm leading-relaxed">
                            Personalized AI tutoring system for Sunderland University, improving student outcomes by 25% and reducing dropout rates.
                        </p>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="tech-badge px-3 py-1 rounded-full text-xs font-medium">React</span>
                            <span class="tech-badge px-3 py-1 rounded-full text-xs font-medium">Machine Learning</span>
                            <span class="tech-badge px-3 py-1 rounded-full text-xs font-medium">Analytics</span>
                        </div>
                        <button class="view-project-btn w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white py-3 px-4 rounded-xl font-medium hover:shadow-lg transition-all text-sm" 
                                data-project="learning-platform">
                            View Case Study
                        </button>
                    </div>
                </div>

                <!-- Project 3: Manufacturing Optimizer -->
                <div class="project-card bg-white rounded-2xl shadow-lg overflow-hidden card-hover fade-in" data-category="manufacturing 2024">
                    <div class="h-48 bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center relative">
                        <svg class="w-20 h-20 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                        <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-white text-sm font-medium">
                            2024
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-xl font-bold text-gray-900">Production Optimizer</h3>
                            <span class="text-sm text-gray-500">Manufacturing</span>
                        </div>
                        <p class="text-gray-600 mb-4 text-sm leading-relaxed">
                            AI-driven production optimization for Nissan UK, reducing waste by 30% and increasing efficiency across three manufacturing plants.
                        </p>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="tech-badge px-3 py-1 rounded-full text-xs font-medium">IoT</span>
                            <span class="tech-badge px-3 py-1 rounded-full text-xs font-medium">Predictive Analytics</span>
                            <span class="tech-badge px-3 py-1 rounded-full text-xs font-medium">Azure</span>
                        </div>
                        <button class="view-project-btn w-full bg-gradient-to-r from-green-500 to-teal-600 text-white py-3 px-4 rounded-xl font-medium hover:shadow-lg transition-all text-sm" 
                                data-project="production-optimizer">
                            View Case Study
                        </button>
                    </div>
                </div>

                <!-- Project 4: Banking Chatbot -->
                <div class="project-card bg-white rounded-2xl shadow-lg overflow-hidden card-hover fade-in" data-category="finance 2023">
                    <div class="h-48 bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center relative">
                        <svg class="w-20 h-20 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-white text-sm font-medium">
                            2023
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-xl font-bold text-gray-900">Smart Banking Assistant</h3>
                            <span class="text-sm text-gray-500">Finance</span>
                        </div>
                        <p class="text-gray-600 mb-4 text-sm leading-relaxed">
                            Intelligent customer service chatbot for Virgin Money, handling 85% of customer queries automatically and reducing call center load.
                        </p>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="tech-badge px-3 py-1 rounded-full text-xs font-medium">Node.js</span>
                            <span class="tech-badge px-3 py-1 rounded-full text-xs font-medium">OpenAI</span>
                            <span class="tech-badge px-3 py-1 rounded-full text-xs font-medium">Security</span>
                        </div>
                        <button class="view-project-btn w-full bg-gradient-to-r from-purple-500 to-pink-600 text-white py-3 px-4 rounded-xl font-medium hover:shadow-lg transition-all text-sm" 
                                data-project="banking-assistant">
                            View Case Study
                        </button>
                    </div>
                </div>

                <!-- Project 5: Retail Analytics -->
                <div class="project-card bg-white rounded-2xl shadow-lg overflow-hidden card-hover fade-in" data-category="retail 2023">
                    <div class="h-48 bg-gradient-to-br from-yellow-500 to-orange-600 flex items-center justify-center relative">
                        <svg class="w-20 h-20 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-white text-sm font-medium">
                            2023
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-xl font-bold text-gray-900">Retail Intelligence Suite</h3>
                            <span class="text-sm text-gray-500">Retail</span>
                        </div>
                        <p class="text-gray-600 mb-4 text-sm leading-relaxed">
                            Comprehensive analytics platform for Marks & Spencer, optimizing inventory management and increasing sales by 18% across 200+ stores.
                        </p>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="tech-badge px-3 py-1 rounded-full text-xs font-medium">Big Data</span>
                            <span class="tech-badge px-3 py-1 rounded-full text-xs font-medium">Tableau</span>
                            <span class="tech-badge px-3 py-1 rounded-full text-xs font-medium">AWS</span>
                        </div>
                        <button class="view-project-btn w-full bg-gradient-to-r from-yellow-500 to-orange-600 text-white py-3 px-4 rounded-xl font-medium hover:shadow-lg transition-all text-sm" 
                                data-project="retail-analytics">
                            View Case Study
                        </button>
                    </div>
                </div>

                <!-- Project 6: HR Automation -->
                <div class="project-card bg-white rounded-2xl shadow-lg overflow-hidden card-hover fade-in" data-category="hr 2023">
                    <div class="h-48 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center relative">
                        <svg class="w-20 h-20 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-white text-sm font-medium">
                            2023
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-xl font-bold text-gray-900">HR Automation Platform</h3>
                            <span class="text-sm text-gray-500">Human Resources</span>
                        </div>
                        <p class="text-gray-600 mb-4 text-sm leading-relaxed">
                            Intelligent recruitment and employee management system for Sage Group, reducing hiring time by 50% and improving employee satisfaction.
                        </p>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="tech-badge px-3 py-1 rounded-full text-xs font-medium">Django</span>
                            <span class="tech-badge px-3 py-1 rounded-full text-xs font-medium">AI Matching</span>
                            <span class="tech-badge px-3 py-1 rounded-full text-xs font-medium">PostgreSQL</span>
                        </div>
                        <button class="view-project-btn w-full bg-gradient-to-r from-indigo-500 to-purple-600 text-white py-3 px-4 rounded-xl font-medium hover:shadow-lg transition-all text-sm" 
                                data-project="hr-automation">
                            View Case Study
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Technologies Section -->
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Technologies We Use</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Our expertise spans across cutting-edge technologies and frameworks to deliver robust, scalable AI solutions.
                </p>
            </div>
            
            <div class="grid md:grid-cols-4 gap-8">
                <div class="text-center fade-in">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-white font-bold text-lg">AI</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">AI & Machine Learning</h3>
                    <div class="flex flex-wrap justify-center gap-2">
                        <span class="tech-badge px-3 py-1 rounded-full text-xs">TensorFlow</span>
                        <span class="tech-badge px-3 py-1 rounded-full text-xs">PyTorch</span>
                        <span class="tech-badge px-3 py-1 rounded-full text-xs">OpenAI</span>
                    </div>
                </div>
                
                <div class="text-center fade-in">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-white font-bold text-lg">BE</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Backend Development</h3>
                    <div class="flex flex-wrap justify-center gap-2">
                        <span class="tech-badge px-3 py-1 rounded-full text-xs">Python</span>
                        <span class="tech-badge px-3 py-1 rounded-full text-xs">Node.js</span>
                        <span class="tech-badge px-3 py-1 rounded-full text-xs">Django</span>
                    </div>
                </div>
                
                <div class="text-center fade-in">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-white font-bold text-lg">FE</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Frontend Development</h3>
                    <div class="flex flex-wrap justify-center gap-2">
                        <span class="tech-badge px-3 py-1 rounded-full text-xs">React</span>
                        <span class="tech-badge px-3 py-1 rounded-full text-xs">Vue.js</span>
                        <span class="tech-badge px-3 py-1 rounded-full text-xs">TypeScript</span>
                    </div>
                </div>
                
                <div class="text-center fade-in">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-white font-bold text-lg">☁</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Cloud & Infrastructure</h3>
                    <div class="flex flex-wrap justify-center gap-2">
                        <span class="tech-badge px-3 py-1 rounded-full text-xs">AWS</span>
                        <span class="tech-badge px-3 py-1 rounded-full text-xs">Azure</span>
                        <span class="tech-badge px-3 py-1 rounded-full text-xs">Docker</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-blue-600 to-purple-700">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <div class="fade-in">
                <h2 class="text-4xl font-bold text-white mb-6">
                    Ready to Start Your Success Story?
                </h2>
                <p class="text-xl text-blue-100 mb-8 leading-relaxed">
                    Join the growing list of successful companies that have transformed their operations with our AI solutions.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="contact.php" class="bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold hover:shadow-xl transition-all">
                        Start Your Project
                    </a>
                    <a href="solutions.php" class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-blue-600 transition-all">
                        View Solutions
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-16">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold">AI-Solutions</h3>
                            <p class="text-gray-400 text-sm">Sunderland, UK</p>
                        </div>
                    </div>
                    <p class="text-gray-400 mb-6 max-w-md">
                        Transforming digital employee experiences through innovative AI-powered solutions. Making advanced technology accessible to businesses worldwide.
                    </p>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Portfolio</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#portfolio" class="hover:text-white transition-colors">Healthcare Projects</a></li>
                        <li><a href="#portfolio" class="hover:text-white transition-colors">Education Solutions</a></li>
                        <li><a href="#portfolio" class="hover:text-white transition-colors">Manufacturing</a></li>
                        <li><a href="#portfolio" class="hover:text-white transition-colors">Finance & Banking</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Company</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="index.php" class="hover:text-white transition-colors">Home</a></li>
                        <li><a href="solutions.php" class="hover:text-white transition-colors">Solutions</a></li>
                        <li><a href="feedback.php" class="hover:text-white transition-colors">Testimonials</a></li>
                        <li><a href="contact.php" class="hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-8 text-center text-gray-400">
                <p>&copy; 2024 AI-Solutions. All rights reserved. Innovating the future of digital employee experience.</p>
            </div>
        </div>
    </footer>

    <!-- Project Detail Modals -->
    <div id="project-modal" class="modal fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="modal-content bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div id="modal-content">
                <!-- Dynamic content will be inserted here -->
            </div>
        </div>
    </div>

    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Filter functionality
        const filterBtns = document.querySelectorAll('.filter-btn');
        const projectCards = document.querySelectorAll('.project-card');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Remove active class from all buttons
                filterBtns.forEach(b => b.classList.remove('active'));
                // Add active class to clicked button
                btn.classList.add('active');

                const filter = btn.getAttribute('data-filter');

                projectCards.forEach(card => {
                    const categories = card.getAttribute('data-category').split(' ');
                    if (filter === 'all' || categories.includes(filter)) {
                        card.classList.remove('hidden');
                    } else {
                        card.classList.add('hidden');
                    }
                });
            });
        });

        // Modal functionality
        const modal = document.getElementById('project-modal');
        const modalContent = document.getElementById('modal-content');
        const viewProjectBtns = document.querySelectorAll('.view-project-btn');

        const projectData = {
            'nhs-assistant': {
                title: 'NHS Digital Assistant',
                client: 'NHS Trusts (15 locations)',
                industry: 'Healthcare',
                year: '2024',
                problem: 'Long patient wait times and overwhelmed call centers were affecting patient satisfaction across multiple NHS trusts. Staff were spending 60% of their time on routine inquiries.',
                solution: 'Developed an AI-powered virtual assistant capable of handling patient inquiries, appointment scheduling, and providing medical information 24/7. The system uses natural language processing to understand patient needs and provides accurate, helpful responses.',
                results: [
                    '40% reduction in patient wait times',
                    '85% of routine inquiries handled automatically',
                    '60% improvement in patient satisfaction scores',
                    '£2.3M annual cost savings across all trusts'
                ],
                technologies: ['Python', 'TensorFlow', 'Natural Language Processing', 'Azure Cloud', 'HIPAA Compliance'],
                testimonial: 'The AI assistant has transformed our patient experience. We can now provide instant support while our staff focus on complex medical cases.',
                testimonialAuthor: 'Dr. Sarah Mitchell, NHS Trust Director'
            },
            'learning-platform': {
                title: 'Smart Learning Platform',
                client: 'University of Sunderland',
                industry: 'Education',
                year: '2024',
                problem: 'High student dropout rates and varying learning paces were affecting academic outcomes. Traditional one-size-fits-all approach wasn\'t meeting diverse student needs.',
                solution: 'Created a personalized AI tutoring system that adapts to individual learning styles, provides real-time feedback, and identifies students at risk of dropping out. The platform uses machine learning to optimize learning paths.',
                results: [
                    '25% improvement in student outcomes',
                    '35% reduction in dropout rates',
                    '90% student satisfaction with personalized learning',
                    'Deployed across 12 departments'
                ],
                technologies: ['React', 'Machine Learning', 'Learning Analytics', 'Node.js', 'MongoDB'],
                testimonial: 'This platform has revolutionized how we deliver education. Students are more engaged and achieving better results than ever before.',
                testimonialAuthor: 'Prof. James Wilson, Head of Digital Learning'
            },
            'production-optimizer': {
                title: 'Production Optimizer',
                client: 'Nissan UK Manufacturing',
                industry: 'Manufacturing',
                year: '2024',
                problem: 'Production inefficiencies and waste were costing millions annually. Manual monitoring couldn\'t keep up with the complexity of modern manufacturing processes.',
                solution: 'Implemented an AI-driven production optimization system using IoT sensors and predictive analytics to monitor equipment, predict maintenance needs, and optimize production schedules in real-time.',
                results: [
                    '30% reduction in production waste',
                    '45% improvement in equipment efficiency',
                    '£5.2M annual cost savings',
                    'Zero unplanned downtime in 6 months'
                ],
                technologies: ['IoT Sensors', 'Predictive Analytics', 'Azure IoT Hub', 'Power BI', 'Machine Learning'],
                testimonial: 'The production optimizer has exceeded all expectations. We\'ve achieved efficiency levels we never thought possible.',
                testimonialAuthor: 'Mark Thompson, Production Director'
            },
            'banking-assistant': {
                title: 'Smart Banking Assistant',
                client: 'Virgin Money',
                industry: 'Finance',
                year: '2023',
                problem: 'High call center volumes and long customer wait times were impacting customer satisfaction. 70% of calls were routine inquiries that could be automated.',
                solution: 'Developed an intelligent chatbot capable of handling complex banking queries, account management, and financial advice while maintaining strict security protocols.',
                results: [
                    '85% of queries handled automatically',
                    '60% reduction in call center load',
                    '92% customer satisfaction rating',
                    '£1.8M annual operational savings'
                ],
                technologies: ['Node.js', 'OpenAI GPT', 'Banking APIs', 'Security Encryption', 'AWS'],
                testimonial: 'Our customers love the instant support, and our team can focus on complex financial advisory services.',
                testimonialAuthor: 'Lisa Chen, Head of Customer Experience'
            },
            'retail-analytics': {
                title: 'Retail Intelligence Suite',
                client: 'Marks & Spencer',
                industry: 'Retail',
                year: '2023',
                problem: 'Inventory management across 200+ stores was inefficient, leading to stockouts and overstock situations. Lack of real-time insights affected decision-making.',
                solution: 'Built a comprehensive analytics platform that provides real-time inventory insights, demand forecasting, and automated reordering across all store locations.',
                results: [
                    '18% increase in sales',
                    '25% reduction in inventory costs',
                    '90% improvement in stock availability',
                    'Deployed across 200+ stores'
                ],
                technologies: ['Big Data Analytics', 'Tableau', 'AWS Redshift', 'Machine Learning', 'Real-time APIs'],
                testimonial: 'The intelligence suite has transformed our retail operations. We now make data-driven decisions that directly impact our bottom line.',
                testimonialAuthor: 'David Roberts, Retail Operations Manager'
            },
            'hr-automation': {
                title: 'HR Automation Platform',
                client: 'Sage Group',
                industry: 'Human Resources',
                year: '2023',
                problem: 'Manual recruitment processes were slow and biased. Employee management tasks consumed excessive HR resources, limiting strategic initiatives.',
                solution: 'Created an intelligent HR platform with AI-powered candidate matching, automated screening, and employee engagement analytics to streamline all HR processes.',
                results: [
                    '50% reduction in hiring time',
                    '40% improvement in candidate quality',
                    '35% increase in employee satisfaction',
                    '£900K annual process savings'
                ],
                technologies: ['Django', 'AI Matching Algorithms', 'PostgreSQL', 'React', 'Machine Learning'],
                testimonial: 'This platform has revolutionized our HR operations. We can now focus on strategic people initiatives rather than administrative tasks.',
                testimonialAuthor: 'Emma Taylor, HR Director'
            }
        };

        viewProjectBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const projectId = btn.getAttribute('data-project');
                const project = projectData[projectId];
                
                if (project) {
                    modalContent.innerHTML = `
                        <div class="p-8">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <h2 class="text-3xl font-bold text-gray-900 mb-2">${project.title}</h2>
                                    <div class="flex items-center space-x-4 text-gray-600">
                                        <span>${project.client}</span>
                                        <span>•</span>
                                        <span>${project.industry}</span>
                                        <span>•</span>
                                        <span>${project.year}</span>
                                    </div>
                                </div>
                                <button id="close-modal" class="text-gray-400 hover:text-gray-600 text-2xl">×</button>
                            </div>
                            
                            <div class="grid md:grid-cols-2 gap-8 mb-8">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-4">The Challenge</h3>
                                    <p class="text-gray-600 leading-relaxed">${project.problem}</p>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-4">Our Solution</h3>
                                    <p class="text-gray-600 leading-relaxed">${project.solution}</p>
                                </div>
                            </div>
                            
                            <div class="mb-8">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Key Results</h3>
                                <div class="grid md:grid-cols-2 gap-4">
                                    ${project.results.map(result => `
                                        <div class="flex items-center">
                                            <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                                            <span class="text-gray-600">${result}</span>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                            
                            <div class="mb-8">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Technologies Used</h3>
                                <div class="flex flex-wrap gap-3">
                                    ${project.technologies.map(tech => `
                                        <span class="tech-badge px-4 py-2 rounded-full text-sm font-medium">${tech}</span>
                                    `).join('')}
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 rounded-2xl p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Client Testimonial</h3>
                                <blockquote class="text-gray-600 italic mb-4">"${project.testimonial}"</blockquote>
                                <cite class="text-gray-500 font-medium">— ${project.testimonialAuthor}</cite>
                            </div>
                            
                            <div class="mt-8 text-center">
                                <a href="contact.php" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-xl font-semibold hover:shadow-lg transition-all">
                                    Start Your Project
                                </a>
                            </div>
                        </div>
                    `;
                    
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                    
                    // Add close modal functionality
                    document.getElementById('close-modal').addEventListener('click', closeModal);
                }
            });
        });

        function closeModal() {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });

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

        // Smooth scrolling
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
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'967da7be663b9e77',t:'MTc1Mzk3MDc3NS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>

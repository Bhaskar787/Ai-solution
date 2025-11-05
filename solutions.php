<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solutions | AI-Solutions - AI-Powered Software Services</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/solutions.css">
</head>
<body class="bg-gray-50">
    <?php include 'navbar.php'; ?>

    <!-- Hero Section -->
    <section class="hero-bg pt-32 pb-20">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <div class="fade-in">
                <div class="inline-flex items-center bg-blue-500/20 px-4 py-2 rounded-full text-blue-300 text-sm font-medium mb-6">
                    🚀 AI-Powered Solutions
                </div>
                <h1 class="text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
                    Our <span class="text-gradient">AI Solutions</span>
                </h1>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto leading-relaxed">
                    Discover our comprehensive suite of AI-powered software services designed to transform your digital workplace and accelerate innovation across industries.
                </p>
            </div>
        </div>
    </section>

    <!-- What Makes Us Unique -->
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">What Makes Our Solutions Unique</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Our AI-powered approach combines cutting-edge technology with affordability and rapid deployment to deliver exceptional results.
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center fade-in">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">AI-Powered Intelligence</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Advanced machine learning algorithms that adapt and improve over time, providing intelligent solutions that evolve with your business needs.
                    </p>
                </div>
                
                <div class="text-center fade-in">
                    <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Affordable & Scalable</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Cost-effective solutions that grow with your business, ensuring you get maximum value without breaking the budget.
                    </p>
                </div>
                
                <div class="text-center fade-in">
                    <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Rapid Deployment</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Quick implementation and fast time-to-value, getting your solutions up and running in days, not months.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="py-8 bg-gray-50 border-y border-gray-200">
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex flex-wrap justify-center gap-4">
                <button class="filter-btn active px-6 py-3 rounded-xl font-medium bg-gray-200 text-gray-700 hover:bg-gray-300" data-filter="all">
                    All Solutions
                </button>
                <button class="filter-btn px-6 py-3 rounded-xl font-medium bg-gray-200 text-gray-700 hover:bg-gray-300" data-filter="ai-assistant">
                    AI Assistant
                </button>
                <button class="filter-btn px-6 py-3 rounded-xl font-medium bg-gray-200 text-gray-700 hover:bg-gray-300" data-filter="prototyping">
                    Prototyping
                </button>
                <button class="filter-btn px-6 py-3 rounded-xl font-medium bg-gray-200 text-gray-700 hover:bg-gray-300" data-filter="employee-experience">
                    Employee Experience
                </button>
                <button class="filter-btn px-6 py-3 rounded-xl font-medium bg-gray-200 text-gray-700 hover:bg-gray-300" data-filter="industry-specific">
                    Industry Specific
                </button>
            </div>
        </div>
    </section>

    <!-- Solutions Grid -->
    <section id="solutions" class="py-20 bg-gray-50">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8" id="solutions-grid">
                
                <!-- AI Virtual Assistant -->
                <div class="solution-card bg-white rounded-2xl shadow-lg overflow-hidden card-hover fade-in" data-category="ai-assistant">
                    <div class="h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                        <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">AI Virtual Assistant</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Intelligent virtual assistant that provides 24/7 support, answers queries, and streamlines workplace communications with natural language processing.
                        </p>
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-900 mb-3">Key Benefits:</h4>
                            <ul class="space-y-2 text-gray-600">
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                                    24/7 automated customer support
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                                    Natural language understanding
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                                    Multi-platform integration
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                                    Reduces response time by 80%
                                </li>
                            </ul>
                        </div>
                        <a href="contact.php" class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-3 px-6 rounded-xl font-medium hover:shadow-lg transition-all text-center block">
                            Request Info
                        </a>
                    </div>
                </div>

                <!-- Rapid Prototyping Platform -->
                <div class="solution-card bg-white rounded-2xl shadow-lg overflow-hidden card-hover fade-in" data-category="prototyping">
                    <div class="h-48 bg-gradient-to-br from-green-500 to-blue-600 flex items-center justify-center">
                        <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Rapid Prototyping Platform</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            AI-powered prototyping tools that accelerate design and development cycles, enabling faster innovation and reduced time-to-market.
                        </p>
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-900 mb-3">Key Benefits:</h4>
                            <ul class="space-y-2 text-gray-600">
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-3"></span>
                                    50% faster development cycles
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-3"></span>
                                    Cost-effective prototyping
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-3"></span>
                                    Real-time collaboration tools
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-3"></span>
                                    Automated testing capabilities
                                </li>
                            </ul>
                        </div>
                        <a href="contact.php" class="w-full bg-gradient-to-r from-green-500 to-blue-600 text-white py-3 px-6 rounded-xl font-medium hover:shadow-lg transition-all text-center block">
                            Request Info
                        </a>
                    </div>
                </div>

                <!-- Employee Experience Analytics -->
                <div class="solution-card bg-white rounded-2xl shadow-lg overflow-hidden card-hover fade-in" data-category="employee-experience">
                    <div class="h-48 bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center">
                        <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Employee Experience Analytics</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Comprehensive analytics platform that measures and improves employee satisfaction, productivity, and engagement across your organization.
                        </p>
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-900 mb-3">Key Benefits:</h4>
                            <ul class="space-y-2 text-gray-600">
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-purple-500 rounded-full mr-3"></span>
                                    Real-time engagement tracking
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-purple-500 rounded-full mr-3"></span>
                                    Predictive analytics insights
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-purple-500 rounded-full mr-3"></span>
                                    Customizable dashboards
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-purple-500 rounded-full mr-3"></span>
                                    Improves retention by 35%
                                </li>
                            </ul>
                        </div>
                        <a href="contact.php" class="w-full bg-gradient-to-r from-purple-500 to-pink-600 text-white py-3 px-6 rounded-xl font-medium hover:shadow-lg transition-all text-center block">
                            Request Info
                        </a>
                    </div>
                </div>

                <!-- Workflow Automation Suite -->
                <div class="solution-card bg-white rounded-2xl shadow-lg overflow-hidden card-hover fade-in" data-category="employee-experience">
                    <div class="h-48 bg-gradient-to-br from-yellow-500 to-orange-600 flex items-center justify-center">
                        <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Workflow Automation Suite</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Intelligent automation tools that streamline repetitive tasks, optimize workflows, and free up employees to focus on high-value activities.
                        </p>
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-900 mb-3">Key Benefits:</h4>
                            <ul class="space-y-2 text-gray-600">
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-yellow-500 rounded-full mr-3"></span>
                                    Automates 70% of routine tasks
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-yellow-500 rounded-full mr-3"></span>
                                    Reduces processing time
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-yellow-500 rounded-full mr-3"></span>
                                    Error reduction by 90%
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-yellow-500 rounded-full mr-3"></span>
                                    Easy drag-and-drop interface
                                </li>
                            </ul>
                        </div>
                        <a href="contact.php" class="w-full bg-gradient-to-r from-yellow-500 to-orange-600 text-white py-3 px-6 rounded-xl font-medium hover:shadow-lg transition-all text-center block">
                            Request Info
                        </a>
                    </div>
                </div>

                <!-- Healthcare AI Assistant -->
                <div class="solution-card bg-white rounded-2xl shadow-lg overflow-hidden card-hover fade-in" data-category="industry-specific">
                    <div class="h-48 bg-gradient-to-br from-red-500 to-pink-600 flex items-center justify-center">
                        <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Healthcare AI Assistant</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Specialized AI solution for healthcare providers, offering patient support, appointment scheduling, and medical information assistance.
                        </p>
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-900 mb-3">Key Benefits:</h4>
                            <ul class="space-y-2 text-gray-600">
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-red-500 rounded-full mr-3"></span>
                                    HIPAA compliant platform
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-red-500 rounded-full mr-3"></span>
                                    Patient triage assistance
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-red-500 rounded-full mr-3"></span>
                                    Appointment optimization
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-red-500 rounded-full mr-3"></span>
                                    Reduces wait times by 40%
                                </li>
                            </ul>
                        </div>
                        <a href="contact.php" class="w-full bg-gradient-to-r from-red-500 to-pink-600 text-white py-3 px-6 rounded-xl font-medium hover:shadow-lg transition-all text-center block">
                            Request Info
                        </a>
                    </div>
                </div>

                <!-- Education Management System -->
                <div class="solution-card bg-white rounded-2xl shadow-lg overflow-hidden card-hover fade-in" data-category="industry-specific">
                    <div class="h-48 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                        <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Education Management System</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Comprehensive AI-powered platform for educational institutions, featuring student support, administrative automation, and learning analytics.
                        </p>
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-900 mb-3">Key Benefits:</h4>
                            <ul class="space-y-2 text-gray-600">
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-indigo-500 rounded-full mr-3"></span>
                                    Personalized learning paths
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-indigo-500 rounded-full mr-3"></span>
                                    Automated grading system
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-indigo-500 rounded-full mr-3"></span>
                                    Student performance analytics
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-indigo-500 rounded-full mr-3"></span>
                                    Improves outcomes by 25%
                                </li>
                            </ul>
                        </div>
                        <a href="contact.php" class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 text-white py-3 px-6 rounded-xl font-medium hover:shadow-lg transition-all text-center block">
                            Request Info
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Industry Use Cases -->
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Industry Use Cases</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Our AI solutions are tailored to meet the unique challenges and requirements of various industries.
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center fade-in">
                    <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Healthcare</h3>
                    <p class="text-gray-600 text-sm">Patient support, appointment scheduling, medical assistance</p>
                </div>
                
                <div class="text-center fade-in">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Education</h3>
                    <p class="text-gray-600 text-sm">Student support, learning analytics, administrative automation</p>
                </div>
                
                <div class="text-center fade-in">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-teal-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Manufacturing</h3>
                    <p class="text-gray-600 text-sm">Process optimization, quality control, predictive maintenance</p>
                </div>
                
                <div class="text-center fade-in">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Finance</h3>
                    <p class="text-gray-600 text-sm">Risk analysis, customer service, fraud detection</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-blue-600 to-purple-700">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <div class="fade-in">
                <h2 class="text-4xl font-bold text-white mb-6">
                    Ready to Transform Your Business?
                </h2>
                <p class="text-xl text-blue-100 mb-8 leading-relaxed">
                    Discover how our AI-powered solutions can revolutionize your workplace and accelerate your digital transformation journey.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="contact.php" class="bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold hover:shadow-xl transition-all">
                        Get Started Today
                    </a>
                    <a href="portfolio.php" class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-blue-600 transition-all">
                        View Portfolio
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

        // Filter functionality
        const filterBtns = document.querySelectorAll('.filter-btn');
        const solutionCards = document.querySelectorAll('.solution-card');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Remove active class from all buttons
                filterBtns.forEach(b => b.classList.remove('active'));
                // Add active class to clicked button
                btn.classList.add('active');

                const filter = btn.getAttribute('data-filter');

                solutionCards.forEach(card => {
                    if (filter === 'all' || card.getAttribute('data-category') === filter) {
                        card.classList.remove('hidden');
                    } else {
                        card.classList.add('hidden');
                    }
                });
            });
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
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'967da1dad1a29e77',t:'MTc1Mzk3MDUzNC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>

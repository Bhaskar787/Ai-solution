<nav class="bg-white/95 backdrop-blur-sm shadow-sm fixed w-full top-0 z-50 border-b border-gray-100">
    <div class="max-w-6xl mx-auto px-6">
        <div class="flex justify-between items-center h-20">
            <div class="flex items-center space-x-3">
                
                    <img src="ai.svg" alt="AI Solutions Logo" class="w-9 h-9 object-contain" />
               
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">AI-Solutions</h1>
                    <p class="text-xs text-gray-500">Sunderland, UK</p>
                </div>
            </div>
            
            <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
            <div class="hidden lg:flex items-center space-x-8">
                <a href="index.php" class="<?php echo ($currentPage == 'index.php') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600'; ?> transition-colors">Home</a>
                <a href="solutions.php" class="<?php echo ($currentPage == 'solutions.php') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600'; ?> transition-colors">Solutions</a>
                <a href="portfolio.php" class="<?php echo ($currentPage == 'portfolio.php') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600'; ?> transition-colors">Portfolio</a>
                <a href="feedback.php" class="<?php echo ($currentPage == 'feedback.php') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600'; ?> transition-colors">Feedback</a>
                <a href="events.php" class="<?php echo ($currentPage == 'events.php') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600'; ?> transition-colors">Events</a>
                <a href="articles.php" class="<?php echo ($currentPage == 'articles.php') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600'; ?> transition-colors">Articles</a>
                <a href="contact.php" class="<?php echo ($currentPage == 'contact.php') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-xl font-medium hover:shadow-lg transition-all' : 'text-gray-700 hover:text-blue-600'; ?>">
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
                    <a href="index.php" class="block <?php echo ($currentPage == 'index.php') ? 'text-blue-600 font-semibold' : 'text-gray-700'; ?>">Home</a>
                    <a href="solutions.php" class="block <?php echo ($currentPage == 'solutions.php') ? 'text-blue-600 font-semibold' : 'text-gray-700'; ?>">Solutions</a>
                    <a href="portfolio.php" class="block <?php echo ($currentPage == 'portfolio.php') ? 'text-blue-600 font-semibold' : 'text-gray-700'; ?>">Portfolio</a>
                    <a href="feedback.php" class="block <?php echo ($currentPage == 'feedback.php') ? 'text-blue-600 font-semibold' : 'text-gray-700'; ?>">Feedback</a>
                    <a href="events.php" class="block <?php echo ($currentPage == 'events.php') ? 'text-blue-600 font-semibold' : 'text-gray-700'; ?>">Events</a>
                    <a href="articles.php" class="block <?php echo ($currentPage == 'articles.php') ? 'text-blue-600 font-semibold' : 'text-gray-700'; ?>">Articles</a>
                    <a href="contact.php" class="<?php echo ($currentPage == 'contact.php') ? 'w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 rounded-xl font-medium text-center block' : 'w-full text-gray-700 block'; ?>">
                        Contact Us
                    </a>
                </div>
            </div>
</nav>
<?php include 'includes/chatbot-styles.php'; ?>
  <!-- Chatbot HTML -->
  <?php include 'includes/chatbot.php'; ?>

<!-- Chatbot Scripts -->
<?php include 'includes/chatbot-scripts.php'; ?>
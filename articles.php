<?php
// Include database connection
require_once 'admin/db_connection.php';

// Get database connection
$link = get_db_connection();

// Pagination parameters
$per_page = 6;
$total_articles = 0;
$total_pages = 1;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$current_page = max(1, $current_page);
$offset = ($current_page - 1) * $per_page;

// Category filter
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';

// Count total articles with optional category filter
$count_query = "SELECT COUNT(*) as count FROM articles WHERE status = 'published'";
$params = [];
$types = "";

if (!empty($category_filter)) {
    $count_query .= " AND category = ?";
    $params[] = $category_filter;
    $types .= "s";
}

$stmt = mysqli_prepare($link, $count_query);
if ($stmt && !empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$count_result = mysqli_stmt_get_result($stmt);
$total_articles = mysqli_fetch_assoc($count_result)['count'];
$total_pages = max(1, ceil($total_articles / $per_page));
mysqli_stmt_close($stmt);

// Fetch articles with pagination and category filter
$query = "SELECT * FROM articles WHERE status = 'published'";
if (!empty($category_filter)) {
    $query .= " AND category = ?";
}
$query .= " ORDER BY date DESC LIMIT ? OFFSET ?";

$stmt = mysqli_prepare($link, $query);
if (!empty($category_filter)) {
    mysqli_stmt_bind_param($stmt, "sii", $category_filter, $per_page, $offset);
} else {
    mysqli_stmt_bind_param($stmt, "ii", $per_page, $offset);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$articles = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);
mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles | AI-Solutions - Thought Leadership & Innovation Insights</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/articles.css">
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
                    <a href="portfolio.php" class="text-gray-700 hover:text-blue-600 transition-colors">Portfolio</a>
                    <a href="feedback.php" class="text-gray-700 hover:text-blue-600 transition-colors">Feedback</a>
                    <a href="events.php" class="text-gray-700 hover:text-blue-600 transition-colors">Events</a>
                    <a href="#articles" class="text-blue-600 font-medium">Articles</a>
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
                <a href="portfolio.php" class="block text-gray-700">Portfolio</a>
                <a href="feedback.php" class="block text-gray-700">Feedback</a>
                <a href="events.php" class="block text-gray-700">Events</a>
                <a href="#articles" class="block text-blue-600 font-medium">Articles</a>
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
                    📚 Thought Leadership
                </div>
                <h1 class="text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
                    Insights & <span class="text-gradient">Innovation</span>
                </h1>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto leading-relaxed mb-8">
                    Explore our latest research, industry insights, and thought leadership on AI transformation, 
                    employee experience innovation, and the future of digital workplace solutions.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#featured" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-xl font-semibold hover:shadow-xl transition-all">
                        Read Featured Article
                    </a>
                    <a href="#recent" class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-blue-600 transition-all">
                        Browse All Articles
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Article -->
    <section id="featured" class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-6">
            <?php if (!empty($articles)): ?>
            <?php $featured = $articles[0]; ?>
            <div class="featured-article rounded-3xl p-12 text-white relative">
                <div class="relative z-10">
                    <div class="flex flex-wrap items-center gap-4 mb-6">
                        <span class="category-tag tag-<?php echo htmlspecialchars($featured['category']); ?>">Featured Article</span>
                        <span class="reading-time px-3 py-1 rounded-full text-sm font-medium"><?php echo htmlspecialchars($featured['reading_time']); ?> min read</span>
                    </div>

                    <h2 class="text-4xl lg:text-5xl font-bold mb-6 leading-tight">
                        <?php echo htmlspecialchars($featured['title']); ?>
                    </h2>

                    <p class="text-xl text-blue-100 mb-8 leading-relaxed max-w-4xl">
                        <?php echo htmlspecialchars(substr($featured['content'], 0, 300)); ?><?php if (strlen($featured['content']) > 300): ?>...<?php endif; ?>
                    </p>

                    <div class="flex items-center justify-between flex-wrap gap-6">
                        <div class="flex items-center space-x-4">
                            <div class="author-avatar w-12 h-12 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold"><?php echo strtoupper(substr($featured['author'], 0, 2)); ?></span>
                            </div>
                            <div>
                                <p class="font-semibold"><?php echo htmlspecialchars($featured['author']); ?></p>
                                <p class="text-blue-200 text-sm">Author</p>
                            </div>
                            <div class="text-blue-200 text-sm">
                                <span>•</span>
                                <span class="ml-2"><?php echo date('F j, Y', strtotime($featured['date'])); ?></span>
                            </div>
                        </div>

                        <div class="flex space-x-3">
                            <a href="article.php?id=<?php echo $featured['id']; ?>" class="share-btn bg-white/20 hover:bg-white/30 px-6 py-3 rounded-xl font-semibold transition-all">
                                Read Full Article
                            </a>
                            <button class="share-btn bg-white/10 hover:bg-white/20 p-3 rounded-xl transition-all">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92s2.92-1.31 2.92-2.92-1.31-2.92-2.92-2.92z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Category Filters -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex flex-wrap justify-center gap-4 mb-8">
                <a href="?<?php echo !empty($category_filter) && $category_filter != 'all' ? '' : 'category=all'; ?>" class="category-btn <?php echo empty($category_filter) || $category_filter == 'all' ? 'active' : ''; ?> px-6 py-3 rounded-xl font-medium bg-white text-gray-700 shadow-sm">
                    All Articles
                </a>
                <a href="?category=ai<?php echo $current_page > 1 ? '&page=' . $current_page : ''; ?>" class="category-btn <?php echo $category_filter == 'ai' ? 'active' : ''; ?> px-6 py-3 rounded-xl font-medium bg-white text-gray-700 shadow-sm">
                    Artificial Intelligence
                </a>
                <a href="?category=experience<?php echo $current_page > 1 ? '&page=' . $current_page : ''; ?>" class="category-btn <?php echo $category_filter == 'experience' ? 'active' : ''; ?> px-6 py-3 rounded-xl font-medium bg-white text-gray-700 shadow-sm">
                    Employee Experience
                </a>
                <a href="?category=innovation<?php echo $current_page > 1 ? '&page=' . $current_page : ''; ?>" class="category-btn <?php echo $category_filter == 'innovation' ? 'active' : ''; ?> px-6 py-3 rounded-xl font-medium bg-white text-gray-700 shadow-sm">
                    Innovation
                </a>
                <a href="?category=research<?php echo $current_page > 1 ? '&page=' . $current_page : ''; ?>" class="category-btn <?php echo $category_filter == 'research' ? 'active' : ''; ?> px-6 py-3 rounded-xl font-medium bg-white text-gray-700 shadow-sm">
                    Research
                </a>
                <a href="?category=technology<?php echo $current_page > 1 ? '&page=' . $current_page : ''; ?>" class="category-btn <?php echo $category_filter == 'technology' ? 'active' : ''; ?> px-6 py-3 rounded-xl font-medium bg-white text-gray-700 shadow-sm">
                    Technology
                </a>
            </div>
        </div>
    </section>

    <!-- Recent Articles -->
    <section id="recent" class="pb-20 bg-gray-50">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Recent Articles</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Stay updated with our latest insights on AI innovation, workplace transformation, and emerging technologies.
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8" id="articles-grid">
                <?php if (!empty($articles)): ?>
                    <?php foreach ($articles as $article): ?>
                        <article class="article-card card-hover bg-white rounded-2xl shadow-lg overflow-hidden" data-category="<?php echo htmlspecialchars($article['category']); ?>">
                            <div class="h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center relative">
                                <div class="text-center text-white">
                                    <svg class="w-16 h-16 mx-auto mb-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                    </svg>
                                    <p class="text-sm"><?php echo htmlspecialchars(ucfirst($article['category'])); ?> Article</p>
                                </div>
                                <div class="absolute top-4 left-4">
                                    <span class="category-tag tag-<?php echo htmlspecialchars($article['category']); ?>"><?php echo htmlspecialchars(ucfirst($article['category'])); ?></span>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                                    <div class="flex items-center space-x-2">
                                        <div class="author-avatar w-8 h-8 rounded-full flex items-center justify-center">
                                            <span class="text-white text-xs font-bold"><?php echo strtoupper(substr($article['author'], 0, 2)); ?></span>
                                        </div>
                                        <span><?php echo htmlspecialchars($article['author']); ?></span>
                                    </div>
                                    <span><?php echo date('M j, Y', strtotime($article['date'])); ?></span>
                                </div>

                                <h3 class="text-xl font-bold text-gray-900 mb-3 leading-tight">
                                    <?php echo htmlspecialchars($article['title']); ?>
                                </h3>

                                <p class="text-gray-600 mb-4 leading-relaxed">
                                    <?php echo htmlspecialchars(substr($article['content'], 0, 150)); ?><?php if (strlen($article['content']) > 150): ?>...<?php endif; ?>
                                </p>

                                <div class="flex items-center justify-between">
                                    <span class="reading-time px-3 py-1 rounded-full text-sm font-medium"><?php echo htmlspecialchars($article['reading_time']); ?> min read</span>
                                    <a href="article.php?id=<?php echo $article['id']; ?>" class="text-blue-600 font-semibold hover:text-blue-700 transition-colors">
                                        Read More →
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 text-lg">No articles found.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Pagination -->
    <section class="pb-20 bg-gray-50">
        <div class="max-w-6xl mx-auto px-6">
            <?php if ($total_pages > 1): ?>
            <div class="flex justify-center items-center space-x-2">
                <?php if ($current_page > 1): ?>
                    <a href="?page=<?php echo $current_page - 1; ?><?php echo !empty($category_filter) ? '&category=' . urlencode($category_filter) : ''; ?>" class="pagination-btn px-4 py-2 rounded-lg bg-white text-gray-600 font-medium hover:bg-blue-50 hover:text-blue-600 transition-colors">
                        ← Previous
                    </a>
                <?php else: ?>
                    <span class="pagination-btn px-4 py-2 rounded-lg bg-gray-100 text-gray-400 font-medium cursor-not-allowed">
                        ← Previous
                    </span>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <?php if ($i == $current_page): ?>
                        <span class="pagination-btn active px-4 py-2 rounded-lg bg-blue-600 text-white font-medium">
                            <?php echo $i; ?>
                        </span>
                    <?php else: ?>
                        <a href="?page=<?php echo $i; ?><?php echo !empty($category_filter) ? '&category=' . urlencode($category_filter) : ''; ?>" class="pagination-btn px-4 py-2 rounded-lg bg-white text-gray-600 font-medium hover:bg-blue-50 hover:text-blue-600 transition-colors">
                            <?php echo $i; ?>
                        </a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($current_page < $total_pages): ?>
                    <a href="?page=<?php echo $current_page + 1; ?><?php echo !empty($category_filter) ? '&category=' . urlencode($category_filter) : ''; ?>" class="pagination-btn px-4 py-2 rounded-lg bg-white text-gray-600 font-medium hover:bg-blue-50 hover:text-blue-600 transition-colors">
                        Next →
                    </a>
                <?php else: ?>
                    <span class="pagination-btn px-4 py-2 rounded-lg bg-gray-100 text-gray-400 font-medium cursor-not-allowed">
                        Next →
                    </span>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Newsletter Signup -->
    <section class="py-20 bg-gradient-to-r from-blue-600 to-purple-700">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <div class="fade-in">
                <h2 class="text-4xl font-bold text-white mb-6">
                    Stay Updated with Our Latest Insights
                </h2>
                <p class="text-xl text-blue-100 mb-8 leading-relaxed">
                    Get our newest articles, research findings, and industry insights delivered directly to your inbox. 
                    Join 2,500+ professionals staying ahead of the AI revolution.
                </p>
                <div class="max-w-md mx-auto">
                    <div class="flex gap-4">
                        <input 
                            type="email" 
                            placeholder="Enter your email address" 
                            class="flex-1 px-6 py-4 rounded-xl border-0 focus:ring-4 focus:ring-blue-300 focus:outline-none"
                        >
                        <button class="bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold hover:shadow-xl transition-all whitespace-nowrap">
                            Subscribe
                        </button>
                    </div>
                    <p class="text-blue-200 text-sm mt-4">
                        No spam, unsubscribe at any time. We respect your privacy.
                    </p>
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
                    <h4 class="text-lg font-semibold mb-4">Articles</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#featured" class="hover:text-white transition-colors">Featured Article</a></li>
                        <li><a href="#recent" class="hover:text-white transition-colors">Recent Posts</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">AI Insights</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Research Papers</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Company</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="index.php" class="hover:text-white transition-colors">Home</a></li>
                        <li><a href="solutions.php" class="hover:text-white transition-colors">Solutions</a></li>
                        <li><a href="portfolio.php" class="hover:text-white transition-colors">Portfolio</a></li>
                        <li><a href="contact.php" class="hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-8 text-center text-gray-400">
                <p>&copy; 2024 AI-Solutions. All rights reserved. Innovating the future of digital employee experience.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });



        // Newsletter subscription
        const subscribeBtn = document.querySelector('button[type="button"]');
        const emailInput = document.querySelector('input[type="email"]');
        
        if (subscribeBtn && emailInput) {
            subscribeBtn.addEventListener('click', () => {
                const email = emailInput.value.trim();
                if (email && email.includes('@')) {
                    // Simulate subscription success
                    subscribeBtn.textContent = 'Subscribed!';
                    subscribeBtn.classList.add('bg-green-500');
                    emailInput.value = '';
                    
                    setTimeout(() => {
                        subscribeBtn.textContent = 'Subscribe';
                        subscribeBtn.classList.remove('bg-green-500');
                    }, 3000);
                } else {
                    // Simple validation feedback
                    emailInput.classList.add('ring-2', 'ring-red-300');
                    setTimeout(() => {
                        emailInput.classList.remove('ring-2', 'ring-red-300');
                    }, 2000);
                }
            });
            
            // Allow Enter key to subscribe
            emailInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    subscribeBtn.click();
                }
            });
        }

        // Share functionality for featured article
        const shareBtn = document.querySelector('.share-btn');
        if (shareBtn && shareBtn.querySelector('svg')) {
            shareBtn.addEventListener('click', () => {
                if (navigator.share) {
                    navigator.share({
                        title: 'The Future of AI in Employee Experience',
                        text: 'Discover how AI is transforming workplace productivity',
                        url: window.location.href
                    });
                } else {
                    // Fallback: copy to clipboard
                    navigator.clipboard.writeText(window.location.href).then(() => {
                        const originalContent = shareBtn.innerHTML;
                        shareBtn.innerHTML = '<span class="text-sm">Link Copied!</span>';
                        setTimeout(() => {
                            shareBtn.innerHTML = originalContent;
                        }, 2000);
                    });
                }
            });
        }

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

        // Read More button functionality
        const readMoreBtns = document.querySelectorAll('button:contains("Read More")');
        document.querySelectorAll('article button').forEach(btn => {
            if (btn.textContent.includes('Read More')) {
                btn.addEventListener('click', () => {
                    // Simulate article opening
                    btn.textContent = 'Opening...';
                    setTimeout(() => {
                        btn.textContent = 'Read More →';
                    }, 1000);
                });
            }
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'967db655278c4f3c',t:'MTc1Mzk3MTM3Mi4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>

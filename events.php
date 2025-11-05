<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events | AI-Solutions - Industry Events & Conferences</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/events.css">
</head>
<body class="bg-gray-50">
    <?php include 'navbar.php'; ?>

    <!-- Hero Section -->
    <section class="hero-bg pt-32 pb-20">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <div class="fade-in">
                <div class="inline-flex items-center bg-blue-500/20 px-4 py-2 rounded-full text-blue-300 text-sm font-medium mb-6">
                    📅 Industry Events
                </div>
                <h1 class="text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
                    Connect with <span class="text-gradient">AI Innovation</span>
                </h1>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto leading-relaxed mb-8">
                    Join us at leading industry conferences, workshops, and networking events where we share insights on AI transformation and digital innovation.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#upcoming" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-xl font-semibold hover:shadow-xl transition-all">
                        View Upcoming Events
                    </a>
                    <a href="#gallery" class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-blue-600 transition-all">
                        Event Gallery
                    </a>
                    <?php
                    session_start();
                    if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
                        echo '<a href="admin/events/manage_events.php" class="bg-green-500 hover:bg-green-600 text-white px-8 py-4 rounded-xl font-semibold hover:shadow-xl transition-all">
                            <i class="fas fa-cog mr-2"></i>Manage Events
                        </a>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Event Filters -->
    <section class="py-12 bg-white">
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex justify-center space-x-4 mb-8">
                <button class="filter-btn active px-6 py-3 rounded-xl font-medium bg-gray-100 text-gray-700" data-filter="all">
                    All Events
                </button>
                <button class="filter-btn px-6 py-3 rounded-xl font-medium bg-gray-100 text-gray-700" data-filter="upcoming">
                    Upcoming
                </button>
                <button class="filter-btn px-6 py-3 rounded-xl font-medium bg-gray-100 text-gray-700" data-filter="past">
                    Past Events
                </button>
            </div>
        </div>
    </section>

    <!-- Events Grid -->
    <section id="events" class="pb-20 bg-white">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8" id="events-grid">
                <?php
include 'admin/db_connection.php';

                // Get mysqli connection
                try {
                    $conn = get_db_connection();
                } catch (Exception $e) {
                    die("Database connection failed: " . $e->getMessage());
                }

                // Fetch events from database
                $sql = "SELECT * FROM events ORDER BY date ASC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $event_date = strtotime($row['date']);
                        $current_date = time();
                        $is_past = $event_date < $current_date;
                        $category = $is_past ? 'past' : 'upcoming';
                        $status_text = $is_past ? 'Past Event' : 'Upcoming';
                        $status_class = $is_past ? 'status-past' : 'status-upcoming';

                        // Calculate days until event
                        $days_until = ceil(($event_date - $current_date) / (60 * 60 * 24));
                        $countdown_text = $is_past ? 'Event completed' : ($days_until == 1 ? '1 day' : $days_until . ' days');

                        // Get gradient colors based on category
                        $gradients = [
                            'AI' => 'from-blue-500 to-purple-600',
                            'Tech' => 'from-green-500 to-teal-600',
                            'Healthcare' => 'from-purple-500 to-pink-600',
                            'Education' => 'from-indigo-500 to-blue-600',
                            'Manufacturing' => 'from-red-500 to-pink-600',
                            'Conference' => 'from-yellow-500 to-orange-600'
                        ];

                        $gradient = 'from-blue-500 to-purple-600'; // default
                        foreach($gradients as $key => $grad) {
                            if (stripos($row['title'], $key) !== false || stripos($row['description'], $key) !== false) {
                                $gradient = $grad;
                                break;
                            }
                        }

                        // Get button gradient based on gradient
                        $button_gradients = [
                            'from-blue-500 to-purple-600' => 'from-blue-600 to-purple-600',
                            'from-green-500 to-teal-600' => 'from-green-600 to-teal-600',
                            'from-purple-500 to-pink-600' => 'from-purple-600 to-pink-600',
                            'from-indigo-500 to-blue-600' => 'from-indigo-600 to-blue-600',
                            'from-red-500 to-pink-600' => 'from-red-600 to-pink-600',
                            'from-yellow-500 to-orange-600' => 'from-yellow-600 to-orange-600'
                        ];
                        $button_gradient = $button_gradients[$gradient] ?? 'from-blue-600 to-purple-600';

                        echo '<div class="event-card card-hover bg-white rounded-2xl shadow-lg overflow-hidden" data-category="' . $category . '">
                            <div class="relative">
                                <div class="h-48 bg-gradient-to-br ' . $gradient . ' flex items-center justify-center">
                                    <div class="text-center text-white">
                                        <svg class="w-16 h-16 mx-auto mb-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                        </svg>
                                        <p class="text-sm">' . htmlspecialchars($row['title']) . '</p>
                                    </div>
                                </div>
                                <div class="event-status ' . $status_class . '">' . $status_text . '</div>
                            </div>
                            <div class="p-6">
                                <div class="flex items-center text-sm text-gray-500 mb-2">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                                    </svg>
                                    ' . date('M j-Y', $event_date) . '
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">' . htmlspecialchars($row['title']) . '</h3>
                                <div class="flex items-center text-sm text-gray-500 mb-3">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                    </svg>
                                    ' . htmlspecialchars($row['location']) . '
                                </div>
                                <p class="text-gray-600 mb-4">
                                    ' . htmlspecialchars($row['description']) . '
                                </p>';

                        if (!$is_past) {
                            echo '<div class="countdown">
                                    <div class="text-sm">Event starts in:</div>
                                    <div class="text-lg font-bold">' . $countdown_text . '</div>
                                </div>';
                        }

                        echo '<div class="mt-4">';
                        if ($is_past) {
                            echo '<a href="#highlights" class="w-full bg-gray-600 text-white py-3 rounded-xl font-medium text-center block hover:bg-gray-700 transition-all">
                                    View Highlights
                                </a>';
                        } else {
                            echo '<a href="contact.php" class="w-full bg-gradient-to-r ' . $button_gradient . ' text-white py-3 rounded-xl font-medium text-center block hover:shadow-lg transition-all">
                                    Register Interest
                                </a>';
                        }
                        echo '</div>
                            </div>
                        </div>';
                    }
                } else {
                    echo '<div class="col-span-full text-center py-12">
                            <div class="text-gray-500 text-lg">No events found. Check back soon for upcoming events!</div>
                        </div>';
                }

                ?>
            </div>
        </div>
    </section>

    <!-- Photo Gallery -->
    <section id="gallery" class="py-20 bg-gray-50">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Event Photo Gallery</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Capturing moments from our participation in industry events, conferences, and networking sessions.
                </p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php
                // Fetch events with images for gallery
                $gallery_sql = "SELECT title, image_path FROM events WHERE image_path IS NOT NULL AND image_path != '' ORDER BY date DESC LIMIT 8";
                $gallery_result = $conn->query($gallery_sql);

                if ($gallery_result->num_rows > 0) {
                    while($gallery_row = $gallery_result->fetch_assoc()) {
                        echo '<div class="gallery-item rounded-xl overflow-hidden aspect-square" data-image="' . htmlspecialchars($gallery_row['title']) . '">
                            <img src="' . htmlspecialchars($gallery_row['image_path']) . '" alt="' . htmlspecialchars($gallery_row['title']) . '" class="w-full h-full object-cover">
                            <div class="overlay-content">
                                <svg class="w-8 h-8 mb-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                                </svg>
                                <p class="text-sm font-medium">View Photo</p>
                            </div>
                        </div>';
                    }
                } else {
                    // Fallback to placeholder if no images
                    echo '<div class="col-span-full text-center py-12">
                            <div class="text-gray-500 text-lg">No event photos available yet. Check back soon!</div>
                        </div>';
                }
                $conn->close();
                ?>
            </div>
        </div>
    </section>

    <!-- Event Highlights -->
    <section id="highlights" class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Event Highlights</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Key moments and achievements from our recent event participations.
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-12">
                <!-- Video Highlight -->
                <div class="fade-in">
                    <div class="video-container mb-6">
                        <iframe width="100%" height="315" src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="AI Expo 2023 Highlights" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen class="rounded-lg"></iframe>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Best Innovation Award Win</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Our NHS Digital Assistant was recognized as the most innovative AI solution at the AI Expo 2023.
                        The award ceremony highlighted our commitment to transforming healthcare through intelligent automation.
                    </p>
                </div>
                
                <!-- Blog-style Writeup -->
                <div class="fade-in">
                    <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl p-8 text-white mb-6">
                        <h3 class="text-2xl font-bold mb-4">EdTech Summit Success</h3>
                        <div class="flex items-center text-blue-100 text-sm mb-4">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                            </svg>
                            September 2023
                        </div>
                        <p class="leading-relaxed">
                            "Our presentation on 'AI-Powered Learning Experiences' drew a packed audience of 300+ education professionals. 
                            The live demonstration of our Smart Learning Platform resulted in immediate partnership discussions with 
                            University of Sunderland, Newcastle University, and Durham University."
                        </p>
                    </div>
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div class="bg-gray-50 p-4 rounded-xl">
                            <div class="text-2xl font-bold text-blue-600">300+</div>
                            <div class="text-sm text-gray-600">Attendees</div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-xl">
                            <div class="text-2xl font-bold text-green-600">3</div>
                            <div class="text-sm text-gray-600">Partnerships</div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-xl">
                            <div class="text-2xl font-bold text-purple-600">25+</div>
                            <div class="text-sm text-gray-600">Leads Generated</div>
                        </div>
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
                    Meet Us at Our Next Event
                </h2>
                <p class="text-xl text-blue-100 mb-8 leading-relaxed">
                    Interested in seeing our AI solutions in action? Connect with us at upcoming industry events or schedule a private demonstration.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="contact.php" class="bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold hover:shadow-xl transition-all">
                        Schedule Meeting
                    </a>
                    <a href="#upcoming" class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-blue-600 transition-all">
                        View Upcoming Events
                    </a>
                </div>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <!-- Lightbox -->
    <div class="lightbox" id="lightbox">
        <div class="lightbox-content">
            <span class="lightbox-close" id="lightbox-close">&times;</span>
            <div class="bg-white rounded-2xl p-8 max-w-4xl">
                <div class="text-center">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4" id="lightbox-title">Event Photo</h3>
                    <img id="lightbox-image" src="" alt="Event Photo" class="max-w-full max-h-96 mx-auto rounded-lg shadow-lg">
                </div>
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

        // Event filtering
        const filterBtns = document.querySelectorAll('.filter-btn');
        const eventCards = document.querySelectorAll('.event-card');
        
        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Remove active class from all buttons
                filterBtns.forEach(b => b.classList.remove('active'));
                // Add active class to clicked button
                btn.classList.add('active');
                
                const filter = btn.getAttribute('data-filter');
                
                eventCards.forEach(card => {
                    const category = card.getAttribute('data-category');
                    
                    if (filter === 'all' || category === filter) {
                        card.classList.remove('hidden');
                        setTimeout(() => {
                            card.style.opacity = '1';
                            card.style.transform = 'scale(1)';
                        }, 100);
                    } else {
                        card.style.opacity = '0';
                        card.style.transform = 'scale(0.8)';
                        setTimeout(() => {
                            card.classList.add('hidden');
                        }, 300);
                    }
                });
            });
        });

        // Gallery lightbox
        const galleryItems = document.querySelectorAll('.gallery-item');
        const lightbox = document.getElementById('lightbox');
        const lightboxClose = document.getElementById('lightbox-close');
        const lightboxTitle = document.getElementById('lightbox-title');
        const lightboxImage = document.getElementById('lightbox-image');
        
        galleryItems.forEach(item => {
            item.addEventListener('click', () => {
                const imageTitle = item.getAttribute('data-image');
                const imgSrc = item.querySelector('img').src;
                lightboxTitle.textContent = imageTitle;
                lightboxImage.src = imgSrc;
                lightbox.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        });
        
        lightboxClose.addEventListener('click', closeLightbox);
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) {
                closeLightbox();
            }
        });
        
        function closeLightbox() {
            lightbox.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
        
        // Close lightbox with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && lightbox.classList.contains('active')) {
                closeLightbox();
            }
        });

        // Countdown timers (simplified for demo)
        function updateCountdowns() {
            const countdowns = [
                { id: 'countdown-1', days: 45 },
                { id: 'countdown-2', days: 69 },
                { id: 'countdown-3', days: 113 }
            ];
            
            countdowns.forEach(countdown => {
                const element = document.getElementById(countdown.id);
                if (element) {
                    const daysElement = element.querySelector('.text-lg');
                    if (daysElement) {
                        daysElement.textContent = `${countdown.days} days`;
                    }
                }
            });
        }
        
        updateCountdowns();

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
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'967db1b9343d9e77',t:'MTc1Mzk3MTE4NC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>

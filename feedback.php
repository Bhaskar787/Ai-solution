<?php
// Include database connection
require_once 'admin/db_connection.php';

// Get database connection
$link = get_db_connection();

// Fetch published testimonials
$query = "SELECT full_name, company, job_title, project, rating, testimonial FROM feedback_submissions WHERE status = 'published' ORDER BY submission_date DESC";
$result = mysqli_query($link, $query);
$testimonials = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Filter out testimonials with empty testimonial text
$valid_testimonials = [];
foreach ($testimonials as $testimonial) {
    if (!empty(trim($testimonial['testimonial']))) {
        $valid_testimonials[] = $testimonial;
    }
}

// Close connection
mysqli_close($link);

// Project mapping for display
$project_mapping = [
    'ai-assistant' => 'Healthcare • NHS Digital Assistant',
    'learning-platform' => 'Education • Smart Learning Platform',
    'production-optimizer' => 'Manufacturing • Production Optimizer',
    'banking-assistant' => 'Finance • Banking Assistant',
    'retail-analytics' => 'Retail • Retail Analytics',
    'hr-automation' => 'Human Resources • HR Automation',
    'other' => 'Other • Custom Solution'
];

// Function to get initials
function get_initials($name) {
    $parts = explode(' ', $name);
    $initials = '';
    foreach ($parts as $part) {
        $initials .= strtoupper(substr($part, 0, 1));
    }
    return $initials ?: 'U'; // Default to 'U' if no name
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Feedback | AI-Solutions - What Our Clients Say</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/feedback.css">
</head>
<body class="bg-gray-50">
    <?php include 'navbar.php'; ?>

    <!-- Hero Section -->
    <section class="hero-bg pt-32 pb-20">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <div class="fade-in">
                <div class="inline-flex items-center bg-yellow-500/20 px-4 py-2 rounded-full text-yellow-300 text-sm font-medium mb-6">
                    ⭐ Client Testimonials
                </div>
                <h1 class="text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
                    What Our <span class="text-gradient">Clients Say</span>
                </h1>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto leading-relaxed mb-8">
                    Don't just take our word for it. Hear from the businesses that have transformed their operations with our AI solutions.
                </p>
                
                <div class="flex justify-center items-center space-x-4 mb-8">
                    <div class="flex items-center space-x-1">
                        <svg class="w-8 h-8 star" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <svg class="w-8 h-8 star" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <svg class="w-8 h-8 star" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <svg class="w-8 h-8 star" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <svg class="w-8 h-8 star" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <div class="text-white">
                        <span class="text-3xl font-bold">4.9</span>
                        <span class="text-gray-300">/5 from 500+ reviews</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonial Carousel -->
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-6">
            <div class="carousel-container">
                <div class="carousel-track" id="carousel-track">
                    <?php if (empty($testimonials)): ?>
                        <div class="carousel-slide active">
                            <div class="text-center">
                                <div class="w-24 h-24 bg-gradient-to-br from-gray-500 to-gray-600 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <span class="text-white text-2xl font-bold">?</span>
                                </div>
                                <blockquote class="text-2xl text-gray-700 italic mb-6 leading-relaxed">
                                    "No testimonials available yet. Be the first to share your experience!"
                                </blockquote>
                                <div>
                                    <div class="font-bold text-gray-900 text-lg">Coming Soon</div>
                                    <div class="text-gray-600">Client Testimonials</div>
                                    <div class="text-sm text-gray-500 mt-1">AI-Solutions</div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php $slideIndex = 0; ?>
                        <?php foreach ($valid_testimonials as $testimonial): ?>
                            <div class="carousel-slide <?php echo $slideIndex === 0 ? 'active' : ''; ?>">
                                <div class="text-center">
                                    <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6">
                                        <span class="text-white text-2xl font-bold"><?php echo htmlspecialchars(get_initials($testimonial['full_name'])); ?></span>
                                    </div>
                                    <div class="flex justify-center mb-4">
                                        <div class="flex space-x-1">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <svg class="w-6 h-6 star <?php echo $i <= $testimonial['rating'] ? 'text-yellow-400' : 'text-gray-300'; ?>" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                </svg>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <blockquote class="text-2xl text-gray-700 italic mb-6 leading-relaxed">
                                        "<?php echo htmlspecialchars($testimonial['testimonial']); ?>"
                                    </blockquote>
                                    <div>
                                        <div class="font-bold text-gray-900 text-lg"><?php echo htmlspecialchars($testimonial['full_name']); ?></div>
                                        <div class="text-gray-600"><?php echo htmlspecialchars($testimonial['job_title'] ?: 'Client'); ?></div>
                                        <div class="text-sm text-gray-500 mt-1"><?php echo htmlspecialchars($project_mapping[$testimonial['project']] ?? 'AI-Solutions Project'); ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <!-- Carousel Navigation -->
                <div class="flex justify-center mt-8 space-x-2">
                    <?php if (empty($valid_testimonials)): ?>
                        <button class="carousel-dot w-3 h-3 rounded-full bg-blue-600 transition-all" data-slide="0"></button>
                    <?php else: ?>
                        <?php foreach ($valid_testimonials as $index => $testimonial): ?>
                            <button class="carousel-dot w-3 h-3 rounded-full <?php echo $index === 0 ? 'bg-blue-600' : 'bg-gray-300'; ?> transition-all" data-slide="<?php echo $index; ?>"></button>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <!-- Carousel Arrows -->
                <button class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/90 hover:bg-white rounded-full p-3 shadow-lg transition-all" id="prev-btn">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/90 hover:bg-white rounded-full p-3 shadow-lg transition-all" id="next-btn">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
    </section>

    <!-- Rating Statistics -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Client Satisfaction Breakdown</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Our commitment to excellence is reflected in consistently high ratings across all service areas.
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-12">
                <div class="fade-in">
                    <h3 class="text-2xl font-bold text-gray-900 mb-8">Rating Distribution</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <span class="w-12 text-sm font-medium text-gray-700">5 star</span>
                            <div class="flex-1 mx-4">
                                <div class="rating-bar">
                                    <div class="rating-fill" style="width: 78%"></div>
                                </div>
                            </div>
                            <span class="w-12 text-sm text-gray-600">78%</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-12 text-sm font-medium text-gray-700">4 star</span>
                            <div class="flex-1 mx-4">
                                <div class="rating-bar">
                                    <div class="rating-fill" style="width: 18%"></div>
                                </div>
                            </div>
                            <span class="w-12 text-sm text-gray-600">18%</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-12 text-sm font-medium text-gray-700">3 star</span>
                            <div class="flex-1 mx-4">
                                <div class="rating-bar">
                                    <div class="rating-fill" style="width: 3%"></div>
                                </div>
                            </div>
                            <span class="w-12 text-sm text-gray-600">3%</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-12 text-sm font-medium text-gray-700">2 star</span>
                            <div class="flex-1 mx-4">
                                <div class="rating-bar">
                                    <div class="rating-fill" style="width: 1%"></div>
                                </div>
                            </div>
                            <span class="w-12 text-sm text-gray-600">1%</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-12 text-sm font-medium text-gray-700">1 star</span>
                            <div class="flex-1 mx-4">
                                <div class="rating-bar">
                                    <div class="rating-fill" style="width: 0%"></div>
                                </div>
                            </div>
                            <span class="w-12 text-sm text-gray-600">0%</span>
                        </div>
                    </div>
                </div>
                
                <div class="fade-in">
                    <h3 class="text-2xl font-bold text-gray-900 mb-8">Key Metrics</h3>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="text-center p-6 bg-white rounded-2xl shadow-sm">
                            <div class="text-3xl font-bold text-blue-600 mb-2">98%</div>
                            <div class="text-gray-600">Client Retention</div>
                        </div>
                        <div class="text-center p-6 bg-white rounded-2xl shadow-sm">
                            <div class="text-3xl font-bold text-green-600 mb-2">4.9</div>
                            <div class="text-gray-600">Average Rating</div>
                        </div>
                        <div class="text-center p-6 bg-white rounded-2xl shadow-sm">
                            <div class="text-3xl font-bold text-purple-600 mb-2">500+</div>
                            <div class="text-gray-600">Total Reviews</div>
                        </div>
                        <div class="text-center p-6 bg-white rounded-2xl shadow-sm">
                            <div class="text-3xl font-bold text-orange-600 mb-2">95%</div>
                            <div class="text-gray-600">Recommend Us</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Testimonials -->
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Video Testimonials</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Hear directly from our clients about their experience working with AI-Solutions.
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-8">
                <div class="fade-in">
                    <div class="video-container mb-4">
                        <iframe
                            src="https://www.youtube.com/embed/dQw4w9WgXcQ"
                            title="NHS Digital Assistant Success Story"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                            class="w-full h-full rounded-xl">
                        </iframe>
                    </div>
                    <div class="text-center">
                        <h3 class="font-bold text-gray-900">Healthcare Transformation</h3>
                        <p class="text-gray-600 text-sm">See how our AI assistant revolutionized patient care</p>
                    </div>
                </div>

                <div class="fade-in">
                    <div class="video-container mb-4">
                        <iframe
                            src="https://www.youtube.com/embed/dQw4w9WgXcQ"
                            title="Manufacturing Excellence Testimonial"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                            class="w-full h-full rounded-xl">
                        </iframe>
                    </div>
                    <div class="text-center">
                        <h3 class="font-bold text-gray-900">Production Optimization</h3>
                        <p class="text-gray-600 text-sm">Discover how we reduced waste by 30% at Nissan UK</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Feedback Form -->
    <section id="feedback" class="py-20 bg-gray-50">
        <div class="max-w-4xl mx-auto px-6">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Share Your Experience</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    We'd love to hear about your experience working with AI-Solutions. Your feedback helps us continue to improve our services.
                </p>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg p-8 fade-in">
                <?php
                if (isset($_GET['success']) && $_GET['success'] == '1') {
                    echo '<div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Thank you for your feedback! Your testimonial has been submitted successfully and will be reviewed by our team.
                        </div>
                    </div>';
                } elseif (isset($_GET['error'])) {
                    $error_messages = [
                        'invalid_request' => 'Invalid request method.',
                        'missing_fields' => 'Please fill in all required fields and provide a rating.',
                        'invalid_email' => 'Please enter a valid email address.',
                        'invalid_file_type' => 'Invalid file type. Please upload JPG, PNG, GIF, PDF, DOC, or DOCX files only.',
                        'file_too_large' => 'File size too large. Maximum size is 5MB.',
                        'upload_failed' => 'File upload failed. Please try again.',
                        'db_error' => 'Database error. Please try again later.',
                        'submit_failed' => 'Submission failed. Please try again.'
                    ];
                    $error = $_GET['error'];
                    $message = isset($error_messages[$error]) ? $error_messages[$error] : 'An error occurred. Please try again.';
                    echo '<div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            ' . htmlspecialchars($message) . '
                        </div>
                    </div>';
                }
                ?>
                <form id="feedback-form" action="admin/feedback/feedback_handler.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" id="name" name="name" required 
                                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all">
                        </div>
                        <div>
                            <label for="company" class="block text-sm font-medium text-gray-700 mb-2">Company</label>
                            <input type="text" id="company" name="company" 
                                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all">
                        </div>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" id="email" name="email" required 
                                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all">
                        </div>
                        <div>
                            <label for="job-title" class="block text-sm font-medium text-gray-700 mb-2">Job Title</label>
                            <input type="text" id="job-title" name="job-title" 
                                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all">
                        </div>
                    </div>
                    
                    <div>
                        <label for="project" class="block text-sm font-medium text-gray-700 mb-2">Which project/service are you reviewing?</label>
                        <select id="project" name="project" 
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all">
                            <option value="">Select a project...</option>
                            <option value="ai-assistant">AI Virtual Assistant</option>
                            <option value="learning-platform">Smart Learning Platform</option>
                            <option value="production-optimizer">Production Optimizer</option>
                            <option value="banking-assistant">Banking Assistant</option>
                            <option value="retail-analytics">Retail Analytics</option>
                            <option value="hr-automation">HR Automation</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-4">Overall Rating *</label>
                        <div class="flex space-x-2" id="rating-stars">
                            <button type="button" class="star interactive text-3xl" data-rating="1">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </button>
                            <button type="button" class="star interactive text-3xl" data-rating="2">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </button>
                            <button type="button" class="star interactive text-3xl" data-rating="3">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </button>
                            <button type="button" class="star interactive text-3xl" data-rating="4">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </button>
                            <button type="button" class="star interactive text-3xl" data-rating="5">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </button>
                        </div>
                        <input type="hidden" id="rating" name="rating" required>
                    </div>
                    
                    <div>
                        <label for="testimonial" class="block text-sm font-medium text-gray-700 mb-2">Your Testimonial *</label>
                        <textarea id="testimonial" name="testimonial" rows="5" required
                                  placeholder="Tell us about your experience working with AI-Solutions..."
                                  class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all resize-none"></textarea>
                    </div>

                    <div>
                        <label for="attachment" class="block text-sm font-medium text-gray-700 mb-2">Attachment (Optional)</label>
                        <input type="file" id="attachment" name="attachment"
                               accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all">
                        <p class="text-xs text-gray-500 mt-1">Supported formats: JPG, PNG, GIF, PDF, DOC, DOCX (Max 5MB)</p>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="consent" name="consent" required
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="consent" class="ml-2 text-sm text-gray-600">
                            I consent to AI-Solutions using my testimonial for marketing purposes *
                        </label>
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" 
                                class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-xl font-semibold hover:shadow-lg transition-all">
                            Submit Feedback
                        </button>
                    </div>
                </form>
                
                <div id="success-message" class="hidden text-center py-8">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Thank You!</h3>
                    <p class="text-gray-600">Your feedback has been submitted successfully. We appreciate you taking the time to share your experience.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-blue-600 to-purple-700">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <div class="fade-in">
                <h2 class="text-4xl font-bold text-white mb-6">
                    Ready to Join Our Success Stories?
                </h2>
                <p class="text-xl text-blue-100 mb-8 leading-relaxed">
                    Experience the same exceptional results that our clients rave about. Let's discuss how we can transform your business.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="contact.php" class="bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold hover:shadow-xl transition-all">
                        Start Your Project
                    </a>
                    <a href="portfolio.php" class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-blue-600 transition-all">
                        View Our Work
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

        // Carousel functionality
        let currentSlide = 0;
        const slides = document.querySelectorAll('.carousel-slide');
        const dots = document.querySelectorAll('.carousel-dot');
        const totalSlides = slides.length;
        let autoRotateInterval;

        function showSlide(index) {
            // Update carousel track position
            const carouselTrack = document.getElementById('carousel-track');
            carouselTrack.style.transform = `translateX(-${index * 100}%)`;

            // Update dots
            dots.forEach(dot => dot.classList.remove('bg-blue-600'));
            dots.forEach(dot => dot.classList.add('bg-gray-300'));
            dots[index].classList.remove('bg-gray-300');
            dots[index].classList.add('bg-blue-600');

            currentSlide = index;
        }

        function nextSlide() {
            const next = (currentSlide + 1) % totalSlides;
            showSlide(next);
        }

        function prevSlide() {
            const prev = (currentSlide - 1 + totalSlides) % totalSlides;
            showSlide(prev);
        }

        function startAutoRotate() {
            autoRotateInterval = setInterval(nextSlide, 6000);
        }

        function stopAutoRotate() {
            clearInterval(autoRotateInterval);
        }

        // Navigation buttons
        document.getElementById('next-btn').addEventListener('click', () => {
            stopAutoRotate();
            nextSlide();
            startAutoRotate();
        });

        document.getElementById('prev-btn').addEventListener('click', () => {
            stopAutoRotate();
            prevSlide();
            startAutoRotate();
        });

        // Dot navigation
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                stopAutoRotate();
                showSlide(index);
                startAutoRotate();
            });
        });

        // Pause on hover
        const carouselContainer = document.querySelector('.carousel-container');
        carouselContainer.addEventListener('mouseenter', stopAutoRotate);
        carouselContainer.addEventListener('mouseleave', startAutoRotate);

        // Start auto-rotation
        startAutoRotate();

        // Star rating functionality
        const ratingStars = document.querySelectorAll('#rating-stars .star');
        const ratingInput = document.getElementById('rating');
        let selectedRating = 0;

        ratingStars.forEach((star, index) => {
            star.addEventListener('click', () => {
                selectedRating = index + 1;
                ratingInput.value = selectedRating;
                updateStarDisplay();
            });

            star.addEventListener('mouseenter', () => {
                highlightStars(index + 1);
            });
        });

        document.getElementById('rating-stars').addEventListener('mouseleave', () => {
            updateStarDisplay();
        });

        function highlightStars(rating) {
            ratingStars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.remove('empty');
                    star.classList.add('star');
                } else {
                    star.classList.add('empty');
                    star.classList.remove('star');
                }
            });
        }

        function updateStarDisplay() {
            highlightStars(selectedRating);
        }

        // Initialize stars as empty
        ratingStars.forEach(star => {
            star.classList.add('empty');
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
</body>
</html>

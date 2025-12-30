<?php
require_once 'includes/functions.php';

$pageTitle = 'Home';

// Fetch data for homepage
$settings = getAllSettings();
$toppers = getToppers(null, 6);
$announcements = getAnnouncements(3);
$events = getEvents(3);
$testimonials = getTestimonials(true, 3);
$galleryImages = getGalleryImages(null, 8);
$staff = getStaff(null, 4);

include 'includes/header.php';
?>

<!-- Hero Section -->
<!-- MOONSTAR PREMIERE HERO SECTION -->
<section class="moonstar-hero">
    <!-- Animated Blobs -->
    <div class="moonstar-blob moonstar-blob-1"></div>
    <div class="moonstar-blob moonstar-blob-2"></div>
    <div class="moonstar-blob moonstar-blob-3"></div>

    <div class="moonstar-container">
        <!-- Content Side -->
        <div class="moonstar-content">
            <div class="moonstar-badge">
                <i class="fas fa-star"></i>
                <span>Welcome to Lotus Valley International School</span>
            </div>

            <h1 class="moonstar-title">
                Shaping Bright Minds for a <br />
                <span class="text-gradient">Better Tomorrow</span>.
            </h1>

            <p class="moonstar-desc">
                Established at Choura campus with a fresh vision and modern outlook.
                We provide a world-class environment where curiosity meets excellence, empowering your child to lead the
                future.
            </p>

            <div class="moonstar-actions">
                <a href="admission.php" class="moonstar-btn moonstar-btn-primary">
                    <i class="fas fa-paper-plane"></i> Apply for Admission
                </a>
                <a href="toppers.php" class="moonstar-btn moonstar-btn-outline">
                    <i class="fas fa-compass"></i> Explore Academics
                </a>
            </div>

            <div class="moonstar-stats-row">
                <div class="moonstar-stat">
                    <strong><?php echo clean($settings['years_established'] ?? '25'); ?>+</strong>
                    <span>Years of Excellence</span>
                </div>
                <div class="moonstar-stat">
                    <strong>100%</strong>
                    <span>College Acceptance</span>
                </div>
                <div class="moonstar-stat">
                    <strong><?php echo clean($settings['students_count'] ?? '1500'); ?>+</strong>
                    <span>Happy Students</span>
                </div>
            </div>
        </div>

        <!-- Visual Side -->
        <div class="moonstar-visual">
            <div class="moonstar-img-wrapper">
                <!-- SVG Illustration Placeholder -->
                <svg width="550" height="450" viewBox="0 0 550 450" xmlns="http://www.w3.org/2000/svg"
                    class="moonstar-hero-img">
                    <defs>
                        <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#3b82f6;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#1d4ed8;stop-opacity:1" />
                        </linearGradient>
                        <clipPath id="blobClip">
                            <rect width="550" height="450" rx="30" />
                        </clipPath>
                    </defs>
                    <rect width="550" height="450" fill="#1e3a8a" rx="30" />
                    <!-- Abstract geometric tech pattern -->
                    <circle cx="50" cy="50" r="300" fill="url(#grad1)" opacity="0.2" />
                    <circle cx="500" cy="400" r="200" fill="#f59e0b" opacity="0.1" />

                    <!-- Student Illustration (External Image) -->
                    <image
                        href="https://images.unsplash.com/photo-1544531861-46487e3831f4?q=80&w=1500&auto=format&fit=crop"
                        x="0" y="0" width="550" height="450" preserveAspectRatio="xMidYMid slice"
                        clip-path="url(#blobClip)" opacity="0.9" />

                </svg>
            </div>

            <!-- Float Card 1 -->
            <div class="moonstar-float-card float-1">
                <i class="fas fa-graduation-cap"></i>
                <div class="moonstar-float-text">
                    <h4>World Class</h4>
                    <span>Education</span>
                </div>
            </div>

            <!-- Float Card 2 -->
            <div class="moonstar-float-card float-2">
                <i class="fas fa-award"></i>
                <div class="moonstar-float-text">
                    <h4>Award Winner</h4>
                    <span>2024-2025</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Stats Strip -->
<section class="stats-strip">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
                <div class="stat-number"><?php echo clean($settings['students_count'] ?? '1500'); ?>+</div>
                <div class="stat-label">Happy Students</div>
            </div>
            <div class="stat-item">
                <div class="stat-icon" style="background-color: rgba(245, 158, 11, 0.1); color: #F59E0B;"><i
                        class="fas fa-chalkboard-teacher"></i></div>
                <div class="stat-number"><?php echo clean($settings['faculty_count'] ?? '100'); ?>+</div>
                <div class="stat-label">Qualified Teachers</div>
            </div>
            <div class="stat-item">
                <div class="stat-icon" style="background-color: rgba(16, 185, 129, 0.1); color: #10B981;"><i
                        class="fas fa-trophy"></i></div>
                <div class="stat-number">100%</div>
                <div class="stat-label">Board Results</div>
            </div>
            <div class="stat-item">
                <div class="stat-icon" style="background-color: rgba(139, 92, 246, 0.1); color: #8B5CF6;"><i
                        class="fas fa-award"></i></div>
                <div class="stat-number"><?php echo clean($settings['years_established'] ?? '25'); ?>+</div>
                <div class="stat-label">Years of Excellence</div>
            </div>
        </div>
    </div>
</section>

<!-- Academic Programs Section -->
<section class="section bg-light">
    <div class="container">
        <div class="section-header">
            <span class="badge badge-primary">Our Programs</span>
            <h2>Academic Programs</h2>
            <p>Comprehensive curriculum designed for every stage of your child's educational journey</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="feature-card">
                <div class="icon-box" style="background: linear-gradient(135deg, #EC4899, #DB2777);">
                    <i class="fas fa-baby"></i>
                </div>
                <h4>Pre-Primary</h4>
                <p>Playful learning environment for Nursery to UKG with focus on foundational skills</p>
                <a href="about.php" class="btn btn-outline btn-pill mt-4"
                    style="font-size: 0.875rem; padding: 0.5rem 1rem;">View Curriculum</a>
            </div>

            <div class="feature-card">
                <div class="icon-box" style="background: linear-gradient(135deg, #14B8A6, #0D9488);">
                    <i class="fas fa-child"></i>
                </div>
                <h4>Primary</h4>
                <p>Building strong fundamentals in Classes 1-5 with activity-based learning</p>
                <a href="about.php" class="btn btn-outline btn-pill mt-4"
                    style="font-size: 0.875rem; padding: 0.5rem 1rem;">View Curriculum</a>
            </div>

            <div class="feature-card">
                <div class="icon-box" style="background: linear-gradient(135deg, #F59E0B, #D97706);">
                    <i class="fas fa-book-reader"></i>
                </div>
                <h4>Middle School</h4>
                <p>Developing critical thinking in Classes 6-8 through integrated curriculum</p>
                <a href="about.php" class="btn btn-outline btn-pill mt-4"
                    style="font-size: 0.875rem; padding: 0.5rem 1rem;">View Curriculum</a>
            </div>

            <div class="feature-card">
                <div class="icon-box" style="background: linear-gradient(135deg, #10B981, #059669);">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h4>Senior Secondary</h4>
                <p>Board exam preparation for Classes 9-12 with career guidance</p>
                <a href="about.php" class="btn btn-outline btn-pill mt-4"
                    style="font-size: 0.875rem; padding: 0.5rem 1rem;">View Curriculum</a>
            </div>
        </div>
    </div>
</section>

<!-- Facilities Section -->
<section class="section bg-white">
    <div class="container">
        <div class="section-header">
            <span class="badge badge-secondary">Infrastructure</span>
            <h2>World-Class Facilities</h2>
            <p>Modern amenities designed to support holistic development of every student</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="feature-card" style="padding: 1.5rem;">
                <div class="icon-box icon-box-sm"
                    style="margin: 0 auto 1rem; background: linear-gradient(135deg, #14B8A6, #0D9488);">
                    <i class="fas fa-flask"></i>
                </div>
                <h4 style="font-size: 1rem;">Science Labs</h4>
            </div>

            <div class="feature-card" style="padding: 1.5rem;">
                <div class="icon-box icon-box-sm"
                    style="margin: 0 auto 1rem; background: linear-gradient(135deg, #F59E0B, #D97706);">
                    <i class="fas fa-desktop"></i>
                </div>
                <h4 style="font-size: 1rem;">Computer Lab</h4>
            </div>

            <div class="feature-card" style="padding: 1.5rem;">
                <div class="icon-box icon-box-sm"
                    style="margin: 0 auto 1rem; background: linear-gradient(135deg, #EC4899, #DB2777);">
                    <i class="fas fa-book"></i>
                </div>
                <h4 style="font-size: 1rem;">Library</h4>
            </div>

            <div class="feature-card" style="padding: 1.5rem;">
                <div class="icon-box icon-box-sm"
                    style="margin: 0 auto 1rem; background: linear-gradient(135deg, #10B981, #059669);">
                    <i class="fas fa-futbol"></i>
                </div>
                <h4 style="font-size: 1rem;">Sports Ground</h4>
            </div>

            <div class="feature-card" style="padding: 1.5rem;">
                <div class="icon-box icon-box-sm"
                    style="margin: 0 auto 1rem; background: linear-gradient(135deg, #8B5CF6, #7C3AED);">
                    <i class="fas fa-music"></i>
                </div>
                <h4 style="font-size: 1rem;">Music Room</h4>
            </div>

            <div class="feature-card" style="padding: 1.5rem;">
                <div class="icon-box icon-box-sm"
                    style="margin: 0 auto 1rem; background: linear-gradient(135deg, #6366F1, #4F46E5);">
                    <i class="fas fa-bus"></i>
                </div>
                <h4 style="font-size: 1rem;">Transport</h4>
            </div>
        </div>
    </div>
</section>

<!-- Toppers Section -->
<?php if (!empty($toppers)): ?>
    <section class="section bg-light">
        <div class="container">
            <div class="section-header">
                <span class="badge badge-secondary"><i class="fas fa-trophy"></i> Our Achievers</span>
                <h2>Celebrating Our Top Performers</h2>
                <p>Outstanding academic achievements by our brilliant students</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($toppers as $topper): ?>
                    <div class="card">
                        <div style="height: 200px; background: linear-gradient(135deg, #F59E0B, #D97706); position: relative;">
                            <?php if ($topper['photo']): ?>
                                <img src="uploads/toppers/<?php echo clean($topper['photo']); ?>"
                                    alt="<?php echo clean($topper['name']); ?>"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <div
                                    style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-user-graduate" style="font-size: 4rem; color: rgba(255,255,255,0.5);"></i>
                                </div>
                            <?php endif; ?>
                            <div
                                style="position: absolute; top: 1rem; right: 1rem; background: var(--color-primary); color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; font-weight: 700; font-size: 1.25rem;">
                                <?php echo clean($topper['percentage']); ?>%
                            </div>
                        </div>
                        <div class="card-body">
                            <h4><?php echo clean($topper['name']); ?></h4>
                            <div style="color: var(--text-muted); margin-top: 0.5rem;">
                                <p><i class="fas fa-star"
                                        style="color: #F59E0B; margin-right: 0.5rem;"></i><?php echo clean($topper['marks']); ?>
                                </p>
                                <p><i class="fas fa-graduation-cap"
                                        style="color: var(--color-primary); margin-right: 0.5rem;"></i><?php echo clean($topper['class']); ?>
                                    • <?php echo clean($topper['board']); ?></p>
                                <?php if ($topper['achievement']): ?>
                                    <p
                                        style="margin-top: 0.75rem; padding: 0.5rem; background: rgba(37, 99, 235, 0.1); border-radius: 0.5rem; color: var(--color-primary); font-weight: 500;">
                                        <?php echo clean($topper['achievement']); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="text-center mt-8">
                <a href="toppers.php" class="btn btn-primary btn-lg btn-pill">
                    View All Achievers <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- News, Notices & Events -->
<section class="section bg-white">
    <div class="container">
        <div class="section-header">
            <span class="badge badge-primary">Stay Updated</span>
            <h2>Latest Updates</h2>
            <p>Stay informed about school announcements, news, and upcoming events</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Announcements -->
            <div>
                <h3 style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">
                    <div class="icon-box icon-box-sm" style="background: linear-gradient(135deg, #F59E0B, #D97706);">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    Announcements
                </h3>

                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <?php if (!empty($announcements)): ?>
                        <?php foreach ($announcements as $announcement): ?>
                            <div class="card"
                                style="border-left: 4px solid <?php echo $announcement['priority'] === 'high' ? '#F59E0B' : ($announcement['priority'] === 'medium' ? '#14B8A6' : '#D1D5DB'); ?>;">
                                <div class="card-body">
                                    <div
                                        style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                                        <h4 style="font-size: 1.125rem;"><?php echo clean($announcement['title']); ?></h4>
                                        <?php if ($announcement['priority'] === 'high'): ?>
                                            <span class="badge badge-secondary" style="font-size: 0.75rem;">Important</span>
                                        <?php endif; ?>
                                    </div>
                                    <p style="color: var(--text-muted); font-size: 0.9375rem; margin-bottom: 0.75rem;">
                                        <?php echo clean($announcement['content']); ?>
                                    </p>
                                    <p style="color: var(--text-muted); font-size: 0.875rem;">
                                        <i class="fas fa-calendar"
                                            style="color: var(--color-primary); margin-right: 0.5rem;"></i><?php echo formatDate($announcement['date']); ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="card text-center" style="padding: 3rem;">
                            <i class="fas fa-bullhorn"
                                style="font-size: 3rem; color: var(--border-light); margin-bottom: 1rem;"></i>
                            <p style="color: var(--text-muted);">No announcements at the moment</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Events -->
            <div>
                <h3 style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">
                    <div class="icon-box icon-box-sm" style="background: linear-gradient(135deg, #14B8A6, #0D9488);">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    Upcoming Events
                </h3>

                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <?php if (!empty($events)): ?>
                        <?php foreach ($events as $event): ?>
                            <div class="card">
                                <div class="card-body" style="display: flex; gap: 1rem;">
                                    <div
                                        style="background: var(--color-primary); color: white; padding: 1rem; border-radius: 0.75rem; text-align: center; min-width: 70px;">
                                        <div style="font-size: 1.5rem; font-weight: 700;">
                                            <?php echo date('d', strtotime($event['event_date'])); ?>
                                        </div>
                                        <div style="font-size: 0.75rem; text-transform: uppercase;">
                                            <?php echo date('M', strtotime($event['event_date'])); ?>
                                        </div>
                                    </div>
                                    <div style="flex: 1;">
                                        <h4 style="font-size: 1.125rem; margin-bottom: 0.5rem;">
                                            <?php echo clean($event['title']); ?>
                                        </h4>
                                        <?php if ($event['description']): ?>
                                            <p style="color: var(--text-muted); font-size: 0.9375rem; margin-bottom: 0.5rem;">
                                                <?php echo clean($event['description']); ?>
                                            </p>
                                        <?php endif; ?>
                                        <div style="display: flex; gap: 1rem; font-size: 0.875rem; color: var(--text-muted);">
                                            <?php if ($event['event_time']): ?>
                                                <span><i class="fas fa-clock"
                                                        style="color: var(--color-primary); margin-right: 0.25rem;"></i><?php echo date('g:i A', strtotime($event['event_time'])); ?></span>
                                            <?php endif; ?>
                                            <?php if ($event['location']): ?>
                                                <span><i class="fas fa-map-marker-alt"
                                                        style="color: #F59E0B; margin-right: 0.25rem;"></i><?php echo clean($event['location']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="card text-center" style="padding: 3rem;">
                            <i class="fas fa-calendar"
                                style="font-size: 3rem; color: var(--border-light); margin-bottom: 1rem;"></i>
                            <p style="color: var(--text-muted);">No upcoming events</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Preview -->
<?php if (!empty($galleryImages)): ?>
    <section class="section bg-light">
        <div class="container">
            <div class="section-header">
                <span class="badge badge-primary">Photo Gallery</span>
                <h2>Capturing Moments</h2>
                <p>Glimpses of life at Lotus Valley</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <?php foreach ($galleryImages as $image): ?>
                    <div class="card" style="overflow: hidden; cursor: pointer;">
                        <div style="aspect-ratio: 1; overflow: hidden;">
                            <img src="uploads/gallery/<?php echo clean($image['image']); ?>" alt="Gallery Image"
                                style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="text-center mt-8">
                <a href="gallery.php" class="btn btn-primary btn-lg btn-pill">
                    View Full Gallery <i class="fas fa-images"></i>
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Testimonials Section -->
<?php if (!empty($testimonials)): ?>
    <section class="section bg-white">
        <div class="container">
            <div class="section-header">
                <span class="badge badge-secondary">Testimonials</span>
                <h2>What Parents Say</h2>
                <p>Hear from the families who trust us with their children's education</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php foreach ($testimonials as $testimonial): ?>
                    <div class="card">
                        <div class="card-body">
                            <div style="display: flex; gap: 0.25rem; margin-bottom: 1rem;">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star"
                                        style="color: <?php echo $i <= $testimonial['rating'] ? '#F59E0B' : '#E5E7EB'; ?>;"></i>
                                <?php endfor; ?>
                            </div>
                            <p style="color: var(--text-body); font-style: italic; margin-bottom: 1.5rem; line-height: 1.7;">
                                "<?php echo clean($testimonial['content']); ?>"
                            </p>
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <?php if ($testimonial['photo']): ?>
                                    <img src="uploads/testimonials/<?php echo clean($testimonial['photo']); ?>"
                                        alt="<?php echo clean($testimonial['name']); ?>"
                                        style="width: 3rem; height: 3rem; border-radius: 50%; object-fit: cover;">
                                <?php else: ?>
                                    <div
                                        style="width: 3rem; height: 3rem; border-radius: 50%; background: var(--color-primary); display: flex; align-items: center; justify-content: center; color: white;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <h4 style="font-size: 1rem; margin-bottom: 0.125rem;">
                                        <?php echo clean($testimonial['name']); ?>
                                    </h4>
                                    <p style="font-size: 0.875rem; color: var(--color-primary);">
                                        <?php echo clean($testimonial['role']); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- CTA Section -->
<section class="section"
    style="background: linear-gradient(135deg, #0D9488, #0F766E); color: white; position: relative; overflow: hidden;">
    <div
        style="position: absolute; inset: 0; background: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Cpath d=\'M30 30c0-5.52 4.48-10 10-10V10C30 10 22.18 14.48 16 20.66V30h14z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
    </div>
    <div class="container text-center" style="position: relative; z-index: 1;">
        <div style="max-width: 48rem; margin: 0 auto;">
            <h2 style="color: white; margin-bottom: 1.5rem;">Ready to Join Our School Community?</h2>
            <p style="font-size: 1.25rem; opacity: 0.9; margin-bottom: 2rem;">
                Give your child the gift of quality education. Start the admission process today!
            </p>
            <div style="display: flex; flex-wrap: wrap; gap: 1rem; justify-content: center;">
                <a href="admission.php" class="btn btn-secondary btn-lg btn-pill">
                    <i class="fas fa-paper-plane"></i> Start Admission Process
                </a>
                <a href="contact.php" class="btn btn-lg btn-pill"
                    style="background: rgba(255,255,255,0.1); color: white; border: 2px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-phone"></i> Contact Office
                </a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
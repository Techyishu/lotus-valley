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

<style>
    :root {
        --navy-blue: #1e3a8a;
        --navy-dark: #172554;
        --maroon-red: #b91c1c;
        --gold: #d4af37;
        --gold-light: #f5d78e;
    }

    .bg-navy {
        background-color: var(--navy-blue);
    }

    .bg-navy-dark {
        background-color: var(--navy-dark);
    }

    .bg-maroon {
        background-color: var(--maroon-red);
    }

    .bg-gold {
        background-color: var(--gold);
    }

    .text-navy {
        color: var(--navy-blue);
    }

    .text-maroon {
        color: var(--maroon-red);
    }

    .text-gold {
        color: var(--gold);
    }

    .border-gold {
        border-color: var(--gold);
    }

    .gradient-navy {
        background: linear-gradient(135deg, var(--navy-dark) 0%, var(--navy-blue) 50%, #2563eb 100%);
    }

    .gradient-text-school {
        background: linear-gradient(135deg, var(--maroon-red), var(--gold));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-pattern {
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .card-hover {
        transition: all 0.3s ease;
    }

    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .animate-float {
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-20px);
        }
    }

    .animate-pulse-slow {
        animation: pulse 3s ease-in-out infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.7;
        }
    }

    .shield-shadow {
        filter: drop-shadow(0 25px 50px rgba(30, 58, 138, 0.3));
    }
</style>

<!-- Hero Section - 2025 Modern Design -->
<section class="relative min-h-screen bg-white overflow-hidden">
    <!-- Background Image with Overlay -->
    <div class="absolute inset-0">
        <!-- You can replace this with an actual school image -->
        <div class="absolute inset-0 bg-gradient-to-br from-navy-dark via-navy to-blue-900"
            style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #1e40af 100%);"></div>
        <!-- Decorative Pattern Overlay -->
        <div class="absolute inset-0 opacity-10"
            style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;1&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
        </div>
        <!-- Gradient Overlay for better text readability -->
        <div class="absolute inset-0 bg-gradient-to-r from-black via-transparent to-black opacity-40"></div>
    </div>

    <!-- Floating Decorative Elements -->
    <div
        class="absolute top-20 left-10 w-72 h-72 bg-maroon rounded-full mix-blend-soft-light filter blur-3xl opacity-30 animate-float">
    </div>
    <div class="absolute bottom-32 right-20 w-96 h-96 bg-gold rounded-full mix-blend-soft-light filter blur-3xl opacity-20 animate-float"
        style="animation-delay: 2s;"></div>

    <!-- Main Content Container -->
    <div class="relative z-10 min-h-screen flex items-center">
        <div class="container mx-auto px-4 py-20 lg:py-32">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">

                <!-- Left Content Column -->
                <div class="lg:col-span-7 text-center lg:text-left space-y-8">

                    <!-- Trust Badge -->
                    <div
                        class="inline-flex items-center gap-3 bg-white bg-opacity-15 backdrop-blur-md border border-white border-opacity-30 rounded-full px-5 py-2.5 shadow-xl">
                        <div class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"
                                style="background-color: #d4af37;"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3"
                                style="background-color: #d4af37;"></span>
                        </div>
                        <span class="text-sm md:text-base font-semibold text-white tracking-wide">CBSE Affiliated •
                            Batch 2025 Open</span>
                    </div>

                    <!-- Main Heading - Bold & Impactful -->
                    <div class="space-y-4">
                        <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black leading-[1.1] text-white">
                            Shaping Tomorrow's
                            <span
                                class="block mt-2 bg-gradient-to-r from-yellow-400 via-gold to-yellow-600 bg-clip-text text-transparent"
                                style="background: linear-gradient(135deg, #fbbf24 0%, #d4af37 50%, #f59e0b 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                                Leaders Today
                            </span>
                        </h1>

                        <!-- Decorative Line -->
                        <div class="flex items-center justify-center lg:justify-start gap-4">
                            <div class="h-1 w-16 md:w-24 rounded-full"
                                style="background: linear-gradient(90deg, transparent, #d4af37);"></div>
                            <p class="text-lg md:text-xl font-bold text-yellow-400 italic tracking-wider">"Prosperity
                                with Purity"</p>
                            <div class="h-1 w-16 md:w-24 rounded-full"
                                style="background: linear-gradient(90deg, #d4af37, transparent);"></div>
                        </div>
                    </div>

                    <!-- Value Proposition -->
                    <p
                        class="text-lg md:text-xl lg:text-2xl text-blue-100 leading-relaxed max-w-2xl mx-auto lg:mx-0 font-light">
                        Where <span class="font-semibold text-white">academic excellence</span> meets <span
                            class="font-semibold text-white">holistic development</span>. Join 2,500+ students on their
                        journey to success.
                    </p>

                    <!-- CTA Buttons - Clear & Action-Oriented -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-4 justify-center lg:justify-start">
                        <!-- Primary CTA -->
                        <a href="admission.php"
                            class="group relative overflow-hidden bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-gray-900 font-bold px-8 py-5 rounded-2xl transition-all duration-300 transform hover:scale-105 hover:shadow-2xl flex items-center justify-center"
                            style="background: linear-gradient(135deg, #d4af37 0%, #f59e0b 100%);">
                            <span class="relative z-10 flex items-center gap-3">
                                <i class="fas fa-graduation-cap text-xl"></i>
                                <span class="text-lg">Start Your Application</span>
                                <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </span>
                            <!-- Shine Effect -->
                            <div
                                class="absolute inset-0 -translate-x-full group-hover:translate-x-full transition-transform duration-1000 bg-gradient-to-r from-transparent via-white to-transparent opacity-20">
                            </div>
                        </a>

                        <!-- Secondary CTA -->
                        <a href="about.php"
                            class="group bg-white bg-opacity-10 hover:bg-opacity-20 backdrop-blur-md text-white font-bold px-8 py-5 rounded-2xl transition-all duration-300 flex items-center justify-center gap-3 border-2 border-white border-opacity-30 hover:border-opacity-50">
                            <i class="fas fa-play-circle text-xl"></i>
                            <span class="text-lg">Take a Virtual Tour</span>
                        </a>
                    </div>

                    <!-- Social Proof - Quick Stats -->
                    <div class="grid grid-cols-3 gap-6 pt-8 max-w-xl mx-auto lg:mx-0">
                        <div class="text-center lg:text-left group cursor-pointer">
                            <div class="text-4xl md:text-5xl font-black bg-gradient-to-br from-yellow-400 to-yellow-600 bg-clip-text text-transparent group-hover:scale-110 transition-transform"
                                style="background: linear-gradient(135deg, #fbbf24, #f59e0b); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                                <?php echo clean($settings['students_count'] ?? '2500'); ?>+
                            </div>
                            <div class="text-sm md:text-base text-blue-200 mt-2 font-medium">Happy Students</div>
                        </div>
                        <div
                            class="text-center lg:text-left group cursor-pointer border-x border-white border-opacity-20">
                            <div class="text-4xl md:text-5xl font-black bg-gradient-to-br from-yellow-400 to-yellow-600 bg-clip-text text-transparent group-hover:scale-110 transition-transform"
                                style="background: linear-gradient(135deg, #fbbf24, #f59e0b); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                                <?php echo clean($settings['years_established'] ?? '25'); ?>+
                            </div>
                            <div class="text-sm md:text-base text-blue-200 mt-2 font-medium">Years Legacy</div>
                        </div>
                        <div class="text-center lg:text-left group cursor-pointer">
                            <div class="text-4xl md:text-5xl font-black bg-gradient-to-br from-yellow-400 to-yellow-600 bg-clip-text text-transparent group-hover:scale-110 transition-transform"
                                style="background: linear-gradient(135deg, #fbbf24, #f59e0b); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                                <?php echo clean($settings['awards_count'] ?? '50'); ?>+
                            </div>
                            <div class="text-sm md:text-base text-blue-200 mt-2 font-medium">Awards Won</div>
                        </div>
                    </div>
                </div>

                <!-- Right Content Column - Visual Element -->
                <div class="lg:col-span-5 relative flex justify-center lg:justify-end">
                    <div class="relative w-full max-w-md lg:max-w-lg">

                        <!-- Main Image Container -->
                        <div class="relative">
                            <!-- Decorative Background Shape -->
                            <div
                                class="absolute -inset-4 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-3xl opacity-20 blur-2xl animate-pulse-slow">
                            </div>

                            <!-- Primary Image -->
                            <div
                                class="relative rounded-3xl overflow-hidden shadow-2xl border-4 border-white border-opacity-20 backdrop-blur-sm transform hover:scale-105 transition-transform duration-500">
                                <img src="assets/images/hero-school-building.jpg"
                                    alt="<?php echo clean($settings['school_name'] ?? 'Anthem International School'); ?>"
                                    class="w-full h-[400px] md:h-[500px] object-cover"
                                    onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%27600%27 height=%27600%27%3E%3Crect fill=%27%231e3a8a%27 width=%27600%27 height=%27600%27/%3E%3Ctext fill=%27%23ffffff%27 font-family=%27Arial%27 font-size=%2724%27 x=%2750%25%27 y=%2750%25%27 text-anchor=%27middle%27 dy=%27.3em%27%3ESchool Campus%3C/text%3E%3C/svg%3E';">
                                <!-- Gradient Overlay for depth -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-navy via-transparent to-transparent opacity-60">
                                </div>
                            </div>

                            <!-- Floating Achievement Badges -->
                            <!-- Top Right Badge -->
                            <div
                                class="absolute -top-6 -right-6 bg-white text-gray-900 px-5 py-3 rounded-2xl shadow-2xl animate-float transform hover:rotate-3 transition-transform cursor-pointer border-2 border-yellow-400">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-trophy text-2xl" style="color: #d4af37;"></i>
                                    <div class="text-left">
                                        <div class="text-xs font-semibold text-gray-600">Top Ranked</div>
                                        <div class="text-sm font-black" style="color: #1e3a8a;">Excellence</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bottom Left Badge -->
                            <div class="absolute -bottom-6 -left-6 px-6 py-3 rounded-2xl shadow-2xl animate-float text-white transform hover:-rotate-3 transition-transform cursor-pointer border-2 border-white border-opacity-30 backdrop-blur-md"
                                style="background: linear-gradient(135deg, #b91c1c 0%, #dc2626 100%); animation-delay: 1s;">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-award text-2xl"></i>
                                    <div class="text-left">
                                        <div class="text-xs font-semibold">Certified</div>
                                        <div class="text-sm font-black">CBSE Board</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Middle Right Success Badge -->
                            <div class="hidden md:block absolute top-1/2 -right-8 transform -translate-y-1/2 bg-gradient-to-br from-green-500 to-green-600 text-white px-4 py-3 rounded-xl shadow-xl animate-float"
                                style="animation-delay: 0.5s;">
                                <div class="text-center">
                                    <div class="text-2xl font-black">98%</div>
                                    <div class="text-xs font-semibold">Pass Rate</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-20 animate-bounce">
        <div class="flex flex-col items-center gap-2 cursor-pointer group">
            <span class="text-white text-sm font-medium opacity-75 group-hover:opacity-100 transition-opacity">Scroll to
                explore</span>
            <div
                class="w-6 h-10 border-2 border-white border-opacity-50 rounded-full flex items-start justify-center p-2">
                <div class="w-1.5 h-3 bg-white rounded-full animate-bounce"></div>
            </div>
        </div>
    </div>

    <!-- Modern Bottom Wave Transition -->
    <div class="absolute bottom-0 left-0 right-0 z-10">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
            <path d="M0,64 C240,96 480,96 720,64 C960,32 1200,32 1440,64 L1440,120 L0,120 Z" fill="#ffffff"
                opacity="1" />
        </svg>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="py-16 md:py-24 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <span class="inline-block bg-navy px-4 py-2 rounded-full text-sm font-semibold mb-4 text-white">
                Why Choose Us
            </span>
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4">
                <span class="text-navy">Building</span> <span class="text-maroon">Future</span> <span
                    class="gradient-text-school">Leaders</span>
            </h2>
            <p class="text-gray-600 max-w-2xl mx-auto text-lg">
                We provide world-class education with a perfect blend of academics, sports, and extracurricular
                activities.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div
                class="group text-center p-8 rounded-2xl bg-gradient-to-br from-gray-50 to-white border border-gray-100 card-hover">
                <div class="w-20 h-20 mx-auto mb-6 rounded-2xl flex items-center justify-center transform group-hover:rotate-6 transition-transform"
                    style="background: linear-gradient(135deg, #1e3a8a, #2563eb);">
                    <i class="fas fa-book-open text-3xl" style="color: #ffffff;"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Quality Education</h3>
                <p class="text-gray-600">CBSE curriculum with innovative teaching methods and personalized attention</p>
            </div>

            <div
                class="group text-center p-8 rounded-2xl bg-gradient-to-br from-gray-50 to-white border border-gray-100 card-hover">
                <div class="w-20 h-20 mx-auto mb-6 rounded-2xl flex items-center justify-center transform group-hover:rotate-6 transition-transform"
                    style="background: linear-gradient(135deg, #b91c1c, #dc2626);">
                    <i class="fas fa-chalkboard-teacher text-3xl" style="color: #ffffff;"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Expert Faculty</h3>
                <p class="text-gray-600"><?php echo clean($settings['faculty_count'] ?? '150'); ?>+ experienced teachers
                    dedicated to student success</p>
            </div>

            <div
                class="group text-center p-8 rounded-2xl bg-gradient-to-br from-gray-50 to-white border border-gray-100 card-hover">
                <div class="w-20 h-20 mx-auto mb-6 rounded-2xl flex items-center justify-center transform group-hover:rotate-6 transition-transform"
                    style="background: linear-gradient(135deg, #d4af37, #eab308);">
                    <i class="fas fa-microscope text-3xl" style="color: #ffffff;"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Modern Infrastructure</h3>
                <p class="text-gray-600">State-of-the-art labs, smart classrooms, and sports facilities</p>
            </div>

            <div
                class="group text-center p-8 rounded-2xl bg-gradient-to-br from-gray-50 to-white border border-gray-100 card-hover">
                <div class="w-20 h-20 mx-auto mb-6 rounded-2xl flex items-center justify-center transform group-hover:rotate-6 transition-transform"
                    style="background: linear-gradient(135deg, #16a34a, #10b981);">
                    <i class="fas fa-trophy text-3xl" style="color: #ffffff;"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Holistic Development</h3>
                <p class="text-gray-600">Sports, arts, music, and leadership programs for complete growth</p>
            </div>
        </div>
    </div>
</section>

<!-- Leadership Section -->
<section class="py-16 md:py-24 bg-gradient-to-br from-gray-50 to-blue-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <span class="inline-block bg-maroon px-4 py-2 rounded-full text-sm font-semibold mb-4 text-white">
                Our Leadership
            </span>
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                Meet Our <span class="gradient-text-school">Visionaries</span>
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <!-- Chairman -->
            <div class="group bg-white rounded-3xl shadow-xl overflow-hidden card-hover">
                <div class="relative h-72 overflow-hidden">
                    <img src="assets/images/about-school.jpg" alt="Chairman"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-navy via-transparent to-transparent opacity-80">
                    </div>
                    <div class="absolute bottom-4 left-4 right-4 text-white">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold mb-2"
                            style="background-color: #d4af37; color: #172554;">Chairman</span>
                    </div>
                </div>
                <div class="p-6 text-center">
                    <h3 class="text-xl font-bold text-gray-900">Shri Satpal Chauhan</h3>
                    <p class="text-gray-600 mt-2 text-sm">Leading with vision and dedication to nurture future leaders
                    </p>
                </div>
            </div>

            <!-- Director -->
            <div class="group bg-white rounded-3xl shadow-xl overflow-hidden card-hover">
                <div class="relative h-72 overflow-hidden">
                    <img src="assets/images/WhatsApp Image 2025-12-19 at 18.42.31.jpeg" alt="Director"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-maroon via-transparent to-transparent opacity-80">
                    </div>
                    <div class="absolute bottom-4 left-4 right-4 text-white">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold mb-2"
                            style="background-color: #d4af37; color: #172554;">Director</span>
                    </div>
                </div>
                <div class="p-6 text-center">
                    <h3 class="text-xl font-bold text-gray-900">Shri Sompal Rana</h3>
                    <p class="text-gray-600 mt-2 text-sm">Driving excellence and innovation in education</p>
                </div>
            </div>

            <!-- Principal -->
            <div class="group bg-white rounded-3xl shadow-xl overflow-hidden card-hover">
                <div class="relative h-72 overflow-hidden">
                    <img src="assets/images/school-building.jpg" alt="Principal"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-navy via-transparent to-transparent opacity-80">
                    </div>
                    <div class="absolute bottom-4 left-4 right-4 text-white">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold mb-2"
                            style="background-color: #d4af37; color: #172554;">Principal</span>
                    </div>
                </div>
                <div class="p-6 text-center">
                    <h3 class="text-xl font-bold text-gray-900">Miss Amita Chopra</h3>
                    <p class="text-gray-600 mt-2 text-sm">Committed to academic excellence and student development</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Preview Section -->
<section class="py-16 md:py-24 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <div class="relative order-2 lg:order-1">
                <div class="grid grid-cols-2 gap-4">
                    <img src="assets/images/WhatsApp Image 2025-12-19 at 18.42.31.jpeg" alt="School Leadership"
                        class="rounded-2xl shadow-xl w-full h-48 md:h-64 object-cover">
                    <img src="assets/images/about-school.jpg" alt="School Staff"
                        class="rounded-2xl shadow-xl w-full h-48 md:h-64 object-cover mt-8">
                    <img src="assets/images/school-building.jpg" alt="School Event"
                        class="rounded-2xl shadow-xl w-full h-48 md:h-64 object-cover object-top col-span-2 block"
                        style="display: block; opacity: 1; visibility: visible; object-position: top center;"
                        onerror="this.style.display='block'; this.style.opacity='1'; this.style.visibility='visible'; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'600\' height=\'400\'%3E%3Crect fill=\'%234F46E5\' width=\'600\' height=\'400\'/%3E%3Ctext fill=\'%23ffffff\' font-family=\'Arial\' font-size=\'24\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\'%3ESchool Building%3C/text%3E%3C/svg%3E';">
                </div>
                <div class="absolute -bottom-6 -right-6 bg-gradient-to-br from-navy to-blue-600 text-white p-6 rounded-2xl shadow-2xl"
                    style="background-color: var(--maroon-red);">
                    <div class="text-4xl font-bold" style="color: #d4af37;">
                        <?php echo clean($settings['years_established'] ?? '25'); ?>+
                    </div>
                    <div class="text-sm text-blue-100">Years of Excellence</div>
                </div>
            </div>

            <div class="space-y-6 order-1 lg:order-2">
                <span class="inline-block bg-navy px-4 py-2 rounded-full text-sm font-semibold text-white">
                    About Our School
                </span>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 leading-tight">
                    Where <span class="gradient-text-school">Dreams</span> Take Flight
                </h2>
                <p class="text-gray-600 leading-relaxed text-lg">
                    <?php echo clean($settings['about_text'] ?? 'Anthem International School is a premier educational institution dedicated to providing quality education and holistic development of students. We believe in nurturing young minds to become responsible citizens and future leaders.'); ?>
                </p>

                <div class="grid grid-cols-2 gap-6 py-6">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-award text-xl" style="color: #1e3a8a;"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">CBSE Affiliated</h4>
                            <p class="text-gray-600 text-sm">Recognized excellence</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-users text-xl" style="color: #b91c1c;"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">Expert Faculty</h4>
                            <p class="text-gray-600 text-sm"><?php echo clean($settings['faculty_count'] ?? '150'); ?>+
                                teachers</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-flask text-xl" style="color: #92400e;"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">Smart Labs</h4>
                            <p class="text-gray-600 text-sm">Modern facilities</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-bus text-xl" style="color: #16a34a;"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">Transport</h4>
                            <p class="text-gray-600 text-sm">All areas covered</p>
                        </div>
                    </div>
                </div>

                <a href="about.php"
                    class="inline-flex items-center bg-navy text-white font-semibold px-8 py-4 rounded-full hover:bg-navy-dark transition-all shadow-lg">
                    Learn More About Us <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Toppers Section -->
<?php if (!empty($toppers)): ?>
    <section class="py-16 md:py-24 bg-gradient-to-br from-gray-50 to-blue-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold mb-4 text-white"
                    style="background-color: #d4af37;">
                    <i class="fas fa-trophy mr-2"></i>Our Pride
                </span>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                    Celebrating Our <span style="color: #b91c1c;">Achievers</span>
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">
                    Outstanding academic achievements by our brilliant students
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($toppers as $topper): ?>
                    <div class="bg-white rounded-3xl overflow-hidden shadow-xl border border-gray-100 card-hover">
                        <div class="relative h-64" style="background: linear-gradient(135deg, #d4af37, #f59e0b);">
                            <?php if ($topper['photo']): ?>
                                <img src="uploads/toppers/<?php echo clean($topper['photo']); ?>"
                                    alt="<?php echo clean($topper['name']); ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-user-graduate text-6xl" style="color: #172554;"></i>
                                </div>
                            <?php endif; ?>
                            <div class="absolute top-4 right-4 px-4 py-2 rounded-full text-lg font-bold shadow-lg text-white"
                                style="background-color: #b91c1c;">
                                <?php echo clean($topper['percentage']); ?>%
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-3"><?php echo clean($topper['name']); ?></h3>
                            <div class="space-y-2 text-sm text-gray-600">
                                <p><i class="fas fa-star mr-2"
                                        style="color: #d4af37;"></i><?php echo clean($topper['marks']); ?></p>
                                <p><i class="fas fa-graduation-cap mr-2"
                                        style="color: #1e3a8a;"></i><?php echo clean($topper['class']); ?> •
                                    <?php echo clean($topper['board']); ?>
                                </p>
                                <?php if ($topper['achievement']): ?>
                                    <p class="font-semibold pt-2" style="color: #b91c1c;">
                                        <?php echo clean($topper['achievement']); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="text-center mt-12">
                <a href="toppers.php"
                    class="inline-flex items-center px-8 py-4 rounded-full font-bold text-white hover:opacity-90 transition shadow-xl"
                    style="background-color: #1e3a8a;">
                    View All Toppers <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Facilities Section -->
<section class="py-16 md:py-24 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <span class="inline-block bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                Our Facilities
            </span>
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                World-Class <span class="gradient-text-school">Infrastructure</span>
            </h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            <div class="text-center p-6 rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100 card-hover">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl flex items-center justify-center"
                    style="background-color: #1e3a8a;">
                    <i class="fas fa-flask text-white text-2xl"></i>
                </div>
                <h4 class="font-bold text-gray-900">Science Labs</h4>
            </div>
            <div class="text-center p-6 rounded-2xl bg-gradient-to-br from-red-50 to-red-100 card-hover">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl flex items-center justify-center"
                    style="background-color: #b91c1c;">
                    <i class="fas fa-desktop text-white text-2xl"></i>
                </div>
                <h4 class="font-bold text-gray-900">Computer Lab</h4>
            </div>
            <div class="text-center p-6 rounded-2xl bg-gradient-to-br from-yellow-50 to-yellow-100 card-hover">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl flex items-center justify-center"
                    style="background-color: #d4af37;">
                    <i class="fas fa-book text-white text-2xl"></i>
                </div>
                <h4 class="font-bold text-gray-900">Library</h4>
            </div>
            <div class="text-center p-6 rounded-2xl bg-gradient-to-br from-green-50 to-green-100 card-hover">
                <div class="w-16 h-16 mx-auto mb-4 bg-green-600 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-futbol text-white text-2xl"></i>
                </div>
                <h4 class="font-bold text-gray-900">Sports Ground</h4>
            </div>
            <div class="text-center p-6 rounded-2xl bg-gradient-to-br from-purple-50 to-purple-100 card-hover">
                <div class="w-16 h-16 mx-auto mb-4 bg-purple-600 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-music text-white text-2xl"></i>
                </div>
                <h4 class="font-bold text-gray-900">Music Room</h4>
            </div>
            <div class="text-center p-6 rounded-2xl bg-gradient-to-br from-orange-50 to-orange-100 card-hover">
                <div class="w-16 h-16 mx-auto mb-4 bg-orange-500 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-bus text-white text-2xl"></i>
                </div>
                <h4 class="font-bold text-gray-900">Transport</h4>
            </div>
        </div>
    </div>
</section>

<!-- Events & Announcements Section -->
<section class="py-16 md:py-24 bg-gradient-to-br from-gray-50 to-blue-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Announcements -->
            <div>
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-2xl md:text-3xl font-bold text-gray-900">
                        <i class="fas fa-bullhorn mr-3" style="color: #b91c1c;"></i>
                        Announcements
                    </h3>
                </div>
                <div class="space-y-4">
                    <?php if (!empty($announcements)): ?>
                        <?php foreach ($announcements as $announcement): ?>
                            <div
                                class="bg-white rounded-2xl shadow-lg p-6 border-l-4 <?php echo $announcement['priority'] === 'high' ? 'border-maroon' : ($announcement['priority'] === 'medium' ? 'border-gold' : 'border-navy'); ?> card-hover">
                                <div class="flex justify-between items-start mb-3">
                                    <h4 class="font-bold text-gray-900 text-lg"><?php echo clean($announcement['title']); ?>
                                    </h4>
                                    <?php if ($announcement['priority'] === 'high'): ?>
                                        <span class="bg-maroon text-white text-xs px-3 py-1 rounded-full">Important</span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-gray-600 mb-3"><?php echo clean($announcement['content']); ?></p>
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-calendar mr-2"></i><?php echo formatDate($announcement['date']); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-12 bg-white rounded-2xl">
                            <i class="fas fa-bullhorn text-gray-300 text-5xl mb-4"></i>
                            <p class="text-gray-500">No announcements at the moment</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Events -->
            <div>
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-2xl md:text-3xl font-bold text-gray-900">
                        <i class="fas fa-calendar-alt mr-3" style="color: #1e3a8a;"></i>
                        Upcoming Events
                    </h3>
                </div>
                <div class="space-y-4">
                    <?php if (!empty($events)): ?>
                        <?php foreach ($events as $event): ?>
                            <div class="bg-white rounded-2xl shadow-lg p-6 card-hover">
                                <div class="flex items-start space-x-4">
                                    <div
                                        class="bg-gradient-to-br from-navy to-blue-600 text-white p-4 rounded-xl text-center min-w-[70px]">
                                        <div class="text-2xl font-bold">
                                            <?php echo date('d', strtotime($event['event_date'])); ?>
                                        </div>
                                        <div class="text-xs uppercase"><?php echo date('M', strtotime($event['event_date'])); ?>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-bold text-gray-900 text-lg mb-2"><?php echo clean($event['title']); ?>
                                        </h4>
                                        <?php if ($event['description']): ?>
                                            <p class="text-gray-600 text-sm mb-3"><?php echo clean($event['description']); ?></p>
                                        <?php endif; ?>
                                        <div class="flex flex-wrap gap-3 text-xs text-gray-500">
                                            <?php if ($event['event_time']): ?>
                                                <span><i
                                                        class="fas fa-clock mr-1"></i><?php echo date('g:i A', strtotime($event['event_time'])); ?></span>
                                            <?php endif; ?>
                                            <?php if ($event['location']): ?>
                                                <span><i
                                                        class="fas fa-map-marker-alt mr-1"></i><?php echo clean($event['location']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-12 bg-white rounded-2xl">
                            <i class="fas fa-calendar text-gray-300 text-5xl mb-4"></i>
                            <p class="text-gray-500">No upcoming events</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Preview -->
<?php if (!empty($galleryImages)): ?>
    <section class="py-16 md:py-24 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <span class="inline-block bg-purple-100 text-purple-700 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                    Photo Gallery
                </span>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                    Capturing <span class="gradient-text-school">Moments</span>
                </h2>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <?php foreach ($galleryImages as $image): ?>
                    <div class="relative group overflow-hidden rounded-2xl aspect-square card-hover">
                        <img src="uploads/gallery/<?php echo clean($image['image']); ?>" alt="Gallery Image"
                            class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-navy via-transparent to-transparent opacity-0 group-hover:opacity-90 transition duration-300">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <i class="fas fa-search-plus text-white text-3xl"></i>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="text-center mt-12">
                <a href="gallery.php"
                    class="inline-flex items-center bg-gradient-to-r from-purple-600 to-pink-600 text-white px-8 py-4 rounded-full font-bold hover:from-pink-600 hover:to-purple-600 transition shadow-xl">
                    View Full Gallery <i class="fas fa-images ml-2"></i>
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Testimonials Section -->
<?php if (!empty($testimonials)): ?>
    <section class="py-16 md:py-24 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <span class="inline-block bg-gold px-4 py-2 rounded-full text-sm font-semibold mb-4 text-white">
                    Testimonials
                </span>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                    What Parents <span style="color: #d4af37;">Say</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach ($testimonials as $testimonial): ?>
                    <div
                        class="bg-gradient-to-br from-gray-50 to-white rounded-3xl p-8 border border-gray-200 shadow-lg card-hover">
                        <div class="flex items-center mb-6">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star text-lg"
                                    style="color: <?php echo $i <= $testimonial['rating'] ? '#d4af37' : '#d1d5db'; ?>;"></i>
                            <?php endfor; ?>
                        </div>
                        <p class="text-gray-700 mb-6 leading-relaxed">"<?php echo clean($testimonial['content']); ?>"</p>
                        <div class="flex items-center">
                            <?php if ($testimonial['photo']): ?>
                                <img src="uploads/testimonials/<?php echo clean($testimonial['photo']); ?>"
                                    alt="<?php echo clean($testimonial['name']); ?>"
                                    class="w-14 h-14 rounded-full object-cover mr-4 border-2 border-gold">
                            <?php else: ?>
                                <div class="w-14 h-14 rounded-full flex items-center justify-center mr-4"
                                    style="background: linear-gradient(135deg, #d4af37 0%, #f59e0b 100%);">
                                    <i class="fas fa-user text-white text-xl"></i>
                                </div>
                            <?php endif; ?>
                            <div>
                                <h4 class="font-bold text-gray-900"><?php echo clean($testimonial['name']); ?></h4>
                                <p class="text-sm" style="color: #d4af37;"><?php echo clean($testimonial['role']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- CTA Section -->
<section class="py-16 md:py-24 bg-white relative overflow-hidden">
    <div class="absolute inset-0 hero-pattern opacity-10"></div>
    <div class="container mx-auto px-4 text-center relative z-10">
        <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
            Begin Your Journey to <span style="color: #d4af37;">Excellence</span>
        </h2>
        <p class="text-xl text-gray-700 mb-10 max-w-2xl mx-auto">
            Join the Anthem family and give your child the gift of quality education
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="admission.php"
                class="inline-flex items-center justify-center px-10 py-5 rounded-full font-bold text-lg hover:bg-yellow-400 transition shadow-2xl transform hover:scale-105"
                style="background-color: #d4af37; color: #172554;">
                <i class="fas fa-graduation-cap mr-3"></i>Apply for Admission
            </a>
            <a href="contact.php"
                class="inline-flex items-center justify-center bg-navy text-white px-10 py-5 rounded-full font-bold text-lg border-2 border-navy hover:bg-navy-dark transition">
                <i class="fas fa-phone mr-3"></i>Contact Us
            </a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
<?php
require_once 'includes/functions.php';

$pageTitle = 'About Us';
$settings = getAllSettings();

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="breadcrumb">
            <a href="index.php">Home</a>
            <span>/</span>
            <span>About Us</span>
        </div>
        <h1>Discover Our <span style="color: #F59E0B;">Journey</span></h1>
        <p>Learn more about Lotus Valley's commitment to excellence in education</p>
    </div>
    <div class="wave-bottom">
        <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 64C240 96 480 96 720 64C960 32 1200 32 1440 64V80H0V64Z" fill="#F5F7FB" />
        </svg>
    </div>
</section>

<!-- About Content -->
<section class="section bg-light">
    <div class="container">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8" style="align-items: center; margin-bottom: 4rem;">
            <div style="order: 2;">
                <div style="position: relative;">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='600' height='400'%3E%3Cdefs%3E%3ClinearGradient id='grad1' x1='0%25' y1='0%25' x2='100%25' y2='100%25'%3E%3Cstop offset='0%25' style='stop-color:%232563EB;stop-opacity:1' /%3E%3Cstop offset='100%25' style='stop-color:%2314B8A6;stop-opacity:1' /%3E%3C/linearGradient%3E%3C/defs%3E%3Crect fill='url(%23grad1)' width='600' height='400'/%3E%3Ctext fill='%23ffffff' font-family='sans-serif' font-size='42' font-weight='bold' x='50%25' y='50%25' text-anchor='middle' dy='.3em'%3ELotus Valley%3C/text%3E%3C/svg%3E"
                        alt="School Building"
                        style="width: 100%; height: 400px; object-fit: cover; border-radius: 1rem; box-shadow: var(--shadow-xl);">
                    <div
                        style="position: absolute; bottom: -1rem; right: -1rem; background: var(--color-primary); color: white; padding: 1.5rem; border-radius: 1rem; box-shadow: var(--shadow-lg);">
                        <div style="font-size: 2rem; font-weight: 700;">
                            <?php echo clean($settings['years_established'] ?? '25'); ?>+
                        </div>
                        <div style="font-size: 0.875rem; opacity: 0.9;">Years Legacy</div>
                    </div>
                </div>
            </div>

            <div style="order: 1;">
                <span class="badge badge-primary mb-4">About Our School</span>
                <h2>Welcome to <span style="color: var(--color-primary);">Lotus Valley International School</span></h2>
                <h4 style="color: var(--text-muted); margin-bottom: 1rem;">(Under the aegis of Lotus Valley Social &
                    Educational Trust)</h4>

                <p style="color: var(--text-body); line-height: 1.8; margin: 1rem 0;">
                    Lotus Valley International School has been established at Choura campus with a fresh vision, modern
                    outlook, and a strong academic foundation. The school is a new and progressive transformation of the
                    well-established Saraswati Senior Secondary School, carrying forward its legacy of trust,
                    discipline, and academic excellence into a contemporary and future-ready educational institution.
                </p>
                <p style="color: var(--text-body); line-height: 1.8; margin: 1rem 0;">
                    Keeping in view the changing needs of education, global standards, and holistic child development,
                    the school has been restructured and initiated under the guidance of Lotus Valley Social &
                    Educational Trust. This new educational journey is being led under the dynamic direction of
                    <strong>Mr. Sompal Rana</strong>, whose vision is to provide education that goes beyond textbooks
                    and prepares students for real-life challenges.
                </p>
                <p style="color: var(--text-body); line-height: 1.8; margin: 1rem 0;">
                    Lotus Valley International School aims to provide high-quality, value-based, and modern education to
                    students from rural and semi-urban areas, enabling them to compete confidently at national and
                    international levels.
                </p>

                <div class="grid grid-cols-2 gap-4 mt-6">
                    <div class="flex items-center gap-3">
                        <div class="icon-box icon-box-sm"><i class="fas fa-check"></i></div>
                        <span style="font-weight: 600;">CBSE Affiliated</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="icon-box icon-box-sm" style="background: #F59E0B;"><i class="fas fa-check"></i>
                        </div>
                        <span style="font-weight: 600;">Modern Facilities</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Leadership Section -->
        <div class="section-header mt-12">
            <span class="badge badge-secondary">Our Leadership</span>
            <h2>Meet Our Visionaries</h2>
            <p>Guided by experienced leaders committed to excellence in education</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="card">
                <div
                    style="height: 250px; overflow: hidden; background: linear-gradient(135deg, #0D9488, #14B8A6); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-user-tie" style="font-size: 5rem; color: rgba(255,255,255,0.3);"></i>
                </div>
                <div class="card-body text-center">
                    <span class="badge badge-secondary mb-2">Chairman</span>
                    <h4>Mr. Sompal Rana</h4>
                    <p style="color: var(--text-muted); font-size: 0.9375rem;">Leading with vision and dedication to
                        nurture future leaders</p>
                </div>
            </div>

            <div class="card">
                <div style="height: 250px; overflow: hidden;">
                    <img src="assets/images/WhatsApp Image 2025-12-19 at 18.42.31.jpeg" alt="Director"
                        style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="card-body text-center">
                    <span class="badge badge-primary mb-2">Director</span>
                    <h4>Mr. Avesh Gautam</h4>
                    <p style="color: var(--text-muted); font-size: 0.9375rem;">Driving excellence and innovation in
                        education</p>
                </div>
            </div>

            <div class="card">
                <div
                    style="height: 250px; overflow: hidden; background: linear-gradient(135deg, #EC4899, #DB2777); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-user-graduate" style="font-size: 5rem; color: rgba(255,255,255,0.3);"></i>
                </div>
                <div class="card-body text-center">
                    <span class="badge badge-secondary mb-2">Principal</span>
                    <h4>Mr. Narinder Rana</h4>
                    <p style="color: var(--text-muted); font-size: 0.9375rem;">Committed to academic excellence and
                        student development</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Vision -->
<section class="section bg-white">
    <div class="container">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
            <div class="card"
                style="border: 2px solid rgba(245, 158, 11, 0.2); background: linear-gradient(135deg, rgba(245, 158, 11, 0.05), rgba(245, 158, 11, 0.1));">
                <div class="card-body" style="padding: 2rem;">
                    <div class="icon-box icon-box-lg mb-6"
                        style="background: linear-gradient(135deg, #F59E0B, #D97706);"><i class="fas fa-eye"></i></div>
                    <h3>Our Vision</h3>
                    <p style="color: var(--text-body); line-height: 1.8; margin-top: 1rem;">
                        Our vision is to nurture disciplined, ethical, confident, and creative learners who excel
                        academically while remaining deeply rooted in moral and cultural values. We strive to develop
                        globally aware individuals who are prepared to lead society with integrity, responsibility, and
                        compassion.
                    </p>
                </div>
            </div>

            <div class="card"
                style="border: 2px solid rgba(37, 99, 235, 0.2); background: linear-gradient(135deg, rgba(37, 99, 235, 0.05), rgba(37, 99, 235, 0.1));">
                <div class="card-body" style="padding: 2rem;">
                    <div class="icon-box icon-box-lg mb-6"><i class="fas fa-bullseye"></i></div>
                    <h3>Our Mission & Objectives</h3>
                    <p style="color: var(--text-body); line-height: 1.8; margin-top: 1rem;">
                        The mission of Lotus Valley International School is to ensure the holistic development of every
                        child. Our key objectives include:
                    </p>
                    <ul
                        style="list-style-type: disc; margin-left: 1.5rem; color: var(--text-body); margin-top: 0.5rem; line-height: 1.6;">
                        <li>Delivering high-quality, modern, and result-oriented education</li>
                        <li>Integrating moral values, discipline, and character-building with academics</li>
                        <li>Developing self-confidence, leadership skills, decision-making abilities, and creative
                            thinking</li>
                        <li>Promoting physical, mental, and emotional well-being through sports and co-curricular
                            activities</li>
                        <li>Encouraging digital learning and technological competence</li>
                        <li>Creating a positive learning environment through active collaboration between teachers,
                            parents, and the community</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Academic Excellence -->
        <div class="section-header">
            <h2>Academic Excellence</h2>
            <p>Our commitment to educational quality</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            <div class="feature-card">
                <div class="icon-box icon-box-lg"
                    style="margin: 0 auto 1.5rem; background: linear-gradient(135deg, #14B8A6, #0D9488);">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <h4>Expert Faculty</h4>
                <p>Highly qualified, experienced, and dedicated teaching faculty</p>
            </div>
            <div class="feature-card">
                <div class="icon-box icon-box-lg"
                    style="margin: 0 auto 1.5rem; background: linear-gradient(135deg, #F59E0B, #D97706);">
                    <i class="fas fa-shapes"></i>
                </div>
                <h4>Active Learning</h4>
                <p>Student-centered and activity-based teaching methodologies</p>
            </div>
            <div class="feature-card">
                <div class="icon-box icon-box-lg"
                    style="margin: 0 auto 1.5rem; background: linear-gradient(135deg, #EC4899, #DB2777);">
                    <i class="fas fa-tasks"></i>
                </div>
                <h4>Personalized Attention</h4>
                <p>Continuous assessment and personalized attention to every learner</p>
            </div>
            <div class="feature-card">
                <div class="icon-box icon-box-lg"
                    style="margin: 0 auto 1.5rem; background: linear-gradient(135deg, #10B981, #059669);">
                    <i class="fas fa-language"></i>
                </div>
                <h4>English Focus</h4>
                <p>Strong focus on English communication skills</p>
            </div>
            <div class="feature-card">
                <div class="icon-box icon-box-lg"
                    style="margin: 0 auto 1.5rem; background: linear-gradient(135deg, #8B5CF6, #7C3AED);">
                    <i class="fas fa-heart"></i>
                </div>
                <h4>Values & Life Skills</h4>
                <p>Emphasis on value education and life skills</p>
            </div>
        </div>

        <!-- Our Belief -->
        <div class="card bg-light p-8 text-center rounded-xl border border-gray-200 shadow-sm mx-auto max-w-4xl">
            <div class="icon-box icon-box-lg mx-auto mb-4"
                style="background: linear-gradient(135deg, #6366F1, #4F46E5);"><i class="fas fa-star"></i></div>
            <h3 class="text-2xl font-bold mb-4 text-gray-800">Our Belief</h3>
            <p class="text-lg text-gray-600 leading-relaxed">
                At Lotus Valley International School, we believe that every child is unique and full of potential. Our
                commitment is to identify, nurture, and develop each student’s talents while preparing them to become
                responsible, confident, and successful individuals.
            </p>
        </div>

    </div>
</section>

<!-- Messages Section -->
<section class="section bg-light">
    <div class="container">
        <div class="section-header">
            <span class="badge badge-primary">Messages</span>
            <h2>Words from Our Leaders</h2>
        </div>

        <!-- Chairman's Message -->
        <div class="card mb-6" style="background: linear-gradient(135deg, #0D9488, #0F766E); color: white;">
            <div class="card-body" style="padding: 2rem;">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6" style="align-items: center;">
                    <div class="text-center">
                        <div
                            style="width: 150px; height: 150px; border-radius: 50%; border: 4px solid rgba(255,255,255,0.3); margin: 0 auto; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user-tie" style="font-size: 3.5rem; color: rgba(255,255,255,0.5);"></i>
                        </div>
                    </div>
                    <div style="grid-column: span 3;">
                        <div class="flex items-center gap-3 mb-4">
                            <i class="fas fa-quote-left" style="font-size: 1.5rem; opacity: 0.5;"></i>
                            <h3 style="color: white;">Chairman's Message</h3>
                        </div>
                        <p style="opacity: 0.9; line-height: 1.8; margin-bottom: 1rem;">
                            It gives me immense pleasure to welcome you to Lotus Valley International School. Education
                            is the most powerful tool to shape the future of a child and, through this institution, our
                            aim is to provide an environment where learning goes beyond classrooms and textbooks.
                        </p>
                        <p style="opacity: 0.9; line-height: 1.8; margin-bottom: 1rem;">
                            Under the guidance of Lotus Valley Social & Educational Trust, we have envisioned a school
                            that nurtures academic excellence, moral values, discipline, and self-confidence. Our focus
                            is on creating responsible citizens who are prepared to face global challenges while staying
                            rooted in Indian culture and ethics.
                        </p>
                        <p style="opacity: 0.9; line-height: 1.8;">
                            I firmly believe that with the collective efforts of dedicated teachers, supportive parents,
                            and enthusiastic students, Lotus Valley International School will set new benchmarks in
                            quality education. I invite you to be a part of this meaningful journey of learning and
                            growth.
                        </p>
                        <div
                            style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.2);">
                            <strong style="font-size: 1.125rem;">Mr. Sompal Rana</strong>
                            <div style="opacity: 0.8; font-size: 0.875rem;">Chairman</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Director's Message -->
        <div class="card mb-6" style="background: linear-gradient(135deg, #F59E0B, #D97706); color: white;">
            <div class="card-body" style="padding: 2rem;">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6" style="align-items: center;">
                    <div class="text-center">
                        <img src="assets/images/WhatsApp Image 2025-12-19 at 18.42.31.jpeg" alt="Director"
                            style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 4px solid rgba(255,255,255,0.3); margin: 0 auto;">
                    </div>
                    <div style="grid-column: span 3;">
                        <div class="flex items-center gap-3 mb-4">
                            <i class="fas fa-quote-left" style="font-size: 1.5rem; opacity: 0.5;"></i>
                            <h3 style="color: white;">Director's Message</h3>
                        </div>
                        <p style="opacity: 0.9; line-height: 1.8; margin-bottom: 1rem;">
                            At Lotus Valley International School, we believe that every child has unique potential. Our
                            responsibility is to identify that potential and provide the right guidance, resources, and
                            opportunities to help each student grow into a confident and capable individual.
                        </p>
                        <p style="opacity: 0.9; line-height: 1.8; margin-bottom: 1rem;">
                            We emphasize modern teaching methodologies, digital learning, and activity-based education
                            to ensure that students develop critical thinking, creativity, and problem-solving skills.
                            Along with academic excellence, we focus on character building, discipline, and life skills.
                        </p>
                        <p style="opacity: 0.9; line-height: 1.8;">
                            Our commitment is to continuously improve the quality of education and create a safe,
                            inclusive, and inspiring learning environment. Together, we strive to prepare our students
                            not only for examinations, but for life.
                        </p>
                        <div
                            style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.2);">
                            <strong style="font-size: 1.125rem;">Mr. Avesh Gautam</strong>
                            <div style="opacity: 0.8; font-size: 0.875rem;">Director</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Principal's Message -->
        <div class="card" style="background: linear-gradient(135deg, #EC4899, #DB2777); color: white;">
            <div class="card-body" style="padding: 2rem;">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6" style="align-items: center;">
                    <div class="text-center">
                        <div
                            style="width: 150px; height: 150px; border-radius: 50%; border: 4px solid rgba(255,255,255,0.3); margin: 0 auto; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user-graduate"
                                style="font-size: 3.5rem; color: rgba(255,255,255,0.5);"></i>
                        </div>
                    </div>
                    <div style="grid-column: span 3;">
                        <div class="flex items-center gap-3 mb-4">
                            <i class="fas fa-quote-left" style="font-size: 1.5rem; opacity: 0.5;"></i>
                            <h3 style="color: white;">Principal's Message</h3>
                        </div>
                        <p style="opacity: 0.9; line-height: 1.8; margin-bottom: 1rem;">
                            It is my privilege to lead Lotus Valley International School, an institution dedicated to
                            holistic education and all-round development of students. We aim to create a joyful learning
                            environment where curiosity is encouraged, talents are nurtured, and values are
                            strengthened.
                        </p>
                        <p style="opacity: 0.9; line-height: 1.8; margin-bottom: 1rem;">
                            Our experienced faculty members work tirelessly to provide personalized attention and
                            academic support to every child. We strongly believe in the partnership between school and
                            parents, as together we can guide students toward excellence.
                        </p>
                        <p style="opacity: 0.9; line-height: 1.8;">
                            At Lotus Valley International School, we are committed to shaping young minds into confident
                            learners, responsible individuals, and compassionate human beings. I warmly welcome you to
                            join us in this journey of educational excellence.
                        </p>
                        <div
                            style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.2);">
                            <strong style="font-size: 1.125rem;">Mr. Narinder Rana</strong>
                            <div style="opacity: 0.8; font-size: 0.875rem;">Principal</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Facilities Section -->
<section class="section bg-white">
    <div class="container">
        <div class="section-header">
            <span class="badge badge-primary">Infrastructure</span>
            <h2>Our Facilities</h2>
            <p>Lotus Valley International School provides a safe, clean, and well-equipped environment to support
                effective learning and all-round development</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="feature-card" style="text-align: left; display: flex; gap: 1rem; align-items: flex-start;">
                <div class="icon-box icon-box-sm" style="flex-shrink: 0;"><i class="fas fa-chalkboard"></i></div>
                <div>
                    <h4>Smart Classrooms</h4>
                    <p>Spacious, well-ventilated, and smart classrooms with digital boards and audio-visual aids</p>
                </div>
            </div>

            <div class="feature-card" style="text-align: left; display: flex; gap: 1rem; align-items: flex-start;">
                <div class="icon-box icon-box-sm" style="flex-shrink: 0; background: #F59E0B;"><i
                        class="fas fa-desktop"></i></div>
                <div>
                    <h4>Labs</h4>
                    <p>Science laboratories and computer lab well-equipped for practical learning</p>
                </div>
            </div>

            <div class="feature-card" style="text-align: left; display: flex; gap: 1rem; align-items: flex-start;">
                <div class="icon-box icon-box-sm" style="flex-shrink: 0; background: #EC4899;"><i
                        class="fas fa-book"></i></div>
                <div>
                    <h4>Library</h4>
                    <p>Well-stocked library and reading zone for fostering reading habits</p>
                </div>
            </div>

            <div class="feature-card" style="text-align: left; display: flex; gap: 1rem; align-items: flex-start;">
                <div class="icon-box icon-box-sm" style="flex-shrink: 0; background: #10B981;"><i
                        class="fas fa-running"></i></div>
                <div>
                    <h4>Sports Facilities</h4>
                    <p>Large playground and indoor sports facilities for physical development</p>
                </div>
            </div>

            <div class="feature-card" style="text-align: left; display: flex; gap: 1rem; align-items: flex-start;">
                <div class="icon-box icon-box-sm" style="flex-shrink: 0; background: #8B5CF6;"><i
                        class="fas fa-spa"></i></div>
                <div>
                    <h4>Wellness</h4>
                    <p>Yoga, meditation, and personality development sessions</p>
                </div>
            </div>

            <div class="feature-card" style="text-align: left; display: flex; gap: 1rem; align-items: flex-start;">
                <div class="icon-box icon-box-sm" style="flex-shrink: 0; background: #6366F1;"><i
                        class="fas fa-bus"></i></div>
                <div>
                    <h4>Transport & Safety</h4>
                    <p>School transportation (Bus Facility) and CCTV surveillance for campus safety</p>
                </div>
            </div>
            <div class="feature-card" style="text-align: left; display: flex; gap: 1rem; align-items: flex-start;">
                <div class="icon-box icon-box-sm" style="flex-shrink: 0; background: #06b6d4;"><i
                        class="fas fa-tint"></i></div>
                <div>
                    <h4>Hygiene</h4>
                    <p>Clean drinking water and hygienic sanitation facilities</p>
                </div>
            </div>
            <div class="feature-card" style="text-align: left; display: flex; gap: 1rem; align-items: flex-start;">
                <div class="icon-box icon-box-sm" style="flex-shrink: 0; background: #ec4899;"><i
                        class="fas fa-microphone"></i></div>
                <div>
                    <h4>Cultural Stage</h4>
                    <p>Dedicated stage for cultural and academic events</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Co-Curricular Activities -->
<section class="section bg-light">
    <div class="container">
        <div class="section-header">
            <h2>Co-Curricular Activities</h2>
            <p>To ensure the all-round development of students, the school places equal importance on co-curricular
                activities</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="card p-6 flex items-center gap-4 bg-white shadow-sm hover:shadow-md transition-shadow">
                <i class="fas fa-running text-2xl text-teal-600"></i>
                <span class="font-semibold text-lg">Sports and regular physical training</span>
            </div>
            <div class="card p-6 flex items-center gap-4 bg-white shadow-sm hover:shadow-md transition-shadow">
                <i class="fas fa-paint-brush text-2xl text-teal-600"></i>
                <span class="font-semibold text-lg">Music, dance, drawing, and painting</span>
            </div>
            <div class="card p-6 flex items-center gap-4 bg-white shadow-sm hover:shadow-md transition-shadow">
                <i class="fas fa-microphone-alt text-2xl text-teal-600"></i>
                <span class="font-semibold text-lg">Debates, speeches, and writing</span>
            </div>
            <div class="card p-6 flex items-center gap-4 bg-white shadow-sm hover:shadow-md transition-shadow">
                <i class="fas fa-spa text-2xl text-teal-600"></i>
                <span class="font-semibold text-lg">Yoga, meditation, and wellness</span>
            </div>
            <div class="card p-6 flex items-center gap-4 bg-white shadow-sm hover:shadow-md transition-shadow">
                <i class="fas fa-calendar-alt text-2xl text-teal-600"></i>
                <span class="font-semibold text-lg">Festivals and cultural events</span>
            </div>
        </div>
    </div>
</section>

<!-- Message to Parents -->
<section class="section bg-white text-center">
    <div class="container max-w-4xl">
        <div class="icon-box icon-box-xl mx-auto mb-6"
            style="background: linear-gradient(135deg, #F59E0B, #D97706); width: 80px; height: 80px; font-size: 2.5rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
            <i class="fas fa-envelope-open-text"></i>
        </div>
        <h2 class="mb-6">Message to Parents</h2>
        <p class="text-lg text-gray-600 leading-relaxed mb-8">
            We warmly invite parents to partner with us in shaping a bright future for their children by choosing Lotus
            Valley International School—where education is not limited to academics but is a lifelong journey of growth
            and character building.
        </p>
        <blockquote class="text-2xl font-serif text-teal-700 italic">
            “Education that builds character, knowledge that shapes the future.”
        </blockquote>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
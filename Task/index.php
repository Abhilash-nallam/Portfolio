<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/csrf.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>LeadDesk Mini &mdash; Never Lose a Lead Again</title>
<meta name="description" content="LeadDesk Mini is a lightweight CRM that captures enquiries from your website and helps your team follow up faster.">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- ============ NAVBAR ============ -->
<nav class="navbar navbar-expand-lg ld-navbar" id="ldNavbar">
    <div class="container">
        <a class="navbar-brand" href="#top">
            <span class="brand-icon"><i class="fa-solid fa-chart-line"></i></span>
            LeadDesk <span>Mini</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#ldNavMenu">
            <i class="fa-solid fa-bars text-white"></i>
        </button>
        <div class="collapse navbar-collapse" id="ldNavMenu">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="#why">Why Us</a></li>
                <li class="nav-item"><a class="nav-link" href="#testimonials">Testimonials</a></li>
                <li class="nav-item"><a class="nav-link" href="#pricing">Pricing</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                <li class="nav-item ms-lg-2"><a class="btn-nav-cta" href="admin/login.php">Admin Login</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- ============ HERO ============ -->
<header class="hero" id="top">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="hero-badge"><i class="fa-solid fa-bolt"></i> Built for fast-moving sales teams</span>
                <h1>Turn every enquiry into a <span class="grad-text">closed deal.</span></h1>
                <p class="lead">LeadDesk Mini captures leads straight from your website and gives your team one clean pipeline to track, follow up, and close &mdash; no spreadsheets required.</p>
                <div class="hero-actions">
                    <a href="#contact" class="btn-hero-primary"><i class="fa-solid fa-paper-plane me-2"></i>Get Your Free Enquiry Form</a>
                    <a href="#services" class="btn-hero-secondary">Explore Services</a>
                </div>

                <!-- Signature element: live pipeline strip -->
                <div class="pipeline-strip">
                    <div class="pipeline-label"><i class="fa-solid fa-diagram-project me-1"></i> Your Lead Pipeline, Live</div>
                    <div class="pipeline-track"><div class="pipeline-fill"></div></div>
                    <div class="pipeline-stages">
                        <div class="pipeline-stage is-active"><div class="dot"></div>New</div>
                        <div class="pipeline-stage"><div class="dot"></div>Contacted</div>
                        <div class="pipeline-stage"><div class="dot"></div>Closed</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="pipeline-strip" style="max-width:none;">
                            <div class="stat-big" style="font-family:var(--font-display);font-weight:800;font-size:2.2rem;color:var(--teal);">2.4x</div>
                            <div class="stat-cap" style="color:rgba(255,255,255,0.6);font-size:0.85rem;">faster average response time</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="pipeline-strip" style="max-width:none;">
                            <div class="stat-big" style="font-family:var(--font-display);font-weight:800;font-size:2.2rem;color:var(--amber);">98%</div>
                            <div class="stat-cap" style="color:rgba(255,255,255,0.6);font-size:0.85rem;">of leads never fall through the cracks</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="pipeline-strip" style="max-width:none;">
                            <div class="pipeline-label">Trusted setup for teams of</div>
                            <div style="display:flex;justify-content:space-between;color:rgba(255,255,255,0.75);font-weight:700;font-size:0.9rem;">
                                <span><i class="fa-solid fa-shop me-1"></i> Local Businesses</span>
                                <span><i class="fa-solid fa-building me-1"></i> Agencies</span>
                                <span><i class="fa-solid fa-store me-1"></i> Startups</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- ============ SERVICES ============ -->
<section class="section-pad" id="services">
    <div class="container">
        <div class="text-center mx-auto mb-5 reveal" style="max-width:640px;">
            <span class="section-eyebrow">What We Offer</span>
            <h2 class="section-title">Everything you need to manage leads end-to-end</h2>
            <p class="section-sub mx-auto">From capture to close, LeadDesk Mini's team handles the full workflow so your enquiries never sit unanswered.</p>
        </div>

        <div class="row g-4">
            <?php
            $services = [
                ['icon' => 'fa-solid fa-inbox', 'title' => 'Lead Capture Forms', 'desc' => 'Embed validated, spam-protected enquiry forms anywhere on your site.'],
                ['icon' => 'fa-solid fa-gauge-high', 'title' => 'Admin Dashboard', 'desc' => 'A single dark-themed dashboard to view, search, and sort every lead.'],
                ['icon' => 'fa-solid fa-route', 'title' => 'Pipeline Tracking', 'desc' => 'Move leads through New, Contacted, and Closed with one click.'],
                ['icon' => 'fa-solid fa-shield-halved', 'title' => 'Secure by Default', 'desc' => 'Hashed passwords, CSRF tokens, and prepared statements throughout.'],
                ['icon' => 'fa-solid fa-magnifying-glass-chart', 'title' => 'Search & Reporting', 'desc' => 'Instantly filter leads by name, email, message, or status.'],
                ['icon' => 'fa-solid fa-mobile-screen-button', 'title' => 'Fully Responsive', 'desc' => 'A clean experience on desktop, tablet, and mobile for your whole team.'],
            ];
            foreach ($services as $s): ?>
                <div class="col-md-6 col-lg-4 reveal">
                    <div class="service-card">
                        <div class="service-icon"><i class="<?= $s['icon'] ?>"></i></div>
                        <h3><?= e($s['title']) ?></h3>
                        <p><?= e($s['desc']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============ WHY CHOOSE US ============ -->
<section class="section-pad why-section" id="why">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <span class="section-eyebrow">Why Choose Us</span>
                <h2 class="section-title mb-4">Built to be fast, secure, and simple to run</h2>

                <div class="why-item reveal">
                    <div class="why-num">01</div>
                    <div><h4>Zero learning curve</h4><p>Your team is managing leads within minutes of logging in &mdash; no training manual needed.</p></div>
                </div>
                <div class="why-item reveal">
                    <div class="why-num">02</div>
                    <div><h4>Security-first engineering</h4><p>Prepared statements, hashed passwords, and CSRF protection are baked into every form.</p></div>
                </div>
                <div class="why-item reveal">
                    <div class="why-num">03</div>
                    <div><h4>Status updates without reloads</h4><p>Change a lead's status and see it reflected instantly &mdash; powered by lightweight AJAX.</p></div>
                </div>
            </div>
            <div class="col-lg-6 reveal">
                <div class="why-visual">
                    <div class="stat-big">100%</div>
                    <div class="stat-cap">Prepared statements on every database query</div>
                    <div class="stat-big">&lt; 2min</div>
                    <div class="stat-cap">Average setup time for a new admin account</div>
                    <div class="stat-big">24/7</div>
                    <div class="stat-cap">Your enquiry form keeps capturing leads around the clock</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============ TESTIMONIALS ============ -->
<section class="section-pad" id="testimonials">
    <div class="container">
        <div class="text-center mx-auto mb-5 reveal" style="max-width:640px;">
            <span class="section-eyebrow">Testimonials</span>
            <h2 class="section-title">Teams that stopped losing leads</h2>
        </div>
        <div class="row g-4">
            <?php
            $testimonials = [
                ['name' => 'Priya Nair', 'role' => 'Founder, Coastline Interiors', 'text' => 'We used to reply to enquiries a week late. Now every new lead lands in one dashboard and nothing slips through.'],
                ['name' => 'Karthik Iyer', 'role' => 'Sales Lead, Bright Web Studio', 'text' => 'The status pipeline is exactly what our small team needed. Simple, fast, and it just works.'],
                ['name' => 'Meera Suresh', 'role' => 'Operations, Vantage Realty', 'text' => 'Setup took an afternoon. Our follow-up time dropped noticeably in the first week alone.'],
            ];
            foreach ($testimonials as $t): ?>
                <div class="col-md-6 col-lg-4 reveal">
                    <div class="testimonial-card">
                        <div class="quote-mark"><i class="fa-solid fa-quote-left"></i></div>
                        <div class="testimonial-stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                        <p class="quote"><?= e($t['text']) ?></p>
                        <div class="testimonial-person">
                            <div class="testimonial-avatar"><?= e(mb_substr($t['name'], 0, 1)) ?></div>
                            <div><div class="t-name"><?= e($t['name']) ?></div><div class="t-role"><?= e($t['role']) ?></div></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============ PRICING ============ -->
<section class="section-pad" id="pricing" style="background:var(--paper);">
    <div class="container">
        <div class="text-center mx-auto mb-5 reveal" style="max-width:640px;">
            <span class="section-eyebrow">Pricing</span>
            <h2 class="section-title">Plans that grow with your pipeline</h2>
            <p class="section-sub mx-auto">Simple, transparent pricing &mdash; upgrade any time as your lead volume grows.</p>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-md-6 col-lg-4 reveal">
                <div class="pricing-card">
                    <div class="pricing-tier">Basic</div>
                    <p class="pricing-desc">For solo founders getting their first leads online.</p>
                    <div class="pricing-price">₹999<small>/mo</small></div>
                    <ul class="pricing-features">
                        <li><i class="fa-solid fa-circle-check"></i> Up to 100 leads / month</li>
                        <li><i class="fa-solid fa-circle-check"></i> 1 admin account</li>
                        <li><i class="fa-solid fa-circle-check"></i> Status pipeline tracking</li>
                        <li><i class="fa-solid fa-circle-check"></i> Email support</li>
                    </ul>
                    <a href="#contact" class="btn-pricing">Choose Basic</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 reveal">
                <div class="pricing-card featured">
                    <span class="pricing-badge">Most Popular</span>
                    <div class="pricing-tier">Professional</div>
                    <p class="pricing-desc">For growing teams that need faster follow-up.</p>
                    <div class="pricing-price">₹2,499<small>/mo</small></div>
                    <ul class="pricing-features">
                        <li><i class="fa-solid fa-circle-check"></i> Up to 1,000 leads / month</li>
                        <li><i class="fa-solid fa-circle-check"></i> 5 admin accounts</li>
                        <li><i class="fa-solid fa-circle-check"></i> AJAX status updates</li>
                        <li><i class="fa-solid fa-circle-check"></i> Search &amp; pagination</li>
                        <li><i class="fa-solid fa-circle-check"></i> Priority support</li>
                    </ul>
                    <a href="#contact" class="btn-pricing">Choose Professional</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 reveal">
                <div class="pricing-card">
                    <div class="pricing-tier">Enterprise</div>
                    <p class="pricing-desc">For teams with high enquiry volume and custom needs.</p>
                    <div class="pricing-price">Custom</div>
                    <ul class="pricing-features">
                        <li><i class="fa-solid fa-circle-check"></i> Unlimited leads</li>
                        <li><i class="fa-solid fa-circle-check"></i> Unlimited admin accounts</li>
                        <li><i class="fa-solid fa-circle-check"></i> Custom integrations</li>
                        <li><i class="fa-solid fa-circle-check"></i> Dedicated onboarding</li>
                    </ul>
                    <a href="#contact" class="btn-pricing">Contact Sales</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============ CONTACT / LEAD FORM ============ -->
<section class="section-pad contact-section" id="contact">
    <div class="container">
        <div class="text-center mx-auto mb-5 reveal" style="max-width:640px;">
            <span class="section-eyebrow">Get In Touch</span>
            <h2 class="section-title">Tell us about your project</h2>
            <p class="section-sub mx-auto">Fill out the form and our team will get back to you within one business day.</p>
        </div>

        <div class="row justify-content-center reveal">
            <div class="col-lg-10">
                <div class="contact-card">
                    <div class="row g-0">
                        <div class="col-lg-5">
                            <div class="contact-info-panel">
                                <h3>Let's build your pipeline</h3>
                                <p>Share a few details about your business and budget, and we'll follow up with next steps.</p>
                                <div class="contact-info-item"><i class="fa-solid fa-envelope"></i> <span>hello@leaddeskmini.com</span></div>
                                <div class="contact-info-item"><i class="fa-solid fa-phone"></i> <span>+91 98765 43210</span></div>
                                <div class="contact-info-item"><i class="fa-solid fa-location-dot"></i> <span>Warangal, Telangana, India</span></div>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="contact-form-panel">
                                <div id="formAlert" class="alert form-alert d-none" role="alert"></div>
                                <form id="leadForm" novalidate>
                                    <?= csrf_field() ?>
                                    <input type="text" name="website" class="hp-field" tabindex="-1" autocomplete="off">

                                    <div class="mb-3">
                                        <label class="form-label" for="name">Full Name</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Your name" required minlength="2">
                                        <div class="invalid-feedback">Please enter your full name.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="email">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" required>
                                        <div class="invalid-feedback">Please enter a valid email address.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="budget">Budget Range</label>
                                        <select class="form-select" id="budget" name="budget" required>
                                            <option value="" selected disabled>Select a budget range</option>
                                            <option value="Under ₹50,000">Under ₹50,000</option>
                                            <option value="₹50,000 - ₹1,00,000">₹50,000 &ndash; ₹1,00,000</option>
                                            <option value="₹1,00,000 - ₹3,00,000">₹1,00,000 &ndash; ₹3,00,000</option>
                                            <option value="Above ₹3,00,000">Above ₹3,00,000</option>
                                        </select>
                                        <div class="invalid-feedback">Please select a budget range.</div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label" for="message">Project Details</label>
                                        <textarea class="form-control" id="message" name="message" rows="4" placeholder="Tell us a bit about what you need..." required minlength="10"></textarea>
                                        <div class="invalid-feedback">Please share a few details (min. 10 characters).</div>
                                    </div>

                                    <button type="submit" class="btn-submit-lead" id="submitLeadBtn">
                                        <span class="btn-text"><i class="fa-solid fa-paper-plane me-2"></i>Send Enquiry</span>
                                        <span class="btn-spinner d-none"><span class="spinner-border spinner-border-sm me-2"></span>Sending...</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============ FOOTER ============ -->
<footer class="site-footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <h5><i class="fa-solid fa-chart-line me-2"></i>LeadDesk Mini</h5>
                <p style="max-width:280px;">A lightweight CRM for capturing and managing leads &mdash; built with PHP, MySQL, and Bootstrap.</p>
                <div class="footer-social">
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                    <a href="#"><i class="fa-brands fa-twitter"></i></a>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <h5>Product</h5>
                <p><a href="#services">Services</a></p>
                <p><a href="#pricing">Pricing</a></p>
                <p><a href="#testimonials">Testimonials</a></p>
            </div>
            <div class="col-6 col-lg-2">
                <h5>Company</h5>
                <p><a href="#why">Why Us</a></p>
                <p><a href="#contact">Contact</a></p>
                <p><a href="admin/login.php">Admin Login</a></p>
            </div>
            <div class="col-lg-4">
                <h5>Stay Updated</h5>
                <p>Follow our build progress and product updates.</p>
            </div>
        </div>
        <div class="footer-bottom text-center">
            &copy; <?= date('Y') ?> LeadDesk Mini. All rights reserved. &middot;
            Built for <a href="https://digitalheroesco.com" target="_blank" rel="noopener">Digital Heroes Training Task</a>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>

@extends('layouts.landing')

@section('content')
<div class="pub-root">

    {{-- Gov Bar --}}
    <div class="pub-govbar">
        <span>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;margin-right:4px">
                <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/>
            </svg>
            Republic of the Philippines &nbsp;·&nbsp; Province of Laguna
        </span>
        <span>Official Website of the Municipality of Pagsanjan</span>
    </div>

    {{-- Navbar --}}
    <nav class="pub-nav">
        <div class="pub-logo">
            <div class="pub-logo-seal">
                <img src="{{ asset('images/municipal-of-pagsanjan-logo.jpg') }}" alt="Pagsanjan Logo"
                     onerror="this.style.display='none'"
                     style="width:36px;height:36px;border-radius:50%;object-fit:cover">
            </div>
            <div>
                <span class="pub-logo-name">Pagsanjan, Laguna</span>
                <span class="pub-logo-sub">Municipal Government</span>
            </div>
        </div>
        <div class="pub-nav-links">
            <a href="#services">Services</a>
            <a href="#announcements">Announcements</a>
            <a href="#about">About</a>
            <a href="#contact">Contact</a>
        </div>
        <a href="{{ route('login') }}" class="pub-hr-btn">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
            Employee Portal
        </a>
    </nav>

    {{-- Hero --}}
    <section class="pub-hero">
        <div class="pub-hero-inner">
            <div class="pub-hero-text">
                <div class="pub-hero-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:6px">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                    Official Municipal Government Portal
                </div>
                <h1 class="pub-hero-title">
                    Smarter HR for<br>
                    <span class="pub-hero-highlight">Pagsanjan LGU</span>
                </h1>
                <p class="pub-hero-sub">
                    PRIME HRIS streamlines employee management, payroll, attendance, and leave tracking
                    for the Municipality of Pagsanjan, Laguna.
                </p>
                <div class="pub-hero-actions">
                    <a href="{{ route('login') }}" class="pub-btn-primary">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        Access HR Portal
                    </a>
                    <button class="pub-btn-ghost" onclick="document.getElementById('chatbot-window').style.display='flex'">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                        Ask Our AI Assistant
                    </button>
                </div>
            </div>
            <div class="pub-hero-card">
                <div class="pub-hero-card-header">
                    <div class="pub-hero-card-dot active"></div>
                    <span>Municipal Services Portal</span>
                </div>
                <div class="pub-hero-stats">
                    <div class="pub-hstat">
                        <span class="pub-hstat-val">17</span>
                        <span class="pub-hstat-label">Offices &amp; Departments</span>
                    </div>
                    <div class="pub-hstat-divider"></div>
                    <div class="pub-hstat">
                        <span class="pub-hstat-val">348</span>
                        <span class="pub-hstat-label">Government Personnel</span>
                    </div>
                    <div class="pub-hstat-divider"></div>
                    <div class="pub-hstat">
                        <span class="pub-hstat-val">24/7</span>
                        <span class="pub-hstat-label">AI Chatbot Support</span>
                    </div>
                </div>
                <div class="pub-hero-card-tags">
                    <span class="pub-tag">✓ BIR Compliant</span>
                    <span class="pub-tag">✓ GSIS Ready</span>
                    <span class="pub-tag">✓ CSC Accredited</span>
                    <span class="pub-tag">✓ ARTA Compliant</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Services --}}
    <section class="pub-section" id="services">
        <div class="pub-section-inner">
            <div class="pub-section-head">
                <span class="pub-eyebrow">WHAT WE OFFER</span>
                <h2>Complete HR Management Suite</h2>
                <p>Everything you need to manage government personnel efficiently in one platform.</p>
            </div>
            <div class="pub-services-grid">
                @php
                $services = [
                    ['svg'=>'users',    'title'=>'Employee Management',   'desc'=>'Maintain complete employee records including personal information, employment history, and document management.'],
                    ['svg'=>'payroll',  'title'=>'Payroll Processing',    'desc'=>'Automated payroll computation with mandatory compensations, salary grades, and net pay calculations.'],
                    ['svg'=>'clock',    'title'=>'Attendance Tracking',   'desc'=>'Monitor daily time records, overtime, tardiness, and generate attendance reports with ease.'],
                    ['svg'=>'clipboard','title'=>'Leave Management',      'desc'=>'Process leave applications, track leave balances, and manage leave types for all employees.'],
                    ['svg'=>'building', 'title'=>'Departments & Positions','desc'=>'Organize your workforce by departments and positions with salary grade management.'],
                    ['svg'=>'calendar', 'title'=>'Work Schedules',        'desc'=>'Configure flexible work schedules, grace periods, and holiday calendars for your organization.'],
                ];
                $svgs = [
                    'users'     => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
                    'payroll'   => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>',
                    'clock'     => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
                    'clipboard' => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/></svg>',
                    'building'  => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
                    'calendar'  => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
                ];
                @endphp
                @foreach($services as $s)
                <div class="pub-service-card">
                    <div class="pub-service-icon">{!! $svgs[$s['svg']] !!}</div>
                    <h4>{{ $s['title'] }}</h4>
                    <p>{{ $s['desc'] }}</p>
                    <span class="pub-service-link">Learn more →</span>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Announcements --}}
    <section class="pub-section alt" id="announcements">
        <div class="pub-section-inner">
            <div class="pub-section-head">
                <span class="pub-eyebrow">LATEST UPDATES</span>
                <h2>Announcements &amp; Advisories</h2>
                <p>Stay informed with the latest news from the Municipal Government.</p>
            </div>
            <div class="pub-announcements">
                @php
                $announcements = [
                    ['date'=>'Jun 20, 2025','tag'=>'Advisory','title'=>'Schedule of Payment for Real Property Tax — 2nd Quarter 2025'],
                    ['date'=>'Jun 18, 2025','tag'=>'Event',   'title'=>'Pagsanjan Founding Anniversary Celebration — June 25, 2025'],
                    ['date'=>'Jun 15, 2025','tag'=>'Program', 'title'=>'MSWD Livelihood Training Program — Open for Registration'],
                    ['date'=>'Jun 10, 2025','tag'=>'Notice',  'title'=>'Water Interruption Advisory — Barangay Pinagsanjan Area'],
                ];
                @endphp
                @foreach($announcements as $a)
                <div class="pub-announce-item">
                    <div class="pub-announce-left">
                        <span class="pub-announce-tag {{ strtolower($a['tag']) }}">{{ $a['tag'] }}</span>
                        <p class="pub-announce-title">{{ $a['title'] }}</p>
                    </div>
                    <span class="pub-announce-date">{{ $a['date'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- About --}}
    <section class="pub-section" id="about">
        <div class="pub-section-inner">
            <div class="pub-section-head">
                <span class="pub-eyebrow">ABOUT THE MUNICIPALITY</span>
                <h2>Municipal Government of Pagsanjan</h2>
                <p>A brief overview of the municipality, its leadership, and its commitment to public service.</p>
            </div>

            <div class="pub-about-hero">
                <div class="pub-about-hero-text">
                    <div class="pub-about-hero-badge">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:6px">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                        </svg>
                        Pagsanjan, Laguna
                    </div>
                    <h3>Home of the Famous<br><span>Pagsanjan Falls</span></h3>
                    <p>
                        Pagsanjan is a <strong>first-class municipality</strong> in the Province of Laguna, Philippines —
                        known as the <strong>"Shooting the Rapids" capital</strong>. Composed of 16 barangays, it serves
                        a population of over 40,000 residents across Region IV-A (CALABARZON).
                    </p>
                    <p>
                        The Municipal Government is committed to transparent, efficient, and responsive governance
                        through its 17 offices and departments, serving every Pagsanjeño.
                    </p>
                </div>
                <div class="pub-about-hero-stats">
                    @foreach([['16','Barangays'],['17','Offices & Depts'],['40K+','Residents'],['348',"Gov't Personnel"]] as $stat)
                    <div class="pub-about-stat">
                        <span class="pub-about-stat-val">{{ $stat[0] }}</span>
                        <span class="pub-about-stat-label">{{ $stat[1] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="pub-about-cards">
                <div class="pub-about-card2">
                    <div class="pub-about-card2-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                    </div>
                    <h4>Vision</h4>
                    <p>A progressive, peaceful, and self-reliant municipality with empowered citizens enjoying a high quality of life under a transparent and accountable local government.</p>
                </div>
                <div class="pub-about-card2">
                    <div class="pub-about-card2-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
                    </div>
                    <h4>Mission</h4>
                    <p>To deliver efficient, effective, and equitable public services through good governance, community participation, and sustainable development programs for all Pagsanjeños.</p>
                </div>
                <div class="pub-about-card2">
                    <div class="pub-about-card2-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    </div>
                    <h4>Key Facts</h4>
                    <ul class="pub-about-list">
                        <li><span>Classification</span><strong>1st Class Municipality</strong></li>
                        <li><span>Province</span><strong>Laguna</strong></li>
                        <li><span>Region</span><strong>IV-A (CALABARZON)</strong></li>
                        <li><span>Barangays</span><strong>16 Barangays</strong></li>
                        <li><span>Departments</span><strong>17 Offices</strong></li>
                        <li><span>Personnel</span><strong>348 Employees</strong></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- Contact --}}
    <section class="pub-section alt" id="contact">
        <div class="pub-section-inner">
            <div class="pub-section-head">
                <span class="pub-eyebrow">GET IN TOUCH</span>
                <h2>Contact Us</h2>
                <p>Reach out to the Municipal Government of Pagsanjan for inquiries, concerns, or assistance.</p>
            </div>
            <div class="pub-contact-grid">

                <div class="pub-contact-panel">
                    <div class="pub-contact-panel-header">
                        <div class="pub-contact-panel-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        </div>
                        <div>
                            <p class="pub-contact-panel-title">Municipal Hall</p>
                            <p class="pub-contact-panel-sub">Pagsanjan, Laguna</p>
                        </div>
                    </div>
                    <div class="pub-contact-items">
                        @php
                        $contactItems = [
                            ['icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>', 'label' => 'Address', 'val' => 'Poblacion, Pagsanjan, Laguna 4008'],
                            ['icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>', 'label' => 'Telephone', 'val' => '(049) 501-0000 · (049) 501-0001'],
                            ['icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>', 'label' => 'Email', 'val' => 'info@pagsanjan.gov.ph'],
                            ['icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>', 'label' => 'Office Hours', 'val' => 'Mon – Fri, 8:00 AM – 5:00 PM'],
                        ];
                        @endphp
                        @foreach($contactItems as $item)
                        <div class="pub-contact-item">
                            <div class="pub-contact-icon">{!! $item['icon'] !!}</div>
                            <div>
                                <p class="pub-contact-label">{{ $item['label'] }}</p>
                                <p class="pub-contact-val">{{ $item['val'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="pub-contact-note">Closed on weekends &amp; public holidays</div>
                </div>

                <form class="pub-contact-form" id="contact-form">
                    <div class="pub-contact-form-head">
                        <p class="pub-contact-form-title">Send us a Message</p>
                        <p class="pub-contact-form-sub">We'll respond within 1–2 business days.</p>
                    </div>
                    <div class="pub-contact-row">
                        <div class="pub-contact-field">
                            <label>Full Name</label>
                            <input type="text" placeholder="Your full name" required>
                        </div>
                        <div class="pub-contact-field">
                            <label>Email Address</label>
                            <input type="email" placeholder="your@email.com" required>
                        </div>
                    </div>
                    <div class="pub-contact-field">
                        <label>Subject</label>
                        <input type="text" placeholder="e.g. Business Permit Inquiry" required>
                    </div>
                    <div class="pub-contact-field">
                        <label>Message</label>
                        <textarea rows="5" placeholder="Type your message here..." required></textarea>
                    </div>
                    <button type="submit" class="pub-btn-primary" style="width:100%;justify-content:center">
                        Send Message
                    </button>
                </form>

            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="pub-cta-section">
        <div class="pub-cta-inner">
            <div class="pub-cta-text">
                <span class="pub-eyebrow light">PRIME HRIS</span>
                <h2>Are you a Municipal Government Employee?</h2>
                <p>The PRIME HRIS portal is exclusively for authorized employees of the Municipal Government of Pagsanjan, Laguna. Access your payroll, leave, and personnel records here.</p>
                <a href="{{ route('login') }}" class="pub-cta-btn">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    Sign In to PRIME HRIS
                </a>
                <p class="pub-cta-note">Municipal Government employees only · Contact your administrator for access</p>
            </div>
            <div class="pub-cta-card">
                <div class="pub-cta-card-label">PRIME HRIS</div>
                <p class="pub-cta-card-sub">Personnel Records &amp; Information Management for Employees</p>
                <div class="pub-cta-features">
                    <div class="pub-cta-feat">✓ Payroll Processing</div>
                    <div class="pub-cta-feat">✓ 201 File Management</div>
                    <div class="pub-cta-feat">✓ Leave &amp; Benefits</div>
                    <div class="pub-cta-feat">✓ DTR Monitoring</div>
                    <div class="pub-cta-feat">✓ BIR / GSIS / PhilHealth</div>
                    <div class="pub-cta-feat">✓ Payroll Reports</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="pub-footer">
        <div class="pub-footer-inner">
            <div class="pub-footer-brand">
                <div class="pub-logo-seal sm">
                    <img src="{{ asset('images/municipal-of-pagsanjan-logo.jpg') }}" alt="Pagsanjan Logo"
                         onerror="this.style.display='none'"
                         style="width:28px;height:28px;border-radius:50%;object-fit:cover">
                </div>
                <div>
                    <span class="pub-footer-name">Municipal Government of Pagsanjan</span>
                    <span class="pub-footer-sub">Province of Laguna · Republic of the Philippines</span>
                </div>
            </div>
            <div class="pub-footer-links">
                <a href="#privacy">Privacy Policy</a>
                <a href="#terms">Terms of Use</a>
                <a href="#contact">Contact Us</a>
                <a href="#sitemap">Sitemap</a>
            </div>
            <p class="pub-footer-copy">© {{ date('Y') }} Municipal Government of Pagsanjan, Laguna. All rights reserved.</p>
        </div>
    </footer>

    {{-- AI Chatbot --}}
    @include('chatbot.chat', ['context' => 'landing'])

</div>

@push('scripts')
<script>
document.getElementById('contact-form').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Message sent! We will get back to you within 1–2 business days.');
    this.reset();
});
</script>
@endpush
@endsection

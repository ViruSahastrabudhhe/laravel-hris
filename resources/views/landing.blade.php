@extends('layouts.app')

@section('content')
<div class="pub-root">

    {{-- Gov Bar --}}
    <div class="pub-govbar">
        <span>🇵🇭 Official Website of the Municipality of Pagsanjan, Laguna</span>
        <span>{{ date('l, F d, Y') }}</span>
    </div>

    {{-- Navbar --}}
    <nav class="pub-nav">
        <a href="{{ route('landing') }}" class="pub-logo">
            <div class="pub-logo-seal">
                <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </div>
            <div>
                <span class="pub-logo-name">PRIME HRIS</span>
                <span class="pub-logo-sub">Pagsanjan, Laguna</span>
            </div>
        </a>
        <div class="pub-nav-links">
            <a href="#about">About</a>
            <a href="#services">Services</a>
            <a href="#contact">Contact</a>
        </div>
        <a href="{{ route('login') }}" class="pub-hr-btn">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
            HR Portal
        </a>
    </nav>

    {{-- Hero --}}
    <section class="pub-hero">
        <div class="pub-hero-inner">
            <div class="pub-hero-text">
                <div class="pub-hero-badge">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    Digitalized HR Management
                </div>
                <h1 class="pub-hero-title">
                    Smarter HR for<br>
                    <span class="pub-hero-highlight">Pagsanjan LGU</span>
                </h1>
                <p class="pub-hero-sub">PRIME HRIS streamlines employee management, payroll, attendance, and leave tracking for the Municipality of Pagsanjan, Laguna.</p>
                <div class="pub-hero-actions">
                    <a href="{{ route('login') }}" class="pub-btn-primary">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                        Access HR Portal
                    </a>
                    <a href="#about" class="pub-btn-ghost">Learn More</a>
                </div>
            </div>

            <div class="pub-hero-card">
                <div class="pub-hero-card-header">
                    <span class="pub-hero-card-dot active"></span>
                    System Status — Online
                </div>
                <div class="pub-hero-stats">
                    <div class="pub-hstat">
                        <span class="pub-hstat-val">100%</span>
                        <span class="pub-hstat-label">Uptime</span>
                    </div>
                    <div class="pub-hstat-divider"></div>
                    <div class="pub-hstat">
                        <span class="pub-hstat-val">24/7</span>
                        <span class="pub-hstat-label">Access</span>
                    </div>
                    <div class="pub-hstat-divider"></div>
                    <div class="pub-hstat">
                        <span class="pub-hstat-val">Secure</span>
                        <span class="pub-hstat-label">Portal</span>
                    </div>
                </div>
                <div class="pub-hero-card-tags">
                    <span class="pub-tag">Payroll</span>
                    <span class="pub-tag">Attendance</span>
                    <span class="pub-tag">Leave</span>
                    <span class="pub-tag">Employees</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Services --}}
    <section class="pub-section alt" id="services">
        <div class="pub-section-inner">
            <div class="pub-section-head">
                <span class="pub-eyebrow">WHAT WE OFFER</span>
                <h2>Complete HR Management Suite</h2>
                <p>Everything you need to manage government personnel efficiently in one platform.</p>
            </div>
            <div class="pub-services-grid">
                <div class="pub-service-card">
                    <div class="pub-service-icon">👥</div>
                    <h4>Employee Management</h4>
                    <p>Maintain complete employee records including personal information, employment history, and document management.</p>
                </div>
                <div class="pub-service-card">
                    <div class="pub-service-icon">💰</div>
                    <h4>Payroll Processing</h4>
                    <p>Automated payroll computation with mandatory deductions, salary grades, and net pay calculations.</p>
                </div>
                <div class="pub-service-card">
                    <div class="pub-service-icon">📅</div>
                    <h4>Attendance Tracking</h4>
                    <p>Monitor daily time records, overtime, tardiness, and generate attendance reports with ease.</p>
                </div>
                <div class="pub-service-card">
                    <div class="pub-service-icon">📋</div>
                    <h4>Leave Management</h4>
                    <p>Process leave applications, track leave balances, and manage leave types for all employees.</p>
                </div>
                <div class="pub-service-card">
                    <div class="pub-service-icon">🏢</div>
                    <h4>Department & Positions</h4>
                    <p>Organize your workforce by departments and positions with salary grade management.</p>
                </div>
                <div class="pub-service-card">
                    <div class="pub-service-icon">🗓️</div>
                    <h4>Work Schedules</h4>
                    <p>Configure flexible work schedules, grace periods, and holiday calendars for your organization.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- About --}}
    <section class="pub-section" id="about">
        <div class="pub-section-inner">
            <div class="pub-about-hero">
                <div class="pub-about-hero-text">
                    <div class="pub-about-hero-badge">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                        Municipality of Pagsanjan
                    </div>
                    <h3>Built for <span>Local Government</span> Efficiency</h3>
                    <p>PRIME HRIS (Personnel Records and Information Management for Employees) is the official human resource information system of the Municipality of Pagsanjan, Laguna.</p>
                    <p>Designed to comply with Civil Service Commission standards and streamline government HR operations.</p>
                </div>
                <div class="pub-about-hero-stats">
                    <div class="pub-about-stat">
                        <span class="pub-about-stat-val">CSC</span>
                        <span class="pub-about-stat-label">Compliant</span>
                    </div>
                    <div class="pub-about-stat">
                        <span class="pub-about-stat-val">LGU</span>
                        <span class="pub-about-stat-label">Powered</span>
                    </div>
                    <div class="pub-about-stat">
                        <span class="pub-about-stat-val">2025</span>
                        <span class="pub-about-stat-label">Launched</span>
                    </div>
                </div>
            </div>

            <div class="pub-about-cards">
                <div class="pub-about-card2">
                    <div class="pub-about-card2-icon">🔒</div>
                    <h4>Secure & Reliable</h4>
                    <p>Role-based access control ensures only authorized personnel can access sensitive employee data.</p>
                </div>
                <div class="pub-about-card2">
                    <div class="pub-about-card2-icon">⚡</div>
                    <h4>Fast & Efficient</h4>
                    <p>Reduce manual paperwork and processing time with automated workflows and digital records.</p>
                </div>
                <div class="pub-about-card2">
                    <div class="pub-about-card2-icon">📊</div>
                    <h4>Data-Driven</h4>
                    <p>Generate reports and insights to support informed decision-making for HR management.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Contact --}}
    <section class="pub-section alt" id="contact">
        <div class="pub-section-inner">
            <div class="pub-section-head">
                <span class="pub-eyebrow">GET IN TOUCH</span>
                <h2>Contact the HR Office</h2>
                <p>For system access requests or HR inquiries, reach out to the Municipal HR Office.</p>
            </div>
            <div class="pub-contact-grid">
                <div class="pub-contact-panel">
                    <div class="pub-contact-panel-header">
                        <div class="pub-contact-panel-icon">🏛️</div>
                        <div>
                            <p class="pub-contact-panel-title">Municipal HR Office</p>
                            <p class="pub-contact-panel-sub">Pagsanjan, Laguna</p>
                        </div>
                    </div>
                    <div class="pub-contact-items">
                        <div class="pub-contact-item">
                            <div class="pub-contact-icon">📍</div>
                            <div>
                                <p class="pub-contact-label">Address</p>
                                <p class="pub-contact-val">Municipal Hall, Pagsanjan, Laguna 4008</p>
                            </div>
                        </div>
                        <div class="pub-contact-item">
                            <div class="pub-contact-icon">📞</div>
                            <div>
                                <p class="pub-contact-label">Phone</p>
                                <p class="pub-contact-val">(049) 000-0000</p>
                            </div>
                        </div>
                        <div class="pub-contact-item">
                            <div class="pub-contact-icon">✉️</div>
                            <div>
                                <p class="pub-contact-label">Email</p>
                                <p class="pub-contact-val">hr@pagsanjan.gov.ph</p>
                            </div>
                        </div>
                        <div class="pub-contact-item">
                            <div class="pub-contact-icon">🕐</div>
                            <div>
                                <p class="pub-contact-label">Office Hours</p>
                                <p class="pub-contact-val">Monday – Friday, 8:00 AM – 5:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display:flex;flex-direction:column;gap:18px">
                    <div class="pub-contact-panel" style="border-top-color:#8e1e18">
                        <p style="font-size:14px;font-weight:700;color:#0b044d;margin:0 0 8px">Need System Access?</p>
                        <p style="font-size:13px;color:#6b6a8a;margin:0 0 16px;line-height:1.65">Contact the HR Office to request an account or reset your credentials.</p>
                        <a href="{{ route('login') }}" class="pub-btn-primary" style="justify-content:center">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                            Go to HR Portal
                        </a>
                    </div>
                    <div class="pub-contact-panel">
                        <p style="font-size:14px;font-weight:700;color:#0b044d;margin:0 0 8px">Quick Links</p>
                        <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px">
                            @foreach([['Employee Portal', route('login')], ['Forgot Password', route('password.request')]] as [$label, $href])
                            <li>
                                <a href="{{ $href }}" style="display:flex;align-items:center;gap:8px;font-size:13px;color:#0b044d;font-weight:500;text-decoration:none;padding:8px 0;border-bottom:1px solid #f0effe">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                                    {{ $label }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="pub-cta-section">
        <div class="pub-cta-inner">
            <div class="pub-cta-text">
                <h2>Ready to get started?</h2>
                <p>Access the PRIME HRIS portal to manage employee records, process payroll, and track attendance — all in one place.</p>
                <a href="{{ route('login') }}" class="pub-cta-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    Sign In to PRIME HRIS
                </a>
                <p class="pub-cta-note">For authorized LGU personnel only</p>
            </div>
            <div class="pub-cta-card">
                <p class="pub-cta-card-label">PRIME HRIS Features</p>
                <p class="pub-cta-card-sub">Everything you need to manage your workforce efficiently.</p>
                <div class="pub-cta-features">
                    <span class="pub-cta-feat">✓ Employee Records</span>
                    <span class="pub-cta-feat">✓ Payroll</span>
                    <span class="pub-cta-feat">✓ Attendance</span>
                    <span class="pub-cta-feat">✓ Leave Management</span>
                    <span class="pub-cta-feat">✓ Departments</span>
                    <span class="pub-cta-feat">✓ Work Schedules</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="pub-footer">
        <div class="pub-footer-inner">
            <div class="pub-footer-brand">
                <div class="pub-logo-seal sm">
                    <svg width="14" height="14" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </div>
                <div>
                    <span class="pub-footer-name">PRIME HRIS</span>
                    <span class="pub-footer-sub">Pagsanjan, Laguna</span>
                </div>
            </div>
            <div class="pub-footer-links">
                <a href="#about">About</a>
                <a href="#services">Services</a>
                <a href="#contact">Contact</a>
                <a href="{{ route('login') }}">Portal</a>
            </div>
            <p class="pub-footer-copy">© {{ date('Y') }} Municipality of Pagsanjan. All rights reserved.</p>
        </div>
    </footer>

</div>
@endsection

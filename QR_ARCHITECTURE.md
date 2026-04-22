# QR Code Attendance System - Architecture Diagram

## System Overview

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         LARAVEL HRIS APPLICATION                            │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────┐
│                              ADMIN INTERFACE                                │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  │
│  │   Generate   │  │   Display    │  │     Scan     │  │   History    │  │
│  │   QR Code    │  │   QR Code    │  │   QR Code    │  │    View      │  │
│  │              │  │              │  │              │  │              │  │
│  │  /qr-code    │  │  /generate   │  │    /scan     │  │  /history    │  │
│  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘  │
│         │                 │                 │                 │           │
└─────────┼─────────────────┼─────────────────┼─────────────────┼───────────┘
          │                 │                 │                 │
          ▼                 ▼                 ▼                 ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                           CONTROLLERS LAYER                                 │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │              QrCodeController (Web Routes)                          │   │
│  │  • index()     - Show generation form                               │   │
│  │  • generate()  - Create QR code with hash                           │   │
│  │  • scan()      - Show scanner interface                             │   │
│  │  • history()   - Display scan records                               │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                                                             │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │           QrScannerController (API Routes)                          │   │
│  │  • scan()       - Process scanned QR data                           │   │
│  │  • uploadScan() - Handle uploaded QR images                         │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
          │                                                     │
          ▼                                                     ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                              MODELS LAYER                                   │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  ┌──────────────────────┐         ┌──────────────────────┐                │
│  │  QrAttendanceScan    │         │     Attendance       │                │
│  │  ─────────────────   │         │  ─────────────────   │                │
│  │  • employee_id       │◄────────┤  • employee_id       │                │
│  │  • date              │         │  • date              │                │
│  │  • time_in/out       │         │  • time_in/out       │                │
│  │  • pm_in/out         │         │  • break_start/end   │                │
│  │  • overtime_in/out   │         │  • overtime_in/out   │                │
│  │  • user_id           │         │  • user_id           │                │
│  │  • qr_code_hash      │         │  • total_minutes     │                │
│  │  • scanned_at        │         │  • attendance_status │                │
│  │  • scanner_ip        │         └──────────────────────┘                │
│  └──────────────────────┘                                                  │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
          │
          ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                            DATABASE LAYER                                   │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                    qr_attendance_scans                              │   │
│  │  ─────────────────────────────────────────────────────────────────  │   │
│  │  Stores QR code data and scan information                           │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                                                             │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                        attendances                                  │   │
│  │  ─────────────────────────────────────────────────────────────────  │   │
│  │  Stores actual attendance records                                   │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘


┌─────────────────────────────────────────────────────────────────────────────┐
│                          SCANNING METHODS                                   │
└─────────────────────────────────────────────────────────────────────────────┘

┌──────────────────┐    ┌──────────────────┐    ┌──────────────────┐
│  Camera Scan     │    │  Upload Image    │    │ External Scanner │
│  ──────────────  │    │  ──────────────  │    │  ──────────────  │
│  • Real-time     │    │  • File upload   │    │  • Wi-Fi device  │
│  • HTML5 QR      │    │  • Image decode  │    │  • ESP32/Arduino │
│  • Browser-based │    │  • Server-side   │    │  • HTTP POST     │
└────────┬─────────┘    └────────┬─────────┘    └────────┬─────────┘
         │                       │                       │
         └───────────────────────┼───────────────────────┘
                                 │
                                 ▼
                    ┌────────────────────────┐
                    │   API Endpoint         │
                    │   /api/qr-scanner/scan │
                    └────────────────────────┘
                                 │
                                 ▼
                    ┌────────────────────────┐
                    │   Validation           │
                    │   • Check hash         │
                    │   • Verify not scanned │
                    │   • Validate data      │
                    └────────────────────────┘
                                 │
                                 ▼
                    ┌────────────────────────┐
                    │   Save to Database     │
                    │   • Update scan record │
                    │   • Create attendance  │
                    │   • Log IP address     │
                    └────────────────────────┘


┌─────────────────────────────────────────────────────────────────────────────┐
│                          DATA FLOW DIAGRAM                                  │
└─────────────────────────────────────────────────────────────────────────────┘

GENERATION FLOW:
─────────────────

Admin Input                QR Generation              Database
───────────                ──────────────              ────────
    │                            │                        │
    │  1. Select Employee        │                        │
    │  2. Enter Date/Times       │                        │
    ├───────────────────────────►│                        │
    │                            │  3. Create Hash        │
    │                            │  4. Generate QR        │
    │                            ├───────────────────────►│
    │                            │                        │  5. Save Record
    │                            │                        │     (pending scan)
    │                            │                        │
    │  6. Display QR Code        │                        │
    │◄───────────────────────────┤                        │
    │                            │                        │


SCANNING FLOW:
──────────────

Scanner                    API Endpoint               Database
───────                    ────────────               ────────
    │                            │                        │
    │  1. Read QR Code           │                        │
    ├───────────────────────────►│                        │
    │                            │  2. Decode JSON        │
    │                            │  3. Verify Hash        │
    │                            ├───────────────────────►│
    │                            │                        │  4. Check Record
    │                            │                        │  5. Verify Not Scanned
    │                            │◄───────────────────────┤
    │                            │                        │
    │                            │  6. Update Scan Time   │
    │                            │  7. Create Attendance  │
    │                            ├───────────────────────►│
    │                            │                        │
    │  8. Success Response       │                        │
    │◄───────────────────────────┤                        │
    │                            │                        │


┌─────────────────────────────────────────────────────────────────────────────┐
│                        SECURITY ARCHITECTURE                                │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────┐
│                                                                             │
│  Layer 1: Generation Security                                              │
│  ────────────────────────────                                              │
│  ✓ Admin authentication required                                           │
│  ✓ Role-based access control                                               │
│  ✓ SHA-256 hash generation                                                 │
│  ✓ Unique hash per QR code                                                 │
│                                                                             │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  Layer 2: Transmission Security                                            │
│  ───────────────────────────                                               │
│  ✓ HTTPS recommended                                                       │
│  ✓ JSON data format                                                        │
│  ✓ API endpoint validation                                                 │
│                                                                             │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  Layer 3: Validation Security                                              │
│  ────────────────────────────                                              │
│  ✓ Hash verification                                                       │
│  ✓ Database record check                                                   │
│  ✓ One-time scan enforcement                                               │
│  ✓ Data integrity validation                                               │
│                                                                             │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  Layer 4: Audit Security                                                   │
│  ───────────────────────                                                   │
│  ✓ IP address logging                                                      │
│  ✓ Timestamp recording                                                     │
│  ✓ Scan history tracking                                                   │
│  ✓ User attribution                                                        │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘


┌─────────────────────────────────────────────────────────────────────────────┐
│                      DEPLOYMENT ARCHITECTURE                                │
└─────────────────────────────────────────────────────────────────────────────┘

                        ┌─────────────────┐
                        │   Web Browser   │
                        │   (Admin Panel) │
                        └────────┬────────┘
                                 │
                                 │ HTTPS
                                 │
                        ┌────────▼────────┐
                        │  Laravel App    │
                        │  (Web Server)   │
                        └────────┬────────┘
                                 │
                ┌────────────────┼────────────────┐
                │                │                │
                ▼                ▼                ▼
        ┌──────────────┐ ┌──────────────┐ ┌──────────────┐
        │   Camera     │ │   Upload     │ │  External    │
        │   Scanner    │ │   Scanner    │ │  Scanner     │
        └──────────────┘ └──────────────┘ └──────┬───────┘
                                                  │
                                                  │ Wi-Fi
                                                  │
                                         ┌────────▼────────┐
                                         │  API Endpoint   │
                                         │  /api/qr-scan   │
                                         └────────┬────────┘
                                                  │
                                         ┌────────▼────────┐
                                         │    Database     │
                                         │    (MySQL)      │
                                         └─────────────────┘


┌─────────────────────────────────────────────────────────────────────────────┐
│                         COMPONENT SUMMARY                                   │
└─────────────────────────────────────────────────────────────────────────────┘

Backend Components:
├── Controllers (2)
│   ├── QrCodeController.php
│   └── Api/QrScannerController.php
├── Models (1)
│   └── QrAttendanceScan.php
├── Migrations (1)
│   └── create_qr_attendance_scans_table.php
└── Routes (2)
    ├── web.php (4 routes)
    └── api.php (2 routes)

Frontend Components:
├── Views (4)
│   ├── qr_code/index.blade.php
│   ├── qr_code/show.blade.php
│   ├── qr_code/scan.blade.php
│   └── qr_code/history.blade.php
└── Assets
    ├── qrcodejs (CDN)
    └── html5-qrcode (CDN)

Documentation:
├── QR_CODE_DOCUMENTATION.md
├── QR_QUICK_START.md
├── QR_FEATURE_SUMMARY.md
└── QR_ARCHITECTURE.md (this file)

Testing Tools:
├── qr-scanner-test.html
└── esp32_qr_scanner.ino

Database Tables:
└── qr_attendance_scans (1 table, 13 columns)


┌─────────────────────────────────────────────────────────────────────────────┐
│                      PERFORMANCE CONSIDERATIONS                             │
└─────────────────────────────────────────────────────────────────────────────┘

Scalability:
• Database indexed on qr_code_hash for fast lookups
• API endpoint optimized for quick responses
• Minimal database queries per scan

Reliability:
• Transaction-based database operations
• Error handling and logging
• Duplicate scan prevention

Availability:
• Stateless API design
• No session dependencies
• Works offline (QR generation)

Maintainability:
• Clean code structure
• Comprehensive documentation
• Modular design

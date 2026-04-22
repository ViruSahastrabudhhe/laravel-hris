# QR Code Attendance Feature - Quick Start Guide

## ✅ Installation Complete!

The QR code attendance system has been successfully installed in your Laravel HRIS application.

## 📁 Files Created

### Controllers
- `app/Http/Controllers/QrCodeController.php` - Main QR code controller
- `app/Http/Controllers/Api/QrScannerController.php` - API scanner controller

### Models
- `app/Models/QrAttendanceScan.php` - QR scan model

### Migrations
- `database/migrations/2026_04_22_154744_create_qr_attendance_scans_table.php`

### Views
- `resources/views/qr_code/index.blade.php` - QR generation form
- `resources/views/qr_code/show.blade.php` - Display generated QR
- `resources/views/qr_code/scan.blade.php` - Scanner interface
- `resources/views/qr_code/history.blade.php` - Scan history

### Routes
- Web routes added to `routes/web.php`
- API routes created in `routes/api.php`

### Documentation
- `QR_CODE_DOCUMENTATION.md` - Full documentation
- `public/qr-scanner-test.html` - API testing tool

## 🚀 Quick Start

### 1. Access the Feature
Login as admin and navigate to:
```
http://your-domain.com/admin/qr-code
```

### 2. Generate a QR Code
1. Select an employee
2. Enter date and times
3. Click "Generate QR Code"
4. Print or save the QR code

### 3. Scan QR Codes
Navigate to:
```
http://your-domain.com/admin/qr-code/scan
```

**Option A: Camera Scan**
- Click "Camera Scan" tab
- Allow camera permissions
- Point at QR code

**Option B: Upload Image**
- Click "Upload Image" tab
- Choose QR image file
- Click "Process QR Code"

### 4. View History
```
http://your-domain.com/admin/qr-code/history
```

## 🔌 API Integration

### Endpoint for External Scanners
```
POST http://your-domain.com/api/qr-scanner/scan
```

### Request Format
```json
{
  "qr_data": "{\"id\":1,\"hash\":\"abc123...\",\"employee_id\":5,\"date\":\"2026-04-22\"}"
}
```

### Test the API
Open in browser:
```
http://your-domain.com/qr-scanner-test.html
```

## 📊 Database Table

The `qr_attendance_scans` table stores:
- Employee ID
- Date
- Time in/out (AM/PM)
- Overtime times
- Admin user ID
- Scan timestamp
- Scanner IP address

## 🔐 Security Features

✅ Unique SHA-256 hash per QR code
✅ One-time scan prevention
✅ IP address logging
✅ Database verification
✅ Admin-only generation

## 📱 Scanner Integration

### Python Example
```python
import requests

url = "http://your-domain.com/api/qr-scanner/scan"
data = {"qr_data": qr_content}
response = requests.post(url, json=data)
print(response.json())
```

### JavaScript Example
```javascript
fetch('http://your-domain.com/api/qr-scanner/scan', {
  method: 'POST',
  headers: {'Content-Type': 'application/json'},
  body: JSON.stringify({qr_data: qrContent})
})
.then(res => res.json())
.then(data => console.log(data));
```

## 🎯 How It Works

1. **Generate:** Admin creates QR code with attendance data
2. **Store:** Data saved to `qr_attendance_scans` table with unique hash
3. **Scan:** QR code scanned via camera/upload/external scanner
4. **Verify:** System validates hash and checks if already scanned
5. **Record:** Attendance saved to `attendances` table
6. **Update:** Scan timestamp and IP recorded

## 📋 Next Steps

1. ✅ Test QR generation
2. ✅ Test camera scanning
3. ✅ Test upload scanning
4. ✅ Test API endpoint with test tool
5. ✅ Integrate with external scanner (if needed)
6. ✅ Train staff on usage

## 🛠️ Troubleshooting

**Camera not working?**
- Enable HTTPS (required for camera access)
- Check browser permissions
- Try different browser

**QR not scanning?**
- Ensure good lighting
- Check QR code quality
- Verify QR data format

**API errors?**
- Check endpoint URL
- Verify JSON format
- Ensure QR not already scanned

## 📞 Support

For detailed documentation, see:
- `QR_CODE_DOCUMENTATION.md`

For API testing:
- `public/qr-scanner-test.html`

---

**Status:** ✅ Ready to use!
**Version:** 1.0
**Date:** April 22, 2026

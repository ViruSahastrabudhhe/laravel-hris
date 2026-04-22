# QR Code Attendance System

## Overview
This feature allows administrators to generate QR codes containing attendance data for employees. The QR codes can be scanned using a camera or uploaded as images to automatically record attendance in the database.

## Features
- ✅ Generate QR codes with attendance data
- ✅ Scan QR codes using camera (real-time)
- ✅ Upload QR code images for scanning
- ✅ Automatic attendance recording
- ✅ Scan history tracking
- ✅ Duplicate scan prevention
- ✅ IP address logging

## Database Structure

### Table: `qr_attendance_scans`
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| employee_id | bigint | Foreign key to employees |
| date | date | Attendance date |
| time_in | time | Morning time in |
| time_out | time | Morning time out |
| pm_in | time | Afternoon time in |
| pm_out | time | Afternoon time out |
| overtime_in | time | Overtime start |
| overtime_out | time | Overtime end |
| user_id | bigint | Admin who generated the QR |
| qr_code_hash | string | Unique hash for verification |
| scanned_at | timestamp | When QR was scanned |
| scanner_ip | string | IP address of scanner |

## Routes

### Web Routes (Admin Only)
```php
GET  /admin/qr-code              // QR generation form
POST /admin/qr-code/generate     // Generate QR code
GET  /admin/qr-code/scan         // Scanner interface
GET  /admin/qr-code/history      // View scan history
```

### API Routes (Public)
```php
POST /api/qr-scanner/scan        // Process scanned QR data
POST /api/qr-scanner/upload      // Upload QR image
```

## Usage

### 1. Generate QR Code
1. Navigate to `/admin/qr-code`
2. Select an employee
3. Enter the date
4. Fill in time fields (optional):
   - Time In / Time Out
   - PM In / PM Out
   - Overtime In / Overtime Out
5. Click "Generate QR Code"
6. Print or download the generated QR code

### 2. Scan QR Code (Camera)
1. Navigate to `/admin/qr-code/scan`
2. Click "Camera Scan" tab
3. Allow camera permissions
4. Point camera at QR code
5. System automatically processes and records attendance

### 3. Scan QR Code (Upload)
1. Navigate to `/admin/qr-code/scan`
2. Click "Upload Image" tab
3. Choose QR code image file
4. Click "Process QR Code"
5. System decodes and records attendance

### 4. External Scanner Integration
For Wi-Fi connected scanners, send POST request to:

**Endpoint:** `POST /api/qr-scanner/scan`

**Headers:**
```
Content-Type: application/json
```

**Request Body:**
```json
{
  "qr_data": "{\"id\":1,\"hash\":\"abc123...\",\"employee_id\":5,\"date\":\"2026-04-22\"}"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Attendance recorded successfully",
  "data": {
    "employee_id": 5,
    "employee_name": "John Doe",
    "date": "2026-04-22",
    "time_in": "08:00:00",
    "time_out": "17:00:00",
    "scanned_at": "2026-04-22 08:05:30"
  }
}
```

**Error Response (400/404/409/500):**
```json
{
  "success": false,
  "message": "Error description"
}
```

## QR Code Data Format
The QR code contains JSON data:
```json
{
  "id": 1,
  "hash": "sha256_hash_here",
  "employee_id": 5,
  "date": "2026-04-22"
}
```

## Security Features
1. **Unique Hash:** Each QR code has a SHA-256 hash to prevent forgery
2. **One-time Scan:** QR codes can only be scanned once
3. **IP Logging:** Scanner IP addresses are recorded
4. **Database Verification:** All scans are verified against database records

## Scanner Integration Examples

### Python Scanner
```python
import requests
import json

def scan_qr(qr_data):
    url = "https://your-domain.com/api/qr-scanner/scan"
    headers = {"Content-Type": "application/json"}
    payload = {"qr_data": qr_data}
    
    response = requests.post(url, json=payload, headers=headers)
    return response.json()

# Usage
qr_content = '{"id":1,"hash":"abc123...","employee_id":5,"date":"2026-04-22"}'
result = scan_qr(qr_content)
print(result)
```

### JavaScript/Node.js Scanner
```javascript
async function scanQR(qrData) {
  const response = await fetch('https://your-domain.com/api/qr-scanner/scan', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ qr_data: qrData })
  });
  
  return await response.json();
}

// Usage
const qrContent = '{"id":1,"hash":"abc123...","employee_id":5,"date":"2026-04-22"}';
scanQR(qrContent).then(result => console.log(result));
```

### cURL Example
```bash
curl -X POST https://your-domain.com/api/qr-scanner/scan \
  -H "Content-Type: application/json" \
  -d '{"qr_data":"{\"id\":1,\"hash\":\"abc123...\",\"employee_id\":5,\"date\":\"2026-04-22\"}"}'
```

## Troubleshooting

### Camera Not Working
- Ensure HTTPS is enabled (required for camera access)
- Check browser permissions
- Try a different browser

### QR Code Not Scanning
- Ensure good lighting
- Hold camera steady
- Check QR code quality

### API Errors
- Verify endpoint URL
- Check JSON format
- Ensure QR code hasn't been scanned already
- Verify network connectivity

## Dependencies
- **html5-qrcode:** For camera and image scanning
- **qrcodejs:** For QR code generation

Both libraries are loaded via CDN in the views.

## Future Enhancements
- [ ] Bulk QR code generation
- [ ] QR code expiration dates
- [ ] Email QR codes to employees
- [ ] Mobile app for scanning
- [ ] Real-time scan notifications
- [ ] Export scan reports

## Support
For issues or questions, contact your system administrator.

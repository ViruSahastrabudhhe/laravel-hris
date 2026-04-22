/*
 * ESP32 QR Code Scanner for Laravel HRIS
 * 
 * Hardware Requirements:
 * - ESP32 Development Board
 * - QR Code Scanner Module (UART/Serial)
 * - Wi-Fi Connection
 * 
 * Libraries Required:
 * - WiFi.h (built-in)
 * - HTTPClient.h (built-in)
 * - ArduinoJson.h (install via Library Manager)
 */

#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>

// Wi-Fi Credentials
const char* ssid = "YOUR_WIFI_SSID";
const char* password = "YOUR_WIFI_PASSWORD";

// API Configuration
const char* apiEndpoint = "http://your-domain.com/api/qr-scanner/scan";

// Serial Configuration (for QR Scanner)
#define RXD2 16  // Connect to QR scanner TX
#define TXD2 17  // Connect to QR scanner RX

void setup() {
  // Initialize Serial for debugging
  Serial.begin(115200);
  
  // Initialize Serial2 for QR Scanner
  Serial2.begin(9600, SERIAL_8N1, RXD2, TXD2);
  
  // Connect to Wi-Fi
  Serial.println("Connecting to Wi-Fi...");
  WiFi.begin(ssid, password);
  
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  
  Serial.println("\nWi-Fi Connected!");
  Serial.print("IP Address: ");
  Serial.println(WiFi.localIP());
  
  Serial.println("QR Scanner Ready!");
}

void loop() {
  // Check if QR scanner has data
  if (Serial2.available()) {
    String qrData = Serial2.readStringUntil('\n');
    qrData.trim();
    
    if (qrData.length() > 0) {
      Serial.println("QR Code Detected:");
      Serial.println(qrData);
      
      // Send to API
      sendToAPI(qrData);
      
      // Wait before next scan
      delay(2000);
    }
  }
}

void sendToAPI(String qrData) {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    
    // Begin HTTP connection
    http.begin(apiEndpoint);
    http.addHeader("Content-Type", "application/json");
    
    // Create JSON payload
    StaticJsonDocument<512> doc;
    doc["qr_data"] = qrData;
    
    String jsonPayload;
    serializeJson(doc, jsonPayload);
    
    Serial.println("Sending to API...");
    Serial.println(jsonPayload);
    
    // Send POST request
    int httpResponseCode = http.POST(jsonPayload);
    
    if (httpResponseCode > 0) {
      String response = http.getString();
      Serial.println("Response Code: " + String(httpResponseCode));
      Serial.println("Response: " + response);
      
      // Parse response
      StaticJsonDocument<1024> responseDoc;
      DeserializationError error = deserializeJson(responseDoc, response);
      
      if (!error) {
        bool success = responseDoc["success"];
        const char* message = responseDoc["message"];
        
        if (success) {
          Serial.println("✓ Attendance Recorded!");
          // Add success indicator (LED, buzzer, etc.)
          successFeedback();
        } else {
          Serial.println("✗ Error: " + String(message));
          // Add error indicator
          errorFeedback();
        }
      }
    } else {
      Serial.println("Error: " + String(httpResponseCode));
      errorFeedback();
    }
    
    http.end();
  } else {
    Serial.println("Wi-Fi Disconnected!");
    errorFeedback();
  }
}

void successFeedback() {
  // Add your success feedback here
  // Example: Green LED, success beep
  Serial.println("SUCCESS FEEDBACK");
}

void errorFeedback() {
  // Add your error feedback here
  // Example: Red LED, error beep
  Serial.println("ERROR FEEDBACK");
}

/*
 * WIRING DIAGRAM:
 * 
 * ESP32          QR Scanner Module
 * -----          -----------------
 * 3.3V    --->   VCC
 * GND     --->   GND
 * GPIO16  --->   TX
 * GPIO17  --->   RX
 * 
 * OPTIONAL FEEDBACK:
 * GPIO2   --->   Green LED (Success)
 * GPIO4   --->   Red LED (Error)
 * GPIO5   --->   Buzzer
 * 
 * SETUP INSTRUCTIONS:
 * 1. Install ArduinoJson library
 * 2. Update Wi-Fi credentials
 * 3. Update API endpoint URL
 * 4. Upload to ESP32
 * 5. Connect QR scanner module
 * 6. Power on and test
 * 
 * COMPATIBLE QR SCANNER MODULES:
 * - GM65 QR Code Scanner
 * - GM66 QR Code Scanner
 * - Any UART/Serial QR scanner
 * 
 * NOTES:
 * - Ensure QR scanner outputs raw data via UART
 * - Configure scanner baud rate to 9600
 * - Test with qr-scanner-test.html first
 */

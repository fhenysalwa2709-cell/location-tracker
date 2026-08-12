# Location Tracker - Sistem Pelacakan Lokasi Real-Time

## 🎯 Fitur Utama

✅ **Real-Time Tracking** - Pelacakan lokasi real-time dengan akurasi tinggi
✅ **Multiple Devices** - Support tracking multiple devices per user
✅ **Geofencing** - Notifikasi otomatis saat masuk/keluar area
✅ **Location History** - Riwayat lokasi hingga 90 hari
✅ **Multi-User** - Mendukung multiple users dengan role berbeda
✅ **API REST** - API lengkap untuk integrasi
✅ **Dashboard** - Interface user-friendly untuk monitoring
✅ **Security** - Enkripsi HTTPS, authentication, dan authorization

## 📁 Struktur Project

```
location-tracker/
├── config/
│   ├── database.php          # Konfigurasi database
│   └── auth.php              # Konfigurasi authentication
├── includes/
│   └── functions.php         # Helper functions
├── api/
│   ├── save_location.php     # Save location endpoint
│   ├── get_location.php      # Get current location
│   ├── get_history.php       # Get location history
│   ├── get_devices.php       # Get user devices
│   └── docs.php              # API documentation
├── process/
│   ├── login.php             # Login handler
│   ├── register.php          # Register handler
│   └── logout.php            # Logout handler
├── dashboard/
│   ├── index.php             # Main dashboard
│   ├── map.php               # Full map view
│   ├── devices.php           # Devices management
│   ├── geofences.php         # Geofences management
│   ├── history.php           # Location history
│   ├── sharing.php           # Sharing management
│   └── settings.php          # User settings
├── assets/
│   ├── css/
│   │   └── style.css         # Main stylesheet
│   ├── js/
│   │   ├── tracker.js        # Location tracker JS library
│   │   └── map.js            # Map handler
│   └── images/
├── db/
│   └── schema.sql            # Database schema
├── logs/
│   └── error.log             # Error logs
├── login.php                 # Login page
├── INSTALLATION.html         # Installation guide
├── CHANGELOG.md              # Version history
└── README.md                 # Project documentation
```

## 🚀 Quick Start

### 1. Clone Repository
```bash
git clone https://github.com/fhenysalwa2709-cell/location-tracker.git
cd location-tracker
```

### 2. Upload ke cPanel
- Via FTP: Upload folder ke public_html
- Via SSH: `git clone` ke server

### 3. Setup Database
- Login ke phpMyAdmin
- Import file `db/schema.sql`
- Buat database user baru

### 4. Konfigurasi
Edit file `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'username');
define('DB_PASS', 'password');
define('DB_NAME', 'location_tracker');
define('GOOGLE_MAPS_API', 'YOUR_API_KEY');
```

### 5. Akses Aplikasi
```
Login: https://yourdomain.com/login.php
Dashboard: https://yourdomain.com/dashboard/index.php
API Docs: https://yourdomain.com/api/docs.php
```

## 🔌 API Endpoints

### 1. Save Location
```
POST /api/save_location.php

{
  "phone": "+628123456789",
  "latitude": -6.2088,
  "longitude": 106.8456,
  "accuracy": 15,
  "altitude": 50,
  "speed": 0,
  "device_id": 1,
  "location_type": "gps"
}
```

### 2. Get Current Location
```
GET /api/get_location.php?phone=+628123456789
```

### 3. Get Location History
```
GET /api/get_history.php?phone=+628123456789&days=7&limit=100
```

### 4. Get Devices
```
GET /api/get_devices.php
```

## 📱 Mobile Integration

### React Native / Flutter
```javascript
import { LocationTracker } from './tracker.js';

const tracker = new LocationTracker({
  phone: '+628123456789',
  apiUrl: 'https://yourdomain.com/api',
  updateInterval: 30000
});

tracker.start();
tracker.onSuccess = (location) => {
  console.log('Location updated:', location);
};
```

### iOS / Android Native
```swift
// Swift example
let locationData = [
  "phone": "+628123456789",
  "latitude": -6.2088,
  "longitude": 106.8456,
  "accuracy": 15
]

// Send to API
URLSession.shared.dataTask(with: url, from: locationData).resume()
```

## 🔒 Security Features

✅ Password hashing dengan bcrypt
✅ HTTPS encryption (SSL/TLS)
✅ SQL injection prevention
✅ XSS protection
✅ CSRF token validation
✅ Rate limiting (100 req/hour)
✅ Session timeout (1 jam)
✅ Login attempt lockout
✅ Two-factor authentication (2FA)

## 📊 Database Tables

### users
- id, phone, name, email, password
- role (user, admin, manager)
- status (active, inactive, suspended)
- last_login, login_attempts

### locations
- id, user_id, device_id
- latitude, longitude, accuracy
- altitude, speed, bearing
- address, city, province, country
- location_type (gps, network, manual)
- created_at

### devices
- id, user_id, device_name, device_type
- imei, os_type, os_version
- status, battery_level
- last_seen, created_at

### geofences
- id, user_id, name
- latitude, longitude, radius
- type (home, work, school, custom)
- alert_type (both, entry, exit)
- is_active

### notifications
- id, user_id, title, message
- type (alert, info, warning)
- is_read, created_at

## 🛠️ Configuration

### config/database.php
```php
// Database settings
define('DB_HOST', 'localhost');
define('DB_USER', 'username');
define('DB_PASS', 'password');
define('DB_NAME', 'location_tracker');

// API Settings
define('GOOGLE_MAPS_API', 'API_KEY');

// Security
define('SESSION_TIMEOUT', 3600);        // 1 hour
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_TIME', 900);           // 15 minutes

// Location tracking
define('LOCATION_UPDATE_INTERVAL', 30); // 30 seconds
define('API_RATE_LIMIT', 100);         // requests per hour
```

## 📈 Performance Tips

1. **Optimize Database**
   - Add indexes on frequently queried columns
   - Archive old location data (> 90 days)
   - Use database replication for scaling

2. **Caching**
   - Cache user data in Redis
   - Cache map tiles locally
   - Implement API response caching

3. **Monitoring**
   - Monitor server performance
   - Check error logs regularly
   - Monitor API rate limits

## 🐛 Troubleshooting

### "Connection failed: Access denied"
- Check database credentials in config/database.php
- Verify user has proper permissions
- Ensure database host is correct

### "Google Maps API not working"
- Verify API Key is valid
- Enable Maps JavaScript API in Google Cloud Console
- Check API restrictions settings
- Wait 5 minutes after enabling API

### "Permission Denied"
- Via SSH: `chmod -R 755 location-tracker/`
- Via FTP: Set folder to 755, files to 644

### "Blank page"
- Check error log in /logs/
- Enable debug: APP_DEBUG = true
- Verify PHP version (min 7.4)

## 📞 Support & Contact

- 📧 Email: support@example.com
- 💬 Chat: https://wa.me/628123456789
- 📚 Documentation: https://github.com/fhenysalwa2709-cell/location-tracker
- 🐛 Issues: https://github.com/fhenysalwa2709-cell/location-tracker/issues

## 📄 Lisensi

MIT License - Bebas digunakan untuk personal dan komersial

## 🤝 Kontribusi

Kontribusi sangat diterima! Silakan:

1. Fork repository
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📝 Changelog

### Version 1.0.0
- ✅ Initial release
- ✅ Real-time location tracking
- ✅ Geofencing functionality
- ✅ Multi-user support
- ✅ REST API
- ✅ Web dashboard

---

**Dibuat dengan ❤️ untuk kemudahan pelacakan lokasi**

Last Updated: 2024

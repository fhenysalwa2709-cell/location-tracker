## 📍 Location Tracker - Complete Documentation

### 🎯 Project Summary

**Location Tracker** adalah sistem pelacakan lokasi real-time yang powerful dan scalable. Dibangun dengan PHP, MySQL, dan Google Maps API untuk memberikan solusi tracking yang comprehensive untuk personal, family, atau enterprise use.

### ✨ Fitur Unggulan

#### 1. **Real-Time Location Tracking**
- Pelacakan lokasi real-time dengan akurasi tinggi
- Support untuk GPS, network, dan manual location entry
- Auto-update setiap 30 detik (configurable)
- Latitude, longitude, altitude, speed, bearing tracking

#### 2. **Multi-Device Support**
- Track multiple devices per user
- Device status monitoring (active/inactive/offline)
- Battery level tracking
- Device information (OS, version, IMEI)

#### 3. **Geofencing**
- Define custom geographic areas
- Pre-defined locations (home, work, school)
- Entry/exit alerts dengan notifikasi
- Multiple geofence support
- Adjustable radius (meters)

#### 4. **Location History**
- Store hingga 90 hari history
- Pagination support
- Filter by date range, location type, device
- Export data (CSV format)
- View detailed location information

#### 5. **Multi-User Management**
- User roles (admin, manager, user)
- User status (active, inactive, suspended)
- Activity logging untuk setiap action
- API key management

#### 6. **REST API**
- Complete API endpoints
- Authentication via phone/API key
- Rate limiting (100 req/hour)
- JSON response format
- Error handling dengan HTTP status codes

#### 7. **Dashboard & UI**
- Clean, modern interface
- Responsive design (mobile-friendly)
- Real-time map visualization
- Activity notifications
- Device management interface

#### 8. **Security**
- Password hashing dengan bcrypt
- HTTPS/SSL encryption required
- SQL injection prevention
- XSS protection
- CSRF token validation
- Session management dengan timeout
- Login attempt lockout
- Two-factor authentication (2FA) support

### 📊 Database Schema

#### Table: users
```sql
id, phone, name, email, password, pin_code, role, status,
last_login, login_attempts, locked_until, created_at, updated_at
```

#### Table: locations
```sql
id, user_id, device_id, latitude, longitude, accuracy,
altitude, speed, bearing, address, city, province, country,
location_type, created_at
```

#### Table: devices
```sql
id, user_id, device_name, device_type, imei, os_type,
os_version, status, battery_level, last_seen, created_at
```

#### Table: geofences
```sql
id, user_id, name, latitude, longitude, radius,
type, alert_type, is_active, created_at, updated_at
```

#### Table: notifications
```sql
id, user_id, title, message, type, related_to,
is_read, created_at
```

### 🔌 API Endpoints Reference

#### 1. Save Location
```
POST /api/save_location.php
Content-Type: application/json

{
  "phone": "+628123456789",
  "latitude": -6.2088,
  "longitude": 106.8456,
  "accuracy": 15,
  "device_id": 1,
  "location_type": "gps"
}

Response (201 Created):
{
  "success": true,
  "message": "Location saved successfully",
  "data": {
    "location_id": 12345,
    "latitude": -6.2088,
    "longitude": 106.8456,
    "timestamp": "2024-01-15 10:30:00"
  }
}
```

#### 2. Get Current Location
```
GET /api/get_location.php?phone=+628123456789

Response (200 OK):
{
  "success": true,
  "data": {
    "location": {
      "id": 12345,
      "latitude": -6.2088,
      "longitude": 106.8456,
      "accuracy": 15,
      "address": "Jl. Example, Jakarta"
    },
    "device": {
      "id": 1,
      "name": "Smartphone",
      "status": "active",
      "battery": 80
    }
  }
}
```

#### 3. Get Location History
```
GET /api/get_history.php?phone=+628123456789&days=7&limit=100

Response (200 OK):
{
  "success": true,
  "pagination": {
    "total": 150,
    "limit": 100,
    "offset": 0,
    "pages": 2
  },
  "data": [...]
}
```

#### 4. Get Devices
```
GET /api/get_devices.php

Response (200 OK):
{
  "success": true,
  "data": [
    {
      "id": 1,
      "device_name": "Smartphone",
      "status": "active",
      "battery_level": 80,
      "last_seen": "2024-01-15 10:30:00"
    }
  ]
}
```

### 🛠️ Installation Steps

#### Step 1: Clone Repository
```bash
git clone https://github.com/fhenysalwa2709-cell/location-tracker.git
cd location-tracker
```

#### Step 2: Upload to cPanel
- Via FTP: Upload folder ke `public_html`
- Via SSH: `git clone` ke server

#### Step 3: Database Setup
1. Login ke phpMyAdmin
2. Import `db/schema.sql`
3. Create database user with permissions

#### Step 4: Configuration
Edit `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'username');
define('DB_PASS', 'password');
define('DB_NAME', 'location_tracker');
define('GOOGLE_MAPS_API', 'YOUR_API_KEY');
```

#### Step 5: Access Application
- Login: `https://yourdomain.com/login.php`
- Dashboard: `https://yourdomain.com/dashboard/index.php`
- API Docs: `https://yourdomain.com/api/docs.php`

### 📱 Mobile Integration

#### JavaScript (Web)
```javascript
const tracker = new LocationTracker({
  phone: '+628123456789',
  apiUrl: 'https://yourdomain.com/api',
  updateInterval: 30000
});

tracker.start();
tracker.onSuccess = (location) => {
  console.log('Location:', location);
};
```

#### React Native / Flutter
```javascript
// Send location to API
fetch('https://yourdomain.com/api/save_location.php', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    phone: '+628123456789',
    latitude: -6.2088,
    longitude: 106.8456
  })
})
```

### 🔒 Security Best Practices

1. **HTTPS Only**: Always use HTTPS/SSL
2. **Strong Passwords**: Enforce strong password policy
3. **API Keys**: Generate and manage API keys securely
4. **Rate Limiting**: Monitor dan limit API requests
5. **Database Backup**: Regular automated backups
6. **Error Logging**: Log errors untuk debugging
7. **Access Control**: Implement proper role-based access
8. **Session Security**: Set appropriate session timeout

### 📈 Performance Optimization

1. **Database Indexes**: On frequently queried columns
2. **Query Caching**: Cache user data
3. **CDN**: Use CDN untuk static assets
4. **Compression**: Enable GZIP compression
5. **Lazy Loading**: Load data on demand
6. **API Optimization**: Minimize response payload

### 🐛 Troubleshooting Common Issues

#### Issue: Database Connection Failed
**Solution**: Verify credentials di `config/database.php`

#### Issue: Google Maps Not Displaying
**Solution**: Check API key, enable Maps JavaScript API, wait 5 minutes

#### Issue: Permission Denied
**Solution**: `chmod -R 755 location-tracker/`

#### Issue: Blank Page
**Solution**: Check error logs, enable debug mode, verify PHP version

### 📞 Support & Resources

- **Documentation**: https://github.com/fhenysalwa2709-cell/location-tracker
- **API Docs**: `/api/docs.php`
- **Installation Guide**: `INSTALLATION.html`
- **Issues**: GitHub Issues page
- **Email**: support@example.com

### 🤝 Contributing

Contributions are welcome! Please:
1. Fork the repository
2. Create feature branch
3. Commit changes
4. Push to branch
5. Open Pull Request

### 📜 License

MIT License - Feel free to use for personal and commercial projects

### 🎓 Learning Resources

- Google Maps API Documentation
- PHP Best Practices
- MySQL Optimization
- REST API Design
- Security in Web Applications

### 📝 Version History

**v1.0.0** (2024-01-15)
- Initial release
- Core tracking functionality
- API endpoints
- Web dashboard
- Geofencing support
- Multi-user system

---

**Last Updated**: 2024-01-15
**Maintained by**: fhenysalwa2709-cell
**Status**: Active Development

🎉 Thank you for using Location Tracker!

<?php
/**
 * Geofences Management Page
 * /dashboard/geofences.php
 */

require_once '../config/database.php';
require_once '../config/auth.php';

requireLogin();

$user_id = getCurrentUserId();

// Get all geofences
$stmt = $conn->prepare("
    SELECT id, name, latitude, longitude, radius, type, alert_type, is_active
    FROM geofences
    WHERE user_id = ?
    ORDER BY name
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$geofences = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Geofence - Location Tracker</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            color: #333;
        }

        .container {
            display: grid;
            grid-template-columns: 250px 1fr;
            min-height: 100vh;
        }

        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            overflow-y: auto;
        }

        .sidebar h2 {
            font-size: 20px;
            margin-bottom: 30px;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar li {
            margin-bottom: 15px;
        }

        .sidebar a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .main {
            padding: 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .header h1 {
            font-size: 28px;
        }

        .btn {
            padding: 10px 20px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn:hover {
            background: #764ba2;
        }

        .content {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            padding: 20px;
        }

        .geofence-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .geofence-card {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            transition: all 0.3s;
        }

        .geofence-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .geofence-name {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }

        .geofence-info {
            font-size: 13px;
            color: #666;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .geofence-info strong {
            color: #333;
            margin-right: 5px;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .badge-home {
            background: #e3f2fd;
            color: #1976d2;
        }

        .badge-work {
            background: #f3e5f5;
            color: #7b1fa2;
        }

        .badge-school {
            background: #e8f5e9;
            color: #388e3c;
        }

        .badge-custom {
            background: #fff3e0;
            color: #f57c00;
        }

        .badge-active {
            background: #d4edda;
            color: #155724;
            margin-left: 10px;
        }

        .badge-inactive {
            background: #f8d7da;
            color: #721c24;
            margin-left: 10px;
        }

        .geofence-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .btn-small {
            flex: 1;
            padding: 8px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s;
        }

        .btn-small:hover {
            background: #764ba2;
        }

        .btn-delete {
            background: #dc3545;
        }

        .btn-delete:hover {
            background: #c82333;
        }

        .empty {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .empty p {
            font-size: 16px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
            }

            .sidebar {
                display: none;
            }

            .geofence-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2>📍 Tracker</h2>
            <ul>
                <li><a href="/dashboard/index.php">Dashboard</a></li>
                <li><a href="/dashboard/map.php">Peta Lokasi</a></li>
                <li><a href="/dashboard/devices.php">Perangkat</a></li>
                <li><a href="/dashboard/geofences.php" class="active">Geofence</a></li>
                <li><a href="/dashboard/history.php">Riwayat</a></li>
                <li><a href="/dashboard/sharing.php">Berbagi</a></li>
                <li><a href="/dashboard/settings.php">Pengaturan</a></li>
                <li><a href="/process/logout.php">Logout</a></li>
            </ul>
        </aside>

        <!-- Main content -->
        <div class="main">
            <div class="header">
                <h1>🛡️ Geofence</h1>
                <button class="btn" onclick="addGeofence()">+ Tambah Geofence</button>
            </div>

            <div class="content">
                <p style="color: #666;">Kelola area geografis dan dapatkan notifikasi otomatis</p>
                
                <?php if (!empty($geofences)): ?>
                    <div class="geofence-grid">
                        <?php foreach ($geofences as $geofence): ?>
                            <div class="geofence-card">
                                <div class="geofence-name"><?php echo htmlspecialchars($geofence['name']); ?></div>
                                <div>
                                    <span class="badge badge-<?php echo $geofence['type']; ?>">
                                        <?php echo ucfirst($geofence['type']); ?>
                                    </span>
                                    <span class="badge badge-<?php echo $geofence['is_active'] ? 'active' : 'inactive'; ?>">
                                        <?php echo $geofence['is_active'] ? 'Aktif' : 'Tidak Aktif'; ?>
                                    </span>
                                </div>
                                
                                <div class="geofence-info">
                                    <strong>📍 Koordinat:</strong>
                                    <?php echo number_format($geofence['latitude'], 6); ?>, 
                                    <?php echo number_format($geofence['longitude'], 6); ?>
                                </div>

                                <div class="geofence-info">
                                    <strong>📏 Radius:</strong>
                                    <?php echo $geofence['radius']; ?>m
                                </div>

                                <div class="geofence-info">
                                    <strong>🔔 Alert:</strong>
                                    <?php echo ucfirst($geofence['alert_type']); ?>
                                </div>

                                <div class="geofence-actions">
                                    <button class="btn-small" onclick="editGeofence(<?php echo $geofence['id']; ?>)">
                                        Edit
                                    </button>
                                    <button class="btn-small btn-delete" onclick="deleteGeofence(<?php echo $geofence['id']; ?>)">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty">
                        <p>Tidak ada geofence</p>
                        <button class="btn" onclick="addGeofence()">Tambah Geofence Pertama</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function addGeofence() {
            alert('Fitur ini akan diimplementasikan di versi berikutnya');
        }

        function editGeofence(id) {
            alert('Fitur edit geofence');
        }

        function deleteGeofence(id) {
            if (confirm('Yakin ingin menghapus geofence ini?')) {
                alert('Geofence dihapus');
            }
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>

<?php


// Fetch venue locations and details
$venue = fetch_data($pdo, "SELECT id, title, latitude, longitude, amenities, price FROM venue WHERE latitude IS NOT NULL AND longitude IS NOT NULL");

?>
<!DOCTYPE html>
<html>
<head>
    <title>Venue Map</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJHoWIiFsp9vF5+RmJMdxG1j97yrHDNHPxmalkGcJA==" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZs1Kkgc8PU1cKB4UUplusxX7j35Y==" crossorigin=""></script>
    <style>
        html, body { height: 100%; margin: 0; padding: 0; }
        #map { width: 100%; height: 100%; }
        .venue-card {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: white;
        }
        .venue-card h3 { margin-top: 0; margin-bottom: 5px; font-size: 1.2em; }
        .venue-card p { margin-bottom: 3px; font-size: 0.9em; }
        .venue-card a { color: blue; text-decoration: none; font-size: 0.9em; }
        .venue-card a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div id="map"></div>

    <script>
        var map = L.map('map').setView([14.4797, 120.9936], 13); // Initial center (Las Piñas) and zoom

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        <?php if ($venues): ?>
            <?php foreach ($venues as $venue): ?>
                var marker = L.marker([<?php echo $venue['latitude']; ?>, <?php echo $venue['longitude']; ?>]).addTo(map);
                marker.bindPopup(`
                    <div class="venue-card">
                        <h3><?php echo htmlspecialchars($venue['title']); ?></h3>
                        <?php if (!empty($venue['amenities'])): ?>
                            <p>Amenities: <?php echo htmlspecialchars($venue['amenities']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($venue['price'])): ?>
                            <p>Price: ₱<?php echo htmlspecialchars(number_format($venue['price'], 2)); ?>/hour</p>
                        <?php endif; ?>
                        <a href="venue_display.php?id=<?php echo $venue['id']; ?>">View Details</a>
                    </div>
                `);
            <?php endforeach; ?>
        <?php else: ?>
            console.log("No venue locations found in the database.");
        <?php endif; ?>
    </script>
</body>
</html>
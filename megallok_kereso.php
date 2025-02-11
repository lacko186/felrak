<?php
session_start();
require_once 'config.php';

// Debug információ
error_log("Session tartalma: " . print_r($_SESSION, true));

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
if (!isset($_SESSION['user_id'])) {
    error_log("Nincs bejelentkezve, átirányítás a login.php-ra");
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaposvár Intelligens Közlekedés</title>
    <script src="markerbetolt.js"></script>

    <!-- Advanced styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css" rel="stylesheet">
    <link href ="header.css" rel="stylesheet">
    <link href ="footer.css" rel="stylesheet"> 
     
    <!-- Google Maps API -->
    <script async
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyArXtWdllsylygVw5t_k-22sXUJn-jMU8k&libraries=places&callback=initMap&loading=async">
    </script>

    <style>
      
        /* Custom map and UI enhancements */
        #map {
            height: 650px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }
        .transit-mode-btn {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .transit-mode-btn.active {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }
        #route{
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            font-weight: bolder;
            font-style: italic;
            color: blue;
        }
        button {
  /* Variables */
  --button_radius: 0.75em;
  --button_color: #e8e8e8;
  --button_outline_color: #000000;
  font-size: 17px;
  font-weight: bold;
  border: none;
  cursor: pointer;
  border-radius: var(--button_radius);
  background: var(--button_outline_color);
}

.button_top {
  display: block;
  box-sizing: border-box;
  border: 2px solid var(--button_outline_color);
  border-radius: var(--button_radius);
  padding: 0.75em 1.5em;
  background: var(--button_color);
  color: var(--button_outline_color);
  transform: translateY(-0.2em);
  transition: transform 0.1s ease;
}

button:hover .button_top {
  /* Pull the button upwards when hovered */
  transform: translateY(-0.33em);
}

button:active .button_top {
  /* Push the button downwards when pressed */
  transform: translateY(0);
}

    </style>
</head>
    <div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-2xl rounded-3xl p-8">
    <h1 class="text-4xl font-bold text-center text-red-700 mb-8">
        <i class="fas fa-map-marked-alt mr-3"></i>Megálló Keresés
    </h1>

 <!-- Keresési szekció -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div>
        <label class="block text-gray-700 mb-2">Megálló keresése név alapján</label>
        <div class="relative">
            <i class="fas fa-bus-simple absolute left-4 top-4 text-blue-500"></i>
            <input
                id="stop-search"
                type="text"
                placeholder="pl. Kaposvár"
                class="w-full pl-12 pr-4 py-3 border-2 rounded-lg focus:ring-2 focus:ring-blue-500"
            >
        </div>
    </div>
    <div>
        <div class="relative">
          
        </div>
    </div>
</div>
<!-- Keresési gomb -->
<button id="search-button" class="w-full bg-red-600 text-white py-3 rounded-lg hover:bg-blue-700 transition mb-6">
    <i class="fas fa-search mr-2"></i>Keresés
</button>

    <!-- Map and Route Details Container -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2">
            <div id="map" class="w-full rounded-2xl"></div>
        </div>

        <div class="md:col-span-1">
    <div id="route-info" class="bg-white rounded-2xl p-4 shadow-lg">
        <!-- Search results will appear here -->
    </div>
</div>

<a href="terkep.php"><button>
  <span style="font-weight:bold" class="button_top">Útvonaltervezés ➤</span>
</button>
</a>



    <script>
// Optimized Stop Map Visualization Script

class StopMapManager {
    constructor(mapElementId = 'map') {
        this.map = null;
        this.markers = [];
        this.infoWindows = [];
        this.mapElementId = mapElementId;
        this.geocodeCache = new Map();
        this.initializeMap();
    }

    // Initialize Google Maps
    initializeMap() {
        this.map = new google.maps.Map(document.getElementById(this.mapElementId), {
            center: { lat: 47.162494, lng: 19.503304 }, // Hungary center
            zoom: 7,
            styles: [
                {
                    featureType: "transit.station",
                    elementType: "all",
                    stylers: [{ visibility: "on" }]
                }
            ]
        });
    }

    // Cached geocoding to reduce API calls
    async getLocationName(position) {
        const cacheKey = `${position.lat},${position.lng}`;
        
        if (this.geocodeCache.has(cacheKey)) {
            return this.geocodeCache.get(cacheKey);
        }

        return new Promise((resolve, reject) => {
            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({ location: position }, (results, status) => {
                if (status === 'OK' && results[0]) {
                    const addressComponents = results[0].address_components;
                    const city = addressComponents.find(component => 
                        component.types.includes('locality') || 
                        component.types.includes('postal_town') ||
                        component.types.includes('administrative_area_level_2')
                    );
                    
                    const locationName = city ? city.long_name : results[0].formatted_address;
                    this.geocodeCache.set(cacheKey, locationName);
                    resolve(locationName);
                } else {
                    resolve('Unknown Location');
                }
            });
        });
    }

    // Clear existing markers
    clearMarkers() {
        this.markers.forEach(marker => marker.setMap(null));
        this.infoWindows.forEach(window => window.close());
        this.markers = [];
        this.infoWindows = [];
    }

    // Create marker with info window
    async createMarker(stop) {
        if (!stop.latitude || !stop.longitude) return null;

        const position = {
            lat: parseFloat(stop.latitude),
            lng: parseFloat(stop.longitude)
        };

        if (isNaN(position.lat) || isNaN(position.lng)) return null;

        // Get location name
        const locationName = await this.getLocationName(position);

        // Determine agency display
        const agencyDisplay = stop.agency_ids === '12' ? locationName : (stop.agency_ids || 'Unknown Agency');

        // Create marker
        const marker = new google.maps.Marker({
            position: position,
            map: this.map,
            title: stop.id,
            icon: {
                url: 'https://maps.gstatic.com/mapfiles/ms2/micons/red-dot.png',
                scaledSize: new google.maps.Size(30, 30)
            }
        });

        // Create info window
        const infoWindow = new google.maps.InfoWindow({
            content: `
                <div class="p-4">
                    <h3 class="font-bold text-lg mb-2">${locationName}</h3>
                    <p class="text-sm mb-2">Station ID: ${stop.id}</p>
                    <p class="text-sm mb-2">Location: ${locationName}</p>
                    <p class="text-sm mb-2">Frequency: ${stop.frequency || '0'} utasok/nap</p>
                    <p class="text-sm mb-2">Agency: ${agencyDisplay}</p>
                    <p class="text-xs mt-2">
                        Coordinates: ${position.lat.toFixed(6)}, ${position.lng.toFixed(6)}
                    </p>
                </div>
            `
        });

        // Add click listener
        marker.addListener('click', () => {
            // Close all other info windows
            this.infoWindows.forEach(w => w.close());
            infoWindow.open(this.map, marker);
        });

        return { marker, infoWindow, locationName };
    }

    // Load and display stops with advanced performance optimization
    async loadStops(stops, batchSize = 100) {
        this.clearMarkers();
        
        const bounds = new google.maps.LatLngBounds();
        const progressContainer = document.getElementById('route-info');

        // Process stops in batches
        for (let i = 0; i < stops.length; i += batchSize) {
            const batch = stops.slice(i, i + batchSize);
            
            // Process batch markers
            const batchResults = await Promise.all(
                batch.map(stop => this.createMarker(stop))
            );

            // Filter out null results
            const validResults = batchResults.filter(result => result !== null);

            // Add markers and info windows
            validResults.forEach(result => {
                this.markers.push(result.marker);
                this.infoWindows.push(result.infoWindow);
                
                // Extend bounds
                bounds.extend(result.marker.getPosition());
            });

            // Update progress
            if (progressContainer) {
                progressContainer.innerHTML = `
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h4 class="font-bold text-lg mb-3">Loading stops... (${i + validResults.length}/${stops.length})</h4>
                        <div class="progress-bar">
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full" 
                                     style="width: ${((i + validResults.length) / stops.length * 100)}%">
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            // Slight delay to prevent UI freezing
            await new Promise(resolve => setTimeout(resolve, 10));
        }

        // Fit map to bounds
        if (this.markers.length > 0) {
            this.map.fitBounds(bounds);
        }

        // Update final info panel with stop details
        this.updateStopsList(stops);
    }

    // Update stops list in the info panel
    async updateStopsList(stops) {
        const progressContainer = document.getElementById('route-info');
        if (!progressContainer) return;

        // Generate stop list with location names
        const stopListHTML = await Promise.all(stops.map(async (stop) => {
            if (!stop.latitude || !stop.longitude) return '';

            const position = {
                lat: parseFloat(stop.latitude),
                lng: parseFloat(stop.longitude)
            };

            const locationName = await this.getLocationName(position);

            return `
                <div class="mb-3 p-3 bg-gray-50 rounded hover:bg-gray-100 cursor-pointer"
                     onclick="window.focusStop(${stop.latitude}, ${stop.longitude})">
                    <p class="font-bold">${stop.id}</p>
                    <p class="text-sm text-gray-600">Location: ${locationName}</p>
                    <p class="text-sm text-gray-500">Frequency: ${stop.frequency || '0'} passengers/day</p>
                </div>
            `;
        }));

        progressContainer.innerHTML = `
            <div class="bg-white p-4 rounded-lg shadow">
                <h4 class="font-bold text-lg mb-3">All Stops (${stops.length})</h4>
                <div class="max-h-96 overflow-y-auto">
                    ${stopListHTML.join('')}
                </div>
            </div>
        `;
    }
}

// Global function for focusing on a specific stop
window.focusStop = function(lat, lng) {
    if (!window.stopMapManager) return;
    
    const position = { lat: parseFloat(lat), lng: parseFloat(lng) };
    window.stopMapManager.map.setCenter(position);
    window.stopMapManager.map.setZoom(15);
};

// Initialize map when Google Maps API loads
window.initMap = function() {
    // Create global stop map manager
    window.stopMapManager = new StopMapManager();

    // Fetch and display stops
    fetchAndDisplayStops();
};

// Fetch stops from API
async function fetchAndDisplayStops() {
    try {
        const response = await fetch('http://localhost:3000/api/stop', {
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const stops = await response.json();
        
        if (!Array.isArray(stops)) {
            throw new Error('Invalid response format - expected array of stops');
        }

        // Load stops using the map manager
        await window.stopMapManager.loadStops(stops);

    } catch (error) {
        console.error('Error fetching stops:', error);
        
        const progressContainer = document.getElementById('route-info');
        if (progressContainer) {
            progressContainer.innerHTML = `
                <div class="bg-white p-4 rounded-lg shadow text-red-600">
                    <h4 class="font-bold text-lg mb-3">Error Loading Stops</h4>
                    <p>${error.message}</p>
                </div>
            `;
        }
    }
}

// Add event listener to ensure initialization
document.addEventListener('DOMContentLoaded', () => {
    // Initialization is now handled by Google Maps API callback
    console.log('Page loaded, waiting for map initialization');
});
</script>

</body>
</html>
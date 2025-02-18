
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

    <!-- Advanced styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

    <script src="betolt.js"></script>
    <link href="header.css" rel="stylesheet">
 
    <script src="hetvegefigyelo.js"></script>

    
<script>
    window.initMap = function() {
    };
</script>

    <style>
        :root {
            --primary-color:linear-gradient(to right, #211717,#b30000);
            --accent-color: #7A7474;
            --text-light: #fbfbfb;
            --background-light: #f8f9fa;
            --transition: all 0.3s ease;
        }

        body{
            background: linear-gradient(to left, #a6a6a6, #e8e8e8);
        }

/*--------------------------------------------------------------------------------------------------------CSS - HEADER---------------------------------------------------------------------------------------------------*/
    .header {
        position: relative;
        background: var(--primary-color);
        color: var(--text-light);
        padding: 1rem;
    }

    .header h1 {
        text-align: center;
        font-size: 2rem;
        padding: 1rem 0;
        margin-left: 38%;
        display: inline-block;
    }

    .nav-wrapper {
        position: absolute;
        top: 1rem;
        left: 1rem;
        z-index: 1000;
    }

    .nav-container {
        position: relative;
    }
/*--------------------------------------------------------------------------------------------------------HEADER END-----------------------------------------------------------------------------------------------------*/

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
        #next{
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
<body>

<!-- -----------------------------------------------------------------------------------------------------HTML - HEADER----------------------------------------------------------------------------------------------- -->
<div class="header">
    <div class="nav-wrapper">
        <div class="nav-container">
            <button class="menu-btn" id="menuBtn">
                <div class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </button>
            <nav class="dropdown-menu" id="dropdownMenu">
                <ul class="menu-items">
                    <li>
                    <a href="index.php" class="active">
                                <img src="home.png" alt="Főoldal">
                                <span>Főolldal</span>
                            </a>
                        </li>
                        <li>
                            <a href="terkep.php" class="active">
                                <img src="placeholder.png" alt="Térkép">
                                <span>Térkép</span>
                            </a>
                        </li>
                        <li>
                            <a href="keses.php">
                                <img src="tickets.png" alt="Jegyvásárlás">
                                <span>Késés Igazolás</span>
                            </a>
                        </li>
                        <li>
                            <a href="menetrend.php">
                                <img src="calendar.png" alt="Menetrend">
                                <span>Menetrend</span>
                            </a>
                        </li>
                        <li>
                            <a href="jaratok.php">
                                <img src="bus.png" alt="járatok">
                                <span>Járatok</span>
                            </a>
                        </li>
                        <li>
                            <a href="info.php">
                                <img src="information-button.png" alt="Információ">
                                <span>Információ</span>
                            </a>
                        </li>
                        <li>
                            <a href="logout.php">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Kijelentkezés</span>
                            </a>
                        </li>
                </ul>
            </nav>
        </div>
    </div>
                <h1>Kaposvár Közlekedési Zrt.</h1>
        </div>
<!-- -----------------------------------------------------------------------------------------------------HEADER END-------------------------------------------------------------------------------------------------- -->

    <div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-2xl rounded-3xl p-8">
    <h1 class="text-4xl font-bold text-center text-red-700 mb-8">
        <i class="fas fa-map-marked-alt mr-3"></i>Kaposvár Mobil Útitárs
    </h1>

    <!-- Advanced Route Planning Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div>
            <label class="block text-gray-700 mb-2">Indulási pont</label>
            <div class="relative">
                <i class="fas fa-map-pin absolute left-4 top-4 text-blue-500"></i>
                <input
                    id="start"
                    type="text"
                    placeholder="pl. Vasútállomás"
                    class="w-full pl-12 pr-4 py-3 border-2 rounded-lg focus:ring-2 focus:ring-blue-500"
                >
            </div>
        </div>
        <div>
            <label class="block text-gray-700 mb-2">Érkezési pont</label>
            <div class="relative">
                <i class="fas fa-flag-checkered absolute left-4 top-4 text-green-500"></i>
                <input
                    id="end"
                    type="text"
                    placeholder="pl. Kossuth tér"
                    class="w-full pl-12 pr-4 py-3 border-2 rounded-lg focus:ring-2 focus:ring-blue-500"
                >
            </div>
        </div>
        <div>
            <label class="block text-gray-700 mb-2">Utazás ideje</label>
            <div class="relative">
                <i class="fas fa-clock absolute left-4 top-4 text-purple-500"></i>
                <input
                    id="travel-time"
                    type="datetime-local"
                    class="w-full pl-12 pr-4 py-3 border-2 rounded-lg focus:ring-2 focus:ring-blue-500"
                >
            </div>
        </div>
    </div>

    <!-- Transit Mode Selection with Advanced Icons -->
    <div class="flex justify-between space-x-4 mb-6">
        <button class="transit-mode-btn flex-1 p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition" data-mode="bus">
            <i class="fas fa-bus text-3xl text-blue-600"></i>
            <span class="block mt-2 font-semibold">Helyi Busz</span>
        </button>
        <button class="transit-mode-btn flex-1 p-3 bg-green-50 rounded-lg hover:bg-green-100 transition" data-mode="train">
            <i class="fas fa-train text-3xl text-green-600"></i>
            <span class="block mt-2 font-semibold">Vonat</span>
        </button>
        <button class="transit-mode-btn flex-1 p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition" data-mode="complex">
            <i class="fas fa-network-wired text-3xl text-purple-600"></i>
            <span class="block mt-2 font-semibold">Helyi Járat</span>
        </button>
    </div>

    <!-- Select for Complex Route -->
    <div id="complex-route-select" class="hidden mb-6">
        <label class="block text-gray-700 mb-2">Válasszon induló járatot</label>
        <select id="complex-route" class="w-full p-3 border-2 rounded-lg focus:ring-2 focus:ring-blue-500">
            <option value="">Válasszon</option>
            <option value="12">12 - Helyi autóbusz-állomás - Sopron u. - Laktanya</option>
            <option value="12 vissza">12 vissza - Laktanya - Sopron u. - Helyi autóbusz-állomás</option>
            <option value="13">13 - Helyi autóbusz-állomás - Kecelhegy - Helyi autóbusz-állomás</option>
            <option value="20">20 - Raktár u. - Laktanya - Videoton</option>
            <option value="20 vissza">20 vissza - Videoton - Laktanya - Raktár u.</option>
            <option value="21">21 - Raktár u. - Videoton</option>
            <option value="21 vissza">21 vissza - Videoton - Raktár u.</option>
            <option value="23">23 - Kaposfüred forduló - Füredi csp. - Kaposvári Egyetem</option>
            <option value="23 vissza">23 vissza - Kaposvári Egyetem - Füredi csp. - Kaposfüred forduló</option>
            <option value="26">26 - Kaposfüred forduló - Losonc köz - Videoton - METYX</option>
            <option value="26 vissza">26 vissza - METYX - Videoton - Losonc köz - Kaposfüred forduló</option>
            <option value="27">27 - Laktanya - Füredi u. csp. - KOMÉTA</option>
            <option value="27 vissza">27 vissza - KOMÉTA - Füredi u. csp. - Laktanya</option>
            <option value="31">31 - Helyi autóbusz-állomás - Egyenesi u. forduló</option>
            <option value="31 vissza">31 vissza - Egyenesi u. forduló - Helyi autóbusz-állomás</option>
            <option value="32">32 - Helyi autóbuszállomás - Kecelhegy - Helyi autóbusz-állomás</option>
            <option value="33">33 - Helyi aut. áll. - Egyenesi u. - Kecelhegy - Helyi aut. áll.</option>
            <option value="40">40 - Koppány vezér u - 67-es út - Raktár u.</option>
            <option value="40 vissza">40 vissza - Raktár u. - 67-es út - Koppány vezér u</option>
            <option value="41">41 - Koppány vezér u - Bartók B. u. - Raktár u.</option>
            <option value="41 vissza">41 vissza - Raktár u. - Bartók B. u. - Koppány vezér u</option>
            <option value="42">42 - Töröcske forduló - Kórház - Laktanya</option>
            <option value="42 vissza">42 vissza - Laktanya - Kórház - Töröcske forduló</option>
            <option value="43">43 - Helyi autóbusz-állomás - Kórház- Laktanya - Raktár utca - Helyi autóbusz-állomás</option>
            <option value="44">44 - Helyi autóbusz-állomás - Raktár utca - Laktanya -Arany János tér - Helyi autóbusz-állomás</option>
            <option value="45">45 - Helyi autóbusz-állomás - 67-es út - Koppány vezér u.</option>
            <option value="45 vissza">45 vissza - Koppány vezér u. - 67-es út - Helyi autóbusz-állomás</option>
            <option value="46">46 - Helyi autóbusz-állomás - Töröcske forduló</option>
            <option value="46 vissza">46 vissza - Töröcske forduló - Helyi autóbusz-állomás</option>
            <option value="47">47 - Koppány vezér u.- Kórház - Kaposfüred forduló</option>
            <option value="47 vissza">47 vissza - Kaposfüred forduló - Kórház - Koppány vezér u.</option>
            <option value="61">61 - Helyi- autóbuszállomás - Béla király u.</option>
            <option value="61 vissza">61 vissza - Béla király u. - Helyi autóbusz-állomás</option>
            <option value="62">62 - Helyi autóbusz-állomás - Városi fürdő - Béla király u.</option>
            <option value="62 vissza">62 vissza - Béla király u. - Városi fürdő - Helyi autóbusz-állomás</option>
            <option value="70">70 - Helyi autóbusz-állomás - Kaposfüred</option>
            <option value="70 vissza">70 vissza - Kaposfüred - Helyi autóbusz-állomás</option>
            <option value="71">71 - Kaposfüred forduló - Kaposszentjakab forduló</option>
            <option value="71 vissza">71 vissza - Kaposszentjakab forduló - Kaposfüred forduló</option>
            <option value="72">72 - Kaposfüred forduló - Hold u. - Kaposszentjakab forduló</option>
            <option value="72 vissza">72 vissza - Kaposszentjakab forduló - Hold u. - Kaposfüred forduló</option>
            <option value="73">73 - Kaposfüred forduló - KOMÉTA - Kaposszentjakab forduló</option>
            <option value="73 vissza">73 vissza - Kaposszentjakab forduló - KOMÉTA - Kaposfüred forduló</option>
            <option value="74">74 - Hold utca - Helyi autóbusz-állomás</option>
            <option value="75">75 - Helyi autóbusz-állomás - Kaposszentjakab</option>
            <option value="75 vissza">75 vissza - Kaposszentjakab - Helyi autóbusz-állomás</option>
            <option value="81">81 - Helyi autóbusz-állomás - Hősök temploma - Toponár forduló</option>
            <option value="81 vissza">81 vissza - Toponár forduló - Hősök temploma - Helyi autóbusz-állomás</option>
            <option value="82">82 - Helyi autóbusz-állomás - Kórház - Toponár Szabó P. u.</option>
            <option value="82 vissza">82 vissza - Toponár Szabó P. u. - Kórház - Helyi autóbusz-állomás</option>
            <option value="83">83 - Helyi autóbusz-állomás - Szabó P. u. - Toponár forduló</option>
            <option value="83 vissza">83 vissza - Toponár forduló - Szabó P. u. - Helyi autóbusz-állomás</option>
            <option value="84">84 - Helyi autóbusz-állomás - Toponár, forduló - Répáspuszta</option>
            <option value="84 vissza">84 vissza - Répáspuszta - Toponár, forduló - Helyi autóbusz-állomás</option>
            <option value="85">85 - Helyi autóbusz-állomás - Kisgát- Helyi autóbusz-állomás</option>
            <option value="86">86 - Helyi autóbusz-állomás - METYX - Szennyvíztelep</option>
            <option value="86 vissza">86 vissza - Szennyvíztelep - METYX - Helyi autóbusz-állomás</option>
            <option value="87">87 - Helyi autóbusz állomás - Videoton - METYX</option>
            <option value="87 vissza">87 vissza - METYX - Videoton - Helyi autóbusz állomás</option>
            <option value="88">88 - Helyi autóbusz-állomás - Videoton</option>
            <option value="88 vissza">88 vissza - Videoton - Helyi autóbusz-állomás</option>
            <option value="89">89 - Helyi autóbusz-állomás - Kaposvári Egyetem</option>
            <option value="89 vissza">89 vissza - Kaposvári Egyetem - Helyi autóbusz-állomás</option>
            <option value="90">90 - Helyi autóbusz-állomás - Rómahegy</option>
            <option value="90 vissza">90 vissza - Rómahegy - Helyi autóbusz-állomás</option>
            <option value="91">91 - Füredi u. csp - Pázmány P. u. - Rómahegy</option>
            <option value="91 vissza">91 vissza - Rómahegy - Pázmány P u. - Füredi u. csp</option>
        </select>
    </div>

    <!-- Advanced Route Search Button -->
    <button id="find-route" class="w-full bg-red-700 text-white py-4 rounded-lg hover:bg-black transition mb-6 flex items-center justify-center">
        <i class="fas fa-route mr-3"></i>Útvonal keresése
    </button>

    <!-- Map and Route Details Container -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2">
            <div id="map" class="w-full rounded-2xl"></div>
        </div>

        <!-- Detailed Route Information Panel -->
        <div id="route-details" class="bg-gray-50 p-6 rounded-2xl">
            <h3 class="text-2xl font-semibold mb-4 text-gray-800 flex items-center">
                <i class="fas fa-info-circle mr-3 text-red-700"></i>Útvonal Részletek
            </h3>
            <div id="route-info" class="space-y-4">
                <!-- Dynamic route information will be inserted here -->
            </div>
        </div>          
        <a href="megallok_kereso.php"><button>
        <span style="font-weight:bold" class="button_top">Megálló keresése ➤</span>
        </button>
        </a>
    </div>
</div>
<script>
let map;
let routingControl;
let markers = [];
let currentPolyline = null;
let currentMarkers = [];
let routesData = {};
let activePopup = null;

// Kaposvár központi koordinátái
const KAPOSVAR_CENTER = {
    lat: 46.3593,
    lng: 17.7967
};

// Térkép inicializálása
function initMap() {
    map = L.map('map').setView([KAPOSVAR_CENTER.lat, KAPOSVAR_CENTER.lng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    setupEventListeners();
}

// Eseménykezelők beállítása
function setupEventListeners() {
    const findRouteButton = document.getElementById('find-route');
    if (findRouteButton) {
        findRouteButton.addEventListener('click', handleRoutePlanning);
    }

    // Közlekedési mód választó gombok
    document.querySelectorAll('.transit-mode-btn').forEach(button => {
        button.addEventListener('click', function() {
            clearMap(); // Módosítva: clearRoute helyett clearMap
            
            document.querySelectorAll('.transit-mode-btn').forEach(btn => 
                btn.classList.remove('active')
            );
            
            this.classList.add('active');
            
            const complexSelect = document.getElementById('complex-route-select');
            if (this.dataset.mode === 'complex') {
                complexSelect.classList.remove('hidden');
            } else {
                complexSelect.classList.add('hidden');
            }
        });
    });
}

// Útvonaltervezés kezelése
async function handleRoutePlanning() {
    const startInput = document.getElementById('start');
    const endInput = document.getElementById('end');
    
    if (!startInput || !endInput || !startInput.value || !endInput.value) {
        showAlert('Kérem adja meg az indulási és érkezési pontot!');
        return;
    }

    try {
        clearMap();
        
        // Helyek keresése
        const startLocation = await geocodeAddress(startInput.value);
        const endLocation = await geocodeAddress(endInput.value);

        if (!startLocation || !endLocation) {
            showAlert('A megadott címek egyike nem található. Kérem ellenőrizze a bevitt adatokat.');
            return;
        }

        // Waypoints létrehozása
        const waypoints = [
            L.latLng(startLocation.lat, startLocation.lon),
            L.latLng(endLocation.lat, endLocation.lon)
        ];

        // Markerek hozzáadása és útvonal inicializálása
        addLocationMarkers(waypoints, [startLocation, endLocation]);
        showRoute(waypoints);

    } catch (error) {
        console.error('Útvonaltervezési hiba:', error);
        showAlert('Hiba történt az útvonaltervezés során. Kérem próbálja újra.');
    }
}

// Útvonal megjelenítése
function showRoute(waypoints) {
    if (routingControl) {
        map.removeControl(routingControl);
    }

    routingControl = L.Routing.control({
        waypoints: waypoints,
        routeWhileDragging: true,
        showAlternatives: true,
        lineOptions: {
            styles: [{ color: '#1a73e8', weight: 4, opacity: 0.7 }]
        },
        altLineOptions: {
            styles: [{ color: '#b31412', weight: 4, opacity: 0.6 }]
        },
        router: L.Routing.osrmv1({
            serviceUrl: 'https://router.project-osrm.org/route/v1',
            profile: 'driving'
        }),
        createMarker: function(i, wp) {
            return L.marker(wp.latLng, {
                icon: createCustomMarker(i === 0 ? 'I' : 'C')
            });
        }
    }).addTo(map);

    routingControl.on('routesfound', function(e) {
        const routes = e.routes;
        displayRouteDetails(routes[0]);
    });
}

// Útvonal részletek megjelenítése
function displayRouteDetails(route) {
    const routeInfo = document.getElementById('route-info');
    if (!route) {
        routeInfo.innerHTML = '<p>Nem található útvonal információ</p>';
        return;
    }

    const duration = Math.round(route.summary.totalTime / 60);
    const distance = (route.summary.totalDistance / 1000).toFixed(1);

    let html = `
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="mb-4 border-b pb-2">
                <div class="text-xl font-bold text-gray-800">Útvonal Összegzés</div>
                <div class="mt-2 grid grid-cols-2 gap-4">
                    <div class="flex items-center">
                        <i class="fas fa-road mr-2 text-blue-600"></i>
                        <span class="font-medium">Távolság: ${distance} km</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-clock mr-2 text-blue-600"></i>
                        <span class="font-medium">Időtartam: ${duration} perc</span>
                    </div>
                </div>
            </div>
        </div>`;

    routeInfo.innerHTML = html;
}

// Markerek hozzáadása
function addLocationMarkers(waypoints, locations) {
    waypoints.forEach((waypoint, index) => {
        const marker = L.marker(waypoint, {
            icon: createCustomMarker(index === 0 ? 'I' : 'C')
        }).addTo(map);

        const location = locations[index];
        marker.bindPopup(`
            <div class="font-bold">${index === 0 ? 'Indulás' : 'Érkezés'}</div>
            <div>${location.display_name}</div>
        `);

        markers.push(marker);
    });

    // Térkép nézet beállítása
    const bounds = L.latLngBounds(waypoints);
    map.fitBounds(bounds, { padding: [50, 50] });
}

// Térkép tisztítása
function clearMap() {
    if (routingControl) {
        map.removeControl(routingControl);
    }
    
    markers.forEach(marker => map.removeLayer(marker));
    markers = [];
    
    if (currentPolyline) {
        map.removeLayer(currentPolyline);
        currentPolyline = null;
    }

    currentMarkers.forEach(marker => {
        map.removeLayer(marker);
    });
    currentMarkers = [];
}

// Egyéni marker létrehozása
function createCustomMarker(text) {
    return L.divIcon({
        className: 'custom-marker',
        html: `<div class="marker-content">${text}</div>`,
        iconSize: [30, 30],
        iconAnchor: [15, 15]
    });
}

// Geocoding függvény
async function geocodeAddress(address) {
    try {
        const response = await fetch(
            `https://nominatim.openstreetmap.org/search?` +
            `format=json&q=${encodeURIComponent(address)}, Magyarország&limit=1&addressdetails=1`,
            {
                headers: {
                    'Accept-Language': 'hu'
                }
            }
        );
        
        const data = await response.json();
        return data[0];
    } catch (error) {
        console.error('Geocoding hiba:', error);
        throw new Error('Címkeresési hiba történt');
    }
}

// Értesítés megjelenítése
function showAlert(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'fixed top-4 right-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-lg z-50';
    alertDiv.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(alertDiv);
    setTimeout(() => alertDiv.remove(), 3000);
}

// CSS stílusok
const styles = `
.custom-marker {
    background-color: white;
    border: 2px solid #1a73e8;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: #1a73e8;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.marker-content {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: bold;
}

.leaflet-routing-container {
    background-color: white;
    padding: 1rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    max-height: 400px;
    overflow-y: auto;
}
`;

// Útvonal részletek kibővített megjelenítése
function displayRouteDetails(route) {
    const routeInfo = document.getElementById('route-info');
    if (!route) {
        routeInfo.innerHTML = '<p>Nem található útvonal információ</p>';
        return;
    }

    const duration = Math.round(route.summary.totalTime / 60);
    const distance = (route.summary.totalDistance / 1000).toFixed(1);
    const travelTime = document.getElementById('travel-time').value;
    const date = travelTime ? new Date(travelTime) : new Date();

    let html = `
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="mb-4 border-b pb-2">
                <div class="text-xl font-bold text-gray-800">Útvonal Összegzés</div>
                <div class="mt-2 space-y-2">
                    <div class="flex items-center">
                        <i class="fas fa-road mr-2 text-blue-600"></i>
                        <span class="font-medium">Távolság: ${distance} km</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-clock mr-2 text-blue-600"></i>
                        <span class="font-medium">Időtartam: ${duration} perc</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>
                        <span class="font-medium">Indulás: ${date.toLocaleString('hu-HU', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        })}</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-flag-checkered mr-2 text-blue-600"></i>
                        <span class="font-medium">Érkezés: ${new Date(date.getTime() + duration * 60000).toLocaleString('hu-HU', {
                            hour: '2-digit',
                            minute: '2-digit'
                        })}</span>
                    </div>
                </div>
            </div>
            <div class="space-y-3">
                <div class="font-semibold text-lg mb-2">Részletes útirányok:</div>`;

    route.instructions.forEach((instruction, index) => {
        const distance = Math.round(instruction.distance);
        html += `
            <div class="flex items-start p-2 hover:bg-gray-50 rounded transition duration-150">
                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                    <span class="text-blue-600 text-sm font-medium">${index + 1}</span>
                </div>
                <div class="flex-grow">
                    <div class="flex items-center">
                        <i class="fas fa-arrow-right text-blue-600 mr-2"></i>
                        <span class="text-gray-800">${instruction.text}</span>
                    </div>
                    ${distance > 0 ? `
                        <div class="text-sm text-gray-600 mt-1">
                            <i class="fas fa-arrows-alt-h mr-1"></i>
                            ${distance} méter
                        </div>
                    ` : ''}
                </div>
            </div>`;
    });

    html += `
            </div>
            <div class="mt-4 pt-3 border-t">
                <div class="text-sm text-gray-600">
                    <i class="fas fa-info-circle mr-2"></i>
                    <span>A megadott útvonal a jelenlegi forgalmi viszonyok alapján lett tervezve.</span>
                </div>
            </div>
        </div>`;

    routeInfo.innerHTML = html;
}

// Helyi járatok adatainak betöltése
async function loadRouteData() {
    try {
        const response = await fetch('helyibusz.json'); // Helyi járatok JSON fájl
        const data = await response.json();
        routesData = data;
        updateRouteOptions('complex');
    } catch (error) {
        console.error('Hiba a járatok betöltésekor:', error);
        showAlert('A helyi járatok adatainak betöltése sikertelen.');
    }
}

// Helyi járat megjelenítése
function displayLocalRoute(routeId) {
    clearMap();
    
    const route = routesData[routeId];
    if (!route) {
        showAlert('A választott járat információi nem elérhetőek.');
        return;
    }

    const stops = route.stops;
    const coordinates = stops.map(stop => [stop.lat, stop.lng]);

    // Útvonal rajzolása
    currentPolyline = L.polyline(coordinates, {
        color: '#FF0000',
        weight: 3
    }).addTo(map);

    // Megállók jelölése
    stops.forEach((stop, index) => {
        const marker = L.marker([stop.lat, stop.lng], {
            icon: createCustomMarker((index + 1).toString())
        }).addTo(map);

        marker.bindPopup(`
            <div class="p-2">
                <div class="font-bold mb-1">${stop.name}</div>
                <div class="text-sm">Érkezés: ${stop.arrival || 'N/A'}</div>
                <div class="text-sm">Indulás: ${stop.departure || 'N/A'}</div>
            </div>
        `);

        currentMarkers.push(marker);
    });

    map.fitBounds(currentPolyline.getBounds());
    displayLocalRouteDetails(route);
}

// Helyi járat részletek megjelenítése
function displayLocalRouteDetails(route) {
    const routeInfo = document.getElementById('route-info');
    const html = `
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="mb-4 border-b pb-2">
                <div class="text-xl font-bold text-gray-800">${route.name}</div>
                <div class="text-sm text-gray-600">Járatszám: ${route.routeNumber}</div>
            </div>
            <div class="space-y-3">
                <div class="font-semibold">Menetrend információk:</div>
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <div>Első indulás:</div>
                    <div>${route.firstDeparture || 'N/A'}</div>
                    <div>Utolsó indulás:</div>
                    <div>${route.lastDeparture || 'N/A'}</div>
                    <div>Járatsűrűség:</div>
                    <div>${route.frequency || 'N/A'}</div>
                </div>
                <div class="mt-4">
                    <div class="font-semibold mb-2">Megállók:</div>
                    <div class="space-y-2">
                        ${route.stops.map((stop, index) => `
                            <div class="flex items-center p-2 hover:bg-gray-50 rounded">
                                <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                    <span class="text-blue-600 text-sm">${index + 1}</span>
                                </div>
                                <div>
                                    <div class="font-medium">${stop.name}</div>
                                    ${stop.arrival ? `<div class="text-sm text-gray-600">Érkezés: ${stop.arrival}</div>` : ''}
                                    ${stop.departure ? `<div class="text-sm text-gray-600">Indulás: ${stop.departure}</div>` : ''}
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            </div>
        </div>`;

    routeInfo.innerHTML = html;
}

// Oldal betöltésekor inicializálás
document.addEventListener('DOMContentLoaded', () => {
    initMap();
    loadRouteData();
});
// Stílusok hozzáadása
const styleSheet = document.createElement('style');
styleSheet.textContent = styles;
document.head.appendChild(styleSheet);

// Oldal betöltésekor inicializálás
document.addEventListener('DOMContentLoaded', initMap);

</script>

<script>

</script>
</body>
</html>

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

$sql = "SELECT id, title, details, date FROM hirek ORDER BY date DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();

?>
<!DOCTYPE html>
<html lang="hu">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="footer.css">
    <script src="betolt.js"></script>
    <title>Kaposbusz</title>
    <style>
     @import url('https://fonts.googleapis.com/css?family=Open+Sans');

    :root {
        --primary-color:linear-gradient(to right, #211717,#b30000);
        --accent-color: #7A7474;
        --text-light: #fbfbfb;
        --background-light: #f8f9fa;
        --transition: all 0.3s ease;
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'Open Sans', sans-serif;
        color: var(--text-light);
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

/*--------------------------------------------------------------------------------------------------------CSS - OTHER PARTS----------------------------------------------------------------------------------------------*/
    .container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .hero {
        background-image: url('https://kaposvariprogramok.hu/sites/default/files/120845739_825620101509249_2047839847436415923_n.jpg');
        background-repeat: no-repeat;
        background-size: cover;
        background-position: bottom center;
        height: 100vh;
        color: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        position: relative;
        margin-bottom: 20px;
        z-index: -2;
    }

    .hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: -1;
    }

    .hero h1 {
        font-size: 46px;
        margin: -20px 0 20px;
    }

    .hero p {
        font-size: 20px;
        letter-spacing: 1px;
    }

    .content h2,
    .content h3 {
        font-size: 150%;
        margin: 20px 0;
    }

    .content p {
        color: #555;
        line-height: 30px;
        letter-spacing: 1.2px;
    }
/*--------------------------------------------------------------------------------------------------------OTHER PARTS END------------------------------------------------------------------------------------------------*/

/*UIverse card */
    .card-container{
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        max-width: 1200px; 
        margin-left: 17%;
    }

    .card {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        width: 290px;
        height: 400px;
        border-radius: 20px;
        background: #fcfcfc;
        position: relative;
        padding: 2rem;
        border: 2px solid #c3c6ce;
        transition: 0.5s ease-out;
        overflow: visible;
        margin-left: 7%;
        margin-bottom: 5%;
        
    }

    .card-details {
        color: black;
        height: 100%;
        gap: .5em;
        display: grid;
        place-content: center;
    }

    .card-button {
        transform: translate(-50%, 125%);
        width: 60%;
        border-radius: 1rem;
        border: none;
        background-color: #b30000;
        color: #fff;
        font-size: 1rem;
        padding: .5rem 1rem;
        position: absolute;
        left: 50%;
        bottom: 0;
        opacity: 0;
        transition: 0.3s ease-out;
    }

    .text-body {
        color: rgb(134, 134, 134);
    }

    /*Text*/
    .text-title {
        font-size: 1.5em;
        font-weight: bold;
    }

    /*Hover*/
    .card:hover {
        border-color:#b30000;
        box-shadow: 0 4px 18px 0 rgba(0, 0, 0, 0.25);
    }

    .card:hover .card-button {
        transform: translate(-50%, 50%);
        opacity: 1;
    }

    .card-container .card {
        display: none; /* Alapértelmezetten rejtett */
    }

    .card-container .card:nth-child(-n+6) {
        display: inline-block; /* Az első 6 kártyát mutassa */
    }

    .news-date{
        background: #b30000;
        width: 90px;
        border-radius: 3px;
        padding:3px;
        color: #fbfbfb;
    }
/*End */

/*--------------------------------------------------------------------------------------------------------CSS - MORE NEWS BUTTON-----------------------------------------------------------------------------------------*/
    .btn-53{
        color: var(--text-light);
        background-color: #b30000;
        border-radius: 30px;
        padding: 15px;
        border: none;
        font-size: 1.5rem;
        margin-left: 42.5%;
        width: 15%;
    }

    .btn-53:hover{
        width: 16%;
        margin-left: 42%;
        background-color:#b60220;
        color:#f5e1e1;
    }
/*--------------------------------------------------------------------------------------------------------MORE NEWS BUTTON END-------------------------------------------------------------------------------------------*/

/*--------------------------------------------------------------------------------------------------------CSS - @MEDIA---------------------------------------------------------------------------------------------------*/
    @media (max-width: 480px) {
            /* Adjust the header */
            .header h1 {
                font-size: 1.5rem;
                margin-left: 12%;
                text-align: center;
            }

            /* Navigation adjustments */
            .nav-wrapper {
                position: static;
                text-align: center;
            }

            /* Hero section */
            .hero {
                height: 50vh;
                background-size: cover;
                padding: 1rem;
                text-align: center;
            }

            .hero h1 {
                font-size: 1.5rem;
            }

            .hero p {
                font-size: 1rem;
            }

            /* Card container and cards */
            .card-container {
                margin-left: 0;
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .card {
                width: 90%;
                margin: 1rem 0;
            }

            .card img {
                width: 100px;
                height: 100px;
            }

            .card-button {
                font-size: 0.8rem;
                padding: 0.4rem 1rem;
            }

            .btn-53 {
                width: 60%;
                font-size: 1.3rem;
                padding: 0.5rem;
                margin-left: 20%;
            }

        }
/*--------------------------------------------------------------------------------------------------------@MEDIA END-----------------------------------------------------------------------------------------------------*/
  
/*--------------------------------------------------------------------------------------------------------CSS - FOOTER---------------------------------------------------------------------------------------------------*/
    footer {
        text-align: center;
        padding: 10px;
        background-color: var(--primary-color);
        color: var(--text-light);
        border-radius: 10px;
        margin-top: 20px;
        box-shadow: var(--shadow);
        padding: 3rem 2rem;
        margin-top: 4rem;
    }

    .footer-content {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
    }

    .footer-section h2 {
        margin-bottom: 1rem;
        color: var(--text-light);
    }

    .footer-links {
        list-style: none;
    }

    .footer-links li {
        margin-bottom: 0.5rem;
    }

    .footer-links a {
        color: var(--text-light);
        text-decoration: none;
        transition: var(--transition);
    }

    .footer-links a:hover {
        color: var(--accent-color);
    }
/*--------------------------------------------------------------------------------------------------------FOOTER END-----------------------------------------------------------------------------------------------------*/

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

<!-- -----------------------------------------------------------------------------------------------------HTML - HERO-------------------------------------------------------------------------------------------------- -->
    <div class="hero">
      <div class="container">
        <h1>Üdvözöljük a Kaposbusz megújult weboldalán</h1>
      </div>
    </div>
<!-- -----------------------------------------------------------------------------------------------------HERO END----------------------------------------------------------------------------------------------------- -->

<!-- -----------------------------------------------------------------------------------------------------HTML - NEWS-------------------------------------------------------------------------------------------------- -->
    <h1 style="color: #b30000; padding-left: 20%; margin-bottom: 3%; margin-top: 5%;">Hírek</h1>

    <div class="card-container">
    <?php
// API képek lekérése
$kepek = [];
$api_url = "http://localhost:3000/api/kepek";
$response = file_get_contents($api_url);
if ($response) {
    $kepek = json_decode($response, true);
}

// Képek indexelése
$kepek_by_id = [];
foreach ($kepek as $kep) {
    $kepek_by_id[$kep['news_id']] = $kep['image_url'];
}

// CSS stílusok
echo '<style>
    .news-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        display: flex;
        flex-direction: column;
        height: 450px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .news-card.hidden {
        display: none !important;
    }
    .card-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 25px;
        padding: 25px;
        margin: 0 auto;
        max-width: 1400px;
    }
    .load-more-container {
        text-align: center;
        margin: 20px 0;
        padding: 20px;
    }
    .btn-53 {
        display: inline-block;
        padding: 15px 30px;
        background-color: #e63946;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 500;
        font-size: 16px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .btn-53:hover {
        background-color: #d62b38;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }
    .details-btn {
        display: inline-block;
        padding: 12px 24px;
        background: #e63946;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.3s ease;
        width: 80%;
        text-align: center;
        margin: 0 auto;
    }
    .details-btn:hover {
        background: #d62b38;
        transform: translateY(-2px);
    }
    @media (max-width: 768px) {
        .card-container {
            grid-template-columns: 1fr;
            padding: 15px;
        }
    }
</style>';

if ($stmt->rowCount() > 0) {
    echo '<div class="card-container">';

    $counter = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $hiddenClass = $counter >= 6 ? ' hidden' : '';
        
        // Teljes kártya legyen kattintható
        echo '<div class="news-card' . $hiddenClass . '">';
        
        // Kép konténer
        echo '<div style="
            width: 100%;
            height: 200px;
            overflow: hidden;
            position: relative;">';
        
        // Default kép ha nincs a tömbben
        $image_url = isset($kepek_by_id[$row["id"]]) ? 
            htmlspecialchars($kepek_by_id[$row["id"]]) : 
            '/assets/img/default-bus.png';
        
        echo '<img src="' . $image_url . '" 
            alt="' . htmlspecialchars($row["title"]) . '" 
            style="
                width: 100%;
                height: 100%;
                object-fit: cover;">';
        
        // Dátum badge
        echo '<div style="
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(230, 57, 70, 0.9);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 0.85em;
            font-weight: 500;">' . 
            htmlspecialchars($row["date"]) . 
        '</div>';
        echo '</div>';
        
        // Tartalom konténer
        echo '<div style="
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;">';
        
        echo '<h2 style="
            margin: 0 0 10px 0;
            font-size: 1.3em;
            color: #1a1a1a;
            font-weight: 600;
            line-height: 1.3;">' . 
            htmlspecialchars($row["title"]) . 
        '</h2>';
        
        echo '<p style="
            margin: 0 0 20px 0;
            color: #666;
            line-height: 1.5;
            flex-grow: 1;">' . 
            htmlspecialchars(substr($row["details"], 0, 100)) . '...</p>';
        
        // Részletek gomb
        echo '<a href="hirek.php?id=' . $row["id"] . '" class="details-btn">Részletek</a>';
        
        echo '</div>'; // Tartalom konténer vége
        echo '</div>'; // Kártya vége
        
        $counter++;
    }
    echo '</div>';

    // Még több hír gomb
    if ($counter > 6) {
     
    }
} else {
    echo '<p style="text-align: center; padding: 20px; color: #666;">Nincsenek hírek.</p>';
}
?>

    </div>
<!-- -----------------------------------------------------------------------------------------------------NEWS END----------------------------------------------------------------------------------------------------- -->

<!-- -----------------------------------------------------------------------------------------------------HTML - MORE NEWS BUTTON-------------------------------------------------------------------------------------- -->
   <!-- A gomb HTML kódja -->
<button class="btn-53" id="btnMoreNews" data-expanded="false">
    Még több hír >> 
</button>

<!-- A javított JavaScript kód -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnMoreNews = document.getElementById('btnMoreNews');
    let isExpanded = false;

    function toggleNews() {
        const cards = document.querySelectorAll('.news-card');
        isExpanded = !isExpanded;
        
        cards.forEach((card, index) => {
            if (index >= 6) {
                if (isExpanded) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            }
        });
        
        // Gomb szövegének frissítése
        btnMoreNews.textContent = isExpanded ? '<< Kevesebb' : 'Még több hír >>';
    }

    // Event listener hozzáadása a gombhoz
    btnMoreNews.addEventListener('click', toggleNews);
});
</script>

<!-- CSS kiegészítés a hidden osztályhoz -->
<style>
.news-card.hidden {
    display: none !important;
}
</style>
<!-- -----------------------------------------------------------------------------------------------------MORE NEWS BUTTON END----------------------------------------------------------------------------------------- -->

<!-- -----------------------------------------------------------------------------------------------------HTML - FOOTER------------------------------------------------------------------------------------------------ -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h2>Kaposvár közlekedés</h2>
                <p style="font-style: italic">Megbízható közlekedési szolgáltatások<br> az Ön kényelméért már több mint 50 éve.</p><br>
                <div class="social-links">
                    <a style="color: darkblue;" href="https://www.facebook.com/VOLANBUSZ/"><i class="fab fa-facebook"></i></a>
                    <a style="color: lightblue"href="https://x.com/volanbusz_hu?mx=2"><i class="fab fa-twitter"></i></a>
                    <a style="color: red"href="https://www.instagram.com/volanbusz/"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
           
            <div  class="footer-section">
                <h3>Elérhetőség</h3>
                <ul class="footer-links">
                    <li><i class="fas fa-phone"></i> +36-82/411-850</li>
                    <li><i class="fas fa-envelope"></i> titkarsag@kkzrt.hu</li>
                    <li><i class="fas fa-map-marker-alt"></i> 7400 Kaposvár, Cseri út 16.</li>
                    <li><i class="fas fa-map-marker-alt"></i> Áchim András utca 1.</li>
                </ul>
            </div>
        </div>
        <div style="text-align: center; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.1);">
            <p>© 2024 Kaposvár közlekedési Zrt. Minden jog fenntartva.</p>
        </div>
    </footer>
<!-- -----------------------------------------------------------------------------------------------------FOOTER END--------------------------------------------------------------------------------------------------- -->
    <script>
/*--------------------------------------------------------------------------------------------------------JS - DROPDOWNMENU----------------------------------------------------------------------------------------------*/
        document.getElementById('menuBtn').addEventListener('click', function() {
            this.classList.toggle('active');
            document.getElementById('dropdownMenu').classList.toggle('active');
        });

        // Kívülre kattintás esetén bezárjuk a menüt
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('dropdownMenu');
            const menuBtn = document.getElementById('menuBtn');
            
            if (!menu.contains(event.target) && !menuBtn.contains(event.target)) {
                menu.classList.remove('active');
                menuBtn.classList.remove('active');
            }
        });

        // Aktív oldal jelölése
        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = window.location.pathname.split('/').pop();
            const menuItems = document.querySelectorAll('.menu-items a');
            
            menuItems.forEach(item => {
                if (item.getAttribute('href') === currentPage) {
                    item.classList.add('active');
                }
            });
        });
/*--------------------------------------------------------------------------------------------------------DROPDOWNMENU END-----------------------------------------------------------------------------------------------*/
    
    document.getElementById('btnMoreNews').addEventListener('click', function() {
        const cards = document.querySelectorAll('.card-container .card');
        const isExpanded = this.getAttribute('data-expanded') === 'true'; // Ellenőrzi a jelenlegi állapotot

        if (isExpanded) {
            // Állapot visszaállítása: csak 6 kártya
            cards.forEach((card, index) => {
                card.style.display = index < 6 ? 'inline-block' : 'none';
            });
            this.textContent = 'Még több hír >>'; // Visszaállítja a szöveget
            this.setAttribute('data-expanded', 'false'); // Állapot frissítése
        } else {
            // Minden kártya megjelenítése
            cards.forEach(card => {
                card.style.display = 'inline-block';
            });
            this.textContent = 'Kevesebb hír <<'; // Szöveg frissítése
            this.setAttribute('data-expanded', 'true'); // Állapot frissítése
        }
   
});
</script>
  </body>
</html>

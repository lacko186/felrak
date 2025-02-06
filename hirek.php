<?php
// Adatbázis kapcsolat
require_once 'config.php';

if (!isset($_GET['id'])) {
    echo 'Hír nem található!';
    exit();
}

$id = (int)$_GET['id'];

$sql = "SELECT title, details, date FROM hirek WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

$news = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$news) {
    echo 'Hír nem található!';
    exit();
}
?>
<!DOCTYPE html>
<html lang="hu">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="header.css">
    <script src="betolt.js"></script>
    <title>Kaposbusz</title>
    <style>
     @import url('https://fonts.googleapis.com/css?family=Open+Sans');

    :root {
        --primary-color:linear-gradient(to right, #211717,#b30000);
        --accent-color: #7A7474;
        --text-light: #fbfbfb;
        --secondary-color: #3498db;
        --hover-color: #2980b9;
        --background-light: #f8f9fa;
        --shadow-color: rgba(0, 0, 0, 0.5);
        --transition: all 0.3s ease;
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'Open Sans', sans-serif;
        color: #222;
        background: #f5f5f5;
    }

/*--------------------------------------------------------------------------------------------------------CSS - HEADER---------------------------------------------------------------------------------------------------*/
        .header {
            position: relative;
            background: var(--primary-color);
            color: var(--text-light);
            padding: 1rem;
            box-shadow: 0 2px 10px var(--shadow-color);
        }

        .header h1 {
            margin-left: 2%;
            text-align: center;
            font-size: 2rem;
            padding: 1rem 0;
            margin-left: 35%;
            display: inline-block;
        }

        .backBtn{
            display: inline-block;
            width: 3%;
            background: #372E2E;
            border: none;
            box-shadow: 0 2px 10px var(--shadow-color);
        }

        .backBtn:hover{
            background: #b40000;
        }

        .backBtn i{
            height: 30px;
            color: var(--text-light);
            padding-top: 20px;
        }
/*--------------------------------------------------------------------------------------------------------HEADER END-----------------------------------------------------------------------------------------------------*/

    #news-container{
        width:1200px;
        height:450px;
        margin-left:20%;
        margin-top:2%
    }

/*--------------------------------------------------------------------------------------------------------CSS - FOOTER---------------------------------------------------------------------------------------------------*/
    footer {
        text-align: center;
        padding: 10px;
        background-color: var(--primary-color);
        color: var(--text-light);
        border-radius: 10px;
        margin-top: 20px;
        box-shadow: var(--shadow);
        background: var(--primary-color);
        color: var(--text-light);
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
        color: var(--text-color);
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


/*--------------------------------------------------------------------------------------------------------CSS - @MEDIA---------------------------------------------------------------------------------------------------*/
        @media (max-width: 480px) {
            h1 {
                font-size: 1.5rem;
            }

            .header h1{
                margin-right: 1%;
                margin-left: 3%;
                font-size: 1.4rem;
            }

            .backBtn{
                width: 15%;
            }

            #news-container{
                width:340px;
                height:850px;
                margin-left:10%;
                margin-top:10%
            }

            #details{
                width: 330px;
                font-size: 1.15rem;
            }
            
            footer{
                width: 415px;
            }

            .header{
                width: 415px;
            }

            body{
                width: 415px;
            }
        }

        @media (max-width: 380px) {
            h1 {
                font-size: 1.5rem;
            }
            
            .header h1{
                margin-left: 2%;
            }

            .backBtn{
                width: 15%;
            }

            #news-container{
                max-width:335px;
                max-height:850px;
                margin-left:5%;
                margin-top:10%
            }

            #details{
                width: 375px;
            }

            footer{
                width: 375px;
            }

            .header{
                width: 375px;
            }

            body{
                width: 375px;
            }
        }
/*--------------------------------------------------------------------------------------------------------@MEDIA END-----------------------------------------------------------------------------------------------------*/
  
    </style>
  </head>
  <body>
<!-- -----------------------------------------------------------------------------------------------------HTML - HEADER------------------------------------------------------------------------------------------------ -->
  <div class="header">
        <button class="backBtn" id=backBtn><img src="back.png" style="width:15px;height:40px;padding-top:12px;padding-bottom:10px;"></button>
        <h1>Kaposvári Közlekedési Zrt. Hírek</h1>
    </div>
<!-- -----------------------------------------------------------------------------------------------------HEADER END--------------------------------------------------------------------------------------------------- -->

<!-- -----------------------------------------------------------------------------------------------------HTML - NEWS CONTAINER---------------------------------------------------------------------------------------- -->
    <div id="news-container">
    <?php
    // Kép betöltése az API-ról az URL id paramétere alapján
    $image_url = null;
    try {
        $api_response = file_get_contents('http://localhost:3000/api/kepek');
        if ($api_response) {
            $kepek = json_decode($api_response, true);
            foreach ($kepek as $kep) {
                if ($kep['news_id'] == $_GET['id']) {
                    $image_url = $kep['image_url'];
                    break;
                }
            }
        }
    } catch (Exception $e) {
        // Hiba esetén nincs képmegjelenítés
    }

    // Ha van kép, megjelenítjük
    if ($image_url): ?>
        <div style="margin-bottom: 30px;">
            <img src="<?php echo htmlspecialchars($image_url); ?>" 
                 alt="<?php echo htmlspecialchars($news['title']); ?>"
                 style="width: 100%; max-height: 400px; object-fit: cover; border-radius: 8px;">
        </div>
    <?php endif; ?>
        <h1 style="padding-bottom:20px;"><?php echo htmlspecialchars($news['title']); ?></h1>
        <p style="background: #b30000;width: 90px;border-radius: 3px;padding:3px;color: #fbfbfb;margin-bottom:20px;"><?php echo htmlspecialchars($news['date']); ?></p>
        <p style="margin-bottom:20px;"><?php echo nl2br(htmlspecialchars($news['details'])); ?></p>
        
    </div>
<!-- -----------------------------------------------------------------------------------------------------NEWS CONTAINER END------------------------------------------------------------------------------------------- -->

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
/*---------------------------------------------------------------------------------------------------------JAVASCRIPT - BACK BUTTON--------------------------------------------------------------------------------------*/
    document.getElementById('backBtn').addEventListener('click', function() {
        window.location.href = 'fooldal.php'; 
    });
/*---------------------------------------------------------------------------------------------------------BACK BUTTON END-----------------------------------------------------------------------------------------------*/
    </script>
  </body>
</html>
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

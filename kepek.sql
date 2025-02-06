-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2025. Feb 06. 12:49
-- Kiszolgáló verziója: 10.4.32-MariaDB
-- PHP verzió: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `kaposvar`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `kepek`
--

CREATE TABLE `kepek` (
  `id` int(11) NOT NULL,
  `news_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `kepek`
--

INSERT INTO `kepek` (`id`, `news_id`, `image_url`, `created_at`) VALUES
(1, 1, 'https://www.kaposbusz.hu/static/files/oldalak/1-1.jpg', '2025-02-06 11:19:53'),
(2, 2, 'https://kaposvarmost.hu/files/7/0/702bca6066127954e5c184a708d6727c.jpg', '2025-02-06 11:19:53'),
(3, 3, 'https://www.tourinformkaposvar.hu/images/kaposvar/kaposvarfoter295.jpg', '2025-02-06 11:19:53'),
(4, 4, 'https://www.kaposbusz.hu/static/images/news-images/22.jpg', '2025-02-06 11:19:53'),
(5, 5, 'https://i.ytimg.com/vi/zJyyW5-kDRw/maxresdefault.jpg', '2025-02-06 11:19:53'),
(6, 6, 'https://www.tourinformkaposvar.hu/images/kaposvar/vasutallomas2.jpg', '2025-02-06 11:19:53'),
(7, 7, 'https://www.tourinformkaposvar.hu/images/kaposvar/vasutallomas.jpg', '2025-02-06 11:19:53'),
(8, 8, 'https://kaposvariprogramok.hu/sites/default/files/120845739_825620101509249_2047839847436415923_n.jpg', '2025-02-06 11:19:53'),
(9, 9, 'https://www.tourinformkaposvar.hu/images/esterhazy_hid/20201111_150411.jpg', '2025-02-06 11:19:53');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `kepek`
--
ALTER TABLE `kepek`
  ADD PRIMARY KEY (`id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `kepek`
--
ALTER TABLE `kepek`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

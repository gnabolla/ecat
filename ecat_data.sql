-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 03, 2025 at 11:58 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecat`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL COMMENT 'Store hashed passwords only (e.g., using password_hash())',
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role_id` int(11) NOT NULL COMMENT 'Foreign key linking to the roles table',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Allow disabling accounts without deleting',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Stores login details for administrative users';

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`user_id`, `username`, `password_hash`, `full_name`, `email`, `role_id`, `is_active`, `last_login`, `created_at`, `updated_at`) VALUES
(2, 'isuroxas', '$2y$10$879bWIdCN1.UEEj9/kGM9ue7PZoDbxIF.ojvO5qwcpsVxkY/1jTDO', 'isu roxas', 'isuroxas@gmail.com', 1, 1, '2025-04-03 21:57:13', '2025-04-03 21:56:59', '2025-04-03 21:57:13');

-- --------------------------------------------------------

--
-- Table structure for table `attempt_scores_by_subject`
--

CREATE TABLE `attempt_scores_by_subject` (
  `score_id` int(11) NOT NULL,
  `attempt_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `items_attempted` int(11) NOT NULL,
  `items_correct` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attempt_scores_by_subject`
--

INSERT INTO `attempt_scores_by_subject` (`score_id`, `attempt_id`, `subject_id`, `score`, `items_attempted`, `items_correct`) VALUES
(1, 1, 8, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `barangays`
--

CREATE TABLE `barangays` (
  `barangay_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `municipality_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barangays`
--

INSERT INTO `barangays` (`barangay_id`, `name`, `municipality_id`) VALUES
(1, 'Amistad', 1),
(2, 'Antonino ', 1),
(3, 'Apanay', 1),
(4, 'Aurora', 1),
(5, 'Bagnos', 1),
(6, 'Bagong Sikat', 1),
(7, 'Bantug-Petines', 1),
(8, 'Bonifacio', 1),
(9, 'Burgos', 1),
(10, 'Calaocan ', 1),
(11, 'Callao', 1),
(12, 'Dagupan', 1),
(13, 'Inanama', 1),
(14, 'Linglingay', 1),
(15, 'M.H. del Pilar', 1),
(16, 'Mabini', 1),
(17, 'Magsaysay ', 1),
(18, 'Mataas na Kahoy', 1),
(19, 'Paddad', 1),
(20, 'Rizal', 1),
(21, 'Rizaluna', 1),
(22, 'Salvacion', 1),
(23, 'San Antonio ', 1),
(24, 'San Fernando', 1),
(25, 'San Francisco', 1),
(26, 'San Juan', 1),
(27, 'San Pablo', 1),
(28, 'San Pedro', 1),
(29, 'Santa Cruz', 1),
(30, 'Santa Maria', 1),
(31, 'Santo Domingo', 1),
(32, 'Santo Tomas', 1),
(33, 'Victoria', 1),
(34, 'Zamora', 1),
(35, 'Allangigan', 2),
(36, 'Aniog', 2),
(37, 'Baniket', 2),
(38, 'Bannawag', 2),
(39, 'Bantug', 2),
(40, 'Barangcuag', 2),
(41, 'Baui', 2),
(42, 'Bonifacio', 2),
(43, 'Buenavista', 2),
(44, 'Bunnay', 2),
(45, 'Calabayan-Minanga', 2),
(46, 'Calaccab', 2),
(47, 'Calaocan', 2),
(48, 'Kalusutan', 2),
(49, 'Campanario', 2),
(50, 'Canangan', 2),
(51, 'Centro I ', 2),
(52, 'Centro II ', 2),
(53, 'Centro III ', 2),
(54, 'Consular', 2),
(55, 'Cumu', 2),
(56, 'Dalakip', 2),
(57, 'Dalenat', 2),
(58, 'Dipaluda', 2),
(59, 'Duroc', 2),
(60, 'Lourdes', 2),
(61, 'Esperanza', 2),
(62, 'Fugaru', 2),
(63, 'Liwliwa', 2),
(64, 'Ingud Norte', 2),
(65, 'Ingud Sur', 2),
(66, 'La Suerte', 2),
(67, 'Lomboy', 2),
(68, 'Loria', 2),
(69, 'Mabuhay', 2),
(70, 'Macalauat', 2),
(71, 'Macaniao', 2),
(72, 'Malannao', 2),
(73, 'Malasin', 2),
(74, 'Mangandingay', 2),
(75, 'Minanga Proper', 2),
(76, 'Pappat', 2),
(77, 'Pissay', 2),
(78, 'Ramona', 2),
(79, 'Rancho Bassit', 2),
(80, 'Rang-ayan', 2),
(81, 'Salay', 2),
(82, 'San Ambrocio', 2),
(83, 'San Guillermo', 2),
(84, 'San Isidro', 2),
(85, 'San Marcelo', 2),
(86, 'San Roque', 2),
(87, 'San Vicente', 2),
(88, 'Santo Niño', 2),
(89, 'Saranay', 2),
(90, 'Sinabbaran', 2),
(91, 'Victory', 2),
(92, 'Viga', 2),
(93, 'Villa Domingo', 2),
(94, 'Apiat', 3),
(95, 'Bagnos', 3),
(96, 'Bagong Tanza', 3),
(97, 'Ballesteros', 3),
(98, 'Bannagao', 3),
(99, 'Bannawag', 3),
(100, 'Bolinao', 3),
(101, 'Caipilan', 3),
(102, 'Camarunggayan', 3),
(103, 'Dalig Kalinga', 3),
(104, 'Diamantina', 3),
(105, 'Divisoria', 3),
(106, 'Esperanza East', 3),
(107, 'Esperanza West', 3),
(108, 'Kalabaza', 3),
(109, 'Rizalina', 3),
(110, 'Macatal', 3),
(111, 'Malasin', 3),
(112, 'Nampicuan', 3),
(113, 'Villa Nuesa', 3),
(114, 'Panecien', 3),
(115, 'San Andres', 3),
(116, 'San Jose ', 3),
(117, 'San Rafael', 3),
(118, 'San Ramon', 3),
(119, 'Santa Rita', 3),
(120, 'Santa Rosa', 3),
(121, 'Saranay', 3),
(122, 'Sili', 3),
(123, 'Victoria', 3),
(124, 'Villa Fugu', 3),
(125, 'San Juan ', 3),
(126, 'San Pedro-San Pablo ', 3),
(127, 'Andabuen', 4),
(128, 'Ara', 4),
(129, 'Binogtungan', 4),
(130, 'Capuseran', 4),
(131, 'Dagupan', 4),
(132, 'Danipa', 4),
(133, 'District II ', 4),
(134, 'Gomez', 4),
(135, 'Guilingan', 4),
(136, 'La Salette', 4),
(137, 'Makindol', 4),
(138, 'Maluno Norte', 4),
(139, 'Maluno Sur', 4),
(140, 'Nacalma', 4),
(141, 'New Magsaysay', 4),
(142, 'District I ', 4),
(143, 'Punit', 4),
(144, 'San Carlos', 4),
(145, 'San Francisco', 4),
(146, 'Santa Cruz', 4),
(147, 'Sevillana', 4),
(148, 'Sinipit', 4),
(149, 'Lucban', 4),
(150, 'Villaluz', 4),
(151, 'Yeban Norte', 4),
(152, 'Yeban Sur', 4),
(153, 'Santiago', 4),
(154, 'Placer', 4),
(155, 'Balliao', 4),
(156, 'Bacnor East', 5),
(157, 'Bacnor West', 5),
(158, 'Caliguian ', 5),
(159, 'Catabban', 5),
(160, 'Cullalabo Del Norte', 5),
(161, 'Cullalabo San Antonio', 5),
(162, 'Cullalabo Del Sur', 5),
(163, 'Dalig', 5),
(164, 'Malasin', 5),
(165, 'Masigun', 5),
(166, 'Raniag', 5),
(167, 'San Bonifacio', 5),
(168, 'San Miguel', 5),
(169, 'San Roque', 5),
(170, 'Aggub', 6),
(171, 'Anao', 6),
(172, 'Angancasilian', 6),
(173, 'Balasig', 6),
(174, 'Cansan', 6),
(175, 'Casibarag Norte', 6),
(176, 'Casibarag Sur', 6),
(177, 'Catabayungan', 6),
(178, 'Cubag', 6),
(179, 'Garita', 6),
(180, 'Luquilu', 6),
(181, 'Mabangug', 6),
(182, 'Magassi', 6),
(183, 'Ngarag', 6),
(184, 'Pilig Abajo', 6),
(185, 'Pilig Alto', 6),
(186, 'Centro ', 6),
(187, 'San Bernardo', 6),
(188, 'San Juan', 6),
(189, 'Saui', 6),
(190, 'Tallag', 6),
(191, 'Ugad', 6),
(192, 'Union', 6),
(193, 'Masipi East', 6),
(194, 'Masipi West', 6),
(195, 'San Antonio', 6),
(196, 'Rang-ay', 7),
(197, 'Calaocan', 7),
(198, 'Canan', 7),
(199, 'Centro ', 7),
(200, 'Culing Centro', 7),
(201, 'Culing East', 7),
(202, 'Culing West', 7),
(203, 'Del Corpuz', 7),
(204, 'Del Pilar', 7),
(205, 'Diamantina', 7),
(206, 'La Paz', 7),
(207, 'Luzon', 7),
(208, 'Macalaoat', 7),
(209, 'Magdalena', 7),
(210, 'Magsaysay', 7),
(211, 'Namnama', 7),
(212, 'Nueva Era', 7),
(213, 'Paraiso', 7),
(214, 'Sampaloc', 7),
(215, 'San Andres', 7),
(216, 'Saranay', 7),
(217, 'Tandul', 7),
(218, 'Alicaocao', 7),
(219, 'Alinam', 7),
(220, 'Amobocan', 7),
(221, 'Andarayan', 7),
(222, 'Baculod', 7),
(223, 'Baringin Norte', 7),
(224, 'Baringin Sur', 7),
(225, 'Buena Suerte', 7),
(226, 'Bugallon', 7),
(227, 'Buyon', 7),
(228, 'Cabaruan', 7),
(229, 'Cabugao', 7),
(230, 'Carabatan Chica', 7),
(231, 'Carabatan Grande', 7),
(232, 'Carabatan Punta', 7),
(233, 'Carabatan Bacareno', 7),
(234, 'Casalatan', 7),
(235, 'San Pablo', 7),
(236, 'Cassap Fuera', 7),
(237, 'Catalina', 7),
(238, 'Culalabat', 7),
(239, 'Dabburab', 7),
(240, 'De Vera', 7),
(241, 'Dianao', 7),
(242, 'Disimuray', 7),
(243, 'District I ', 7),
(244, 'District II ', 7),
(245, 'District III ', 7),
(246, 'Duminit', 7),
(247, 'Faustino', 7),
(248, 'Gagabutan', 7),
(249, 'Gappal', 7),
(250, 'Guayabal', 7),
(251, 'Labinab', 7),
(252, 'Linglingay', 7),
(253, 'Mabantad', 7),
(254, 'Maligaya', 7),
(255, 'Manaoag', 7),
(256, 'Marabulig I', 7),
(257, 'Marabulig II', 7),
(258, 'Minante I', 7),
(259, 'Minante II', 7),
(260, 'Nagcampegan', 7),
(261, 'Naganacan', 7),
(262, 'Nagrumbuan', 7),
(263, 'Nungnungan I', 7),
(264, 'Nungnungan II', 7),
(265, 'Pinoma', 7),
(266, 'Rizal', 7),
(267, 'Rogus', 7),
(268, 'San Antonio', 7),
(269, 'San Fermin', 7),
(270, 'San Francisco', 7),
(271, 'San Isidro', 7),
(272, 'San Luis', 7),
(273, 'Santa Luciana', 7),
(274, 'Santa Maria', 7),
(275, 'Sillawit', 7),
(276, 'Sinippil', 7),
(277, 'Tagaran', 7),
(278, 'Turayong', 7),
(279, 'Union', 7),
(280, 'Villa Concepcion', 7),
(281, 'Villa Luna', 7),
(282, 'Villaflor', 7),
(283, 'Aguinaldo', 8),
(284, 'Calimaturod', 8),
(285, 'Capirpiriwan', 8),
(286, 'Caquilingan', 8),
(287, 'Dallao', 8),
(288, 'Gayong', 8),
(289, 'Laurel', 8),
(290, 'Magsaysay', 8),
(291, 'Malapat', 8),
(292, 'Osmena', 8),
(293, 'Quezon', 8),
(294, 'Quirino', 8),
(295, 'Rizaluna', 8),
(296, 'Roxas Pob.', 8),
(297, 'Sagat', 8),
(298, 'San Juan', 8),
(299, 'Taliktik', 8),
(300, 'Tanggal', 8),
(301, 'Tarinsing', 8),
(302, 'Turod Norte', 8),
(303, 'Turod Sur', 8),
(304, 'Villamiemban', 8),
(305, 'Villamarzo', 8),
(306, 'Anonang', 8),
(307, 'Camarao', 8),
(308, 'Wigan', 8),
(309, 'Ayod', 9),
(310, 'Bucal Sur', 9),
(311, 'Bucal Norte', 9),
(312, 'Dibulo', 9),
(313, 'Digumased ', 9),
(314, 'Dimaluade', 9),
(315, 'Dicambangan', 10),
(316, 'Dicaroyan', 10),
(317, 'Dicatian', 10),
(318, 'Bicobian', 10),
(319, 'Dilakit', 10),
(320, 'Dimapnat', 10),
(321, 'Dimapula ', 10),
(322, 'Dimasalansan', 10),
(323, 'Dipudo', 10),
(324, 'Dibulos', 10),
(325, 'Ditarum', 10),
(326, 'Sapinit', 10),
(327, 'Angoluan', 11),
(328, 'Annafunan', 11),
(329, 'Arabiat', 11),
(330, 'Aromin', 11),
(331, 'Babaran', 11),
(332, 'Bacradal', 11),
(333, 'Benguet', 11),
(334, 'Buneg', 11),
(335, 'Busilelao', 11),
(336, 'Caniguing', 11),
(337, 'Carulay', 11),
(338, 'Castillo', 11),
(339, 'Dammang East', 11),
(340, 'Dammang West', 11),
(341, 'Dicaraoyan', 11),
(342, 'Dugayong', 11),
(343, 'Fugu', 11),
(344, 'Garit Norte', 11),
(345, 'Garit Sur', 11),
(346, 'Gucab', 11),
(347, 'Gumbauan', 11),
(348, 'Ipil', 11),
(349, 'Libertad', 11),
(350, 'Mabbayad', 11),
(351, 'Mabuhay', 11),
(352, 'Madadamian', 11),
(353, 'Magleticia', 11),
(354, 'Malibago', 11),
(355, 'Maligaya', 11),
(356, 'Malitao', 11),
(357, 'Narra', 11),
(358, 'Nilumisu', 11),
(359, 'Pag-asa', 11),
(360, 'Pangal Norte', 11),
(361, 'Pangal Sur', 11),
(362, 'Rumang-ay', 11),
(363, 'Salay', 11),
(364, 'Salvacion', 11),
(365, 'San Antonio Ugad', 11),
(366, 'San Antonio Minit', 11),
(367, 'San Carlos', 11),
(368, 'San Fabian', 11),
(369, 'San Felipe', 11),
(370, 'San Juan', 11),
(371, 'San Manuel', 11),
(372, 'San Miguel', 11),
(373, 'San Salvador', 11),
(374, 'Santa Ana', 11),
(375, 'Santa Cruz', 11),
(376, 'Santa Maria', 11),
(377, 'Santa Monica', 11),
(378, 'Santo Domingo', 11),
(379, 'Silauan Sur ', 11),
(380, 'Silauan Norte ', 11),
(381, 'Sinabbaran', 11),
(382, 'Soyung', 11),
(383, 'Taggappan', 11),
(384, 'Tuguegarao', 11),
(385, 'Villa Campo', 11),
(386, 'Villa Fermin', 11),
(387, 'Villa Rey', 11),
(388, 'Villa Victoria', 11),
(389, 'Cabugao ', 11),
(390, 'Diasan', 11),
(391, 'Barcolan', 12),
(392, 'Buenavista', 12),
(393, 'Dammao', 12),
(394, 'Furao', 12),
(395, 'Guibang', 12),
(396, 'Lenzon', 12),
(397, 'Linglingay', 12),
(398, 'Mabini', 12),
(399, 'Pintor', 12),
(400, 'District I ', 12),
(401, 'District II ', 12),
(402, 'District III ', 12),
(403, 'Rizal', 12),
(404, 'Songsong', 12),
(405, 'Union', 12),
(406, 'Upi', 12),
(407, 'Cabeseria 27', 12),
(408, 'Aggasian', 12),
(409, 'Alibagu', 12),
(410, 'Allinguigan 1st', 12),
(411, 'Allinguigan 2nd', 12),
(412, 'Allinguigan 3rd', 12),
(413, 'Arusip', 12),
(414, 'Baculod ', 12),
(415, 'Bagumbayan ', 12),
(416, 'Baligatan', 12),
(417, 'Ballacong', 12),
(418, 'Bangag', 12),
(419, 'Cabeseria 5', 12),
(420, 'Batong-Labang', 12),
(421, 'Bigao', 12),
(422, 'Cabeseria 4', 12),
(423, 'Cabannungan 1st', 12),
(424, 'Cabannungan 2nd', 12),
(425, 'Cabeseria 6 & 24', 12),
(426, 'Cabeseria 19', 12),
(427, 'Cabeseria 25', 12),
(428, 'Cabeseria 3', 12),
(429, 'Cabeseria 23', 12),
(430, 'Cadu', 12),
(431, 'Calamagui 1st', 12),
(432, 'Calamagui 2nd', 12),
(433, 'Camunatan', 12),
(434, 'Capellan', 12),
(435, 'Capo', 12),
(436, 'Cabeseria 9 and 11', 12),
(437, 'Carikkikan Norte', 12),
(438, 'Carikkikan Sur', 12),
(439, 'Cabeseria 14 and 16', 12),
(440, 'Cabeseria 2', 12),
(441, 'Fugu', 12),
(442, 'Fuyo', 12),
(443, 'Gayong-Gayong Norte', 12),
(444, 'Gayong-Gayong Sur', 12),
(445, 'Guinatan', 12),
(446, 'Lullutan', 12),
(447, 'Cabeseria 10', 12),
(448, 'Malalam', 12),
(449, 'Malasin', 12),
(450, 'Manaring', 12),
(451, 'Mangcuram', 12),
(452, 'Villa Imelda', 12),
(453, 'Marana I', 12),
(454, 'Marana II', 12),
(455, 'Marana III', 12),
(456, 'Minabang', 12),
(457, 'Morado', 12),
(458, 'Naguilian Norte', 12),
(459, 'Naguilian Sur', 12),
(460, 'Namnama', 12),
(461, 'Nanaguan', 12),
(462, 'Cabeseria 7', 12),
(463, 'Osmeña', 12),
(464, 'Paliueg', 12),
(465, 'Pasa', 12),
(466, 'Pilar', 12),
(467, 'Quimalabasa', 12),
(468, 'Rang-ayan', 12),
(469, 'Rugao', 12),
(470, 'Cabeseria 22', 12),
(471, 'Salindingan', 12),
(472, 'San Andres', 12),
(473, 'Centro - San Antonio', 12),
(474, 'San Felipe', 12),
(475, 'San Ignacio', 12),
(476, 'San Isidro', 12),
(477, 'San Juan', 12),
(478, 'San Lorenzo', 12),
(479, 'San Pablo', 12),
(480, 'Cabeseria 17 and 21', 12),
(481, 'San Vicente ', 12),
(482, 'Santa Barbara ', 12),
(483, 'Santa Catalina', 12),
(484, 'Santa Isabel Norte', 12),
(485, 'Santa Isabel Sur', 12),
(486, 'Santa Victoria', 12),
(487, 'Santo Tomas', 12),
(488, 'Siffu', 12),
(489, 'Sindon Bayabo', 12),
(490, 'Sindon Maride', 12),
(491, 'Sipay', 12),
(492, 'Tangcul', 12),
(493, 'Centro Poblacion', 12),
(494, 'Bagong Silang', 12),
(495, 'Imelda Bliss Village', 12),
(496, 'San Rodrigo', 12),
(497, 'Santa Maria', 12),
(498, 'Abulan', 13),
(499, 'Addalam', 13),
(500, 'Arubub', 13),
(501, 'Bannawag', 13),
(502, 'Bantay', 13),
(503, 'Barangay I ', 13),
(504, 'Barangay II ', 13),
(505, 'Barangcuag', 13),
(506, 'Dalibubon', 13),
(507, 'Daligan', 13),
(508, 'Diarao', 13),
(509, 'Dibuluan', 13),
(510, 'Dicamay I', 13),
(511, 'Dicamay II', 13),
(512, 'Dipangit', 13),
(513, 'Disimpit', 13),
(514, 'Divinan', 13),
(515, 'Dumawing', 13),
(516, 'Fugu', 13),
(517, 'Lacab', 13),
(518, 'Linamanan', 13),
(519, 'Linomot', 13),
(520, 'Malannit', 13),
(521, 'Minuri', 13),
(522, 'Namnama', 13),
(523, 'Napaliong', 13),
(524, 'Palagao', 13),
(525, 'Papan Este', 13),
(526, 'Papan Weste', 13),
(527, 'Payac', 13),
(528, 'Pungpongan', 13),
(529, 'San Antonio', 13),
(530, 'San Isidro', 13),
(531, 'San Jose', 13),
(532, 'San Roque', 13),
(533, 'San Sebastian', 13),
(534, 'San Vicente', 13),
(535, 'Santa Isabel', 13),
(536, 'Santo Domingo', 13),
(537, 'Tupax', 13),
(538, 'Usol', 13),
(539, 'Villa Bello', 13),
(540, 'Bustamante', 14),
(541, 'Centro 1 ', 14),
(542, 'Centro 2 ', 14),
(543, 'Centro 3 ', 14),
(544, 'Concepcion', 14),
(545, 'Dadap', 14),
(546, 'Harana', 14),
(547, 'Lalog 1', 14),
(548, 'Lalog 2', 14),
(549, 'Luyao', 14),
(550, 'Macañao', 14),
(551, 'Macugay', 14),
(552, 'Mambabanga', 14),
(553, 'Pulay', 14),
(554, 'Puroc', 14),
(555, 'San Isidro', 14),
(556, 'San Miguel', 14),
(557, 'Santo Domingo', 14),
(558, 'Union Kalinga', 14),
(559, 'Diana', 15),
(560, 'Eleanor', 15),
(561, 'Fely ', 15),
(562, 'Lita ', 15),
(563, 'Reina Mercedes', 15),
(564, 'Minanga', 15),
(565, 'Malasin', 15),
(566, 'Canadam', 15),
(567, 'Aplaya', 15),
(568, 'Santa Marina', 15),
(569, 'Aga', 16),
(570, 'Andarayan', 16),
(571, 'Aneg', 16),
(572, 'Bayabo', 16),
(573, 'Calinaoan Sur', 16),
(574, 'Capitol', 16),
(575, 'Carmencita', 16),
(576, 'Concepcion', 16),
(577, 'Maui', 16),
(578, 'Quibal', 16),
(579, 'Ragan Almacen', 16),
(580, 'Ragan Norte', 16),
(581, 'Ragan Sur ', 16),
(582, 'Rizal', 16),
(583, 'San Andres', 16),
(584, 'San Antonio', 16),
(585, 'San Isidro', 16),
(586, 'San Jose', 16),
(587, 'San Juan', 16),
(588, 'San Macario', 16),
(589, 'San Nicolas', 16),
(590, 'San Patricio', 16),
(591, 'San Roque', 16),
(592, 'Sto. Rosario', 16),
(593, 'Santor', 16),
(594, 'Villa Luz', 16),
(595, 'Villa Pereda', 16),
(596, 'Visitacion', 16),
(597, 'Calaocan', 16),
(598, 'San Pedro', 17),
(599, 'Binmonton', 17),
(600, 'Casili', 17),
(601, 'Centro I ', 17),
(602, 'Holy Friday', 17),
(603, 'Maligaya', 17),
(604, 'Manano', 17),
(605, 'Olango', 17),
(606, 'Centro II ', 17),
(607, 'Rang-ayan', 17),
(608, 'San Jose Norte I', 17),
(609, 'San Jose Sur', 17),
(610, 'Siempre Viva Norte', 17),
(611, 'Trinidad', 17),
(612, 'Victoria', 17),
(613, 'San Jose Norte II', 17),
(614, 'San Ramon', 17),
(615, 'Siempre Viva Sur', 17),
(616, 'Aguinaldo', 18),
(617, 'Bagong Sikat', 18),
(618, 'Burgos', 18),
(619, 'Cabaruan', 18),
(620, 'Flores', 18),
(621, 'La Union', 18),
(622, 'Magsaysay ', 18),
(623, 'Manaring', 18),
(624, 'Mansibang', 18),
(625, 'Minallo', 18),
(626, 'Minanga', 18),
(627, 'Palattao', 18),
(628, 'Quezon ', 18),
(629, 'Quinalabasa', 18),
(630, 'Quirino ', 18),
(631, 'Rangayan', 18),
(632, 'Rizal', 18),
(633, 'Roxas ', 18),
(634, 'San Manuel', 18),
(635, 'Santo Tomas', 18),
(636, 'Sunlife', 18),
(637, 'Surcoc', 18),
(638, 'Tomines', 18),
(639, 'Villa Paz', 18),
(640, 'Santa Victoria', 18),
(641, 'Bisag', 19),
(642, 'Dialaoyao', 19),
(643, 'Dicadyuan', 19),
(644, 'Didiyan', 19),
(645, 'Dimalicu-licu', 19),
(646, 'Dimasari', 19),
(647, 'Dimatican', 19),
(648, 'Maligaya', 19),
(649, 'Marikit', 19),
(650, 'Dicabisagan East ', 19),
(651, 'Dicabisagan West ', 19),
(652, 'Santa Jacinta', 19),
(653, 'Villa Robles', 19),
(654, 'Culasi', 19),
(655, 'Alomanay', 19),
(656, 'Didaddungan', 19),
(657, 'San Isidro', 19),
(658, 'Abut', 20),
(659, 'Alunan ', 20),
(660, 'Arellano ', 20),
(661, 'Aurora', 20),
(662, 'Barucboc Norte', 20),
(663, 'Estrada', 20),
(664, 'Santos ', 20),
(665, 'Lepanto', 20),
(666, 'Mangga', 20),
(667, 'Minagbag', 20),
(668, 'Samonte ', 20),
(669, 'Turod', 20),
(670, 'Dunmon', 20),
(671, 'Calangigan', 20),
(672, 'San Juan', 20),
(673, 'Binarzang', 21),
(674, 'Cabaruan', 21),
(675, 'Camaal', 21),
(676, 'Dolores', 21),
(677, 'Luna', 21),
(678, 'Manaoag', 21),
(679, 'Rizal', 21),
(680, 'San Isidro', 21),
(681, 'San Jose', 21),
(682, 'San Juan', 21),
(683, 'San Mateo', 21),
(684, 'San Vicente', 21),
(685, 'Sta. Catalina', 21),
(686, 'Santa Lucia ', 21),
(687, 'Santiago', 21),
(688, 'Sto. Domingo', 21),
(689, 'Sinait', 21),
(690, 'Suerte', 21),
(691, 'Villa Bulusan', 21),
(692, 'Villa Miguel', 21),
(693, 'Vintar', 21),
(694, 'Ambatali', 22),
(695, 'Bantug', 22),
(696, 'Bugallon Norte', 22),
(697, 'Burgos', 22),
(698, 'Nagbacalan', 22),
(699, 'Oscariz', 22),
(700, 'Pabil', 22),
(701, 'Pagrang-ayan', 22),
(702, 'Planas', 22),
(703, 'Purok ni Bulan', 22),
(704, 'Raniag', 22),
(705, 'San Antonio', 22),
(706, 'San Miguel', 22),
(707, 'San Sebastian', 22),
(708, 'Villa Beltran', 22),
(709, 'Villa Carmen', 22),
(710, 'Villa Marcos', 22),
(711, 'General Aguinaldo', 22),
(712, 'Bugallon Proper ', 22),
(713, 'Banquero', 23),
(714, 'Binarsang', 23),
(715, 'Cutog Grande', 23),
(716, 'Cutog Pequeño', 23),
(717, 'Dangan', 23),
(718, 'District I ', 23),
(719, 'District II ', 23),
(720, 'Labinab Grande ', 23),
(721, 'Labinab Pequeño ', 23),
(722, 'Mallalatang Grande', 23),
(723, 'Mallalatang Tunggui', 23),
(724, 'Nappaccu Grande', 23),
(725, 'Nappaccu Pequeño', 23),
(726, 'Sallucong', 23),
(727, 'Santor', 23),
(728, 'Sinippil', 23),
(729, 'Tallungan ', 23),
(730, 'Turod', 23),
(731, 'Villador', 23),
(732, 'Santiago', 23),
(733, 'Anao', 24),
(734, 'Imbiao', 24),
(735, 'Lanting', 24),
(736, 'Lucban', 24),
(737, 'Marcos', 24),
(738, 'Masigun', 24),
(739, 'Rizal ', 24),
(740, 'Vira ', 24),
(741, 'Bantug ', 24),
(742, 'Luna ', 24),
(743, 'Quiling', 24),
(744, 'Rang-ayan', 24),
(745, 'San Antonio', 24),
(746, 'San Jose', 24),
(747, 'San Pedro', 24),
(748, 'San Placido', 24),
(749, 'San Rafael', 24),
(750, 'Simimbaan', 24),
(751, 'Sinamar', 24),
(752, 'Sotero Nuesa', 24),
(753, 'Villa Concepcion', 24),
(754, 'Matusalem', 24),
(755, 'Muñoz East', 24),
(756, 'Muñoz West', 24),
(757, 'Doña Concha', 24),
(758, 'San Luis', 24),
(759, 'Bautista', 25),
(760, 'Calaocan', 25),
(761, 'Dabubu Grande', 25),
(762, 'Dabubu Pequeño', 25),
(763, 'Dappig', 25),
(764, 'Laoag', 25),
(765, 'Mapalad', 25),
(766, 'Masaya Centro ', 25),
(767, 'Masaya Norte', 25),
(768, 'Masaya Sur', 25),
(769, 'Nemmatan', 25),
(770, 'Palacian', 25),
(771, 'Panang', 25),
(772, 'Quimalabasa Norte', 25),
(773, 'Quimalabasa Sur', 25),
(774, 'Rang-ay', 25),
(775, 'Salay', 25),
(776, 'San Antonio', 25),
(777, 'Santo Niño', 25),
(778, 'Santos', 25),
(779, 'Sinaoangan Norte', 25),
(780, 'Sinaoangan Sur', 25),
(781, 'Virgoneza', 25),
(782, 'Anonang', 26),
(783, 'Aringay', 26),
(784, 'Centro 1 ', 26),
(785, 'Centro 2 ', 26),
(786, 'Colorado', 26),
(787, 'Dietban', 26),
(788, 'Dingading', 26),
(789, 'Dipacamo', 26),
(790, 'Estrella', 26),
(791, 'Guam', 26),
(792, 'Nakar', 26),
(793, 'Palawan', 26),
(794, 'Progreso', 26),
(795, 'Rizal', 26),
(796, 'San Francisco Sur', 26),
(797, 'San Mariano Norte', 26),
(798, 'San Mariano Sur', 26),
(799, 'Sinalugan', 26),
(800, 'Villa Remedios', 26),
(801, 'Villa Rose', 26),
(802, 'Villa Sanchez', 26),
(803, 'Villa Teresita', 26),
(804, 'Burgos', 26),
(805, 'San Francisco Norte', 26),
(806, 'Calaoagan', 26),
(807, 'San Rafael', 26),
(808, 'Camarag', 27),
(809, 'Cebu', 27),
(810, 'Gomez', 27),
(811, 'Gud', 27),
(812, 'Nagbukel', 27),
(813, 'Patanad', 27),
(814, 'Quezon', 27),
(815, 'Ramos East', 27),
(816, 'Ramos West', 27),
(817, 'Rizal East ', 27),
(818, 'Rizal West ', 27),
(819, 'Victoria', 27),
(820, 'Villaflor', 27),
(821, 'Agliam', 28),
(822, 'Babanuang', 28),
(823, 'Cabaritan', 28),
(824, 'Caraniogan', 28),
(825, 'Eden', 28),
(826, 'Malalinta', 28),
(827, 'Mararigue', 28),
(828, 'Nueva Era', 28),
(829, 'Pisang', 28),
(830, 'District 1 ', 28),
(831, 'District 2 ', 28),
(832, 'District 3 ', 28),
(833, 'District 4 ', 28),
(834, 'San Francisco', 28),
(835, 'Sandiat Centro', 28),
(836, 'Sandiat East', 28),
(837, 'Sandiat West', 28),
(838, 'Sta. Cruz', 28),
(839, 'Villanueva', 28),
(840, 'Alibadabad', 29),
(841, 'Binatug', 29),
(842, 'Bitabian', 29),
(843, 'Buyasan', 29),
(844, 'Cadsalan', 29),
(845, 'Casala', 29),
(846, 'Cataguing', 29),
(847, 'Daragutan East', 29),
(848, 'Daragutan West', 29),
(849, 'Del Pilar', 29),
(850, 'Dibuluan', 29),
(851, 'Dicamay', 29),
(852, 'Dipusu', 29),
(853, 'Disulap', 29),
(854, 'Disusuan', 29),
(855, 'Gangalan', 29),
(856, 'Ibujan', 29),
(857, 'Libertad', 29),
(858, 'Macayucayu', 29),
(859, 'Mallabo', 29),
(860, 'Marannao', 29),
(861, 'Minanga', 29),
(862, 'Old San Mariano', 29),
(863, 'Palutan', 29),
(864, 'Panninan', 29),
(865, 'Zone I ', 29),
(866, 'Zone II ', 29),
(867, 'Zone III ', 29),
(868, 'San Jose', 29),
(869, 'San Pablo', 29),
(870, 'San Pedro', 29),
(871, 'Sta. Filomena', 29),
(872, 'Tappa', 29),
(873, 'Ueg', 29),
(874, 'Zamora', 29),
(875, 'Balagan', 29),
(876, 'Bacarreña', 30),
(877, 'Bagong Sikat', 30),
(878, 'Bella Luz', 30),
(879, 'Daramuangan Sur', 30),
(880, 'Estrella', 30),
(881, 'Gaddanan', 30),
(882, 'Malasin', 30),
(883, 'Mapuroc', 30),
(884, 'Marasat Grande', 30),
(885, 'Marasat Pequeño', 30),
(886, 'Old Centro I', 30),
(887, 'Old Centro II', 30),
(888, 'Barangay I ', 30),
(889, 'Barangay II ', 30),
(890, 'Barangay III ', 30),
(891, 'Barangay IV ', 30),
(892, 'Salinungan East', 30),
(893, 'Salinungan West', 30),
(894, 'San Andres', 30),
(895, 'San Antonio', 30),
(896, 'San Ignacio', 30),
(897, 'San Manuel', 30),
(898, 'San Marcos', 30),
(899, 'San Roque', 30),
(900, 'Sinamar Norte', 30),
(901, 'Sinamar Sur', 30),
(902, 'Victoria', 30),
(903, 'Villafuerte', 30),
(904, 'Villa Cruz', 30),
(905, 'Villa Magat', 30),
(906, 'Villa Gamiao', 30),
(907, 'Dagupan', 30),
(908, 'Daramuangan Norte', 30),
(909, 'Annanuman', 31),
(910, 'Auitan', 31),
(911, 'Ballacayu', 31),
(912, 'Binguang', 31),
(913, 'Bungad', 31),
(914, 'Dalena', 31),
(915, 'Caddangan/Limbauan', 31),
(916, 'Calamagui', 31),
(917, 'Caralucud', 31),
(918, 'Guminga', 31),
(919, 'Minanga Norte', 31),
(920, 'Minanga Sur', 31),
(921, 'San Jose', 31),
(922, 'Poblacion', 31),
(923, 'Simanu Norte', 31),
(924, 'Simanu Sur', 31),
(925, 'Tupa', 31),
(926, 'Bangad', 32),
(927, 'Buenavista', 32),
(928, 'Calamagui North', 32),
(929, 'Calamagui East', 32),
(930, 'Calamagui West', 32),
(931, 'Divisoria', 32),
(932, 'Lingaling', 32),
(933, 'Mozzozzin Sur', 32),
(934, 'Mozzozzin North', 32),
(935, 'Naganacan', 32),
(936, 'Poblacion 1', 32),
(937, 'Poblacion 2', 32),
(938, 'Poblacion 3', 32),
(939, 'Quinagabian', 32),
(940, 'San Antonio', 32),
(941, 'San Isidro East', 32),
(942, 'San Isidro West', 32),
(943, 'San Rafael West', 32),
(944, 'San Rafael East', 32),
(945, 'Villabuena', 32),
(946, 'Abra', 32),
(947, 'Ambalatungan', 32),
(948, 'Balintocatoc', 32),
(949, 'Baluarte', 32),
(950, 'Bannawag Norte', 32),
(951, 'Batal', 32),
(952, 'Buenavista', 32),
(953, 'Cabulay', 32),
(954, 'Calao East ', 32),
(955, 'Calao West ', 32),
(956, 'Calaocan', 32),
(957, 'Villa Gonzaga', 32),
(958, 'Centro East ', 32),
(959, 'Centro West ', 32),
(960, 'Divisoria', 32),
(961, 'Dubinan East', 32),
(962, 'Dubinan West', 32),
(963, 'Luna', 32),
(964, 'Mabini', 32),
(965, 'Malvar', 32),
(966, 'Nabbuan', 32),
(967, 'Naggasican', 32),
(968, 'Patul', 32),
(969, 'Plaridel', 32),
(970, 'Rizal', 32),
(971, 'Rosario', 32),
(972, 'Sagana', 32),
(973, 'Salvador', 32),
(974, 'San Andres', 32),
(975, 'San Isidro', 32),
(976, 'San Jose', 32),
(977, 'Sinili', 32),
(978, 'Sinsayon', 32),
(979, 'Santa Rosa', 32),
(980, 'Victory Norte', 32),
(981, 'Victory Sur', 32),
(982, 'Villasis', 32),
(983, 'Ammugauan', 33),
(984, 'Antagan', 33),
(985, 'Bagabag', 33),
(986, 'Bagutari', 33),
(987, 'Balelleng', 33),
(988, 'Barumbong', 33),
(989, 'Bubug', 33),
(990, 'Bolinao-Culalabo', 33),
(991, 'Cañogan Abajo Norte', 33),
(992, 'Calinaoan Centro', 33),
(993, 'Calinaoan Malasin', 33),
(994, 'Calinaoan Norte', 33),
(995, 'Cañogan Abajo Sur', 33),
(996, 'Cañogan Alto', 33),
(997, 'Centro', 33),
(998, 'Colunguan', 33),
(999, 'Malapagay', 33),
(1000, 'San Rafael Abajo', 33),
(1001, 'San Rafael Alto', 33),
(1002, 'San Roque', 33),
(1003, 'San Vicente', 33),
(1004, 'Uauang Tuliao', 33),
(1005, 'Uauang Galicia', 33),
(1006, 'Biga Occidental', 33),
(1007, 'Biga Oriental', 33),
(1008, 'Calanigan Norte', 33),
(1009, 'Calanigan Sur', 33),
(1010, 'Annafunan', 34),
(1011, 'Antagan I', 34),
(1012, 'Antagan II', 34),
(1013, 'Arcon', 34),
(1014, 'Balug', 34),
(1015, 'Banig', 34),
(1016, 'Bantug', 34),
(1017, 'Bayabo East', 34),
(1018, 'Caligayan', 34),
(1019, 'Camasi', 34),
(1020, 'Carpentero', 34),
(1021, 'Compania', 34),
(1022, 'Cumabao', 34),
(1023, 'Fugu Abajo', 34),
(1024, 'Fugu Norte', 34),
(1025, 'Fugu Sur', 34),
(1026, 'Fermeldy', 34),
(1027, 'Lalauanan', 34),
(1028, 'Lanna', 34),
(1029, 'Lapogan', 34),
(1030, 'Lingaling', 34),
(1031, 'Liwanag', 34),
(1032, 'Sta. Visitacion', 34),
(1033, 'Malamag East', 34),
(1034, 'Malamag West', 34),
(1035, 'Maligaya', 34),
(1036, 'Minanga', 34),
(1037, 'Namnama', 34),
(1038, 'Paragu', 34),
(1039, 'Pilitan', 34),
(1040, 'Barangay District 1 ', 34),
(1041, 'Barangay District 2 ', 34),
(1042, 'Barangay District 3 ', 34),
(1043, 'Barangay District 4 ', 34),
(1044, 'San Mateo', 34),
(1045, 'San Pedro', 34),
(1046, 'San Vicente', 34),
(1047, 'Santa', 34),
(1048, 'Sta. Catalina', 34),
(1049, 'Sto. Niño', 34),
(1050, 'Sinippil', 34),
(1051, 'Sisim Abajo', 34),
(1052, 'Sisim Alto', 34),
(1053, 'Tunggui', 34),
(1054, 'Ugad', 34),
(1055, 'Moldero', 34);

-- --------------------------------------------------------

--
-- Table structure for table `campuses`
--

CREATE TABLE `campuses` (
  `campus_id` int(11) NOT NULL,
  `campus_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `campuses`
--

INSERT INTO `campuses` (`campus_id`, `campus_name`) VALUES
(1, 'Echague Campus'),
(2, 'Cabagan Campus'),
(3, 'Cauayan Campus'),
(4, 'Ilagan Campus'),
(5, 'Roxas Campus'),
(6, 'Angadanan Campus'),
(7, 'Jones Campus'),
(8, 'San Mateo Campus'),
(9, 'San Mariano Campus'),
(10, 'Santiago Extension'),
(11, 'Palanan Extension');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `campus_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `course_name`, `campus_id`) VALUES
(1, 'Doctor of Veterinary Medicine', 1),
(2, 'Bachelor of Science in Data Science and Analytics', 1),
(3, 'Bachelor of Science in Animal Husbandry', 1),
(4, 'Bachelor of Science in Agriculture', 1),
(5, 'Bachelor of Science in Agribusiness', 1),
(6, 'Bachelor of Science in Forestry', 1),
(7, 'Bachelor of Science in Environmental Science', 1),
(8, 'Bachelor of Science in Biology', 1),
(9, 'Bachelor of Science in Mathematics', 1),
(10, 'Bachelor of Science in Chemistry', 1),
(11, 'Bachelor of Science in Psychology', 1),
(12, 'Bachelor of Arts in Communication', 1),
(13, 'Bachelor of Arts in English Language Studies', 1),
(14, 'Bachelor of Science in Business Administration', 1),
(15, 'Bachelor in Public Administration', 1),
(16, 'Bachelor of Science in Management Accounting', 1),
(17, 'Bachelor of Science in Entrepreneurship', 1),
(18, 'Bachelor of Science in Accountancy', 1),
(19, 'Bachelor of Science in Hospitality Management', 1),
(20, 'Bachelor of Science in Tourism Management', 1),
(21, 'Bachelor of Science in Agricultural and Biosystems Engineering', 1),
(22, 'Bachelor of Science in Civil Engineering', 1),
(23, 'Bachelor of Elementary Education', 1),
(24, 'Bachelor of Secondary Education', 1),
(25, 'Bachelor of Physical Education', 1),
(26, 'Bachelor of Technology and Livelihood Education', 1),
(27, 'Bachelor of Science in Information Technology', 1),
(28, 'Bachelor of Science in Information Systems', 1),
(29, 'Bachelor of Science in Computer Science', 1),
(30, 'Bachelor of Library and Information Science', 1),
(31, 'Bachelor of Science in Fisheries and Aquatic Sciences', 1),
(32, 'Bachelor of Science in Criminology', 1),
(33, 'Bachelor of Science in Law Enforcement Administration', 1),
(34, 'Bachelor of Science in Nursing', 1),
(35, 'Diploma in Agricultural Technology', 1),
(36, 'Bachelor of Elementary Education', 2),
(37, 'Bachelor of Secondary Education', 2),
(38, 'Bachelor of Physical Education', 2),
(39, 'Bachelor of Technology and Livelihood Education', 2),
(40, 'Bachelor of Early Childhood Education', 2),
(41, 'Bachelor of Science in Forestry', 2),
(42, 'Bachelor of Science in Environmental Science', 2),
(43, 'Bachelor of Agricultural Technology', 2),
(44, 'Bachelor of Science in Agriculture', 2),
(45, 'Bachelor of Science in Agri-Business', 2),
(46, 'Bachelor of Science in Hospitality Management', 2),
(47, 'Bachelor of Arts in Sociology', 2),
(48, 'Bachelor of Arts in Communication', 2),
(49, 'Bachelor of Science in Chemistry', 2),
(50, 'Bachelor of Science in Entrepreneurship', 2),
(51, 'Bachelor of Science in Psychology', 2),
(52, 'Bachelor of Science in Development Communication', 2),
(53, 'Bachelor of Science in Biology', 2),
(54, 'Bachelor of Science in Mathematics', 2),
(55, 'Bachelor of Science in Physics', 2),
(56, 'Bachelor of Science in Tourism Management', 2),
(57, 'Bachelor of Science in Criminology', 2),
(58, 'Bachelor of Science in Law Enforcement Administration', 2),
(59, 'Bachelor of Science in Industrial Security Management', 2),
(60, 'Bachelor of Science in Information Technology', 2),
(61, 'Bachelor of Science in Computer Science', 2),
(62, 'Bachelor of Science in Computer Engineering', 2),
(63, 'Associate in Hotel & Restaurant Management', 2),
(64, 'Diploma in Agricultural Technology', 2),
(65, 'Doctor of Information Technology', 3),
(66, 'Master in Information Technology', 3),
(67, 'Master in Business Administration', 3),
(68, 'Master in Public Administration', 3),
(69, 'Bachelor of Science in Entertainment and Multimedia Computing', 3),
(70, 'Bachelor of Science in Accounting Information System', 3),
(71, 'Bachelor of Science in Industrial Technology', 3),
(72, 'Associate in Aircraft Maintenance Technology', 3),
(73, 'Diploma in Agricultural Technology', 3),
(74, 'Associate in Hotel, Restaurant & Management', 3),
(75, 'Electrical Technology (Two Years)', 3),
(76, 'Electronics Technology (Two Years)', 3),
(77, 'Automotive Technology (Two Years)', 3),
(78, 'Mechanical Technology (Three Years)', 3),
(79, 'Refrigeration & Airconditioning Technology', 3),
(80, 'Bachelor of Science in Architecture', 4),
(81, 'Bachelor of Science in Industrial Technology', 4),
(82, 'Bachelor of Science in Information Technology', 4),
(83, 'Bachelor of Science in Electrical Engineering', 4),
(84, 'Bachelor of Science in Civil Engineering', 4),
(85, 'Bachelor of Science in Nursing', 4),
(86, 'Bachelor of Science in Midwifery', 4),
(87, 'Bachelor of Secondary Education', 4),
(88, 'Bachelor of Technology and Livelihood Education', 4),
(89, 'Bachelor of Technical Vocational Teacher Education', 4),
(90, 'Bachelor of Science in Psychology', 4),
(91, 'Bachelor of Physical Education', 4),
(92, 'Master of Arts in Industrial Education', 4),
(93, 'Master of Arts in Education, Major in Educational Management', 4),
(94, 'Bachelor of Science in Agriculture', 5),
(95, 'Bachelor of Science in Information Technology', 5),
(96, 'Bachelor of Secondary Education', 5),
(97, 'Bachelor of Science in Fisheries', 5),
(98, 'Bachelor of Science in Agri-Business', 5),
(99, 'Bachelor of Science in Criminology', 5),
(100, 'Master of Science in Fisheries', 5),
(101, 'Bachelor of Secondary Education', 6),
(102, 'Bachelor of Agricultural Technology', 6),
(103, 'Bachelor of Science in Industrial Technology', 6),
(104, 'Bachelor of Science in Criminology', 6),
(105, 'Bachelor of Technical-Vocational Teacher Education', 6),
(106, 'Bachelor of Science in Information Technology', 6),
(107, 'Bachelor of Science in Hospitality Management', 6),
(108, 'Associate in Hotel and Restaurant Management', 6),
(109, 'Automotive Technology (Two Years)', 6),
(110, 'Electronics Technology (Two Years)', 6),
(111, 'Electrical Technology (Two Years)', 6),
(112, 'Bachelor of Elementary Education', 7),
(113, 'Bachelor of Secondary Education', 7),
(114, 'Bachelor of Science in Information Technology', 7),
(115, 'Bachelor of Science in Criminology', 7),
(116, 'Bachelor of Science in Agriculture', 7),
(117, 'Bachelor of Agricultural Technology', 8),
(118, 'Bachelor of Science in Information Technology', 8),
(119, 'Bachelor of Secondary Education', 8),
(120, 'Bachelor of Technical-Vocational Teacher Education', 8),
(121, 'Diploma in Agricultural Technology', 8),
(122, 'Bachelor of Agricultural Technology', 9),
(123, 'Bachelor of Secondary Education', 9),
(124, 'Bachelor of Science in Hospitality Management', 9),
(125, 'Bachelor of Science in Information Technology', 9),
(126, 'Bachelor of Science in Forestry (First 2 Years)', 9),
(127, 'Diploma in Agricultural Technology', 9),
(128, 'Bachelor of Science in Information Technology', 10),
(129, 'Bachelor of Science in Agriculture', 10),
(130, 'Bachelor of Elementary Education', 11),
(131, 'Bachelor of Science in Information Technology', 11),
(132, 'Bachelor of Science in Agriculture', 11);

-- --------------------------------------------------------

--
-- Table structure for table `municipalities`
--

CREATE TABLE `municipalities` (
  `municipality_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `province_id` int(11) DEFAULT NULL,
  `zip_code` varchar(10) DEFAULT NULL,
  `area_code` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `municipalities`
--

INSERT INTO `municipalities` (`municipality_id`, `name`, `province_id`, `zip_code`, `area_code`) VALUES
(1, 'Alicia', 1, '3306.0', '78.0'),
(2, 'Angadanan', 1, '3405.0', '78.0'),
(3, 'Aurora', 1, '3316.0', '78.0'),
(4, 'Benito Soliven', 1, '3331.0', '78.0'),
(5, 'Burgos', 1, '3322.0', '78.0'),
(6, 'Cabagan', 1, '3328.0', '78.0'),
(7, 'Cabatuan', 1, '3315.0', '78.0'),
(8, 'Cordon', 1, '3312.0', '78.0'),
(9, 'Dinapigue', 1, '3336.0', '78.0'),
(10, 'Divilacan', 1, '3335.0', '78.0'),
(11, 'Echague', 1, '3309.0', '78.0'),
(12, 'Gamu', 1, '3301.0', '78.0'),
(13, 'Jones', 1, '3313.0', '78.0'),
(14, 'Luna', 1, '3304.0', '78.0'),
(15, 'Maconacon', 1, '3333.0', '78.0'),
(16, 'Delfin Albano', 1, '3326.0', '78.0'),
(17, 'Mallig', 1, '3323.0', '78.0'),
(18, 'Naguilian', 1, '3302.0', '78.0'),
(19, 'Palanan', 1, '3334.0', '78.0'),
(20, 'Quezon', 1, '3324.0', '78.0'),
(21, 'Quirino', 1, '3321.0', '78.0'),
(22, 'Ramon', 1, '3319.0', '78.0'),
(23, 'Reina Mercedes', 1, '3303.0', '78.0'),
(24, 'Roxas', 1, '3320.0', '78.0'),
(25, 'San Agustin', 1, '3314.0', '78.0'),
(26, 'San Guillermo', 1, '3308.0', '78.0'),
(27, 'San Isidro', 1, '3310.0', '78.0'),
(28, 'San Manuel', 1, '3317.0', '78.0'),
(29, 'San Mariano', 1, '3332.0', '78.0'),
(30, 'San Mateo', 1, '3318.0', '78.0'),
(31, 'San Pablo', 1, '3329.0', '78.0'),
(32, 'Santa Maria', 1, '3330.0', '78.0'),
(33, 'Santo Tomas', 1, '3327.0', '78.0'),
(34, 'Tumauini', 1, '3325.0', '78.0');

-- --------------------------------------------------------

--
-- Table structure for table `provinces`
--

CREATE TABLE `provinces` (
  `province_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `provinces`
--

INSERT INTO `provinces` (`province_id`, `name`) VALUES
(1, 'Isabela');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `choice1` varchar(255) NOT NULL,
  `choice2` varchar(255) NOT NULL,
  `choice3` varchar(255) NOT NULL,
  `choice4` varchar(255) NOT NULL,
  `correct_answer` varchar(255) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `subject_id`, `question_text`, `choice1`, `choice2`, `choice3`, `choice4`, `correct_answer`, `image_path`, `created_at`, `updated_at`) VALUES
(1, 1, 'The saccharine lady attracts many tourists in their hometown.', 'leave', 'sweet', 'arid', 'quit', 'sweet', 'assets/img/660efe0b0d8e6.png', '2024-03-21 19:46:40', '2024-04-04 19:22:51'),
(2, 1, 'Gray’s children buy many gifts for present. They are pensive to their teacher and classmates.', 'oppressed', 'caged', 'thoughtful', 'happy', 'thoughtful', 'assets/img/660efe26ad438.png', '2024-03-21 19:46:40', '2024-04-04 19:23:18'),
(3, 1, 'Our dogs went hiding because of stentorian fireworks on New Year’s Eve.', 'violent', 'misbegotten', 'loud', 'stealthy', 'loud', 'assets/img/660efe442eff4.png', '2024-03-21 19:46:40', '2024-04-04 19:23:48'),
(4, 1, 'We are hoping that these herbal medicines will abate her excruciating body pain.', 'free', 'augment', 'provoke', 'wane', 'augment', 'assets/img/660efec3b78b4.png', '2024-03-21 19:46:40', '2024-04-04 22:38:47'),
(5, 1, 'The case was dismissed because of dearth evidence presented to the court.', 'lack', 'poverty', 'abundance', 'foreign', 'lack', 'assets/img/660eff006fefb.png', '2024-03-21 19:46:40', '2024-04-04 19:26:56'),
(6, 1, 'The teacher has introduced a motivational story which is germane to the topic.', 'irrelevant', 'indifferent', 'impartial', 'improvident', 'improvident', 'assets/img/660eff1ebf848.png', '2024-03-21 19:46:40', '2024-04-04 19:27:26'),
(7, 1, 'I love to buy and read abridge dictionary because it has its complete details.', 'shorten', 'extend', 'stress', 'easy', 'extend', 'assets/img/660eff6d7cdf6.png', '2024-03-21 19:46:40', '2024-04-04 22:41:48'),
(8, 1, 'Although his manner was perfectly amicable, I am still uncomfortable. The opposite meaning of the word \"amicable\" is ___________.', 'Pleasant', 'Friendly', 'Affable', 'Grumpy', 'Grumpy', NULL, '2024-03-21 19:46:40', '2024-03-21 19:46:40'),
(9, 1, 'The new leader is a brash young man. Which word describes best the opposite meaning of the word \"brash\"?', 'Polite', 'Handsome', 'Arrogant', 'Cool', 'Polite', NULL, '2024-03-21 19:46:40', '2024-03-21 19:46:40'),
(10, 1, 'Embrace your challenges, learn from them, and then take actions to improve your circumstances. Choose the word that is most opposite in meaning to the word “embrace”', 'Contradict', 'Face', 'Obscure', 'Reject', 'Reject', NULL, '2024-03-21 19:46:40', '2024-03-21 19:46:40'),
(11, 1, 'Which of the following demonstrates the proper use of quotation marks?', 'The professor asked, “Did anyone take notes from the last lecture?”', '“I need to buy a new umbrella,” she said.', 'My brother says, “Hello.”', '“Stop!” shouted the security guard.', 'The professor asked, “Did anyone take notes from the last lecture?”', NULL, '2024-03-21 19:46:40', '2024-03-21 19:46:40'),
(12, 1, 'Which of the following shows the incorrect use of \"who’s\"?', 'Who’s sandwich is this?', 'James composed a list of who’s attending the art class at the Activity Center.', 'Kyle, who’s going to Thailand in April, loves architecture.', 'Do you know who’s going to the concert tonight?', 'Who’s sandwich is this?', NULL, '2024-03-21 19:46:40', '2024-03-21 19:46:40'),
(13, 1, 'According to the text, academic dishonesty means ________________.', 'Cheating', 'Lying', 'Deceiving', 'misconduct', 'Cheating', NULL, '2024-03-21 19:46:40', '2024-04-04 22:45:00'),
(14, 1, 'Why do students commit academic dishonesty?', 'They want to get ahead of others', 'They feel they have the right to cheat', 'They like to receive high honors', 'They want to achieve a good record', 'They feel they have the right to cheat', NULL, '2024-03-21 19:46:40', '2024-03-21 19:46:40'),
(15, 1, 'Which of the following statements can help students avoid academic dishonesty?', 'Be honest at all times', 'Paraphrase someone else’s idea', 'Tweak ideas to make it seem original', 'Recognize the idea of others', 'Be honest at all times', NULL, '2024-03-21 19:46:40', '2024-03-21 19:46:40'),
(16, 1, 'As I think that I was never to get well, my illness began to recede. The word \"recede\" means ________.', 'Retreat', 'Forward', 'Move', 'Come', 'Retreat', NULL, '2024-03-21 19:46:40', '2024-03-21 19:46:40'),
(17, 1, 'When Greg lost his job and became impoverished, the bank foreclosed on his home. Which word has the same meaning as impoverished?', 'Affluent', 'Indolent', 'Destitute', 'Inane', 'Destitute', NULL, '2024-03-21 19:46:40', '2024-03-21 19:46:40'),
(18, 1, 'The cover-up lasts to this day, but its genesis is now coming to light. The correct synonym of the word \"genesis\" is _________.', 'Movement', 'Relevant', 'Style', 'Beginning', 'Beginning', NULL, '2024-03-21 19:46:40', '2024-03-21 19:46:40'),
(19, 1, 'High school students are more loquacious than the college students in round-table discussions during the literary contest.', 'talkative', 'thirsty', 'beautiful', 'complicated', 'talkative', 'assets/img/660efd8267d2d.png', '2024-03-21 19:46:40', '2024-04-04 19:20:34'),
(20, 1, 'Can you believe the student had the temerity to ask many questions from the terror teacher?', 'audacity', 'fearfulness', 'shyness', 'stupidity', 'audacity', 'assets/img/660efde1f3a97.png', '2024-03-21 19:46:40', '2024-04-04 19:22:10'),
(21, 2, 'A student conducted an experiment to see the effect of light intensity on the growth of a certain plant. Which could be the independent, dependent, and control variables of his experiment?', 'height of the plant, light intensity, weight of the plant', 'height of the plant, light intensity, color of light used', 'light intensity, height of the plant, the amount of nutrients the plant receives', 'light intensity, size of leaves, the size of flower pots used', 'light intensity, height of the plant, the amount of nutrients the plant receives', NULL, '2024-03-21 19:54:47', '2024-03-21 19:54:47'),
(22, 2, 'Which of the following levels of organization is arranged in the correct sequence from most to least inclusive?', 'ecosystem, community, population, individual', 'community, ecosystem, individual, population', 'individual, population, community, ecosystem', 'population, ecosystem, individual, community', 'ecosystem, community, population, individual', NULL, '2024-03-21 19:54:47', '2024-03-21 19:54:47'),
(23, 2, 'Which kind of relationship is exhibited by a lice and human?', 'Commensalism', 'Parasitism', 'Symbiosis', 'Competition', 'Parasitism', NULL, '2024-03-21 19:54:47', '2024-03-21 19:54:47'),
(24, 2, 'Important abiotic factors in ecosystems include which of the following?', 'I only', 'II only', 'I and II only', 'I, II, and III', 'I, II, and III', 'assets/img/660efb12d3171.png', '2024-03-21 19:54:47', '2024-04-04 19:17:41'),
(25, 2, 'What is a cell?', 'smallest and advanced unit of life', 'smallest and basic unit of life', 'largest and basic unit of life', 'largest and advanced unit of life', 'smallest and basic unit of life', NULL, '2024-03-21 19:54:47', '2024-03-21 19:54:47'),
(26, 2, 'Which of the following statements best explains why it is warmer at the equator than at the North Pole?', 'The equator has a larger area than the North Pole', 'The equator is closer to the Sun than the North Pole.', 'The equator receives more direct sunlight than the North Pole.', 'The equator has more hours of daylight per year than the North Pole.', 'The equator receives more direct sunlight than the North Pole.', NULL, '2024-03-21 19:54:47', '2024-03-21 19:54:47'),
(27, 2, 'During a “leap year”, we add an extra day to our calendar because:', 'Earth takes 24 hours to rotate', 'The moon takes 27.3 days to orbit', 'Earth takes 365 ¼ days to revolve', 'The moon takes 29.5 days to orbit.', 'Earth takes 365 ¼ days to revolve', NULL, '2024-03-21 19:54:47', '2024-03-21 19:54:47'),
(28, 2, 'Which of the following would be the effect of a rise on carbon dioxide in the atmosphere?', 'Cooling of the atmosphere', 'Increases in the solar radiation', 'Darkening of the air', 'Warming of the atmosphere', 'Warming of the atmosphere', NULL, '2024-03-21 19:54:47', '2024-03-21 19:54:47'),
(29, 2, 'A government existing law also known as Ecological Solid Waste Management of Act of 2000', 'RA 9003', 'RA 2000', 'RA 9007', 'RA 2001', 'RA 9003', NULL, '2024-03-21 19:54:47', '2024-03-21 19:54:47'),
(30, 2, 'The most visually striking evidence of global warming is ____________?', 'The increased precipitation in the gulf coast of countries', 'The increase in drought in agricultural areas', 'Rapid melting of glacial ice on nearly every continent', 'temperature fluctuations during winter months', 'Rapid melting of glacial ice on nearly every continent', NULL, '2024-03-21 19:54:47', '2024-03-21 19:54:47'),
(31, 2, 'What do you call a closed continuous path through which electrons can flow?', 'Circuit', 'Charge', 'Voltage', 'Resistor', 'Circuit', NULL, '2024-03-21 19:56:41', '2024-03-21 19:56:41'),
(32, 2, 'What wave property can best explain the apparent bending of a pencil when it is dipped into a glass of water?', 'Reflection', 'Refraction', 'Diffraction', 'Interference', 'Refraction', NULL, '2024-03-21 19:56:41', '2024-03-21 19:56:41'),
(33, 2, 'What is the total mechanical energy of the system?', '34J', '66J', '100J', '0J', '100J', NULL, '2024-03-21 19:56:41', '2024-03-21 19:56:41'),
(34, 2, 'Matter changes in composition and/or state depending on conditions. Which of the following describes matter undergoing a chemical change?', 'Sugar mixes with sodium chloride', 'Stained tiles soaked in hydrochloric acid', 'Solid ice melting into water', 'Sheets of paper were shredded to pieces', 'Stained tiles soaked in hydrochloric acid', NULL, '2024-03-21 19:56:41', '2024-03-21 19:56:41'),
(35, 2, 'In which list are celestial features correctly shown in order of increasing size?', 'galaxy → solar system → universe → planet', 'solar system → galaxy → planet → universe', 'planet → solar system → galaxy → universe', 'universe → galaxy → solar system → planet', 'planet → solar system → galaxy → universe', NULL, '2024-03-21 19:56:41', '2024-03-21 19:56:41'),
(36, 2, 'Which of the following statements about vectors and scalars are TRUE?', 'I, III and IV', 'III and V', 'I, II, V', 'I, III and V', 'I, III and IV', 'assets/img/660effe9dea01.png', '2024-03-21 19:56:41', '2024-04-04 19:33:26'),
(37, 2, 'If a car has an acceleration of 0 m/s2 then one can be sure that the car is not ________.', 'changing its position', 'changing its velocity', 'moving', 'changing its parts.', 'changing its velocity', NULL, '2024-03-21 19:56:41', '2024-03-21 19:56:41'),
(38, 2, 'Which of the following statements is/are not TRUE about a free-falling object?', 'I and IV', 'III and IV', 'I, III and IV', 'II and III', 'III and IV', 'assets/img/660f003965457.png', '2024-03-21 19:56:41', '2024-04-04 19:34:38'),
(39, 2, 'An object weighs 600N on Earth. A second object weighs 100N on the moon. Which has a bigger mass? Note: The moon’s gravity is 1/6th of the Earth’s.', 'The object on the earth', 'The object on the moon', 'Both objects have the same mass', 'Cannot be determined', 'Both objects have the same mass', NULL, '2024-03-21 19:56:41', '2024-04-04 23:18:38'),
(40, 2, 'The ideal gas law summarizes the simple gas laws. Which of the following correctly states the relationship between the four conditions?', 'V=nRTP', 'T=nR/PV', 'P=nRT/V', 'T=R/PV', 'P=nRT/V', NULL, '2024-03-21 19:56:41', '2024-03-21 19:56:41'),
(51, 3, 'If 3/8 of the class are absent and there are only 20 who are present how many students are absent?', '9', '10', '12', '15', '12', NULL, '2024-03-21 20:06:17', '2024-03-21 20:06:17'),
(52, 3, 'A man has a certain amount of money. He used ¼ of his money to buy a book and used 80% of the remainder to pay his tuition fee. If the man has still Php 600.00 left, how much is the cost of the book?', 'Php 2500.00', 'Php 1500.00', 'Php 1000.00', 'Php 500.00', 'Php 1000.00', NULL, '2024-03-21 20:06:17', '2024-03-21 20:06:17'),
(53, 3, 'A box contains four white balls and five red balls. If three balls are drawn from the box, what is the probability that one ball is white?', '10/42', '10/21', '5/21', '25/42', '10/21', NULL, '2024-03-21 20:06:17', '2024-04-04 23:19:43'),
(54, 3, 'Sample question text?', 'A', 'B', 'C', 'D', 'D', NULL, '2025-04-03 16:12:43', '2025-04-03 16:12:43'),
(55, 3, 'Which of the following mathematical statements is/are TRUE?', 'I only', 'II only', 'I and III', 'I and II', 'II only', 'assets/img/660f03598951f.png', '2024-03-21 20:06:17', '2024-04-04 19:45:29'),
(56, 3, 'Out of fifty students, 34 are enrolled in College Algebra and 25 are enrolled in Statistics and Probability class. If 16 are both enrolled in the two subjects, how many students are not enrolled in neither subject?', '7', '9', '16', '18', '7', NULL, '2024-03-21 20:06:17', '2024-04-04 23:20:44'),
(57, 3, 'A 24-meter piece of wire is cut into two parts with unequal lengths. If the ratio of the shorter piece to the longer piece is 3:5, how long is the shorter piece?', '8 m', '9 m', '15 m', '16 m', '9 m', NULL, '2024-03-21 20:06:17', '2024-03-21 20:06:17'),
(58, 3, 'How many 60 cm square tiles will cover the floor of a living room with dimensions 4.8 meters and 5.4 meters?', '72', '82', '92', '102', '72', NULL, '2024-03-21 20:07:13', '2024-04-04 23:21:00'),
(59, 3, 'A flagpole 20 ft high cast a shadow of 12 ft at the same time a building cast a shadow of 42 ft. How high is the building?', '66 ft', '67 ft', '70 ft', '74 ft', '70 ft', NULL, '2024-03-21 20:07:13', '2024-03-21 20:07:13'),
(60, 3, 'Given the set of data 8, 10, 5, 9, 7, 8, 6, 8, 9, 5, which of the following is true?', 'mean ≤ median ≤ mode', 'median ≤ mean ≤ mode', 'median ≤ mode ≤ mean', 'mode ≤ mean ≤ median', 'mean ≤ median ≤ mode', NULL, '2024-03-21 20:07:13', '2024-04-04 23:21:29'),
(61, 3, 'Van fares are computed as follows: Php 30.00 for the first four kilometers and Php 2.50 for every additional kilometer. How much should you pay (in pesos) for a ride that covers 24 kilometers?', '50', '60', '70', '80', '80', NULL, '2024-03-21 20:07:13', '2024-03-21 20:07:13'),
(62, 3, 'Five less than twice a number is seven. What is the number?', '5', '6', '7', '8', '6', NULL, '2024-03-21 20:07:13', '2024-03-21 20:07:13'),
(63, 3, 'Bong is 3 kg heavier than his younger sister Bing, and he is 2 kg lighter than his older sister Beng? If the three siblings have a total weight of 155 kg, how heavy is Bing in kilograms?', '47', '49', '52', '48', '49', NULL, '2024-03-21 20:07:13', '2024-03-21 20:07:13'),
(64, 3, 'How many line segments can you form from 8 collinear points?', '7', '14', '21', '28', '28', NULL, '2024-03-21 20:07:13', '2024-03-21 20:07:13'),
(65, 3, 'What will be the value of the expression 3x^2 – 5y + 2z^3 when x = 3, y = -2, and z = -1?', '15', '19', '35', '19', '35', NULL, '2024-03-21 20:07:13', '2024-04-04 17:44:40'),
(66, 3, 'There are 1800 college freshmen at a certain university. If the ratio of the male student to the female student is 5:7, how many male students are there?', '750', '1050', '1200', '1250', '750', NULL, '2024-03-21 20:07:13', '2024-03-21 20:07:13'),
(67, 3, 'The total number of ducks and pigs in a farm is 18. The total number of legs among them is 56. Assuming each duck has exactly two legs and each pig has exactly four legs, determine how many pigs are in the field?', '6', '8', '9', '10', '10', NULL, '2024-03-21 20:07:13', '2024-03-21 20:07:13'),
(68, 3, 'Patrick is 6 years older than Mark, and Mark is 5 years older than Michael. If the total of their ages is 61, then how old is Michael?', '10', '15', '20', '26', '15', NULL, '2024-03-21 20:07:13', '2024-04-04 23:23:10'),
(69, 3, 'Peter has 4 exams in his algebra subject. His scores in the first three exams were 80, 78, and 73. What score does Peter need to get on his fourth exam in order to have an average of 80?', '85', '87', '89', '90', '89', NULL, '2024-03-21 20:07:13', '2024-03-21 20:07:13'),
(70, 3, 'If a certain task can be completed by 8 men in 12 days, how many men can be assigned to do the same task to finish it in 6 days?', '9', '16', '18', '32', '16', NULL, '2024-03-21 20:07:13', '2024-03-21 20:07:13'),
(71, 4, 'You are an overseas Filipino worker who went to Japan for the first time. During the first few days of your stay, you are disoriented and frustrated due to your exposure to a very strange culture. What does this situation signify?', 'Cultural diversity', 'Cultural relativism', 'Culture shock', 'Fear of culture', 'Culture shock', NULL, '2024-03-21 20:11:49', '2024-03-21 20:11:49'),
(72, 4, 'Which of the following refers to the “way of life” of individuals in a community?', 'Society', 'Culture', 'Manner', 'Organization', 'Culture', NULL, '2024-03-21 20:11:49', '2024-03-21 20:11:49'),
(73, 4, 'Aside from teaching morality and reverence to our Creator, this institution also instills cultural appreciation and cultural values that would shape the personality of a child. What is this institution?', 'Church', 'Government', 'Community', 'Social media', 'Church', NULL, '2024-03-21 20:11:49', '2024-03-21 20:11:49'),
(74, 4, 'Socialization is an important social activity in the development of a person. How does socialization affect the development of an individual?', 'Socialization is an effective tool to understand cultural differences.', 'Socialization helps an individual become better than anyone else.', 'Socialization process is necessary to meet the demands of the society.', 'Undergoing constant socialization enables an individual to fully develop in physical, emotional, and mental aspects.', 'Undergoing constant socialization enables an individual to fully develop in physical, emotional, and mental aspects.', NULL, '2024-03-21 20:11:49', '2024-03-21 20:11:49'),
(75, 4, 'Which of the following statements below is NOT TRUE?', 'Informal sanctions are gossip, unfavorable and favorable public opinion, giving or withdrawing of affection, love or friendship; verbal admiration or criticism, reprimands, or verbal commendations', 'Labelling theory states how members of society label others whether they are deviant or not', 'Laws - are informal rules that are met with positive sanctions', 'Sanctions refers to systems of reward and punishment in order to ensure that norms are followed and expectations met', 'Laws - are informal rules that are met with positive sanctions', NULL, '2024-03-21 20:11:49', '2024-03-21 20:11:49'),
(76, 4, 'In 1973, the American Psychiatric Association (APA) declassified homosexuality as what?', 'Disease', 'Mental health', 'Mental disorder', 'Mental issue', 'Mental disorder', NULL, '2024-03-21 20:11:49', '2024-03-21 20:11:49'),
(77, 4, 'School is one of the different institutions in the society that help in the foundation and development of a person. Students are learning by example from their teacher and their fellow students. Which is an example of enculturation in school?', 'Singing Lupang Hinirang', 'Playing computer games', 'Wearing K-Pop fashion styles', 'Washing the clothes and the dishes', 'Singing Lupang Hinirang', NULL, '2024-03-21 20:11:49', '2024-03-21 20:11:49'),
(78, 4, 'Having more than one wife is not bad from a Muslim point of view. In relation, people must see this custom within the context of Muslims’ problems and opportunities. What kind of view is illustrated here?', 'Ethnocentrism', 'Cultural relativism', 'Barbarism', 'Egocentrism', 'Cultural relativism', NULL, '2024-03-21 20:11:49', '2024-03-21 20:11:49'),
(79, 4, 'The 1987 Philippine Constitution is an example of what kind of constitution?', 'Written Constitution', 'Unwritten Constitution', 'Fundamental law', 'Statute law', 'Written Constitution', NULL, '2024-03-21 20:11:49', '2024-03-21 20:11:49'),
(80, 4, 'Social Pathology explains that deviant behavior is caused by actual physical and mental illness, malfunctions, or deformities. Which of the following is the best solution in controlling this problem caused by actual physical and mental illness, malfunctions, or deformities?', 'Education', 'Capital punishment', 'Hospitalization', 'Rehabilitation', 'Rehabilitation', NULL, '2024-03-21 20:11:49', '2024-04-04 23:24:55'),
(81, 4, 'These are the reason why we need to study the society EXCEPT:', 'To understand the world where we live in and the intricate realities of group interactions and social processes', 'To explain and understand human behavior in the society', 'To determine the existence of animals, their functions, nature, and characteristics', 'To understand how and why human beings act the way that they do', 'To determine the existence of animals, their functions, nature, and characteristics', NULL, '2024-03-21 20:13:11', '2024-03-21 20:13:11'),
(82, 4, 'According to Herbert Mead, our concept of self is product of which of the following term?', 'Social Process', 'Culture', 'Socialization', 'Social Experience', 'Socialization', NULL, '2024-03-21 20:13:11', '2024-04-04 23:25:27'),
(83, 4, 'Buying Gucci, Christian Dior, and Prada bags is an example of what kinds of culture?', 'Popular Culture', 'Subculture', 'Counter Culture', 'High Culture', 'High Culture', NULL, '2024-03-21 20:13:11', '2024-03-21 20:13:11'),
(84, 4, 'Which term refers to the established language, religion, values, rituals, and social customs which are often the norm for society as a whole.', 'Popular culture', 'Counter culture', 'Mainstream culture', 'Subculture', 'Popular culture', NULL, '2024-03-21 20:13:11', '2024-04-04 23:25:43'),
(85, 4, 'Which of the following statements BEST described the Katipunan?', 'It was a movement which aimed for the separation of the Philippines through force.', 'It was a movement that wanted reforms from Spain.', 'It was established to make the Philippines as the Province of Spain.', 'It was established to to strengthen the colonial government.', 'It was a movement which aimed for the separation of the Philippines through force.', NULL, '2024-03-21 20:13:11', '2024-03-21 20:13:11'),
(86, 4, 'The early Filipino revolts were considered a failure. Which of the following is the most important explanation for such failure?', 'The Filipinos had the full support of the clergy to whom Filipinos obeyed because of fear.', 'The Spaniard had an advanced weapon and well-trained military.', 'The revolts were limited in scope.', 'The lack of unity and leadership which failed to sensitize the people to a common identity and purpose.', 'The lack of unity and leadership which failed to sensitize the people to a common identity and purpose.', NULL, '2024-03-21 20:13:11', '2024-03-21 20:13:11'),
(87, 4, 'Colonization of the Philippines by Spain is best described as done through:', 'The \"cross\" because of the conversion by the clergy of pagan Filipinos to Christianity', 'The \"cross\" because Magellan planted the first cross on Philippine soil', 'The \"sword\" because the soldiers forcibly made Filipinos accept the Spanish rule', 'The \"sword and cross\" because they had to make sure that colonization was accomplished.', 'The \"sword and cross\" because they had to make sure that colonization was accomplished.', NULL, '2024-03-21 20:13:11', '2024-03-21 20:13:11'),
(88, 4, 'According to the 1987 Constitution, what kind of state is the Philippines?', 'Federal and Republican', 'Democratic and Federal', 'Federal and Authoritarian', 'Democratic and Republican', 'Democratic and Republican', NULL, '2024-03-21 20:13:11', '2024-03-21 20:13:11'),
(89, 4, 'How many years does a President of the country serve in the Philippines?', '3 years', '6 years', '9 years', '12 years', '6 years', NULL, '2024-03-21 20:13:11', '2024-03-21 20:13:11'),
(90, 4, 'Who is the proponent of the idea that each newly born individual is a tabula rasa or clean slate?', 'Auguste Comte', 'Karl Marx', 'John Locke', 'Thomas Hobbes', 'John Locke', NULL, '2024-03-21 20:13:11', '2024-03-21 20:13:11'),
(91, 5, 'Anong pormasyon ng pantig ang salitang trumpeta?', 'KPKK', 'KKPPKK', 'KKPK', 'KKP', 'KKPK', NULL, '2024-03-21 21:08:30', '2024-03-21 21:08:30'),
(92, 5, 'Alin sa mga sumusunod ang pangngalang pantangi?', 'Mongol', 'Relo', 'Abogado', 'Bata', 'Mongol', NULL, '2024-03-21 21:08:30', '2024-03-21 21:08:30'),
(93, 5, 'Anong aspeto ng pandiwa ang salitang yumuko?', 'Perpektibo', 'Imperpektibo', 'Kontemplatibo', 'Kagaganap', 'Perpektibo', NULL, '2024-03-21 21:08:30', '2024-03-21 21:08:30'),
(94, 5, 'Ipinagmalaki mo siya BAHAG naman pala ANG kaniyang BUNTOT. Ang ibig sabihin ng may malalaking letra ay ____________.', 'kuripot', 'duwag', 'mahiyain', 'traydor', 'duwag', NULL, '2024-03-21 21:08:30', '2024-03-21 21:08:30'),
(95, 5, 'Kabaliwan at paglulustay ang inyong ginagawa taun-taon. Higit na marami ang maralitang nangangailangan ng salapi at dunong. Ang nagsasalita ay ___________.', 'Kuripot', 'Praktikal', 'Maramot', 'Matipid', 'Praktikal', NULL, '2024-03-21 21:08:30', '2024-03-21 21:08:30'),
(96, 5, 'Ang mga kalahok ay “walang itulak kabigin”. Ano ang ibig-sabihin ng talatang ito?', 'Walang magaling', 'May napili na', 'Pawang magagaling', 'Walang mapili', 'Pawang magagaling', NULL, '2024-03-21 21:08:30', '2024-03-21 21:08:30'),
(97, 5, 'Pagsulat ng anumang pumapaloob sa paaralan ay nauuri sa anong pagsulat?', 'Jornalistik', 'Akademiko', 'Teknikal', 'Referensyal', 'Akademiko', NULL, '2024-03-21 21:08:30', '2024-03-21 21:08:30'),
(98, 5, 'Alin sa mga sumusunod ang pagtanggap ng mensahe sa pamamagitan ng pagtugon sa damdamin at kaisipan sa mga titik at simbolong nakalimbag sa pahina?', 'Pagsulat', 'Pakikinig', 'Pagsasalita', 'Pagbasa', 'Pagbasa', NULL, '2024-03-21 21:08:30', '2024-03-21 21:08:30'),
(99, 5, 'Alin sa mga sumusunod ang pinakatamang pangungusap?', 'Nagpapabata ang pulbos sa kutis na Clinique.', 'Nagpapabata sa kutis ang pulbos na Clinique.', 'Nagpapabata ng mukha sa kutis ang pulbos na Clinique.', 'Nagpapabata sa kutis ng mukha ang pulbos na Clinique.', 'Nagpapabata sa kutis ang pulbos na Clinique.', NULL, '2024-03-21 21:08:30', '2024-03-21 21:08:30'),
(100, 5, 'Alin ang wastong salitang naayon sa pahayag: “__________ pag-asa pa ba ako?', 'May', 'Mayroon', 'Magka', 'Nagka', 'May', NULL, '2024-03-21 21:08:30', '2024-03-21 21:08:30'),
(101, 5, '“Naniningalang pugad” Ang idyomatikong pahayag na ito ay nangangahulugang?', 'Nagpapakasal', 'Nanliligaw', 'Nagsasayaw', 'Kumakanta', 'Nanliligaw', NULL, '2024-03-21 21:08:30', '2024-03-21 21:08:30'),
(102, 5, 'Aanhin pa ang bahay na bato kung ang nakatira’y kwago, mabuti pa ang kubo na ang nakatira’y tao.” Ito ay isang halimbawa ng _________________.', 'sawikain', 'salawikain', 'kasabihan', 'kawikaan', 'salawikain', NULL, '2024-03-21 21:08:30', '2024-03-21 21:08:30'),
(103, 5, 'Sa pagsasalita, kinakailangang maging natural ang galaw ng kamay, nakatutulong ito upang mas maging kanais-nais at kahikahikayat ang pagsasalita. Anong sangkap ng pagsasalita ang kailangang gamitin?', 'Kumpas', 'Tindig', 'Tinig', 'Boses', 'Kumpas', NULL, '2024-03-21 21:08:30', '2024-03-21 21:08:30'),
(104, 5, 'Isang paglalarawan ng wika sa tekstwal na tagapamagitan sa pamamagitan ng paggamit ng isang pangkat ng mga tanda, simbolo ay makikita sa anong makrong kasanayan?', 'Pagsasalita', 'Pakikinig', 'Pagbasa', 'Pagsulat', 'Pagsulat', NULL, '2024-03-21 21:08:30', '2024-03-21 21:08:30'),
(105, 5, 'Isang uri ng pagsulat na may kinalaman sa isang partikular na propesyon.', 'Teknikal', 'Akademik', 'Profesyunal', 'Malikhain', 'Profesyunal', NULL, '2024-03-21 21:08:30', '2024-03-21 21:08:30'),
(106, 5, 'Ito ay magkasunod na patinig at malapatinig sa iisang pantig sa loob ng isang salita.', 'Klaster', 'Diptonggo', 'Malapatinig', 'Alibata', 'Klaster', NULL, '2024-03-21 21:08:30', '2024-04-04 23:30:05'),
(107, 5, 'Ano ang tawag sa dalawang salitang magkaiba ang kahulugan ngunit magkatulad na magkatulad ang bigkas maliban sa isang ponema?', 'Pares-nominal', 'Pares-minimal', 'Pares-ponema', 'Pares-tunog', 'Pares-minimal', NULL, '2024-03-21 21:08:30', '2024-03-21 21:08:30'),
(108, 5, 'Tumutukoy sa magkasunod na tunog katinig sa iisang pantig ng isang salita.', 'Klaster', 'Diptonggo', 'Malapatinig', 'Alibata', 'Diptonggo', NULL, '2024-03-21 21:08:30', '2024-04-04 23:30:23'),
(109, 5, 'Anong uri ng aktong ilokusyonaryo ang pahayag na nagpapakita ng sinseridad ng nagsasalita?', 'Assertiv', 'Expresiv', 'Komisiv', 'Deklarativ', 'Komisiv', NULL, '2024-03-21 21:08:30', '2024-04-04 23:30:56'),
(110, 5, 'Kinindatan ni Dustin si Jerick dahil mayroon silang lihim na ayaw ipaalam sa kanilang mga magulang. Ang pagkindat ay isang halimbawa ng anong uri ng di-berbal na komunikasyon?', 'Paralanguage', 'Proksemiks', 'Kronemiks', 'Oculesic', 'Oculesic', NULL, '2024-03-21 21:08:30', '2024-03-21 21:08:30'),
(111, 8, '', '', '', '', '', '5', 'assets/img/660ee7f804dd3.png', '2024-04-04 09:48:15', '2025-04-03 14:42:21'),
(112, 8, '', '', '', '', '', '1', 'assets/img/660eed48e22e9.png', '2024-04-04 10:11:13', '2025-04-03 14:42:21'),
(113, 8, '', '', '', '', '', '7', 'assets/img/660ef150500d2.png', '2024-04-04 10:28:23', '2025-04-03 14:42:21'),
(114, 8, '', '', '', '', '', '4', 'assets/img/660ef16062972.png', '2024-04-04 10:28:39', '2025-04-03 14:42:21'),
(115, 8, '', '', '', '', '', '3', 'assets/img/660ef17710ade.png', '2024-04-04 10:28:56', '2025-04-03 14:42:21'),
(116, 8, '', '', '', '', '', '1', 'assets/img/660ef1f87109b.png', '2024-04-04 10:31:12', '2025-04-03 14:42:21'),
(117, 8, '', '', '', '', '', '6', 'assets/img/660ef209339cc.png', '2024-04-04 10:31:29', '2025-04-03 14:42:21'),
(118, 8, '', '', '', '', '', '1', 'assets/img/660ef215c3e5d.png', '2024-04-04 10:31:44', '2025-04-03 14:42:21'),
(119, 8, '', '', '', '', '', '8', 'assets/img/660ef222d2e95.png', '2024-04-04 10:31:57', '2025-04-03 14:42:21'),
(120, 8, '', '', '', '', '', '4', 'assets/img/660ef235b992d.png', '2024-04-04 10:32:16', '2025-04-03 14:42:21'),
(121, 8, '', '', '', '', '', '5', 'assets/img/660ef24713ab0.png', '2024-04-04 10:32:31', '2025-04-03 14:42:21'),
(122, 8, '', '', '', '', '', '6', 'assets/img/660ef25c4e972.png', '2024-04-04 10:32:49', '2025-04-03 14:42:21'),
(123, 8, '', '', '', '', '', '2', 'assets/img/660ef26a5c87a.png', '2024-04-04 10:33:07', '2025-04-03 14:42:21'),
(124, 8, '', '', '', '', '', '1', 'assets/img/660ef277bab6c.png', '2024-04-04 10:33:21', '2025-04-03 14:42:21'),
(125, 8, '', '', '', '', '', '2', 'assets/img/660ef2870e3f2.png', '2024-04-04 10:33:36', '2025-04-03 14:42:21'),
(126, 8, '', '', '', '', '', '4', 'assets/img/660ef2949bd0d.png', '2024-04-04 10:33:50', '2025-04-03 14:42:21'),
(127, 8, '', '', '', '', '', '6', 'assets/img/660ef2a532037.png', '2024-04-04 10:34:04', '2025-04-03 14:42:21'),
(128, 8, '', '', '', '', '', '7', 'assets/img/660ef2b4934cc.png', '2024-04-04 10:34:22', '2025-04-03 14:42:21'),
(129, 8, '', '', '', '', '', '3', 'assets/img/660ef2cc973ef.png', '2024-04-04 10:34:45', '2025-04-03 14:42:21'),
(130, 8, '', '', '', '', '', '8', 'assets/img/660ef2de6f7f0.png', '2024-04-04 10:35:01', '2025-04-03 14:42:21');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL COMMENT 'e.g., Administrator, Admission Officer, Question Manager',
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Defines user roles for staff access';

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`, `description`) VALUES
(1, 'Administrator', 'Full system access.'),
(2, 'Admission Officer', 'Manages student data and applications.'),
(3, 'Question Manager', 'Manages test subjects and questions.');

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE `schools` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `school_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schools`
--

INSERT INTO `schools` (`id`, `school_id`, `school_name`, `address`) VALUES
(2, 300498, 'Addalam Region High School', 'Bello Street, San Roque, Jones, Isabela'),
(3, 300521, 'Alfreda Albano National High School', 'Purok 1B, Magassi, Cabagan, Isabela'),
(4, 300593, 'Alibadabad National High School', 'Purok 3, Alibadabad, San Mariano, Isabela'),
(5, 300500, 'Alicia National High School', 'Maharlika Highway, Paddad, Alicia,Isabela'),
(6, 300499, 'Alicia Vocational School', 'Purok 5, M.H. Del Pilar, Alicia, Isabela'),
(7, 300546, 'Andabuen National High School', 'Purok 2, Andabuen, Benito Soliven, Isabela'),
(8, 300501, 'Angadanan High School', 'Panganiban St., Centro I, Angadanan, Isabela'),
(9, 300547, 'Antagan National High School', 'Purok II, Antagan I, Tumauini, Isabela'),
(10, 300510, 'Bacnor National High School', 'Purok 1, Bacnor West, Burgos, Isabela'),
(11, 306112, 'Barucboc National High School', 'Barucboc, Quezon, Isabela'),
(12, 300503, 'Benito Soliven National High School', 'Purok 6, District II, Benito Soliven, Isabela'),
(13, 300605, 'Buenaventura G. Masigan National High School', 'Roxas Boulevard Street corner Osmena Street'),
(14, 300509, 'Burgos National High School', 'Purok 2, Caliguian, Burgos, Isabela'),
(15, 300522, 'Cabagan Riverside National High School', 'Purok 6, San Juan, Cabagan, Isabela'),
(16, 306157, 'Cabaruan Integrated School', 'n/a'),
(17, 300504, 'Cabatuan National High School', 'J. Jacinto St, Del Pilar, Cabatuan, Isabela'),
(18, 300506, 'Cadaloria High School', 'Ricardo Street, Loria, Angadanan, Isabela'),
(19, 300508, 'Cagasat High School', 'A Tasani Street, Gayong, Cordon, Isabela'),
(20, 306101, 'Calanigan National High School', 'Calanigan Norte, Santo Tomas, Isabela'),
(21, 300512, 'Callang National High School', 'Rizal St., District 4, San Manuel, Isabela'),
(22, 306143, 'Cordon National High School', 'Sta. Maria Street, Magsaysay, Cordon, Isabela'),
(23, 300518, 'Dabubu High School', 'Purok 6, Dabubu Pequeno, San Agustin, Isabela'),
(24, 400404, 'Dalton Academy', 'Magsaysay Street, Magsaysay. Alicia, Isabela'),
(25, 306137, 'Daragutan East National High School', 'Purok 02, Daragutan East, San Mariano, Isabela'),
(26, 300520, 'Delfin Albano High School', 'Purok 6, Catabayungan, Cabagan, Isabela'),
(27, 300523, 'Diadi Region High School', 'Purok 2,Valdez Street, Villamarzo, Cordon, Isabela'),
(28, 300524, 'Dibuluan National High School', 'Purok 1, Dibuluan, Jones, Isabela'),
(29, 300526, 'Dinapigue National High School', 'Ipil St., Digumased, Dinapigue, Isabela'),
(30, 300549, 'Divilacan National High School', '#NAME?'),
(31, 300529, 'Don Mariano Marcos National High School', '-'),
(32, 300532, 'Dona Josefa E. Marcos High School', 'NATIONAL HIGHWAY'),
(33, 300533, 'Do�a Magdalena Gaffud High School', 'Dammang West, Echague, Isabela'),
(34, 300534, 'Dorganda High School', 'Baltazar Street'),
(35, 300612, 'Echague National High School', 'San Fabian,Echague,Isabela'),
(36, 306109, 'Fermeldy National High School', 'Purok 4, Fermeldy, Tumauini, Isabela'),
(37, 300537, 'Gamu Agri-Fishery School', 'National Highway, Linglingay, Gamu, Isabela'),
(38, 300572, 'General Emilio Aguinaldo National High School', 'Purok 4, Gen. Aguinaldo, Ramon, Isabela'),
(39, 300539, 'Highway Region National High School', 'Barangay Road, Garit Norte, Echague, Isabela'),
(40, 300541, 'Imelda R. Marcos High School', 'Purok 1, Narra, Echague, Isabela'),
(41, 300554, 'Isabela School of Fisheries', 'Sitio Disangkilan, Culasi, Palanan, Isabela'),
(42, 600013, 'Isabela State University Echague Senior High School', 'San Fabian, Echague, Isabela'),
(43, 300555, 'Jones Rural School', 'Barangay 2, Jones, Isabela'),
(44, 306121, 'Jones West High School', 'Purok 4'),
(45, 300586, 'Josefina Albano National High School', 'Purok 6, Paragu, Tumauini, Isabela'),
(46, 306114, 'La Paz National High School', 'Purok 4, La Paz, Cabatuan, Isabela'),
(47, 300556, 'Lalauanan High School', 'Purok 5, Lalauanan, Tumauini, Isabela'),
(48, 300551, 'Lanna National High School', 'Malamag, Lanna, Tumauini, Isabela'),
(49, 300580, 'Lanting Region National High School', 'Lanting, Roxas, Isabela'),
(50, 306115, 'Luis-Fe Gomez Diamantina National High School', 'National Highway, Purok 7, Diamantina, Cabatuan, Isabela'),
(51, 306150, 'Luna General Comprehensive High School', 'Miguel R. Guerrero Complex, Mambabanga, Luna, Isabela'),
(52, 300558, 'Luna National High School', 'Bagara St., Luyao, Luna, Isabela'),
(53, 300559, 'Mabini National High School', 'Purok 6, Mabini, Gamu, Isabela'),
(54, 300560, 'Maconacon National High School', 'Ilang-Ilang St., Fely, Maconacon, Isabela'),
(55, 300513, 'Malalinta National High School', 'Malalinta, San Manuel, Isabela'),
(56, 300561, 'Mallig National High School-Main', 'Olango, Mallig, Isabela'),
(57, 306166, 'Mallig Plains National High School', 'PUROK 3, Centro 1, Mallig, Isabela'),
(58, 306123, 'Manuel L. Quezon National High School', 'District Gragasin, Quezon, San isidro, Isabela'),
(59, 306135, 'Matusalem National High School', 'Purok Nuesa, Matusalem, Roxas, Isabela'),
(60, 306111, 'Monico Rarama National High School', 'San Pedro, Roxas, Isabela'),
(61, 300562, 'Munoz High School', '#NAME?'),
(62, 300604, 'Naganacan-Villabuena National High School', 'Naganacan, Sta Maria, Isabela'),
(63, 300563, 'Naguilian National High School', 'National Highway, Magsaysay, Naguilian, Isabela'),
(64, 400457, 'Northeastern Integrated School of San Agustin Incorporated', 'Purok #1 Masaya Sur, San Agustin, Isabela'),
(65, 400476, 'Northern Isabela Academy, Inc.', 'Calinaoan, Sto. Tomas, Isabela'),
(66, 300564, 'Palanan National High School', 'Dicabisagan West'),
(67, 300565, 'Palayan Region High School', 'Purok 2, Bagnos, Alicia, Isabela'),
(68, 300566, 'Pangal Sur High School', 'Purok 2'),
(69, 300568, 'Quezon National High School', 'ALUNAN, QUEZON, ISABELA'),
(70, 300569, 'Quirino National High School', 'Purok 1, Luna, Quirino, Isabela'),
(71, 300587, 'Ragan Sur National High School', 'Purok 2, Ragan Sur, Delfin Albano, Isabela'),
(72, 300571, 'Ramon National High School', 'Prulyn St., Purok 7, Oscariz, Ramon, Isabela'),
(73, 300574, 'Raniag High School', 'Raniag, Ramon, Isabela'),
(74, 300610, 'Regional Science High School for Region II', 'Camp Samal, Arcon, Tumauini, Isabela'),
(75, 306116, 'Reina Mercedes National High School', 'Purok 1, Cutog Pequeno, Reina Mercedes, Isabela'),
(76, 300575, 'Reina Mercedes Vocational and Industrial School', 'National Highway, Tallungan, Reina Mercedes, Isabela'),
(77, 300570, 'Rizal Comprehensive National High School', 'Purok 1, Rizal, Quirino, Isabela'),
(78, 300576, 'Rizal Region National High School', 'Zone 7, Rizal, Alicia, Isabela'),
(79, 300579, 'Roxas National High School', 'Facoma Site'),
(80, 300581, 'Salinungan National High School', 'Ramones St.'),
(81, 300582, 'San Agustin National High School', 'Zone # 07, Masaya Centro, San Agustin, Isabela'),
(82, 300511, 'San Antonino National High School', 'San Antonino, Burgos, Isabela'),
(83, 300585, 'San Antonio National High School', 'San Antonio, Delfin Albano, Isabela'),
(84, 300590, 'San Guillermo Vocational & Industrial High School', 'T. Village, Purok 6, Centro-2, San Guillermo, Isabela'),
(85, 300591, 'San Isidro National High School', 'Provincial Road'),
(86, 306110, 'San Jose National High School', 'San Jose Norte I, Mallig, Isabela'),
(87, 300592, 'San Mariano National High School - Main', 'Dumelod Street'),
(88, 306117, 'San Mateo General Comprehensive High School', 'Old Centro Proper'),
(89, 306127, 'San Mateo National High School', 'SINAMAR NORTE'),
(90, 300596, 'San Mateo Vocational and Industrial High School', 'National Highway, San Andres'),
(91, 300597, 'San Pablo National High School', 'Poblacion, San Pablo, Isabela'),
(92, 300514, 'Sandiat National High School', 'Purok 1, Sandiat East, San Manuel, Isabela'),
(93, 300603, 'Santa Maria High School', 'Bliss Site Poblacion1, Santa Maria, Isabela'),
(94, 300606, 'Santo Tomas National High School', 'Centro, Santo Tomas, Isabela'),
(95, 400450, 'School of Saint Joseph (Naguilian, Isabela), Inc.', 'QUEZON (POB.), Naguilian, Isabela'),
(96, 400477, 'School of Saint Matthias', 'District IV, Tumauini, Isabela'),
(97, 300600, 'Sgt. Prospero Bello High School - Main', 'Purok 7 Napaliong, Jones, Isabela'),
(98, 306132, 'Simanu National High School', 'Simanu Norte, San Pablo, Isabela'),
(99, 410964, 'SISTECH COLLEGE OF SANTIAGO CITY (RAMON BRANCH)', 'Purok 1'),
(100, 306168, 'Southeastern Region High School', 'Purok 4, Sinaoangan Sur, San Agustin, Isabela'),
(101, 400429, 'St. John Berchman High School Incorporated', 'National Road'),
(102, 300601, 'St. Paul Vocational and Industrial High School', 'Auitan-San Jose Road, Auitan, San Pablo, Isabela'),
(103, 300609, 'Tumauini National High School', 'Former Delfin Albano Road, Annafunan,Tumauini, Isabela'),
(104, 300595, 'Ueg National High School', 'Purok 4'),
(105, 300611, 'Ugad High School', 'Villa Turingan, Sto. Domingo, Echague, Isabela'),
(106, 300502, 'Villa Domingo National High School', 'PUROK 1, Villa Domingo, Angadanan, Isabela'),
(107, 342077, 'Aurora Senior High School', 'Ballesteros, Aurora, Isabela'),
(108, 305731, 'Delfin Albano (Magsaysay) Stand Alone Senior High School', 'San Antonio, Delfin Albano, Isabela'),
(109, 401773, 'HG Baquiran College', 'Tumauini, Isabela'),
(110, 342078, 'Isabela Sports Senior High School', 'Alibagu, City of Ilagan, Isabela'),
(111, 600337, 'Isabela State University Roxas Campus', 'Luna-Rangayan'),
(112, 600012, 'Isabela State University-Cabagan', 'National Highway, Garita, Cabagan, Isabela'),
(113, 305941, 'Rodolfo B. Albano Stand-Alone Senior High School', 'Mabini Avenue, Catabayungan, Cabagan, Isabela'),
(114, 305832, 'Roxas Stand Alone Senior High School', 'Purok 3'),
(115, 305943, 'Salinungan Stand-Alone Senior High School', 'Ramones St.'),
(116, 411116, 'Santo Tomas Technological International School (STTIS) INC.', 'King Taner Arcade Bldg, Centro, Sto. Tomas, Isabela'),
(117, 305942, 'Tumauini Stand-Alone Senior High Schol', 'Annafunan, Tumauini Isabela'),
(118, 409368, 'Worldstar College of Science and Technology, Inc.', '1st/2nd Flr., Hatol Bldg., National Highway, Bantug, Roxas, Isabela'),
(119, 1415509, 'Advance Montessori Education Center of Isabela', '#NAME?'),
(120, 1400459, 'Alejandrino Family Learning Center', 'National Highway, Zone 3 Poblacion, San Mariano, Isabela'),
(121, 1415538, 'Alvarez Ramales School Foundation', 'Baldugo St.'),
(122, 1500075, 'Banquero Integrated School', 'Purok 4 Lappay St., Banquero, Reina Mercedes, Isabelas'),
(123, 1500077, 'Bimonton Integrated School', 'Purok 5, Bimonton, Mallig, Isabela'),
(124, 1501202, 'Cabaruan Integrated School', 'Barangay Cabaruan'),
(125, 1415553, 'Casa Del Ni�o Montessori School of Roxas', 'San Rafael, Roxas, Isabela'),
(126, 1500091, 'Cebu Integrated School', 'Purok 5, Cebu, San Isidro, Isabela'),
(127, 1500088, 'Cumabao Integrated School', 'Cumabao,Tumauini,Isabela'),
(128, 1500014, 'Del Pilar Integrated School', 'ZONE 2, DEL PILAR SAN MARIANO ISABELA'),
(129, 1500086, 'Dicamay Integrated School', 'Purok 4, Brgy. Dicamay'),
(130, 1500016, 'Dingading Integrated School', 'Purok 2, DINGADING San Guillermo, Isabela'),
(131, 1500093, 'Eden Integrated School', 'D. Baldoz St., Eden, San Manuel,Isabela'),
(132, 1400443, 'Infant Jesus De Providencia Learning and Development Center, Incorporated', '#16 Vallejo Street'),
(133, 1400453, 'JET Montessori School of Ramon, Incorporated', 'Roxas Corner Quirino, Bugallon Proper, Ramon, Isabela'),
(134, 1400410, 'La Salette of Aurora, Inc.', 'Sta. Rosa'),
(135, 1400416, 'La Salette of Cabatuan, Inc.', 'Rafael St., San Andres, Cabatuan, Isabela'),
(136, 1400444, 'La Salette of Jones, Inc.', 'Gallardo St. Barangay 1, Jones, Isabela'),
(137, 1400452, 'La Salette of Quezon', 'Samonte'),
(138, 1400454, 'La Salette of Ramon, Inc.', 'Ambatali St., Bugallon Proper, Ramon, Isabela'),
(139, 1400455, 'La Salette of Roxas College, Inc.', 'Magsaysay St.'),
(140, 1400462, 'La Salette of San Mateo, Incorporated', 'La Salette Lane Brgy. 1, San Mateo, Isabela'),
(141, 1415508, 'Lord Reigns Christian Academy', 'Purok 7, Bugallon Proper, Ramon, Isabela'),
(142, 1400446, 'Magsaysay Memorial High School', 'Villa Luz, Delfin Albano, Isabela'),
(143, 1400448, 'Mallig Plains Colleges', 'Purok 6, Almaciga St. Casili, Mallig, Isabela'),
(144, 1400406, 'Northeast Luzon Adventist College, Inc.', 'National Hi-way'),
(145, 1415544, 'Odizee School of Achievers', 'Acto'),
(146, 1400417, 'Philippine Yuh Chiau School', 'A. Bonifacio St.'),
(147, 1500022, 'Rizal Integrated School, San Guillermo', 'Mangay Street'),
(148, 1400414, 'Saint Ferdinand College - Cabagan Campus', 'Don Francisco Albano St.'),
(149, 1500070, 'San Jose Integrated School', 'Purok 03'),
(150, 1500092, 'San Miguel Integrated School', 'Purok 6, San Miguel, Ramon, Isabela'),
(151, 1500081, 'San Sebastian Integrated School', 'General Luna St., San Sebastian, Ramon, Isabela'),
(152, 1400407, 'School of Our Lady of Atocha', 'Magsaysay, Alicia, Isabela'),
(153, 1500094, 'Simimbaan Integrated School', 'Progreso'),
(154, 400458, 'Southern Isabela Academy, Inc.', 'R.MAGSAYSAY EXTENSION, CENTRO I, SAN GUILLERMO, ISABELA'),
(155, 1400432, 'St. Dominic Human Development Center', 'Soyung, Echague, Isabela'),
(156, 1415516, 'St. Thomas Montessori De San Mariano', 'ZONE 3, SAN MARIANO, ISABELA'),
(157, 1500082, 'Sto. Domingo Integrated School', 'PROVINCIAL ROAD'),
(158, 1500023, 'Sto. Ni�o Integrated School', '#NAME?'),
(159, 1415590, 'Top Achievers Private School, Inc.', 'Fortune, Aurora, Alicia Isabela'),
(160, 1410161, 'TOP ACHIEVERS PRIVATE SCHOOL, INC., ROXAS CAMPUS', 'Maharlika Highway, Nassim Compound, San Antonio, Roxas, Isabela'),
(161, 1500076, 'Turod Integrated School', 'Purok 1, Dulay Street, Turod, Reina Mercedes, Isabela'),
(162, 1500068, 'Villa Cacho Integrated School', 'VILLA CACHO, SANTIAGO, QUIRINO, ISABELA'),
(163, 1500073, 'Wigan Integrated School', 'Purok 4A, Wigan,Cordon, Isabela'),
(164, 1415534, 'Wonderful Grace Learning Center', 'Purok 1, Luna, Quirino, Isabela');

-- --------------------------------------------------------

--
-- Table structure for table `strands`
--

CREATE TABLE `strands` (
  `strand_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `strands`
--

INSERT INTO `strands` (`strand_id`, `name`) VALUES
(1, 'Accountancy, Business, and Management (ABM)'),
(2, 'Science, Technology, Engineering, and Mathematics (STEM)'),
(3, 'Humanities and Social Sciences (HUMSS)'),
(4, 'General Academic Strand (GAS)'),
(5, 'Home Economics (HE)'),
(6, 'Information and Communications Technology (ICT)'),
(7, 'Industrial Arts'),
(8, 'Agri-Fishery Arts'),
(9, 'Arts and Design'),
(10, 'Sports');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `passcode` varchar(10) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `first_preference_id` int(11) DEFAULT NULL,
  `second_preference_id` int(11) DEFAULT NULL,
  `strand_id` int(11) DEFAULT NULL,
  `enrollment_status` enum('Freshman','Transferee','Second Course','Others') DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `lrn` varchar(20) DEFAULT NULL,
  `gwa` decimal(4,2) DEFAULT NULL,
  `barangay_id` int(11) DEFAULT NULL,
  `sex` enum('Male','Female') DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `municipality_id` int(11) DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL,
  `purok` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `passcode`, `last_name`, `first_name`, `middle_name`, `first_preference_id`, `second_preference_id`, `strand_id`, `enrollment_status`, `school_id`, `lrn`, `gwa`, `barangay_id`, `sex`, `birthday`, `email`, `contact_number`, `created_at`, `municipality_id`, `province_id`, `purok`) VALUES
(1, '0000', 'BALTAZAR', 'CARLO', 'VIDAL', 95, 32, 2, 'Freshman', 114, '12314', 89.00, 741, 'Male', '1992-09-29', 'carlo.v.baltazar@isu.edu.ph', '+639166027454', '2025-04-02 09:15:52', 24, 1, '3320, Vidal Junkshop Compound, Besides Forrest Hotel, Isabela, Roxas, Bantug (Pob.)'),
(2, '0001', 'ABANILLA', 'AIRA KATRIEL', 'YMASA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:52', NULL, NULL, NULL),
(3, '0002', 'ABENOJA', 'AGE', 'MONTILLA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:52', NULL, NULL, NULL),
(4, '0003', 'ABOBO', 'MARIVIC', 'PASCUAL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:52', NULL, NULL, NULL),
(5, '0004', 'ACIBAR', 'MARIA THERESA JOYCE DELA', 'CRUZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(6, '0005', 'AGSALOG', 'JHON LENARD', 'DAVID', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(7, '0006', 'AGUSTIN', 'BRYLLE ARCIS', 'TUMOLVA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(8, '0007', 'AGUSTIN', 'ERICKSON', 'CENTINO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(9, '0008', 'AGUSTIN', 'ZYREL BLEMAR', 'ULEP', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(10, '0009', 'ALAVA', 'ANGELA', 'VILLANUEVA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(11, '0010', 'ALLAUIGAN', 'ANGELO', 'DOMINGO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(12, '0011', 'ALTERADO', 'DIANA MAE', 'RAMOS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(13, '0012', 'ALTIZ', 'RACHELLE MAE', 'PEPITO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(14, '0013', 'AMADA', 'JESSICA', 'RIVERA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(15, '0014', 'ANCERO', 'RICKY', 'CORPUZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(16, '0015', 'ANCHETA', 'JUSH', 'NOSES', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(17, '0016', 'ANDRES', 'JAIRAH BIANCA', 'ANTONIO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(18, '0017', 'ANGANGAN', 'ANGELYN', 'AGUSTIN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(19, '0018', 'ANTONIO', 'LIZA MAE', 'PALOMA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(20, '0019', 'APALLA', 'COLEEN JOY', 'LICTAOA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(21, '0020', 'APOSTOL', 'SUNSHINE', 'PAGUIRIGAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(22, '0021', 'AQUINO', 'JUDY ANNE', 'BALBIN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(23, '0022', 'ARCEO', 'JEMICA', 'BA?EZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(24, '0023', 'ARDILES', 'ROBILYN', 'ALAVA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(25, '0024', 'AREOLA', 'PRECILYN', 'ASIRIT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(26, '0025', 'ARQUERO', 'KRISTINE JOY', 'QUITALIG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(27, '0026', 'ARQUERO', 'MA.LETICIA', 'SANCHEZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(28, '0027', 'ARQUERRO', 'JULIUS MARTY DE', 'FIESTA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(29, '0028', 'ASUERO', 'GIAN CARLO', 'VITALES', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(30, '0029', 'ASUERO', 'ROSE JANE', 'LABAYOH', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(31, '0030', 'BAAY', 'PRECIOUS ANGIE', 'AGAOAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(32, '0031', 'BACCAY', 'EDLYN MAE', 'BADDONG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(33, '0032', 'BACCAY', 'JAYMARK', 'URBANO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(34, '0033', 'BACULI', 'RHEALINE FELLIXS VALERIE', 'CAUILAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(35, '0034', 'BALAJADIA', 'IRISH', 'EDRADAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(36, '0035', 'BALDERAMOS', 'MARIA,CLAUDIA', 'ABON', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(37, '0036', 'BALITE', 'PRINCES ELLA', 'FERRER', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(38, '0037', 'BALLESTEROS', 'PRINCESS NICOLE', 'SILVA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(39, '0038', 'BALUBAR', 'CHARMAINE', 'SEJALBO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(40, '0039', 'BALUBAR', 'KEZIA', 'ESCUBIO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(41, '0040', 'BANGUG', 'EDMAR', 'BULUSAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(42, '0041', 'BARLITA', 'KRISHA', 'JOSE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(43, '0042', 'BARUT', 'ARISTOTLE CIRILO', 'MATUSALEM', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(44, '0043', 'BASILIO', 'MICHELLE', 'MARTIN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(45, '0044', 'BAUA', 'MABEL DELOS', 'SANTOS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(46, '0045', 'BAUTISTA', 'FENDI', 'ZALUN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(47, '0046', 'BAUTISTA', 'JESSA', 'SORIANO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(48, '0047', 'BEDUYA', 'ANGEL', 'SALINAS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(49, '0048', 'BELLO', 'IVAN JEFF', 'CANDELARIA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(50, '0049', 'BENITO', 'MARGAUX', 'LUCAS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(51, '0050', 'BERMUDEZ', 'KARL JUSTINE', 'VIERNES', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(52, '0051', 'BIADO', 'NICUL', 'MADRIAGA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(53, '0052', 'BICARME', 'REINDEL', 'BAYANAY', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(54, '0053', 'BRUNIO', 'ZYREN', 'BERNARDO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(55, '0054', 'BUCAG', 'CHRISTIAN BERT', 'LANDICHO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(56, '0055', 'BULUSAN', 'IAN', 'MINA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(57, '0056', 'CABALSI', 'AINGILYN', 'BUGAOISAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(58, '0057', 'CABANIT', 'IAN', 'DOMINGO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(59, '0058', 'CABANIT', 'JERICHO', 'CALAPIT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(60, '0059', 'CABASAG', 'KING JB', 'GALABAO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(61, '0060', 'CABAUATAN', 'MARRY ANN', 'LAGAJET', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(62, '0061', 'CABREROS', 'DARLENE', 'MARCOS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(63, '0062', 'CACHO', 'BERNADETH', 'CORTEZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(64, '0063', 'CACHO', 'ROSEMAE', 'RANIA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(65, '0064', 'CADORNA', 'JANDEL', 'DALIT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(66, '0065', 'CADORNA', 'SHARMAINE', 'CARRIAGA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(67, '0066', 'CALIGUIRAN', 'JOHN PAUL', 'MINA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(68, '0067', 'CALLE', 'BENJO', 'CARILLO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(69, '0068', 'CALLE', 'WISLIE JECHELLE', 'GASMIN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(70, '0069', 'CALLUENG', 'FREDDIE', 'VELASCO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(71, '0070', 'CAMANGEG', 'ERYLL RUTH', 'ABAD', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(72, '0071', 'CAMPANO', 'CHERIE MAE', 'MARIANO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(73, '0072', 'CAMUS', 'ROSEMARIE', 'MERCADO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(74, '0073', 'CANITE', 'VALLERIE JOY', 'REMIGIO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(75, '0074', 'CARANZO', 'ANGEL', 'BOADO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(76, '0075', 'CARIAZO', 'M JHAY', 'DELA CRUZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(77, '0076', 'CASER', 'JOMABHEL', 'ULAY', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(78, '0077', 'CASTIGO', 'ANGELYN', 'TANGONAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(79, '0078', 'CASTRO', 'JESSICA', 'VARGAS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(80, '0079', 'CENARDO', 'ELYSSA MAE', 'TABUADA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(81, '0080', 'CERCADO', 'RHEA FRENCHIE', 'ALAVA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(82, '0081', 'CORPUZ', 'ZHAYRINE ANGEL', 'BARTOLOME', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(83, '0082', 'CUCHAPIN', 'CLOEY MAE', 'MARZAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(84, '0083', 'CUDAL', 'JOMELYN', 'AZUERO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(85, '0084', 'DACAYANAN', 'MARK IAN', 'SORIANO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(86, '0085', 'DACUAG', 'JUBIE ANN', 'HIDALGO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(87, '0086', 'DAGDAG', 'KYLE NORBEN', 'BORCES', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(88, '0087', 'DAGUIO', 'JEFFERSON', 'ALTAYO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(89, '0088', 'DALIT', 'FRANCIS ADRIAN', 'MORA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(90, '0089', 'DALIT', 'RAISA JOMAIKA', 'GUMIRAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(91, '0090', 'DALIT', 'RHEA', 'FERNANDEZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(92, '0091', 'DATUIN', 'RICA MAE', 'AZARCE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(93, '0092', 'DAYOS', 'JONARD', 'ESTIOCO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(94, '0093', 'DE GUZMAN', 'ARMANDO', 'SILAROY', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(95, '0094', 'DE LEON', 'JAKE VENCI', 'LARDIZABAL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(96, '0095', 'DE SOLA', 'JOHN MICHAEL', 'DELFIN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(97, '0096', 'DE VERA', 'KATHLEEN', 'QUINEZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(98, '0097', 'DE VERA', 'VIELLE ANGELA GRACE', 'MINOR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(99, '0098', 'DELA CRUZ', 'ERICH GRACE', 'GABRIEL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(100, '0099', 'DELA CRUZ', 'JASMINE', 'LABONG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(101, '0100', 'DELA CRUZ', 'KHRISTINE CLAIRE', 'ASUNCION', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(102, '0101', 'DELA CRUZ', 'LEA', 'TAYLAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(103, '0102', 'DELA ROSA', 'LYZAH JILLIAN', 'AGTANG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(104, '0103', 'DELOS SANTOS', 'JOHN MARK', 'PI?ERA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(105, '0104', 'DERIJE', 'VINCE JOSHUA', 'LAPUENTE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(106, '0105', 'DESCANZO', 'JHONLERY', 'URSUA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(107, '0106', 'DESCARGAR', 'PRINCESS ALEXAH', 'VALDEZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(108, '0107', 'DIAMPOC', 'KEITH', 'TALUGAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(109, '0108', 'DIASCAN', 'KYLA', 'MAMAUAG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(110, '0109', 'DINGRAT', 'QUENIE', 'LOPEZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(111, '0110', 'DIVINA', 'RAM JESCLER', 'DUMLAO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(112, '0111', 'DOLLENTE', 'SHIELA FAYE', 'CABATU', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(113, '0112', 'DOMINGO', 'LEANDER', 'POSION', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(114, '0113', 'DORUELO', 'CHRIS JOHN', 'CARDONA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(115, '0114', 'DORUELO', 'KATHERINE', 'GERARDO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(116, '0115', 'DUARTE', 'JESSA MAE', 'DUMLAO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(117, '0116', 'DUCO', 'CRIZZA JANE', 'REYES', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(118, '0117', 'DUMALAY', 'THIZALLY', 'ZAMORA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(119, '0118', 'DUMAYAS', 'JOHN PAUL', 'DE LEON', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(120, '0119', 'EBREO', 'DAREL', 'ESQUIVEL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(121, '0120', 'EDROSO', 'LUCKY MILES', 'CAMIGUING', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(122, '0121', 'ELVI?A', 'JHON LLOYD', 'DUMAWAL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(123, '0122', 'ESQUIVEL', 'RHEN JAY', 'COROTAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(124, '0123', 'ESTABILLO', 'JAMAICA CLARENCE', 'ULEP', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(125, '0124', 'ESTARDO', 'REYMOND', 'PRADO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(126, '0125', 'ESTIMADA', 'JEMIMA', 'ESCUBIO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(127, '0126', 'EUGENIO', 'VENUS', 'URMATAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(128, '0127', 'FELICIANO', 'KYLE ADZEN', 'BERCASIO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(129, '0128', 'FERNANDEZ', 'A-JAY', 'SINFUEGO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(130, '0129', 'FRAGATA', 'ANGELO', 'BAGGAY', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(131, '0130', 'GABA', 'JAIRUS', 'MANRIQUE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(132, '0131', 'GALIZA', 'MELANIE', 'DANCEL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(133, '0132', 'GALLARDO', 'GLYDEL JEA', 'DEUS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(134, '0133', 'GALLEON', 'JENNIFER', 'REALIZA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(135, '0134', 'GALLESTRE', 'DIANA ROSE', 'DEL ROSARIO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(136, '0135', 'GALLESTRE', 'KARLA JANE', 'PACIS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(137, '0136', 'GANDEZA', 'PRECIOUS ELAIZA', 'ANTONIO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(138, '0137', 'GANGAN', 'CHRISTEL FAYE', 'MEDICO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(139, '0138', 'GANZAGAN', 'JAYSON', 'PARINGIT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(140, '0139', 'GARCIA', 'ABIGAIL', 'LEONEN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(141, '0140', 'GARCIA', 'DANIELA CLOUWIE', 'AGRAAM', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(142, '0141', 'GARCIA', 'SARAH JEAN', 'AGBUNAG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(143, '0142', 'GASMIN', 'DIANA JANE', 'BANGUG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(144, '0143', 'GATCHALIAN', 'ASHLY NICOLE', 'TABILIN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(145, '0144', 'GAUDAN', 'ANDREW', 'MARQUEZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(146, '0145', 'GELACIO', 'PRINCESS LEANE', 'VALLE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(147, '0146', 'GOROSPE', 'NICOLE', 'BALORIO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(148, '0147', 'GRANADOZO', 'RHEAN', 'DUCO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:53', NULL, NULL, NULL),
(149, '0148', 'GUZMAN', 'JENAMARI', 'BAUTISTA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(150, '0149', 'GUZMAN', 'NEJE MIAH', 'AQUINO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(151, '0150', 'GUZMAN', 'RICA', 'NICOLAS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(152, '0151', 'HABON', 'ACZER', 'ALMORADIE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(153, '0152', 'HERNANDEZ', 'DARYNE', 'LIMBAYAO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(154, '0153', 'HERNANDEZ', 'JAY ANN MAE', 'DIOSO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(155, '0154', 'HERNANDEZ', 'RENZ', 'DORUELO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(156, '0155', 'HERNANDEZ', 'SHENA MAE', 'DELOS SANTOS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(157, '0156', 'ILAGAN', 'CHELO MAY', 'ALMEROL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(158, '0157', 'ILAGAN', 'SHARON', 'AMBROCIO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(159, '0158', 'INERE', 'JILLIAN MAE', 'ANCHETA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(160, '0159', 'INERE', 'MARILYN', 'CORPUZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(161, '0160', 'INFANTE', 'CALVINJAKE', 'ULOG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(162, '0161', 'IQUIN', 'JANELLE', 'ANQUE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(163, '0162', 'IQUIN', 'JOHN RUSSEL', 'MORALES', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(164, '0163', 'ISIP', 'PRINCES ANN', 'AGAM', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(165, '0164', 'JARDINEZ', 'MARCELINO', 'DUCO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(166, '0165', 'JOVE', 'MARK JHON', 'ERORITA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(167, '0166', 'JULIAN', 'JAMIEBEL JOY', 'GRANDE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(168, '0167', 'JULIAN', 'JAYLORD', 'DELEON', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(169, '0168', 'LABAYOG', 'CHEOMLLYMAR', 'VALDEZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(170, '0169', 'LABBUANAN', 'MARSHA', 'RINGOR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(171, '0170', 'LABORTE', 'MIA ROSE', 'MADDUMBA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(172, '0171', 'LABORTE', 'PRINCESS LARA MAE', 'FLORENDO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(173, '0172', 'LACERNA', 'PRINCESS NICOLE', 'JAVA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(174, '0173', 'LACUESTA', 'JOSHUA', 'MEDRANO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(175, '0174', 'LAGUINDAY', 'JEMELYN', 'ADLUZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(176, '0175', 'LARIOSA', 'ALEAH NICOLE', 'SADUCOS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(177, '0176', 'LASAM', 'ERNEST', 'ALAPOT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(178, '0177', 'LAURIA', 'JAYMAR', 'CARBONELL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(179, '0178', 'LAYUGAN', 'RACHELLE', 'SAGUCIO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(180, '0179', 'LIBAO', 'TONY JAMES', 'CUIZON', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(181, '0180', 'LIBED', 'JASMIN', 'GRAGANTA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(182, '0181', 'LIDAY', 'JHEZ', 'BAUTISTA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(183, '0182', 'LINDA', 'JONA MAE', 'DELOS SANTOS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(184, '0183', 'LLEGAD', 'MARK ANTHONY', 'ROSIETE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(185, '0184', 'LOMBOY', 'SHELLA', 'RIMANDO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(186, '0185', 'LUNGUB', 'NADINE', 'ESTEBAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(187, '0186', 'MACADANGDANG', 'LHEA', 'SABADO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(188, '0187', 'MACAPULAY', 'WINSY', 'DOCUMENTO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(189, '0188', 'MACARILAY', 'ARREN JAY', 'AGANON', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(190, '0189', 'MADDAWIN', 'JULIO', 'DUMAWAL JR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(191, '0190', 'MADDUMA', 'MARK', 'TABLIAGO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(192, '0191', 'MADDUMBA', 'MAICA', 'CALAUNAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(193, '0192', 'MALLAVO', 'STEPHANIE CYRILLE', 'PIL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(194, '0193', 'MANUEL', 'KRISHIA MAE', 'GARCIA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(195, '0194', 'MANZANO', 'LEO MARK', 'BINGAYAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(196, '0195', 'MARCOS', 'STANLEY JEFF', 'BANGLOY', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(197, '0196', 'MARIANO', 'NICOLE ANN', 'AGNER', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(198, '0197', 'MARINDUQUE', 'ELLA JANE', 'M.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(199, '0198', 'MARINDUQUE', 'MARICEL', 'BAUA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(200, '0199', 'MARINDUQUE', 'MELANIE JOICE', 'VENTURA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(201, '0200', 'MARTIN', 'KATHLEEN CHARISH', 'GARCIA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(202, '0201', 'MARTIN', 'KATHLYN', 'JULIO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(203, '0202', 'MARTINEZ', 'CAROL JOY', 'SADUCOS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(204, '0203', 'MATIAS', 'ROGELIO', 'LUMBOY', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(205, '0204', 'MELCHOR', 'TRISHA MAE', 'LUCENA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(206, '0205', 'MENSALVAS', 'DELMARIE', 'VELASCO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(207, '0206', 'MERCADO', 'JASPER', 'BONGOLAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(208, '0207', 'MERCADO', 'LUISA JEAN', 'ABAD', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(209, '0208', 'MOISES', 'MYRAH', 'SORIANO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(210, '0209', 'MOLANO', 'ELLAINE ROSE', 'REYES', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(211, '0210', 'MONFORTE', 'REYLAND', 'RIOS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(212, '0211', 'MONTORO', 'ERICKA MAY', 'EMBOY', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(213, '0212', 'MORALES', 'MERINE JANE', 'SALINAS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(214, '0213', 'NACAR', 'JOHN PAUL', 'GALLARDO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(215, '0214', 'NAGASAO', 'LOREN', 'SANTIAGO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(216, '0215', 'NAGUM', 'FRANCIS', 'TALLEDO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(217, '0216', 'NARAG', 'JANNA JANE', 'SALINAS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(218, '0217', 'NICOLAS', 'JHERWINA', 'SABADO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(219, '0218', 'OLEGENIO', 'DIANA', 'IGNACIO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(220, '0219', 'ORENCIA', 'JETHRO', 'MANGABAT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(221, '0220', 'ORUA', 'AISHA JANE', 'SAGUN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(222, '0221', 'PABRO', 'CHESKA CLAIRE', 'VARGAS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(223, '0222', 'PACIS', 'JEMICCAH', 'GALUPO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(224, '0223', 'PADRIGO', 'DANICA JOY', 'EUGENIO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(225, '0224', 'PAGUIRIGAN', 'EDIESON', 'TAGAYUN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(226, '0225', 'PAGUYO', 'RON RON', 'CALANTOC', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(227, '0226', 'PAJO', 'ALYSA', 'PERALTA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(228, '0227', 'PALAGUITTO', 'JUNE', 'LOPEZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(229, '0228', 'PALAPUZ', 'DAISY', 'TAJON', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(230, '0229', 'PALISOC', 'MANUELA', 'GRAGASIN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(231, '0230', 'PAMBID', 'MARK RAVEN', 'VALDEZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(232, '0231', 'PARAISO', 'DIMPLE', 'FLORES', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(233, '0232', 'PASSILAN', 'ROXANNE', 'I?IGO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(234, '0233', 'PASTOR', 'CHARLENE MAE', 'BALUBAR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(235, '0234', 'PAULO', 'ANGELYN', 'PAMBID', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(236, '0235', 'PETINES', 'KATHLYN JOY', 'CABBAB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(237, '0236', 'PIEDAD', 'RICH MEL', 'SALDO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(238, '0237', 'PIGAO', 'GERALDINE', 'RAGUINDIN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(239, '0238', 'PILLOS', 'GABRIEL', 'SANTOS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(240, '0239', 'PINTO', 'FRANCIS JOHN', 'AYAT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(241, '0240', 'PINTO', 'FRETHZ', 'AYAT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(242, '0241', 'ADLOS', 'PRINCESS GWYNETH', 'CARVAJAL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(243, '0242', 'AGUSTIN', 'JULIA NICOLE', 'PABILING', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(244, '0243', 'ALAVA', 'JOHNNY', 'NATIVIDAD', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(245, '0244', 'ALCANTARA', 'ANGELA JOY', 'VILLACAMPO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(246, '0245', 'ALLADO', 'ANGEL MAY', 'GAPAY', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(247, '0246', 'AMOS', 'DENISE', 'DAGGAO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(248, '0247', 'ANDRES', 'NOVELYN', 'AGUINALDO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(249, '0248', 'ANGUI', 'EMERALD', 'VALIENTE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(250, '0249', 'ANTALAN', 'PRINCESS JANE', 'RIGOR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(251, '0250', 'APALLA', 'JONARD', 'NATIVIDAD', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(252, '0251', 'AQUINO', 'VJAY', 'PANAGA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(253, '0252', 'ARANDA', 'SAISAE', 'MAMASALAGAT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(254, '0253', 'ASIRIT', 'NEL XANDER', 'LORENZO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(255, '0254', 'ATTABAN', 'JERWIN', 'AGBAYANI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(256, '0255', 'BALA', 'JOYCE', 'MERCADO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(257, '0256', 'BALINO', 'CHRISTIAN JAYLORD', 'LIZARDO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(258, '0257', 'BAUTISTA', 'JHERWIN', 'MADAYAG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(259, '0258', 'BENITO', 'JOHNRELLE', 'DE GUZMAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(260, '0259', 'BERGONIA', 'PRINCESS ANGELIQUE', 'BAYONA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(261, '0260', 'BILOG', 'SHARMAINE', 'LARIOSA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(262, '0261', 'BIMBO', 'JOHN MARK', 'BASBAS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(263, '0262', 'BRUNIO', 'ZYREL', 'BERNARDO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(264, '0263', 'BUGUINA', 'JENELYN', 'SILAPAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(265, '0264', 'BULLANDAY', 'RICA', 'MARTIN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(266, '0265', 'BULUSAN', 'MARIAN SHIN', 'ALLAUIGAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(267, '0266', 'CALAMAZA', 'RECHELLE', 'ANQUILLANO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(268, '0267', 'CALIBOSO', 'FLORENCIO', 'TALAY', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(269, '0268', 'CALIXTRO', 'LHEANNE JOYCE', 'CARIAGA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(270, '0269', 'CANIEDO', 'PRINCESS', 'TABIOS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(271, '0270', 'CONTAWE', 'BADREA', 'ALAMBRA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(272, '0271', 'CORNELIO', 'SHIENA MARIE', 'SANTIAGO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(273, '0272', 'CORPUZ', 'JOHN LLOYD', 'SORIANO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(274, '0273', 'CORPUZ', 'MAC LORDY', 'ANADILLA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(275, '0274', 'CORPUZ', 'NICOLE', 'AGUSTIN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(276, '0275', 'CORPUZ', 'RUTH', 'DAUS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(277, '0276', 'CORTEZ', 'DIVINE', 'CASTILLO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(278, '0277', 'COSTALES', 'ELLA MAY', 'DAGMAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(279, '0278', 'DALIT', 'JOVY', 'SILVA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(280, '0279', 'DATUL', 'PRINCESS MARIE', 'JOSE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(281, '0280', 'DAYAG', 'JHERWIN CHARLES', 'MILLAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(282, '0281', 'DE OCAMPO', 'JIMRICH VERA', 'CRUZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(283, '0282', 'DEVERA', 'EUNICE', 'DELOS REYES', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(284, '0283', 'DIEGO', 'MARY ANN', 'VALDEZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(285, '0284', 'DOMINGO', 'AARON KYLE', 'CALUTAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(286, '0285', 'DOMINGO', 'RINA', 'SANTOS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(287, '0286', 'DORUELO', 'EJIE BOY', 'CABATU', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(288, '0287', 'DUCO', 'JHAN-JHAN', 'CASTRO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(289, '0288', 'DULAY', 'JOMAR', 'AGUINALDO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(290, '0289', 'FELIPE', 'JAERON', 'QUIBUYEN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(291, '0290', 'FERNANDEZ', 'ANGEL', 'MIRANDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(292, '0291', 'FERNANDEZ', 'EDELYN', 'FLORES', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(293, '0292', 'GADAYOS', 'JAYMAR', 'BUSCAS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(294, '0293', 'GALASTRE', 'MARIE FAYE', 'GALASTRE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(295, '0294', 'GAMIAO', 'GERALD PHILIP', 'FORTO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(296, '0295', 'GANADO', 'LANDER', 'GAMBING', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:54', NULL, NULL, NULL),
(297, '0296', 'GANIZO', 'SHIELA MAY', 'PASAGUE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(298, '0297', 'GANZAGAN', 'JENNYROSE', 'FERNANDEZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(299, '0298', 'GINEZ', 'JAYPEE', 'ALAMAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(300, '0299', 'GUILLERMO', 'JAY-R', 'GASMIN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(301, '0300', 'GUMPAL', 'PREVIE LOU', 'GALOT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(302, '0301', 'GUZMAN', 'BEA', 'TABUSO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(303, '0302', 'HERNAEZ', 'ATHENA MAE', 'MIGUEL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(304, '0303', 'HITEROZA', 'LOUIE', 'DULAY', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(305, '0304', 'IQUIN', 'JASPHER MIKE', 'CADORNA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(306, '0305', 'IQUIN', 'MARLON', 'ZALUN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(307, '0306', 'JIMENEZ', 'JASMIN', 'PAGALAMAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(308, '0307', 'JULIAN', 'RYAN', 'BULUSAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(309, '0308', 'LABUGUIN', 'MARIANICA', 'SARANGAY', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(310, '0309', 'LAGASCA', 'MICHAELLA', 'TUVERA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL);
INSERT INTO `students` (`student_id`, `passcode`, `last_name`, `first_name`, `middle_name`, `first_preference_id`, `second_preference_id`, `strand_id`, `enrollment_status`, `school_id`, `lrn`, `gwa`, `barangay_id`, `sex`, `birthday`, `email`, `contact_number`, `created_at`, `municipality_id`, `province_id`, `purok`) VALUES
(311, '0310', 'LAGUINDAY', 'JEFFREY FRANCISCO', 'FRANCISCO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(312, '0311', 'LAGUINDAY', 'MELVIE', 'MANARANG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(313, '0312', 'LAMBERT', 'CHERUBMEL VINCI', 'ABALOS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(314, '0313', 'LAPITAN', 'APRIL MAY', 'BALITNOG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(315, '0314', 'LAURIA', 'RASHEL', 'LORENZO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(316, '0315', 'LEGASPI', 'CHADDY MAE', 'CABUTAJE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(317, '0316', 'LLACUNA', 'DANELYN', 'ELEVAZO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(318, '0317', 'LOMBOY', 'GIGI', 'RIMANDO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(319, '0318', 'LOPEZ', 'BEA', 'VILLANUEVA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(320, '0319', 'LORENZO', 'REZZA', 'ORDONIO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(321, '0320', 'LUMILAN', 'GLEICEL', 'LAURIA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(322, '0321', 'MALLILLIN', 'CHRISTIAN JOE', 'DELOS REYES', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(323, '0322', 'MANUCDUC', 'ANGELICA', 'ULEP', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(324, '0323', 'MARCELO', 'IMEE', 'SERVILLA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(325, '0324', 'MARIANO', 'ROXANNE', 'REMODO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(326, '0325', 'MARTINEZ', 'OYO BOY', 'ZALUM', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(327, '0326', 'MASANAY', 'DARREN', 'TAGUINOD', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(328, '0327', 'MATA', 'JOANA', 'TAMARAY', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(329, '0328', 'MAURICIO', 'ADRIAN', 'QUIJANI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(330, '0329', 'MAYES', 'ISABEL', 'SEROMA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(331, '0330', 'MEDINA', 'JOHN WYNE', 'MADAMBA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(332, '0331', 'MENDOZA', 'MICHAEL', 'RUEZO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(333, '0332', 'MENSALVAS', 'MARK VINCE', 'PARALLAG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(334, '0333', 'MENSALVAS', 'STEFFANIE', 'PADAMA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(335, '0334', 'MERCADO', 'JOSEPHINE', 'BONGOLAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(336, '0335', 'MINA', 'ROVELYN', 'ALBINO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(337, '0336', 'MIRANDA', 'CAMILLE', 'CAMANGIAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(338, '0337', 'MOLINA', 'JOHN GLENZER', 'TALLA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(339, '0338', 'NACNAC', 'GEMMA', 'DE GUZMAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(340, '0339', 'NATIVIDAD', 'EMARIE JOY', 'SUYAT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(341, '0340', 'OSIAS', 'KHATE CLARISSE', 'GINEZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(342, '0341', 'OTRILLO', 'ARVIE', 'CASALAMITAO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(343, '0342', 'PABLO', 'JONATHAN', 'BUENO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(344, '0343', 'PAGADOR', 'PRINCESS', 'FUGABAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(345, '0344', 'PAROLA', 'JOHNZEL', 'ESPIRITU', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(346, '0345', 'PASARDAN', 'VENUS', 'CANCINO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(347, '0346', 'PASARDAN', 'VERLY', 'CANCINO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(348, '0347', 'PASCUA', 'HELEN', 'AGUSTIN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(349, '0348', 'PASCUA', 'JENA ZEA', 'VIERNES', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(350, '0349', 'PASCUA', 'RODERICK', 'PACIS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(351, '0350', 'PASCUAL', 'EDWARD', 'TAGANGIN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(352, '0351', 'PILLOS', 'EMERSON', 'CALPITO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(353, '0352', 'PINTO', 'AIZEL JHON DREI', 'LAGURA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(354, '0353', 'PLACIDO', 'PAULYN', 'VALDEZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(355, '0354', 'PORTALES', 'MARK JOSHUA', 'SALADINO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(356, '0355', 'PREZA', 'LORENCE GIAN', 'CABANDO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(357, '0356', 'PUDOL', 'ELAIZA MAE', 'CORPUZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(358, '0357', 'QUEBRAL', 'JAMES EARL', 'CABARTEJA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(359, '0358', 'QUEDUQUE', 'RUSSHEL', 'MADAYAG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(360, '0359', 'QUIMING', 'JEIA MIA MAICA', 'DELA CRUZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(361, '0360', 'RABI', 'SHAILA MAE', 'SIMON', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(362, '0361', 'RAMIREZ', 'JIGIE', 'ANDRES', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(363, '0362', 'RAMONES', 'MAE PRINCES', 'RIVERA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(364, '0363', 'RAMOS', 'BON ANDREI', 'CORONG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(365, '0364', 'RAMOS', 'CRYSTAL JANE', 'DUMALANTA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(366, '0365', 'RAMOS', 'ELLAISA MARIE', 'ABOBO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(367, '0366', 'RAMOS', 'JERYMY', 'GAMBOA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(368, '0367', 'RAMOS', 'JOHN LLYOD', 'BOLIBOL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(369, '0368', 'RAMOS', 'KATHLENE FAYE', 'BAGALAY', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(370, '0369', 'RAMOS', 'MAKK RYAN', 'CADORNA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(371, '0370', 'RAMOS', 'PRINCESS GILLYN', 'MARIANO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(372, '0371', 'RAMOS', 'ROSE ANN', 'DOMINGO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(373, '0372', 'RAMOS', 'VIMAYA JOY', 'CLEMEN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(374, '0373', 'RANCHEZ', 'TRIXIE ANN', 'RAMIL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(375, '0374', 'RAPAL', 'NELICA MARIAH', 'FERNANDEZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(376, '0375', 'REBAO', 'ROMAR', 'CORPUZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(377, '0376', 'REMIGIO', 'RISHIA VEA', 'BOLIBOL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(378, '0377', 'REPOTOLA', 'THREZA', 'ABANILLA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(379, '0378', 'RESPICIO', 'EDDIESON', 'AGAG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(380, '0379', 'REYES', 'DARYL JAMESON', 'JASOLIN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(381, '0380', 'REYNO', 'TIRSO', 'MOLAR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(382, '0381', 'RIVERA', 'GRACE', 'CARDONA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(383, '0382', 'RIVERA', 'MA VISITACION', 'ANCIANO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(384, '0383', 'ROBLES', 'PATRICK LANCE', 'AGOO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(385, '0384', 'RODRIGUEZ', 'ANGELICA', 'RAMIL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(386, '0385', 'RODRIGUEZ', 'MONIQUE', 'GAZMIN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(387, '0386', 'RONDON', 'JINKY', 'DE LEON', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(388, '0387', 'RONQUILLO', 'JOHN PAUL', 'VALLEJO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(389, '0388', 'ROSETE', 'SASY MAE', 'DELA CRUZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(390, '0389', 'ROSQUETA', 'DONALYN', 'BARTOLOME', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(391, '0390', 'RUIZ', 'ALDRIN MARK', 'BAUTISTA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(392, '0391', 'SABADO', 'CHARLIN', 'MORALES', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(393, '0392', 'SABADO', 'DANIEL', 'DUMPIT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(394, '0393', 'SABIANO', 'MARK LAURENCE', 'APOSTOL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(395, '0394', 'SABLAY', 'APRIL JOY', 'LUMAUIG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(396, '0395', 'SAGNIP', 'BERNARDO', 'OPRECIO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(397, '0396', 'SALAGUINTO', 'AVIJANE', 'MOLINA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(398, '0397', 'SALDIVAR', 'FAITH ANGELHIC', 'PAGUYO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(399, '0398', 'SALINAS', 'ANGELICA', 'DESCARGAR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(400, '0399', 'SALTAT', 'JONNAMAI', 'EUGENIO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(401, '0400', 'SANTIAGO', 'AIREZEL', 'CABANILLA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(402, '0401', 'SANTOS', 'GERSON', 'VIADO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(403, '0402', 'SAPON', 'ANGEL DAVE', 'GALANG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(404, '0403', 'SAPON', 'MARIA LUISA', 'OSCIAR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(405, '0404', 'SAQUING', 'ANGELICA', 'LEONIN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(406, '0405', 'SEGUNDO', 'RONALD', 'WITAWIT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(407, '0406', 'SERNA', 'JHON', 'ANCHETA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(408, '0407', 'SEROMA', 'CRISTINE JOY', 'CASAUAY', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(409, '0408', 'SILVA', 'ANDREI', 'CADORNA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(410, '0409', 'SILVA', 'JAYMARK DIAVE', 'TADEO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(411, '0410', 'SIMPLICIANO', 'MARY JOY', 'DELOS SANTOS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(412, '0411', 'SIMPLICIANO', 'RYAN KRISTOFF', 'BICARME', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(413, '0412', 'SIMSIM', 'AIJAY', 'MALLA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(414, '0413', 'SINAD', 'LEIAN', 'CORPUZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(415, '0414', 'SOBREVILLA', 'PAULEEN', 'CABRAL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(416, '0415', 'SOLITO', 'CARLO JAY', 'BUNCAG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(417, '0416', 'SORIANO', 'DIANA MAE', 'BINGAYAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(418, '0417', 'SOTELO', 'JURISH SAB', 'MERCADO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(419, '0418', 'SUMARIA', 'KHYLA', 'MIGUEL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(420, '0419', 'SUPNET', 'ALJON', 'DOMINGO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(421, '0420', 'TABAGO', 'MARVIN', 'RAMOS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(422, '0421', 'TABERNA', 'ASHLEY', 'BALTAZAR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(423, '0422', 'TABILIN', 'AILENE', 'COQUIAL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(424, '0423', 'TABLIAGO', 'ASHLIE CLARE', 'SEBASTIAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(425, '0424', 'TABLIAGO', 'GEORGIA', 'BALAGAT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(426, '0425', 'TABLIAGO', 'RIZALYN', 'MARTINEZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(427, '0426', 'TABORA', 'FLORIZA', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(428, '0427', 'TACADENA', 'MARC ALWYNE', 'CALIGIURAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(429, '0428', 'TADEO', 'GLAIZA', 'DALIT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(430, '0429', 'TADEO', 'MAY ANN', 'OLOGENIO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(431, '0430', 'TAGANI', 'JOVELYN', 'DE VERA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(432, '0431', 'TALAO', 'JANE', 'ASUNCION', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(433, '0432', 'TALAYONG', 'R-JAY', 'URBANO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(434, '0433', 'TALLEDO', 'JAMES', 'DUMO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(435, '0434', 'TALOSIG', 'JASMINE', 'BAUA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(436, '0435', 'TALUGAN', 'REYSHALYN MAE', 'VERGARA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(437, '0436', 'TAMAYO', 'KRISTINE', 'SALVADOR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(438, '0437', 'TAPAWAN', 'RICHELLE', 'DELA CRUZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(439, '0438', 'TAPEC', 'WALLY', 'MATIAS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(440, '0439', 'TATSON', 'LOVELY JANE', 'ADRIANO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(441, '0440', 'TELAN', 'JOHN REY', 'CARDINEZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(442, '0441', 'TERRANO', 'JENCEL', 'REYES', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(443, '0442', 'TOBIAS', 'JESSAMIE', 'ABANILLA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(444, '0443', 'TOLENTINO', 'JENALYN', 'MACADANGDANG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(445, '0444', 'TOMAS', 'MARK ASSET', 'BAUTISTA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(446, '0445', 'TOMINES', 'PRECIOUS KING', 'AIDO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(447, '0446', 'TOMINES', 'PRINCESS MAE', 'MOLINA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(448, '0447', 'TORRES', 'JOHN REY', 'MAGNO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(449, '0448', 'TUMALIUAN', 'VINCENT CLINE', 'ADLUZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(450, '0449', 'TUMAMAO', 'JORNAL', 'LANDICHO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(451, '0450', 'TUNGCUL', 'JOYLYN', 'LISING', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(452, '0451', 'TUQUIERO', 'MARK DAVID', 'SALAS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(453, '0452', 'UCOL', 'JAYSON', 'HERNANI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(454, '0453', 'ULSAN', 'JARENZ-EJAY', 'BALLESTEROS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:55', NULL, NULL, NULL),
(455, '0454', 'URBANO', 'SHERY ANN', 'GASMIN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:56', NULL, NULL, NULL),
(456, '0455', 'UMATAN', 'A-JAY', 'ASUERO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:56', NULL, NULL, NULL),
(457, '0456', 'UTANES', 'SHANE NICOLE', 'CASTRO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:56', NULL, NULL, NULL),
(458, '0457', 'UY', 'JHON RALF', 'CALLE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:56', NULL, NULL, NULL),
(459, '0458', 'VALDEZ', 'DIANA MAE', 'DULDULAO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:56', NULL, NULL, NULL),
(460, '0459', 'VALDEZ', 'FLORDELUNA', 'YADAO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:56', NULL, NULL, NULL),
(461, '0460', 'VALDEZ', 'JAMAICA', 'SALINAS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:56', NULL, NULL, NULL),
(462, '0461', 'VALDEZ', 'JASPHER', 'GALUPO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:56', NULL, NULL, NULL),
(463, '0462', 'VALDEZ', 'JAYPOY', 'GUERRERO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:56', NULL, NULL, NULL),
(464, '0463', 'VALDEZ', 'JOHN DENVER', 'MANUEL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:56', NULL, NULL, NULL),
(465, '0464', 'VALDEZ', 'MARK DAVID', 'ANDAYA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:56', NULL, NULL, NULL),
(466, '0465', 'VALDEZ', 'RANDOLF', 'MANUEL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:56', NULL, NULL, NULL),
(467, '0466', 'VALDEZ', 'ROSELYN', 'PAGGAO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:56', NULL, NULL, NULL),
(468, '0467', 'VALDEZ', 'SHALYNIA', 'TORRES', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:56', NULL, NULL, NULL),
(469, '0468', 'VALLEJO', 'MARIA FLOR', 'CAMALIG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:56', NULL, NULL, NULL),
(470, '0469', 'VELASCO', 'PRINCESS LEA', 'FAJARDO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:56', NULL, NULL, NULL),
(471, '0470', 'VENTURA', 'JOHN RICK', 'LIMON', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:56', NULL, NULL, NULL),
(472, '0471', 'VENTURA', 'KLEISER JOHN', 'CORPUZ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:56', NULL, NULL, NULL),
(473, '0472', 'VERONA', 'RALPH GABRIEL', 'PAGDANGANAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:56', NULL, NULL, NULL),
(474, '0473', 'VICENTE', 'ANGEL', 'BAYUBAY', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:56', NULL, NULL, NULL),
(475, '0474', 'VIERNES', 'MYLENE', 'BALAURO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:56', NULL, NULL, NULL),
(476, '0475', 'VILLANEA', 'JESSICA', 'SALDIVAR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:56', NULL, NULL, NULL),
(477, '0476', 'VILLANUEVA', 'DANIELA JANE', 'BUYAIN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:56', NULL, NULL, NULL),
(478, '0477', 'VILLANUEVA', 'RHIAN', 'TUPPIL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:56', NULL, NULL, NULL),
(479, '0478', 'VILLARIN', 'TYRONE', 'SUMAWANG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:56', NULL, NULL, NULL),
(480, '0479', 'VILORIA', 'DIANA', 'RUMBAOA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:56', NULL, NULL, NULL),
(481, '0480', 'VINARAO', 'VINCENT', 'TIPLAUS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02 09:15:56', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student_answers`
--

CREATE TABLE `student_answers` (
  `answer_id` int(11) NOT NULL,
  `attempt_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `selected_choice` varchar(255) DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT NULL,
  `answered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_answers`
--

INSERT INTO `student_answers` (`answer_id`, `attempt_id`, `question_id`, `selected_choice`, `is_correct`, `answered_at`) VALUES
(1, 1, 116, '1', 1, '2025-04-03 21:18:58');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'English', '2025-04-03 14:40:33', '2025-04-03 14:40:33'),
(2, 'Science', '2025-04-03 14:40:33', '2025-04-03 14:40:33'),
(3, 'Math', '2025-04-03 14:40:33', '2025-04-03 14:40:33'),
(4, 'Social Science', '2025-04-03 14:40:33', '2025-04-03 14:40:33'),
(5, 'Filipino', '2025-04-03 14:40:33', '2025-04-03 14:40:33'),
(8, 'ABSTRACT REASONING', '2025-04-03 14:40:33', '2025-04-03 14:40:33');

-- --------------------------------------------------------

--
-- Table structure for table `test_attempts`
--

CREATE TABLE `test_attempts` (
  `attempt_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `status` enum('Not Started','In Progress','Completed','Expired','Aborted') NOT NULL DEFAULT 'Not Started',
  `total_score` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test_attempts`
--

INSERT INTO `test_attempts` (`attempt_id`, `student_id`, `start_time`, `end_time`, `status`, `total_score`, `created_at`) VALUES
(1, 1, '2025-04-03 21:18:34', '2025-04-03 21:19:00', 'Completed', 1, '2025-04-03 21:18:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `uq_admin_username` (`username`),
  ADD UNIQUE KEY `uq_admin_email` (`email`),
  ADD KEY `idx_user_role` (`role_id`);

--
-- Indexes for table `attempt_scores_by_subject`
--
ALTER TABLE `attempt_scores_by_subject`
  ADD PRIMARY KEY (`score_id`),
  ADD UNIQUE KEY `uq_attempt_subject_score` (`attempt_id`,`subject_id`),
  ADD KEY `idx_score_attempt` (`attempt_id`),
  ADD KEY `idx_score_subject` (`subject_id`);

--
-- Indexes for table `barangays`
--
ALTER TABLE `barangays`
  ADD PRIMARY KEY (`barangay_id`),
  ADD KEY `municipality_id` (`municipality_id`);

--
-- Indexes for table `campuses`
--
ALTER TABLE `campuses`
  ADD PRIMARY KEY (`campus_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`),
  ADD KEY `campus_id` (`campus_id`);

--
-- Indexes for table `municipalities`
--
ALTER TABLE `municipalities`
  ADD PRIMARY KEY (`municipality_id`),
  ADD KEY `province_id` (`province_id`);

--
-- Indexes for table `provinces`
--
ALTER TABLE `provinces`
  ADD PRIMARY KEY (`province_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_subject_id` (`subject_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `uq_role_name` (`role_name`);

--
-- Indexes for table `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `strands`
--
ALTER TABLE `strands`
  ADD PRIMARY KEY (`strand_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `passcode` (`passcode`),
  ADD KEY `first_preference_id` (`first_preference_id`),
  ADD KEY `second_preference_id` (`second_preference_id`),
  ADD KEY `strand_id` (`strand_id`),
  ADD KEY `school_id` (`school_id`),
  ADD KEY `barangay_id` (`barangay_id`);

--
-- Indexes for table `student_answers`
--
ALTER TABLE `student_answers`
  ADD PRIMARY KEY (`answer_id`),
  ADD UNIQUE KEY `uq_attempt_question` (`attempt_id`,`question_id`),
  ADD KEY `idx_answer_attempt` (`attempt_id`),
  ADD KEY `idx_answer_question` (`question_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `test_attempts`
--
ALTER TABLE `test_attempts`
  ADD PRIMARY KEY (`attempt_id`),
  ADD KEY `idx_student_attempt` (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `attempt_scores_by_subject`
--
ALTER TABLE `attempt_scores_by_subject`
  MODIFY `score_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `barangays`
--
ALTER TABLE `barangays`
  MODIFY `barangay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1056;

--
-- AUTO_INCREMENT for table `campuses`
--
ALTER TABLE `campuses`
  MODIFY `campus_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT for table `municipalities`
--
ALTER TABLE `municipalities`
  MODIFY `municipality_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `provinces`
--
ALTER TABLE `provinces`
  MODIFY `province_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `schools`
--
ALTER TABLE `schools`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT for table `strands`
--
ALTER TABLE `strands`
  MODIFY `strand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=482;

--
-- AUTO_INCREMENT for table `student_answers`
--
ALTER TABLE `student_answers`
  MODIFY `answer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `test_attempts`
--
ALTER TABLE `test_attempts`
  MODIFY `attempt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD CONSTRAINT `fk_user_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON UPDATE CASCADE;

--
-- Constraints for table `attempt_scores_by_subject`
--
ALTER TABLE `attempt_scores_by_subject`
  ADD CONSTRAINT `fk_score_attempt` FOREIGN KEY (`attempt_id`) REFERENCES `test_attempts` (`attempt_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_score_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `barangays`
--
ALTER TABLE `barangays`
  ADD CONSTRAINT `barangays_ibfk_1` FOREIGN KEY (`municipality_id`) REFERENCES `municipalities` (`municipality_id`);

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`campus_id`);

--
-- Constraints for table `municipalities`
--
ALTER TABLE `municipalities`
  ADD CONSTRAINT `municipalities_ibfk_1` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`province_id`);

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `fk_questions_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`first_preference_id`) REFERENCES `courses` (`course_id`),
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`second_preference_id`) REFERENCES `courses` (`course_id`),
  ADD CONSTRAINT `students_ibfk_3` FOREIGN KEY (`strand_id`) REFERENCES `strands` (`strand_id`),
  ADD CONSTRAINT `students_ibfk_4` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`),
  ADD CONSTRAINT `students_ibfk_5` FOREIGN KEY (`barangay_id`) REFERENCES `barangays` (`barangay_id`);

--
-- Constraints for table `student_answers`
--
ALTER TABLE `student_answers`
  ADD CONSTRAINT `fk_answer_attempt` FOREIGN KEY (`attempt_id`) REFERENCES `test_attempts` (`attempt_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_answer_question` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `test_attempts`
--
ALTER TABLE `test_attempts`
  ADD CONSTRAINT `fk_attempt_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

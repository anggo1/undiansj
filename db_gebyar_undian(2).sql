-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 24, 2026 at 11:03 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_gebyar_undian`
--

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(10) UNSIGNED NOT NULL,
  `nik` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `department` varchar(50) DEFAULT NULL,
  `is_won` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `nik`, `name`, `department`, `is_won`) VALUES
(674, '8510035', 'HABIBI', 'EKSPEDISI', 0),
(675, '8510035', 'HABIBI', 'EKSPEDISI', 0),
(676, '8510035', 'HABIBI', 'EKSPEDISI', 0),
(677, '8510035', 'HABIBI', 'EKSPEDISI', 0),
(678, '8510035', 'HABIBI', 'EKSPEDISI', 0),
(679, '8510035', 'HABIBI', 'EKSPEDISI', 0),
(680, '8510035', 'HABIBI', 'EKSPEDISI', 0),
(681, '8510035', 'HABIBI', 'EKSPEDISI', 0),
(682, '8510035', 'HABIBI', 'EKSPEDISI', 0),
(683, '8510035', 'HABIBI', 'EKSPEDISI', 0),
(684, '8510035', 'HABIBI', 'EKSPEDISI', 0),
(685, '8510035', 'HABIBI', 'EKSPEDISI', 0),
(686, '8510035', 'HABIBI', 'EKSPEDISI', 0),
(687, '8510035', 'HABIBI', 'EKSPEDISI', 0),
(688, '8510035', 'HABIBI', 'EKSPEDISI', 0),
(689, '8510035', 'HABIBI', 'EKSPEDISI', 0),
(690, '8510035', 'HABIBI', 'EKSPEDISI', 0),
(691, '8510035', 'HABIBI', 'EKSPEDISI', 0),
(692, '8510035', 'HABIBI', 'EKSPEDISI', 0),
(693, '8004022', 'Jef Rendi', 'HUMASKUM', 1),
(694, '8004022', 'Jef Rendi', 'HUMASKUM', 1),
(695, '8004022', 'Jef Rendi', 'HUMASKUM', 1),
(696, '8004022', 'Jef Rendi', 'HUMASKUM', 1),
(697, '8004022', 'Jef Rendi', 'HUMASKUM', 1),
(698, '8004022', 'Jef Rendi', 'HUMASKUM', 1),
(699, '8004022', 'Jef Rendi', 'HUMASKUM', 1),
(700, '8004022', 'Jef Rendi', 'HUMASKUM', 1),
(701, '8004022', 'Jef Rendi', 'HUMASKUM', 1),
(702, '8004022', 'Jef Rendi', 'HUMASKUM', 1),
(703, '8004022', 'Jef Rendi', 'HUMASKUM', 1),
(704, '8004022', 'Jef Rendi', 'HUMASKUM', 1),
(705, '8004022', 'Jef Rendi', 'HUMASKUM', 1),
(706, '8004022', 'Jef Rendi', 'HUMASKUM', 1),
(707, '8004022', 'Jef Rendi', 'HUMASKUM', 1),
(708, '8004022', 'Jef Rendi', 'HUMASKUM', 1),
(709, '8004022', 'Jef Rendi', 'HUMASKUM', 1),
(710, '8004022', 'Jef Rendi', 'HUMASKUM', 1),
(711, '9311035', 'RIDWAN MINTARJA', 'LOGISTIK', 0),
(712, '9311036', 'RIDWAN MINTARJA', 'LOGISTIK', 0),
(713, '9311037', 'RIDWAN MINTARJA', 'LOGISTIK', 0),
(714, '9311038', 'RIDWAN MINTARJA', 'LOGISTIK', 1),
(715, '9311039', 'RIDWAN MINTARJA', 'LOGISTIK', 0),
(716, '9311040', 'RIDWAN MINTARJA', 'LOGISTIK', 0),
(717, '9311041', 'RIDWAN MINTARJA', 'LOGISTIK', 0),
(718, '9311042', 'RIDWAN MINTARJA', 'LOGISTIK', 0),
(719, '9311043', 'RIDWAN MINTARJA', 'LOGISTIK', 0),
(720, '9311044', 'RIDWAN MINTARJA', 'LOGISTIK', 0),
(721, '9311045', 'RIDWAN MINTARJA', 'LOGISTIK', 0),
(722, '9311046', 'RIDWAN MINTARJA', 'LOGISTIK', 0),
(723, '090496006', 'NAYA PRILY ANDARESTA', 'MARKETING-MITRA', 0),
(724, '090496007', 'NAYA PRILY ANDARESTA', 'MARKETING-MITRA', 0),
(725, '090496008', 'NAYA PRILY ANDARESTA', 'MARKETING-MITRA', 0),
(726, '090496009', 'NAYA PRILY ANDARESTA', 'MARKETING-MITRA', 0),
(727, '8209036', 'ANGGORO', 'PERSONALIA', 0),
(728, '8209036', 'ANGGORO', 'PERSONALIA', 0),
(729, '8209036', 'ANGGORO', 'PERSONALIA', 0),
(730, '8209036', 'ANGGORO', 'PERSONALIA', 0),
(731, '8209036', 'ANGGORO', 'PERSONALIA', 0),
(732, '8209036', 'ANGGORO', 'PERSONALIA', 0),
(733, '8209036', 'ANGGORO', 'PERSONALIA', 0),
(734, '8209036', 'ANGGORO', 'PERSONALIA', 0),
(735, '8209036', 'ANGGORO', 'PERSONALIA', 0),
(736, '8209036', 'ANGGORO', 'PERSONALIA', 0),
(737, '8209036', 'ANGGORO', 'PERSONALIA', 0),
(738, '8209036', 'ANGGORO', 'PERSONALIA', 0),
(739, '8209036', 'ANGGORO', 'PERSONALIA', 0),
(740, '8209036', 'ANGGORO', 'PERSONALIA', 0),
(741, '8209036', 'ANGGORO', 'PERSONALIA', 0),
(742, '8209036', 'ANGGORO', 'PERSONALIA', 0),
(743, '8209036', 'ANGGORO', 'PERSONALIA', 0),
(744, '8209036', 'ANGGORO', 'PERSONALIA', 0),
(745, '8209036', 'ANGGORO', 'PERSONALIA', 0),
(746, '8209036', 'ANGGORO', 'PERSONALIA', 0),
(747, '8209036', 'ANGGORO', 'PERSONALIA', 0),
(748, '8209036', 'ANGGORO', 'PERSONALIA', 0);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(10) UNSIGNED NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `color` varchar(7) NOT NULL,
  `stock` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `item_name`, `color`, `stock`) VALUES
(1, 'Sepeda Motor', '#9b59b6', 0),
(3, 'Smartphone', '#e74c3c', 1),
(4, 'Kulkas', '#f1c40f', 3),
(5, 'Mesin Cuci', '#34495e', 3),
(6, 'Voucher Belanja', '#1abc9c', 5),
(7, 'test', '#8b5cf6', 0);

-- --------------------------------------------------------

--
-- Table structure for table `surveys`
--

CREATE TABLE `surveys` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `surveys`
--

INSERT INTO `surveys` (`id`, `title`, `description`, `is_active`) VALUES
(1, 'Survey Refreshing Sinar Jaya Group', 'Masukan Anda membantu kami meningkatkan kualitas layanan.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `survey_answers`
--

CREATE TABLE `survey_answers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `response_id` bigint(20) UNSIGNED NOT NULL,
  `question_id` int(10) UNSIGNED NOT NULL,
  `answer_text` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `survey_answers`
--

INSERT INTO `survey_answers` (`id`, `response_id`, `question_id`, `answer_text`) VALUES
(36, 8, 14, '12345'),
(37, 8, 15, 'test'),
(38, 8, 16, 'test'),
(39, 8, 17, 'Gelombang 1'),
(40, 9, 14, '12345'),
(41, 9, 15, 'test'),
(42, 9, 16, 'test'),
(43, 9, 17, 'Gelombang 1'),
(44, 10, 14, '12344'),
(45, 10, 15, '12345'),
(46, 10, 16, 'test'),
(47, 10, 17, 'Gelombang 2'),
(48, 11, 14, '123456'),
(49, 11, 15, 'test'),
(50, 11, 16, 'test'),
(51, 11, 17, 'Gelombang 1');

-- --------------------------------------------------------

--
-- Table structure for table `survey_questions`
--

CREATE TABLE `survey_questions` (
  `id` int(10) UNSIGNED NOT NULL,
  `survey_id` int(10) UNSIGNED NOT NULL,
  `question_text` text NOT NULL,
  `question_type` enum('text','textarea','radio','checkbox','select') NOT NULL DEFAULT 'text',
  `options` text DEFAULT NULL COMMENT 'Satu opsi per baris',
  `is_required` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `survey_questions`
--

INSERT INTO `survey_questions` (`id`, `survey_id`, `question_text`, `question_type`, `options`, `is_required`, `sort_order`) VALUES
(14, 1, 'NIK ( Nomor Induk Karyawan )', 'text', '', 1, 1),
(15, 1, 'Nama', 'text', '', 1, 2),
(16, 1, 'Bagian', 'text', '', 1, 3),
(17, 1, 'Gelombang', 'radio', 'Gelombang 1\r\nGelombang 2', 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `survey_responses`
--

CREATE TABLE `survey_responses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `survey_id` int(10) UNSIGNED NOT NULL,
  `submitted_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `survey_responses`
--

INSERT INTO `survey_responses` (`id`, `survey_id`, `submitted_at`) VALUES
(1, 1, '2026-09-23 07:59:43'),
(2, 1, '2026-09-23 08:25:59'),
(3, 1, '2026-09-24 07:03:14'),
(4, 1, '2026-09-24 07:04:01'),
(5, 1, '2026-09-24 07:18:22'),
(6, 1, '2026-09-24 07:18:36'),
(7, 1, '2026-09-24 07:19:00'),
(8, 1, '2026-09-24 07:25:00'),
(9, 1, '2026-09-24 07:25:14'),
(10, 1, '2026-09-24 07:41:30'),
(11, 1, '2026-09-24 10:59:31');

-- --------------------------------------------------------

--
-- Table structure for table `tb_anggota`
--

CREATE TABLE `tb_anggota` (
  `id` int(11) NOT NULL,
  `nomer` varchar(222) NOT NULL,
  `nama` varchar(222) NOT NULL,
  `kota` varchar(222) NOT NULL,
  `kosong` varchar(111) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_pengguna`
--

CREATE TABLE `tb_pengguna` (
  `id` int(11) NOT NULL,
  `username` varchar(222) NOT NULL,
  `password` varchar(222) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_pengguna`
--

INSERT INTO `tb_pengguna` (`id`, `username`, `password`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3');

-- --------------------------------------------------------

--
-- Table structure for table `winners`
--

CREATE TABLE `winners` (
  `id` int(10) UNSIGNED NOT NULL,
  `employee_id` int(10) UNSIGNED NOT NULL,
  `nik` varchar(64) NOT NULL,
  `item_id` int(10) UNSIGNED NOT NULL,
  `won_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `winners`
--

INSERT INTO `winners` (`id`, `employee_id`, `nik`, `item_id`, `won_at`) VALUES
(63, 703, '8004022', 3, '2026-09-23 08:42:15'),
(64, 714, '9311038', 1, '2026-09-24 08:59:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `surveys`
--
ALTER TABLE `surveys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `survey_answers`
--
ALTER TABLE `survey_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `response_id` (`response_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `survey_questions`
--
ALTER TABLE `survey_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `survey_id` (`survey_id`);

--
-- Indexes for table `survey_responses`
--
ALTER TABLE `survey_responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `survey_id` (`survey_id`);

--
-- Indexes for table `tb_anggota`
--
ALTER TABLE `tb_anggota`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_pengguna`
--
ALTER TABLE `tb_pengguna`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `winners`
--
ALTER TABLE `winners`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_winners_nik` (`nik`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `item_id` (`item_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=749;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `surveys`
--
ALTER TABLE `surveys`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `survey_answers`
--
ALTER TABLE `survey_answers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `survey_questions`
--
ALTER TABLE `survey_questions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `survey_responses`
--
ALTER TABLE `survey_responses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tb_anggota`
--
ALTER TABLE `tb_anggota`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1642;

--
-- AUTO_INCREMENT for table `tb_pengguna`
--
ALTER TABLE `tb_pengguna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `winners`
--
ALTER TABLE `winners`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `survey_answers`
--
ALTER TABLE `survey_answers`
  ADD CONSTRAINT `fk_answers_question` FOREIGN KEY (`question_id`) REFERENCES `survey_questions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_answers_response` FOREIGN KEY (`response_id`) REFERENCES `survey_responses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `survey_questions`
--
ALTER TABLE `survey_questions`
  ADD CONSTRAINT `fk_questions_survey` FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `survey_responses`
--
ALTER TABLE `survey_responses`
  ADD CONSTRAINT `fk_responses_survey` FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `winners`
--
ALTER TABLE `winners`
  ADD CONSTRAINT `winners_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `winners_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

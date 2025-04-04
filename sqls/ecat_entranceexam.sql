ecat db table:

CREATE TABLE `attempt_scores_by_subject` (
  `score_id` int(11) NOT NULL,
  `attempt_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `items_attempted` int(11) NOT NULL,
  `items_correct` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `test_attempts` (
  `attempt_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `status` enum('Not Started','In Progress','Completed','Expired','Aborted') NOT NULL DEFAULT 'Not Started',
  `total_score` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `original_session_id` int(11) DEFAULT NULL COMMENT 'The session_id from the source lab DB',
  `source_lab_id` varchar(50) DEFAULT NULL COMMENT 'Identifier for the source lab (e.g., LAB1, SERVER_A)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test_attempts`
--

INSERT INTO `test_attempts` (`attempt_id`, `student_id`, `start_time`, `end_time`, `status`, `total_score`, `created_at`, `original_session_id`, `source_lab_id`) VALUES
(1, 26, '2025-04-03 09:50:57', '2025-04-03 10:44:31', 'Completed', 52, '2025-04-04 00:50:57', 1, 'CLA'),
(2, 8, '2025-04-03 09:51:02', '2025-04-03 11:02:17', 'Completed', 50, '2025-04-04 00:51:02', 2, 'CLA'),
(3, 25, '2025-04-03 09:51:02', '2025-04-03 11:03:40', 'Completed', 58, '2025-04-04 00:51:02', 3, 'CLA'),
(4, 36, '2025-04-03 09:51:02', '2025-04-03 10:41:03', 'Completed', 39, '2025-04-04 00:51:02', 4, 'CLA'),
(5, 16, '2025-04-03 09:51:03', '2025-04-03 10:43:00', 'Completed', 51, '2025-04-04 00:51:03', 5, 'CLA'),
(6, 37, '2025-04-03 09:51:03', '2025-04-03 11:02:04', 'Completed', 37, '2025-04-04 00:51:03', 6, 'CLA'),
(7, 10, '2025-04-03 09:51:03', '2025-04-03 10:59:00', 'Completed', 32, '2025-04-04 00:51:03', 7, 'CLA'),
(8, 31, '2025-04-03 09:51:03', '2025-04-03 10:54:37', 'Completed', 53, '2025-04-04 00:51:03', 8, 'CLA'),
(9, 20, '2025-04-03 09:51:04', '2025-04-03 11:20:46', 'Completed', 31, '2025-04-04 00:51:04', 9, 'CLA'),

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

entranceexam db tables:
CREATE TABLE `quizquestions` (
  `id` int(11) NOT NULL,
  `grade` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `difficulty` enum('Easy','Medium','Hard') DEFAULT NULL,
  `question` text DEFAULT NULL,
  `choice1` varchar(255) DEFAULT NULL,
  `choice2` varchar(255) DEFAULT NULL,
  `choice3` varchar(255) DEFAULT NULL,
  `choice4` varchar(255) DEFAULT NULL,
  `answer` varchar(255) DEFAULT NULL,
  `explanation` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quizquestions`
--

INSERT INTO `quizquestions` (`id`, `grade`, `subject`, `difficulty`, `question`, `choice1`, `choice2`, `choice3`, `choice4`, `answer`, `explanation`, `image_url`, `created_at`, `updated_at`) VALUES
(1, NULL, 'English', NULL, 'The saccharine lady attracts many tourists in their hometown.', 'leave', 'sweet', 'arid', 'quit', 'sweet', NULL, 'assets/img/660efe0b0d8e6.png', '2024-03-21 19:46:40', '2024-04-04 19:22:51'),
(2, NULL, 'English', NULL, 'Gray’s children buy many gifts for present. They are pensive to their teacher and classmates.', 'oppressed', 'caged', 'thoughtful', 'happy', 'thoughtful', NULL, 'assets/img/660efe26ad438.png', '2024-03-21 19:46:40', '2024-04-04 19:23:18'),
(3, NULL, 'English', NULL, 'Our dogs went hiding because of stentorian fireworks on New Year’s Eve.', 'violent', 'misbegotten', 'loud', 'stealthy', 'loud', NULL, 'assets/img/660efe442eff4.png', '2024-03-21 19:46:40', '2024-04-04 19:23:48'),
(4, NULL, 'English', NULL, 'We are hoping that these herbal medicines will abate her excruciating body pain.', 'free', 'augment', 'provoke', 'wane', 'augment', NULL, 'assets/img/660efec3b78b4.png', '2024-03-21 19:46:40', '2024-04-04 22:38:47'),
(5, NULL, 'English', NULL, 'The case was dismissed because of dearth evidence presented to the court.', 'lack', 'poverty', 'abundance', 'foreign', 'lack', NULL, 'assets/img/660eff006fefb.png', '2024-03-21 19:46:40', '2024-04-04 19:26:56'),
(6, NULL, 'English', NULL, 'The teacher has introduced a motivational story which is germane to the topic.', 'irrelevant', 'indifferent', 'impartial', 'improvident', 'improvident', NULL, 'assets/img/660eff1ebf848.png', '2024-03-21 19:46:40', '2024-04-04 19:27:26'),
(7, NULL, 'English', NULL, 'I love to buy and read abridge dictionary because it has its complete details.', 'shorten', 'extend', 'stress', 'easy', 'extend', NULL, 'assets/img/660eff6d7cdf6.png', '2024-03-21 19:46:40', '2024-04-04 22:41:48'),


CREATE TABLE `player_sessions` (
  `session_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `score` int(11) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `duration` int(11) GENERATED ALWAYS AS (timestampdiff(SECOND,`start_time`,`end_time`)) VIRTUAL,
  `correct_answers` int(11) DEFAULT NULL,
  `total_questions` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `player_sessions`
--

INSERT INTO `player_sessions` (`session_id`, `user_id`, `score`, `start_time`, `end_time`, `correct_answers`, `total_questions`, `created_at`, `updated_at`) VALUES
(1, 26, 52, '2025-04-03 17:50:57', '2025-04-03 18:44:31', 52, 120, '2025-04-04 00:50:57', '2025-04-04 01:44:31'),
(2, 8, 50, '2025-04-03 17:51:02', '2025-04-03 19:02:17', 50, 120, '2025-04-04 00:51:02', '2025-04-04 02:02:17'),
(3, 25, 58, '2025-04-03 17:51:02', '2025-04-03 19:03:40', 58, 120, '2025-04-04 00:51:02', '2025-04-04 02:03:40'),
(4, 36, 39, '2025-04-03 17:51:02', '2025-04-03 18:41:03', 39, 120, '2025-04-04 00:51:02', '2025-04-04 01:41:03'),
(5, 16, 51, '2025-04-03 17:51:03', '2025-04-03 18:43:00', 51, 120, '2025-04-04 00:51:03', '2025-04-04 01:43:00'),
(6, 37, 37, '2025-04-03 17:51:03', '2025-04-03 19:02:04', 37, 120, '2025-04-04 00:51:03', '2025-04-04 02:02:04'),
(7, 10, 32, '2025-04-03 17:51:03', '2025-04-03 18:59:00', 32, 120, '2025-04-04 00:51:03', '2025-04-04 01:59:00'),
(8, 31, 53, '2025-04-03 17:51:03', '2025-04-03 18:54:37', 53, 120, '2025-04-04 00:51:03', '2025-04-04 01:54:37'),
(9, 20, 31, '2025-04-03 17:51:04', '2025-04-03 19:20:46', 31, 120, '2025-04-04 00:51:04', '2025-04-04 02:20:46'),
(10, 4, 35, '2025-04-03 17:51:04', '2025-04-03 18:49:50', 35, 120, '2025-04-04 00:51:04', '2025-04-04 01:49:50'),
(11, 30, 50, '2025-04-03 17:51:05', '2025-04-03 18:53:07', 50, 120, '2025-04-04 00:51:05', '2025-04-04 01:53:07'),

CREATE TABLE `answer_attempts` (
  `attempt_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `selected_answer` varchar(255) NOT NULL,
  `is_correct` tinyint(1) NOT NULL,
  `attempt_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `answer_attempts`
--

INSERT INTO `answer_attempts` (`attempt_id`, `session_id`, `question_id`, `selected_answer`, `is_correct`, `attempt_time`) VALUES
(0, 16, 89, '6 years', 1, '2025-04-04 00:51:21'),
(0, 15, 77, 'Singing Lupang Hinirang', 1, '2025-04-04 00:51:28'),ecat db table:

CREATE TABLE `attempt_scores_by_subject` (
  `score_id` int(11) NOT NULL,
  `attempt_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `items_attempted` int(11) NOT NULL,
  `items_correct` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `test_attempts` (
  `attempt_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `status` enum('Not Started','In Progress','Completed','Expired','Aborted') NOT NULL DEFAULT 'Not Started',
  `total_score` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `original_session_id` int(11) DEFAULT NULL COMMENT 'The session_id from the source lab DB',
  `source_lab_id` varchar(50) DEFAULT NULL COMMENT 'Identifier for the source lab (e.g., LAB1, SERVER_A)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test_attempts`
--

INSERT INTO `test_attempts` (`attempt_id`, `student_id`, `start_time`, `end_time`, `status`, `total_score`, `created_at`, `original_session_id`, `source_lab_id`) VALUES
(1, 26, '2025-04-03 09:50:57', '2025-04-03 10:44:31', 'Completed', 52, '2025-04-04 00:50:57', 1, 'CLA'),
(2, 8, '2025-04-03 09:51:02', '2025-04-03 11:02:17', 'Completed', 50, '2025-04-04 00:51:02', 2, 'CLA'),
(3, 25, '2025-04-03 09:51:02', '2025-04-03 11:03:40', 'Completed', 58, '2025-04-04 00:51:02', 3, 'CLA'),
(4, 36, '2025-04-03 09:51:02', '2025-04-03 10:41:03', 'Completed', 39, '2025-04-04 00:51:02', 4, 'CLA'),
(5, 16, '2025-04-03 09:51:03', '2025-04-03 10:43:00', 'Completed', 51, '2025-04-04 00:51:03', 5, 'CLA'),
(6, 37, '2025-04-03 09:51:03', '2025-04-03 11:02:04', 'Completed', 37, '2025-04-04 00:51:03', 6, 'CLA'),
(7, 10, '2025-04-03 09:51:03', '2025-04-03 10:59:00', 'Completed', 32, '2025-04-04 00:51:03', 7, 'CLA'),
(8, 31, '2025-04-03 09:51:03', '2025-04-03 10:54:37', 'Completed', 53, '2025-04-04 00:51:03', 8, 'CLA'),
(9, 20, '2025-04-03 09:51:04', '2025-04-03 11:20:46', 'Completed', 31, '2025-04-04 00:51:04', 9, 'CLA'),

entranceexam db tables:
CREATE TABLE `quizquestions` (
  `id` int(11) NOT NULL,
  `grade` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `difficulty` enum('Easy','Medium','Hard') DEFAULT NULL,
  `question` text DEFAULT NULL,
  `choice1` varchar(255) DEFAULT NULL,
  `choice2` varchar(255) DEFAULT NULL,
  `choice3` varchar(255) DEFAULT NULL,
  `choice4` varchar(255) DEFAULT NULL,
  `answer` varchar(255) DEFAULT NULL,
  `explanation` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quizquestions`
--

INSERT INTO `quizquestions` (`id`, `grade`, `subject`, `difficulty`, `question`, `choice1`, `choice2`, `choice3`, `choice4`, `answer`, `explanation`, `image_url`, `created_at`, `updated_at`) VALUES
(1, NULL, 'English', NULL, 'The saccharine lady attracts many tourists in their hometown.', 'leave', 'sweet', 'arid', 'quit', 'sweet', NULL, 'assets/img/660efe0b0d8e6.png', '2024-03-21 19:46:40', '2024-04-04 19:22:51'),
(2, NULL, 'English', NULL, 'Gray’s children buy many gifts for present. They are pensive to their teacher and classmates.', 'oppressed', 'caged', 'thoughtful', 'happy', 'thoughtful', NULL, 'assets/img/660efe26ad438.png', '2024-03-21 19:46:40', '2024-04-04 19:23:18'),
(3, NULL, 'English', NULL, 'Our dogs went hiding because of stentorian fireworks on New Year’s Eve.', 'violent', 'misbegotten', 'loud', 'stealthy', 'loud', NULL, 'assets/img/660efe442eff4.png', '2024-03-21 19:46:40', '2024-04-04 19:23:48'),
(4, NULL, 'English', NULL, 'We are hoping that these herbal medicines will abate her excruciating body pain.', 'free', 'augment', 'provoke', 'wane', 'augment', NULL, 'assets/img/660efec3b78b4.png', '2024-03-21 19:46:40', '2024-04-04 22:38:47'),
(5, NULL, 'English', NULL, 'The case was dismissed because of dearth evidence presented to the court.', 'lack', 'poverty', 'abundance', 'foreign', 'lack', NULL, 'assets/img/660eff006fefb.png', '2024-03-21 19:46:40', '2024-04-04 19:26:56'),
(6, NULL, 'English', NULL, 'The teacher has introduced a motivational story which is germane to the topic.', 'irrelevant', 'indifferent', 'impartial', 'improvident', 'improvident', NULL, 'assets/img/660eff1ebf848.png', '2024-03-21 19:46:40', '2024-04-04 19:27:26'),
(7, NULL, 'English', NULL, 'I love to buy and read abridge dictionary because it has its complete details.', 'shorten', 'extend', 'stress', 'easy', 'extend', NULL, 'assets/img/660eff6d7cdf6.png', '2024-03-21 19:46:40', '2024-04-04 22:41:48'),


CREATE TABLE `player_sessions` (
  `session_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `score` int(11) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `duration` int(11) GENERATED ALWAYS AS (timestampdiff(SECOND,`start_time`,`end_time`)) VIRTUAL,
  `correct_answers` int(11) DEFAULT NULL,
  `total_questions` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `player_sessions`
--

INSERT INTO `player_sessions` (`session_id`, `user_id`, `score`, `start_time`, `end_time`, `correct_answers`, `total_questions`, `created_at`, `updated_at`) VALUES
(1, 26, 52, '2025-04-03 17:50:57', '2025-04-03 18:44:31', 52, 120, '2025-04-04 00:50:57', '2025-04-04 01:44:31'),
(2, 8, 50, '2025-04-03 17:51:02', '2025-04-03 19:02:17', 50, 120, '2025-04-04 00:51:02', '2025-04-04 02:02:17'),
(3, 25, 58, '2025-04-03 17:51:02', '2025-04-03 19:03:40', 58, 120, '2025-04-04 00:51:02', '2025-04-04 02:03:40'),
(4, 36, 39, '2025-04-03 17:51:02', '2025-04-03 18:41:03', 39, 120, '2025-04-04 00:51:02', '2025-04-04 01:41:03'),
(5, 16, 51, '2025-04-03 17:51:03', '2025-04-03 18:43:00', 51, 120, '2025-04-04 00:51:03', '2025-04-04 01:43:00'),
(6, 37, 37, '2025-04-03 17:51:03', '2025-04-03 19:02:04', 37, 120, '2025-04-04 00:51:03', '2025-04-04 02:02:04'),
(7, 10, 32, '2025-04-03 17:51:03', '2025-04-03 18:59:00', 32, 120, '2025-04-04 00:51:03', '2025-04-04 01:59:00'),
(8, 31, 53, '2025-04-03 17:51:03', '2025-04-03 18:54:37', 53, 120, '2025-04-04 00:51:03', '2025-04-04 01:54:37'),
(9, 20, 31, '2025-04-03 17:51:04', '2025-04-03 19:20:46', 31, 120, '2025-04-04 00:51:04', '2025-04-04 02:20:46'),
(10, 4, 35, '2025-04-03 17:51:04', '2025-04-03 18:49:50', 35, 120, '2025-04-04 00:51:04', '2025-04-04 01:49:50'),
(11, 30, 50, '2025-04-03 17:51:05', '2025-04-03 18:53:07', 50, 120, '2025-04-04 00:51:05', '2025-04-04 01:53:07'),

CREATE TABLE `answer_attempts` (
  `attempt_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `selected_answer` varchar(255) NOT NULL,
  `is_correct` tinyint(1) NOT NULL,
  `attempt_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `answer_attempts`
--

INSERT INTO `answer_attempts` (`attempt_id`, `session_id`, `question_id`, `selected_answer`, `is_correct`, `attempt_time`) VALUES
(0, 16, 89, '6 years', 1, '2025-04-04 00:51:21'),
(0, 15, 77, 'Singing Lupang Hinirang', 1, '2025-04-04 00:51:28'),
(0, 8, 101, 'Nanliligaw', 1, '2025-04-04 00:51:34'),
(0, 12, 2, 'thoughtful', 1, '2025-04-04 00:51:35'),
(0, 10, 35, 'galaxy → solar system → universe → planet', 0, '2025-04-04 00:51:45'),
(0, 8, 97, 'Akademiko', 1, '2025-04-04 00:51:45'),
(0, 13, 35, 'galaxy → solar system → universe → planet', 0, '2025-04-04 00:51:46'),
(0, 9, 85, 'It was established to make the Philippines as the Province of Spain.', 0, '2025-04-04 00:51:46'),
(0, 27, 89, '6 years', 1, '2025-04-04 00:51:48'),
(0, 7, 53, '10/42', 0, '2025-04-04 00:51:49'),
(0, 1, 31, 'Charge', 0, '2025-04-04 00:51:53'),
(0, 8, 101, 'Nanliligaw', 1, '2025-04-04 00:51:34'),
(0, 12, 2, 'thoughtful', 1, '2025-04-04 00:51:35'),
(0, 10, 35, 'galaxy → solar system → universe → planet', 0, '2025-04-04 00:51:45'),
(0, 8, 97, 'Akademiko', 1, '2025-04-04 00:51:45'),
(0, 13, 35, 'galaxy → solar system → universe → planet', 0, '2025-04-04 00:51:46'),
(0, 9, 85, 'It was established to make the Philippines as the Province of Spain.', 0, '2025-04-04 00:51:46'),
(0, 27, 89, '6 years', 1, '2025-04-04 00:51:48'),
(0, 7, 53, '10/42', 0, '2025-04-04 00:51:49'),
(0, 1, 31, 'Charge', 0, '2025-04-04 00:51:53'),
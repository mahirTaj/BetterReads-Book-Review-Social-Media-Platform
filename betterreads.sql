-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 13, 2024 at 07:58 AM
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
-- Database: `betterreads`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`username`, `password`) VALUES
('admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `author`
--

CREATE TABLE `author` (
  `author_id` int(11) NOT NULL,
  `biography` varchar(2000) DEFAULT NULL,
  `personal_website` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `author`
--

INSERT INTO `author` (`author_id`, `biography`, `personal_website`) VALUES
(17, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `author_book_add_and_claim_request`
--

CREATE TABLE `author_book_add_and_claim_request` (
  `request_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `isbn` int(11) NOT NULL,
  `publish_date` date DEFAULT NULL,
  `pages` int(11) DEFAULT NULL,
  `purchase_link` varchar(255) DEFAULT NULL,
  `editions` int(11) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `author_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `author_writes_book`
--

CREATE TABLE `author_writes_book` (
  `author_id` int(11) NOT NULL,
  `isbn` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `author_written_genre`
--

CREATE TABLE `author_written_genre` (
  `author_id` int(11) NOT NULL,
  `genre_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `isbn` varchar(13) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author_name` varchar(255) NOT NULL,
  `publish_date` date DEFAULT NULL,
  `pages` int(11) DEFAULT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `format` varchar(50) DEFAULT NULL,
  `purchase_link` varchar(255) DEFAULT NULL,
  `publisher` varchar(255) DEFAULT NULL,
  `language` varchar(10) NOT NULL,
  `cover` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`isbn`, `title`, `author_name`, `publish_date`, `pages`, `description`, `format`, `purchase_link`, `publisher`, `language`, `cover`) VALUES
('9781594771538', 'The 7 Habits of Highly Effective People: Powerful Lessons in Personal Change', 'Stephen R. Covey', '1989-01-01', 372, '', 'paperback', 'https://www.amazon.com/gp/product/0743269519/ref=x_gr_bb_amazon?ie=UTF8&camp=1789&creative=9325&creativeASIN=0743269519&SubscriptionId=1MGPYB6YW3HWK55XCGG2', 'Free Press', 'English', 'book_cover/66defb0a0bdde9.57634337.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `book_belongs_to_genre`
--

CREATE TABLE `book_belongs_to_genre` (
  `isbn` varchar(13) NOT NULL,
  `genre_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_belongs_to_genre`
--

INSERT INTO `book_belongs_to_genre` (`isbn`, `genre_name`) VALUES
('9781594771538', 'non-fiction'),
('9781594771538', 'self-help');

-- --------------------------------------------------------

--
-- Table structure for table `book_request`
--

CREATE TABLE `book_request` (
  `request_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `isbn` int(11) DEFAULT NULL,
  `publish_date` date DEFAULT NULL,
  `pages` int(11) DEFAULT NULL,
  `purchase_link` varchar(255) DEFAULT NULL,
  `editions` int(11) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `author_name` varchar(100) DEFAULT NULL,
  `reader_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `genre`
--

CREATE TABLE `genre` (
  `genre_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genre`
--

INSERT INTO `genre` (`genre_name`) VALUES
('business'),
('fantasy'),
('fiction'),
('history'),
('horror'),
('mystery'),
('non-fiction'),
('religion'),
('science fiction'),
('self-help'),
('thriller');

-- --------------------------------------------------------

--
-- Table structure for table `reader`
--

CREATE TABLE `reader` (
  `reader_id` int(11) NOT NULL,
  `about_me` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reader`
--

INSERT INTO `reader` (`reader_id`, `about_me`) VALUES
(14, ''),
(16, '');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `review_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `posting_date` date NOT NULL DEFAULT current_timestamp(),
  `description` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`review_id`, `rating`, `posting_date`, `description`) VALUES
(15, 5, '2024-09-13', 'best book ever!!!!'),
(21, 4, '2024-09-13', 'This book changed my life!!!');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `mname` varchar(50) DEFAULT NULL,
  `lname` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `joining_date` datetime NOT NULL DEFAULT current_timestamp(),
  `profile_picture` varchar(255) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `fname`, `mname`, `lname`, `password`, `email`, `joining_date`, `profile_picture`, `gender`, `country`, `date_of_birth`) VALUES
(14, 'Mahir', 'Tajwar', 'Rahman', '$2y$10$aNh15LlPi63UAhNSbiau/.LN04h.OEfhzPC21ki5f7AaRa2xm8NTq', 'mahir19800@gmail.com', '2024-09-06 21:12:12', 'dp/66e3bf5264e216.71843876.jpg', 'male', 'Bangladesh', '2001-09-04'),
(16, 'Abrar', '', 'Samin', '$2y$10$hA2ohLbnmqAQcx9e.CbYgOFcHpfJOlARyZtKu8jXbydFMsSLYPX1G', 'abrar@gmail.com', '2024-09-13 09:05:48', NULL, NULL, NULL, NULL),
(17, 'Arif', '', 'Azad', '$2y$10$Z8IoXlDhKC59tlLzRg16Kekb.2y6MFdcRuj3jWGMcDgRSKCsuvUyW', 'arif@gmail.com', '2024-09-13 10:48:19', 'dp/66e3c5cb7d9a01.48735381.jpg', 'male', 'Bangladesh', '1971-12-16');

-- --------------------------------------------------------

--
-- Table structure for table `user_books_read_status`
--

CREATE TABLE `user_books_read_status` (
  `isbn` varchar(13) NOT NULL,
  `reader_id` int(11) NOT NULL,
  `reading_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_comments_review`
--

CREATE TABLE `user_comments_review` (
  `review_id` int(11) NOT NULL,
  `reader_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_follows_user`
--

CREATE TABLE `user_follows_user` (
  `follower_id` int(11) NOT NULL,
  `followed_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_likes_genre`
--

CREATE TABLE `user_likes_genre` (
  `genre_name` varchar(50) NOT NULL,
  `reader_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_likes_review`
--

CREATE TABLE `user_likes_review` (
  `review_id` int(11) NOT NULL,
  `reader_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_reviews_book`
--

CREATE TABLE `user_reviews_book` (
  `review_id` int(11) NOT NULL,
  `isbn` varchar(13) NOT NULL,
  `reader_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_reviews_book`
--

INSERT INTO `user_reviews_book` (`review_id`, `isbn`, `reader_id`) VALUES
(15, '9781594771538', 16),
(21, '9781594771538', 14);

-- --------------------------------------------------------

--
-- Table structure for table `user_social_media_url`
--

CREATE TABLE `user_social_media_url` (
  `social_media_url` varchar(255) NOT NULL,
  `reader_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`author_id`),
  ADD UNIQUE KEY `personal_website` (`personal_website`);

--
-- Indexes for table `author_book_add_and_claim_request`
--
ALTER TABLE `author_book_add_and_claim_request`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `author_id` (`author_id`);

--
-- Indexes for table `author_writes_book`
--
ALTER TABLE `author_writes_book`
  ADD PRIMARY KEY (`author_id`,`isbn`),
  ADD KEY `author_writes_book_ibfk_2` (`isbn`);

--
-- Indexes for table `author_written_genre`
--
ALTER TABLE `author_written_genre`
  ADD PRIMARY KEY (`author_id`,`genre_name`),
  ADD KEY `genre_name` (`genre_name`);

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`isbn`),
  ADD UNIQUE KEY `purchase_link` (`purchase_link`);

--
-- Indexes for table `book_belongs_to_genre`
--
ALTER TABLE `book_belongs_to_genre`
  ADD PRIMARY KEY (`isbn`,`genre_name`),
  ADD KEY `genre_name` (`genre_name`);

--
-- Indexes for table `book_request`
--
ALTER TABLE `book_request`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `reader_id` (`reader_id`);

--
-- Indexes for table `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`genre_name`);

--
-- Indexes for table `reader`
--
ALTER TABLE `reader`
  ADD PRIMARY KEY (`reader_id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`review_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `profile_picture` (`profile_picture`);

--
-- Indexes for table `user_books_read_status`
--
ALTER TABLE `user_books_read_status`
  ADD PRIMARY KEY (`isbn`,`reader_id`),
  ADD KEY `reader_id` (`reader_id`);

--
-- Indexes for table `user_comments_review`
--
ALTER TABLE `user_comments_review`
  ADD PRIMARY KEY (`review_id`,`reader_id`),
  ADD KEY `reader_id` (`reader_id`);

--
-- Indexes for table `user_follows_user`
--
ALTER TABLE `user_follows_user`
  ADD PRIMARY KEY (`follower_id`,`followed_id`),
  ADD KEY `followed_id` (`followed_id`);

--
-- Indexes for table `user_likes_genre`
--
ALTER TABLE `user_likes_genre`
  ADD PRIMARY KEY (`genre_name`,`reader_id`),
  ADD KEY `reader_id` (`reader_id`);

--
-- Indexes for table `user_likes_review`
--
ALTER TABLE `user_likes_review`
  ADD PRIMARY KEY (`review_id`,`reader_id`) USING BTREE,
  ADD KEY `user_likes_review_ibfk_2` (`reader_id`);

--
-- Indexes for table `user_reviews_book`
--
ALTER TABLE `user_reviews_book`
  ADD PRIMARY KEY (`review_id`,`isbn`,`reader_id`),
  ADD KEY `reader_id` (`reader_id`),
  ADD KEY `user_reviews_book_ibfk_2` (`isbn`);

--
-- Indexes for table `user_social_media_url`
--
ALTER TABLE `user_social_media_url`
  ADD PRIMARY KEY (`social_media_url`,`reader_id`),
  ADD KEY `reader_id` (`reader_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `author`
--
ALTER TABLE `author`
  ADD CONSTRAINT `author_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `author_book_add_and_claim_request`
--
ALTER TABLE `author_book_add_and_claim_request`
  ADD CONSTRAINT `author_book_add_and_claim_request_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `author` (`author_id`);

--
-- Constraints for table `author_writes_book`
--
ALTER TABLE `author_writes_book`
  ADD CONSTRAINT `author_writes_book_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `author` (`author_id`),
  ADD CONSTRAINT `author_writes_book_ibfk_2` FOREIGN KEY (`isbn`) REFERENCES `book` (`isbn`);

--
-- Constraints for table `author_written_genre`
--
ALTER TABLE `author_written_genre`
  ADD CONSTRAINT `author_written_genre_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `author` (`author_id`),
  ADD CONSTRAINT `author_written_genre_ibfk_2` FOREIGN KEY (`genre_name`) REFERENCES `genre` (`genre_name`);

--
-- Constraints for table `book_belongs_to_genre`
--
ALTER TABLE `book_belongs_to_genre`
  ADD CONSTRAINT `book_belongs_to_genre_ibfk_1` FOREIGN KEY (`isbn`) REFERENCES `book` (`isbn`),
  ADD CONSTRAINT `book_belongs_to_genre_ibfk_2` FOREIGN KEY (`genre_name`) REFERENCES `genre` (`genre_name`);

--
-- Constraints for table `book_request`
--
ALTER TABLE `book_request`
  ADD CONSTRAINT `book_request_ibfk_1` FOREIGN KEY (`reader_id`) REFERENCES `reader` (`reader_id`);

--
-- Constraints for table `reader`
--
ALTER TABLE `reader`
  ADD CONSTRAINT `reader_ibfk_1` FOREIGN KEY (`reader_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `user_books_read_status`
--
ALTER TABLE `user_books_read_status`
  ADD CONSTRAINT `user_books_read_status_ibfk_1` FOREIGN KEY (`isbn`) REFERENCES `book` (`isbn`),
  ADD CONSTRAINT `user_books_read_status_ibfk_2` FOREIGN KEY (`reader_id`) REFERENCES `reader` (`reader_id`);

--
-- Constraints for table `user_comments_review`
--
ALTER TABLE `user_comments_review`
  ADD CONSTRAINT `user_comments_review_ibfk_1` FOREIGN KEY (`review_id`) REFERENCES `review` (`review_id`),
  ADD CONSTRAINT `user_comments_review_ibfk_2` FOREIGN KEY (`reader_id`) REFERENCES `reader` (`reader_id`);

--
-- Constraints for table `user_follows_user`
--
ALTER TABLE `user_follows_user`
  ADD CONSTRAINT `user_follows_user_ibfk_1` FOREIGN KEY (`follower_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `user_follows_user_ibfk_2` FOREIGN KEY (`followed_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `user_likes_genre`
--
ALTER TABLE `user_likes_genre`
  ADD CONSTRAINT `user_likes_genre_ibfk_1` FOREIGN KEY (`genre_name`) REFERENCES `genre` (`genre_name`),
  ADD CONSTRAINT `user_likes_genre_ibfk_2` FOREIGN KEY (`reader_id`) REFERENCES `reader` (`reader_id`);

--
-- Constraints for table `user_likes_review`
--
ALTER TABLE `user_likes_review`
  ADD CONSTRAINT `user_likes_review_ibfk_1` FOREIGN KEY (`review_id`) REFERENCES `review` (`review_id`),
  ADD CONSTRAINT `user_likes_review_ibfk_2` FOREIGN KEY (`reader_id`) REFERENCES `reader` (`reader_id`);

--
-- Constraints for table `user_reviews_book`
--
ALTER TABLE `user_reviews_book`
  ADD CONSTRAINT `user_reviews_book_ibfk_1` FOREIGN KEY (`review_id`) REFERENCES `review` (`review_id`),
  ADD CONSTRAINT `user_reviews_book_ibfk_2` FOREIGN KEY (`isbn`) REFERENCES `book` (`isbn`),
  ADD CONSTRAINT `user_reviews_book_ibfk_3` FOREIGN KEY (`reader_id`) REFERENCES `reader` (`reader_id`);

--
-- Constraints for table `user_social_media_url`
--
ALTER TABLE `user_social_media_url`
  ADD CONSTRAINT `user_social_media_url_ibfk_1` FOREIGN KEY (`reader_id`) REFERENCES `reader` (`reader_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

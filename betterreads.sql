-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2024 at 07:09 PM
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
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `author`
--

CREATE TABLE `author` (
  `user_id` int(11) NOT NULL,
  `biography` text DEFAULT NULL,
  `personal_website` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `author_writes_book`
--

CREATE TABLE `author_writes_book` (
  `user_id` int(11) NOT NULL,
  `isbn` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `author_written_genre`
--

CREATE TABLE `author_written_genre` (
  `user_id` int(11) NOT NULL,
  `genre_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `isbn` varchar(13) NOT NULL,
  `title` varchar(255) NOT NULL,
  `publish_date` date DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `pages` int(11) DEFAULT NULL,
  `editions` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `purchase_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `book_belongs_to_genre`
--

CREATE TABLE `book_belongs_to_genre` (
  `isbn` varchar(13) NOT NULL,
  `genre_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `book_language`
--

CREATE TABLE `book_language` (
  `isbn` varchar(13) NOT NULL,
  `language` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `book_request`
--

CREATE TABLE `book_request` (
  `request_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `isbn` varchar(13) DEFAULT NULL,
  `publish_date` date DEFAULT NULL,
  `pages` int(11) DEFAULT NULL,
  `purchase_link` varchar(255) DEFAULT NULL,
  `editions` int(11) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `author_name` varchar(255) DEFAULT NULL
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
('Biography'),
('Business'),
('Classics'),
('Crime'),
('Fantasy'),
('Historical Fiction'),
('History'),
('Horror'),
('Humor and Comedy'),
('Poetry'),
('Psychology'),
('Religion'),
('Science'),
('Thriller'),
('Travel');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `review_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `posting_date` date NOT NULL DEFAULT current_timestamp(),
  `description` text DEFAULT NULL,
  `started_reading` date DEFAULT NULL,
  `finished_reading` date DEFAULT NULL,
  `spoilers` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `mname` varchar(100) DEFAULT NULL,
  `lname` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `joining_date` datetime NOT NULL DEFAULT current_timestamp(),
  `gender` varchar(10) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `fname`, `mname`, `lname`, `email`, `password`, `joining_date`, `gender`, `country`, `date_of_birth`, `profile_picture`) VALUES
(2, 'Mahir', 'Tajwar', 'Rahman', 'mahir19800@gmail.com', '$2y$10$Gam1eZDA45yiuxpJnX8sqONxje3vomy.fMSsYnswZPbJ6jPEECg3O', '2024-09-04 22:52:48', NULL, NULL, NULL, 'dp/66d894278a9517.91117509.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user_books_read_status`
--

CREATE TABLE `user_books_read_status` (
  `isbn` varchar(13) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reading_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_comments_review`
--

CREATE TABLE `user_comments_review` (
  `user_id` int(11) NOT NULL,
  `review_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL
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
  `user_id` int(11) NOT NULL,
  `genre_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_likes_review`
--

CREATE TABLE `user_likes_review` (
  `user_id` int(11) NOT NULL,
  `review_Id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_reviews_book`
--

CREATE TABLE `user_reviews_book` (
  `user_id` int(11) NOT NULL,
  `review_Id` int(11) NOT NULL,
  `isbn` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_social_media_url`
--

CREATE TABLE `user_social_media_url` (
  `user_id` int(11) NOT NULL,
  `social_media_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `Personal_Website` (`personal_website`);

--
-- Indexes for table `author_writes_book`
--
ALTER TABLE `author_writes_book`
  ADD PRIMARY KEY (`user_id`,`isbn`),
  ADD KEY `ISBN` (`isbn`);

--
-- Indexes for table `author_written_genre`
--
ALTER TABLE `author_written_genre`
  ADD PRIMARY KEY (`user_id`,`genre_name`),
  ADD KEY `Genre_name` (`genre_name`);

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`isbn`),
  ADD UNIQUE KEY `Purchase_Link` (`purchase_link`);

--
-- Indexes for table `book_belongs_to_genre`
--
ALTER TABLE `book_belongs_to_genre`
  ADD PRIMARY KEY (`genre_name`,`isbn`),
  ADD KEY `ISBN` (`isbn`);

--
-- Indexes for table `book_language`
--
ALTER TABLE `book_language`
  ADD PRIMARY KEY (`language`,`isbn`),
  ADD KEY `ISBN` (`isbn`);

--
-- Indexes for table `book_request`
--
ALTER TABLE `book_request`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `User_ID` (`user_id`);

--
-- Indexes for table `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`genre_name`);

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
  ADD UNIQUE KEY `Email` (`email`),
  ADD UNIQUE KEY `Profile_Picture` (`profile_picture`);

--
-- Indexes for table `user_books_read_status`
--
ALTER TABLE `user_books_read_status`
  ADD PRIMARY KEY (`isbn`,`user_id`),
  ADD KEY `User_ID` (`user_id`);

--
-- Indexes for table `user_comments_review`
--
ALTER TABLE `user_comments_review`
  ADD PRIMARY KEY (`user_id`,`review_id`),
  ADD KEY `Review_Id` (`review_id`);

--
-- Indexes for table `user_follows_user`
--
ALTER TABLE `user_follows_user`
  ADD PRIMARY KEY (`follower_id`,`followed_id`),
  ADD UNIQUE KEY `Follower_ID` (`follower_id`,`followed_id`),
  ADD KEY `Followed_ID` (`followed_id`);

--
-- Indexes for table `user_likes_genre`
--
ALTER TABLE `user_likes_genre`
  ADD PRIMARY KEY (`user_id`,`genre_name`),
  ADD KEY `Genre_name` (`genre_name`);

--
-- Indexes for table `user_likes_review`
--
ALTER TABLE `user_likes_review`
  ADD PRIMARY KEY (`user_id`,`review_Id`),
  ADD KEY `Review_Id` (`review_Id`);

--
-- Indexes for table `user_reviews_book`
--
ALTER TABLE `user_reviews_book`
  ADD PRIMARY KEY (`user_id`,`review_Id`,`isbn`),
  ADD KEY `Review_Id` (`review_Id`),
  ADD KEY `ISBN` (`isbn`);

--
-- Indexes for table `user_social_media_url`
--
ALTER TABLE `user_social_media_url`
  ADD PRIMARY KEY (`social_media_url`,`user_id`),
  ADD KEY `User_ID` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `book_request`
--
ALTER TABLE `book_request`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `author`
--
ALTER TABLE `author`
  ADD CONSTRAINT `author_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user` (`User_ID`);

--
-- Constraints for table `author_writes_book`
--
ALTER TABLE `author_writes_book`
  ADD CONSTRAINT `author_writes_book_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `author` (`User_ID`),
  ADD CONSTRAINT `author_writes_book_ibfk_2` FOREIGN KEY (`ISBN`) REFERENCES `book` (`ISBN`);

--
-- Constraints for table `author_written_genre`
--
ALTER TABLE `author_written_genre`
  ADD CONSTRAINT `author_written_genre_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `author` (`User_ID`),
  ADD CONSTRAINT `author_written_genre_ibfk_2` FOREIGN KEY (`Genre_name`) REFERENCES `genre` (`Genre_name`);

--
-- Constraints for table `book_belongs_to_genre`
--
ALTER TABLE `book_belongs_to_genre`
  ADD CONSTRAINT `book_belongs_to_genre_ibfk_1` FOREIGN KEY (`Genre_name`) REFERENCES `genre` (`Genre_name`),
  ADD CONSTRAINT `book_belongs_to_genre_ibfk_2` FOREIGN KEY (`ISBN`) REFERENCES `book` (`ISBN`);

--
-- Constraints for table `book_language`
--
ALTER TABLE `book_language`
  ADD CONSTRAINT `book_language_ibfk_1` FOREIGN KEY (`ISBN`) REFERENCES `book` (`ISBN`);

--
-- Constraints for table `book_request`
--
ALTER TABLE `book_request`
  ADD CONSTRAINT `book_request_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user` (`User_ID`);

--
-- Constraints for table `user_books_read_status`
--
ALTER TABLE `user_books_read_status`
  ADD CONSTRAINT `user_books_read_status_ibfk_1` FOREIGN KEY (`ISBN`) REFERENCES `book` (`ISBN`),
  ADD CONSTRAINT `user_books_read_status_ibfk_2` FOREIGN KEY (`User_ID`) REFERENCES `user` (`User_ID`);

--
-- Constraints for table `user_comments_review`
--
ALTER TABLE `user_comments_review`
  ADD CONSTRAINT `user_comments_review_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user` (`User_ID`),
  ADD CONSTRAINT `user_comments_review_ibfk_2` FOREIGN KEY (`Review_Id`) REFERENCES `review` (`Review_Id`);

--
-- Constraints for table `user_follows_user`
--
ALTER TABLE `user_follows_user`
  ADD CONSTRAINT `user_follows_user_ibfk_1` FOREIGN KEY (`Follower_ID`) REFERENCES `user` (`User_ID`),
  ADD CONSTRAINT `user_follows_user_ibfk_2` FOREIGN KEY (`Followed_ID`) REFERENCES `user` (`User_ID`);

--
-- Constraints for table `user_likes_genre`
--
ALTER TABLE `user_likes_genre`
  ADD CONSTRAINT `user_likes_genre_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user` (`User_ID`),
  ADD CONSTRAINT `user_likes_genre_ibfk_2` FOREIGN KEY (`Genre_name`) REFERENCES `genre` (`Genre_name`);

--
-- Constraints for table `user_likes_review`
--
ALTER TABLE `user_likes_review`
  ADD CONSTRAINT `user_likes_review_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user` (`User_ID`),
  ADD CONSTRAINT `user_likes_review_ibfk_2` FOREIGN KEY (`Review_Id`) REFERENCES `review` (`Review_Id`);

--
-- Constraints for table `user_reviews_book`
--
ALTER TABLE `user_reviews_book`
  ADD CONSTRAINT `user_reviews_book_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user` (`User_ID`),
  ADD CONSTRAINT `user_reviews_book_ibfk_2` FOREIGN KEY (`Review_Id`) REFERENCES `review` (`Review_Id`),
  ADD CONSTRAINT `user_reviews_book_ibfk_3` FOREIGN KEY (`ISBN`) REFERENCES `book` (`ISBN`);

--
-- Constraints for table `user_social_media_url`
--
ALTER TABLE `user_social_media_url`
  ADD CONSTRAINT `user_social_media_url_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user` (`User_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

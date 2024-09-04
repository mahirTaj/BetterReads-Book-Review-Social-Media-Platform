-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2024 at 03:47 AM
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
-- Table structure for table `author`
--

CREATE TABLE `author` (
  `User_ID` int(11) NOT NULL,
  `Biography` text DEFAULT NULL,
  `Personal_Website` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `author_writes_book`
--

CREATE TABLE `author_writes_book` (
  `User_ID` int(11) NOT NULL,
  `ISBN` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `author_written_genre`
--

CREATE TABLE `author_written_genre` (
  `User_ID` int(11) NOT NULL,
  `Genre_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `ISBN` varchar(13) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Publish_date` date DEFAULT NULL,
  `Type` varchar(50) DEFAULT NULL,
  `Pages` int(11) DEFAULT NULL,
  `Editions` int(11) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `Purchase_Link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `book_belongs_to_genre`
--

CREATE TABLE `book_belongs_to_genre` (
  `ISBN` varchar(13) NOT NULL,
  `Genre_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `book_language`
--

CREATE TABLE `book_language` (
  `ISBN` varchar(13) NOT NULL,
  `Language` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `book_request`
--

CREATE TABLE `book_request` (
  `Request_ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `ISBN` varchar(13) DEFAULT NULL,
  `Publish_Date` date DEFAULT NULL,
  `Pages` int(11) DEFAULT NULL,
  `Purchase_Link` varchar(255) DEFAULT NULL,
  `Editions` int(11) DEFAULT NULL,
  `Type` varchar(50) DEFAULT NULL,
  `Author_Name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `genre`
--

CREATE TABLE `genre` (
  `Genre_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `Review_Id` int(11) NOT NULL,
  `Rating` int(11) NOT NULL,
  `Posting_date` date NOT NULL DEFAULT current_timestamp(),
  `Description` text DEFAULT NULL,
  `Started_Reading` date DEFAULT NULL,
  `Finished_Reading` date DEFAULT NULL,
  `Spoilers` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `User_ID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Joining_Date` datetime NOT NULL DEFAULT current_timestamp(),
  `Gender` varchar(10) DEFAULT NULL,
  `Country` varchar(100) DEFAULT NULL,
  `Date_of_Birth` date DEFAULT NULL,
  `Profile_Picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_books_read_status`
--

CREATE TABLE `user_books_read_status` (
  `ISBN` varchar(13) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `Reading_Status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_comments_review`
--

CREATE TABLE `user_comments_review` (
  `User_ID` int(11) NOT NULL,
  `Review_Id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_follows_user`
--

CREATE TABLE `user_follows_user` (
  `Follower_ID` int(11) NOT NULL,
  `Followed_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_likes_genre`
--

CREATE TABLE `user_likes_genre` (
  `User_ID` int(11) NOT NULL,
  `Genre_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_likes_review`
--

CREATE TABLE `user_likes_review` (
  `User_ID` int(11) NOT NULL,
  `Review_Id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_reviews_book`
--

CREATE TABLE `user_reviews_book` (
  `User_ID` int(11) NOT NULL,
  `Review_Id` int(11) NOT NULL,
  `ISBN` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_social_media_url`
--

CREATE TABLE `user_social_media_url` (
  `User_ID` int(11) NOT NULL,
  `Social_Media_Url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`User_ID`),
  ADD UNIQUE KEY `Personal_Website` (`Personal_Website`);

--
-- Indexes for table `author_writes_book`
--
ALTER TABLE `author_writes_book`
  ADD PRIMARY KEY (`User_ID`,`ISBN`),
  ADD KEY `ISBN` (`ISBN`);

--
-- Indexes for table `author_written_genre`
--
ALTER TABLE `author_written_genre`
  ADD PRIMARY KEY (`User_ID`,`Genre_name`),
  ADD KEY `Genre_name` (`Genre_name`);

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`ISBN`),
  ADD UNIQUE KEY `Purchase_Link` (`Purchase_Link`);

--
-- Indexes for table `book_belongs_to_genre`
--
ALTER TABLE `book_belongs_to_genre`
  ADD PRIMARY KEY (`Genre_name`,`ISBN`),
  ADD KEY `ISBN` (`ISBN`);

--
-- Indexes for table `book_language`
--
ALTER TABLE `book_language`
  ADD PRIMARY KEY (`Language`,`ISBN`),
  ADD KEY `ISBN` (`ISBN`);

--
-- Indexes for table `book_request`
--
ALTER TABLE `book_request`
  ADD PRIMARY KEY (`Request_ID`),
  ADD KEY `User_ID` (`User_ID`);

--
-- Indexes for table `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`Genre_name`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`Review_Id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`User_ID`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `Profile_Picture` (`Profile_Picture`);

--
-- Indexes for table `user_books_read_status`
--
ALTER TABLE `user_books_read_status`
  ADD PRIMARY KEY (`ISBN`,`User_ID`),
  ADD KEY `User_ID` (`User_ID`);

--
-- Indexes for table `user_comments_review`
--
ALTER TABLE `user_comments_review`
  ADD PRIMARY KEY (`User_ID`,`Review_Id`),
  ADD KEY `Review_Id` (`Review_Id`);

--
-- Indexes for table `user_follows_user`
--
ALTER TABLE `user_follows_user`
  ADD PRIMARY KEY (`Follower_ID`,`Followed_ID`),
  ADD UNIQUE KEY `Follower_ID` (`Follower_ID`,`Followed_ID`),
  ADD KEY `Followed_ID` (`Followed_ID`);

--
-- Indexes for table `user_likes_genre`
--
ALTER TABLE `user_likes_genre`
  ADD PRIMARY KEY (`User_ID`,`Genre_name`),
  ADD KEY `Genre_name` (`Genre_name`);

--
-- Indexes for table `user_likes_review`
--
ALTER TABLE `user_likes_review`
  ADD PRIMARY KEY (`User_ID`,`Review_Id`),
  ADD KEY `Review_Id` (`Review_Id`);

--
-- Indexes for table `user_reviews_book`
--
ALTER TABLE `user_reviews_book`
  ADD PRIMARY KEY (`User_ID`,`Review_Id`,`ISBN`),
  ADD KEY `Review_Id` (`Review_Id`),
  ADD KEY `ISBN` (`ISBN`);

--
-- Indexes for table `user_social_media_url`
--
ALTER TABLE `user_social_media_url`
  ADD PRIMARY KEY (`Social_Media_Url`,`User_ID`),
  ADD KEY `User_ID` (`User_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `book_request`
--
ALTER TABLE `book_request`
  MODIFY `Request_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `Review_Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT;

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

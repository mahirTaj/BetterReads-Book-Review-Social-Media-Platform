-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 19, 2024 at 06:02 PM
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
(17, NULL, NULL),
(18, 'Librarian’s note: There is more than one author in the Goodreads database with this name.&#13;&#10;&#13;&#10;Spencer Johnson, M.D. left behind a medical career to write short books about life. The most famous was “Who Moved My Cheese?&#34; published in 1998. The book became a publishing phenomenon and a workplace manual. Over 50 million copies of Spencer Johnson’s books are in use worldwide in 47 languages.&#13;&#10;&#13;&#10;Dr. Johnson&#39;s education included a psychology degree from the University of Southern California, a M.D. from the Royal College of Surgeons and medical clerkships at Harvard Medical School and the Mayo Clinic.', 'http://spencerjohnson.com/');

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

--
-- Dumping data for table `author_writes_book`
--

INSERT INTO `author_writes_book` (`author_id`, `isbn`) VALUES
(18, '9780091883768');

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
('9780091883768', 'Who Moved My Cheese?', 'Spencer Johnson', '2002-01-01', 98, '&quot;Who Moved My Cheese?&quot; is a simple parable that reveals profound truths. It is an amusing and enlightening story of four characters who live in a &quot;Maze&quot; and look for &quot;Cheese&quot; to nourish them and make them happy.  Two are mice named Sniff and Scurry. And two are &quot;Littlepeople&quot; — beings the size of mice who look and act a lot like people. Their names are Hem and Haw.  &quot;Cheese&quot; is a metaphor for what you want to have in life — whether it&#039;s a good job, a loving relationship, money, a possession, health, or spiritual peace of mind.  And the &quot;Maze&quot; is where you look for what you want — the organisation you work in or the family or community you live in.  In the story, the characters are faced with unexpected change. Eventually, one of them deals with it successfully, and writes what he has learned from his experience on the Maze walls.  When you come to see &quot;The Handwriting on the Wall,&quot; you can discover for yourself ', 'hardcover', 'https://www.amazon.com/gp/product/0091883768/ref=x_gr_bb_amazon?ie=UTF8&camp=1789&creative=9325&creativeASIN=0091883768&SubscriptionId=1MGPYB6YW3HWK55XCGG2', 'Putnam', 'English', 'book_cover/66e857b354c440.44531837.jpg'),
('9780439784542', 'Harry Potter and the Half-Blood Prince', 'J.K. Rowling', '2006-09-16', 652, 'It is the middle of the summer, but there is an unseasonal mist pressing against the windowpanes. Harry Potter is waiting nervously in his bedroom at the Dursleys&#039; house in Privet Drive for a visit from Professor Dumbledore himself. One of the last times he saw the Headmaster, he was in a fierce one-to-one duel with Lord Voldemort, and Harry can&#039;t quite believe that Professor Dumbledore will actually appear at the Dursleys&#039; of all places. Why is the Professor coming to visit him now? What is it that cannot wait until Harry returns to Hogwarts in a few weeks&#039; time? Harry&#039;s sixth year at Hogwarts has already got off to an unusual start, as the worlds of Muggle and magic start to intertwine...', 'paperback', 'https://www.amazon.com/s?k=Harry+Potter+and+the+Half-Blood+Prince&i=stripbooks&adid=082VK13VJJCZTQYGWWCZ&campaign=211041&creative=374001', 'Scholastic In', 'English', 'book_cover/66ec3b678e9713.68327031.jpg'),
('9781451648539', 'Steve Jobs', 'Walter Isaacson', '2011-10-24', 630, 'Walter Isaacson&#039;s worldwide bestselling biography of Apple cofounder Steve Jobs. Based on more than forty interviews with Steve Jobs conducted over two years--as well as interviews with more than 100 family members, friends, adversaries, competitors, and colleagues--Walter Isaacson has written a riveting story of the roller-coaster life and searingly intense personality of a creative entrepreneur whose passion for perfection and ferocious drive revolutionized six industries: personal computers, animated movies, music, phones, tablet computing, and digital publishing. Isaacson&#039;s portrait touched millions of readers. At a time when America is seeking ways to sustain its innovative edge, Jobs stands as the ultimate icon of inventiveness and applied imagination. He knew that the best way to create value in the twenty-first century was to connect creativity with technology. He built a company where leaps of the imagination were combined with remarkable feats of engineering. Althou', 'hardcover', 'https://www.amazon.com/gp/product/1451648537/ref=x_gr_bb_amazon?ie=UTF8&camp=1789&creative=9325&creativeASIN=1451648537&SubscriptionId=1MGPYB6YW3HWK55XCGG2', 'Simon & Schuster', 'English', 'book_cover/66e858622096a5.12239712.jpg'),
('9781594771538', 'The 7 Habits of Highly Effective People: Powerful Lessons in Personal Change', 'Stephen R. Covey', '1989-01-01', 372, '', 'paperback', 'https://www.amazon.com/gp/product/0743269519/ref=x_gr_bb_amazon?ie=UTF8&camp=1789&creative=9325&creativeASIN=0743269519&SubscriptionId=1MGPYB6YW3HWK55XCGG2', 'Free Press', 'English', 'book_cover/66defb0a0bdde9.57634337.jpg'),
('9781734231205', 'Secrets of Divine Love: A Spiritual Journey into the Heart of Islam', 'A. Helwa', '0001-02-20', 387, 'Are you longing to experience a more intimate and loving relationship with the Divine?  Secrets of Divine Love draws upon spiritual secrets of the Qur&#039;an, ancient mystical poetry, and stories from the world&#039;s greatest prophets and spiritual masters to help you reignite your faith, overcome your doubts, and deepen your connection with God.  Through the use of scientific evidence, practical exercises, and guided meditations, you will develop the tools and awareness needed to discern and overcome your negative inner critic that prevents you from experiencing God&#039;s all-encompassing love.  The passages in this book serve as a compass and guiding light that returns you to the source of divine peace and surrender. Through the principles and practices of Islam, you will learn how to unlock your spiritual potential and unveil your divine purpose. Secrets of Divine Love uses a rational, yet heart-based approach towards the Qur&#039;an that not only enlightens the mind, but inspire', 'paperback', 'https://www.amazon.com/gp/product/1734231203/ref=x_gr_bb_amazon?ie=UTF8&camp=1789&creative=9325&creativeASIN=1734231203&SubscriptionId=1MGPYB6YW3HWK55XCGG2', 'Naulit Publishing House', 'English', 'book_cover/66e8592014b365.80997227.jpg');

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
('9780091883768', 'non-fiction'),
('9780091883768', 'self-help'),
('9780439784542', 'children'),
('9780439784542', 'fantasy'),
('9780439784542', 'fiction'),
('9780439784542', 'science-fiction'),
('9781451648539', 'history'),
('9781451648539', 'non-fiction'),
('9781594771538', 'non-fiction'),
('9781594771538', 'self-help'),
('9781734231205', 'non-fiction'),
('9781734231205', 'religion'),
('9781734231205', 'self-help');

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
('biography'),
('business'),
('children'),
('classic'),
('crime'),
('fantasy'),
('fiction'),
('history'),
('horror'),
('mystery'),
('non-fiction'),
('religion'),
('science-fiction'),
('self-help'),
('thriller');

-- --------------------------------------------------------

--
-- Table structure for table `reader`
--

CREATE TABLE `reader` (
  `reader_id` int(11) NOT NULL,
  `about_me` varchar(255) DEFAULT NULL,
  `social_link` varchar(255) DEFAULT NULL,
  `social_platform` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reader`
--

INSERT INTO `reader` (`reader_id`, `about_me`, `social_link`, `social_platform`) VALUES
(14, '', NULL, NULL),
(16, '', NULL, NULL);

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
(32, 4, '2024-09-16', 'this book changed my life'),
(33, 5, '2024-09-19', 'this book is very interesting!!!'),
(35, 4, '2024-09-19', 'this book is joss'),
(36, 5, '2024-09-19', 'this book is not good'),
(37, 4, '2024-09-19', 'i am enjoying this book');

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
(17, 'Arif', '', 'Azad', '$2y$10$Z8IoXlDhKC59tlLzRg16Kekb.2y6MFdcRuj3jWGMcDgRSKCsuvUyW', 'arif@gmail.com', '2024-09-13 10:48:19', 'dp/66e3c5cb7d9a01.48735381.jpg', 'male', 'Bangladesh', '1971-12-16'),
(18, 'Spencer', '', 'Johnson', '$2y$10$po8LeOhmLCfvsd6laAF8RuxMAZOeJRjlvNa4a76YAwQJTY5GGLr/m', 'spencerjohnson@gmail.com', '2024-09-19 21:34:48', 'dp/66ec459136e752.76382278.jpg', 'male', 'USA', '1938-07-03');

-- --------------------------------------------------------

--
-- Table structure for table `user_books_read_status`
--

CREATE TABLE `user_books_read_status` (
  `isbn` varchar(13) NOT NULL,
  `reader_id` int(11) NOT NULL,
  `reading_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_books_read_status`
--

INSERT INTO `user_books_read_status` (`isbn`, `reader_id`, `reading_status`) VALUES
('9780091883768', 14, 'want to read'),
('9781594771538', 14, 'read'),
('9781594771538', 16, 'currently reading');

-- --------------------------------------------------------

--
-- Table structure for table `user_comments_review`
--

CREATE TABLE `user_comments_review` (
  `comment_id` int(11) NOT NULL,
  `review_id` int(11) NOT NULL,
  `reader_id` int(11) NOT NULL,
  `comment` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_comments_review`
--

INSERT INTO `user_comments_review` (`comment_id`, `review_id`, `reader_id`, `comment`) VALUES
(4, 15, 14, 'You are correct'),
(13, 15, 14, 'right'),
(15, 32, 16, 'absolutely'),
(17, 15, 16, 'thank you!!'),
(19, 33, 14, 'you are right!');

-- --------------------------------------------------------

--
-- Table structure for table `user_follows_user`
--

CREATE TABLE `user_follows_user` (
  `follower_id` int(11) NOT NULL,
  `followed_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_follows_user`
--

INSERT INTO `user_follows_user` (`follower_id`, `followed_id`) VALUES
(14, 16),
(14, 17);

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

--
-- Dumping data for table `user_likes_review`
--

INSERT INTO `user_likes_review` (`review_id`, `reader_id`) VALUES
(15, 14),
(33, 14);

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
(32, '9781594771538', 14),
(33, '9780091883768', 16),
(35, '9780091883768', 14),
(37, '9780439784542', 14);

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
  ADD PRIMARY KEY (`comment_id`),
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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `user_comments_review`
--
ALTER TABLE `user_comments_review`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2024 at 07:07 AM
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
-- Database: `classrecord`
--

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `image_id` int(11) NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `image_data` longblob DEFAULT NULL,
  `image_name` varchar(255) DEFAULT NULL,
  `image_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblassesment`
--

CREATE TABLE `tblassesment` (
  `assesment_id` varchar(255) NOT NULL,
  `assesment_title` varchar(255) DEFAULT NULL,
  `total_item` int(11) DEFAULT NULL,
  `term` varchar(255) DEFAULT NULL,
  `assesment_type` varchar(225) NOT NULL,
  `class_id` varchar(255) NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `datetime` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblassesment`
--

INSERT INTO `tblassesment` (`assesment_id`, `assesment_title`, `total_item`, `term`, `assesment_type`, `class_id`, `user_id`, `datetime`) VALUES
('7057ec095', 'SUMMATIVE 1', 50, 'MidTerm', 'Assignment', 'YFO9u5tNi2', '6639907633472', '05/07, 2024 10:27:53 am'),
('761ffe27d', 'PT', 20, 'MidTerm', 'Output', 'y2GngqWXtm', '663998387384f', '05/07, 2024 11:00:54 am'),
('bd3bba9c4', 'SUMMATIVE 1', 50, 'Final', 'Summative', '9wlwbFf5ob', '6639934874125', '05/07, 2024 10:38:02 am');

-- --------------------------------------------------------

--
-- Table structure for table `tblattendance`
--

CREATE TABLE `tblattendance` (
  `attendance_id` int(11) NOT NULL,
  `student_id` varchar(225) NOT NULL,
  `record` varchar(225) NOT NULL,
  `datetime` varchar(225) NOT NULL,
  `class_id` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblattendance`
--

INSERT INTO `tblattendance` (`attendance_id`, `student_id`, `record`, `datetime`, `class_id`) VALUES
(281, '2021-0297-H', 'PRESENT', '2024-05-07T09:51', 'W6JtlZ3CMw'),
(282, '2021-0297-H', 'PRESENT', '2024-05-08T09:52', 'W6JtlZ3CMw'),
(283, '2021-0297-H', 'PRESENT', '2024-05-09T09:55', 'W6JtlZ3CMw'),
(284, '2021-0297-H', 'PRESENT', '2024-05-07T10:41', '9wlwbFf5ob');

-- --------------------------------------------------------

--
-- Table structure for table `tblclass`
--

CREATE TABLE `tblclass` (
  `class_id` varchar(225) NOT NULL,
  `course_number` varchar(225) NOT NULL,
  `class_year` varchar(225) NOT NULL,
  `class_section` varchar(225) NOT NULL,
  `school_year` varchar(225) NOT NULL,
  `user_id` varchar(225) NOT NULL,
  `class_course` varchar(225) NOT NULL,
  `datetime` varchar(225) NOT NULL,
  `class_semester` varchar(225) NOT NULL,
  `laboratory` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblclass`
--

INSERT INTO `tblclass` (`class_id`, `course_number`, `class_year`, `class_section`, `school_year`, `user_id`, `class_course`, `datetime`, `class_semester`, `laboratory`) VALUES
('9wlwbFf5ob', 'STAT 1', '3', 'A', '2023-2024', '6639934874125', 'BSHM', '05/07, 2024 10:36:31 am', 'First Semester', 'Laboratory'),
('e5IlgdAHlj', '', '1', 'A', '2023-2024', '66399b4bce6fe', 'BSIT', '05/07, 2024 11:10:57 am', 'Second Semester', 'Laboratory'),
('HqTCscs4vy', 'CCIT01', '1', 'A', '2023-2024', '66399b4bce6fe', 'BSIT', '05/07, 2024 11:11:26 am', 'First Semester', 'Laboratory'),
('W6JtlZ3CMw', 'STAT 1', '3', 'B', '2023-2024', '66397b7e4112f', 'BSHM', '05/07, 2024 09:40:22 am', 'First Semester', 'Laboratory'),
('WCh9dh1qcK', 'CCIT01', '1', 'B', '2023-2024', '6639972bf1e35', 'BSIT', '05/07, 2024 10:52:52 am', 'First Semester', 'Laboratory'),
('y2GngqWXtm', 'CCIT02', '2', 'A', '2023-2024', '663998387384f', 'BSIT', '05/07, 2024 10:58:31 am', 'Second Semester', 'Laboratory'),
('YFO9u5tNi2', 'HMPE 3', '3', 'A', '2023-2024', '6639907633472', 'BSHM', '05/07, 2024 10:24:29 am', 'First Semester', 'Laboratory'),
('Yx2sBPqdzd', 'HPC 11', '4', 'A', '2023-2024', '66397b7e4112f', 'BSHM', '05/07, 2024 08:59:50 am', 'First Semester', 'Laboratory');

-- --------------------------------------------------------

--
-- Table structure for table `tblschedule`
--

CREATE TABLE `tblschedule` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `description` varchar(225) DEFAULT NULL,
  `class_id` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblstdassesment`
--

CREATE TABLE `tblstdassesment` (
  `std_assesment_id` int(11) NOT NULL,
  `assesment_id` varchar(225) NOT NULL,
  `student_id` varchar(225) NOT NULL,
  `score` varchar(225) NOT NULL,
  `class_id` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblstdassesment`
--

INSERT INTO `tblstdassesment` (`std_assesment_id`, `assesment_id`, `student_id`, `score`, `class_id`) VALUES
(78, '7057ec095', '2022-H-G', '30', 'YFO9u5tNi2'),
(79, 'bd3bba9c4', '2021-0297-H', '34', '9wlwbFf5ob'),
(80, '761ffe27d', 'df', '18', 'y2GngqWXtm');

-- --------------------------------------------------------

--
-- Table structure for table `tblstudents`
--

CREATE TABLE `tblstudents` (
  `student_id` varchar(255) NOT NULL,
  `fname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `middlename` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `class_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblstudents`
--

INSERT INTO `tblstudents` (`student_id`, `fname`, `lastname`, `middlename`, `age`, `class_id`) VALUES
('2021-0297-H', 'Karl Jared', 'Opeña', 'Baltazar', 0, '9wlwbFf5ob'),
('2021-0297-H', 'Karl Jared', 'Opeña', 'Baltazar', 23, 'W6JtlZ3CMw'),
('2022-H-G', 'JUAN', 'DELACRUZ', 'YU', 22, 'YFO9u5tNi2'),
('df', 'Mia', 'More', 'Mee', 19, 'y2GngqWXtm');

-- --------------------------------------------------------

--
-- Table structure for table `tblsubjects`
--

CREATE TABLE `tblsubjects` (
  `subjectID` int(11) NOT NULL,
  `course_number` varchar(225) NOT NULL,
  `descriptive_title` varchar(225) NOT NULL,
  `credit_units` int(10) NOT NULL,
  `course_description` varchar(225) NOT NULL,
  `class_course` varchar(225) NOT NULL,
  `semester` varchar(225) NOT NULL,
  `laboratory` varchar(25) NOT NULL,
  `year` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblsubjects`
--

INSERT INTO `tblsubjects` (`subjectID`, `course_number`, `descriptive_title`, `credit_units`, `course_description`, `class_course`, `semester`, `laboratory`, `year`) VALUES
(13, 'CCIT01 ', 'Introduction to Computing', 3, 'This course provides an overview of the Computing\r\nIndustry and Computing profession, including\r\nResearch and Application of Computing in different\r\nfields such as Biology, Sociology, Environment and\r\nGaming.', 'BSIT', 'First Semester', '1', '1'),
(14, 'CCIT02 ', 'Computer Programming 1 ', 3, 'The Course covers the use of general purpose\r\nprogramming language to solve problems. The\r\nemphasis is to train students to design, implement,\r\ntest and debug programs intended to solve\r\ncomputing problems using fundamentals\r', 'BSIT', 'Second Semester', '1', '2'),
(15, ' CCIT03 ', 'Computer Programming 2', 3, 'This course is continuation of CCIT02 –\r\nFundamentals of Programming. The emphasis is to\r\ntrain students to design, implement, test, and debug\r\nprograms intended to solve computing problems\r\nusing basic data structures and st', 'BSIT', 'First Semester', '1', '3'),
(51, 'STAT 1', 'Statistical Method', 3, '', 'BSHM', 'First Semester', '0', '3'),
(52, 'HMPE 3', 'Front Office Operation', 3, '', 'BSHM', 'First Semester', '1', '3'),
(53, 'HPC 11', 'Research in Hospitality 2', 3, '', 'BSHM', 'First Semester', '1', '4');

-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

CREATE TABLE `tbluser` (
  `user_id` varchar(225) NOT NULL,
  `Fullname` varchar(225) NOT NULL,
  `Department` varchar(225) NOT NULL,
  `username` varchar(225) DEFAULT NULL,
  `password` varchar(225) DEFAULT NULL,
  `user_type` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbluser`
--

INSERT INTO `tbluser` (`user_id`, `Fullname`, `Department`, `username`, `password`, `user_type`) VALUES
('6638ca415608b', 'Admin123', 'CCS2', 'Admin123', '$2y$10$3ieWaSVT52qSEziZb8uiN..4ldk1ncBfICvuaamxLn4rJw6SIeXje', 'admin'),
('66397b7e4112f', 'Karl Jared Opeña ', 'CCS', 'karljared', '$2y$10$lFqRwJ7Pmt/Hf81O4rqys.LmZFuhCfhxf5Zf3S39uQyzhcl6xxYgK', 'user'),
('6639907633472', 'Reymie M. Grijaldo', 'BSHM', 'reymiegrijaldo', '$2y$10$jxD6HYWw2GsOyZXqbv2ob.X29aqGnWDygp0QsvVYvzblXBz4kWCne', 'user'),
('6639934874125', 'Guanzon', 'BSHM', 'Eile1987', '$2y$10$9iqy0TrX3kcG1d70J8zRVu2VdLb9tvKPyH/k5XEYEkyxJqGfXzG/a', 'user'),
('6639972bf1e35', 'Jeen Tabanda', 'BSIT', 'jeen27', '$2y$10$a05tCmrhq37dcTPDNgCjruYc4A.3VtnWSborrYguhr9OrV4fq1S4.', 'user'),
('663998387384f', 'Art', 'BEED', 'Bootybutt', '$2y$10$3jZgEB1k2hyDtE35AxhKM.tb4udR.Ewb4psv8yvcS5ouM5pOb5r1q', 'user'),
('66399b4bce6fe', 'Mark anthony', 'BEED', 'CATACUTAN', '$2y$10$S9nGk/uAEGnuvsxTvY98R.mRIUdx8x1Wn2tT5lMXpSKtc2zNLiWDC', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_course`
--

CREATE TABLE `tbl_course` (
  `id` int(11) NOT NULL,
  `course_title` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_course`
--

INSERT INTO `tbl_course` (`id`, `course_title`) VALUES
(1, 'BSIT'),
(2, 'BSHM'),
(3, 'BSAB'),
(4, 'BEED');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_student`
--

CREATE TABLE `tbl_student` (
  `student_id` varchar(225) NOT NULL,
  `last_name` int(11) NOT NULL,
  `first_name` int(11) NOT NULL,
  `middle_name` int(11) NOT NULL,
  `age` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `section` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_subjects`
--

CREATE TABLE `tbl_subjects` (
  `id` int(11) NOT NULL,
  `course_number` varchar(225) NOT NULL,
  `course_description` varchar(225) NOT NULL,
  `user_id` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tblassesment`
--
ALTER TABLE `tblassesment`
  ADD PRIMARY KEY (`assesment_id`,`class_id`),
  ADD KEY `idx_class_id` (`class_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tblattendance`
--
ALTER TABLE `tblattendance`
  ADD PRIMARY KEY (`attendance_id`);

--
-- Indexes for table `tblclass`
--
ALTER TABLE `tblclass`
  ADD PRIMARY KEY (`class_id`);

--
-- Indexes for table `tblschedule`
--
ALTER TABLE `tblschedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblstdassesment`
--
ALTER TABLE `tblstdassesment`
  ADD PRIMARY KEY (`std_assesment_id`);

--
-- Indexes for table `tblstudents`
--
ALTER TABLE `tblstudents`
  ADD PRIMARY KEY (`student_id`,`class_id`);

--
-- Indexes for table `tblsubjects`
--
ALTER TABLE `tblsubjects`
  ADD PRIMARY KEY (`subjectID`);

--
-- Indexes for table `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `tbl_course`
--
ALTER TABLE `tbl_course`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_subjects`
--
ALTER TABLE `tbl_subjects`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tblattendance`
--
ALTER TABLE `tblattendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=285;

--
-- AUTO_INCREMENT for table `tblschedule`
--
ALTER TABLE `tblschedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tblstdassesment`
--
ALTER TABLE `tblstdassesment`
  MODIFY `std_assesment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `tblsubjects`
--
ALTER TABLE `tblsubjects`
  MODIFY `subjectID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `tbl_course`
--
ALTER TABLE `tbl_course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_subjects`
--
ALTER TABLE `tbl_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `images_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbluser` (`user_id`);

--
-- Constraints for table `tblassesment`
--
ALTER TABLE `tblassesment`
  ADD CONSTRAINT `tblassesment_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `tblclass` (`class_id`),
  ADD CONSTRAINT `tblassesment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `tbluser` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

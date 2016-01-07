-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 07, 2016 at 05:38 AM
-- Server version: 5.5.46-0ubuntu0.14.04.2
-- PHP Version: 5.6.16-3+deb.sury.org~trusty+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `rms_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `awards`
--

CREATE TABLE IF NOT EXISTS `awards` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `match_id` int(11) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `student_id` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `match_id` (`match_id`),
  KEY `student_id` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `coach`
--

CREATE TABLE IF NOT EXISTS `coach` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(30) NOT NULL,
  `second_name` varchar(30) DEFAULT NULL,
  `sport_id` int(5) NOT NULL,
  `contact_number` varchar(10) NOT NULL,
  `e_mail` varchar(50) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sport_id` (`sport_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

CREATE TABLE IF NOT EXISTS `equipment` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `equipment_category_id` int(11) DEFAULT NULL,
  `available` int(1) NOT NULL,
  `reserved` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `equipment_category` (`equipment_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `equipment_category`
--

CREATE TABLE IF NOT EXISTS `equipment_category` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `lend_time` int(2) NOT NULL,
  `name` varchar(50) NOT NULL,
  `sport_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sport` (`sport_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Store the list of equipment category' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `equipment_lend`
--

CREATE TABLE IF NOT EXISTS `equipment_lend` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `equipment_reserve_id` int(11) DEFAULT NULL,
  `lend_date` date NOT NULL,
  `due_date` date NOT NULL,
  `state` int(1) NOT NULL,
  `returned_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `equipment_reserve_id` (`equipment_reserve_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `equipment_reserve`
--

CREATE TABLE IF NOT EXISTS `equipment_reserve` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `reserved_date` date NOT NULL,
  `student_id` varchar(8) DEFAULT NULL,
  `equipment_id` int(11) DEFAULT NULL,
  `state` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`,`equipment_id`),
  KEY `equipment_id` (`equipment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `match`
--

CREATE TABLE IF NOT EXISTS `match` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `sport_id` int(11) DEFAULT NULL,
  `type` int(1) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `state` int(1) NOT NULL,
  `oppenent` varchar(100) DEFAULT NULL,
  `remarks` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sport_id` (`sport_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `match_member`
--

CREATE TABLE IF NOT EXISTS `match_member` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `match_id` int(11) DEFAULT NULL,
  `student_id` varchar(8) DEFAULT NULL,
  `remarks` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `match_id` (`match_id`),
  KEY `student_id` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `practice_schedule`
--

CREATE TABLE IF NOT EXISTS `practice_schedule` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `sport_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sport_id` (`sport_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sport`
--

CREATE TABLE IF NOT EXISTS `sport` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `minimum_age` int(2) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Store the list of sports' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `sport`
--

INSERT INTO `sport` (`id`, `name`, `gender`, `minimum_age`, `description`) VALUES
(1, 'Cricket', '0', 15, 'Cricket is a bat-and-ball game played between two teams of 11 players each on a field at the center of which is a rectangular 22-yard-long pitch.');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE IF NOT EXISTS `student` (
  `id` varchar(8) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `second_name` varchar(30) DEFAULT NULL,
  `faculty` varchar(20) NOT NULL,
  `department` varchar(30) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `birthday` date NOT NULL,
  `contact_number` varchar(10) NOT NULL,
  `e_mail` varchar(50) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Store the list of students';

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `username` varchar(10) NOT NULL,
  `password` varchar(150) NOT NULL,
  `role` varchar(20) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `password`, `role`) VALUES
('admin', '$2a$12$cyTWeE9kpq1PjqKFiWUZFuCRPwVyAZwm4XzMZ1qPUFl7/flCM3V0G', 'ROLE_ADMIN'),
('choda', '$2a$12$cyTWeE9kpq1PjqKFiWUZFuCRPwVyAZwm4XzMZ1qPUFl7/flCM3V0G', 'ROLE_COACH'),
('isuru', '$2a$12$LCY0MefVIEc3TYPHV9SNnuzOfyr2p/AXIGoQJEDs4am4JwhNz/jli', 'ROLE_STUDENT');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `awards`
--
ALTER TABLE `awards`
  ADD CONSTRAINT `awards_ibfk_1` FOREIGN KEY (`match_id`) REFERENCES `match` (`id`),
  ADD CONSTRAINT `awards_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`);

--
-- Constraints for table `equipment`
--
ALTER TABLE `equipment`
  ADD CONSTRAINT `equipment_ibfk_1` FOREIGN KEY (`equipment_category_id`) REFERENCES `equipment_category` (`id`);

--
-- Constraints for table `equipment_category`
--
ALTER TABLE `equipment_category`
  ADD CONSTRAINT `equipment_category_ibfk_1` FOREIGN KEY (`sport_id`) REFERENCES `sport` (`id`);

--
-- Constraints for table `equipment_lend`
--
ALTER TABLE `equipment_lend`
  ADD CONSTRAINT `equipment_lend_ibfk_1` FOREIGN KEY (`equipment_reserve_id`) REFERENCES `equipment_reserve` (`id`);

--
-- Constraints for table `equipment_reserve`
--
ALTER TABLE `equipment_reserve`
  ADD CONSTRAINT `equipment_reserve_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`),
  ADD CONSTRAINT `equipment_reserve_ibfk_2` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`);

--
-- Constraints for table `match`
--
ALTER TABLE `match`
  ADD CONSTRAINT `match_ibfk_1` FOREIGN KEY (`sport_id`) REFERENCES `sport` (`id`);

--
-- Constraints for table `match_member`
--
ALTER TABLE `match_member`
  ADD CONSTRAINT `match_member_ibfk_1` FOREIGN KEY (`match_id`) REFERENCES `match` (`id`),
  ADD CONSTRAINT `match_member_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`);

--
-- Constraints for table `practice_schedule`
--
ALTER TABLE `practice_schedule`
  ADD CONSTRAINT `practice_schedule_ibfk_1` FOREIGN KEY (`sport_id`) REFERENCES `sport` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

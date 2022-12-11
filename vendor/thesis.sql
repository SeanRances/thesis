-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 29, 2022 at 02:16 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `thesis`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(10) NOT NULL,
  `account_id` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `usertype` varchar(255) NOT NULL,
  `picture` varchar(255) NOT NULL,
  `account_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `account_id`, `username`, `password`, `name`, `usertype`, `picture`, `account_status`) VALUES
(1, '1234', 'aaron@gmail.com', 'MTIz', 'Aaron Marquez', 'user', '', 'ACTIVE'),
(2, '1235', 'joed@gmail.com', 'MTIz', 'John Eduard Jayag', 'admin', '62dfdacb8dafe.jpg', 'ACTIVE'),
(14, 'ACC_1656666033', 'malimo@gmail.com', 'MTIzNA==', 'Employee 1', 'user', '62f35175af877.png', 'ACTIVE'),
(15, 'ACC_1656666042', 'malimol@gmail.com', 'MTIzNDU=', 'Employee 2', 'user', '62e0cd7b918cb.jpg', 'ACTIVE'),
(16, 'ACC_1656666052', 'karina@gmail.com', 'MTIzNQ==', 'Employee 3', 'user', '', 'ACTIVE'),
(17, 'ACC_1656666064', 'wendy@gmail.com', 'MTIz', 'Employee 4', 'user', '', 'ACTIVE'),
(18, 'ACC_1656666074', 'irene@gmail.com', 'MTIz', 'Employee 5', 'user', '', 'ACTIVE'),
(31, 'ACC_1659246332', 'sean@gmail.com', 'MTIz', 'Employee 6', 'user', '', 'ACTIVE');

-- --------------------------------------------------------

--
-- Table structure for table `da_table`
--

CREATE TABLE `da_table` (
  `id` int(10) NOT NULL,
  `emp_id` varchar(255) NOT NULL,
  `emp_fullname` varchar(255) NOT NULL,
  `monthly_QA` decimal(4,2) NOT NULL,
  `monthly_CPH` decimal(4,2) NOT NULL,
  `monthly_ATT` decimal(4,2) NOT NULL,
  `monthly_score_QA` int(10) NOT NULL,
  `monthly_score_CPH` int(10) NOT NULL,
  `monthly_score_ATT` int(10) NOT NULL,
  `monthly_perf` decimal(4,2) NOT NULL,
  `perf_comment` varchar(255) NOT NULL,
  `record_month` varchar(255) NOT NULL,
  `record_year` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `da_table`
--

INSERT INTO `da_table` (`id`, `emp_id`, `emp_fullname`, `monthly_QA`, `monthly_CPH`, `monthly_ATT`, `monthly_score_QA`, `monthly_score_CPH`, `monthly_score_ATT`, `monthly_perf`, `perf_comment`, `record_month`, `record_year`) VALUES
(13, 'EMP_1656666042', 'Employee 2', '60.15', '3.12', '1.00', 1, 1, 4, '1.30', 'Underachieved', 'November', '2022');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(10) NOT NULL,
  `account_id` varchar(255) NOT NULL,
  `emp_id` varchar(255) NOT NULL,
  `emp_fullname` varchar(255) NOT NULL,
  `emp_da` int(10) NOT NULL,
  `emp_email` varchar(255) NOT NULL,
  `emp_contact` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `account_id`, `emp_id`, `emp_fullname`, `emp_da`, `emp_email`, `emp_contact`) VALUES
(33, 'ACC_1656666033', 'EMP_1656666033', 'Employee 1', 12, 'example@gmai.com', '09958556227'),
(34, 'ACC_1656666042', 'EMP_1656666042', 'Employee 2', 10, 'example@gmai.com', '09958556227'),
(35, 'ACC_1656666052', 'EMP_1656666052', 'Employee 3', 7, 'example@gmai.com', '09958556227'),
(36, 'ACC_1656666064', 'EMP_1656666064', 'Employee 4', 7, 'example@gmai.com', '09958556227'),
(37, 'ACC_1656666074', 'EMP_1656666074', 'Employee 5', 7, 'example@gmai.com', '09958556227'),
(50, 'ACC_1659246332', 'EMP_1659246332', 'Employee 6', 7, 'ajsm@gmail.com', '09958556227');

-- --------------------------------------------------------

--
-- Table structure for table `employee_goals`
--

CREATE TABLE `employee_goals` (
  `id` int(10) NOT NULL,
  `goal_id` varchar(255) NOT NULL,
  `emp_id` varchar(255) NOT NULL,
  `emp_fullname` varchar(255) NOT NULL,
  `goal_QA` int(10) NOT NULL,
  `goal_CPH` int(10) NOT NULL,
  `goal_ATT` int(10) NOT NULL,
  `start_day` varchar(255) NOT NULL,
  `start_month` varchar(255) NOT NULL,
  `start_year` varchar(255) NOT NULL,
  `end_day` varchar(255) NOT NULL,
  `end_month` varchar(255) NOT NULL,
  `end_year` varchar(255) NOT NULL,
  `goal_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `employee_goals`
--

INSERT INTO `employee_goals` (`id`, `goal_id`, `emp_id`, `emp_fullname`, `goal_QA`, `goal_CPH`, `goal_ATT`, `start_day`, `start_month`, `start_year`, `end_day`, `end_month`, `end_year`, `goal_status`) VALUES
(7, '100', 'EMP_1659246332', 'Employee 6', 3, 4, 3, '1', 'August', '2022', '31', 'August', '2022', 'Failed');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `notif_id` varchar(255) NOT NULL,
  `emp_id` varchar(255) NOT NULL,
  `notif_desc` varchar(255) NOT NULL,
  `notif_type` varchar(255) NOT NULL,
  `notif_status` varchar(255) NOT NULL,
  `record_day` varchar(255) NOT NULL,
  `record_month` varchar(255) NOT NULL,
  `record_year` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `notif_id`, `emp_id`, `notif_desc`, `notif_type`, `notif_status`, `record_day`, `record_month`, `record_year`) VALUES
(2, 'nt_1669703859', 'EMP_1656666042', 'received a disciplinary action', 'DA', 'UNREAD', '29', 'November', '2022');

-- --------------------------------------------------------

--
-- Table structure for table `performance_record`
--

CREATE TABLE `performance_record` (
  `id` int(10) NOT NULL,
  `emp_id` varchar(255) NOT NULL,
  `emp_fullname` varchar(255) NOT NULL,
  `emp_QA` decimal(4,2) NOT NULL,
  `emp_CPH` decimal(4,2) NOT NULL,
  `ds_QA` int(10) NOT NULL,
  `ds_CPH` int(10) NOT NULL,
  `emp_att` int(10) NOT NULL,
  `emp_perf` decimal(4,2) NOT NULL,
  `perf_comment` varchar(255) NOT NULL,
  `record_day` varchar(255) NOT NULL,
  `record_month` varchar(255) NOT NULL,
  `record_year` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `performance_record`
--

INSERT INTO `performance_record` (`id`, `emp_id`, `emp_fullname`, `emp_QA`, `emp_CPH`, `ds_QA`, `ds_CPH`, `emp_att`, `emp_perf`, `perf_comment`, `record_day`, `record_month`, `record_year`) VALUES
(2455, 'EMP_1656666033', 'Employee 1', '99.12', '8.23', 4, 4, 1, '4.00', 'Surpassed', '1', 'July', '2022'),
(2456, 'EMP_1656666042', 'Employee 2', '98.30', '4.23', 2, 4, 1, '3.10', 'Achieved', '1', 'July', '2022'),
(2457, 'EMP_1656666052', 'Employee 3', '98.20', '5.41', 2, 4, 1, '3.10', 'Achieved', '1', 'July', '2022'),
(2458, 'EMP_1656666064', 'Employee 4', '97.92', '5.41', 2, 4, 1, '3.10', 'Achieved', '1', 'July', '2022'),
(2459, 'EMP_1656666074', 'Employee 5', '98.33', '3.21', 1, 4, 1, '2.65', 'Achieved', '1', 'July', '2022'),
(2461, 'EMP_1656666033', 'Employee 1', '99.12', '9.12', 4, 4, 1, '4.00', 'Surpassed', '2', 'July', '2022'),
(2462, 'EMP_1656666042', 'Employee 2', '95.20', '2.23', 1, 3, 1, '2.20', 'Achieved', '2', 'July', '2022'),
(2463, 'EMP_1656666052', 'Employee 3', '93.12', '4.20', 2, 2, 1, '2.20', 'Achieved', '2', 'July', '2022'),
(2464, 'EMP_1656666064', 'Employee 4', '97.92', '5.41', 2, 4, 1, '3.10', 'Achieved', '2', 'July', '2022'),
(2465, 'EMP_1656666074', 'Employee 5', '98.33', '3.21', 1, 4, 1, '2.65', 'Achieved', '2', 'July', '2022'),
(2467, 'EMP_1656666033', 'Employee 1', '98.88', '9.21', 4, 4, 1, '4.00', 'Surpassed', '4', 'July', '2022'),
(2468, 'EMP_1656666042', 'Employee 2', '93.22', '6.27', 3, 2, 1, '2.65', 'Achieved', '4', 'July', '2022'),
(2469, 'EMP_1656666052', 'Employee 3', '92.34', '6.43', 3, 2, 1, '2.65', 'Achieved', '4', 'July', '2022'),
(2470, 'EMP_1656666064', 'Employee 4', '98.60', '5.80', 2, 4, 1, '3.10', 'Achieved', '4', 'July', '2022'),
(2471, 'EMP_1656666074', 'Employee 5', '97.26', '5.99', 2, 4, 1, '3.10', 'Achieved', '4', 'July', '2022'),
(2473, 'EMP_1656666033', 'Employee 1', '99.12', '8.23', 4, 4, 1, '4.00', 'Surpassed', '5', 'July', '2022'),
(2474, 'EMP_1656666042', 'Employee 2', '94.41', '5.34', 2, 2, 1, '2.20', 'Achieved', '5', 'July', '2022'),
(2475, 'EMP_1656666052', 'Employee 3', '92.56', '6.43', 3, 2, 1, '2.65', 'Achieved', '5', 'July', '2022'),
(2476, 'EMP_1656666064', 'Employee 4', '93.24', '4.10', 2, 2, 1, '2.20', 'Achieved', '5', 'July', '2022'),
(2477, 'EMP_1656666074', 'Employee 5', '92.61', '6.38', 3, 2, 1, '2.65', 'Achieved', '5', 'July', '2022'),
(2479, 'EMP_1656666033', 'Employee 1', '97.82', '7.93', 4, 4, 1, '4.00', 'Surpassed', '6', 'July', '2022'),
(2480, 'EMP_1656666042', 'Employee 2', '94.80', '5.16', 2, 2, 1, '2.20', 'Achieved', '6', 'July', '2022'),
(2481, 'EMP_1656666052', 'Employee 3', '94.50', '6.71', 3, 2, 1, '2.65', 'Achieved', '6', 'July', '2022'),
(2482, 'EMP_1656666064', 'Employee 4', '93.88', '6.69', 3, 2, 1, '2.65', 'Achieved', '6', 'July', '2022'),
(2483, 'EMP_1656666074', 'Employee 5', '94.04', '4.19', 2, 2, 1, '2.20', 'Achieved', '6', 'July', '2022'),
(2485, 'EMP_1656666033', 'Employee 1', '96.44', '8.99', 4, 3, 1, '3.55', 'Surpassed', '7', 'July', '2022'),
(2486, 'EMP_1656666042', 'Employee 2', '94.47', '6.62', 3, 2, 1, '2.65', 'Achieved', '7', 'July', '2022'),
(2487, 'EMP_1656666052', 'Employee 3', '93.52', '4.22', 2, 2, 1, '2.20', 'Achieved', '7', 'July', '2022'),
(2488, 'EMP_1656666064', 'Employee 4', '94.59', '6.32', 3, 2, 1, '2.65', 'Achieved', '7', 'July', '2022'),
(2489, 'EMP_1656666074', 'Employee 5', '94.06', '4.99', 2, 2, 1, '2.20', 'Achieved', '7', 'July', '2022'),
(2491, 'EMP_1656666033', 'Employee 1', '96.44', '7.55', 4, 3, 1, '3.55', 'Surpassed', '8', 'July', '2022'),
(2492, 'EMP_1656666042', 'Employee 2', '94.24', '5.30', 2, 2, 1, '2.20', 'Achieved', '8', 'July', '2022'),
(2493, 'EMP_1656666052', 'Employee 3', '92.48', '6.98', 3, 2, 1, '2.65', 'Achieved', '8', 'July', '2022'),
(2494, 'EMP_1656666064', 'Employee 4', '92.10', '5.19', 2, 2, 1, '2.20', 'Achieved', '8', 'July', '2022'),
(2495, 'EMP_1656666074', 'Employee 5', '93.79', '5.06', 2, 2, 1, '2.20', 'Achieved', '8', 'July', '2022'),
(2497, 'EMP_1656666033', 'Employee 1', '95.33', '8.11', 4, 3, 1, '3.55', 'Surpassed', '11', 'July', '2022'),
(2498, 'EMP_1656666042', 'Employee 2', '94.13', '5.81', 2, 2, 1, '2.20', 'Achieved', '11', 'July', '2022'),
(2499, 'EMP_1656666052', 'Employee 3', '92.85', '4.44', 2, 2, 1, '2.20', 'Achieved', '11', 'July', '2022'),
(2500, 'EMP_1656666064', 'Employee 4', '92.17', '4.30', 2, 2, 1, '2.20', 'Achieved', '11', 'July', '2022'),
(2501, 'EMP_1656666074', 'Employee 5', '92.80', '5.10', 2, 2, 1, '2.20', 'Achieved', '11', 'July', '2022'),
(2510, 'EMP_1656666033', 'Employee 1', '96.23', '7.12', 4, 3, 1, '3.55', 'Surpassed', '12', 'July', '2022'),
(2511, 'EMP_1656666042', 'Employee 2', '92.30', '5.10', 2, 2, 1, '2.20', 'Achieved', '12', 'July', '2022'),
(2512, 'EMP_1656666052', 'Employee 3', '93.60', '5.97', 2, 2, 1, '2.20', 'Achieved', '12', 'July', '2022'),
(2513, 'EMP_1656666064', 'Employee 4', '92.67', '6.00', 3, 2, 1, '2.65', 'Achieved', '12', 'July', '2022'),
(2514, 'EMP_1656666074', 'Employee 5', '93.56', '5.05', 2, 2, 1, '2.20', 'Achieved', '12', 'July', '2022'),
(2516, 'EMP_1656666033', 'Employee 1', '95.23', '9.85', 4, 3, 1, '3.55', 'Surpassed', '13', 'July', '2022'),
(2517, 'EMP_1656666042', 'Employee 2', '94.81', '4.47', 2, 2, 1, '2.20', 'Achieved', '13', 'July', '2022'),
(2518, 'EMP_1656666052', 'Employee 3', '92.74', '6.97', 3, 2, 1, '2.65', 'Achieved', '13', 'July', '2022'),
(2519, 'EMP_1656666064', 'Employee 4', '92.41', '6.09', 3, 2, 1, '2.65', 'Achieved', '13', 'July', '2022'),
(2520, 'EMP_1656666074', 'Employee 5', '94.48', '6.29', 3, 2, 1, '2.65', 'Achieved', '13', 'July', '2022'),
(2522, 'EMP_1656666033', 'Employee 1', '97.23', '7.10', 4, 4, 1, '4.00', 'Surpassed', '14', 'July', '2022'),
(2523, 'EMP_1656666042', 'Employee 2', '93.24', '5.16', 2, 2, 1, '2.20', 'Achieved', '14', 'July', '2022'),
(2524, 'EMP_1656666052', 'Employee 3', '92.70', '4.96', 2, 2, 1, '2.20', 'Achieved', '14', 'July', '2022'),
(2525, 'EMP_1656666064', 'Employee 4', '94.26', '5.13', 2, 2, 1, '2.20', 'Achieved', '14', 'July', '2022'),
(2526, 'EMP_1656666074', 'Employee 5', '92.61', '5.32', 2, 2, 1, '2.20', 'Achieved', '14', 'July', '2022'),
(2528, 'EMP_1656666033', 'Employee 1', '98.11', '7.12', 4, 4, 1, '4.00', 'Surpassed', '15', 'July', '2022'),
(2529, 'EMP_1656666042', 'Employee 2', '92.16', '5.58', 2, 2, 1, '2.20', 'Achieved', '15', 'July', '2022'),
(2530, 'EMP_1656666052', 'Employee 3', '93.66', '4.56', 2, 2, 1, '2.20', 'Achieved', '15', 'July', '2022'),
(2531, 'EMP_1656666064', 'Employee 4', '92.08', '5.10', 2, 2, 1, '2.20', 'Achieved', '15', 'July', '2022'),
(2532, 'EMP_1656666074', 'Employee 5', '93.58', '5.06', 2, 2, 1, '2.20', 'Achieved', '15', 'July', '2022'),
(2534, 'EMP_1656666033', 'Employee 1', '97.23', '7.11', 4, 4, 1, '4.00', 'Surpassed', '18', 'July', '2022'),
(2535, 'EMP_1656666042', 'Employee 2', '93.52', '5.60', 2, 2, 1, '2.20', 'Achieved', '18', 'July', '2022'),
(2536, 'EMP_1656666052', 'Employee 3', '93.91', '5.15', 2, 2, 1, '2.20', 'Achieved', '18', 'July', '2022'),
(2537, 'EMP_1656666064', 'Employee 4', '93.03', '6.81', 3, 2, 1, '2.65', 'Achieved', '18', 'July', '2022'),
(2538, 'EMP_1656666074', 'Employee 5', '92.65', '6.77', 3, 2, 1, '2.65', 'Achieved', '18', 'July', '2022'),
(2540, 'EMP_1656666033', 'Employee 1', '98.21', '7.21', 4, 4, 1, '4.00', 'Surpassed', '19', 'July', '2022'),
(2541, 'EMP_1656666042', 'Employee 2', '92.33', '6.70', 3, 2, 1, '2.65', 'Achieved', '19', 'July', '2022'),
(2542, 'EMP_1656666052', 'Employee 3', '94.16', '6.86', 3, 2, 1, '2.65', 'Achieved', '19', 'July', '2022'),
(2543, 'EMP_1656666064', 'Employee 4', '94.38', '4.23', 2, 2, 1, '2.20', 'Achieved', '19', 'July', '2022'),
(2544, 'EMP_1656666074', 'Employee 5', '94.58', '6.73', 3, 2, 1, '2.65', 'Achieved', '19', 'July', '2022'),
(2546, 'EMP_1656666033', 'Employee 1', '96.44', '8.99', 4, 3, 1, '3.55', 'Surpassed', '20', 'July', '2022'),
(2547, 'EMP_1656666042', 'Employee 2', '93.92', '5.68', 2, 2, 1, '2.20', 'Achieved', '20', 'July', '2022'),
(2548, 'EMP_1656666052', 'Employee 3', '92.96', '5.03', 2, 2, 1, '2.20', 'Achieved', '20', 'July', '2022'),
(2549, 'EMP_1656666064', 'Employee 4', '94.21', '6.51', 3, 2, 1, '2.65', 'Achieved', '20', 'July', '2022'),
(2550, 'EMP_1656666074', 'Employee 5', '93.07', '4.02', 2, 2, 1, '2.20', 'Achieved', '20', 'July', '2022'),
(2552, 'EMP_1656666033', 'Employee 1', '97.23', '7.44', 4, 4, 1, '4.00', 'Surpassed', '21', 'July', '2022'),
(2553, 'EMP_1656666042', 'Employee 2', '94.00', '4.23', 2, 2, 1, '2.20', 'Achieved', '21', 'July', '2022'),
(2554, 'EMP_1656666052', 'Employee 3', '93.79', '5.41', 2, 2, 1, '2.20', 'Achieved', '21', 'July', '2022'),
(2555, 'EMP_1656666064', 'Employee 4', '93.06', '5.41', 2, 2, 1, '2.20', 'Achieved', '21', 'July', '2022'),
(2556, 'EMP_1656666074', 'Employee 5', '93.79', '5.23', 2, 2, 1, '2.20', 'Achieved', '21', 'July', '2022'),
(2558, 'EMP_1656666033', 'Employee 1', '96.54', '8.33', 4, 3, 1, '3.55', 'Surpassed', '22', 'July', '2022'),
(2559, 'EMP_1656666042', 'Employee 2', '94.51', '4.60', 2, 2, 1, '2.20', 'Achieved', '22', 'July', '2022'),
(2560, 'EMP_1656666052', 'Employee 3', '92.85', '5.68', 2, 2, 1, '2.20', 'Achieved', '22', 'July', '2022'),
(2561, 'EMP_1656666064', 'Employee 4', '92.21', '6.99', 3, 2, 1, '2.65', 'Achieved', '22', 'July', '2022'),
(2562, 'EMP_1656666074', 'Employee 5', '94.45', '5.59', 2, 2, 1, '2.20', 'Achieved', '22', 'July', '2022'),
(2564, 'EMP_1656666033', 'Employee 1', '98.29', '8.33', 4, 4, 1, '4.00', 'Surpassed', '25', 'July', '2022'),
(2565, 'EMP_1656666042', 'Employee 2', '92.38', '5.34', 2, 2, 1, '2.20', 'Achieved', '25', 'July', '2022'),
(2566, 'EMP_1656666052', 'Employee 3', '92.70', '4.68', 2, 2, 1, '2.20', 'Achieved', '25', 'July', '2022'),
(2567, 'EMP_1656666064', 'Employee 4', '93.53', '4.68', 2, 2, 1, '2.20', 'Achieved', '25', 'July', '2022'),
(2568, 'EMP_1656666074', 'Employee 5', '94.52', '6.23', 3, 2, 1, '2.65', 'Achieved', '25', 'July', '2022'),
(2570, 'EMP_1656666033', 'Employee 1', '96.17', '7.91', 4, 3, 1, '3.55', 'Surpassed', '26', 'July', '2022'),
(2571, 'EMP_1656666042', 'Employee 2', '92.46', '4.18', 2, 2, 1, '2.20', 'Achieved', '26', 'July', '2022'),
(2572, 'EMP_1656666052', 'Employee 3', '92.47', '6.41', 3, 2, 1, '2.65', 'Achieved', '26', 'July', '2022'),
(2573, 'EMP_1656666064', 'Employee 4', '92.96', '6.59', 3, 2, 1, '2.65', 'Achieved', '26', 'July', '2022'),
(2574, 'EMP_1656666074', 'Employee 5', '92.97', '6.82', 3, 2, 1, '2.65', 'Achieved', '26', 'July', '2022'),
(2576, 'EMP_1656666033', 'Employee 1', '95.55', '8.77', 4, 3, 1, '3.55', 'Surpassed', '27', 'July', '2022'),
(2577, 'EMP_1656666042', 'Employee 2', '94.15', '4.37', 2, 2, 1, '2.20', 'Achieved', '27', 'July', '2022'),
(2578, 'EMP_1656666052', 'Employee 3', '94.28', '4.31', 2, 2, 1, '2.20', 'Achieved', '27', 'July', '2022'),
(2579, 'EMP_1656666064', 'Employee 4', '93.44', '6.41', 3, 2, 1, '2.65', 'Achieved', '27', 'July', '2022'),
(2580, 'EMP_1656666074', 'Employee 5', '94.31', '5.16', 2, 2, 1, '2.20', 'Achieved', '27', 'July', '2022'),
(2582, 'EMP_1656666033', 'Employee 1', '95.12', '8.11', 4, 3, 1, '3.55', 'Surpassed', '28', 'July', '2022'),
(2583, 'EMP_1656666042', 'Employee 2', '92.57', '4.45', 2, 2, 1, '2.20', 'Achieved', '28', 'July', '2022'),
(2584, 'EMP_1656666052', 'Employee 3', '93.40', '5.47', 2, 2, 1, '2.20', 'Achieved', '28', 'July', '2022'),
(2585, 'EMP_1656666064', 'Employee 4', '92.72', '4.84', 2, 2, 1, '2.20', 'Achieved', '28', 'July', '2022'),
(2586, 'EMP_1656666074', 'Employee 5', '93.85', '6.87', 3, 2, 1, '2.65', 'Achieved', '28', 'July', '2022'),
(2588, 'EMP_1656666033', 'Employee 1', '97.21', '7.44', 4, 4, 1, '4.00', 'Surpassed', '29', 'July', '2022'),
(2589, 'EMP_1656666042', 'Employee 2', '93.58', '5.77', 2, 2, 1, '2.20', 'Achieved', '29', 'July', '2022'),
(2590, 'EMP_1656666052', 'Employee 3', '93.53', '5.20', 2, 2, 1, '2.20', 'Achieved', '29', 'July', '2022'),
(2591, 'EMP_1656666064', 'Employee 4', '93.62', '5.88', 2, 2, 1, '2.20', 'Achieved', '29', 'July', '2022'),
(2592, 'EMP_1656666074', 'Employee 5', '94.96', '4.66', 2, 2, 1, '2.20', 'Achieved', '29', 'July', '2022'),
(2599, 'EMP_1656666033', 'Employee 1', '99.12', '8.23', 4, 4, 1, '4.00', 'Surpassed', '31', 'July', '2022'),
(2600, 'EMP_1656666042', 'Employee 2', '98.30', '4.23', 4, 2, 1, '3.10', 'Achieved', '31', 'July', '2022'),
(2601, 'EMP_1656666052', 'Employee 3', '98.20', '5.41', 4, 2, 1, '3.10', 'Achieved', '31', 'July', '2022'),
(2602, 'EMP_1656666064', 'Employee 4', '97.92', '5.41', 4, 2, 1, '3.10', 'Achieved', '31', 'July', '2022'),
(2603, 'EMP_1656666074', 'Employee 5', '98.33', '3.21', 4, 1, 1, '2.65', 'Achieved', '31', 'July', '2022'),
(2604, 'EMP_1659246332', 'Employee 6', '83.22', '4.09', 1, 2, 1, '1.75', 'Underachieved', '31', 'July', '2022'),
(2699, 'EMP_1656666033', 'Employee 1', '99.12', '8.23', 4, 4, 1, '4.00', 'Surpassed', '10', 'August', '2022'),
(2700, 'EMP_1656666042', 'Employee 2', '98.30', '4.23', 4, 2, 1, '3.10', 'Achieved', '10', 'August', '2022'),
(2701, 'EMP_1656666052', 'Employee 3', '98.20', '5.41', 4, 2, 1, '3.10', 'Achieved', '10', 'August', '2022'),
(2702, 'EMP_1656666064', 'Employee 4', '97.92', '5.41', 4, 2, 1, '3.10', 'Achieved', '10', 'August', '2022'),
(2703, 'EMP_1656666074', 'Employee 5', '98.33', '3.21', 4, 1, 1, '2.65', 'Achieved', '10', 'August', '2022'),
(2704, 'EMP_1659246332', 'Employee 6', '81.22', '4.09', 1, 2, 1, '1.75', 'Underachieved', '10', 'August', '2022'),
(2705, 'EMP_1656666033', 'Employee 1', '99.12', '8.23', 4, 4, 1, '4.00', 'Surpassed', '26', 'November', '2022'),
(2706, 'EMP_1656666042', 'Employee 2', '98.30', '4.23', 4, 2, 1, '3.10', 'Achieved', '26', 'November', '2022'),
(2707, 'EMP_1656666052', 'Employee 3', '98.20', '5.41', 4, 2, 1, '3.10', 'Achieved', '26', 'November', '2022'),
(2708, 'EMP_1656666064', 'Employee 4', '97.92', '5.41', 4, 2, 1, '3.10', 'Achieved', '26', 'November', '2022'),
(2709, 'EMP_1656666074', 'Employee 5', '98.33', '3.21', 4, 1, 1, '2.65', 'Achieved', '26', 'November', '2022'),
(2710, 'EMP_1659246332', 'Employee 6', '83.22', '4.09', 1, 2, 1, '1.75', 'Underachieved', '26', 'November', '2022'),
(2726, 'EMP_1656666033', 'Employee 1', '99.00', '7.00', 4, 4, 1, '4.00', 'Surpassed', '29', 'November', '2022'),
(2727, 'EMP_1656666042', 'Employee 2', '22.00', '2.00', 1, 1, 1, '1.30', 'Underachieved', '29', 'November', '2022');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`),
  ADD KEY `password` (`password`),
  ADD KEY `name` (`name`),
  ADD KEY `usertype` (`usertype`),
  ADD KEY `emp_id` (`account_id`);

--
-- Indexes for table `da_table`
--
ALTER TABLE `da_table`
  ADD PRIMARY KEY (`id`),
  ADD KEY `da_owner` (`emp_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emp_id` (`emp_id`,`emp_fullname`,`emp_da`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `employee_goals`
--
ALTER TABLE `employee_goals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goal_owner` (`emp_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `performance_record`
--
ALTER TABLE `performance_record`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emp_id` (`emp_id`,`emp_fullname`,`emp_QA`),
  ADD KEY `emp_CPH` (`emp_CPH`,`ds_QA`,`ds_CPH`),
  ADD KEY `emp_att` (`emp_att`,`emp_perf`,`perf_comment`),
  ADD KEY `record_day` (`record_day`,`record_month`,`record_year`),
  ADD KEY `employee_name_record` (`emp_fullname`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `da_table`
--
ALTER TABLE `da_table`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `employee_goals`
--
ALTER TABLE `employee_goals`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `performance_record`
--
ALTER TABLE `performance_record`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2728;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `da_table`
--
ALTER TABLE `da_table`
  ADD CONSTRAINT `da_owner` FOREIGN KEY (`emp_id`) REFERENCES `employees` (`emp_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `account_owner` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employee_goals`
--
ALTER TABLE `employee_goals`
  ADD CONSTRAINT `goal_owner` FOREIGN KEY (`emp_id`) REFERENCES `employees` (`emp_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `performance_record`
--
ALTER TABLE `performance_record`
  ADD CONSTRAINT `employee_name_record` FOREIGN KEY (`emp_fullname`) REFERENCES `accounts` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `record_owner` FOREIGN KEY (`emp_id`) REFERENCES `employees` (`emp_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

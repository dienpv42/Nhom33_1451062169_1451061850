-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 14, 2017 lúc 05:47 CH
-- Phiên bản máy phục vụ: 10.1.21-MariaDB
-- Phiên bản PHP: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `ticket`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `booking`
--

CREATE TABLE `booking` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `movie_id` int(11) DEFAULT NULL,
  `day` date DEFAULT NULL,
  `time` varchar(50) DEFAULT NULL,
  `seat` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `calendar`
--

CREATE TABLE `calendar` (
  `id` int(11) NOT NULL,
  `movie_id` int(11) DEFAULT NULL,
  `day` date DEFAULT NULL,
  `time` varchar(50) DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `calendar`
--

INSERT INTO `calendar` (`id`, `movie_id`, `day`, `time`, `created`) VALUES
(10, 2, '2017-05-11', '09:00', '2017-05-11 02:28:30'),
(11, 2, '2017-05-11', '10:00', '2017-05-11 02:28:30'),
(12, 2, '2017-05-11', '11:30', '2017-05-11 02:28:30'),
(13, 2, '2017-05-11', '09:30', '2017-05-11 02:28:30'),
(14, 2, '2017-05-11', '12:00', '2017-05-11 02:28:30'),
(15, 2, '2017-05-11', '13:30', '2017-05-11 02:28:30'),
(16, 2, '2017-05-11', '09:00', '2017-05-11 02:28:30'),
(17, 2, '2017-05-11', '09:00', '2017-05-11 02:28:30'),
(18, 2, '2017-05-11', '13:00', '2017-05-11 02:28:30');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `movie`
--

CREATE TABLE `movie` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `director` varchar(255) DEFAULT NULL,
  `actor` varchar(255) DEFAULT NULL,
  `language` varchar(255) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `description` text,
  `open_date` date DEFAULT NULL,
  `trial_url` varchar(50) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `movie`
--

INSERT INTO `movie` (`id`, `title`, `duration`, `director`, `actor`, `language`, `country`, `category_id`, `description`, `open_date`, `trial_url`, `created`, `modified`) VALUES
(1, 'AAA', '90', 'long', 'jadsj sajdsaj', 'vietnam', 'AU', NULL, 'sajdhaskjdasllllllldkasdljas', '2017-05-12', 'watch?v=zd3aLG5RS1w', '2017-05-11 02:24:33', '2017-05-11 02:24:33'),
(2, 'Long', '90', 'long', 'assadsa', 'viet nam', 'AR', 1, 'fghfhhfg', '2017-05-25', 'watch?v=zd3aLG5RS1w', '2017-05-11 02:26:04', '2017-05-11 02:26:04');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `movie_category`
--

CREATE TABLE `movie_category` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `movie_category`
--

INSERT INTO `movie_category` (`id`, `title`, `created`, `modified`) VALUES
(1, 'Hanh Dong', '2017-05-11 02:25:17', '2017-05-11 02:25:17'),
(2, 'Tinh Cam', '2017-05-11 02:25:27', '2017-05-11 02:25:27');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `is_admin` tinyint(4) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `fullname`, `address`, `is_admin`, `created`, `modified`) VALUES
(26200, 'admin@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'admin', 'ton that tung', 1, '2017-05-11 00:59:18', '2017-05-11 00:59:18'),
(26201, 'longtq42@wru.vn', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'long', 'sadajskkdas', NULL, '2017-05-11 02:22:28', '2017-05-11 02:22:28');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `movie_id` (`movie_id`);

--
-- Chỉ mục cho bảng `calendar`
--
ALTER TABLE `calendar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `movie_id` (`movie_id`);

--
-- Chỉ mục cho bảng `movie`
--
ALTER TABLE `movie`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `movie_category`
--
ALTER TABLE `movie_category`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT cho bảng `calendar`
--
ALTER TABLE `calendar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT cho bảng `movie`
--
ALTER TABLE `movie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT cho bảng `movie_category`
--
ALTER TABLE `movie_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT cho bảng `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26211;
--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`movie_id`) REFERENCES `movie` (`id`);

--
-- Các ràng buộc cho bảng `calendar`
--
ALTER TABLE `calendar`
  ADD CONSTRAINT `calendar_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movie` (`id`);

--
-- Các ràng buộc cho bảng `movie`
--
ALTER TABLE `movie`
  ADD CONSTRAINT `movie_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `movie_category` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

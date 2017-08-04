-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 04, 2017 at 10:39 AM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 7.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `epic-empires`
--

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` text,
  `loc_x` int(11) NOT NULL,
  `loc_y` int(11) NOT NULL,
  `points` int(11) NOT NULL DEFAULT '0',
  `level` int(11) NOT NULL DEFAULT '1',
  `r_food` int(11) NOT NULL DEFAULT '400',
  `r_wood` int(11) NOT NULL DEFAULT '400',
  `r_gold` int(11) NOT NULL DEFAULT '400',
  `r_workers` int(11) NOT NULL DEFAULT '10',
  `b_center` int(11) NOT NULL DEFAULT '1',
  `b_barracks` int(11) NOT NULL DEFAULT '0',
  `b_academy` int(11) NOT NULL DEFAULT '0',
  `b_house` int(11) NOT NULL DEFAULT '1',
  `units` int(11) NOT NULL DEFAULT '2',
  `archers` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `user_id`, `name`, `loc_x`, `loc_y`, `points`, `level`, `r_food`, `r_wood`, `r_gold`, `r_workers`, `b_center`, `b_barracks`, `b_academy`, `b_house`, `units`, `archers`) VALUES
(9, 2, NULL, 1, 1, 1620, 1, 0, 100, 0, 7, 1, 1, 1, 3, 0, 0),
(17, 3, NULL, 2, 1, 250, 2, 8000, 5000, 2300, 12, 2, 0, 1, 1, 0, 0),
(19, 5, NULL, 1, 2, 0, 1, 0, 500, 100, 10, 1, 0, 0, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `user_id`, `city_id`, `title`, `content`, `time`) VALUES
(67, 3, 17, 'You won the attack against artur99', '<p>Attacker:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>100 -&gt; 2734</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p><p>Defender:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>120 -&gt; 0</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>30 -&gt; 0</span> <br></p>', 1501832907),
(68, 2, 9, 'You have been attacked by testtest', '<p>Attacker:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>100 -&gt; 2734</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p><p>Defender:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>120 -&gt; 0</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>30 -&gt; 0</span> <br></p>', 1501832907),
(69, 3, 17, 'You won the attack against artur99', '<p>Attacker:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>90 -&gt; 2793</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p><p>Defender:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>120 -&gt; 0</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>30 -&gt; 0</span> <br></p>', 1501833072),
(70, 2, 9, 'You have been attacked by testtest', '<p>Attacker:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>90 -&gt; 2793</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p><p>Defender:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>120 -&gt; 0</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>30 -&gt; 0</span> <br></p>', 1501833072),
(71, 3, 17, 'You won the attack against artur99', '<p>Attacker:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>10 -&gt; 10</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p><p>Defender:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>0 -&gt; 0</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p>', 1501833074),
(72, 2, 9, 'You have been attacked by testtest', '<p>Attacker:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>10 -&gt; 10</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p><p>Defender:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>0 -&gt; 0</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p>', 1501833074),
(73, 3, 17, 'You lost your attack on artur99', '<p>Attacker:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>500 -&gt; 0</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p><p>Defender:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>600 -&gt; 100</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p>', 1501833156),
(74, 2, 9, 'You resisted the attack from testtest', '<p>Attacker:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>500 -&gt; 0</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p><p>Defender:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>600 -&gt; 100</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p>', 1501833156),
(75, 3, 17, 'You won the attack against artur99', '<p>Attacker:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>30 -&gt; 285</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p><p>Defender:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>40 -&gt; 0</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>10 -&gt; 0</span> <br></p>', 1501833344),
(76, 2, 9, 'You have been attacked by testtest', '<p>Attacker:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>30 -&gt; 285</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p><p>Defender:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>40 -&gt; 0</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>10 -&gt; 0</span> <br></p>', 1501833344),
(77, 3, 17, 'You lost your attack on artur99', '<p>Attacker:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>30 -&gt; 0</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p><p>Defender:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>35 -&gt; 35</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>4 -&gt; 4</span> <br></p>', 1501833488),
(78, 2, 9, 'You resisted the attack from testtest', '<p>Attacker:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>30 -&gt; 0</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p><p>Defender:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>35 -&gt; 35</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>4 -&gt; 4</span> <br></p>', 1501833488),
(79, 3, 17, 'You lost your attack on artur99', '<p>Attacker:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>30 -&gt; 0</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p><p>Defender:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>35 -&gt; 35</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>4 -&gt; 4</span> <br></p>', 1501833566),
(80, 2, 9, 'You resisted the attack from testtest', '<p>Attacker:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>30 -&gt; 0</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p><p>Defender:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>35 -&gt; 35</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>4 -&gt; 4</span> <br></p>', 1501833566),
(81, 3, 17, 'You lost your attack on artur99', '<p>Attacker:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>30 -&gt; 0</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p><p>Defender:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>35 -&gt; 35</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>4 -&gt; 4</span> <br></p>', 1501833800),
(82, 2, 9, 'You resisted the attack from testtest', '<p>Attacker:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>30 -&gt; 0</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p><p>Defender:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>35 -&gt; 35</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>4 -&gt; 4</span> <br></p>', 1501833800),
(83, 3, 17, 'You won the attack against artur99', '<p>Attacker:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>100 -&gt; 1434</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p><p>Defender:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>90 -&gt; 0</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>20 -&gt; 0</span> <br></p>', 1501833898),
(84, 2, 9, 'You have been attacked by testtest', '<p>Attacker:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>100 -&gt; 1434</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p><p>Defender:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>90 -&gt; 0</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>20 -&gt; 0</span> <br></p>', 1501833898),
(85, 3, 17, 'You won the attack against artur99', '<p>Attacker:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>100 -&gt; 22962</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p><p>Defender:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>1524 -&gt; 0</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>20 -&gt; 0</span> <br></p>', 1501833898),
(86, 2, 9, 'You have been attacked by testtest', '<p>Attacker:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>100 -&gt; 22962</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p><p>Defender:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>1524 -&gt; 0</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>20 -&gt; 0</span> <br></p>', 1501833898),
(87, 3, 17, 'You won the attack against artur99', '<p>Attacker:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>140 -&gt; 1870</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p><p>Defender:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>120 -&gt; 0</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>20 -&gt; 0</span> <br></p>', 1501833963),
(88, 2, 9, 'You have been attacked by testtest', '<p>Attacker:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>140 -&gt; 1870</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p><p>Defender:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>120 -&gt; 0</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>20 -&gt; 0</span> <br></p>', 1501833963),
(89, 2, 9, 'You lost your attack on testtest', '<p>Attacker:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>10 -&gt; 0</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p><p>Defender:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>10 -&gt; 10</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p>', 1501835110),
(90, 3, 17, 'You resisted the attack from artur99', '<p>Attacker:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>10 -&gt; 0</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p><p>Defender:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>10 -&gt; 10</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p>', 1501835110),
(91, 4, 18, 'You won the attack against artur99', '<p>Attacker:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>1 -&gt; 1</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p><p>Defender:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>0 -&gt; 0</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p>', 1501835461),
(92, 2, 9, 'You have been attacked by testtest2', '<p>Attacker:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>1 -&gt; 1</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p><p>Defender:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>0 -&gt; 0</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p>', 1501835461),
(93, 5, 19, 'You lost your attack on artur99', '<p>Attacker:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>1 -&gt; 0</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p><p>Defender:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>1 -&gt; 1</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p>', 1501835643),
(94, 2, 9, 'You resisted the attack from testtest2', '<p>Attacker:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>1 -&gt; 0</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p><p>Defender:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>1 -&gt; 1</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p>', 1501835643),
(95, 2, 9, 'You lost your attack on testtest2', '<p>Attacker:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>1 -&gt; 0</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p><p>Defender:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>1 -&gt; 1</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p>', 1501835876),
(96, 5, 19, 'You resisted the attack from artur99', '<p>Attacker:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>1 -&gt; 0</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p><p>Defender:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>1 -&gt; 1</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p>', 1501835876),
(97, 2, 9, 'You won the attack against testtest2', '<p>Attacker:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>1 -&gt; 1</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p><p>Defender:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>0 -&gt; 0</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p>', 1501835876),
(98, 5, 19, 'You have been attacked by artur99', '<p>Attacker:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>1 -&gt; 1</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p><p>Defender:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>0 -&gt; 0</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p>', 1501835876),
(99, 5, 19, 'You lost your attack on artur99', '<p>Attacker:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>11 -&gt; 1</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p><p>Defender:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>10 -&gt; 9</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p>', 1501835930),
(100, 2, 9, 'You resisted the attack from testtest2', '<p>Attacker:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>11 -&gt; 1</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p><p>Defender:<br><img src="/assets/img/items/res_unit.png" alt="" class="res-img unit-icon" title="Terrain Troops"> <span>10 -&gt; 9</span> <br><img src="/assets/img/items/res_archer.png" alt="" class="res-img unit-icon" title="Archer Troops"> <span>0 -&gt; 0</span> <br></p>', 1501835930);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `type` varchar(32) NOT NULL,
  `city_id` int(11) NOT NULL,
  `workers` int(11) NOT NULL DEFAULT '0',
  `target` int(11) DEFAULT NULL,
  `time_s` int(11) NOT NULL,
  `time_e` int(11) NOT NULL,
  `param` text NOT NULL,
  `result` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(32) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` text NOT NULL,
  `reg_tm` int(11) DEFAULT NULL,
  `log_tm` int(15) DEFAULT NULL,
  `reg_ip` varchar(15) DEFAULT NULL,
  `log_ip` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `reg_tm`, `log_tm`, `reg_ip`, `log_ip`) VALUES
(2, 'artur99', 'david1989mail@yahoo.com', '23c02fd3784fe77479e04c93a1f08026b13674194912b2720d', 1501753609, 1501811269, '127.0.0.1', '127.0.0.1'),
(3, 'testtest', 'test@mail.com', '125d6d03b32c84d492747f79cf0bf6e179d287f341384eb5d6', 1501768451, 1501811269, '127.0.0.1', '127.0.0.1'),
(5, 'testtest2', 'testtest2@mail.com', '7de44b82d4a8eb039ed55aac63c92d3735e14b44020c943306', 1501835627, 1501835627, '127.0.0.1', '127.0.0.1');

-- --------------------------------------------------------

--
-- Table structure for table `vars`
--

CREATE TABLE `vars` (
  `id` varchar(32) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `vars`
--

INSERT INTO `vars` (`id`, `value`) VALUES
('last_x', '1'),
('last_y', '2'),
('map_size', '50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `city_id` (`city_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `city_id` (`city_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `vars`
--
ALTER TABLE `vars`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;
--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

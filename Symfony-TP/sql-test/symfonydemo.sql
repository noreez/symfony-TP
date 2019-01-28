-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Hôte : database
-- Généré le :  Dim 27 jan. 2019 à 23:59
-- Version du serveur :  5.6.43
-- Version de PHP :  7.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `symfonydemo`
--

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id`, `title`, `description`) VALUES
(1, 'Best Actions', 'In this category, you have the best actions of rocket league');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthday` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:simple_array)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `firstname`, `lastname`, `email`, `birthday`, `roles`, `password`) VALUES
(1, 'Toto', 'Tata', 'toto@gmail.com', '2014-03-15 10:00:00', 'ROLE_USER', '$2y$13$dpNZP/c.2et2HByaF7W87ekma4jnG92xp1iafg2kkYmKYdc4.qJqm'),
(2, NULL, NULL, 'admin@gmail.com', NULL, 'ROLE_ADMIN', '$2y$13$6jo8M05vKG7.Hrpwci.xaeNo5qQj/.C6cihUgVhHJ8wc96veCqDIW'),
(3, 'Titi', 'trtr', 'titi@gmail.com', '2014-03-01 00:00:00', 'ROLE_USER', '$2y$13$INFgtZoTz.50rvoYI6FB.OoZ/NNdqs3sBP5V.UESlee4P/fRDM8Ze');

-- --------------------------------------------------------

--
-- Structure de la table `video`
--

CREATE TABLE `video` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `finalurl` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `video`
--

INSERT INTO `video` (`id`, `user_id`, `category_id`, `title`, `created_at`, `published`, `url`, `description`, `finalurl`) VALUES
(1, 1, 1, 'Rocket league First Part', '2014-01-28 00:00:00', 1, 'https://www.youtube.com/watch?v=bjx6IPJCXaA', 'Hi, you can see my video, enjoy ;)', 'https://www.youtube.com/embed/bjx6IPJCXaA'),
(2, 1, 1, 'Rocket league Second Part', '2014-01-28 00:00:00', 1, 'https://www.youtube.com/watch?v=hplpw-f-8rM', 'Hi, this is the second part, Enjoy :)', 'https://www.youtube.com/embed/hplpw-f-8rM'),
(3, 1, 1, 'Preparation Video', '2014-01-31 00:00:00', 0, 'https://www.youtube.com/watch?v=4vWOZzGZ0bM', 'Hi, this video is in preparation', 'https://www.youtube.com/embed/4vWOZzGZ0bM'),
(4, 3, 1, 'Rocket league - Best Moment', '2014-01-01 00:00:00', 1, 'https://www.youtube.com/watch?v=4vWOZzGZ0bM', 'This is the best moments, Like', 'https://www.youtube.com/embed/4vWOZzGZ0bM');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_64C19C12B36786B` (`title`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- Index pour la table `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_7CC7DA2C2B36786B` (`title`),
  ADD KEY `IDX_7CC7DA2CA76ED395` (`user_id`),
  ADD KEY `IDX_7CC7DA2C12469DE2` (`category_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `video`
--
ALTER TABLE `video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `video`
--
ALTER TABLE `video`
  ADD CONSTRAINT `FK_7CC7DA2C12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `FK_7CC7DA2CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

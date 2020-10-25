CREATE DATABASE spa;
USE spa;
--
-- Structure de la table `adopter`
--

CREATE TABLE `adopter` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `birthday` date NOT NULL,
  `address` longtext NOT NULL
);

-- --------------------------------------------------------

--
-- Structure de la table `animal`
--

CREATE TABLE `animal` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `race_id` int(11) DEFAULT NULL,
  `adopter_id` int(11) DEFAULT NULL,
  `refuge_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `tattoo` int(11) NOT NULL,
  `is_sterilized` tinyint(1) NOT NULL,
  `is_vaccined` tinyint(1) NOT NULL,
  `adopted_on` datetime DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Structure de la table `race`
--

CREATE TABLE `race` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `species_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
);

-- --------------------------------------------------------

--
-- Structure de la table `refuge`
--

CREATE TABLE `refuge` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` longtext NOT NULL
);

-- --------------------------------------------------------

--
-- Structure de la table `species`
--

CREATE TABLE `species` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `name` varchar(255) NOT NULL
);

--
-- FK pour la table `animal`
--
ALTER TABLE animal ADD CONSTRAINT FK_animal_race FOREIGN KEY (race_id) REFERENCES race (id);
ALTER TABLE animal ADD CONSTRAINT FK_animal_adopter FOREIGN KEY (adopter_id) REFERENCES adopter (id);
ALTER TABLE animal ADD CONSTRAINT FK_animal_refuge FOREIGN KEY (refuge_id) REFERENCES refuge (id);
--
-- FK pour la table `race`
--
ALTER TABLE race ADD CONSTRAINT FK_race_species FOREIGN KEY (species_id) REFERENCES species (id);

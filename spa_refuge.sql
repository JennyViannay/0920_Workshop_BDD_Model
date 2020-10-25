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

--
-- Init 2 refuges
--

INSERT INTO refuge (`name`, `address`) VALUES ('SPA Paris', 'La Fayette, Paris 10');
INSERT INTO refuge (`name`, `address`) VALUES ('SPA Marseille', 'Jaures, Marseille 4');

--
-- Init 2 images
--

CREATE TABLE `image` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `animal_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL
);

ALTER TABLE image ADD CONSTRAINT FK_image_animal FOREIGN KEY (animal_id) REFERENCES animal (id);


--
-- Add user
--

CREATE TABLE `user` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
);

CREATE TABLE `role` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `name` varchar(255) NOT NULL
);

ALTER TABLE user ADD role_id INT NOT NULL;

ALTER TABLE user ADD adopter_id INT NULL;

ALTER TABLE user ADD CONSTRAINT FK_user_role FOREIGN KEY (role_id) REFERENCES role (id);
ALTER TABLE user ADD CONSTRAINT FK_user_adopter FOREIGN KEY (adopter_id) REFERENCES adopter (id);

INSERT INTO role (`name`) VALUES ('ADMIN') , ('USER');


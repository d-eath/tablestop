SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Jeux de table'),
(2, 'Jeux de cartes'),
(3, 'Jeux de cartes à collectionner'),
(4, 'Jeux de rôle (RPG)'),
(5, 'Équipements de jeux');

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `username` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `customers` (`id`, `username`, `first_name`, `last_name`, `gender`, `address`, `city`, `province`, `postal_code`, `phone_number`, `email`, `password`) VALUES
(0, 'admin', 'Administrateur', 'tablestop', '', '', '', '', '', '', '', '$2y$10$Vn/L8YiPxVVL7A9mAjZ4UeEKf2AVrPnb74Rp8Ha9vg/ZbwqCw.VQy');

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `total` decimal(7,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `backorder_quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(7,2) NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `inventory_stock` int(11) NOT NULL,
  `min_restock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `products` (`id`, `category_id`, `title`, `price`, `description`, `inventory_stock`, `min_restock`) VALUES
(1, 1, 'Monopoly — Le jeu de transactions rapides de propriétés', '21.99', 'À Monopoly, les joueurs achètent, vendent et échangent des propriétés pour gagner. Pour tout posséder, il faut mettre tous ses adversaires en faillite !\r\n\r\nPour 2 à 8 joueurs de 8 ans et plus.', 14, 5),
(2, 1, 'Sorry! — Le jeu classique de la douce revanche !', '21.99', 'À Sorry, chaque joueur obtient 4 pions pour se déplacer autour du jeu. Les joueurs doivent choisir une carte 1 ou 2 pour obtenir un pion de la zone de départ, puis défier les adversaires dans ce jeu classique de vengeance ! Soyez le premier joueur à obtenir les 4 pions à la base à domicile pour gagner.\r\n\r\nPour 2 à 4 joueurs de 6 ans et plus.', 8, 5),
(3, 1, 'Risk — Le jeu de conquête stratégique', '33.49', 'Le but du jeu Risk est simple : les joueurs tentent de conquérir les territoires ennemis en montant leur armée, en mobilisant leurs troupes et en engageant des combats dont le dénouement, victoire ou défaite, sera déterminé par les dés.\r\n\r\nPour 2 à 5 joueurs de 10 ans et plus.', 0, 5),
(4, 1, 'Labyrinthe — Chasse aux trésors dans un labyrinthe en mouvement', '41.79', 'Une palpitante chasse aux trésors dans un labyrinthe en mouvement ! Un dédale de couloirs que l\'on décale à sa guise, pour retrouver le plus de trésors et de secrets possible. Le plus adroit à déplacer les couloirs sera le gagnant.\r\n\r\nPour 2 à 4 joueurs de 8 ans et plus.', 5, 5),
(5, 1, 'Destins — Le jeu de la vie', '29.99', 'À Destins, les joueurs font leurs propres choix sur le chemin sinueux de la vie. Tout au long du parcours qui les mènera du point de départ à la retraite, ils iront de surprise en surprise en découvrant ce que le destin leur réserve en matière de famille, de carrière et pour d\'autres étapes importantes de la vie.\r\n\r\nPour 2 à 4 joueurs de 8 ans et plus.', 16, 5),
(6, 1, 'Scrabble (version française)', '20.99', 'La confrontation mot à mot est sur le point de commencer ! Il suffit de rassembler des lettres, former des mots et faire des points pour remporter la partie.\r\n\r\nPour 2 à 4 joueurs de 10 ans et plus.', 0, 5),
(7, 1, 'Jenga (classique)', '21.49', 'Voici le jeu classique de blocs à empiler et de tour à démolir de Jenga ! L\'ennemi : la loi de la gravité ! Il faut ériger une tour avec les blocs de bois, puis retirer les blocs, un à la fois, jusqu\'à ce que la tour s\'effondre. Le joueur qui aura la main assez adroite pour être le dernier à retirer un bloc sans provoquer l\'effondrement sera le grand gagnant à Jenga !\r\n\r\nPour 1 joueur et plus de 6 ans et plus.', 11, 5),
(8, 1, 'Jeu d\'échecs', '99.99', 'Le jeu de société classique et stratégique depuis 1475.\r\n\r\nCette planche de jeu d\'échecs est faite en bois de hêtre et de bouleau. Elle peut être pliée et les pièces d\'échec peuvent être stockées à l\'intérieur de la planche lorsqu\'elle est pliée.\r\n\r\nPour 2 joueurs de 6 ans et plus.', 1, 1),
(9, 2, 'UNO', '7.49', 'Le jeu de carte le plus populaire pour la famille !\r\n\r\nSoyez le premier joueur ou la première équipe à amasser 500 points. Pour accumuler des points, il faut se débarrasser de toutes ses cartes avant son ou ses adversaires. Jouez les cartes de votre jeu en associant les couleurs ou les chiffres avec ceux de la carte qui se trouve sur le dessus de la pile. Quand il ne vous reste plus qu\'une carte en main, criez UNO.\r\n\r\nPour 2 à 10 joueurs de 7 ans et plus.', 21, 10),
(10, 2, 'Skip-Bo', '12.79', 'À Skip-Bo, les joueurs utilisent habileté et stratégies pour créer des piles de cartes en ordre croissant (2, 3, 4…) jusqu’à ce qu’il n’y ait plus de cartes à jouer.\r\n\r\nPour 2 à 6 joueurs de 8 ans et plus.', 9, 10),
(11, 2, 'Monopoly Deal — Achetez. Vendez. Négociez. Gagnez !', '7.49', 'Le plaisir d\'un jeu de Monopoly joué avec des cartes. Collectionnez 3 cartes de propriété pour gagner.\r\n\r\nPour 2 à 5 joueurs de 8 ans et plus.', 22, 10),
(12, 2, 'Cards Against Humanity : édition canadienne (anglais uniquement)', '34.99', 'Le jeu de party pour les gens terribles. Le jeu est simple : chaque ronde, un joueur demande une question d\'une carte noire, puis tous les autres répondent avec leur carte blanche la plus drôle.\r\n\r\nPour 4 joueurs ou plus de 17 ans et plus. Disponible uniquement en anglais.', 0, 5),
(13, 2, 'Exploding Kittens (version française)', '29.99', 'Un jeu de cartes. Pour ceux qui aiment les chatons. Et les explosions. Et les rayons laser. Et parfois aussi les boucs.\r\n\r\nPour 2 à 5 joueurs de 7 ans et plus.', 1, 5),
(14, 2, 'Codenames (version française)', '22.29', 'Le jeu est divisé en deux équipes, et chaque côté a un chef d\'équipe. Le chef d\'équipe doit donner un indice de un mot pour guider son équipe à choisir leurs cartes.\r\n\r\nPour 2 à 8 joueurs de 14 ans et plus.', 1, 5),
(15, 3, 'Yu-Gi-Oh! — Paquets de débutant Match of The Millennium et Twisted Nightmares', '79.99', 'Ce produit contient deux paquets entièrement personnalisable. Choisissez votre côté et revivez deux des duels les plus sombres de la série original.\r\n\r\nLe paquet Match of The Millennium contient :\r\n\r\n* 1x paquet Yugi de 31 cartes\r\n* 1x paquet Pegasus de 31 cartes\r\n* 4x cartes de compétence (2 pour Yugi, 2 pour Pegasus)\r\n* 2x cartes de variante Ultra Rare\r\n\r\nLe paquet Twisted Nightmares contient :\r\n\r\n* 1x paquet Bakura de 30 cartes\r\n* 1x paquet Marik de 30 cartes\r\n* 4x cartes de compétence (2 pour Bakura, 2 pour Marik)\r\n* 2x cartes de variante Ultra Rare', 0, 1),
(16, 3, 'Magic: The Gathering — Kit de débutant Spellslinger', '49.99', 'Apprenez à jouer à Magic. Vous pouvez jouer avec un ami immédiatement avec ce kit de débutant.', 0, 1),
(17, 5, 'Kit de poker avec boîtier en aluminium', '59.79', 'Ce kit de poker dans un boîtier en aluminium comprend :\r\n\r\n* 300 jetons de poker\r\n* 2 paquets de cartes à jouer\r\n* 5 dés à six faces\r\n* Un bouton de dealer et de petite/grosse blinde', 1, 5),
(18, 5, '2 paquets de 54 cartes de jeu standard', '14.49', '2 paquets (derrière de cartes rouge et bleu) de cartes de jeu standard (avec Joker).', 39, 20),
(19, 5, 'Dés à six faces standard (5 pc.)', '5.99', '5 dés à six faces réguliers.', 25, 20),
(20, 5, 'Pions de joueurs (32 pc.)', '16.49', '32 pions de joueurs à usage multiple. Rouge, jaune, orange, vert, bleu, violet, blanc et noir (8 chaque).', 32, 10);


ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_62534E21F85E0677` (`username`);

ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_E52FFDEE9395C3F3` (`customer_id`);

ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_62809DB08D9F6D38` (`order_id`),
  ADD KEY `IDX_62809DB04584665A` (`product_id`);

ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B3BA5A5A12469DE2` (`category_id`);


ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

ALTER TABLE `orders`
  ADD CONSTRAINT `FK_E52FFDEE9395C3F3` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

ALTER TABLE `order_items`
  ADD CONSTRAINT `FK_62809DB04584665A` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `FK_62809DB08D9F6D38` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

ALTER TABLE `products`
  ADD CONSTRAINT `FK_B3BA5A5A12469DE2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

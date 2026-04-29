-- ══════════════════════════════════════════
--  MG Phone — Base de données complète
--  Exécuter dans phpMyAdmin > SQL
-- ══════════════════════════════════════════

CREATE DATABASE IF NOT EXISTS mgphone CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mgphone;

-- ── Admins ──
CREATE TABLE IF NOT EXISTS admins (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50)  NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Identifiants par défaut: admin / mdp123456
INSERT INTO admins (username, password) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
-- Note: hash bcrypt de "mdp123456"

-- ── Produits ──
CREATE TABLE IF NOT EXISTS produits (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    marque      VARCHAR(50)   NOT NULL,
    nom         VARCHAR(100)  NOT NULL,
    specs       VARCHAR(255)  DEFAULT '',
    prix        DECIMAL(10,2) NOT NULL,
    prix_ancien DECIMAL(10,2) DEFAULT NULL,
    badge       VARCHAR(30)   DEFAULT NULL,
    emoji       VARCHAR(10)   DEFAULT '📱',
    type        ENUM('neuf','occasion') NOT NULL DEFAULT 'neuf',
    etat        VARCHAR(50)   DEFAULT NULL,
    batterie    INT           DEFAULT NULL,
    garantie    VARCHAR(50)   DEFAULT NULL,
    stock       INT           NOT NULL DEFAULT 10,
    actif       TINYINT(1)    NOT NULL DEFAULT 1,
    created_at  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ── Commandes ──
CREATE TABLE IF NOT EXISTS commandes (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    prenom     VARCHAR(50)  NOT NULL,
    nom        VARCHAR(50)  NOT NULL,
    telephone  VARCHAR(20)  NOT NULL,
    adresse    VARCHAR(255) NOT NULL,
    total      DECIMAL(10,2) NOT NULL DEFAULT 0,
    statut     ENUM('en_attente','confirmee','livree','annulee') NOT NULL DEFAULT 'en_attente',
    vu         TINYINT(1)   NOT NULL DEFAULT 0,
    created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ── Détails commandes ──
CREATE TABLE IF NOT EXISTS commande_items (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    commande_id INT           NOT NULL,
    produit_id  INT           NOT NULL,
    nom_produit VARCHAR(150)  NOT NULL,
    prix_unit   DECIMAL(10,2) NOT NULL,
    quantite    INT           NOT NULL DEFAULT 1,
    FOREIGN KEY (commande_id) REFERENCES commandes(id) ON DELETE CASCADE,
    FOREIGN KEY (produit_id)  REFERENCES produits(id)  ON DELETE RESTRICT
);

-- ── Données d'exemple ──
INSERT INTO produits (marque, nom, specs, prix, badge, emoji, type, stock) VALUES
('Apple',   'iPhone 16 Pro Max', '256 Go · Titane Naturel · 5G',  5490, 'Nouveau',    '📱', 'neuf', 8),
('Samsung', 'Galaxy S25 Ultra',  '512 Go · Phantom Black · 5G',   4990, 'Bestseller', '📲', 'neuf', 5),
('Xiaomi',  '15 Ultra',          '512 Go · Blanc · Leica Camera', 3890, NULL,         '🔋', 'neuf', 2),
('Samsung', 'Galaxy A55 5G',     '128 Go · Bleu · Écran AMOLED',  1290, 'Promo',      '💫', 'neuf', 12);

INSERT INTO produits (marque, nom, specs, prix, prix_ancien, badge, emoji, type, etat, batterie, garantie, stock) VALUES
('Apple',   'iPhone 14 Pro', 'Débloqué tous opérateurs', 2490, 3200, 'Top affaire', '🏆', 'occasion', 'Excellent',    92, '3 mois', 3),
('Samsung', 'Galaxy S23',    '256 Go · Phantom Black',   1890, NULL, NULL,          '🔍', 'occasion', 'Très bon état', 88, '2 mois', 1),
('Google',  'Pixel 8',       '128 Go · Obsidian',        1490, NULL, NULL,          '💎', 'occasion', 'Bon état',     85, '2 mois', 4);

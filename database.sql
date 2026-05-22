-- ==============================================
-- Flower Store Database Setup
-- ==============================================

CREATE DATABASE IF NOT EXISTS `flower_store_db` DEFAULT CHARACTER SET utf8mb4;
USE `flower_store_db`;

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `quantity` INT NOT NULL DEFAULT 0,
  `image` VARCHAR(255) NOT NULL DEFAULT 'placeholder.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `admin` (`username`, `password`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');


INSERT INTO `products` (`name`, `description`, `price`, `quantity`, `image`) VALUES
('Red Rose Bouquet', 'A stunning bouquet of 12 fresh red roses, perfect for expressing love and romance. Hand-tied with elegant wrapping.', 49.99, 25, 'placeholder.jpg'),
('Sunflower Arrangement', 'Bright and cheerful sunflower arrangement that brings warmth and happiness to any room. Includes 8 premium sunflowers.', 39.99, 18, 'placeholder.jpg'),
('Lavender Dreams', 'A calming arrangement of fresh lavender stems paired with white baby breath. Ideal for relaxation and gifting.', 34.99, 15, 'placeholder.jpg'),
('Mixed Tulip Bundle', 'Colorful mix of 15 premium tulips in pink, yellow, and white. A perfect spring gift for any occasion.', 44.99, 20, 'placeholder.jpg'),
('White Lily Elegance', 'Elegant arrangement of pure white lilies symbolizing peace and purity. Perfect for weddings and formal events.', 54.99, 12, 'placeholder.jpg'),
('Orchid Collection', 'Exotic orchid arrangement featuring purple and white phalaenopsis orchids in a decorative ceramic pot.', 64.99, 10, 'placeholder.jpg'),
('Wildflower Basket', 'A charming basket filled with seasonal wildflowers. Each arrangement is unique and naturally beautiful.', 29.99, 30, 'placeholder.jpg'),
('Pink Peony Bouquet', 'Luxurious bouquet of soft pink peonies. A romantic and sophisticated choice for special celebrations.', 59.99, 8, 'placeholder.jpg');

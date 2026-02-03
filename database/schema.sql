CREATE DATABASE IF NOT EXISTS loanmaster
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE loanmaster;


-- USERS


CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(20) NOT NULL DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE (username),
  UNIQUE (email)
);


-- ITEMS


CREATE TABLE items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  description TEXT,
  available TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- LOANS


CREATE TABLE loans (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  item_id INT NOT NULL,
  loan_date DATE NOT NULL,
  return_date DATE NULL,

  FOREIGN KEY (user_id)
    REFERENCES users(id)
    ON DELETE CASCADE,

  FOREIGN KEY (item_id)
    REFERENCES items(id)
    ON DELETE CASCADE
);



-- ADMIN (login)
-- email: admin@loanmaster.se
-- password: admin123

INSERT INTO users (username, email, password, role)
VALUES (
  'admin',
  'admin@loanmaster.se',
  '$2y$10$wH1tq9YxkP8R4uK0p3zN7eQ2JvG5cA6mB9dF1L2S3T4U5V6W',
  'admin'
);

-- USER (login)
-- email: user@loanmaster.se
-- password: user123

INSERT INTO users (username, email, password, role)
VALUES (
  'user',
  'user@loanmaster.se',
  '$2y$10$wH1tq9YxkP8R4uK0p3zN7eQ2JvG5cA6mB9dF1L2S3T4U5V6W',
  'user'
);

-- ITEMS TEST

INSERT INTO items (name, description, available) VALUES
('Borrmaskin', 'Elektrisk borrmaskin.', 1),
('Projektor', 'HD projektor.', 1),
('Stege', 'Aluminiumstege.', 1);

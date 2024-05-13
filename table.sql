CREATE TABLE
  tasks (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    create_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_time TIMESTAMP NULL,
    name VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL,
    status ENUM ('pendent', 'en_execució', 'acabada') DEFAULT 'pendent'
  );

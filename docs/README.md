# Documentation de la Base de Donnees GameBook

## MCD (Modele Conceptuel des Donnees)

### Entites et Attributs

#### Users
- id (cle primaire)
- name
- email
- email_verified_at
- password
- remember_token
- created_at
- updated_at

#### Categories
- id (cle primaire)
- name (50 caracteres)
- description (texte)
- created_at
- updated_at

#### Games
- id (cle primaire)
- name (50 caracteres)
- description (texte)
- difficulty (enum: easy, medium, hard)
- min_players (entier, default: 2)
- max_players (entier, default: 4)
- spots (entier)
- duration (entier)
- status (enum: available, unavailable)
- category_id (cle etrangere -> Categories)
- price (decimal 10,2)
- image_url (texte nullable)
- created_at
- updated_at

#### Tables
- id (cle primaire)
- capacity (entier)
- reference (50 caracteres)
- created_at
- updated_at

#### Reservations
- id (cle primaire)
- user_id (cle etrangere -> Users, cascadeOnDelete)
- table_id (cle etrangere -> Tables, cascadeOnDelete)
- game_id (cle etrangere -> Games, nullable, nullOnDelete)
- date (date)
- start_time (time)
- end_time (time)
- spots (entier)
- status (enum: pending, confirmed, cancelled, completed, in_progress)
- price (decimal 10,2, default: 0.00)
- created_at
- updated_at

#### ReservationSessions
- id (cle primaire)
- reservation_id (cle etrangere -> Reservations, cascadeOnDelete)
- duration (entier)
- status (enum: inactive, active, ended)
- started_at (timestamp nullable)
- ended_at (timestamp nullable)
- created_at
- updated_at

#### SessionGames
- id (cle primaire)
- session_id (cle etrangere -> ReservationSessions, cascadeOnDelete)
- game_id (cle etrangere -> Games, cascadeOnDelete)
- price_at_time (decimal 10,2)
- is_active (booleen, default: false)
- returned_at (timestamp nullable)
- added_at (timestamp, useCurrent)
- created_at
- updated_at

### Associations

1. **Category - Game** (1,n)
   - Une categorie peut contenir plusieurs jeux
   - Un jeu appartient a une categorie

2. **Reservation - User** (n,1)
   - Un utilisateur peut avoir plusieurs reservations
   - Une reservation appartient a un utilisateur

3. **Reservation - Table** (n,1)
   - Une table peut avoir plusieurs reservations
   - Une reservation concerne une table

4. **Reservation - Game** (n,1)
   - Un jeu peut etre reserve plusieurs fois
   - Une reservation peut concerner un jeu

5. **ReservationSession - Reservation** (n,1)
   - Une reservation peut avoir plusieurs sessions
   - Une session appartient a une reservation

6. **SessionGame - ReservationSession** (n,1)
   - Une session peut avoir plusieurs jeux
   - Un jeu de session appartient a une session

7. **SessionGame - Game** (n,1)
   - Un jeu peut etre ajoute a plusieurs sessions
   - Un jeu de session appartient a un jeu

---

## MLD (Modele Logique des Donnees)

### Tables SQL

```sql
-- Users (existant)
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255),
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Categories
CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50),
    description TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Games
CREATE TABLE games (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50),
    description TEXT,
    difficulty ENUM('easy', 'medium', 'hard'),
    min_players INT DEFAULT 2,
    max_players INT DEFAULT 4,
    spots INT,
    duration INT,
    status ENUM('available', 'unavailable') DEFAULT 'available',
    category_id BIGINT UNSIGNED NULL,
    price DECIMAL(10,2),
    image_url VARCHAR(255) NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Tables
CREATE TABLE tables (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    capacity INT,
    reference VARCHAR(50),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Reservations
CREATE TABLE reservations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    table_id BIGINT UNSIGNED NOT NULL,
    game_id BIGINT UNSIGNED NULL,
    date DATE,
    start_time TIME,
    end_time TIME,
    spots INT,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed', 'in_progress') DEFAULT 'pending',
    price DECIMAL(10,2) DEFAULT 0.00,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (table_id) REFERENCES tables(id) ON DELETE CASCADE,
    FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE SET NULL
);

-- ReservationSessions
CREATE TABLE reservation_sessions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reservation_id BIGINT UNSIGNED NOT NULL,
    duration INT,
    status ENUM('inactive', 'active', 'ended') DEFAULT 'inactive',
    started_at TIMESTAMP NULL,
    ended_at TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (reservation_id) REFERENCES reservations(id) ON DELETE CASCADE
);

-- SessionGames
CREATE TABLE session_games (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    session_id BIGINT UNSIGNED NOT NULL,
    game_id BIGINT UNSIGNED NOT NULL,
    price_at_time DECIMAL(10,2),
    is_active BOOLEAN DEFAULT FALSE,
    returned_at TIMESTAMP NULL,
    added_at TIMESTAMP,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (session_id) REFERENCES reservation_sessions(id) ON DELETE CASCADE,
    FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE
);
```

---

## Schema Graphique de la Base de Donnees

```
+============+       +============+       +==========+
|  USERS     |       | CATEGORIES |       |  GAMES   |
+============+       +============+       +==========+
| id (PK)    |<---->| id (PK)    |<---->| id (PK)  |
| name       |     | name      |     | name    |
| email      |     | desc     |     | desc    |
| password   |     +----------+     | diff   |
| ...        |                   | price  |
+============+                   | cat_id |
        ^                    | ...    |
        | n                 +==========+
        |                        ^
+============+                   |
| TABLES    |                   |
+============+                   |
| id (PK)   |                   |
| capacity |                   |
| ref      |                   |
+============+                   |
        ^                        |
        | n                    |
+============+       +===============+
| RESERVATIONS|     |RESERVATION_   |
+============+       |SESSIONS       |
| id (PK)   |<----| id (PK)       |
| user_id   |     | reservation_  |
| table_id |     |   id         |
| game_id   |     | duration    |
| date     |     | status      |
| start    |     | started_at  |
| end      |     | ended_at    |
| spots    |     +==============+
| status   |            ^
| price   |            | n
+============+   +=============+
              |   | SESSION_    |
              |   | GAMES      |
              |   +=============+
              +-->| id (PK)    |
                 | session_id |
                 | game_id   |
                 | price_    |
                 | is_active |
                 | returned_ |
                 | added_at  |
                 +============+
```

### Legendes

| Symbol | Signification |
|--------|---------------|
| (PK)   | Cle primaire  |
| (FK)   | Cle etrangere  |
| >      | Relation      |
| n      | Cardinalite plusieurs |
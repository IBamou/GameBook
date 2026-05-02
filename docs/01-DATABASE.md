# Database Design

## Overview
GameBook uses a relational database (MySQL) to manage game center reservations, sessions, and game tracking.

## Entity Relationship Diagram

```
Users ────── Reservations ────── Tables
              │
              └────── ReservationSessions ────── SessionGames
                                                      │
                                                      └──── Games ───── Categories
```

## Tables

### users
Standard Laravel users table with roles managed via Spatie permission package.

### categories
Game categories (e.g., Board Games, Video Games, Sports).

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| name | varchar(50) | Category name |
| description | text | Optional description |
| created_at | timestamp | |
| updated_at | timestamp | |

### games
Available games in the center.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| name | varchar(50) | Game name |
| description | text | Game description |
| difficulty | enum | easy, medium, hard |
| min_players | int | Minimum players (default 2) |
| max_players | int | Maximum players (default 4) |
| spots | int | Required spots/table capacity |
| duration | int | Default session duration in minutes |
| status | enum | available, unavailable |
| category_id | bigint | FK to categories |
| price | decimal(10,2) | Price per session in MAD |
| image_url | varchar | Optional image |
| created_at | timestamp | |
| updated_at | timestamp | |

### tables
Physical gaming tables.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| capacity | int | Number of seats |
| reference | varchar(50) | Table identifier (e.g., "T1") |
| created_at | timestamp | |
| updated_at | timestamp | |

### reservations
Customer reservations.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| user_id | bigint | FK to users |
| table_id | bigint | FK to tables |
| game_id | bigint | FK to games (optional) |
| date | date | Reservation date |
| start_time | time | Start time |
| end_time | time | End time |
| spots | int | Number of players |
| status | enum | pending, confirmed, cancelled, completed |
| price | decimal(10,2) | Total price |
| created_at | timestamp | |
| updated_at | timestamp | |

### reservation_sessions
Active session tracking per reservation.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| reservation_id | bigint | FK to reservations |
| duration | int | Session duration in minutes |
| status | enum | inactive, active, ended |
| started_at | timestamp | When session started |
| ended_at | timestamp | When session ended |
| current_game_id | bigint | FK to games (current game) |
| additional_charges | decimal(10,2) | Extra charges from game switching |
| created_at | timestamp | |
| updated_at | timestamp | |

## Relationships

- **Category** hasMany **Games**
- **Game** belongsTo **Category**
- **User** hasMany **Reservations**
- **Reservation** belongsTo **User**
- **Reservation** belongsTo **Table**
- **Reservation** belongsTo **Game** (optional)
- **Reservation** hasMany **ReservationSessions**
- **ReservationSession** belongsTo **Reservation**
- **ReservationSession** belongsTo **Game** (current_game_id)

## Indexes

- `reservations.table_id` + `reservations.date` + `reservations.status` (for conflict checking)
- `reservations.user_id` (for user reservations)
- `reservation_sessions.reservation_id` + `reservation_sessions.status` (for active session lookup)
- `games.category_id` (for category filtering)
- `tables.reference` (unique)
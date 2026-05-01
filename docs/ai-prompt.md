# AI Prompt for Aji L3bo - Laravel 13 Game Cafe App

I have a Laravel 13 game cafe reservation app called "Aji L3bo" (Game Cafe). Help me improve the TailwindCSS styling.

## PROJECT CONTEXT

- **Framework**: Laravel 13
- **Templating**: Blade templates
- **Styling**: TailwindCSS
- **Layout**: resources/views/layouts/app.blade.php with @yield('content')
- **PHP**: 8.4+

## CURRENT PAGES

- `/sessions` - Admin dashboard showing table cards with status: available/booked/ready/in_progress
- `/games` - Browse game catalog (grid of game cards)
- `/categories` - Browse games by category
- `/tables` - Admin table management (grid of table cards)
- `/reservations/index` - Admin view all reservations (table)
- `/reservations/my` - User view their reservations
- `/reservations/create` - Create reservation form
- `/reservations/{reservation}/show` - Reservation details
- `/reservations/{reservation}/edit` - Edit reservation

## DATABASE SCHEMA

```php
// Users
users: id, name, email, phone, role (admin/user), password

// Games
games: id, name, description, difficulty (easy|medium|hard), min_players, max_players, spots, duration, status (available|unavailable), price, category_id

// Categories  
categories: id, name, description

// Tables
tables: id, reference, capacity

// Reservations
reservations: id, user_id, table_id, game_id, date, start_time, end_time, spots, status (pending|confirmed|cancelled|completed), price

// Sessions
reservation_sessions: id, reservation_id, duration, status (inactive|active|ended), started_at, ended_at
```

## RELATIONSHIPS

```php
// Table
tables → hasMany reservations

// Reservation
reservations → belongsTo user, table, game
reservations → hasMany sessions
```

## ROUTES STRUCTURE

- **Admin** (auth + admin middleware): CRUD for games, categories, tables, view all reservations, sessions management
- **User** (auth only): create/view own reservations
- **Public**: view games, categories

## DESIGN PREFERENCES

- Modern, clean look with smooth transitions
- Card-based layouts with rounded corners
- Status badges with semantic colors:
  - Green = available/confirmed/active
  - Blue = booked
  - Amber/Yellow = ready/pending
  - Red = cancelled/in_use
- Good whitespace and typography hierarchy
- Hover effects on interactive elements
- Dark mode support (optional)

## CAN YOU HELP WITH

1. Improve the Sessions index page (table cards with countdown timer, start/end session buttons)
2. Improve the Games index page (grid of game cards with images)
3. Improve the Reservations pages (both admin table and user list)
4. Create more consistent button styles
5. Better form designs for create/edit pages

Focus on:
- Consistent styling across all pages
- Better UX (clearer CTAs, better feedback)
- Modern aesthetics with smooth hover states
- Responsive design

The views are in:
- `resources/views/games/index.blade.php`
- `resources/views/sessions/index.blade.php`
- `resources/views/reservations/index.blade.php`
- `resources/views/reservations/my.blade.php`
- `resources/views/reservations/create.blade.php`
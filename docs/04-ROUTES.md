# Routes & Endpoints

## Public Routes

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/` | | Landing page |
| GET | `/dashboard` | | User dashboard |
| GET | `/games` | games.index | Browse all games |
| GET | `/games/{game}` | games.show | Game details |
| GET | `/categories` | categories.index | Browse categories |
| GET | `/categories/{category}` | categories.show | Category with games |

## Authenticated Routes (All Users)

### Profile
| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/profile` | profile.edit | Edit profile |
| PATCH | `/profile` | profile.update | Update profile |
| DELETE | `/profile` | profile.destroy | Delete account |

### My Reservations
| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/reservations/my` | reservations.my | User's reservations |
| GET | `/reservations/create` | reservations.create | Create reservation form |
| POST | `/reservations/store` | reservations.store | Save new reservation |
| GET | `/reservations/{reservation}` | reservations.show | Reservation details |
| GET | `/reservations/{reservation}/edit` | reservations.edit | Edit reservation form |
| PUT | `/reservations/{reservation}/update` | reservations.update | Update reservation |
| DELETE | `/reservations/{reservation}/delete` | reservations.delete | Cancel/delete reservation |

### My Sessions
| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/sessions/my` | sessions.my | User's sessions |

## Admin Routes (Middleware: auth + admin)

### Categories
| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/categories/create` | categories.create | Create category form |
| POST | `/categories/store` | categories.store | Save category |
| GET | `/categories/{category}/edit` | categories.edit | Edit category form |
| PUT | `/categories/{category}/update` | categories.update | Update category |
| DELETE | `/categories/{category}/delete` | categories.delete | Delete category |

### Games
| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/games/create` | games.create | Create game form |
| POST | `/games/store` | games.store | Save game |
| GET | `/games/{game}/status` | games.status | Toggle availability |
| GET | `/games/{game}/edit` | games.edit | Edit game form |
| PUT | `/games/{game}/update` | games.update | Update game |
| DELETE | `/games/{game}/delete` | games.delete | Delete game |

### Reservations (Admin)
| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/reservations` | reservations.index | All reservations |
| PATCH | `/reservations/{reservation}/status` | reservations.status | Update status |

### Sessions (Admin)
| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/sessions` | sessions.index | Session management |
| POST | `/sessions/{reservation}/start-session` | sessions.start | Start session |
| POST | `/sessions/{reservation}/end-session` | sessions.end | End session |
| POST | `/sessions/{reservation}/update-session-game` | sessions.updateGame | Switch game |

### Tables
| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/tables` | tables.index | All tables |
| GET | `/tables/create` | tables.create | Create table form |
| POST | `/tables/store` | tables.store | Save table |
| GET | `/tables/{table}` | tables.show | Table details |
| GET | `/tables/{table}/status` | tables.status | Table status |
| GET | `/tables/{table}/edit` | tables.edit | Edit table form |
| PUT | `/tables/{table}/update` | tables.update | Update table |
| DELETE | `/tables/{table}/delete` | tables.delete | Delete table |

## Route Groups

```php
// All authenticated users
Route::middleware('auth')->group(function () {
    // Reservations, Profile, My Sessions
});

// Admin only
Route::middleware(['auth', 'admin'])->group(function () {
    // Categories, Games, Tables, All Reservations, Sessions
});
```

## Named Routes Usage

```blade
// Link to route
<a href="{{ route('reservations.create') }}">Reserve</a>

// Form action
<form action="{{ route('sessions.start', $reservation) }}" method="POST">

// Redirect
return redirect()->route('sessions.index');
```
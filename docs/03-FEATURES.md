# Features

## User Features

### Authentication
- User registration and login
- Role-based access (user/admin)
- Password reset functionality

### Reservations
- View available tables
- Create new reservation (select table, date, time, game)
- View own reservations
- Cancel own reservations (before session starts)
- Real-time availability checking

### My Sessions
- View active/ended sessions
- See remaining time (live countdown)
- See current game
- Game switching (during active session)

## Admin Features

### Dashboard
- Overview of today's reservations
- Quick stats (total reservations, active sessions, revenue)

### Table Management
- View all tables with real-time status
- Status indicators: available, booked, ready, in_progress

### Reservation Management
- View all reservations
- Confirm pending reservations
- Cancel/manage any reservation
- Filter by date, status, table

### Session Management
- Start session (from ready reservation)
- End session manually
- View all sessions
- Real-time countdown display

### Game Management
- CRUD operations for games
- Set availability (available/unavailable)
- Categorize games
- Set price, duration, player count

### Category Management
- CRUD operations for categories
- View games per category

## Real-Time Features

### Live Updates
- Table status changes broadcast immediately
- Session countdown updates every second
- No page refresh needed for status changes

### WebSocket Events
- TableStatusChanged
- ReservationUpdated

## Pricing Features

### Dynamic Pricing
- Price calculated based on game × spots
- Additional charges for game switching
- High-Water Mark pricing (only pay difference)

### Late Arrival Adjustment
- Session duration reduced by late minutes
- Fair billing for actual play time

## Validation Features

### Reservation Conflict Prevention
- No overlapping reservations on same table
- Checks both date and time overlap
- Excludes cancelled reservations
- Prevents reserving table during active session

### Game Availability
- Cannot switch to unavailable game
- Game marked busy during active session

## UI/UX Features

### Responsive Design
- Mobile-friendly interface
- Tablet and desktop optimized

### Visual Indicators
- Color-coded table status badges
- Progress indicators for sessions
- Real-time countdown display

### Navigation
- Dropdown menus for admin (My/All)
- Clear page titles and breadcrumbs

## Technical Features

### Timezone
- Morocco timezone (Africa/Casablanca) throughout

### Pagination
- 6 items per page for categories and games
- Optimized for readability

### Observers
- ReservationObserver: Handle status changes
- ReservationSessionObserver: Handle session events

### Commands
- CheckTableStatus: Automatic session/table management
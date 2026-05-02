# Application Logic

## Table Status System

Table status is **computed dynamically** based on reservations and active sessions (not stored in DB):

```
available → booked → ready → in_progress → available
```

### Status Logic (in Table Model)

1. **in_progress**: Active session exists on table
2. **ready**: Reservation time reached, no active session yet
3. **booked**: Future confirmed reservation exists
4. **available**: No reservations/sessions

## Reservation Flow

### Creating a Reservation
1. User selects table, date, time slot
2. **Conflict Check**: Verify no overlapping confirmed reservations on same table/date
3. **Active Session Check**: Verify table not currently in use
4. Calculate price (game price × spots)
5. Create reservation with `pending` status

### Confirming a Reservation
- Admin can confirm pending reservations
- Confirmed reservations count toward table status

### Conflict Detection (ReservationStoreRequest/ReservationUpdateRequest)

```php
// Check for overlapping reservations
$conflict = Reservation::where('table_id', $tableId)
    ->where('date', $date)
    ->whereNotIn('status', ['cancelled'])
    ->whereTime('start_time', '<', $endTime)
    ->whereTime('end_time', '>', $startTime)
    ->exists();
```

Also checks for active sessions to prevent reserving a table in use.

## Session Management

### Starting a Session
1. Admin clicks "Start Session" on a `ready` reservation
2. Creates `ReservationSession` with:
   - `status`: active
   - `started_at`: now
   - `duration`: game.duration
   - `current_game_id`: reservation.game_id

### Session Countdown
- Frontend displays countdown timer (Alpine.js)
- Updates every second
- When reaches 0 → auto-submits end session

### Ending a Session
1. Admin clicks "End" or countdown reaches 0
2. Updates session:
   - `status`: ended
   - `ended_at`: now
3. Updates game status: available
4. Triggers `TableStatusChanged` event for real-time update
5. Checks for next reservation → updates table status

## Game Switching (High-Water Mark Pricing)

Users can switch games during an active session:

### Pricing Logic
- **High-Water Mark**: Only charge the difference if switching to more expensive game
- If new game is cheaper → no additional charge

### Process (ReservationSessionController@switchGame)
1. Validate new game is available (not in use by other sessions)
2. Get old and new game prices
3. If new price > old price:
   - `additional_charges += (new_price - old_price)`
4. Update `current_game_id` to new game
5. Mark new game as busy

## Late Arrival Handling

If user arrives late:
1. Calculate late minutes = arrival time - start time
2. Reduce session duration by late minutes
3. Minimum session: 1 minute
4. Adjusted duration used for countdown

## Real-Time Updates (WebSockets)

### Events
- **TableStatusChanged**: Broadcasts when table status changes
- **ReservationUpdated**: Broadcasts reservation updates

### Channel
- Public channel: `table-status`

### Listeners (Frontend)
Echo.js listens for events and reloads page or updates UI

## Automatic Status Checking

### CheckTableStatus Command
Runs every minute via Laravel scheduler:

1. **End expired sessions**: If session duration passed, end it
2. **Update table status**: Set to booked/available based on next reservation
3. **Handle ready status**: If reservation time reached but no active session

## Pricing Calculation

### Reservation Price
```
price = game.price × spots
```

### Additional Charges (Game Switching)
```
total = original_price + additional_charges
```

## Timezone

All times use **Africa/Casablanca** (Morocco UTC+1)
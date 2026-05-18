<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isLoggedIn()) {
    redirect('modules/auth/login.php');
}

$user_id = $_SESSION['user_id'];
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'upcoming';

// Get events
$events_sql = "SELECT e.*, u.full_name as organizer_name,
               (SELECT COUNT(*) FROM event_attendees WHERE event_id = e.id AND status = 'going') as going_count,
               (SELECT COUNT(*) FROM event_attendees WHERE event_id = e.id AND user_id = ?) as user_status
               FROM events e
               JOIN users u ON e.created_by = u.id
               WHERE e.event_date " . ($filter == 'upcoming' ? '>= NOW()' : '< NOW()') . "
               ORDER BY e.event_date " . ($filter == 'upcoming' ? 'ASC' : 'DESC') . "
               LIMIT 20";
$events_stmt = $conn->prepare($events_sql);
$events_stmt->execute([$user_id]);
$events = $events_stmt->get_result();
?>
<?php include_once '../../includes/header.php'; ?>

<div class="events-container">
    <!-- Header -->
    <div class="bg-white border-bottom p-3 sticky-top">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Events</h5>
            <button class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#createEventModal">
                <i class="bi bi-plus-lg"></i> Create Event
            </button>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="bg-white border-bottom px-3 py-2">
        <div class="btn-group w-100" role="group">
            <a href="?filter=upcoming" class="btn text-dark <?php echo $filter == 'upcoming' ? 'btn-primary' : 'btn-outline-primary'; ?> rounded-pill me-2">
                Upcoming
            </a>
            <a href="?filter=past" class="btn text-dark <?php echo $filter == 'past' ? 'btn-primary' : 'btn-outline-primary'; ?> rounded-pill">
                Past Events
            </a>
        </div>
    </div>
    
    <div class="p-3">
        <?php if($events->num_rows > 0): ?>
            <?php while($event = $events->fetch_assoc()): ?>
            <div class="card mb-3 border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="fw-bold mb-0"><?php echo htmlspecialchars($event['title']); ?></h6>
                        <span class="badge <?php echo strtotime($event['event_date']) > time() ? 'bg-success' : 'bg-secondary'; ?>">
                            <?php echo strtotime($event['event_date']) > time() ? 'Upcoming' : 'Past'; ?>
                        </span>
                    </div>
                    <p class="small text-muted mb-2">
                        <i class="bi bi-calendar"></i> <?php echo date('F j, Y \a\t g:i A', strtotime($event['event_date'])); ?>
                    </p>
                    <p class="small text-muted mb-2">
                        <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($event['location']); ?>
                    </p>
                    <p class="small mb-2"><?php echo htmlspecialchars(substr($event['description'], 0, 100)); ?>...</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="small text-muted">
                            <i class="bi bi-people"></i> <?php echo $event['going_count']; ?> going
                            <span class="mx-2">•</span>
                            <i class="bi bi-person"></i> Organized by <?php echo htmlspecialchars($event['organizer_name']); ?>
                        </div>
                        <div>
                            <?php if($event['user_status'] == 'going'): ?>
                            <button class="btn btn-sm btn-success rounded-pill" disabled>
                                <i class="bi bi-check-lg"></i> Going
                            </button>
                            <?php else: ?>
                            <button class="btn btn-sm btn-primary rounded-pill" onclick="rsvpEvent(<?php echo $event['id']; ?>, this)">
                                <i class="bi bi-calendar-check"></i> RSVP
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
        <div class="text-center py-5">
            <i class="bi bi-calendar-event fs-1 text-muted"></i>
            <p class="text-muted mt-2">No events found</p>
            <button class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#createEventModal">
                Create an Event
            </button>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Create Event Modal -->
<div class="modal fade" id="createEventModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo SITE_URL; ?>modules/events/create.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Event Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date & Time</label>
                        <input type="datetime-local" name="event_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Max Attendees (Optional)</label>
                        <input type="number" name="max_attendees" class="form-control" placeholder="Unlimited if empty">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Event</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>

<script>
function rsvpEvent(eventId, element) {
    $.ajax({
        url: '<?php echo SITE_URL; ?>modules/events/rsvp.php',
        method: 'POST',
        data: {event_id: eventId},
        success: function(response) {
            if(response == 'success') {
                $(element).text('Going').prop('disabled', true).removeClass('btn-primary').addClass('btn-success');
                showToast('You are now going to this event!', 'success');
                location.reload();
            }
        }
    });
}
</script>

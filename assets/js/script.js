// General scripts for the application
$(document).ready(function() {
    // Enable Bootstrap tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeTo(500, 0).slideUp(500, function() {
            $(this).remove();
        });
    }, 5000);
    
    // Confirm before sensitive actions
    $('.confirm-action').on('click', function(e) {
        if (!confirm($(this).data('confirm-message') || 'Are you sure?')) {
            e.preventDefault();
        }
    });
});
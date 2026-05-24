$(document).ready(function() {
    const $downloadBtn = $('#downloadBtn');

    $downloadBtn.on('click', function() {
        const $btn = $(this);
        const originalContent = $btn.html();
        
        $btn.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Preparing...');

        // Trigger download via AJAX or direct link
        // Since it's a file download, a hidden form or window.location is better
        window.location.href = 'ajax/download-pptx.php';

        // Reset button after a short delay
        setTimeout(() => {
            $btn.prop('disabled', false).html(originalContent);
        }, 3000);
    });

    // Confetti trigger
    if ($('#confetti').length) {
        // Confetti logic already in CSS, but we could add JS for more dynamic effects
    }
});

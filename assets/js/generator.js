$(document).ready(function() {
    const $form = $('#generateForm');
    const $submitBtn = $('#submitBtn');
    const $topicInput = $('#topicInput');
    const $detailsInput = $('#detailsInput');
    const $slideCount = $('#slideCount');
    const $slideBadge = $('#slideBadge');
    const $charCounter = $('#charCounter');
    
    // Update slide count badge
    $slideCount.on('input', function() {
        $slideBadge.text($(this).val());
    });

    // Character counter
    $detailsInput.on('input', function() {
        const len = $(this).val().length;
        $charCounter.text(`${len} / 500`);
        if (len > 450) $charCounter.addClass('warning');
        else $charCounter.removeClass('warning');
    });

    // Tone pill selection
    $('.tone-pill').on('click', function() {
        $('.tone-pill').removeClass('selected');
        $(this).addClass('selected');
    });

    // Form submission
    $form.on('submit', function(e) {
        e.preventDefault();

        const topic = $topicInput.val().trim();
        if (!topic) return;

        const data = {
            topic: topic,
            details: $detailsInput.val().trim(),
            slideCount: $slideCount.val(),
            tone: $('.tone-pill.selected').data('tone')
        };

        // Show loading overlay
        slideaiLoader.show();
        $submitBtn.prop('disabled', true).addClass('loading');

        $.ajax({
            url: 'ajax/generate-content.php',
            method: 'POST',
            data: data,
            success: function(response) {
                if (response.success) {
                    window.location.href = 'results.php';
                } else {
                    alert('Error: ' + (response.error || 'Failed to generate content'));
                    slideaiLoader.hide();
                    $submitBtn.prop('disabled', false).removeClass('loading');
                }
            },
            error: function(xhr) {
                let errorMsg = 'An error occurred while generating content.';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMsg = xhr.responseJSON.error;
                }
                alert('Error: ' + errorMsg);
                slideaiLoader.hide();
                $submitBtn.prop('disabled', false).removeClass('loading');
            }
        });
    });
});

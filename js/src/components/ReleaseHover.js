export function initReleaseHover() {
    // Find all anchor tags with releave hover detail spans in the cover view
    $('.cover-view a').each(function () {
        const $anchor = $(this);
        const $releaseHoverDetail = $anchor.find('.release-hover-detail');

        if (!$releaseHoverDetail.length) return;

        // Mouse move handler for this specific anchor
        const handleMouseMove = function (e) {
            const x = e.clientX;
            const y = e.clientY;
            $releaseHoverDetail.css({
                top: y + 20 + 'px',
                left: x + 20 + 'px',
            });
        };

        // Show hover detail and attach mousemove handler on hover
        $anchor.on('mouseenter', function () {
            $releaseHoverDetail.show();
            $anchor.on('mousemove', handleMouseMove);
        });

        // Hide hover detail and remove mousemove handler when leaving
        $anchor.on('mouseleave', function () {
            $releaseHoverDetail.hide();
            $anchor.off('mousemove', handleMouseMove);
        });
    });
}

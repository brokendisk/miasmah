export function initReleaseFilter() {
    // View type toggle
    $('.view-toggle a').on('click', e => {
        e.preventDefault();
        const $coverView = $('.cover-view');
        const $listView = $('.list-view');
        const viewClass = $(e.currentTarget).attr('class');

        if (viewClass === 'covers') {
            $listView.removeClass('active');
            $coverView.removeClass('release-hide');
        } else if (viewClass === 'list') {
            $coverView.addClass('release-hide');
            $listView.addClass('active');
        }
    });
}

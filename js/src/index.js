import { initNavigation } from './components/Navigation';
import { initReleaseFilter } from './components/ReleaseFilter';
import { initReleaseImagesLazyLoad } from './components/ReleaseImagesLazyLoad';
import { initPlayer } from './components/Player';

// Expose jQuery globally
window.jQuery = window.$ = $;

// Initialize components when DOM is ready
$(function () {
    initNavigation();
    initReleaseFilter();
    initReleaseImagesLazyLoad();

    // Initialize player if we're on a release page and have playlist data
    if (typeof morrPlayerData !== 'undefined' && morrPlayerData.playlist) {
        initPlayer(morrPlayerData.playlist);
    }
});

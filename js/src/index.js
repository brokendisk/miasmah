import { initNavigation } from './components/Navigation';
import { initReleaseFilter } from './components/ReleaseFilter';
import { initReleaseHover } from './components/ReleaseHover';
import { initReleaseImagesLazyLoad } from './components/ReleaseImagesLazyLoad';
import { initPlayer } from './components/Player';

// Expose jQuery's $ alias globally (WordPress loads jQuery in noConflict mode)
// This ensures $ is available for components that use it
if (typeof window.jQuery !== 'undefined') {
    window.$ = window.jQuery;
}

initNavigation();
initReleaseFilter();
initReleaseHover();
initReleaseImagesLazyLoad();

// Initialize player if we're on a release page and have playlist data
if (typeof morrPlayerData !== 'undefined' && morrPlayerData.playlist) {
    initPlayer(morrPlayerData.playlist);
}

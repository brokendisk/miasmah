import 'jplayer/dist/jplayer/jquery.jplayer.min';
import 'jplayer/dist/add-on/jplayer.playlist.min';

export function initPlayer(playlist) {
    if (!playlist || !playlist.length) return;

    return new jPlayerPlaylist(
        {
            jPlayer: '#jquery_jplayer_1',
            cssSelectorAncestor: '#jp_container_1',
        },
        playlist,
        {
            playlistOptions: {
                enableRemoveControls: false,
            },
            swfPath: '/js',
            supplied: 'mp3',
            smoothPlayBar: true,
            keyEnabled: false,
            useStateClassSkin: true,
        }
    );
}

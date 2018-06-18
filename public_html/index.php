<?php
/**
 * Created by PhpStorm.
 * User: raphael
 * Date: 11.01.18
 * Time: 16:10
 */

require_once(__DIR__ . "/../resources/models/Config.php");
require_once(__DIR__ . "/../resources/models/Info.php");
require_once(__DIR__ . "/../resources/models/InfoList.php");
require_once(__DIR__ . "/../resources/models/DBConnect.php");
require_once(__DIR__ . "/../resources/models/User.php");
require_once(__DIR__ . "/../resources/libs/smarty/Smarty.class.php");
$smarty = new Smarty();

$smarty->setTemplateDir(__DIR__ . "/../resources/smarty/templates");
$smarty->setCompileDir(__DIR__ . "/../resources/smarty/templates_c");
$smarty->setCacheDir(__DIR__ . "/../resources/smarty/cache");
$smarty->setConfigDir(__DIR__ . "/../resources/smarty/configs");

session_start();

if (isset($_SESSION["username"], $_SESSION["userId"])) {
    $smarty->assign("username", $_SESSION["username"]);
    $smarty->assign("userId", $_SESSION["userId"]);
}
$smarty->assign("configArr", Config::configArr);
checkCookieAgreement();

if (isset($_GET["p"])) {
    $smarty->assign("page", $_GET["p"]);
    assignInfos($smarty);
    switch ($_GET['p']) {
        case "login":
            $smarty->display('login.tpl');
            break;
        case "logging_in":
            require_once(__DIR__ . "/../resources/helpers/login.php");
            assignInfos($smarty);
            $smarty->display('login.tpl');
            break;
        case "register":
            $smarty->display('register.tpl');
            break;
        case "registering":
            require_once(__DIR__ . "/../resources/helpers/register.php");
            assignInfos($smarty);
            $smarty->display('register.tpl');
            break;
        case "logout":
            require_once(__DIR__ . "/../resources/helpers/logout.php");
            break;
        case "song":
            require_once(__DIR__ . "/../resources/models/Song.php");
            require_once(__DIR__ . "/../resources/models/Artist.php");
            require_once(__DIR__ . "/../resources/models/User.php");
            require_once(__DIR__ . "/../resources/models/Cover.php");
            if (isset($_GET["id"])) {
                $song = new Song($_GET["id"]);
                $artist = new Artist($song->getArtistId());
                $user = new User($song->getUserId());
                $cover = new Cover($song->getCoverId());
                $smarty->assign("song", $song);
                $smarty->assign("artist", $artist);
                $smarty->assign("user", $user);
                $smarty->assign("cover", $cover);
            }
            $smarty->display('song.tpl');
            break;
        case "album":
            require_once(__DIR__ . "/../resources/models/Album.php");
            require_once(__DIR__ . "/../resources/models/Artist.php");
            require_once(__DIR__ . "/../resources/models/Cover.php");
            require_once(__DIR__ . "/../resources/models/Song.php");
            if (isset($_GET["id"])) {
                $album = new Album($_GET["id"]);
                $artist = new Artist($album->getArtistId());
                $cover = new Cover($album->getCoverId());

                $songIds = $album->getSongIds();
                $songs = array();
                for ($i = 0; $i < count($songIds); $i++) {
                    $songs[] = new Song($songIds[$i]);
                }

                $smarty->assign("album", $album);
                $smarty->assign("artist", $artist);
                $smarty->assign("cover", $cover);
                $smarty->assign("songs", $songs);
            }
            $smarty->display('album.tpl');
            break;
        case "artist":
            require_once(__DIR__ . "/../resources/models/Artist.php");
            if (isset($_GET["id"])) {
                $artist = new Artist($_GET["id"]);
                $smarty->assign("artist", $artist);
            }
            $smarty->display('artist.tpl');
            break;
        case "user":
            require_once(__DIR__ . "/../resources/models/User.php");
            if (isset($_GET["id"])) {
                $user = new User($_GET["id"]);
                $smarty->assign("user", $user);
            }
            $smarty->display('user.tpl');
            break;
        case "playlist":
            require_once(__DIR__ . "/../resources/models/Playlist.php");
            require_once(__DIR__ . "/../resources/models/User.php");
            require_once(__DIR__ . "/../resources/models/Artist.php");
            require_once(__DIR__ . "/../resources/models/Song.php");
            require_once(__DIR__ . "/../resources/models/Cover.php");
            if (isset($_GET["id"])) {
                $playlist = new Playlist($_GET["id"]);
                $user = new User($playlist->getUserId());

                $songIds = $playlist->getSongIds();
                $songs = array();
                for ($i = 0; $i < count($songIds); $i++) {
                    $songs[] = new Song($songIds[$i]);
                }
//                var_dump($playlist);
                $smarty->assign("playlist", $playlist);
                $smarty->assign("user", $user);
                $smarty->assign("songs", $songs);
            }
            $smarty->display('playlist.tpl');
            break;
        case "add_song":
            if (isset($_GET["do"])) {
                switch ($_GET["do"]) {
                    case "upload":
                        require_once(__DIR__ . "/../resources/models/Song.php");
                        require_once(__DIR__ . "/../resources/libs/getid3/getid3.php");
                        if (isset($_SESSION["username"], $_SESSION["userId"])) {
                            if (isset($_POST["title"], $_POST["album"], $_POST["artist"], $_POST["genre"], $_POST["songtext"])) {
                                $songTitle = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
                                $songAlbum = filter_input(INPUT_POST, 'album', FILTER_SANITIZE_STRING);
                                $songArtist = filter_input(INPUT_POST, 'artist', FILTER_SANITIZE_STRING);
                                $songGenre = filter_input(INPUT_POST, 'genre', FILTER_SANITIZE_STRING);
                                $songText = filter_input(INPUT_POST, 'songtext', FILTER_SANITIZE_STRING);
                                try {
                                    Song::createNewSong($songTitle, $songAlbum, $songArtist, $songGenre, $songText);
                                } catch (Exception $exception) {
                                    $exception->getMessage();
                                }
                            }
                        }
                        break;
                }
            }
            assignInfos($smarty);
            $smarty->assign("artists", DBConnect::getArtists());
            $smarty->assign("genres", DBConnect::getGenres());
            $smarty->display('add_song.tpl');
            break;
        case "api_artists":
            require_once(__DIR__ . "/../resources/api/artists.php");
            break;
        case "api_albums":
            require_once(__DIR__ . "/../resources/api/albums.php");
            break;
        case "api_playlists":
            require_once(__DIR__ . "/../resources/api/playlists.php");
            break;
        case "api_genres":
            require_once(__DIR__ . "/../resources/api/genres.php");
            break;
        case "api_songs":
            require_once(__DIR__ . "/../resources/api/songs.php");
            break;
        default:
            $smarty->display('404.tpl');
    }
} else {
    $smarty->assign("page", "index");
    assignInfos($smarty);
    $smarty->display('index.tpl');
}


function checkCookieAgreement() {
    if(!isset($_COOKIE["cookiesAccepted"]) || (isset($_COOKIE["cookiesAccepted"]) && $_COOKIE["cookiesAccepted"] != 1)) {
        InfoList::addInfo(new Info(Config::configArr["strings"]["cookieText"], Config::configArr["strings"]["cookieButton"], "","green"));
    }
}

function assignInfos($smarty) {
    $smarty->assign("infos", InfoList::getInfosArray());
}
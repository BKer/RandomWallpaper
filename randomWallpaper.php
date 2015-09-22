<?php
/**
 * Script to change the wallpaper with a random wallpaper from wallhaven.cc
 *
 * PHP Version 5.6 (Probably earlier as well)
 *
 * @category Wallpapers
 * @package  Comtilaacloudgarlicbke
 * @author   Bart Kerkvliet <bkerkvliet@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT
 * @version  GIT: $id$
 * @link     https://www.github.com/BKer/RandomWallpaper
 */
error_reporting(0);

// @TODO Support days?
// @TODO Allow to not remove the images.
// @TODO Proper rewrite :D

require "vendor/autoload.php";

use Wallhaven\Category;
use Wallhaven\Order;
use Wallhaven\Purity;
use Wallhaven\Sorting;
use Wallhaven\Wallhaven;

// Settings
$account = [
    "username" => "YourUsername",
    "password" => "YourPassword"
];

$default = [
    "keywords" => "",
    "categories" => Category::PEOPLE | Category::GENERAL,
    "purity" => Purity::ALL,
    "sorting" => Sorting::RANDOM,
    "resolutions" => ["1920x1080"],
    "ratios" => ["16x9"]
];

$time = [
    "work" => [
        "from" => "06:00",
        "keywords" => "landscape",
        "purity" => Purity::SFW
    ],
    "evening" => [
        "from" => "19:00",
        "purity" => Purity::SFW | Purity::SKETCHY
    ],
    "night-time" => [
        "from" => "22:00",
        "purity" => Purity::ALL
    ]
];

$downloadPath = "/tmp/phpWallHaven";
$fallbackPath = '/Some/Path/To/Wallpapers';

/**
 * Check connectivity with alpha.wallhaven.cc
 *
 * This function will open a connection with alpha.wallhaven.cc on port 80
 * If it can establish a connection it will return true, else it will
 * return false.
 *
 * @return boolean
 */
function isConnected()
{
    $connected = @fsockopen("alpha.wallhaven.cc", 80); 
    if ($connected) {
        $is_conn = true; //action when connected
        fclose($connected);
    } else {
        $is_conn = false; //action in connection failure
    }
    return $is_conn;
}

// Clean up files/images.
array_map('unlink', glob($downloadPath . '/wallhaven-*'));

if (isConnected()) {
    $wh = new Wallhaven($account["username"], $account["password"]);

    $result = [];
    $dt = new DateTime();
    foreach ($time as $t) {
        $fromTime = new DateTime($t['from']);
        if ($dt > $fromTime) {
            $result = array_merge($default, $t);
        } else {
            break;
        }
    }

    $wallpapers = $wh->filter()
        ->keywords($result['keywords'])
        ->categories($result["categories"])
        ->purity($result["purity"])
        ->sorting($result["sorting"])
        ->resolutions($result["resolutions"])
        ->ratios($result["ratios"])
        ->pages(1)
        ->getWallpapers();

    $id = $wallpapers[0]->getId();
    $url = $wallpapers[0]->getImageUrl();
    $fileName = trim(substr($url, strrpos($url, '/') + 1));

    mkdir($downloadPath, 0700, true);

    $wh->wallpaper($id)->download($downloadPath);

    shell_exec(
        "feh --bg-fill '" .
        escapeshellarg($downloadPath) . "/" .
        escapeshellarg($fileName) . "'"
    );
} else {
    // @HACK Just quick and dirty hack :D
    shell_exec(
        "feh --bg-fill --randomize --recursive '" .
        escapeshellarg($fallbackPath) . "'"
    );
}
?>
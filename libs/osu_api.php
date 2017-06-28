<?php

$key = "<osu!ApiKey>";

// Beatmap
function get_beatmaps($key,$beatmap_id)
{
    $url = "https://osu.ppy.sh/api/get_beatmaps?k=$key&b=$beatmap_id";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, $url);
    $get_beatmaps = curl_exec($curl);
    curl_close($curl);

    $get_beatmaps = json_decode($get_beatmaps, true);
    return $get_beatmaps;
}

// User
function get_user($key,$username,$mode=0)
{
    $url = "https://osu.ppy.sh/api/get_user?k=$key&u=$username&m=$mode";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, $url);
    $get_user = curl_exec($curl);
    curl_close($curl);

    $get_user = json_decode($get_user, true);
    return $get_user;
}

// Scores
function get_scores($key,$beatmap_id,$username)
{
    $url = "https://osu.ppy.sh/api/get_scores?k=$key&b=$beatmap_id&u=$username&limit=$limit";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, $url);
    $get_scores = curl_exec($curl);
    curl_close($curl);

    $get_scores = json_decode($get_scores, true);
    return $get_scores;
}

// Best Performance
function get_user_best($key,$username,$mode=0)
{
    $url = "https://osu.ppy.sh/api/get_user_best?k=$key&u=$username&m=$mode";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, $url);
    $get_user_best = curl_exec($curl);
    curl_close($curl);

    $get_user_best = json_decode($get_user_best, true);
    return $get_user_best;
}

// Recently Played
function get_user_recent($key,$username,$mode=0)
{
    $url = "https://osu.ppy.sh/api/get_user_recent?k=$key&u=$username&m=$mode";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, $url);
    $get_user_recent = curl_exec($curl);
    curl_close($curl);

    $get_user_recent = json_decode($get_user_recent, true);
    return $get_user_recent;
}

// Multiplayer
function get_match($key,$mp)
{
    $url = "https://osu.ppy.sh/api/get_match?k=$key&mp=$mp";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, $url);
    $get_match = curl_exec($curl);
    curl_close($curl);

    $get_match = json_decode($get_match, true);
    return $get_match;
}

// Get replay data
function get_replay($key,$mode,$beatmap_id,$username)
{
    $url = "https://osu.ppy.sh/api/get_match?k=$key&m=$mode&b=$beatmap_id&u=$username";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, $url);
    $get_replay = curl_exec($curl);
    curl_close($curl);

    $get_replay = json_decode($get_replay, true);
    return $get_replay;
}

$mods = array(
    "None"           => 0,
	"NoFail"         => 1,
	"Easy"           => 2,
	"NoVideo"        => 4,
	"Hidden"         => 8,
	"HardRock"       => 16,
	"SuddenDeath"    => 32,
	"DoubleTime"     => 64,
	"Relax"          => 128,
	"HalfTime"       => 256,
	"Nightcore"      => 512,
	"Flashlight"     => 1024,
	"Autoplay"       => 2048,
	"SpunOut"        => 4096,
	"Relax2"         => 8192,
	"Perfect"        => 16384,
	"Key4"           => 32768,
	"Key5"           => 65536,
	"Key6"           => 131072,
	"Key7"           => 262144,
	"Key8"           => 524288,
	"FadeIn"         => 1048576,
	"Random"         => 2097152,
	"LastMod"        => 4194304,
	"Key9"           => 16777216,
	"Key10"          => 33554432,
	"Key1"           => 67108864,
	"Key3"           => 134217728,
	"Key2"           => 268435456
);
?>

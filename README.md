# osu-no-name

__The cloud for replays__

You can share your replays or just save them privately.
This is a project I had the idea to do in order to deepen my knowledge, I thought it might be useful to some players.

## Features

* Sign up / Log in
* Upload a replay
* Public or Private
* Manage your replays (View public/private,delete or edit them)
* Download counter

## Features i want to implement

* Add a Youtube link
* Add Favorites
* Add Leaderboard
* Add search bar
* Add some cool badges

## Problems encountered

* I can't fetch replay data (Like the name, maxcombo, mods etc..) So i add an input "beatmap_id" to fetch the data.
I can't use the api/get_scores because with that technique i can't fetch the data of an unranked beatmap. I plan to update the upload.php
file soon.

## Installation

* Replace `<dbName>` and `<password>` in `/libs/db.php`
* Replace `<osu!ApiKey>` with your API key here : https://osu.ppy.sh/p/api in `/libs/osu_api.php`

<?php
// this expects $user (with $user['USER_ID']) to already be set by the including page

$profileDir = __DIR__ . "/profile-pictures/{$user['USER_ID']}/";
$webProfileDir = "../profile-pictures/{$user['USER_ID']}/";

$profilePath = "../profile-pictures/default-profile.png"; // fallback

foreach (["pfp.png", "pfp.jpg"] as $filename) {
    if (file_exists($profileDir . $filename)) {
        $profilePath = $webProfileDir . $filename;
        break;
    }
}
?>
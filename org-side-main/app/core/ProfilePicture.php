<?php

class ProfilePicture
{
    public static function resolve($conn, $user, $userId)
    {
        require __DIR__ . "/../../../profile-picture.php";

        return $profilePath;
    }
}

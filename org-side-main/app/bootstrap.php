<?php
session_start();

require_once __DIR__ . "/core/Database.php";
require_once __DIR__ . "/core/Auth.php";
require_once __DIR__ . "/core/ProfilePicture.php";
require_once __DIR__ . "/core/ImageUploader.php";

require_once __DIR__ . "/models/UserModel.php";
require_once __DIR__ . "/models/EventModel.php";

require_once __DIR__ . "/controllers/DashboardController.php";
require_once __DIR__ . "/controllers/EventController.php";
require_once __DIR__ . "/controllers/OrganizationController.php";

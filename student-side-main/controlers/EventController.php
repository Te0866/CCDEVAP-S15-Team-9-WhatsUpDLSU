
<?php

session_start();

require_once "../../dbconnection.php";
require_once "../models/EventModel.php";

header("Content-Type: application/json");

$model=new EventModel($conn);

$action=$_GET["action"] ?? "";

switch($action)
{
    case "interested":

        echo json_encode(
            $model->getInterestedEvents($_SESSION["user_id"])
        );

        break;

    case "category":

        echo json_encode(
            $model->getCategoryStats()
        );

        break;

    case "popular":

        echo json_encode(
            $model->getPopularEvents()
        );

        break;
}

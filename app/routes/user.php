<?php


// Profile
$app->get("/u/{username}", "UserController:dashboard")->setName("user.profile");
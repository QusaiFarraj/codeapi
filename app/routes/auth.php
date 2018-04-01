<?php

// Login
$app->get("/login", "UserController:login")->setName("login")->add($app->getContainer()->get("GuestMiddleware"));
$app->post("/login", "UserController:login")->setName("login.post")->add($app->getContainer()->get("GuestMiddleware"));

//activate
$app->get('/activate', 'UserController:activate')->setName('activate')->add($app->getContainer()->get("GuestMiddleware"));
$app->get('/activate/resend', 'UserController:resendActivation')->setName('activate.resend')->add($app->getContainer()->get("GuestMiddleware"));

// Logout
$app->get("/logout", "UserController:logout")->setName("logout")->add($app->getContainer()->get("AuthMiddleware"));

// Register
$app->get("/register", "UserController:register")->setName("register")->add($app->getContainer()->get("GuestMiddleware"));
$app->post("/register", "UserController:register")->setName("register.post")->add($app->getContainer()->get("GuestMiddleware"));

// Password Reset
$app->get("/password-reset", "UserController:resetPassword")->setName("password.reset")->add($app->getContainer()->get("GuestMiddleware"));
$app->post("/password-reset", "UserController:resetPassword")->setName('password.reset.post')->add($app->getContainer()->get("GuestMiddleware"));

// Password Recover
$app->get("/recover-password", "UserController:recoverPassword")->setName("password.recover")->add($app->getContainer()->get("GuestMiddleware"));
$app->post("/recover-password", "UserController:recoverPassword")->setName('password.recover.post')->add($app->getContainer()->get("GuestMiddleware"));

// Password Change
$app->get("/change-password", "UserController:changePassword")->setName('password.change')->add($app->getContainer()->get("AuthMiddleware"));
$app->post("/change-password", "UserController:changePassword")->setName('password.change.post')->add($app->getContainer()->get("AuthMiddleware"));

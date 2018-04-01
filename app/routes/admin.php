<?php


$app->group('/admin', function (){
    
    // Dashboard
    $this->map(['GET'], '[/dashboard]', 'AdminController:dashboard')->setName('admin.dashboard');

    // Users (list, add, delete)
    $this->map(['GET'], '/users[/]', 'AdminController:getUsers')->setName('admin.users');
    $this->map(['GET'], '/users/{userId}/edit[/]', 'AdminController:editUser')->setName('admin.users.edit');
    $this->map(['GET', 'POST'], '/users/{userId}/delete[/]', 'AdminController:deleteUser')->setName('admin.users.delete');
    // $this->route(['GET', 'POST'], '/users/{userId}/edit/profile', 'AdminController:editUser')->setName('admin.users.edit.profile');
    
    // $this->route(['GET', 'POST'], '/users/{userId}/edit/settings', 'AdminController:settingUser')->setName('admin.users.edit.settings');
    // $this->route(['POST'], '/users/{userId}/update/role/{role}/{action}', 'AdminController:updateRole')->setName('admin.users.update.role');
})->add("AdminMiddleware");

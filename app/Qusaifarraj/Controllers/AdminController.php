<?php

namespace Qusaifarraj\Controllers;

use \Qusaifarraj\Models\User as User;
use Carbon\Carbon;

/**
* 
*/
class AdminController extends Controller
{
    public function dashboard($request, $response)
    {
        return $this->view->render($response, 'admin/dashboard.twig');
    }

    public function getUsers($request, $response)
    {
        $users = $this->user->all();
        return $this->view->render($response, 'admin/users.twig', ['users' => $users]);
    }

    // public function editUsers($request, $response)
    // {
    //     $users = $this->user->all();
    //     return $this->view->render($response, 'admin/users/edit.twig', ['users' => $users]);
    // }

    public function editUser($request, $response, $args)
    {
        $router = $this->router;
        
        if ($request->isPost()) {
            $body = $request->getParsedBody();
            $firstName = $body['first_name'];
            $lastName = $body['last_name'];

            $user = $this->user->where('id', $userId);
            $user->update($body);

            if (!$user) {
                $this->flash->addMessage('global', 'Error! Couldn\'t update user!');
                $errors = $user;
                return $this->view->render($response, 'admin/users/edit.twig', ['errors' => $errors]);
            }

            $this->flash->addMessage('global', 'User has been updated!');
            return $response->withRedirect($router->pathFor('admin.users'));
        } else {
            $username = $args['username'];
            $user = $this->user->where('username', $username)->first();
            if (!$user) {
                return $response->withRedirect($router->pathFor('error.404'));
            }
            return $this->view->render($response, 'admin/users/edit.twig', ['user' => $user]);
        }
    }

    public function deleteUser($request, $response, $args)
    {
        $router = $this->router;
        
        if ($request->isPost()) {
            
            $body = $request->getParsedBody();
            $userId = $body['userId'];

            $user = $this->user->where('id', $userId);
            $user->delete();

            if (!$user) {
                $this->flash->addMessage('global', 'Error! Couldn\'t delete user!');
                $errors = $user;
                return $this->view->render($response, 'admin/users/delete.twig', ['errors' => $errors]);
            }

            $this->flash->addMessage('global', 'User has been deleted!');
            return $response->withRedirect($router->pathFor('admin.users'));
        } else {
            $username = $args['username'];
            $user = $this->user->where('username', $username)->first();
            if (!$user) {
                return $response->withRedirect($router->pathFor('error.404'));
            }
            return $this->view->render($response, 'admin/users/delete.twig', ['user' => $user]);
        }
    }
}

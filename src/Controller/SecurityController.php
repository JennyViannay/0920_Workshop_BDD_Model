<?php

namespace App\Controller;

use App\Model\AdopterManager;
use App\Model\UserManager;

class SecurityController extends AbstractController
{
    public function login()
    {
        $userManager = new UserManager();
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['email']) && !empty($_POST['password'])) {
                $user = $userManager->search($_POST['email']);
                if ($user) {
                    if ($user->password === md5($_POST['password'])) {
                        $_SESSION['user'] = $user;;
                        header('Location:/');
                    } else {
                        $error = 'Password incorrect !';
                    }
                } else {
                    $error = 'User not found';
                }
            } else {
                $error = 'Tous les champs sont obligatoires !';
            }
        }
        return $this->twig->render('Security/login.html.twig', [
            'error' => $error
        ]);
    }

    public function register()
    {
        $userManager = new UserManager();
        $adopterManager = new AdopterManager();
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (
                !empty($_POST['email']) &&
                !empty($_POST['password']) &&
                !empty($_POST['password2'])
            ) {
                $user = $userManager->search($_POST['email']);
                if ($user) {
                    $error = 'Email already exist';
                }
                if ($_POST['password'] != $_POST['password2']) {
                    $error = 'Password do not match';
                }
                if ($error === null) {
                    $timestamp = strtotime($_POST['birthday']);
                    $date_formated = date('Y-m-d', $timestamp);
                    $adopter = [
                        'firstname' => $_POST['firstname'],
                        'lastname' => $_POST['lastname'],
                        'birthday' => $date_formated,
                        'address' => $_POST['address']
                    ];
                    $adopterId = $adopterManager->insert($adopter);

                    $payload = [
                        'email' => $_POST['email'],
                        'password' => md5($_POST['password']),
                        'adopter_id' => $adopterId,
                        'role_id' => 2
                    ];
                    $idUser = $userManager->insert($payload);
                    $user = $userManager->selectOneById($idUser);
                    if ($user) {
                        $_SESSION['user'] = $user;
                        $_SESSION['adopter_id'] = $adopterId;
                        header('Location:/');
                    }
                }
            }
        }
        return $this->twig->render('Security/register.html.twig', [
            'error' => $error
        ]);
    }

    public function logout()
    {
        session_destroy();
        header('Location:/');
    }
}

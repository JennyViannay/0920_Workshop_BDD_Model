<?php

namespace App\Controller;

use App\Model\UserManager;
use App\Model\AdopterManager;
use App\Model\AnimalManager;

/**
 * Class UserController
 *
 */
class UserController extends AbstractController
{


    /**
     * Display race listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function account()
    {
        $animalManager = new AnimalManager();
        $user = $this->getUser();
        $animals = $animalManager->selectByAdopter($user['adopter']['id']);
        return $this->twig->render('User/account.html.twig', [
            'user' => $user['user'],
            'adopter' => $user['adopter'],
            'animals' => $animals ? $animals : ''
        ]);
    }


    /**
     * Display race edition page specified by $id
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function edit(): string
    {
        $error = null;
        $userManager = new UserManager();
        $adopterManager = new AdopterManager();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (
                !empty($_POST['email']) &&
                !empty($_POST['password']) &&
                !empty($_POST['password2'])
                ) {
                $user = $userManager->search($_POST['email']);
                if ($user && $user->email != $_POST['email']) {
                    $error = 'Email already exist';
                }
                if ($_POST['password'] != $_POST['password2']) {
                    $error = 'Password do not match';
                }
                if ($error === null) {
                    $timestamp = strtotime($_POST['birthday']);
                    $date_formated = date('Y-m-d', $timestamp);
                    $adopter = [
                        'id' => $_POST['adopter_id'],
                        'firstname' => $_POST['firstname'],
                        'lastname' => $_POST['lastname'],
                        'birthday' => $date_formated,
                        'address' => $_POST['address']
                    ];
                    $adopterManager->update($adopter);

                    $payload = [
                        'id' => $_POST['id'],
                        'email' => $_POST['email'],
                        'password' => md5($_POST['password']),
                        'adopter_id' => $adopter['id']
                    ];
                    $userManager->update($payload);
                    $_SESSION['user'] = $user;
                    header('Location:/user/account');
                }
            }
        }
        return $this->twig->render('User/edit.html.twig', [
            'user' => $this->getUser(),
            'error' => null
        ]);
    }

    public function getUser()
    {
        $userManager = new UserManager();
        $adopterManager = new AdopterManager();
        $userSession = $_SESSION['user'];
        $user = $userManager->selectOneById($userSession->id);
        $adopter = $adopterManager->selectOneById($userSession->adopter_id);
        return $user = [
            'user' => $user,
            'adopter' => $adopter,
        ];
    }

    // /**
    //  * Handle race deletion
    //  *
    //  * @param int $id
    //  */
    // public function delete(int $id)
    // {
    //     $user = $this->getUser();
    //     $userManager = new UserManager();
    //     $userManager->delete($user['user']->id);
    //     header('Location:/race/index');
    // }
}

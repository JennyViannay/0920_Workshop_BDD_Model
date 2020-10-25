<?php

namespace App\Controller;

use App\Model\AdopterManager;

/**
 * Class AdopterController
 *
 */
class AdopterController extends AbstractController
{


    /**
     * Display adopter listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $adopterManager = new AdopterManager();
        $adopters = $adopterManager->selectAll();

        return $this->twig->render('Adopter/index.html.twig', ['adopters' => $adopters]);
    }


    /**
     * Display adopter informations specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function show(int $id)
    {
        $adopterManager = new AdopterManager();
        $adopter = $adopterManager->selectOneById($id);

        return $this->twig->render('Adopter/show.html.twig', ['adopter' => $adopter]);
    }


    /**
     * Display adopter edition page specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function edit(int $id): string
    {
        $adopterManager = new AdopterManager();
        $adopter = $adopterManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $timestamp = strtotime($_POST['birthday']);
            $date_formated = date('Y-m-d', $timestamp);
            $adopter = [
                'id' => $_POST['id'],
                'firstname' => $_POST['firstname'],
                'lastname' => $_POST['lastname'],
                'birthday' => $date_formated,
                'address' => $_POST['address']
            ];

            $adopterManager->update($adopter);
            header('Location:/adopter/show/' . $id);
        }

        return $this->twig->render('Adopter/edit.html.twig', ['adopter' => $adopter]);
    }


    /**
     * Display specie creation page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function add()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $adopterManager = new AdopterManager();
            $timestamp = strtotime($_POST['birthday']);
            $date_formated = date('Y-m-d', $timestamp);
            $adopter = [
                'firstname' => $_POST['firstname'],
                'lastname' => $_POST['lastname'],
                'birthday' => $date_formated,
                'address' => $_POST['address']
            ];
            $id = $adopterManager->insert($adopter);
            header('Location:/adopter/show/' . $id);
        }

        return $this->twig->render('Adopter/add.html.twig');
    }



    /**
     * Handle Adopter deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $adopterManager = new AdopterManager();
        $adopterManager->delete($id);
        header('Location:/adopter/index');
    }
}

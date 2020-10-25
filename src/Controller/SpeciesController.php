<?php

namespace App\Controller;

use App\Model\SpeciesManager;

/**
 * Class SpeciesController
 *
 */
class SpeciesController extends AbstractController
{


    /**
     * Display specie listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $speciesManager = new SpeciesManager();
        $species = $speciesManager->selectAll();

        return $this->twig->render('Species/index.html.twig', ['speciess' => $species]);
    }


    /**
     * Display specie informations specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function show(int $id)
    {
        $speciesManager = new SpeciesManager();
        $species = $speciesManager->selectOneById($id);

        return $this->twig->render('Species/show.html.twig', ['species' => $species]);
    }


    /**
     * Display specie edition page specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function edit(int $id): string
    {
        $speciesManager = new SpeciesManager();
        $species = $speciesManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $species = [
                'id' => $_POST['id'],
                'name' => $_POST['name']
            ];
            $speciesManager->update($species);
            header('Location:/species/show/' . $id);
        }

        return $this->twig->render('Species/edit.html.twig', ['species' => $species]);
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
            $speciesManager = new SpeciesManager();
            $species = [
                'name' => $_POST['name']
            ];
            $id = $speciesManager->insert($species);
            header('Location:/species/show/' . $id);
        }

        return $this->twig->render('Species/add.html.twig');
    }


    /**
     * Handle species deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $speciesManager = new SpeciesManager();
        $speciesManager->delete($id);
        header('Location:/species/index');
    }
}

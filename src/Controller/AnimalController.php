<?php

namespace App\Controller;

use App\Model\AnimalManager;
use App\Model\RaceManager;
use App\Model\RefugeManager;

/**
 * Class AnimalController
 *
 */
class AnimalController extends AbstractController
{


    /**
     * Display animal listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $animalManager = new AnimalManager();
        $animals = $animalManager->selectAllWithDetail();

        return $this->twig->render('animal/index.html.twig', ['animals' => $animals]);
    }


    /**
     * Display animal informations specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function show(int $id)
    {
        $animalManager = new AnimalManager();
        $animal = $animalManager->selectOneWithDetails($id);

        return $this->twig->render('Animal/show.html.twig', ['animal' => $animal]);
    }


    /**
     * Display animal edition page specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function edit(int $id): string
    {
        $animalManager = new AnimalManager();
        $animal = $animalManager->selectOneWithDetails($id);

        $refugeManager = new RefugeManager();
        $refuges = $refugeManager->selectAll();

        $raceManager = new RaceManager();
        $races = $raceManager->selectAllWithDetail();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $vaccined = isset($_POST['is_vaccined']) ? true : false;
            $sterilized = isset($_POST['is_sterilized']) ? true : false;
            $animal = [
                'id' => $_POST['id'],
                'name' => $_POST['name'],
                'age' => $_POST['age'],
                'tattoo' => $_POST['tattoo'],
                'is_sterilized' => $sterilized,
                'is_vaccined' => $vaccined,
                'race_id' => $_POST['race_id'],
                'refuge_id' => $_POST['refuge_id'],
            ];
            
            $animalManager->update($animal);
            header('Location:/animal/show/' . $id);
        }

        return $this->twig->render('Animal/edit.html.twig', [
            'animal' => $animal,
            'races' => $races,
            'refuges' => $refuges
        ]);
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
        $refugeManager = new RefugeManager();
        $refuges = $refugeManager->selectAll();

        $raceManager = new RaceManager();
        $races = $raceManager->selectAllWithDetail();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $vaccined = isset($_POST['is_vaccined']) ? true : false;
            $sterilized = isset($_POST['is_sterilized']) ? true : false;
            $animalManager = new AnimalManager();
            $animal = [
                'name' => $_POST['name'],
                'age' => $_POST['age'],
                'tattoo' => $_POST['tattoo'],
                'is_sterilized' => $sterilized,
                'is_vaccined' => $vaccined,
                'race_id' => $_POST['race_id'],
                'refuge_id' => $_POST['refuge_id'],
            ];
            $id = $animalManager->insert($animal);
            header('Location:/animal/show/' . $id);
        }

        return $this->twig->render('Animal/add.html.twig', [
            'races' => $races,
            'refuges' => $refuges
        ]);
    }


    /**
     * Handle animal deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $animalManager = new AnimalManager();
        $animalManager->delete($id);
        header('Location:/animal/index');
    }
}

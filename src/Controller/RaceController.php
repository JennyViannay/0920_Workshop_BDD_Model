<?php

namespace App\Controller;

use App\Model\RaceManager;
use App\Model\SpeciesManager;

/**
 * Class RefugeController
 *
 */
class RaceController extends AbstractController
{


    /**
     * Display race listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $raceManager = new RaceManager();
        $races = $raceManager->selectAllWithDetail();

        return $this->twig->render('Race/index.html.twig', ['races' => $races]);
    }


    /**
     * Display race informations specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function show(int $id)
    {
        $raceManager = new RaceManager();
        $race = $raceManager->selectOneWithDetails($id);

        return $this->twig->render('Race/show.html.twig', ['race' => $race]);
    }


    /**
     * Display race edition page specified by $id
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
        $species = $speciesManager->selectAll();

        $raceManager = new RaceManager();
        $race = $raceManager->selectOneWithDetails($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $race = [
                'id' => $_POST['id'],
                'name' => $_POST['name'],
                'species_id' => $_POST['species']
            ];
            $raceManager->update($race);
            header('Location:/race/show/' . $id);
        }

        return $this->twig->render('Race/edit.html.twig', [
            'race' => $race,
            'speciess' => $species
        ]);
    }


    /**
     * Display race creation page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function add()
    {
        $speciesManager = new SpeciesManager();
        $species = $speciesManager->selectAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $raceManager = new RaceManager();
            $race = [
                'name' => $_POST['name'],
                'species_id' => $_POST['species']
            ];
            $id = $raceManager->insert($race);
            header('Location:/race/show/' . $id);
        }

        return $this->twig->render('Race/add.html.twig', [
            'speciess' => $species
        ]);
    }


    /**
     * Handle race deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $raceManager = new RaceManager();
        $raceManager->delete($id);
        header('Location:/race/index');
    }
}

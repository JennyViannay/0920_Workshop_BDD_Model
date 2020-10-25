<?php
/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

use App\Model\AdopterManager;
use App\Model\AnimalManager;
use DateTime;

class HomeController extends AbstractController
{

    /**
     * Display home page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $animalManager = new AnimalManager();
        $animalManager = new AnimalManager();
        $animals = $animalManager->selectAllWithDetail();
        $allAnimals = [];
        foreach ($animals as $animal) {
            $images = $animalManager->getImagesAnimal($animal['id']);
            if($images){
                $animal['image'] = $images[0]['url'];
                array_push($allAnimals, $animal);
            } else {
                array_push($allAnimals, $animal);
            }
        }
        return $this->twig->render('Home/index.html.twig', [
            'animals' => $allAnimals
        ]);
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
        $images = $animalManager->getImagesAnimal($id);

        return $this->twig->render('Home/show.html.twig', [
            'animal' => $animal,
            'images' => $images
        ]);
    }

    /**
     * Adopting animal informations specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function adopting(int $id)
    {
        if($_SESSION['user']){
            $animalManager = new AnimalManager();
            $animal = $animalManager->selectOneWithDetails($id);
            $createdAt = date('Y-m-d H:i:s', $timestamp = time());
            $animal = [@@       
                'id' => $id,
                'adopted_on' => $createdAt,
                'adopter_id' => $_SESSION['user']->adopter_id
            ];
            $animalManager->isAdopting($animal);
            header('Location:/home/success/' . $id);
        } else {
            header('Location:/security/login');
        }

        return $this->twig->render('Home/adopting.html.twig',[
            'animal' => $animal
        ]);
    }
}

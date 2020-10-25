<?php

namespace App\Controller;

use App\Model\AnimalManager;
use App\Model\ImageManager;
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
        
        return $this->twig->render('animal/index.html.twig', ['animals' => $allAnimals]);
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

        return $this->twig->render('Animal/show.html.twig', [
            'animal' => $animal,
            'images' => $images
        ]);
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
        $images = $animalManager->getImagesAnimal($id);

        $refugeManager = new RefugeManager();
        $refuges = $refugeManager->selectAll();

        $raceManager = new RaceManager();
        $races = $raceManager->selectAllWithDetail();

        $imageManager = new ImageManager();
        
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

            if(isset($_POST['image_id_1']) && isset($_POST['image1'])){
                $image = [
                    'url' => $_POST['image1'],
                    'id' => $_POST['image_id_1']
                ];
                $imageManager->update($image);
            } elseif(!isset($_POST['image_id_1']) && isset($_POST['image1'])) {
                $image = $_POST['image1'];
                $imageManager->insert($image, $animal['id']);
            }
            if(isset($_POST['image_id_2']) && isset($_POST['image2'])){
                $image = [
                    'url' => $_POST['image2'],
                    'id' => $_POST['image_id_2']
                ];
                $imageManager->update($image);
            } elseif(!isset($_POST['image_id_2']) && isset($_POST['image2'])) {
                $image = $_POST['image2'];
                $imageManager->insert($image, $animal['id']);
            }
            if(isset($_POST['image_id_3']) && isset($_POST['image3'])){
                $image = [
                    'url' => $_POST['image3'],
                    'id' => $_POST['image_id_3']
                ];
                $imageManager->update($image);
            } elseif(!isset($_POST['image_id_3']) && isset($_POST['image3'])) {
                $image = $_POST['image3'];
                $imageManager->insert($image, $animal['id']);
            }
            
            header('Location:/animal/show/' . $id);
        }

        return $this->twig->render('Animal/edit.html.twig', [
            'animal' => $animal,
            'races' => $races,
            'refuges' => $refuges,
            'images' => $images,
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
        $animalManager = new AnimalManager();
        $imageManager = new ImageManager();
        $refugeManager = new RefugeManager();
        $raceManager = new RaceManager();
        
        $refuges = $refugeManager->selectAll();
        $races = $raceManager->selectAllWithDetail();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $vaccined = isset($_POST['is_vaccined']) ? true : false;
            $sterilized = isset($_POST['is_sterilized']) ? true : false;
            $animal = [
                'name' => $_POST['name'],
                'age' => $_POST['age'],
                'tattoo' => $_POST['tattoo'],
                'is_sterilized' => $sterilized,
                'is_vaccined' => $vaccined,
                'race_id' => $_POST['race_id'],
                'refuge_id' => $_POST['refuge_id'],
            ];
            $idAnimal = $animalManager->insert($animal);

            if(isset($_POST['image1'])){
                $image = $_POST['image1'];
                $imageManager->insert($image, $idAnimal);
            }
            if(isset($_POST['image2'])){
                $image = $_POST['image2'];
                $imageManager->insert($image, $idAnimal);
            }
            if(isset($_POST['image3'])){
                $image = $_POST['image3'];
                $imageManager->insert($image, $idAnimal);
            }

            header('Location:/animal/show/' . $idAnimal);
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
        $imageManager = new ImageManager();
        $animalManager = new AnimalManager();
        $images = $animalManager->getImagesAnimal($id);
        foreach ($images as $image) {
            $imageManager->delete($image['id']);
        }
        $animalManager->delete($id);
        header('Location:/animal/index');
    }
}

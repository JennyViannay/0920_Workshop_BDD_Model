<?php

namespace App\Controller;

use App\Model\ImageManager;

/**
 * Class ImageController
 *
 */
class ImageController extends AbstractController
{

    /**
     * Handle image deletion
     *
     * @param int $id
     */
    public function delete(int $idAnimal, int $id)
    {
        $imageManager = new ImageManager();
        $imageManager->delete($id);
        header('Location:/animal/show/' .$idAnimal);
    }
}

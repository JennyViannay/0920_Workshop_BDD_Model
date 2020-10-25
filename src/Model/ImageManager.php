<?php

namespace App\Model;

/**
 *
 */
class ImageManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'image';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }


    /**
     * @param array $image
     * @return int
     */
    public function insert(string $image, int $id): int
    {
        // prepared request
        $statement = $this->pdo->prepare(
            "INSERT INTO " . self::TABLE . " (`url`, `animal_id`) VALUES (:url, :animal_id)"
        );
        $statement->bindValue('url', $image, \PDO::PARAM_STR);
        $statement->bindValue('animal_id', $id, \PDO::PARAM_INT);

        if ($statement->execute()) {
            return (int) $this->pdo->lastInsertId();
        }
    }


    /**
     * @param int $id
     */
    public function delete(int $id): void
    {
        // prepared request
        $statement = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }


    /**
     * @param array $image
     * @return bool
     */
    public function update(array $image): bool
    {

        // prepared request
        $statement = $this->pdo->prepare(
            "UPDATE " . self::TABLE . " SET `url` = :url WHERE id=:id"
        );
        $statement->bindValue('id', $image['id'], \PDO::PARAM_INT);
        $statement->bindValue('url', $image['url'], \PDO::PARAM_STR);

        return $statement->execute();
    }

}

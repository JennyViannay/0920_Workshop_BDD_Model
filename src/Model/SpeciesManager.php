<?php

namespace App\Model;

/**
 *
 */
class SpeciesManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'species';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }


    /**
     * @param array $species
     * @return int
     */
    public function insert(array $species): int
    {
        // prepared request
        $statement = $this->pdo->prepare(
            "INSERT INTO " . self::TABLE . " (`name`) VALUES (:name)"
        );
        $statement->bindValue('name', $species['name'], \PDO::PARAM_STR);

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
     * @param array $species
     * @return bool
     */
    public function update(array $species): bool
    {

        // prepared request
        $statement = $this->pdo->prepare(
            "UPDATE " . self::TABLE . " SET `name` = :name WHERE id=:id"
        );
        $statement->bindValue('id', $species['id'], \PDO::PARAM_INT);
        $statement->bindValue('name', $species['name'], \PDO::PARAM_STR);

        return $statement->execute();
    }
}

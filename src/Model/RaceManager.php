<?php

namespace App\Model;

/**
 *
 */
class RaceManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'race';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }


    /**
     * @param array $race
     * @return int
     */
    public function insert(array $race): int
    {
        // prepared request
        $statement = $this->pdo->prepare(
            "INSERT INTO " . self::TABLE . " (`name`, `species_id`) VALUES (:name, :species_id)"
        );
        $statement->bindValue('name', $race['name'], \PDO::PARAM_STR);
        $statement->bindValue('species_id', $race['species_id'], \PDO::PARAM_INT);

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
     * @param array $race
     * @return bool
     */
    public function update(array $race): bool
    {

        // prepared request
        $statement = $this->pdo->prepare(
            "UPDATE " . self::TABLE . " SET `name` = :name, `species_id` = :species_id WHERE id=:id"
        );
        $statement->bindValue('id', $race['id'], \PDO::PARAM_INT);
        $statement->bindValue('name', $race['name'], \PDO::PARAM_STR);
        $statement->bindValue('species_id', $race['species_id'], \PDO::PARAM_INT);

        return $statement->execute();
    }

    public function selectAllWithDetail(): array
    {
        return $this->pdo->query('
        SELECT race.id, race.name, race.species_id as species_id, species.name as species_name FROM '
        . $this->table .
        ' INNER JOIN species ON race.species_id=species.id')->fetchAll();
    }

    public function selectOneWithDetails(int $id)
    {
        // prepared request
        $statement = $this->pdo->prepare("
        SELECT race.id, race.name, race.species_id as species_id, species.name as species_name FROM 
        $this->table 
        INNER JOIN species ON race.species_id=species.id WHERE race.id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }
}

<?php

namespace App\Model;

/**
 *
 */
class AnimalManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'animal';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }


    /**
     * @param array $animal
     * @return int
     */
    public function insert(array $animal): int
    {
        // prepared request
        $statement = $this->pdo->prepare(
            "INSERT INTO " . self::TABLE . " 
            (`name`,`age`,`tattoo`,`is_sterilized`,`is_vaccined`,`race_id`,`refuge_id`) 
            VALUES 
            (:name, :age, :tattoo, :is_sterilized, :is_vaccined, :race_id, :refuge_id)"
        );
        $statement->bindValue('name', $animal['name'], \PDO::PARAM_STR);
        $statement->bindValue('age', $animal['age'], \PDO::PARAM_STR);
        $statement->bindValue('tattoo', $animal['tattoo'], \PDO::PARAM_INT);
        $statement->bindValue('is_sterilized', $animal['is_sterilized'], \PDO::PARAM_INT);
        $statement->bindValue('is_vaccined', $animal['is_vaccined'], \PDO::PARAM_INT);
        $statement->bindValue('race_id', $animal['race_id'], \PDO::PARAM_INT);
        $statement->bindValue('refuge_id', $animal['refuge_id'], \PDO::PARAM_INT);

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
     * @param array $animal
     * @return bool
     */
    public function update(array $animal): bool
    {

        // prepared request
        $statement = $this->pdo->prepare(
            "UPDATE " . self::TABLE . " SET 
            `name` = :name,
            `age` = :age,
            `tattoo` = :tattoo,
            `is_sterilized` = :is_sterilized,
            `is_vaccined` = :is_vaccined,
            `race_id` = :race_id,
            `refuge_id` = :refuge_id
            WHERE id=:id"
        );
        $statement->bindValue('id', $animal['id'], \PDO::PARAM_INT);
        $statement->bindValue('name', $animal['name'], \PDO::PARAM_STR);
        $statement->bindValue('age', $animal['age'], \PDO::PARAM_STR);
        $statement->bindValue('tattoo', $animal['tattoo'], \PDO::PARAM_INT);
        $statement->bindValue('is_sterilized', $animal['is_sterilized'], \PDO::PARAM_INT);
        $statement->bindValue('is_vaccined', $animal['is_vaccined'], \PDO::PARAM_INT);
        $statement->bindValue('race_id', $animal['race_id'], \PDO::PARAM_INT);
        $statement->bindValue('refuge_id', $animal['refuge_id'], \PDO::PARAM_INT);

        return $statement->execute();
    }

    public function selectAllWithDetail(): array
    {
        return $this->pdo->query("SELECT 
            animal.id,
            animal.name,
            animal.age,
            race.name as race_name,
            animal.race_id,
            refuge.name as refuge_name,
            animal.refuge_id,
            animal.is_sterilized,
            animal.is_vaccined,
            animal.tattoo,
            animal.adopted_on,
            animal.adopter_id
            FROM animal 
            INNER JOIN race ON animal.race_id=race.id
            INNER JOIN refuge ON animal.refuge_id=refuge.id")->fetchAll();
    }

    public function selectOneWithDetails(int $id)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT 
        animal.id,
        animal.name,
        animal.age,
        race.name as race_name,
        animal.race_id,
        refuge.name as refuge_name,
        animal.refuge_id,
        animal.is_sterilized,
        animal.is_vaccined,
        animal.tattoo,
        animal.adopted_on,
        animal.adopter_id
        FROM $this->table 
        INNER JOIN race ON animal.race_id=race.id
        INNER JOIN refuge ON animal.refuge_id=refuge.id 
        WHERE animal.id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    public function getImagesAnimal(int $id)
    {
        $statement = $this->pdo->prepare("SELECT * FROM image WHERE animal_id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * @param array $animal
     */
    public function isAdopting(array $animal): void
    {
        // prepared request
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE .
        " SET adopted_on=:adopted_on, adopter_id=:adopter_id WHERE id=:id"
        );
        $statement->bindValue('id', $animal['id'], \PDO::PARAM_INT);
        $statement->bindValue('adopted_on', $animal['adopted_on'], \PDO::PARAM_STR);
        $statement->bindValue('adopter_id', $animal['adopter_id'], \PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * @param int $id
     */
    public function selectByAdopter(int $id): array
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM " .$this->table ." WHERE adopter_id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }
}

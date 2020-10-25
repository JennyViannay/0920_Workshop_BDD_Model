<?php

namespace App\Model;

/**
 *
 */
class AdopterManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'adopter';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }


    /**
     * @param array $adopter
     * @return int
     */
    public function insert(array $adopter): int
    {
        // prepared request
        $statement = $this->pdo->prepare(
            "INSERT INTO " . self::TABLE . " 
            (`firstname`,`lastname`,`birthday`,`address`) 
            VALUES 
            (:firstname, :lastname, :birthday, :address)"
        );
        $statement->bindValue('firstname', $adopter['firstname'], \PDO::PARAM_STR);
        $statement->bindValue('lastname', $adopter['lastname'], \PDO::PARAM_STR);
        $statement->bindValue('birthday', $adopter['birthday'], \PDO::PARAM_STR);
        $statement->bindValue('address', $adopter['address'], \PDO::PARAM_STR);

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
     * @param array $adopter
     * @return bool
     */
    public function update(array $adopter): bool
    {

        // prepared request
        $statement = $this->pdo->prepare(
            "UPDATE " . self::TABLE . " SET 
            `firstname` = :firstname,
            `lastname` = :lastname,
            `birthday` = :birthday,
            `address` = :address
            WHERE id=:id"
        );
        $statement->bindValue('id', $adopter['id'], \PDO::PARAM_INT);
        $statement->bindValue('firstname', $adopter['firstname'], \PDO::PARAM_STR);
        $statement->bindValue('lastname', $adopter['lastname'], \PDO::PARAM_STR);
        $statement->bindValue('birthday', $adopter['birthday'], \PDO::PARAM_STR);
        $statement->bindValue('address', $adopter['address'], \PDO::PARAM_STR);

        return $statement->execute();
    }
}

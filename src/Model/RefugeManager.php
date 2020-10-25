<?php

namespace App\Model;

/**
 *
 */
class RefugeManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'refuge';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }


    /**
     * @param array $refuge
     * @return int
     */
    public function insert(array $refuge): int
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`name`, `address`) VALUES (:name, :address)");
        $statement->bindValue('name', $refuge['name'], \PDO::PARAM_STR);
        $statement->bindValue('address', $refuge['address'], \PDO::PARAM_STR);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
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
     * @param array $refuge
     * @return bool
     */
    public function update(array $refuge):bool
    {

        // prepared request
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `name` = :name, `address` = :address WHERE id=:id");
        $statement->bindValue('id', $refuge['id'], \PDO::PARAM_INT);
        $statement->bindValue('name', $refuge['name'], \PDO::PARAM_STR);
        $statement->bindValue('address', $refuge['address'], \PDO::PARAM_STR);

        return $statement->execute();
    }
}

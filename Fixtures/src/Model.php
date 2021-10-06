<?php

namespace App;

class Model
{

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Delete resource by pk
     *
     * @param int $id
     * @return \PDOStatement
     */
    public function delete(int $id)
    {
        $query = 'DELETE FROM user WHERE id=:id';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindparam(':id', $id);
        $stmt->execute();

        $stmt = null;
    }

    /**
     * Return all resources
     *
     * @return array resources
     */
    public function all()
    {
        $stmt = $this->pdo->query("SELECT * FROM user");

        return $stmt->fetchAll(\PDO::FETCH_CLASS, 'App\\User');
    }

    /**
     * Return all resources
     *
     * @return array resources
     */
    public function update(User $user)
    {
        $stmt = $this->pdo->prepare('UPDATE user SET username=:username WHERE id=:id');
        $stmt->execute([':username' => $user->username, ':id' => $user->id]);

        return $stmt->setFetchMode(\PDO::FETCH_CLASS, 'App\\User');
    }

    /**
     * @param array $id
     * @return mixed
     */
    public function find(int $id)
    {

        $query = 'SELECT * FROM user WHERE id=:id';

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':id' => $id]);

        return $stmt->fetchObject('App\\User');
    }

    /**
     * Return all resources
     * TODO
     * @return array resources
     */
    public function hydrate(array $users): void
    {
        $request = 'INSERT INTO user (username) VALUES (:values)';

        $stmt = $this->pdo->prepare($request);

        foreach ($users as $u) {
            $stmt->execute([':values' => $u['username']]);
        }

        $stmt = null;
    }

    public function save(User $user): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO user (username) VALUES (:username)');
        $stmt->execute([':username' => $user->username]);
    }
}

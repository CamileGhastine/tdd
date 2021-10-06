<?php

use PHPUnit\Framework\TestCase;

use App\{User, Model};
use Datas\DatasTrait;

class UserTest extends TestCase
{
  use DatasTrait;

  protected $model;

  public function setUp(): void
  {
    $this->pdo = new \PDO('sqlite::memory:');
    $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    $this->pdo->exec(
      "CREATE TABLE IF NOT EXISTS user
          (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username VARCHAR( 225 )
          )
            "
    );

    $this->model = new Model($this->pdo);
    $this->model->hydrate($this->getUsers());
  }

  /**
   * @test count method insert
   */
  public function testSeedsCreate()
  {
    $this->assertCount(3, $this->model->all());
  }

  /**
   * @test save method insert
   */
  public function testInsertSave()
  {
    $camile = new User();
    $username = 'Camile' . rand(0, 1000);
    $camile->username = $username;
    $this->model->save($camile);

    $lastEntryInDB =  $this->model->all()[array_key_last($this->model->all())];

    $this->assertSame($username,$lastEntryInDB->username);
  }

  /**
   * @test save method update
   */
  public function testUpdateSave()
  {
    $updateUser = $this->model->find(1);
    $updateUser->username = 'Camile';
    $this->model->update($updateUser);

    $this->assertSame('Camile', $this->model->find(1)->username);
  }

  /**
   * @test delete resource by id
   */
  public function testDelete()
  {
    $this->model->delete(1);

    $this->assertFalse($this->model->find(1));
  }
}

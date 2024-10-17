<?php
/**
 * Classe abstraite EntityRepository
 */
abstract class EntityRepository {
    protected $cnx;

    protected function __construct(){
        // Modifiez ici vos informations de connexion à la base de données
        $this->cnx = new PDO("mysql:host=localhost;dbname=lego;charset=utf8", "root", "");
    }

    abstract public function find($id);

    abstract public function findAll();

    abstract public function save($entity);

    abstract public function delete($id);

    abstract public function update($entity);
}
?>

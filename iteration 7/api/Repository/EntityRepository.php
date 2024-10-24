<?php
/**
 * Classe abstraite EntityRepository
 */
abstract class EntityRepository {
    protected $cnx;

    protected function __construct(){
        // Modifiez ici vos informations de connexion à la base de données
        $this->cnx = new PDO("mysql:host=54.37.68.129;dbname=lego;charset=utf8", "ubuntu", "8x6LG)u@2z");
    }

    abstract public function find($id);

    abstract public function findAll();

    abstract public function save($entity);

    abstract public function delete($id);

    abstract public function update($entity);
}
?>

<?php
class Type{
    private $db;
    private $insert;
    private $select;
    private $selectById;
    private $update;
    private $delete;

    public function __construct($db){
        $this->db = $db;
        $this->insert = $this->db->prepare("INSERT INTO type(libelle) VALUES(:libelle)");
        $this->select = $this->db->prepare("SELECT id, libelle FROM type ORDER BY id");
        $this->selectById = $this->db->prepare("SELECT id, libelle from type where id=:id");
        $this->update = $this->db->prepare('UPDATE type set libelle=:libelle where id=:id');
        $this->delete = $this->db->prepare('DELETE from type where id=:id');
    }
    public function insert($libelle){
        $r = true;
        $this->insert->execute(array(':libelle'=>$libelle));
        if ($this->insert->errorCode()!=0){
            print_r($this->insert->errorInfo());
            $r=false;
        }
        return $r;
    }
    public function select(){
        $this->select->execute();
        if ($this->select->errorCode()!=0){
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }

    public function selectById($id){
        $this->selectById->execute(array(':id'=>$id)); 
        if ($this->selectById->errorCode()!=0){
            print_r($this->selectById->errorInfo());
        }
        return $this->selectById->fetch();
    }

    public function update($id, $libelle){
        $r = true;
        $this->update->execute(array(':id'=>$id, ':libelle'=>$libelle));
        if ($this->update->errorCode() != 0){
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function delete($id){
        $r = true;
    }
}
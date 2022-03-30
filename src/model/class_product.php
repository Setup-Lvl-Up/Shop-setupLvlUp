<?php
class Product{
    private $db;
    private $insert;
    private $select;
    private $selectById;
    private $update;
    private $delete;
    private $selectLimit;
    private $selectCount;
    private $recherche;
    private $selectIn;

    public function __construct($db){
        $this->db = $db;
        $this->insert = $this->db->prepare("INSERT INTO produit(designation, description, prix, photo, idType) VALUES(:designation, :description, :prix, :photo, :idType)");
        $this->select = $db->prepare("SELECT p.id, designation, idType, description, prix, photo, t.libelle AS libelletype FROM produit p, type t WHERE p.idType  = t.id ORDER BY designation");
        $this->selectById = $this->db->prepare("SELECT id, designation, description, prix, photo, idType from produit where id=:id");
        $this->selectIn = $this->db->prepare("SELECT id, designation, description, prix, photo, idType from produit where FIND_IN_SET(id, :ids)");
        $this->update = $this->db->prepare("UPDATE produit SET designation=:designation, description=:description, prix=:prix, photo=:photo, idType=:idType WHERE id=:id");
        $this->delete = $this->db->prepare("DELETE from produit where id=:id");
        $this->selectLimit = $db->prepare("SELECT p.id, designation, idType, description, prix, photo, idType, t.libelle AS libelletype FROM produit p, type t WHERE p.idType  = t.id ORDER BY designation limit :inf, :limite");
        $this->selectCount =$db->prepare("SELECT count(*) as nb from produit");
        $this->recherche = $db->prepare("SELECT p.id,designation,description,prix,photo,t.libelle 
        as type from produit p,type t where p.idType = t.id and designation like :recherche order by designation");
    }

    public function insert($designation, $description, $prix, $photo, $idType){
        $r = true;
        $this->insert->execute(array(':designation'=>$designation, ':description'=>$description, ':prix'=>$prix, ':photo'=>$photo, ':idType'=>$idType));
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
    public function selectLimit($inf, $limite){
        $this->selectLimit->bindParam(':inf', $inf, PDO::PARAM_INT);
        $this->selectLimit->bindParam(':limite', $limite, PDO::PARAM_INT);
        $this->selectLimit->execute();
        if ($this->selectLimit->errorCode()!=0){
            print_r($this->selectLimit->errorInfo());
        }
        return $this->selectLimit->fetchAll();
    }
    public function selectCount(){
        $this->selectCount->execute();
        if ($this->selectCount->errorCode()!=0){
            print_r($this->selectCount->errorInfo());
        }
        return $this->selectCount->fetch();
    }
  
    public function selectIn($ids){
        $implose = implode(',', $ids);
        $this->selectIn->bindParam(':ids', $implose);
        $this->selectIn->execute();
        if ($this->selectIn->errorCode()!=0){
            print_r($this->selectIn->errorInfo());
        }
    return $this->selectIn->fetchAll();
    }

    public function update($id, $designation, $description, $prix, $photo, $idType){
        $r = true;
        $this->update->execute(array(':id'=>$id, ':designation'=>$designation,':description'=>$description,':prix'=>$prix, ':photo'=>$photo, ':idType'=>$idType));
        if ($this->update->errorCode() != 0){
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function delete($id){
        $r = true;
        $this->delete->execute(array(':id'=>$id));
        if ($this->delete->errorCode()!=0){
            print_r($this->delete->errorInfo());
            $r=false;
        }
        return $r;
    }

    public function recherche($recherche){
        $this->recherche->execute(array('recherche'=>'%'.$recherche.'%'));
        if ($this->recherche->errorCode()!=0) {
            print_r($this->recherche->errorInfo());
        }
        return $this->recherche->fetchAll();
    }
}
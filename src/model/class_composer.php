<?php
class Composer{
    private $db;
    private $insert;

    public function __construct($db){
        $this->db = $db;
        $this->insert = $this->db->prepare("INSERT INTO composer(idCommande, idProduit, qte) VALUES(:idCommande, :idProduit, :qte)");
    }

    public function insert($idCommande, $idProduit, $qte){
        $r = true;
        $this->insert->execute(array(':idCommande'=>$idCommande, ':idProduit'=>$idProduit, ':qte'=>$qte));
        if ($this->insert->errorCode()!=0){
            print_r($this->insert->errorInfo());
            $r=false;
        }
        return $r;
    }


}

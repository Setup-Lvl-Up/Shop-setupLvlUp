<?php
class User{
    private $db;
    private $insert;
    private $connect;
    private $select;
    private $selectById;
    private $selectByEmail;
    private $update;
    private $updateMdp;
    private $updateMail;
    private $delete;

    public function __construct($db){
        $this->db = $db;
        $this->insert = $this->db->prepare("INSERT INTO utilisateur(email, mdp, nom, prenom, idRole) VALUES (:email, :mdp, :nom, :prenom, :role)");
        $this->connect = $this->db->prepare("SELECT email, idRole, mdp FROM utilisateur WHERE email=:email");
        $this->select = $this->db->prepare("SELECT u.id, email, idRole, nom, prenom, r.libelle AS libellerole FROM utilisateur u, role r WHERE u.idRole = r.id ORDER BY nom");
        $this->selectById = $this->db->prepare("SELECT id, email, nom, prenom, idRole from utilisateur where id=:id");
        $this->selectByEmail = $this->db->prepare("SELECT id from utilisateur where email=:email");
        $this->update = $this->db->prepare("UPDATE utilisateur set nom=:nom, prenom=:prenom, idrole=:role where id=:id");
        $this->updateMdp = $this->db->prepare("UPDATE utilisateur SET mdp=:mdp where id=:id");
        $this->updateMail = $this->db->prepare("UPDATE utilisateur SET email=:email WHERE id=:id");
        $this->delete = $this->db->prepare("DELETE from utilisateur where id=:id");
    }
    /*** @category fonction/procedure de la classe User() ***/
    public function insert($email, $mdp, $role, $nom, $prenom){
        $r = true;
        $this->insert->execute(array(':email'=>$email, ':mdp'=>$mdp, ':role'=>$role, ':nom'=>$nom, ':prenom'=>$prenom));
        if ($this->insert->errorCode()!=0){
            print_r($this->insert->errorInfo());
            $r=false;
        }
        return $r;
    }
    
    public function connect($email){
        $this->connect->execute(array(':email'=>$email));
        if ($this->connect->errorCode()!=0){
            print_r($this->connect->errorInfo());
        }
        return $this->connect->fetch();
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
    public function selectByEmail($email){
        $this->selectByEmail->execute(array(':email'=>$email)); 
        if ($this->selectByEmail->errorCode()!=0){
            print_r($this->selectByEmail->errorInfo());
        }
        return $this->selectByEmail->fetch();
    }
    public function update($id, $role, $nom, $prenom){
        $r = true;
        $this->update->execute(array(':id'=>$id, ':role'=>$role,':nom'=>$nom,':prenom'=>$prenom));
        if ($this->update->errorCode() != 0){
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }
    public function updateMdp($id, $mdp){
        $r = true;
        $this->updateMdp->execute(array(':id'=>$id, ':mdp'=>$mdp));
        if ($this->updateMdp->errorCode() != 0){
            print_r($this->updateMdp->errorInfo());
            $r = false;
        }
        return $r;
    }


    public function updateMail($id, $email){
        $r = true;
        $this->updateMail->execute(array(':id'=>$id,':email'=>$email));
        if ($this->updateMail->errorCode()!=0){ 
            print_r($this->updateMail->errorInfo());
            $r=false;
            }
        return $r;
    }
    public function delete($id){
        $r = true;
        $this->delete->execute(array(':id'=>$id));
        if ($this->delete->errorCode()!= 0){
            print_r($this->delete->errorInfo());
            $r=false;
        }
        return $r;
    }
}
?>
<?php
class UserManager
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    
    // Autres méthodes de gestion des utilisateurs...




    public function getUserById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUser($id, $email, $nom,$password, $role)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET email = :email, nom = :nom, password= :password ,role = :role WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'email' => $email,
            'nom' => $nom,
            'password'=>$password,
            'role' => $role
        ]);
    }

    public function readAll() {
        $sql = "SELECT * FROM users where role='vendor'";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function deleteUser($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function emailExists($email)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetchColumn() > 0;
    }
}

//class generale de crud

abstract class abstractCrud
{
    protected $pdo;
    protected $table;

    function __construct($pdo,$table)
    {
       $this->pdo=$pdo;
       $this->table=$table; 
    }

    // create
    public function create($data) {
        $columns = implode(", ", array_keys($data));
         $placeholders = ":" . implode(", :", array_keys($data));
    
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
    
        $stmt = $this->pdo->prepare($sql);
    
        if ($stmt->execute($data)) {
            return true;  
        } else {
            $errorInfo = $stmt->errorInfo();
            return false; 
        }
    }

    // read only one
    public function read($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
    
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->execute(['id' => $id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //read All
    public function readAll() {
        $sql = "SELECT * FROM {$this->table}";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //update

    public function update($id, $data) {

        $setClause = "";

        foreach ($data as $column => $value) {
            $setClause .= "{$column} = :{$column}, ";
        }

        $setClause = rtrim($setClause, ", ");

        $sql = "UPDATE {$this->table} SET {$setClause} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);

        $data['id'] = $id;
        return $stmt->execute($data);
    }

    //delete

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute(['id' => $id]);
    }
}

// class voiture 

class VoitureCRUD extends AbstractCRUD {
    public function __construct($pdo) {
        parent::__construct($pdo, "voitures"); 

    }

    public function delete($NumImmatriculation) {

        // Assurez-vous que NumImmatriculation est bien défini et valide
        if (empty($NumImmatriculation)) {
            echo "Numéro d'immatriculation manquant.";
            return false;
        }
    
        // Préparer la requête DELETE
        $sql = "DELETE FROM {$this->table} WHERE NumImmatriculation = :NumImmatriculation";
     
        try {
            $stmt = $this->pdo->prepare($sql);
            
            // Exécuter la requête et vérifier si la suppression a réussi
            $result = $stmt->execute(['NumImmatriculation' => $NumImmatriculation]);
    
            if ($result) {
                return true;  // Suppression réussie
            } else {
                echo "Erreur : La suppression a échoué.";
                return false;
            }
        } catch (PDOException $e) {
            // Afficher l'erreur si la préparation ou l'exécution échoue
            echo "Erreur SQL : " . $e->getMessage();
            return false;
        }
    }
    public function getByNumImmatriculation($NumImmatriculation) {
        $sql = "SELECT * FROM {$this->table} WHERE NumImmatriculation = :NumImmatriculation";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['NumImmatriculation' => $NumImmatriculation]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

//contrat

class ContratCRUD extends AbstractCRUD {
    public function __construct($pdo) {
        parent::__construct($pdo, "contrats");
    }

}

//clients


class ClientCRUD extends AbstractCRUD {
    public function __construct($pdo) {
        parent::__construct($pdo, "clients"); 
    }

}

class vendorCRUD extends AbstractCRUD {
    public function __construct($pdo) {
        parent::__construct($pdo, "vendor"); 
    }

}
?>



    
    



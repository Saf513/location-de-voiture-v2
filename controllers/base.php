<?php
class UserManager
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function createUser($email, $password, $nom, $role = 'vendor') // 'vendor' par défaut
    {
        // Vérifiez ici que le rôle est bien dans les valeurs autorisées
        if (!in_array($role, ['admin', 'vendor'])) {
            throw new Exception("Role invalide");
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare("INSERT INTO users (email, password, nom, role) VALUES (:email, :password, :nom, :role)");

        if ($stmt->execute([
            'email' => $email,
            'password' => $hashedPassword,
            'nom' => $nom,
            'role' => $role // Assurez-vous que ce rôle soit 'admin' ou 'vendor'
        ])) {
            // Si l'insertion est réussie, on récupère l'ID du nouvel utilisateur
            $userId = $this->pdo->lastInsertId();

            // Maintenant, on insère l'utilisateur dans la table vendors
            $vendorStmt = $this->pdo->prepare("INSERT INTO vendors (user_id, nom) VALUES (:user_id, :nom)");
            return $vendorStmt->execute([
                'user_id' => $userId,
                'nom' => $nom
            ]);
        } else {
            return false;
        }
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

    public function updateUser($id, $email, $nom, $role)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET email = :email, nom = :nom, role = :role WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'email' => $email,
            'nom' => $nom,
            'role' => $role
        ]);
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



    
    



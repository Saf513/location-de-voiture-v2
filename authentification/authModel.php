<?php
 class Auth
{
    protected $pdo;
    protected $sessionName = 'user_session';

    public function __construct($pdo)
    {
       
        $this->pdo = $pdo;
    }

    public function login($email, $password)
    {
        
        if (empty($email) || empty($password)) {
            return "L'email ou le mot de passe ne peut pas être vide.";
        }

        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            
            $_SESSION[$this->sessionName] = $user['id'];

            if (isset($_POST['remember'])) {
                setcookie('user_id', $user['id'], time() + (86400 * 30), '/', '', false, true);
            }

            return $user;
        } else {
            return "Email ou mot de passe incorrect";
        }
    }

    public function logout()
    {
        unset($_SESSION[$this->sessionName]);

        if (isset($_COOKIE['user_id'])) {
            setcookie('user_id', '', time() - 3600, '/', '', false, true);
        }

        session_destroy();
    }

    public function isLoggedIn()
    {
        if (isset($_SESSION[$this->sessionName])) {
            return true;
        }

        if (isset($_COOKIE['user_id'])) {
          
            $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');
            $stmt->execute(['id' => $_COOKIE['user_id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                $_SESSION[$this->sessionName] = $user['id'];
                return true;
            }
        }

        return false;
    }

    public function getUser()
    {
        if ($this->isLoggedIn()) {
            $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');
            $stmt->execute(['id' => $_SESSION[$this->sessionName]]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return null;
    }

    public function emailExists($email)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }
}

class Admin extends Auth
{
    public function approveVendor($vendorId)
    {
        $stmt = $this->pdo->prepare("UPDATE vendors SET approved = 1 WHERE id = :vendorId");
        $stmt->execute(['vendorId' => $vendorId]);
    }

    public function createUser($email, $password, $nom, $role = 'vendor') 
    {
       
        if (!in_array($role, ['admin', 'vendor'])) {
            throw new Exception("Role invalide");
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare("INSERT INTO users (email, password, nom, role) VALUES (:email, :password, :nom, :role)");

        if ($stmt->execute([
            'email' => $email,
            'password' => $hashedPassword,
            'nom' => $nom,
            'role' => $role 
        ])) {
            
            $userId = $this->pdo->lastInsertId();

            // // Maintenant, on insère l'utilisateur dans la table vendors
            // $vendorStmt = $this->pdo->prepare("INSERT INTO vendors (user_id, nom) VALUES (:user_id, :nom)");
            // return $vendorStmt->execute([
            //     'user_id' => $userId,
            //     'nom' => $nom
            // ]);
        } else {
            return false;
        }
    }
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

class Vendor extends Auth
{
    // Méthode de registre spécifique au vendeur
    public function register($name, $email, $password,$role)
    {
        if ($this->emailExists($email)) {
            return "Email déjà utilisé";
        }

        // Hasher le mot de passe avant de l'enregistrer
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Enregistrement dans la table `users`
        $stmt = $this->pdo->prepare("INSERT INTO users (nom, email, password,role) VALUES (?, ?, ?,?)");
        $stmt->execute([$name, $email, $hashedPassword,$role]);

        return true;
    }
}

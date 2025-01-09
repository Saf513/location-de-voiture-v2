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
        // Détruire la session et supprimer le cookie
        unset($_SESSION[$this->sessionName]);

        if (isset($_COOKIE['user_id'])) {
            setcookie('user_id', '', time() - 3600, '/', '', false, true);
        }

        session_destroy();
    }

    public function isLoggedIn()
    {
        // Vérification de la session
        if (isset($_SESSION[$this->sessionName])) {
           
            return true;
        }

        // Vérification du cookie "remember me"
        if (isset($_COOKIE['user_id'])) {
          
            // Vérifier l'existence de l'utilisateur avec l'ID du cookie
            $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');
            $stmt->execute(['id' => $_COOKIE['user_id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            // Si l'utilisateur existe, on recrée la session
            if ($user) {
                $_SESSION[$this->sessionName] = $user['id'];
                return true;
            }
        }

        return false;
    }

    public function getUser()
    {
        // Retourner les informations de l'utilisateur si connecté
        if ($this->isLoggedIn()) {
            $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');
            $stmt->execute(['id' => $_SESSION[$this->sessionName]]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return null;
    }

    public function emailExists($email)
    {
        // Vérifier si l'email existe déjà dans la base de données
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

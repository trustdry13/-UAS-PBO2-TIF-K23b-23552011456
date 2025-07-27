<?php
class User {
    private $id;
    private $username;
    private $password;
    private $namaLengkap;
    private $email;
    private $role;

    public function __construct($username, $password, $namaLengkap, $email, $role = 'customer') {
        $this->username = $username;
        $this->password = $password;
        $this->namaLengkap = $namaLengkap;
        $this->email = $email;
        $this->role = $role;
    }

    // Getter methods
    public function getId() { return $this->id; }
    public function getUsername() { return $this->username; }
    public function getNamaLengkap() { return $this->namaLengkap; }
    public function getEmail() { return $this->email; }
    public function getRole() { return $this->role; }

    // Method untuk registrasi user
    public function register(Database $db) {
        // Cek apakah username atau email sudah ada
        $stmt = $db->prepare("SELECT id FROM user WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $this->username, $this->email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return false; // Username atau email sudah terdaftar
        }

        // Hash password
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

        // Simpan user baru
        $stmt = $db->prepare("INSERT INTO user (username, password, nama_lengkap, email, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $this->username, $hashedPassword, $this->namaLengkap, $this->email, $this->role);
        return $stmt->execute();
    }

    // Method static untuk login
    public static function login(Database $db, $username, $password) {
        $stmt = $db->prepare("SELECT * FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                // Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                $_SESSION['role'] = $user['role'];
                return true;
            }
        }

        return false;
    }

    // Method static untuk logout
    public static function logout() {
        session_unset();
        session_destroy();
    }

    // Method static untuk mendapatkan user by id
    public static function getById(Database $db, $id) {
        $stmt = $db->prepare("SELECT * FROM user WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            $userObj = new User($user['username'], '', $user['nama_lengkap'], $user['email'], $user['role']);
            $userObj->id = $user['id'];
            return $userObj;
        }

        return null;
    }
}
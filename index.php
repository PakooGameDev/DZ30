<?php
class Database {
    private $host = 'localhost';
    private $name = 'tms';
    private $user = 'root';
    private $password = '';
    private $conn;

    public function getConnection()
    {
        try {
            $this->conn = new PDO(
                "mysql:host=$this->host; dbname=$this->name", 
                 $this->user,
                 $this->password
                );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }   catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
        return $this->conn;
    }
}

class Users {
    private $dataTable = 'users';
    private $conn;
    private string $name;
    private int $age;
    private string $email;

    public function __construct($name, $age, $email, $conn)
    {
        $this->name = $name;
        $this->age = $age;
        $this->email = $email;
        $this->conn = $conn;
    }

    public function add()
    {
        $query = "INSERT INTO $this->dataTable (name, age, email) VALUES (:name, :age, :email)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':age', $this->age);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        return $stmt;
    }

    public function view(): string
    {
        $query = "SELECT * FROM $this->dataTable";
        
        $stmt = $this->conn->query($query);

        $row = $stmt->fetch();
        return "ID: ". $row['id'] ."
                Name: ". $row['name'] ."
                Age:  ". $row['age'] ."
                Email:  ". $row['age'] ."";
    }

    public function edit(int $id, string $value): void 
    {
        $query = "UPDATE $this->dataTable SET name = :name WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $value);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function delete(int $id): void
    {
        $query = "DELETE FROM $this->dataTable WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}

$db = new Database();
$db = $db->getConnection();

$user = new Users('Миша', 18, 'ff23gd@mail.ru', $db);
$user->add();
echo $user->view();
$user->edit(3,'Grzegorz');
$user->delete(20);

?>

<?php
require_once "Database.php";

class User extends Database {

    public function createUser($first_name, $last_name, $username, $password){
        $password = password_hash($password, PASSWORD_DEFAULT);

        // SQL
        $sql = "INSERT INTO users (first_name, last_name, username, `password`) VALUES ('$first_name', '$last_name', '$username', '$password')";

        // Execute the command
        if($this->conn->query($sql)){
            header('location: ../views');   // go to index.php
            exit;
        } else {
            die("Error creating user: " . $this->conn->error);
        }
    }

    public function login($username, $password){
        // $username = "john"
        // $password = "john12345"
        $sql = "SELECT * FROM users WHERE username = '$username'";

        if($result = $this->conn->query($sql)){
            // $result holds the record from the database
            if($result->num_rows == 1){
                // username is existing
                $user_details = $result->fetch_assoc();
                // $user_details = ["id" => 2, "first_name" => "John", "last_name" => "Doe", ...];

                if(password_verify($password, $user_details['password'])){
                    # 1 Start the session
                    session_start();

                    # 2 Create session variables
                    $_SESSION['id'] = $user_details['id'];
                    // $_SESSION['id'] = 2;
                    $_SESSION['username'] = $user_details['username'];
                    // $_SESSION['username'] = 'john';

                    # 3 Open the dashboard
                    header("location: ../views/dashboard.php");
                    exit;
                } else {
                    die("Password is incorrect.");
                }
            } else {
                // username not found
                die("Username not found.");
            }
        } else {
            die("Error with SQL: " . $this->conn->error);
        }
    }

    public function getAllUsers(){
        $user_id = $_SESSION['id'];
        $sql = "SELECT id, first_name, last_name, username FROM users WHERE id != $user_id";

        if($result = $this->conn->query($sql)){
            return $result;
            // $result contains many rows
        } else {
            die("Error: " . $this->conn->error);
        }
    }

    public function getUser($user_id){
        $sql = "SELECT first_name, last_name, username FROM users WHERE id = $user_id";

        if($result = $this->conn->query($sql)){
            return $result->fetch_assoc();
        } else {
            die("Error: " . $this->conn->error);
        }
    }

    public function updateUser($id, $first_name, $last_name, $username){
        $sql = "UPDATE users SET first_name = '$first_name', last_name = '$last_name', username = '$username' WHERE id = $id";

        if($this->conn->query($sql)){
            header('location: ../views/dashboard.php');
            exit;
        } else {
            die("Error: " . $this->conn->error);
        }
    }

    public function deleteUser($id){
        $sql = "DELETE FROM users WHERE id = $id";

        if($this->conn->query($sql)){
            header('location: ../views/dashboard.php');
            exit;
        } else {
            die("Error: " . $this->conn->error);
        }
    }
}
<?php
// Connect to database
$conn = new mysqli('127.0.0.1', 'laravel', 'secret', 'laravel');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if users table exists
$result = $conn->query("SHOW TABLES LIKE 'users'");
if ($result->num_rows > 0) {
    echo "Users table found!\n\n";
    
    // List all tables
    echo "All tables in database:\n";
    $tables = $conn->query("SHOW TABLES");
    while ($table = $tables->fetch_array()) {
        echo "- " . $table[0] . "\n";
    }
    
    echo "\n";
    
    // Show all users
    echo "Users in the database:\n";
    $users = $conn->query("SELECT * FROM users");
    
    if ($users->num_rows > 0) {
        while ($user = $users->fetch_assoc()) {
            echo "ID: {$user['id']}, Name: {$user['name']}, Email: {$user['email']}\n";
        }
    } else {
        echo "No users found in the database.\n";
    }
    
    // Show all roles
    echo "\nRoles in the database:\n";
    $roles = $conn->query("SHOW TABLES LIKE 'roles'");
    if ($roles->num_rows > 0) {
        $roleData = $conn->query("SELECT * FROM roles");
        if ($roleData->num_rows > 0) {
            while ($role = $roleData->fetch_assoc()) {
                echo "ID: {$role['id']}, Name: {$role['name']}, Display Name: {$role['display_name']}\n";
            }
        } else {
            echo "No roles found in the database.\n";
        }
    } else {
        echo "Roles table not found!\n";
    }
    
} else {
    echo "Users table not found! Database tables:\n";
    $tables = $conn->query("SHOW TABLES");
    while ($table = $tables->fetch_array()) {
        echo "- " . $table[0] . "\n";
    }
}

$conn->close();

echo "\n";
echo "Admin URL should be: http://localhost:8080/admin\n";
echo "Check if Voyager is properly installed in the container.\n";


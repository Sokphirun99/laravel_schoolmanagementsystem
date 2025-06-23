<?php
// Connect to database
$conn = new mysqli('127.0.0.1', 'laravel', 'secret', 'laravel');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get admin user
echo "Admin User:\n";
$adminQuery = $conn->query("SELECT * FROM users WHERE email = 'admin@school.com'");
$adminUser = $adminQuery->fetch_assoc();
echo "ID: {$adminUser['id']}, Name: {$adminUser['name']}, Email: {$adminUser['email']}, Role ID: {$adminUser['role_id']}\n\n";

// Check user_roles table
echo "User Roles (Many-to-Many):\n";
$userRolesResult = $conn->query("SHOW TABLES LIKE 'user_roles'");
if ($userRolesResult->num_rows > 0) {
    $userRolesQuery = $conn->query("SELECT * FROM user_roles WHERE user_id = {$adminUser['id']}");
    if ($userRolesQuery->num_rows > 0) {
        while ($userRole = $userRolesQuery->fetch_assoc()) {
            echo "User ID: {$userRole['user_id']}, Role ID: {$userRole['role_id']}\n";
        }
    } else {
        echo "No entries found in user_roles for admin user.\n";
    }
} else {
    echo "user_roles table not found!\n";
}

// Add admin user to user_roles if missing
if ($adminUser['role_id'] == 1) {
    echo "\nAdmin user already has role_id set to 1 (admin) in the users table.\n";
} else {
    echo "\nUpdating admin user's role_id to 1 in the users table.\n";
    $updateQuery = $conn->query("UPDATE users SET role_id = 1 WHERE id = {$adminUser['id']}");
    if ($updateQuery) {
        echo "Successfully updated admin user's role_id to 1.\n";
    } else {
        echo "Failed to update admin user's role_id: " . $conn->error . "\n";
    }
}

// Check user_roles table
$userRolesQuery = $conn->query("SELECT * FROM user_roles WHERE user_id = {$adminUser['id']} AND role_id = 1");
if ($userRolesQuery->num_rows == 0) {
    echo "\nInserting admin user into user_roles table with role_id = 1.\n";
    $insertQuery = $conn->query("INSERT INTO user_roles (user_id, role_id) VALUES ({$adminUser['id']}, 1)");
    if ($insertQuery) {
        echo "Successfully added admin user to user_roles table with role_id = 1.\n";
    } else {
        echo "Failed to insert into user_roles: " . $conn->error . "\n";
    }
} else {
    echo "\nAdmin user already exists in user_roles table with role_id = 1.\n";
}

$conn->close();

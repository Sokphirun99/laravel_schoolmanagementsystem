<?php
// Use the Laravel database configuration from your .env file
try {
    $host = '127.0.0.1';
    $dbname = 'laravel';
    $username = 'laravel';
    $password = 'secret';
    $port = 3306;
    
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database successfully!\n";
    
    // Check if admin role exists
    $roleStmt = $conn->query("SELECT * FROM roles WHERE name = 'admin'");
    $roleExists = $roleStmt->rowCount() > 0;
    
    if ($roleExists) {
        $adminRole = $roleStmt->fetch(PDO::FETCH_ASSOC);
        $adminRoleId = $adminRole['id'];
        echo "Found existing admin role with ID: $adminRoleId\n";
    } else {
        echo "Creating admin role...\n";
        $conn->query("INSERT INTO roles (name, display_name, created_at, updated_at) VALUES ('admin', 'Administrator', NOW(), NOW())");
        $adminRoleId = $conn->lastInsertId();
        echo "Admin role created with ID: $adminRoleId\n";
    }
    
    // Check if admin user exists
    $userStmt = $conn->query("SELECT * FROM users WHERE email = 'admin@school.com'");
    $userExists = $userStmt->rowCount() > 0;
    
    if ($userExists) {
        $adminUser = $userStmt->fetch(PDO::FETCH_ASSOC);
        $adminUserId = $adminUser['id'];
        
        echo "Updating existing admin user with ID: $adminUserId\n";
        // Update password
        $password = password_hash('password', PASSWORD_BCRYPT);
        $updateStmt = $conn->prepare("UPDATE users SET password = ?, role_id = ? WHERE id = ?");
        $updateStmt->execute([$password, $adminRoleId, $adminUserId]);
        echo "Updated admin user password\n";
    } else {
        echo "Creating admin user...\n";
        // Hash password
        $password = password_hash('password', PASSWORD_BCRYPT);
        
        // Insert the admin user
        $insertStmt = $conn->prepare("INSERT INTO users (role_id, name, email, avatar, password, created_at, updated_at) VALUES (?, 'Admin User', 'admin@school.com', 'users/default.png', ?, NOW(), NOW())");
        $insertStmt->execute([$adminRoleId, $password]);
        
        $adminUserId = $conn->lastInsertId();
        echo "Admin user created with ID: $adminUserId\n";
    }
    
    // Check if entry exists in user_roles
    $userRolesStmt = $conn->query("SELECT * FROM user_roles WHERE user_id = $adminUserId AND role_id = $adminRoleId");
    $userRoleExists = $userRolesStmt->rowCount() > 0;
    
    if (!$userRoleExists) {
        $conn->query("INSERT INTO user_roles (user_id, role_id) VALUES ($adminUserId, $adminRoleId)");
        echo "Added admin user to user_roles table\n";
    } else {
        echo "Admin user already exists in user_roles table\n";
    }
    
    echo "\nAdmin login credentials:\n";
    echo "Email: admin@school.com\n";
    echo "Password: password\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

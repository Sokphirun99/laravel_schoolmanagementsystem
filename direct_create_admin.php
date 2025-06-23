<?php
// Direct database connection to create admin user in Docker environment

// Connect to database
try {
    $conn = new mysqli('127.0.0.1', 'laravel', 'secret', 'laravel');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    echo "Connected to database successfully!\n";

    // Check if admin role exists
    $roleResult = $conn->query("SELECT * FROM roles WHERE name = 'admin'");
    if ($roleResult->num_rows == 0) {
        echo "Creating admin role...\n";
        $conn->query("INSERT INTO roles (name, display_name, created_at, updated_at) VALUES ('admin', 'Administrator', NOW(), NOW())");
        $adminRoleId = $conn->insert_id;
        echo "Admin role created with ID: $adminRoleId\n";
    } else {
        $adminRole = $roleResult->fetch_assoc();
        $adminRoleId = $adminRole['id'];
        echo "Found existing admin role with ID: $adminRoleId\n";
    }

    // Check if admin user exists
    $userResult = $conn->query("SELECT * FROM users WHERE email = 'admin@school.com'");
    
    if ($userResult->num_rows == 0) {
        echo "Creating admin user...\n";
        // Hash password - using PHP's password_hash as a fallback for Laravel's Hash facade
        $password = password_hash('password', PASSWORD_BCRYPT);
        
        // Insert the admin user
        $stmt = $conn->prepare("INSERT INTO users (role_id, name, email, avatar, password, created_at, updated_at) VALUES (?, 'Admin User', 'admin@school.com', 'users/default.png', ?, NOW(), NOW())");
        $stmt->bind_param('is', $adminRoleId, $password);
        $stmt->execute();
        
        $adminUserId = $conn->insert_id;
        echo "Admin user created with ID: $adminUserId\n";
        
        // Add to user_roles table
        $conn->query("INSERT INTO user_roles (user_id, role_id) VALUES ($adminUserId, $adminRoleId)");
        echo "Added admin user to user_roles table\n";
    } else {
        $adminUser = $userResult->fetch_assoc();
        $adminUserId = $adminUser['id'];
        
        echo "Updating existing admin user with ID: $adminUserId\n";
        // Update password
        $password = password_hash('password', PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE users SET password = ?, role_id = ? WHERE id = ?");
        $stmt->bind_param('sii', $password, $adminRoleId, $adminUserId);
        $stmt->execute();
        
        // Check if entry exists in user_roles
        $userRolesResult = $conn->query("SELECT * FROM user_roles WHERE user_id = $adminUserId AND role_id = $adminRoleId");
        if ($userRolesResult->num_rows == 0) {
            $conn->query("INSERT INTO user_roles (user_id, role_id) VALUES ($adminUserId, $adminRoleId)");
            echo "Added admin user to user_roles table\n";
        }
    }

    echo "\nAdmin login credentials:\n";
    echo "Email: admin@school.com\n";
    echo "Password: password\n";

    $conn->close();

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

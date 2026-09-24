<?php
// Check if the form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // --- Database Configuration (You MUST replace these with your actual details) ---
    // Look for these details in your InfinityFree control panel -> MySQL Databases
    $servername = "sql209.epizy.com"; 
    $username = "if0_40226469"; // e.g., if0_30226469
    $password = "Abhilash509"; // The password you set for the DB user
    $dbname = "if0_40226469_usersdata";       // e.g., if0_30226469_userdata (or similar)
    $table = "contactme";          // This matches the table name you created

    // --- Retrieve and Sanitize Form Data ---
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

    // Basic validation
    if (empty($name) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($subject) || empty($message)) {
        header("Location: index.html?status=error_validation");
        exit;
    }

    // --- 1. Database Connection ---
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        // Log the error and redirect to an error page or show a generic message
        header("Location: index.html?status=error_db_connect");
        exit;
    }

    // --- 2. SQL Insert Query ---
    // Note: The column names here must exactly match those in your 'contactme' table
    $sql = "INSERT INTO contactme (Name, Email, Subject, Message) VALUES (?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    // 's' stands for string (data type of the variables)
    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    // --- 3. Execute and Check ---
    if ($stmt->execute()) {
        // Success: Redirect back to the contact page with a success message
        header("Location: thankyou.html?status=success");
    } else {
        // Failure: Redirect back with a failure message
        header("Location: index.html?status=error_db_insert");
    }

    // --- 4. Close Connection ---
    $stmt->close();
    $conn->close();

    exit;

} else {
    // If someone tries to access the PHP file directly, redirect them to the contact form
    header("Location: index.html");
    exit;
}
?>
<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include 'db.php';
session_start(); // Start the session

// Fetch a random English text from the database
$english_text = '';
$english_data_id = 0; // Initialize the variable to store the English data ID
try {
    $stmt = $pdo->query("SELECT * FROM english_data WHERE translated = 0 ORDER BY RAND() LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $english_text = $row['text']; // Adjust this based on your column name
        $english_data_id = $row['id']; // Store the ID for later use
    } else {
        die("No untranslated English data available.");
    }
} catch (PDOException $e) {
    die("Error fetching English data: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture input from textarea
    $translatedText = $_POST['monglish_input'];

    // Set user ID (0 if not logged in)
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

    // Prepare SQL query to insert into translations table
    try {
        $stmt = $pdo->prepare("INSERT INTO translations (english_data_id, malayalam_translation, user_id, upvotes, downvotes, average_stars) VALUES (?, ?, ?, 0, 0, 0)");

        // Execute the query
        $stmt->execute([$english_data_id, $translatedText, $userId]);

        // If the user is logged in, update user statistics
        if ($userId > 0) {
            // Increment translations made
            $updateUserStmt = $pdo->prepare("UPDATE users SET translations_made = translations_made + 1 WHERE id = ?");
            $updateUserStmt->execute([$userId]);

            // Insert into user_writings table
            $lastInsertId = $pdo->lastInsertId(); // Get the last inserted translation ID
            $insertWritingStmt = $pdo->prepare("INSERT INTO user_writings (user_id, translated_data_id) VALUES (?, ?)");
            $insertWritingStmt->execute([$userId, $lastInsertId]);
        }

        // Redirect or show success message
        echo "<p>Translation submitted successfully.</p>";
    } catch (PDOException $e) {
        // Handle errors
        die("Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Translation Portal</title>
</head>
<body>
    <header>
        <h1>Translation Portal</h1>
        <nav>
            <ul>
                <li><button onclick="navigateTo('write')">Write</button></li>
                <li><button onclick="navigateTo('review')">Review</button></li>
                <li><button onclick="navigateTo('about')">About</button></li>
                <li><button onclick="navigateTo('dataset')">Dataset</button></li>
                
                <?php if (isset($_SESSION['username'])): ?>
                    <li class="dropdown">
                        <button id="profile-button">Profile</button>
                        <div class="dropdown-content" id="dropdown-menu">
                            <button onclick="logout()">Logout</button>
                        </div>
                    </li>
                <?php else: ?>
                    <li><button id="auth-button" onclick="handleAuth()">Login/Signup</button></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <div class="text-container">
            <div class="text-area">
                <h2>English</h2>
                <p id="english-text"><?php echo htmlspecialchars($english_text); ?></p>
            </div>
            <div class="text-area">
                <h2>Monglish</h2>
                <form method="POST">
                    <textarea id="monglish-input" name="monglish_input" placeholder="Write the Malayalam text here..." required></textarea>
                    <div class="actions">
                        <button type="submit">Submit</button>
                        <button type="button" onclick="skipTranslation()">Skip</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        function handleAuth() {
            window.location.href = "login.php"; // Redirect to login page
        }

        function logout() {
            window.location.href = "logout.php"; // Redirect to logout page
        }

        function navigateTo(page) {
            window.location.href = page + '.php'; // Redirect to the specified page
        }

        function skipTranslation() {
            alert("Skipped the translation.");
            // Add any additional logic needed for skipping a translation
            location.reload(); // Reload the page for a new translation
        }
    </script>
</body>
</html>


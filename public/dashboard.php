<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/util.php';

if(!isLogged()) {
    header("Location: login.php");
    exit();
}

$searchKeyword = "";
if(isset($_GET['search']))
    $searchKeyword = $_GET['search'];
$vacancies = json_decode(fetchVacancy($searchKeyword), true)

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/mainstyles.css">
</head>
<body>
    <div class="container">
        <h2>Welcome to Your Dashboard</h2>
        <p>Hello, <?php echo getSessionName(); ?>!</p>
        <div class="tabs">
            <a href="dashboard.html" class="tablink">Home</a>
            <a href="vacancy.html" class="tablink">Vacancy</a>
            <a href="add_vacancy.html" class="tablink">Add Vacancy</a>
            <a href="profile.html" class="tablink">Profile</a>
            <a href="/api/logout" class="tablink">Logout</a> <!-- Adjust the URL according to your logout functionality -->
        </div>
        <div class="content">
            <!-- Content for Home tab goes here -->
            <p>This is the Home tab content.</p>
        </div>
    </div>
</body>
</html>



<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/util.php';

if(!isLogged())
    header("Location: login.php");

$searchKeyword = "";
if(isset($_GET['search']))
    $searchKeyword = $_GET['search'];
$vacancies = json_decode(fetchVacancy($searchKeyword), true)

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
</head>
<body>

<h1>Hi, <?php echo getSessionName(); ?></h1>

<a href="/api/logout"><button>LOGOUT</button></a>
<a href="create_vacancy.php"><button>Create Vacancy</button></a>
<a href="profile.php"><button>Profile</button></a>

<br><br>

<?php
    foreach ($vacancies as $vacancy) {
        $id = $vacancy['id'];
        $title = $vacancy['title'];
        $description = $vacancy['description'];
        $status = $vacancy['status'];
        $account = $vacancy['account'];

        echo "======================= <br>";
        echo "ID : $id <br>";
        echo "Title : $title <br>";
        echo "Description : $description <br>";
        echo "Status : $status <br>";
        echo "Account : $account <br>";
        echo "======================= <br>";
    }

?>


</body>
</html>

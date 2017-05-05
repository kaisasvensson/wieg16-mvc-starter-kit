<?php

use App\Database;
use App\Models\MovieModel;


//roten i projektet

// Sökväg till grundmappen i projektet konstant som pekar till mappen där filen ligger i
$baseDir = __DIR__ . '/..';

// Ladda in Composers autoload-fil -alla klasser laddas in automatiskt
require $baseDir . '/vendor/autoload.php';

// Ladda konfigurationsdata
$config = require $baseDir . '/config/config.php';

// Normalisera url-sökvägar
$path = function ($uri) {
    return ($uri == "/") ? $uri : rtrim($uri, '/');
};

$dsn = "mysql:host=" . $config['db_host'] . ";dbname=" . $config['db_name'] . ";charset=" . $config['charset'];
$pdo = new PDO($dsn, $config['db_username'], $config['db_password'], $config['options']);

$db = new Database($pdo);


// markus exempel

/*$db->create( 'movies', [
    'title' => 'The Shawshank Redemption',
    'year' => 2001,
    'director' => 'Frank Darabont'
]);*/

/*
$movie = $db->getById('movies', 1);
$movies = $db->getAll('movies');

$movie = $movie->getById(1);
$movies = $movie->getAll();
$movieModel->create([
    'title' => 'The Shawshank Redemption',   //exempel på hur det kan se ut
    'year' => 2001,
    'director' => 'Frank Darabont'
]);*/

// Routing

//$controller = new Controller($baseDir);
$url = $path($_SERVER['REQUEST_URI']);
switch ($url) {
    case '/':
        //$controller->index();
        $movieModel = new MovieModel($db);
        $allMovies = $movieModel->getAll();
        require $baseDir . '/views/index.php';
        break;

    case '/create-movie':

        // $controller->createRecipe($recipeModel, $_POST);
        $MovieModel = new MovieModel($db);
        $MovieId = $MovieModel->create($_POST);
        // Dirigera tillbaka till förstasidan efter att vi har sparat.
        // Vi skickar med id:t på receptet som sparades för att kunna använda oss av det i vår vy.
        header('Location: /?id=' . $MovieId);
        break;
    default:
        header('HTTP/1.0 404 Not Found');
        echo 'Page not found';
        break;
}
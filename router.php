<?php
$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

$routes = [
  "/" => "controllers/index.php",
  "/login.php" => "login.php",
  "/student/login.php" => "student/login.php",
  "/student/authenticate.php" => "student/authenticate.php",
  "/student/dashboard.php" => "student/dashboard.php",
  "/student/logout.php" => "student/logout.php",
  "/admin/login.php" => "admin/login.php",
  "/admin/authenticate.php" => "admin/authenticate.php",
  "/admin/dashboard.php" => "admin/dashboard.php",
  "/admin/logout.php" => "admin/logout.php",
];

function routesToController(string $uri, array $routes): void
{
  if (array_key_exists($uri, $routes)) {
    require $routes[$uri];
  } else {
    // Check if this is an admin or student route without direct mapping
    if (strpos($uri, '/admin/') === 0) {
      // Admin route, check if file exists
      $adminFile = substr($uri, 1); // Remove leading slash
      if (file_exists($adminFile)) {
        require $adminFile;
        return;
      }
    } elseif (strpos($uri, '/student/') === 0) {
      // Student route, check if file exists
      $studentFile = substr($uri, 1); // Remove leading slash
      if (file_exists($studentFile)) {
        require $studentFile;
        return;
      }
    }
    
    // If we get here, the route was not found
    http_response_code(404);
    echo "404 Not Found";
  }
}

routesToController($uri, $routes);
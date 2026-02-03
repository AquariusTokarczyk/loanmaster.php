<?php

// Visar fel i utveckling (skola). / Pokazuje błędy w dev (szkoła).
ini_set('display_errors', '1');
error_reporting(E_ALL);

// Laddar konfiguration. / Ładuje konfigurację.
$konfig = require __DIR__ . '/../config/config.php';

// Enkel autoloader för App\ utan Composer. / Prosty autoloader dla App\ bez Composera.
spl_autoload_register(function (string $klass): void {
  $prefix = 'App\\';

  if (strncmp($klass, $prefix, strlen($prefix)) !== 0) {
    return;
  }

  $relativ = substr($klass, strlen($prefix));
  $relativ = str_replace('\\', '/', $relativ);

  $fil = __DIR__ . '/../app/' . $relativ . '.php';

  if (is_file($fil)) {
    require $fil;
  }
});

use App\Core\Auth;
use App\Core\Router;
use App\Models\BaseModel;
use App\Controllers\AuthController;
use App\Controllers\ItemController;
use App\Controllers\LoanController;
use App\Controllers\AdminController;

// Startar session. / Uruchamia sesję.
Auth::startaSession();

// Ger modellerna DB-konfig. / Daje modelom konfig DB.
BaseModel::sattKonfig($konfig);

// Skapar router och controllers. / Tworzy router i kontrolery.
$router = new Router();

$auth = new AuthController();
$item = new ItemController();
$lan = new LoanController();
$admin = new AdminController();

// Startsida. / Strona główna.
$router->get('/', function () {
  $view = new \App\Core\View();
  $view->render('home', []);
});

// Auth routes. / Trasy auth.
$router->get('/login', [$auth, 'visaLogin']);
$router->post('/login', [$auth, 'loggaIn']);
$router->get('/register', [$auth, 'visaRegister']);
$router->post('/register', [$auth, 'registrera']);
$router->get('/logout', [$auth, 'loggaUt']);

// Items routes. / Trasy items.
$router->get('/items', [$item, 'index']);
$router->get('/items/show', [$item, 'show']);

// Loans routes. / Trasy wypożyczeń.
$router->get('/lan', [$lan, 'index']);
$router->post('/lan/skapa', [$lan, 'skapa']);
$router->post('/lan/aterlamna', [$lan, 'aterlamna']);

// Admin routes. / Trasy admin.
$router->get('/admin/items', [$admin, 'items']);
$router->post('/admin/items/skapa', [$admin, 'skapaItem']);
$router->post('/admin/items/uppdatera', [$admin, 'uppdateraItem']);
$router->post('/admin/items/tabort', [$admin, 'taBortItem']);

// Dispatch. / Dispatch.
$router->dispatch();

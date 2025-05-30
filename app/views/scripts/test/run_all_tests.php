<?php
// filepath: app/views/scripts/test/run_all_tests.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ✅ Setup dei path (basato sulla struttura esistente)
define('ROOT_PATH', realpath(__DIR__ . '/../../../../'));
define('WEB_ROOT', '');

echo "<h1>🧪 Test Suite - TODO List App</h1>";
echo "<pre>";

// ✅ Autoloader semplificato per i test
function testAutoloader($className) {
    $paths = [
        ROOT_PATH . '/app/models/' . $className . '.php',
        ROOT_PATH . '/lib/' . $className . '.php',
        ROOT_PATH . '/app/controllers/' . $className . '.php'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
}
spl_autoload_register('testAutoloader');

echo "Loading test environment...\n\n";

// ✅ Include e esegui tutti i test
$testFiles = [
    'test_lib/JsonCRUDTest.php',
    'test_user/UserModelTest.php', 
    'test_task/TaskModelTest.php',
    'test_tasklist/TasklistTest.php'
];

foreach ($testFiles as $testFile) {
    $fullPath = __DIR__ . '/' . $testFile;
    if (file_exists($fullPath)) {
        echo "🔧 Running: $testFile\n";
        require_once $fullPath;
        echo "\n";
    } else {
        echo "  File not found: $testFile\n";
    }
}

echo " Test suite completed!\n";
echo "</pre>";
?>
<?php
/**
 * Script para limpar cache do Laravel no servidor
 * Acesse: https://mozcommodities.com/clear-cache.php
 * IMPORTANTE: Delete este arquivo após usar!
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Definir o caminho base
$basePath = __DIR__;

echo "<h1>Diagnóstico e Limpeza - MozCommodities</h1>";

// Verificar se os arquivos essenciais existem
echo "<h2>1. Verificação de Arquivos Essenciais</h2>";
echo "<pre>";

$essentialFiles = [
    '/vendor/autoload.php',
    '/bootstrap/app.php',
    '/.env',
    '/public/build/manifest.json',
    '/public/build/assets/app-DlT2O1zp.css',
    '/public/build/assets/app-ByW0VTRm.js',
    '/config/livewire.php',
];

$allOk = true;
foreach ($essentialFiles as $file) {
    $fullPath = $basePath . $file;
    if (file_exists($fullPath)) {
        $size = filesize($fullPath);
        echo "[OK] $file (". number_format($size) ." bytes)\n";
    } else {
        echo "[ERRO] $file - NÃO EXISTE!\n";
        $allOk = false;
    }
}
echo "</pre>";

// Função para limpar diretórios de cache
function clearDirectory($dir) {
    if (!is_dir($dir)) {
        return "Diretório não existe: $dir";
    }

    $files = glob($dir . '/*');
    $count = 0;

    foreach ($files as $file) {
        if (is_file($file) && basename($file) !== '.gitignore') {
            unlink($file);
            $count++;
        }
    }

    return "Limpos $count arquivos em $dir";
}

echo "<h2>2. Limpeza de Cache</h2>";
echo "<pre>";

// Limpar cache de views
echo "Views Cache: ";
echo clearDirectory($basePath . '/storage/framework/views') . "\n";

// Limpar cache de dados
echo "Data Cache: ";
echo clearDirectory($basePath . '/storage/framework/cache/data') . "\n";

// Limpar bootstrap cache
echo "Bootstrap Cache:\n";
$bootstrapCache = [
    $basePath . '/bootstrap/cache/config.php',
    $basePath . '/bootstrap/cache/routes-v7.php',
    $basePath . '/bootstrap/cache/services.php',
    $basePath . '/bootstrap/cache/packages.php',
];

foreach ($bootstrapCache as $file) {
    if (file_exists($file)) {
        unlink($file);
        echo "  Deletado: " . basename($file) . "\n";
    }
}

echo "</pre>";

// Verificar permissões
echo "<h2>3. Verificação de Permissões</h2>";
echo "<pre>";
$folders = [
    '/storage',
    '/storage/logs',
    '/storage/framework',
    '/storage/framework/cache',
    '/storage/framework/sessions',
    '/storage/framework/views',
    '/bootstrap/cache',
];

foreach ($folders as $folder) {
    $path = $basePath . $folder;
    if (is_dir($path)) {
        $perms = substr(sprintf('%o', fileperms($path)), -4);
        $writable = is_writable($path) ? 'OK' : 'SEM PERMISSÃO!';
        echo "$folder: $perms ($writable)\n";
    } else {
        echo "$folder: NÃO EXISTE!\n";
    }
}

echo "</pre>";

// Verificar .env
echo "<h2>4. Verificação do .env</h2>";
echo "<pre>";
if (file_exists($basePath . '/.env')) {
    echo ".env existe: SIM\n";

    $env = file_get_contents($basePath . '/.env');

    // APP_KEY
    if (strpos($env, 'APP_KEY=base64:') !== false) {
        echo "APP_KEY: Configurada\n";
    } else {
        echo "APP_KEY: NÃO CONFIGURADA - ERRO!\n";
    }

    // APP_ENV
    if (preg_match('/APP_ENV=(.+)/', $env, $matches)) {
        echo "APP_ENV: " . trim($matches[1]) . "\n";
    }

    // APP_DEBUG
    if (preg_match('/APP_DEBUG=(.+)/', $env, $matches)) {
        echo "APP_DEBUG: " . trim($matches[1]) . "\n";
    }

    // APP_URL
    if (preg_match('/APP_URL=(.+)/', $env, $matches)) {
        echo "APP_URL: " . trim($matches[1]) . "\n";
    }

    // DB
    if (preg_match('/DB_DATABASE=(.+)/', $env, $matches)) {
        echo "DB_DATABASE: " . trim($matches[1]) . "\n";
    }

    // SESSION
    if (preg_match('/SESSION_DRIVER=(.+)/', $env, $matches)) {
        echo "SESSION_DRIVER: " . trim($matches[1]) . "\n";
    }

} else {
    echo ".env existe: NÃO - CRIAR ARQUIVO!\n";
    echo "\nCopie o conteúdo de .env.production para .env e configure:\n";
    echo "- DB_DATABASE\n";
    echo "- DB_USERNAME\n";
    echo "- DB_PASSWORD\n";
}
echo "</pre>";

// Tentar carregar Laravel
echo "<h2>5. Limpeza via Laravel Artisan</h2>";
echo "<pre>";

try {
    require $basePath . '/vendor/autoload.php';
    $app = require_once $basePath . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

    // Limpar caches
    $kernel->call('config:clear');
    echo "config:clear - OK\n";

    $kernel->call('route:clear');
    echo "route:clear - OK\n";

    $kernel->call('view:clear');
    echo "view:clear - OK\n";

    $kernel->call('cache:clear');
    echo "cache:clear - OK\n";

    echo "\nTodas as caches foram limpas!\n";

} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
    echo "Ficheiro: " . $e->getFile() . "\n";
    echo "Linha: " . $e->getLine() . "\n";
}

echo "</pre>";

// Teste de Conexão com Banco
echo "<h2>6. Teste de Conexão com Banco de Dados</h2>";
echo "<pre>";

try {
    $pdo = $app->make('db')->connection()->getPdo();
    echo "Conexão com banco: OK\n";
    echo "Driver: " . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . "\n";

    // Testar tabela users
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Total de usuários: " . $result['total'] . "\n";

} catch (Exception $e) {
    echo "Erro de conexão: " . $e->getMessage() . "\n";
}

echo "</pre>";

// Informações do Servidor
echo "<h2>7. Informações do Servidor</h2>";
echo "<pre>";
echo "PHP Version: " . phpversion() . "\n";
echo "Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'N/A') . "\n";
echo "Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') . "\n";
echo "Script Path: " . __FILE__ . "\n";
echo "</pre>";

echo "<h2 style='color: red;'>IMPORTANTE: DELETE ESTE ARQUIVO APÓS USAR!</h2>";
?>

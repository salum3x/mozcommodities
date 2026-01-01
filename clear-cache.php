<?php
/**
 * Script para limpar cache do Laravel no servidor
 * Acesse: https://mozcommodities.com/clear-cache.php
 * IMPORTANTE: Delete este arquivo após usar!
 */

// Definir o caminho base
$basePath = __DIR__;

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

echo "<h1>Limpeza de Cache - MozCommodities</h1>";
echo "<pre>";

// Limpar cache de views
echo "1. Views Cache:\n";
echo clearDirectory($basePath . '/storage/framework/views') . "\n\n";

// Limpar cache de dados
echo "2. Data Cache:\n";
echo clearDirectory($basePath . '/storage/framework/cache/data') . "\n\n";

// Limpar cache de sessões (cuidado - desconecta usuários)
// echo "3. Sessions:\n";
// echo clearDirectory($basePath . '/storage/framework/sessions') . "\n\n";

// Limpar bootstrap cache
echo "3. Bootstrap Cache:\n";
$bootstrapCache = [
    $basePath . '/bootstrap/cache/config.php',
    $basePath . '/bootstrap/cache/routes-v7.php',
    $basePath . '/bootstrap/cache/services.php',
    $basePath . '/bootstrap/cache/packages.php',
];

foreach ($bootstrapCache as $file) {
    if (file_exists($file)) {
        unlink($file);
        echo "Deletado: " . basename($file) . "\n";
    }
}

echo "\n";

// Verificar permissões de pastas importantes
echo "4. Verificando permissões:\n";
$folders = [
    '/storage/logs',
    '/storage/framework/cache',
    '/storage/framework/sessions',
    '/storage/framework/views',
    '/bootstrap/cache',
];

foreach ($folders as $folder) {
    $path = $basePath . $folder;
    if (is_dir($path)) {
        $perms = substr(sprintf('%o', fileperms($path)), -4);
        $writable = is_writable($path) ? 'OK' : 'SEM PERMISSÃO';
        echo "$folder: $perms ($writable)\n";
    }
}

echo "\n";
echo "5. Verificando .env:\n";
if (file_exists($basePath . '/.env')) {
    echo ".env existe: SIM\n";

    // Verificar APP_KEY
    $env = file_get_contents($basePath . '/.env');
    if (strpos($env, 'APP_KEY=base64:') !== false) {
        echo "APP_KEY configurada: SIM\n";
    } else {
        echo "APP_KEY configurada: NAO - ERRO!\n";
    }

    // Verificar DB
    if (preg_match('/DB_DATABASE=(.+)/', $env, $matches)) {
        echo "DB_DATABASE: " . trim($matches[1]) . "\n";
    }
} else {
    echo ".env existe: NAO - CRIAR ARQUIVO!\n";
}

echo "\n";
echo "========================================\n";
echo "Cache limpa com sucesso!\n";
echo "IMPORTANTE: Delete este arquivo agora!\n";
echo "========================================\n";
echo "</pre>";

// Tentar carregar Laravel para limpar cache via Artisan
echo "<h2>Tentando limpar via Laravel...</h2>";
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

    echo "\nTodas as caches foram limpas via Artisan!\n";

} catch (Exception $e) {
    echo "Erro ao executar Artisan: " . $e->getMessage() . "\n";
    echo "A limpeza manual foi realizada.\n";
}

echo "</pre>";
?>

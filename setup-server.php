<?php
/**
 * Script de configuração para servidor de produção
 * Cria links simbólicos necessários quando Laravel está na raiz
 * Acesse: https://mozcommodities.com/setup-server.php
 * IMPORTANTE: Delete este arquivo após usar!
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$basePath = __DIR__;

echo "<h1>Configuração do Servidor - MozCommodities</h1>";

// 1. Criar link simbólico para build/
echo "<h2>1. Configurando pasta build/</h2>";
echo "<pre>";

$buildSource = $basePath . '/public/build';
$buildTarget = $basePath . '/build';

if (is_dir($buildSource)) {
    if (!file_exists($buildTarget)) {
        // Tentar criar link simbólico
        if (@symlink($buildSource, $buildTarget)) {
            echo "[OK] Link simbólico criado: build/ -> public/build/\n";
        } else {
            // Se symlink falhar, copiar os arquivos
            echo "[INFO] Symlink falhou, copiando arquivos...\n";

            // Criar diretório
            mkdir($buildTarget, 0755, true);
            mkdir($buildTarget . '/assets', 0755, true);

            // Copiar manifest.json
            copy($buildSource . '/manifest.json', $buildTarget . '/manifest.json');
            echo "[OK] Copiado: manifest.json\n";

            // Copiar assets
            $assets = glob($buildSource . '/assets/*');
            foreach ($assets as $asset) {
                $filename = basename($asset);
                copy($asset, $buildTarget . '/assets/' . $filename);
                echo "[OK] Copiado: assets/$filename\n";
            }
        }
    } else {
        echo "[OK] build/ já existe\n";
    }
} else {
    echo "[ERRO] public/build/ não existe!\n";
}

echo "</pre>";

// 2. Criar link simbólico para vendor/livewire/
echo "<h2>2. Configurando pasta vendor/livewire/ (assets públicos)</h2>";
echo "<pre>";

$livewireSource = $basePath . '/public/vendor/livewire';
$vendorTarget = $basePath . '/vendor-public';
$livewireTarget = $basePath . '/vendor-public/livewire';

// Criar estrutura vendor-public/livewire na raiz
if (is_dir($livewireSource)) {
    if (!file_exists($vendorTarget)) {
        mkdir($vendorTarget, 0755, true);
    }

    if (!file_exists($livewireTarget)) {
        if (@symlink($livewireSource, $livewireTarget)) {
            echo "[OK] Link simbólico criado: vendor-public/livewire/ -> public/vendor/livewire/\n";
        } else {
            // Copiar arquivos
            mkdir($livewireTarget, 0755, true);

            $files = glob($livewireSource . '/*');
            foreach ($files as $file) {
                $filename = basename($file);
                copy($file, $livewireTarget . '/' . $filename);
                echo "[OK] Copiado: $filename\n";
            }
        }
    } else {
        echo "[OK] vendor-public/livewire/ já existe\n";
    }
} else {
    echo "[ERRO] public/vendor/livewire/ não existe!\n";
}

echo "</pre>";

// 3. Criar .htaccess para redirecionar /vendor/livewire para vendor-public/livewire
echo "<h2>3. Atualizando .htaccess</h2>";
echo "<pre>";

$htaccessPath = $basePath . '/.htaccess';
$htaccessContent = file_get_contents($htaccessPath);

// Adicionar regras de rewrite se não existirem
if (strpos($htaccessContent, 'vendor/livewire') === false) {
    $newRules = "
    # Redirecionar assets do Livewire
    RewriteRule ^vendor/livewire/(.*)$ public/vendor/livewire/\$1 [L]

    # Redirecionar assets do build
    RewriteRule ^build/(.*)$ public/build/\$1 [L]
";

    // Inserir antes do último RewriteRule
    $htaccessContent = str_replace(
        "    # Send Requests To Front Controller...",
        "    # Redirecionar assets estáticos para public/
    RewriteRule ^vendor/livewire/(.*)$ public/vendor/livewire/\$1 [L]
    RewriteRule ^build/(.*)$ public/build/\$1 [L]

    # Send Requests To Front Controller...",
        $htaccessContent
    );

    file_put_contents($htaccessPath, $htaccessContent);
    echo "[OK] .htaccess atualizado com redirecionamentos\n";
} else {
    echo "[OK] .htaccess já contém as regras\n";
}

echo "</pre>";

// 4. Verificar se storage link existe
echo "<h2>4. Verificando Storage Link</h2>";
echo "<pre>";

$storageLink = $basePath . '/storage/app/public';
$publicStorage = $basePath . '/public/storage';

if (!file_exists($publicStorage)) {
    if (is_dir($storageLink)) {
        if (@symlink($storageLink, $publicStorage)) {
            echo "[OK] Link storage criado\n";
        } else {
            echo "[AVISO] Não foi possível criar link storage\n";
            echo "Execute manualmente: ln -s storage/app/public public/storage\n";
        }
    }
} else {
    echo "[OK] Storage link já existe\n";
}

echo "</pre>";

// 5. Verificar estrutura final
echo "<h2>5. Verificação Final</h2>";
echo "<pre>";

$checkPaths = [
    '/public/build/manifest.json',
    '/public/build/assets/app-DlT2O1zp.css',
    '/public/build/assets/app-ByW0VTRm.js',
    '/public/vendor/livewire/livewire.min.js',
];

foreach ($checkPaths as $path) {
    $fullPath = $basePath . $path;
    if (file_exists($fullPath)) {
        echo "[OK] $path\n";
    } else {
        echo "[ERRO] $path NÃO EXISTE!\n";
    }
}

echo "</pre>";

// 6. Mostrar .htaccess atual
echo "<h2>6. Conteúdo do .htaccess</h2>";
echo "<pre>";
echo htmlspecialchars(file_get_contents($htaccessPath));
echo "</pre>";

echo "<h2 style='color: green;'>Configuração concluída!</h2>";
echo "<p>Agora tente acessar o site novamente.</p>";
echo "<h2 style='color: red;'>IMPORTANTE: DELETE ESTE ARQUIVO APÓS USAR!</h2>";
?>

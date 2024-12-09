<?php

// Função para renomear diretórios e Negocios, ajustando a capitalização
function capitalizeDirectoriesAndFiles($dir)
{
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $fileinfo) {
        if ($fileinfo->isDir()) {
            // Renomeia diretórios, alterando a primeira letra para maiúscula
            $oldPath = $fileinfo->getRealPath();
            $newDir = ucfirst(basename($oldPath));
            $newPath = dirname($oldPath) . DIRECTORY_SEPARATOR . $newDir;

            if ($newPath !== $oldPath) {
                echo "Renaming directory: $oldPath -> $newPath\n";
                rename($oldPath, $newPath);
            }
        } elseif ($fileinfo->isFile()) {
            // Renomeia Negocios, alterando a primeira letra para maiúscula
            $oldPath = $fileinfo->getRealPath();
            $newFile = ucfirst(basename($oldPath));
            $newPath = dirname($oldPath) . DIRECTORY_SEPARATOR . $newFile;

            if ($newPath !== $oldPath) {
                echo "Renaming file: $oldPath -> $newPath\n";
                rename($oldPath, $newPath);
            }
        }
    }
}

// Caminho do diretório raiz do seu projeto
$projectDir = __DIR__ . '/src'; // Altere o caminho para a raiz do seu diretório de código

// Executa a função de renomeação
capitalizeDirectoriesAndFiles($projectDir);

echo "Renaming process completed.\n";




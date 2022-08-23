<?php

namespace PhpZoomer\Traits;

trait ZoomerFileManger
{
    public function checkCredenialFileExists(string $path): bool
    {
        return file_exists($path);
    }


    /**
     * Store Credenial in file
     *
     * @param array<string, mixed> $credenial
     * @param string $path
     * @return mixed
     */
    public function storeCredenialInFile(array $credenial, string $path): mixed
    {
        return file_put_contents($path, json_encode($credenial));
    }

    /**
     * Get Credenial From file
     *
     * @param string $path
     * @return mixed
     */
    public function getCredenialFromFile(string $path): mixed
    {
        return json_decode((string) file_get_contents($path), true);
    }
}

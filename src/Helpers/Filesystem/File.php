<?php

namespace Helldar\Support\Helpers\Filesystem;

use DirectoryIterator;
use Helldar\Support\Exceptions\FileNotFoundException;
use Helldar\Support\Facades\Helpers\Arr;
use Helldar\Support\Facades\Helpers\Filesystem\Directory as DirectoryHelper;
use Helldar\Support\Facades\Helpers\Instance;
use SplFileInfo;
use Throwable;

class File
{
    /**
     * Save content to file.
     *
     * @param  string  $path
     * @param  string  $content
     * @param  int  $mode
     */
    public function store(string $path, string $content, int $mode = 755): void
    {
        DirectoryHelper::make(pathinfo($path, PATHINFO_DIRNAME), $mode);

        file_put_contents($path, $content);
    }

    /**
     * Checks if the file exists.
     *
     * @param  string  $path
     *
     * @return bool
     */
    public function exists(string $path): bool
    {
        return file_exists($path) && is_file($path);
    }

    /**
     * Deletes files in the specified paths.
     *
     * @param  string|string[]  $paths
     *
     * @return bool
     */
    public function delete($paths): bool
    {
        $paths = Arr::wrap($paths);

        $success = true;

        foreach ($paths as $path) {
            try {
                if (! @unlink($path)) {
                    $success = false;
                }
            } catch (Throwable $e) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * Checks if an object or link is a file at the specified path.
     *
     * @param  \DirectoryIterator|\SplFileInfo|string  $value
     *
     * @return bool
     */
    public function isFile($value): bool
    {
        if (Instance::of($value, [SplFileInfo::class, DirectoryIterator::class])) {
            return $value->isFile();
        }

        return is_file($value);
    }

    /**
     * Checks the existence of a file.
     *
     * @param  \DirectoryIterator|\SplFileInfo|string  $path
     *
     * @throws \Helldar\Support\Exceptions\FileNotFoundException
     */
    public function validate($path): void
    {
        if (! $this->isFile($path)) {
            throw new FileNotFoundException($path);
        }
    }
}

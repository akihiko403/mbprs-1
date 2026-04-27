<?php

if (class_exists('ZipArchive', false)) {
    return;
}

class ZipArchive
{
    public const CREATE = 1;

    private string $targetFilename = '';
    private string $workingDirectory = '';

    public function open(string $filename, int $flags = 0): bool
    {
        $this->targetFilename = $filename;
        $this->workingDirectory = sys_get_temp_dir().DIRECTORY_SEPARATOR.'zip-polyfill-'.uniqid('', true);

        if (! is_dir($this->workingDirectory)) {
            mkdir($this->workingDirectory, 0777, true);
        }

        return true;
    }

    public function addEmptyDir(string $dirname): bool
    {
        $directory = $this->workingDirectory.DIRECTORY_SEPARATOR.str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $dirname);

        if (! is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        return true;
    }

    public function addFromString(string $localname, string $contents): bool
    {
        $path = $this->workingDirectory.DIRECTORY_SEPARATOR.str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $localname);
        $directory = dirname($path);

        if (! is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        file_put_contents($path, $contents);

        return true;
    }

    public function addFile(string $path, string $localname): bool
    {
        $destination = $this->workingDirectory.DIRECTORY_SEPARATOR.str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $localname);
        $directory = dirname($destination);

        if (! is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        copy($path, $destination);

        return true;
    }

    public function close(): bool
    {
        $temporaryZip = sys_get_temp_dir().DIRECTORY_SEPARATOR.'zip-polyfill-'.uniqid('', true).'.zip';

        if (file_exists($temporaryZip)) {
            unlink($temporaryZip);
        }

        if (file_exists($this->targetFilename)) {
            unlink($this->targetFilename);
        }

        $command = sprintf(
            "powershell -NoProfile -Command \"Add-Type -AssemblyName System.IO.Compression.FileSystem; if (Test-Path -LiteralPath '%s') { Remove-Item -LiteralPath '%s' -Force }; [System.IO.Compression.ZipFile]::CreateFromDirectory('%s', '%s')\" 2>&1",
            str_replace("'", "''", $temporaryZip),
            str_replace("'", "''", $temporaryZip),
            str_replace("'", "''", $this->workingDirectory),
            str_replace("'", "''", $temporaryZip),
        );

        exec($command, $output, $exitCode);

        if ($exitCode !== 0 || ! file_exists($temporaryZip)) {
            $this->deleteDirectory($this->workingDirectory);

            throw new RuntimeException('ZipArchive polyfill failed: '.trim(implode(PHP_EOL, $output)));
        }

        rename($temporaryZip, $this->targetFilename);
        $this->deleteDirectory($this->workingDirectory);

        return true;
    }

    private function deleteDirectory(string $directory): void
    {
        if (! is_dir($directory)) {
            return;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $item) {
            if ($item->isDir()) {
                rmdir($item->getPathname());
            } else {
                unlink($item->getPathname());
            }
        }

        rmdir($directory);
    }
}

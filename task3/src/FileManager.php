<?php
declare(strict_types=1);

namespace Task3;

class FileManager
{
    public array $content;
    public string $pathMask;
    private array $arPath;

    public function __construct(string $pathMask)
    {
        $this->pathMask = $pathMask;

        $this->arPath = glob($pathMask);

        foreach($this->arPath as $path)
        {
            $this->content[] = file($path);
        }
    }

    public function getContent($id)
    {
        return $this->content[$id] ?: null;
    }

    public function getCountFiles()
    {
        return count($this->arPath);
    }
}
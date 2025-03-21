<?php
namespace App\Util;

class Files
{
    public static function getContentFromDir(string $dirPath, array $exceptions = []): string
    {
        $files    = scandir($dirPath);
        $contents = "";

        foreach ($files as $file) {

            if (
                $file === '.' || $file === '..' || array_any($exceptions, function ($value) use ($file) {
                    return $file === $value;
                })
            ) {
                continue;
            }

            if (is_dir("$dirPath/$file")) {
                $dirContent = Files::getContentFromDir("$dirPath/$file", $exceptions);
                $contents .= $dirContent;
                continue;
            }

            $fileContent = file_get_contents("$dirPath/$file") . "  ";

            $contents .= $fileContent;
        }

        return $contents;
    }

    public static function writeFile(string $contents, string $filepath = './output.txt')
    {
        $newFile = fopen($filepath, 'wr+');

        fwrite($newFile, $contents);

        fclose($newFile);
    }

}

<?php
namespace Library\Util;

class Files
{
    public static function getContentFromDir(string $dirPath, array $exceptions = []): string
    {
        $files = scandir($dirPath);
        echo $dirPath;
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

        Files::writeOnOutputFile($contents);
        return $contents;
    }

    private static function writeOnOutputFile(string $contents, string $filename = './output.txt')
    {
        $newFile = fopen($filename, 'wr+');

        fwrite($newFile, $contents);

        fclose($newFile);
    }

}

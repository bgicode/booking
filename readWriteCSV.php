<?php
function Read(string $dataPath): mixed
{
    if (($file = fopen($dataPath, 'r')) !== false) {
        while (($data = fgetcsv($file, 1000, ';')) !== false) {
            $arWritingLines[] = $data;
        }
    }
    
    fclose($file);

    return $arWritingLines;
}

function Write(array $line, $path): bool
{
    if (file_exists($path)) {
        $file = fopen($path, 'a');

        if (fputcsv($file, $line, ';')) {
            return true;
        } else {
            return false;
        };

        fclose($file);
    } else {
        return false;
    };
}

function reWrite($arList, $path)
{
    if (file_exists($path)) {
        $file = fopen($path, 'w');

        foreach ($arList as $arline){
            if (fputcsv($file, $arline, ';')) {
                // return true;
            } else {
                // return false;
                // break;
            };
        }
        return true;
        fclose($file);
    } else {
        return false;
    };
}

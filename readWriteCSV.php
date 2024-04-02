<?php
function Read(string $dataPath): array
{
    if (($file = fopen($dataPath, 'r')) !== false) {
        while (($data = fgetcsv($file, 1000, ';')) !== false) {
            $arWritingLines[] = $data;
        }
    }
    
    fclose($file);

    return $arWritingLines;
}

function Write(array $line): bool
{
    $path = 'date.csv';

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

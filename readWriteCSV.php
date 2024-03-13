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

function Write(string $dataPath, array $line)
{
    $file = fopen($dataPath, 'a');

    fputcsv($file, $line, ';');

    fclose($file);
}
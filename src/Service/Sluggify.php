<?php
namespace App\Service;

class Sluggify
{
    public function encode(string $string): string
    {
        $trimmed = trim($string);
        $sluggifiedString = str_replace('/\s+/', '-', $trimmed);
        return $sluggifiedString;
    }
}

?>

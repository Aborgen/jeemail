<?php
namespace App\DataFixtures;
use bheller\ImagesGenerator\ImagesGeneratorProvider;
use Faker\Factory;
/**
 *
 */
class GenerateImagesProvider
{
    public static function generateImage(string $dir, int $width, int $height,
                                         string $format, string $text): string
    {
        $letter = strtoupper(str_split($text)[0]);
        $faker = Factory::create();
        $faker->addProvider(new ImagesGeneratorProvider($faker));
        $image = $faker->imageGenerator($dir, $width, $height, $format, false,
                                        $letter, $faker->hexColor(),
                                        $faker->hexcolor());
        return $image;
    }
}

?>

<?php
namespace App\DataFixtures;
use bheller\ImagesGenerator\ImagesGeneratorProvider;
use Faker\Factory;

class GenerateImagesProvider
{
    private const DIR    = "/home/thatman/Documents/__Code_Personal/react-clones/jeebak/public/static/icons/";
    private const FORMAT = "jpg";
    private const IMAGE_SIZE = ["min" => 700, "max" => 2200]; // Bytes

    private function isValidImage(string $img): bool
    {
        $size = fileSize($img);
        if($size > self::IMAGE_SIZE['max'] ||
           $size < self::IMAGE_SIZE['min'] ) {
            return false;
        }

        $mimeType = mime_content_type($img);
        return $mimeType === "image/jpeg" ? true : false;
    }

    private function deleteImage(string $img): void
    {
        if($this->isValidImage($img)) {
            unlink($img);
        }
    }

    public function clearImages(): void
    {
        $glob = glob(self::DIR . '*.' . self::FORMAT);
        if(count($glob) > 0) {
            array_map('self::deleteImage', $glob);
        }
    }

    public function generateImage(int $width, int $height, string $text): string
    {
        $letter = strtoupper(str_split($text)[0]);
        $faker  = Factory::create();
        $faker->addProvider(new ImagesGeneratorProvider($faker));
        $image = $faker->imageGenerator(self::DIR, $width, $height,
                                        self::FORMAT, false, $letter,
                                        $faker->hexColor(), $faker->hexcolor());
        return $image;
    }
}
?>

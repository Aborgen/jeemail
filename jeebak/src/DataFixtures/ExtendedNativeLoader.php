<?php
namespace App\DataFixtures;

use Nelmio\Alice\Faker\Provider\AliceProvider;
use Nelmio\Alice\Loader\NativeLoader;
use Faker\Factory as FakerGeneratorFactory;
use Faker\Generator as FakerGenerator;

use App\DataFixtures\GenerateImagesProvider;

/**
 *
 */
class ExtendedNativeLoader extends NativeLoader
{
    protected function createFakerGenerator(): FakerGenerator
        {
            // Clear out Fixture images from public/static/icons
            $imgProvider = new GenerateImagesProvider();
            $imgProvider->clearImages();

            $generator = FakerGeneratorFactory::create(parent::LOCALE);
            $generator->addProvider(new AliceProvider());
            $generator->addProvider($imgProvider);
            $generator->seed($this->getSeed());

            return $generator;
        }
}

?>

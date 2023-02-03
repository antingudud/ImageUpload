<?php
namespace Aulia\ImageUpload\Test;

use Aulia\ImageUpload\File;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class TestFile extends TestCase
{
    public $testFile = array(
        'name'=>'Mentahan B17B copy.png',
        'tmp_name'=>'C:\xampp\htdocs\Image-Upload\Aulia\ImageUpload\Test\images\Mentahan B17B copy.png',
        'type'=>'image/png',
        'size'=>1472190,
        'error'=>0
     );

    public function testFileConstructor()
    {
        $this->assertInstanceOf(
            File::class,
            new File($this->testFile["tmp_name"])
        );
    }
    public function testCannotBeCreatedFromInvalidFile()
    {
        $this->expectException(InvalidArgumentException::class);
        new File($this->testFile["name"]);
    }

    public function testCanReturnTrueIfFileMimeTypeIsImage()
    {
        $file = new File($this->testFile["tmp_name"]);

        $this->assertEquals(true, $file->isImage());
    }
    public function testCanTellIfFileMimeTypeIsNotImage()
    {
        $file = new File('C:\xampp\htdocs\Image-Upload\Aulia\ImageUpload\src\File.php');

        $this->assertEquals(false, $file->isImage());
    }
}
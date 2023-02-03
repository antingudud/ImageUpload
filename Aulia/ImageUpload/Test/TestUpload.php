<?php
namespace Aulia\ImageUpload\Test;

use Aulia\ImageUpload\Filesystem\Mock;
use Aulia\ImageUpload\PathResolver\Simple;
use PHPUnit\Framework\TestCase;
use Aulia\ImageUpload\Upload;
use Aulia\ImageUpload\Validator\Simple as ValidatorSimple;

class TestUpload extends TestCase
{
    public $testFile = array(
        'name'=>'Mentahan B17B copy.png',
        'tmp_name'=>'C:\xampp\htdocs\Image-Upload\Aulia\ImageUpload\Test\images\Mentahan B17B copy.png',
        'type'=>'image/png',
        'size'=>61474,
        'error'=>0
     );
    public $testServer = Array();

    public function testFileConstructor()
    {
        $this->assertInstanceOf(
            Upload::class,
            new Upload($this->testFile, $this->testServer)
        );
    }

    public function testCanProcessFileFromValidFile()
    {
        // $files = [$this->testFile, $this->testFile];
        $files = [
            'name'=>[null, "amingus.png"],
            'tmp_name'=>[$this->testFile['tmp_name'], $this->testFile['tmp_name']],
            'type'=>[$this->testFile['type'], $this->testFile['type']],
            'size'=>[$this->testFile['size'], $this->testFile['size']],
            'error'=>[0,0]
        ];
        // $file = $this->testFile;
        $file['name'] = 'amingus.png';
        $destination = dirname(__DIR__, 1). "/Test/images/upload";
        $pathresolver = new Simple($destination);
        $upload = new Upload($files, $this->testServer);
        $validator = new ValidatorSimple("2M", ['image/png']);

        $upload->addValidator([$validator]);
        $upload->setPathResolver($pathresolver);
        $upload->setFilesystem(new Mock);
        $result = $upload->processAll();

        foreach($result as $key => $value)
        {
            $this->assertObjectNotHasAttribute('error', $value);
            if(!property_exists($value, 'completed'))
            {
                continue;
            }
            $this->assertTrue($value->completed, 'Object is true');
        }
        $this->assertEquals('C:\xampp\htdocs\Image-Upload\Aulia\ImageUpload/Test/images/upload/amingus.png', $upload->getFile()[1]->getPathname());
    }
    public function testWillGiveErrorWhenProcessingInvalidFiles()
    {
        $files = $this->testFile;
        $files['name'] = 'big cummer.png';
        // $files['tmp_name'] = null;
        $files['error'] = 1;
        $files['size'] = 614740000;

        $destination = dirname(__DIR__, 1). "/Test/images/upload";
        $pathresolver = new Simple($destination);
        $file = new Upload($files, $this->testServer);
        $validator = new ValidatorSimple("2M", ['image/png']);

        $file->addValidator([$validator]);
        $file->setFilesystem(new Mock);
        $file->setPathResolver($pathresolver);
        $result = $file->processAll();
        $this->assertObjectHasAttribute('error', $result[0]);
    }
    public function testCanGetErrorMessageFromValidCode()
    {
        $file = new Upload($this->testFile, $this->testServer);

        $this->assertEquals('The uploaded file exceeds the upload_max_filesize directive in php.ini', $file->getMessage(1));
        $this->assertEquals('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form' , $file->getMessage(2));
        $this->assertEquals('The uploaded file was only partially uploaded' , $file->getMessage(3));
        $this->assertEquals('No file was uploaded' , $file->getMessage(4));
        $this->assertEquals('Missing a temporary folder' , $file->getMessage(6));
        $this->assertEquals('Failed to write file to disk' , $file->getMessage(7));
        $this->assertEquals('A PHP extension stopped the file upload' , $file->getMessage(8));
    }
}
<div align="center" id="top"> 
  <img src="./.github/app.gif" alt="Image Upload" />

  &#xa0;

  <!-- <a href="https://imageupload.netlify.app">Demo</a> -->
</div>

<h1 align="center">Image Upload</h1>

<p align="center">
  <img alt="Github top language" src="https://img.shields.io/github/languages/top/antingudud/ImageUpload?color=56BEB8">

  <img alt="Github language count" src="https://img.shields.io/github/languages/count/antingudud/ImageUpload?color=56BEB8">

  <img alt="Repository size" src="https://img.shields.io/github/repo-size/antingudud/ImageUpload?color=56BEB8">

  <img alt="License" src="https://img.shields.io/github/license/antingudud/ImageUpload?color=56BEB8">
</p>

<!-- Status -->

<!-- <h4 align="center"> 
	🚧  Image Upload 🚀 Under construction...  🚧
</h4> 

<hr> -->

<br>

## About ##

Image upload package for backend.

How to use
``` php
    $upload = new \Aulia\ImageUpload\Upload($_FILES['form_id'], $_SERVER);

    $filesystem = new \Aulia\ImageUpload\Filesystem\Simple();

    $validator = new \Aulia\ImageUpload\Validator\Simple("2M", ['image/png', 'image/jpeg', 'application/vnd.ms-excel']);
    $pathresolver = new \Aulia\ImageUpload\PathResolver\Simple('/path/to/destination/');

    $upload->setFilesystem($filesystem);
    $upload->addValidator([$validator]);
    $upload->setPathResolver($pathresolver);
    list($files, $headers) = $upload->processAll();

    foreach($headers as $header => $value) {
        header($header . ': ' . $value);
    }

    echo json_encode(['files' => $files]);
    
    foreach($files as $file)
    {
        if($file->completed)
        {
            echo $file->getRealPath();

            var_dump($file->isFile());
        }
    }
```

Composer autoloading is recommended.
```json
{
    "autoload": {
        "psr-4": {
            "Aulia\\ImageUpload\\": "Path/To/Aulia/ImageUpload/src"
        }
    }
}

```
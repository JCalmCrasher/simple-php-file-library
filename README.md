# PHP File Controller

A simple PHP library to manage basic file operations

## Installation

Clone from the repo using https https://github.com/JCalmCrasher/php-file-controller.git or ssh git@github.com:JCalmCrasher/php-file-controller.git

## Usage

Example of use for this library:

```
    require 'path/FileController.php';

    // Instantiate the File handler
    $file = new FileController();
```

## A Simple Example

```
    require_once __DIR__ . '/FileController.php';

    // Instantiate the File handler
    $file = new FileController();

    // Validates the file and uploads if the file is okay
    $file->validateAndUploadFile('file_path', 'file_name_to_save_as');
```

## Some Handy Methods

#### Gets File Size

```
    $file->getFileSize()
```

#### Gets File Temporary Path

```
    $file->getTemporaryPath()
```

#### Gets File Extension

```
    $file->getFileExtension()
```

#### Gets Extensions allowed

```
    $file->getAllowedExtensions()
```

#### Sets Maximum File Size

```
    $file->setFileMaxSize()
```

#### Sets the allowed extensions for file

```
    $file->setAllowedExtension()()
```

## TODO

    - Add new features.
    - Improve documentation.

## Copyright

© 2020
You can contact me on [Twitter](https://twitter.com/proJosh001) or through my [email](mailto:josh001pro@gmail.com).

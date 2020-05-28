<?php

/**
 * Handle and upload $_FILES files
 * 
 * @author Josh Xtreme
 * 
 * @property array $rawFile
 * @property int $fileSize
 * @property string $fileName
 * @property string $filePath
 * @property string $fileExtension
 * @property string $fileTemporaryPath
 * @property int $fileMaxSize
 * @property array $fillableExtensions
 * @property array $fileHayStackMessage
 * 
 * @method File __construct($file)
 * @method String getFileSize()
 * @method String getFileMaxSize()
 * @method String getTemporaryPath() File fake path
 * @method String getFileExtension()
 * @method String getAllowedExtensions()
 * @method String setAllowedExtension()
 * @method String setFileMaxSize()
 * @method String uploadFile()
 * @method String fileErrorCheck()
 * @method String testFileUploadable()
 */
class FileController
{
    /**
     * @var mixed $rawFile Actual file
     */
    private $rawFile;

    /**
     * @var mixed $fileSize file size
     */
    private $fileSize;

    /**
     * @var mixed $rawFile Full file name
     */
    private $fileName;

    /**
     * @var mixed $filePath Fullfile path
     */
    private $filePath;

    /**
     * @var mixed $fileExtension File extension
     */
    private $fileExtension;

    /**
     * @var mixed $fileTemporaryPath File temporary path
     */
    private $fileTemporaryPath;


    /**
     * @var int $fileMaxSize File maximum size uploadable; default is 1000 kilobytes (1MB)
     */
    private $fileMaxSize = 1000000;

    /**
     * @var array $fillableExtensions File extensions to be allowed
     */
    private $fillableExtensions = ['jpg', 'png', 'tiff', 'txt', 'doc', 'docx', 'pdf', 'xls', 'xlsx'];

    /**
     * @var mixed $fileHayStackMessage contain stack error messages
     */
    public $fileHayStackMessage = [];

    /**
     * Accepts raw file
     * @param mixed $file Actual file
     * @return mixed
     */
    public function __construct($file = [])
    {
        $this->rawFile = $file;
        $this->fileName = $this->rawFile['name'];
        $this->fileSize = $this->rawFile['size'];
        $this->fileTemporaryPath = $this->rawFile['tmp_name'];
        $this->fileExtension = strtolower(end(explode('.', $this->fileName)));

        return $this->rawFile;
    }

    /**
     * Sets allowdd extensions
     * 
     * @param mixed $allowedExtensions Allowed extension(s)
     * @return void
     */
    public function setAllowedExtension($allowedExtensions = ['*'])
    {
        if (isset($allowedExtensions)) {
            $this->fillableExtensions = $allowedExtensions;
        }
    }

    /**
     * Set file max size
     * 
     * @param mixed $fileSize File size
     * @return void
     */
    public function setFileMaxSize($fileSize)
    {
        $this->fileMaxSize = $fileSize;
    }

    /**
     * Returns file name
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * ReturnsFile size
     * @return int
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * Returns File max size
     * @return int
     */
    public function getFileMaxSize()
    {
        return $this->fileMaxSize;
    }

    /**
     * Returns File extension
     * @return int
     */
    public function getFileExtension()
    {
        return $this->fileExtension;
    }

    /**
     * Returns temporary file path
     * @return string
     */
    public function getTemporaryFilePath()
    {
        return $this->fileTemporaryPath;
    }

    /**
     * Returns file path
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * Returns allowed file extension(s)
     * @return array
     */
    public function getAllowedExtensions()
    {
        return $this->fillableExtensions;
    }

    /**
     * Test if file passed basic tests
     * 
     * @return string
     */
    public function testFileUploadable()
    {
        if (isset($this->rawFile)) {
            if (!in_array($this->fileExtension, $this->fillableExtensions)) {
                $extVerb = count($this->fillableExtensions) > 1 ? "are" : "is";
                $extensions = implode(', ', $this->fillableExtensions);
                $this->fileHayStackMessage[0] = "This file extension is not allowed, only" . $extensions . " $extVerb allowed";
            } else if ($this->fileSize > $this->fileMaxSize) {
                $this->fileHayStackMessage[0] = "The file is larger than the file size set; select another image or increase file size capacity";
            } else {
                unset($this->fileHayStackMessage[0]);
                return "You are good to go!";
            }
        } else {
            return "Please select a file";
        }

        if (count($this->fileHayStackMessage) > 0) {
            foreach ($this->fileHayStackMessage as $message) {
                return "$message\n";
            }
        };
    }

    /**
     * Validate file and upload
     * 
     * @param $fileDirectoryToUpload The upload directory
     * @param $fileNameToUse File name to save file
     * 
     * @return int
     */
    public function validateAndUploadFile($fileDirectoryToUpload, $fileNameToUse)
    {
        if (!isset($this->rawFile)) {
            $this->fileHayStackMessage[0] = "Please select a file";
        } else {
            if (!in_array($this->fileExtension, $this->fillableExtensions)) {
                $extVerb = count($this->fillableExtensions) > 1 ? "are" : "is";
                $extensions = implode(', ', $this->fillableExtensions);
                $this->fileHayStackMessage[0] = "This file extension is not allowed, only file extensions with " . $extensions . " $extVerb allowed";
            } else if ($this->fileSize > $this->fileMaxSize) {
                $this->fileHayStackMessage[0] = "The file is too large; please select another image";
            } else {
                return $this->uploadFile($fileDirectoryToUpload, $fileNameToUse);
                // $this->fileHayStackMessage[0] = 1;
            }
        }
    }

    /**
     * Uploads file
     * @param $fileDirectoryToUpload The upload directory
     * @param $fileNameToUse File name to save file
     * 
     * @return int
     */
    public function uploadFile($fileDirectoryToUpload, $fileNameToUse)
    {
        $uploadFilePath = $fileDirectoryToUpload . '/' . basename($fileNameToUse) . "." . $this->fileExtension;
        file_exists($fileDirectoryToUpload) ? $this->filePath = $uploadFilePath : mkdir($fileDirectoryToUpload);

        if ($this->rawFile['error'] == UPLOAD_ERR_OK) {
            $isFileMoved = move_uploaded_file($this->fileTemporaryPath, $uploadFilePath);
            $this->filePath = $uploadFilePath;
            if ($isFileMoved) return 1;
        } else {
            $this->fileErrorCheck();
        };
    }

    /**
     * Checks file against file error constants
     * 
     * @return void
     */
    private function fileErrorCheck()
    {
        switch ($this->rawFile['error']) {
            case UPLOAD_ERR_INI_SIZE:
                $this->fileHayStackMessage[0] = "The uploaded file exceeds the size set";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $this->fileHayStackMessage[0] = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                break;
            case UPLOAD_ERR_PARTIAL:
                $this->fileHayStackMessage[0] = "The uploaded file was only partially uploaded";
                break;
            case UPLOAD_ERR_NO_FILE:
                $this->fileHayStackMessage[0] = "No file was uploaded";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $this->fileHayStackMessage[0] = "File folder couldn't not be found";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $this->fileHayStackMessage[0] = "This file couldn't not be save, please try again";
                break;
            case UPLOAD_ERR_EXTENSION:
                $this->fileHayStackMessage[0] = "File upload stopped by extension";
                break;

            default:
                $this->fileHayStackMessage[0] = "Unknown upload error";
                break;
        }
    }
}

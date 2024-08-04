<?php 
    namespace Jubilant;

    
    use Jubilant\RandomString;
    use Jubilant\Database;
    use Jubilant\Lang;
    
    class FileUpload {
        private string $targetDirectory;
        private int $maxFileSize;
        private array $allowedFileTypes = [];
        
        public function __construct(string $targetDirectory, array $allowedFileTypes = ["jpg", "png", "gif"], $maxFileSize = 500000) {
            require_once __DIR__.'/../settings.php';
            if($targetDirectory != null) {
                $this->targetDirectory = $targetDirectory;
                $this->allowedFileTypes = $allowedFileTypes;
                $this->maxFileSize = $maxFileSize;
            } else {
                echo Lang::trans('emptyUploadDirectory');
            }
        }
        
        private function createUploadedFilesTable() {
            require_once __DIR__.'/../settings.php';
            $Database = new Database($DatabaseConnection[0], $DatabaseConnection[1], $DatabaseConnection[2], $DatabaseConnection[3]);
            $Database->connect();

            $query = "CREATE TABLE IF NOT EXISTS uploadedFiles(
                ID INT AUTO_INCREMENT PRIMARY KEY,
                customID VARCHAR(255) NOT NULL,
                fileName VARCHAR(255) NOT NULL,
                status VARCHAR(255) NOT NULL,
                uploadTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";

            if(!$Database->getConnection()->query($query)) {
                return Lang::trans('cantCreateFileUploadsTableError');
            }

            return true;
        }

        public function upload($file, ?bool $customFileName = false, ?bool $uploadToDatabase = false, ?string $fileCustomID = null) {
            require_once __DIR__.'/../settings.php';
            $Database = new Database($DatabaseConnection[0], $DatabaseConnection[1], $DatabaseConnection[2], $DatabaseConnection[3]);
            $Database->connect();
            $RandomString = new RandomString();
            $fileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

            if($file["size"] > $this->maxFileSize) {
                echo Lang::trans('tooBigFilesize');
                return false;
            }
            if(!in_array($fileType, $this->allowedFileTypes)) {
                echo Lang::trans('inaptFileFormat');
                return false;
            }

            if($customFileName === true) {
                $targetFile = $this->targetDirectory.$RandomString->generateCustomString('',1,5).'.'.$fileType;
            } else {
                $targetFile = $this->targetDirectory.basename($file["name"]);
            }

            if($uploadToDatabase === true) {
                $this->createUploadedFilesTable();

                $fileid = strval($fileCustomID);
                $filename = strval($targetFile);

                $Database->insert("uploadedfiles",array("$fileid","$filename","true"),array("customID","fileName","status"));
            }

            if(move_uploaded_file($file["tmp_name"], $targetFile)) {
                return Lang::trans('fileUploadedSuccessfully');
            } else {
                return Lang::trans('fileUploadedUnsuccessfully');
            }

            return true;
        }

    }
?>
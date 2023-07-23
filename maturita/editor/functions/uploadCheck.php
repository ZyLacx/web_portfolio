<?php
class UploadCheck {

    private $dirFiles;
    private $path;

    function __construct()
    {
    }

    function setDirFiles($path) { $this->dirFiles = array_slice(scandir($path), 2); $this->path = $path;}

    function uploadCheck($files){
        $errors = [];
        $i = 0;
        foreach($files as $file){            
            $fileName = $file["name"];
            $fileTypeArr = $file["type"];
            $fileTmpName = $file["tmp_name"];
            $fileSize = $file["size"];
            $error = $file["error"];
            $maxSize = 20000000;
        
            // @param $fileGetExt zoberie file name a rozdelí pri bodkách
            // @param  string $fileExt  zmení na lowercase nech je to fool proof a zoberie poslednú vec z arrayu -> end()
    
            $fileType = explode("/", $fileTypeArr);

            // if(strpos("img", $this->path) === false){
            //     if($fileType[0] == "image" || $fileType[0] == "video"){
            //         $args = $fileName . ":type";
            //         return $args;
            //     }
            // }
            
            $fileName = str_replace(" ", '_', $fileName);

            $fileExt = explode(".", $fileName);
            $ext = strtolower(end($fileExt));

            // checkne file extension a ak je valid tak skontroluje či tam nie je error alebo nie je príliš veľký
            if(!in_array($fileName, $this->dirFiles)){
                    if($error === 0){
                        if ($fileSize < $maxSize){
                            $filePath = $this->path . $fileName;    
                            move_uploaded_file($fileTmpName, $filePath);
                            if($fileType[0] == "image"){
                                $this->image_resize($fileName, $ext, 400);
                            }
                        
                        }
                        else {
                            $errors["size"][$i] = $fileName;
                        }
                    }
                    else {
                        $errors["upload"][$i] = $fileName."\n".$error;
                    }
                }
            else{
                $errors["name"][$i] = $fileName;
            }
            $i++;
        }
        return $errors;
    }
    
    function checkErrors($uploadErrors){
        $fileNames = "";
        $errorMessages = [];
        foreach($uploadErrors as $index => $errorName){
            foreach($errorName as $error){
                $fileNames .= $error.", ";
            }
                switch($index){
                    case "size":
                        array_push($errorMessages, $fileNames."\nSúbor je príliš veľký");
                        break;
                    case "upload":
                        array_push($errorMessages, $fileNames."\nBol problém s nahrávaním súboru");
                        break;
                    // case "format":
                    //     echo 'Zlý formát súboru, skúste súbor prekonvertovať <a href="https://www.online-convert.com/">tu.</a>';        
                    //     break;
                    case "name":
                        array_push($errorMessages, $fileNames."\nSúbor s týmto menom už existuje.");
                        break;
                    // case "type":
                    //     echo "Nenahrávajte do dokumentov videá a obrázky";
                    //     break;
                    default:
                        array_push($errorMessages, "Neznáma chyba");
                        break;
                    }
            }
        $encodedErrMess = json_encode($errorMessages);
        return $encodedErrMess;
    }
    
    // Zlepší čítateľnosť
    function OrganizeArray($files){
        $file_array = array();
        $file_count = count($files["name"]);
        $file_keys = array_keys($files);
    
        for ($i = 0; $i < $file_count; $i++){
            foreach ($file_keys as $key) {
                $file_array[$i][$key] = $files[$key][$i];
            }
        }
    
        return $file_array;
    }

    function image_resize($file, $ext, $w){
        $old_file = $this->path.$file;
        $new_file = $this->path."thumbnails/".$file;

        list($width, $height) = getimagesize($old_file);
        $ratio = $width / $height;
        $h = $w / $ratio;

        if($ext == "png"){            
            $src = imagecreatefrompng($old_file);
            $dst = imagecreatetruecolor($w, $h);
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            $transparency = imagecolorallocatealpha($dst, 255, 255, 255, 127);
            imagefilledrectangle($dst, 0, 0, $width, $height, $transparency);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $width, $height);
            imagepng($dst, $new_file);
        }
        else{
            $src = imagecreatefromjpeg($old_file);
            $dst = imagecreatetruecolor($w, $h);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $width, $height);
            imagejpeg($dst, $new_file);
        }

        imagedestroy($src);
        imagedestroy($dst);
    }
}
    
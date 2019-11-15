<?php
$data = [];
$num = "/^([+]{1}[0-9]{1,4}|0{1})[0-9]{10,11}$/";
function inputFileCheck($check)
{
    # Check if file file is a actual file or fake file
    if ($check) {
        return true;
    }
}
function inputFileType($presentType, $fileTypes)
{
    #  Allow certain file formats
    if (in_array($presentType, $fileTypes)) {
        return true;
    }
}
function inputFileSize($FileSize, $wantedSize)
{
    // Check file size
    if ($FileSize < $wantedSize) {
        return true;
    }
}
function inputFileExistence($fileLocation)
{
    #Check if file already exists
    if (file_exists($fileLocation)) {

        return true;
    }
}
function fileMigrate($file_temp, $target_file)
{
    # code...
    if (move_uploaded_file($file_temp, $target_file)) {
        return true;
    }
}
function checkingFile($name, $fileTypes, $wantedSize, $data)
{
    # code...echo 
    $target_dir = "content/files/";

    $filenameWithExt = basename($_FILES[$name]["name"]);
    $FileType = pathinfo($filenameWithExt, PATHINFO_EXTENSION);
    $target_file = $target_dir . pathinfo($filenameWithExt, PATHINFO_FILENAME) . '_' . time() . '.' . $FileType;

    if ($name === "passport") {
        $check = getimagesize($_FILES[$name]["tmp_name"]);  # Check if passport file is a actual passport or fake passport
        if (!inputFileCheck($check)) {
            $data["$name" . "_err"] = " File is not an $name.<br/>";
            return $data["$name" . "_err"];
        }
    }


    $inputFileSize = $_FILES[$name]["size"];

    $inputFile_tmp = $_FILES[$name]["tmp_name"]; #$_FILES["fileToUpload"]["tmp_name"]

    # Check if file already exists
    if (inputFileExistence($target_file)) {
        $data["$name" . "_err"] = "<br/>Sorry, file already exists. $name.<br/>";
        return $data["$name" . "_err"];
    }
    #check file size
    if (!inputFileSize($inputFileSize, $wantedSize)) {
        $data["$name" . "_err"] = "<br/>Sorry, your file is too large.. $name.<br/>";
        return $data["$name" . "_err"];
    }
    // Allow certain file formats
    if (!inputFileType($FileType, $fileTypes)) {
        $data["$name" . "_err"] = "Sorry, only " . strtoupper(implode(', ', $fileTypes)) . " files are allowed. $name.<br/>";
        return $data["$name" . "_err"];
    }

    if (empty($data["$name" . "_err"])) {
        $data[$name] = pathinfo($filenameWithExt, PATHINFO_FILENAME) . '_' . time() . '.' . $FileType;
        if (!move_uploaded_file($inputFile_tmp, $target_file)) {
            return $data["$name" . "_err"];
        } else {
            return $data["$name"];
        }
    } else {
        return  $data["$name" . "_err"];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    # code...
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    //var_dump($_POST);
    $data = [

        'passport' => '',
        'resume' => '',
        'cover_letter' => '',
        'first_name' => trim($_POST['first_name']),
        'last_name' => trim($_POST['last_name']),
        'email' => trim($_POST['email']),
        'phone_no' => trim($_POST['phone_no']),
        'address' => trim($_POST['address']),
        'city' => trim($_POST['city']),
        'state' => trim($_POST['state']),
        'country' => trim($_POST['country']),
        'passport_err' => '',
        'resume_err' => '',
        'cover_letter_err' => '',
        'first_name_err' => '',
        'last_name_err' => '',
        'address_err' => '',
        'city_err' => '',
        'state_err' => '',
        'country_err' => '',
        'phone_no_err' => '',
        'email_err' => '',
    ];
    //var_dump($data, $_FILES);
    $age = array("passport" => ["jpg", "gif", "png", "jpeg"], "resume" => ["pdf", "doc", "docx"], "cover_letter" => ["pdf", "doc", "docx"]);

    foreach ($age as $x => $x_value) {
        $checked = checkingFile($x, $x_value, $wantedSize = 6582840, $data);

        if ($checked) {
            $filenameWithExt = basename($_FILES[$x]["name"]);
            $FileType = pathinfo($filenameWithExt, PATHINFO_EXTENSION);
            $data[$x] = pathinfo($filenameWithExt, PATHINFO_FILENAME) . '_' . time() . '.' . $FileType;
        }
    }

    if (
        empty($data['passport_err']) && empty($data['resume_err']) && empty($data['cover_letter_err'])
        && empty($data['phone_no_err']) && empty($data['country_err']) && empty($data['first_name_err'])
        && empty($data['state_err']) && empty($data['city_err']) && empty($data['email_err'])
        && empty($data['last_name_err']) && empty($data['address_err'])
    ) {
        # code...# Set DSN
        $dsn = "mysql:host=localhost;dbname=demo";
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        //CREATE PDO INSTANCE
        try {
            $dbh = new PDO($dsn, "root", "0907IT02352", $options);

            $sql = "INSERT INTO careers VALUES (NULL,'" . $data['first_name'] . "', '" . $data['last_name'] . "', '" . $data['email'] . "', '" .
                $data['phone_no'] . "', '" . $data['address'] . "', '" . $data['city'] . "', '" . $data['state'] . "', '" . $data['country'] . "', '" .
                $data['passport'] . "', '" . $data['resume'] . "', '" . $data['cover_letter'] . "')";
            $stmt = $dbh->prepare($sql);
            if ($stmt->execute()) {
                return true;
                var_dump($dbh, $dsn, $data);
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}

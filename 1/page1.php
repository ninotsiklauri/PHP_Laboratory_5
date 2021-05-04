<!DOCTYPE html>
<html lang="en">
<head>
    <title>N1</title>
    <style>
        .page1{
            border: 2px solid black;
            width: 500px;
            margin: 20px auto;
            padding: 10px;
            border-radius: 10px;
            background-color: wheat;
        }
    </style>
</head>
<body>
    <div class="page1">
    <?php
        if(isset($_GET['folder'])){
            mkdir("files");
        }
        if (is_dir("files")) {
            echo '<p>Folder Created</p>';
        }else{
            echo '<p><a href="page1.php?folder=files">Make Folder For Files</a></p>';
        }
        $size_error = "";
        $type_error = "";
        if (isset($_POST['upload'])) {
            $file = $_FILES['file'];
            // print_r($file);
            if ($file['size']>100*1024*1024) {
                $size_error = "Error of Size!!!";
            }
            $file_type = pathinfo($file['name'], PATHINFO_EXTENSION);
            // echo $file_type;
            if ($file_type != "PNG" && $file_type != "jpg" && $file_type != "gif") {
                $type_error = "Error of Type!!!";
            }
            if(empty($size_error) && empty($type_error)){
                date_default_timezone_set("Asia/Tbilisi");
                $file_patch = "files/".date('Y-m-d-h-i-sa').".".$file_type;
                move_uploaded_file($file['tmp_name'], $file_patch);
            }
        }
    ?> 
    <p><?=$size_error?></p>
    <p><?=$type_error?></p>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="file">
        <br><br>
        <button name="upload">Upload File</button>
    </form>
    </div>
</body>
</html>
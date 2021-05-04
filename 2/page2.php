<?php
if (!is_dir('files')){
	mkdir('files');
}
if (isset($_REQUEST['action']) && isset($_REQUEST['file'])){
	$action = $_REQUEST['action'];
	$file = urldecode($_REQUEST['file']);
	$file_with_dir = 'files/'.$file;

	if (file_exists($file_with_dir)){
		if ($action == 'delete'){
			if(preg_match('/^[^.][-a-z0-9_.]+[a-z]$/i', $file)){
				unlink($file_with_dir);
				header('Location:'.$_SERVER['PHP_SELF']);
				exit;
			}else{
				die('რაღაც შეცდომაა');
			}
		}else if ($action == 'download'){
			if(preg_match('/^[^.][-a-z0-9_.]+[a-z]$/i', $file)){
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="'.basename($file_with_dir).'"');
	            header('Expires: 0');
	            header('Cache-Control: must-revalidate');
	            header('Pragma: public');
	            header('Content-Length:'.filesize($file_with_dir));
	            // flush();
	            readfile($file_with_dir);
	            exit;
			}else{
				die('რაღაც შეცდომაა');
			}
		}
	}else{
		die('ფაილი არ არსებობს');
	}
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['File'])){
	$file_name = basename($_FILES['File']['name']);
	$file = 'files/' . str_replace(' ', '_', $file_name);
	$tmp_name = $_FILES['File']['tmp_name'];
	$size = $_FILES['File']['size'];

	if ($size >= ((50 * 1024) * 1024) || $size == 0){
		die('<p>ფაილი არ უნდა აღემატებოდეს 50 მეგაბაიტს</p>');
	}else{
		if (!move_uploaded_file($tmp_name, $file))
		{
			echo'<p>ფაილი:'.htmlspecialchars($_FILES['File']['name']).'ვერ აიტვირთა</p>';
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Files Managment</title>
	<style>
		.container{
			border: 2px solid black;
			margin: auto;
			width: 600px;
			height: 100px;
			padding: 20px;
		}
	</style>
</head>
<body>
	<div class="container">
		<div class="files">
			<table style="width:50%;">
				<?php 
				foreach (array_diff(scandir('files/'), array('.', '..')) as $file){
					echo '<tr><td>'.htmlspecialchars($file).'</td><td><a href="?action=delete&file='
					.urlencode($file).'">წაშლა</a><span> || </span><a href="?action=download&file='
					.urlencode($file) .'">ჩამოტირთვა</a></td><tr>';
				}
				?>
			</table>
		</div>
		<form action="" method="POST" enctype="multipart/form-data">
			<br>
			<input type="file" name="File">
			<button type="submit">Upload</button>
		</form>
	</div>
</body>
</html>
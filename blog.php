<?php
if (file_exists(dirname(__FILE__). '/database.php')) {
    require_once(dirname(__FILE__). '/database.php');
}

// init database
$database = DB::getInstance();
//if its an Insert Article Statment
if( array_key_exists("blog", $_POST) && array_key_exists("title", $_POST))
{
	//get field data from form
	$blog = $_POST["blog"];
	$title = $_POST["title"];
	$author_id = 1;//"admin";
	$time = date("Y-m-d H:i:s");
	//remove illegal characters for file name
	$time = str_replace(':', '_', $time);
	$time = str_replace(' ', '_', $time);
	
	if(array_key_exists("upload", $_FILES)){
		$target_dir = "img/uploads/";
		$target_file = $target_dir . basename($_FILES["upload"]["name"]);
		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		$target_rename = $target_dir . $title."_".$time.".".$imageFileType;
		// Check if image file is a actual image or fake image
		if(isset($_POST["submit"])) {
		    $check = getimagesize($_FILES["upload"]["tmp_name"]);
		    if($check !== false) {
		        echo "File is an image - " . $check["mime"] . ".";
		        $uploadOk = 1;
		    } else {
		        echo "File is not an image.";
		        $uploadOk = 0;
		    }
		}
	}
	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
	    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
	    $uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
	    echo "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} else {
	    if (move_uploaded_file($_FILES["upload"]["tmp_name"], $target_rename)) {
	        echo "The file has been uploaded.";
	    } else {
	        echo "Sorry, there was an error uploading your file.";
	    }
	}
	$image_path = $target_rename;

	//create database query
	$query =  'INSERT INTO blog (title, body, author_id, image_path)
	                        VALUES (?,?,?,?)';
	//execute query
	$res = $database->query($query, [$title, $blog, $author_id, $image_path]);
	print_r( $database->results());
	header("Location: /tech");
	die();


	
}else
	if (array_key_exists("id", $_GET)) 
	{
		$id = $_GET["id"];
		if ($id != '*')
		{			
			$query =  "SELECT * FROM blog WHERE id = $id";
			$res = $database->query($query);
			$D = $database->results();
			$result = array('id' => $id , 'title' => $D[0]->title, 'body' => $D[0]->body, 'image' => $D[0]->image_path );
        	print_r($result);
		}else 
		{
			$query =  "SELECT * FROM blog ";
			$res = $database->query($query);
			$D = $database->results();
			$results = array();
			foreach($D as $d) 
			{
				$blog = array('id' => $d->id, 'title' => $d->title, 'body' => $d->body, 'image' => $d->image_path);
				$result[] = $blog['body'];
			}
        	print_r($result);
		}
	}




//print($title."<br/>");
//print($blog."<br/>");
//print($blog);
//print_r($_POST)



?>





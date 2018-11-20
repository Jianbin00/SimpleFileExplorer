<!--
Author:Jianbin Li
FileName:simple_file_explorer.php
Created: 03/28/2016
Modified:11/19/2018
Description: A simple file explorer as a netdisk. Can Move, create folders, upload and download files.

-->

<html>
  <head><title>Test</title>
    <script language="JavaScript" src="http://code.jquery.com/jquery-latest.js"> 
    </script>
    <script>
        $(document).ready(function(){
        	$("#moveFile").hide();
        	$("#createDir").hide();
        	$("#upload").hide();
        });
        
        function toShow(){
            
            var cb=document.getElementsByClassName("checkbox");
            var ischecked;
            for(var i=0;!ischecked&&i<cb.length;i++)
            {
                if(cb[i].checked)
                {
                    ischecked=true;
                }
            }
            if(ischecked)
            {
                $("#moveFile").show();
            }else{
                alert("Please choose the file(s) to move.");
            }
        }
        
        function cancelMove(){
            var selects=document.getElementById("targetDir").options;
            for(var i = 0; i < selects.length; i++){
      selects[i].selected = false;
    }
            $("#moveFile").hide();
        }
        
        function toShowCreate(){
            $("#createDir").show();
        }
        
        function cancelCreate(){
            document.getElementById("newDirName").value="";
            $("#createDir").hide();
        }
        
        function toShowUpload(){
            $("#upload").show();
            }
        function cancelUpload(){
            document.getElementsByName("upfile")[0].value=null;
            $("#upload").hide();
        }


    </script>
    <style>
        .fileimg{
            border none;
            width:32px;
            height:32px;
        }
        .dirimg{
            border none;
            width:32px;
            height:32px;
        }
        
        /***** This is all button styles *****/
  
  		input[type = "button"], input[value = "Create"], input[type = "submit"] {
  			background-color: #6666ff;
  			border: 1px solid #6666ff;
  			border-radius: 3px;
  			color: white;
  			padding: 5px;
  			font-size: 0.8em;
  		}
  		
  		input[type = "button"]:hover, input[value = "Create"]:hover, input[type = "submit"]:hover {
  			background-color: blue;
  			border: 1px solid blue;
  			border-radius: 3px;
  			color: white;
  			padding: 5px;
  			font-size: 0.8em;
  			cursor: pointer;
  		}
  		
  		input[type = "submit"] {
  			margin-right: 5px;
  		}
  		
  		/***** This is file upload button style *****/

		.custom-file-input::-webkit-file-upload-button {
  			visibility: hidden;
  			width: 100px;
		}
		
		.custom-file-input::before {
  			content: 'Choose files';
  			display: inline-block;
  			background-color: #6666ff;
  			border: 1px solid #6666ff;
  			border-radius: 3px;
  			padding: 5px 8px;
  			outline: none;
  			white-space: nowrap;
  			-webkit-user-select: none;
  			cursor: pointer;
  			font-size: 1.2em;
  			color: white;
		}
		
		.custom-file-input:hover::before {
  			border-color: #6666ff;
  			background-color: blue;
		}
		
		body{
			width: 100%;
		}
		img{
		    width:40px;
		    height:40px;
		}
		
		.popup{
		    background:#8FBC8F;
            position:absolute;
            left:50%;
            top:50%;
            padding-left:20px;
            padding-right:20px;
            padding-top:20px;
            padding-bottom:20px;
		}
		
		#moveFile{
		    margin-left:-150px;
		    margin-top:-150px;
		}
		#createDir{
		    margin-left:-150px;
		    margin-top:-100px;
		}
		#upload{
		    margin-left:-150px;
		    margin-top:-100px;
		}
		
		#filelist {
			margin: 0 auto;
			width: 50%;
			padding: 20px 20px 20px 70px;
		}
        
    </style>
    
    </head>
  <body>

    <form id='filelist' method='post' action=''> 

<?php
    /*
    $rootUrl="files";
    $page="simple_file_explorer.php";
    
    */
    function getFileUrl($file)
    {
        return "<tr><td><input type='checkbox' class='checkbox' name='selectFile[]' value=".$file."></td><td><img class='fileimg' src='file.ico'></td><td><a href=".$file.">".getFileName($file)."</a></td><td>".human_filesize(filesize($file))."</td><td>".date ("M d Y", filemtime($file))."</td><td><a class='downloadLink' id='a_lnk' href='$file' download>download</a>";
    }
    function getImageUrl($file)
    {
        return "<tr><td><input type='checkbox' class='checkbox' name='selectFile[]' value=".$file."></td><td colspan='2'><a href=".$file." >"."<img src=".$file." alt=".getFileName($file)."></a></td><td>".human_filesize(filesize($file))."</td><td>".date ("M d Y", filemtime($file))."</td><td><a class='downloadLink' id='a_lnk' href='$file' download>download</a>";
    }
	function getDirUrl($file)
	
	{
	    return "<tr><td><input type='checkbox' class='checkbox' name='selectFile[]' value=".$file."></td><td><img class='dirimg' src='dir.ico'></td><td><a href='simple_file_explorer.php?loc=".$file."'  name='loc'>".getFileName($file)."</a></td><tr>";
	}
	//Get the File name like 'File1' in 'dir/File1'
	function getFileName($file)
	{
	    $parts=explode("/",$file);
	    return $parts[count($parts)-1];
	}
	
	function getLoc($rootUrl)
	{
	    if(isset($_GET['loc'])&&!($_GET['loc']==''||preg_match("~\.~",$_GET['loc'])||preg_match("~\.\.~",$_GET['loc'])||!preg_match("~".$rootUrl."~",$_GET['loc'])))
	    {

            $loc=$_GET['loc'];
        
        }else{
            $loc=$rootUrl;
        }
        return $loc;
    }
	
	    
	function printUrl($file)
	{

        if(is_dir($file))
        {
            print getDirUrl($file);
        }else if(isImage($file))
		{
			print getImageUrl($file);
		}else{
			print getFileUrl($file);
		}

	}

	function getPre($dir)
	{
	    $parts=explode("/",$dir);
	    $parts[count($parts)-1]="";
	    $loc=implode("/",$parts);
	    return substr($loc,0,strlen($loc)-1);
	}
	
	function isImage($file)
	{
		$finfo=finfo_file(finfo_open(FILEINFO_MIME_TYPE),$file);
		return preg_match("~image~",$finfo);
	}

    function getFiles($dir, $type = '*') {
       if (is_dir($dir) & $dir != '..') {
        //$fileList = glob("$dir/*$type");
        return glob("$dir/$type");
        //foreach ($fileList as $file) {
         /// return $file;
        }
      } 
      
    function getTargetDir($dir,$space)
    {
        return "<option value=$dir >".$space.getFileName($dir)."</option>";
    }
    function getDirs($dir,$space='')
    {
        if(is_dir($dir))
        {
            print getTargetDir($dir,$space);
            $space=$space."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
            $fileList=getFiles($dir);
            foreach($fileList as $file)
            {
                if(is_dir($file))
                {
                    getDirs($file,$space);
                }
            }
        }
    }
    function human_filesize($bytes, $decimals = 2) {
        $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }
    
    function moveFile() {
       $targetDir = $_POST['targetDir'];
       $selectFile =  $_POST['selectFile'];
        foreach($selectFile as $file) {
        rename($file, $targetDir . "/" .  getFileName($file));
        }
         refresh();
    }
    function refresh(){
            $url = $_SERVER["REQUEST_URI"];  
            print "<script type='text/javascript'>";  
            print "window.location.href='$url'";  
            print "</script>";  
    }

    $rootUrl="files";

    $loc=getLoc($rootUrl);
	$fileList=getFiles($loc);
    if(count($fileList)==0){
        print "<p>Can't find directories or files in this directory.</p>";
    }else{        
            print "<table>";
            print "<tr><th></th><th colspan='2'>Name</th><th>Size</th><th>Last Modified Date</th><th>Download</th></tr>";
    	if(!preg_match("~".$rootUrl."\/*~",$loc))
    	{
    	    print "<tr><td></td><td><a href='simple_file_explorer.php?loc=".getPre(getLoc($loc))."'  name='loc'>..</a></td></tr>";
    	}
        foreach($fileList as $file)
        {

            printUrl($file);
            
        }
        print "</table>";
    }
    ?>
    <input type='button' id='moveButton' value='Move' onclick='toShow()'>
    <input type='button' id='createDirButton' value='Create Directory' onclick='toShowCreate()'>
    <input type='button' id='uploadButton' value='Upload' onclick='toShowUpload()'>
    
    <?php
    print "<div class='popup' id='moveFile'>";
    print "<p>Secect the directory to move in:</p>";
    print "<select id='targetDir' name='targetDir' size=10>";
    getDirs($rootUrl);
    print "</select>";
    print "<br><input type='submit' value='Move'><input type='button' id='cancelButton' value='Cancel' onclick='cancelMove()'>";
    print "</div>";
    
    
    print "<div class='popup' id='createDir'>";
    print "<p>Input the name to directory:</p>";
    print "<input type='text' id='newDirName' name='newDirName'>";
    print "<br><input type='submit' value='Create'><input type='button' id='cancelButton' value='Cancel' onclick='cancelCreate()'>";
    print "</div>";
	
	if (isset($_POST['newDirName'])) {

        $newDir = $loc."/".$_POST['newDirName'];

        if (!file_exists("$newDir")) {
        
            mkdir ("$newDir");
            refresh();
   
 

        }
        
    }




  

	?>
<!--- added the code for the file uploading. -->

   </form>
<div class='popup' id='upload'>
   <form method='post' action='' enctype='multipart/form-data'>

   <input type='hidden' name='MAX_FILE_SIZE' value='50000'>

    Select a file to upload (Maximum size: 50KB) <br><br><input type='file' name='upfile' class='custom-file-input'>

   <br><br><input type='submit' value='Upload'><input type='button' value='Cancel' onClick='cancelUpload()'>
   

   </form>
</div>
<?php
  // Move file 
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['targetDir']) & isset($_POST['selectFile'])) {
      moveFile();
    } 
  }

  if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // handle the file upload


    if (isset($_FILES['upfile'])) {

      $newname = $_FILES['upfile']['name'];

      $url = "./";

      if($_FILES['upfile']['error'] != UPLOAD_ERR_OK){

        //print "<p>File upload unsuccessful.</p>";

        //print "<p>Try uploading again." ;

      } else {

        move_uploaded_file($_FILES['upfile']['tmp_name'], getLoc($rootUrl) . "/" . $newname);

//      or die("can't move file to $newname");

        refresh();

      //  print "<p>The file has been uploaded .</p>";

      }

    }

  }

?>

  </body>
</html>

<?php 
/**
*
* Protecting sub-directories from file-listing using PHP
*
* @author    	Max Kostinevich <info@circlewaves.com>
* @license   	GPL-2.0+
* @link      	http://circlewaves.com
* @copyright 	2014 Max Kostinevich / Circlewaves Team <info@circlewaves.com>
* @version		1.0.0
*
*/


/**
*----------------------------------------- 
* Function is walks recursively through the subdirectories and looking for index files (index.php / index.html and etc)
* If index file does not exists - function will create 'dummy' index file
*-----------------------------------------
*/
function protect_folder($path,&$files = array()){
	if(is_dir($path)){
    $dir = opendir($path);
		// Walk recursively through the subdirectories 
    while($folder = readdir($dir)){
				// If subdirectory is folder (and not a "." ".." folder) and does not contains index.php or index.html - create it
				$sub = $path."/".$folder;
				
				// Add or remove filenames which should be checked
				$index_files_names=array(
					'php'=>$sub.'/index.php',
					'html'=>$sub.'/index.html',
					'htm'=>$sub.'/index.htm'
				);
        if((is_dir($sub))&&($folder != ".")&&($folder != "..")){
					$index_files = array_filter($index_files_names, 'file_exists');
					if(!$index_file = array_shift($index_files)) { // If folder does not contain any index files - create new one
							// You could specify name and extension of your "dummy" index file, for example: $index_file=$index_files_names['html'];
							$index_file=$index_files_names['php'];
							// Create a file
							$fh=fopen($index_file, 'a');
							// 'Dummy' File Content
							$fileData = "<?php // Silence is golden";
							fwrite($fh, $fileData);
							fclose($fh);		
						
							echo 'File created: '.$index_file.'<br />';
					}else{
						echo 'Index file  already exists in directory: '.$sub.' <br />';
					}
					
					protect_folder($sub,$files); 
				}
		}	
	}
}

/**
*----------------------------------------- 
* Form Handler 
*-----------------------------------------
*/
if(isset($_POST['is_submit'])&&($_POST['is_submit']==1)){
	// Call function "protect_folder" and pass directory name in which current script (protect.php) is located
	protect_folder(dirname(__FILE__));

}

?>


<h2>Protect sub-directories</h2>
<form method="post">
<input type="hidden" name="is_submit" value="1" />
<input type="submit" value="Protect" />
</form>
<hr />
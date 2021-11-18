<?php


function scanD($target, $obj) {

	$excludeFiles = array("resources/mapcache.db", "update.json", "version.json");
	$clientExcludeFiles = array("Intersect Editor.exe", "Intersect Editor.pdb");
	$excludeDirectories = array("logs", "screenshots");
	$excludeExtensions = array(".xml", ".config", ".php");

	if(is_dir($target)){

		$skipDir = false;
		$dir = str_replace(getcwd() . "/", "", $target);
		foreach ($excludeDirectories as $excludeDir) {
			if (endsWith($dir, $excludeDir . "/")) {
				$skipDir = true;
				break;
			}
		}

		if ($skipDir == false) {
			$files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned

			foreach( $files as $file )
			{
				scanD( $file, $obj );

				$skip = is_dir($file);

				foreach ($excludeExtensions as $extension) {
					if (endsWith($file, $extension)) {
						$skip = true;
						break;
					}
				}

				$path = str_replace(getcwd() . "/", "", $file);

				if (in_array($path, $excludeFiles)) {
					$skip = true;	
				}

				if ($skip == false) {
					if (in_array($path,$clientExcludeFiles)) {
						$obj->Files[] = array("Path"=>$path, "Hash"=>md5_file($file), "Size"=>filesize($file), "ClientIgnore"=>true);
					}
					else {
						$obj->Files[] = array("Path"=>$path, "Hash"=>md5_file($file), "Size"=>filesize($file));
					}
				}

			}
		}


	} 
}

function endsWith($haystack, $needle)
{
	$length = strlen($needle);
	if ($length == 0) {
		return true;
	}

	return (substr($haystack, -$length) === $needle);
}


$path = getcwd();
$obj->TrustCache = true;
if (file_exists("stream.php")) {
	$obj->StreamingUrl = (((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')) ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . "/stream.php";
}
$obj->Files = [];
scanD($path, $obj);
echo json_encode($obj, JSON_UNESCAPED_SLASHES);

?>
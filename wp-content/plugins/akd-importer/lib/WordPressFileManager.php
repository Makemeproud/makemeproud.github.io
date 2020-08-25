<?php
class WordPressFileManager
{
	public static function createWritableDirectory($paramToFolderWithoutEndSlash)
	{
		// Create an upload dir if not exist
		$newDirectoryExists = TRUE;

		if (!file_exists($paramToFolderWithoutEndSlash))
		{
			// The mkdir doesn't work in WordPress setup on regular servers (DirectAdmin+Ubuntu based etc.)
			//$uploadDirectoryExists = mkdir($paramToFolderWithoutEndSlash, 0777, TRUE);
			$newDirectoryExists = wp_mkdir_p($paramToFolderWithoutEndSlash);
		}

		// Check if folder iss writable
		$newDirectoryWritable = FALSE;
		if($newDirectoryExists)
		{
			if(is_writable($paramToFolderWithoutEndSlash) === FALSE)
			{
				chmod($paramToFolderWithoutEndSlash, 0777);
				if(is_writable($paramToFolderWithoutEndSlash))
				{
					$newDirectoryWritable = TRUE;
				}
			} else
			{
				$newDirectoryWritable = TRUE;
			}
		}

		return $newDirectoryWritable;
	}


	/**
	 * Because copy($moveFolderAndAllFilesInsideFrom, $paramToFolder) - does NOT work in this WordPress setup (because of CHMOD rights),
	 * so we need a workaround function - and this is the main reason why we have a function bellow, which DOES WORK!
	 * @param $moveFolderAndAllFilesInsideFrom
	 * @param $paramToFolderWithoutEndSlash
	 */
	public static function recurseCopy($moveFolderAndAllFilesInsideFrom, $paramToFolderWithoutEndSlash)
	{
		$sourceDirectory = opendir($moveFolderAndAllFilesInsideFrom);
		while (FALSE !== ( $file = readdir($sourceDirectory)) )
		{
			if (( $file != '.' ) && ( $file != '..' ))
			{
				if ( is_dir($moveFolderAndAllFilesInsideFrom.'/'.$file))
				{
					static::recurseCopy($moveFolderAndAllFilesInsideFrom.'/'.$file, $paramToFolderWithoutEndSlash.'/'.$file);
				} else
				{
					copy($moveFolderAndAllFilesInsideFrom.'/'.$file, $paramToFolderWithoutEndSlash.'/'.$file);
				}
			}
		}
		closedir($sourceDirectory);
	}

	/**
	 * Copy folder and all it's files from it's old location to new location
	 * @param $copyAllFilesFromFolderWithoutEndSlash
	 * @param $paramToFolderWithoutEndSlash
	 * @return bool
	 */
	public static function copyFolder($copyAllFilesFromFolderWithoutEndSlash, $paramToFolderWithoutEndSlash)
	{
		$copied = FALSE;
		if(file_exists($copyAllFilesFromFolderWithoutEndSlash))
		{
			$toDirectoryIsWritable = static::createWritableDirectory($paramToFolderWithoutEndSlash);
			if($toDirectoryIsWritable)
			{
				// NOTE: copy() does NOT work in this WordPress setup (because of CHMOD rights)
				//$copied = copy($moveFolderAndAllFilesInsideFrom, $paramToFolderWithoutEndSlash);
				static::recurseCopy($copyAllFilesFromFolderWithoutEndSlash, $paramToFolderWithoutEndSlash);
				$copied = TRUE;
			}
			// DEBUG
			//echo "<br />[{$copyAllFilesFromFolderWithoutEndSlash}] SOURCE FOLDER (TO MOVE FILES FROM IT) DO EXISTS, ";
			//echo "destination folder is writable: "; var_dump($toDirectoryIsWritable);
		} else
		{
			// DEBUG
			//echo "<br />[{$copyAllFilesFromFolderWithoutEndSlash}] SOURCE FOLDER (TO MOVE FILES FROM IT) DO NOT EXISTS";
		}

		return $copied;
	}

	public static function copyFile($ScrFolder, $DestinationFolder, $file)
	{
		$copied = FALSE;
		$sourceFile = $ScrFolder.'/'.$file;
		$desFile = $DestinationFolder.'/'.$file;

		if(file_exists($sourceFile))
		{
			$toDirectoryIsWritable = static::createWritableDirectory($DestinationFolder);
			if($toDirectoryIsWritable)
			{
				// NOTE: copy() does NOT work in this WordPress setup (because of CHMOD rights)
				$copied = copy($sourceFile, $desFile);
				$copied = TRUE;
			}
			// DEBUG
			//echo "<br />[{$copyAllFilesFromFolderWithoutEndSlash}] SOURCE FOLDER (TO MOVE FILES FROM IT) DO EXISTS, ";
			//echo "destination folder is writable: "; var_dump($toDirectoryIsWritable);
		} else
		{
			// DEBUG
			//echo "<br />[{$copyAllFilesFromFolderWithoutEndSlash}] SOURCE FOLDER (TO MOVE FILES FROM IT) DO NOT EXISTS";
		}

		return $copied;
	}
}
?>
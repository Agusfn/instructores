<?php

namespace App\Helpers;

use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class Images {


	/**
	 * Appends a string to the end of the name of a file image. 
	 * Eg: "photo.jpg", appending "graphy" returns "photography.jpg"
	 *
	 * @param string $fileName
	 * @param string $appendStr
	 * @return string
	 */
	public static function appendToImgName($fileName, $appendStr)
	{
		if(preg_match("/^(.*)(.(jpeg|jpg|png|bmp))$/", $fileName, $matches)) {
			return $matches[1].$appendStr.$matches[2];
		}
		else {
			throw new \Exception("Invalid file name/format.");
		}
		
	}


	/**
	 * Encodes and compresses an image of any format to jpg format, and optionally resizes it if width or height is larger than indicated
	 * This is used for file uploads from the client to mantain a standard format, and to avoid large image file sizes.
	 * 
	 * @param  Intervention\Image\Image $image
	 * @param  int $maxWidth
	 * @param  int $maxHeight
	 * @return Intervention\Image\Image
	 */
	public static function toJpgAndResize($image, $maxWidth, $maxHeight)
	{
		$image = $image->encode("jpg", 75);

		if($image->width() > $maxWidth) {
			$image->resize($maxWidth, null, function ($constraint) {
			    $constraint->aspectRatio();
			});
		}

		if($image->height() > $maxHeight) {
			$image->resize(null, $maxHeight, function ($constraint) {
			    $constraint->aspectRatio();
			});
		}

		return $image;
	}


	/**
	 * Saves an Intervention Image with a random number name, saving on Storage disk dir.
	 * 
	 * @param string $disk laravel disk
	 * @param  string $dir 	storage dir
	 * @param  Intervention\Image\Image $image
	 * @return string 	file storage path
	 */
	public static function saveImgWithRandName($disk, $dir, $image)
	{
		$path = $dir."/".rand().self::imgExtFromMime($image->mime());
		Storage::disk($disk)->put($path, $image->stream());
		return $path;
	}



	/**
	 * Get image file extension from MIME type
	 * @param  string $mime
	 * @return string
	 */
	public static function imgExtFromMime($mime)
	{
		if($mime == "image/jpeg")
			return ".jpg";
		else if($mime == "image/png")
			return ".png";
		else if($mime == "image/bmp")
			return ".bmp";
		else if($mime == "image/gif")
			return ".gif";
	}

}
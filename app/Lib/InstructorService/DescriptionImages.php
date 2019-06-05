<?php
namespace App\Lib\InstructorService;

use App\Lib\Helpers\Images;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

trait DescriptionImages
{



	/**
	 * Saves an image and thumbnail in storage and saves the file names in the entity's pictures property
	 * 
	 * @param \Illuminate\Http\File|\Illuminate\Http\UploadedFile $file
	 * @return array 	img file names
	 */
	public function addImage($file)
	{
		$savePath = "img/service/".$this->number;
		$fileName = rand().".jpg";
		$thumbnailFileName = Images::appendToImgName($fileName, "-thumbnail");

        $image = Images::toJpgAndResize(Image::make($file), 1920, 1080);
        $thumbnail = clone $image;
        $thumbnail->fit(200);

		Storage::put($savePath."/".$fileName, $image->stream());
		Storage::put($savePath."/".$thumbnailFileName, $thumbnail->stream());

		$this->addImageToJson($fileName, $thumbnailFileName);

		return [
			"name" => $fileName,
			"thumbnail_name" => $thumbnailFileName
		];
	}



	/**
	 * Remove image (and thumbnail) from disk and from image json property
	 * @param  string $fileName
	 * @return null
	 */
	public function removeImage($fileName)
	{
		$savePath = "img/service/".$this->number;

		Storage::delete([
			$savePath."/".$fileName, 
			$savePath."/".Images::appendToImgName($fileName, "-thumbnail")
		]);

		$this->removeImageFromJson($fileName);
	}





	/**
	 * Adds an image to the instructor service
	 * They are stored in default driver (public): storage/app/public/img/service/<number>/ (accessed with symlink)
	 * 
	 * @param string $fileName
	 * 
	 */
	public function addImageToJson($fileName, $thumbnailFileName)
	{

		$image = [
			"name" => $fileName,
			"thumbnail_name" => $thumbnailFileName
		];

		if(!$this->images_json) {
			$this->images_json = json_encode([$image]);
		} 
		else {
			$images = $this->images();
			$images[] = $image;
			$this->images_json = json_encode($images);
		}
		$this->save();
	}




	/**
	 * Remove an image from the images json property of this instructor service
	 * @param  string $fileName
	 * @return null
	 */
	public function removeImageFromJson($fileName)
	{
		$images = $this->images();

		if($images == null)
			return;

		for($i=0; $i<sizeof($images); $i++) 
		{
			if($images[$i]["name"] == $fileName) {
				unset($images[$i]);
				$images = array_values($images);
			}
		}

		if(sizeof($images) == 0)
			$this->images_json = null;
		else
			$this->images_json = json_encode($images);

		$this->save();
	}


	/**
	 * Returns an array of the service images URLs, both fullsized and thumbnails.
	 * @return array
	 */
	public function imageUrls()
	{
		$urls = [];
		if($this->images() != null)
		{
			foreach($this->images() as $image) {
				$urls[] = [
					"fullsize" => Storage::url("img/service/".$this->number."/".$image["name"]),
					"thumbnail" => Storage::url("img/service/".$this->number."/".$image["thumbnail_name"])
				];
			}
		}
		return $urls;
	}



	/**
	 * Check whether the instructor service has certain image by file name
	 * @param  string  $imageName
	 * @return boolean
	 */
	public function hasImage($fileName)
	{
		$images = $this->images();
		foreach($images as $image)
		{
			if($image["name"] == $fileName)
				return true;
		}
		return false;
	}





}
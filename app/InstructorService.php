<?php

namespace App;

use App\Helpers\Dates;
use App\Helpers\Images;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class InstructorService extends Model
{


    /**
     * 
     *
     * @var array
     */
	protected $fillable = ["title", "description", "features", "work_hours"];



	/**
	 * Generates an unique InstructorService number.
	 * @return int
	 */
	public static function generateNumber()
	{
		$number = rand(1000000, 9999999);
		if(self::where("number", $number)->count() == 0)
			return $number;
		else
			return self::generateNumber();
	}


	/**
	 * Find InstructorService by number
	 * @param  int $number
	 * @return null|InstructorService
	 */
	public static function findByNumber($number)
	{
		return self::where("number", $number)->first();
	}




	/**
	 * Get the Instructor who provides this service.
	 * @return App\Instructor
	 */
	public function instructor()
	{
		return $this->belongsTo("App\Instructor");
	}


	/**
	 * Get the date ranges associated with this service.
	 * @return App\ServiceDateRange[]
	 */
	public function dateRanges()
	{
		return $this->hasMany("App\ServiceDateRange");
	}


	/**
	 * Check if a certain date range doesn't have any of its dates included in another range, for a certain service.
	 * Start and end dates must belong to the same year.
	 * 
	 * @param  string  $date_start Y-m-d
	 * @param  string  $date_end   Y-m-d
	 * @return boolean
	 */
	public function hasRangeAvailable($dateStart, $dateEnd)
	{
		
		if(Dates::getYear($dateStart) != Dates::getYear($dateEnd)) {
			throw new \Exception("Start and end date must belong to the same year.");
		}

		$queryDates = Dates::dateRangeToArray($dateStart, $dateEnd);

		
		$offeredDateRanges = $this->dateRanges()->whereYear("date_start", Dates::getYear($dateStart))->get();

		foreach($offeredDateRanges as $offeredDateRange) {

			$offeredDates = Dates::dateRangeToArray($offeredDateRange->date_start, $offeredDateRange->date_end);

			if(count(array_intersect($queryDates, $offeredDates)) > 0)
				return false;

		}

		return true;
	}



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
	 * Get instructor service images as array of assoc array with "img" and "thumbnail"
	 * @return string
	 */
	public function images()
	{
		return json_decode($this->images_json, true);
	}



	public function imageUrls()
	{
		$urls = [];
		foreach($this->images() as $image) {
			$urls[] = Storage::url("img/service/".$this->number."/".$image["name"]);
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


	/**
	 * [hasSplitWorkHours description]
	 * @return boolean [description]
	 */
	public function hasSplitWorkHours()
	{
		$hours = explode(",", $this->work_hours);
		if(sizeof($hours) == 4)
			return true;

		return false;
	}



	/**
	 * [featuresArray description]
	 * @return [type] [description]
	 */
	public function featuresArray()
	{
		return preg_split("/\r\n|\n|\r/", $this->features);
	}


}

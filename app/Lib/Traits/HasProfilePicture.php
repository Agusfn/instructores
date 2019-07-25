<?php

namespace App\Lib\Traits;

use App\User;
use App\Instructor;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

trait HasProfilePicture
{



	/**
	 * Returns the path where the images are stored, in the public 
	 * @return string
	 */
	private function profilePicPath()
	{
		if($this instanceof User) {
			return "img/users/";
		} else {
			return "img/instructors/";
		}
	}



    /**
     * Downloads and sets the profile picture from an external image url.
     * @param string $imgUrl
     * @return boolean success
     */
    public function setProfilePicFromImgUrl($imgUrl)
    {
        try {
            $image = Image::make($imgUrl);
        }
        catch(\Exception $e) {
            \Log::notice("Could not get profile picture from url ".$imgUrl.". Error message: ".$e->getMessage());
            return false;
        }

        $this->setProfilePic($image);
    }


	/**
	 * Set a profile pic from an intervention image object.
	 * @param Intervention\Image\ImageManagerStatic $file
	 */
	public function setProfilePic($image)
	{
        try
        {
            if($image->mime() != "image/jpeg") {
                $image = $image->encode("jpg", 75);
            }

            $fileName = $this->generateFileName();

            $this->deleteCurrentProfilePic();

            Storage::put($this->profilePicPath().$fileName, $image->stream());

            $this->profile_picture = $fileName;
            $this->save();
        }
        catch(\Exception $e) {
            \Log::notice("Could not save profile picture. Error message: ".$e->getMessage());
        }
	}


	/**
	 * Generates a random file name for the profile picture JPG image.
	 * @return string
	 */
	private function generateFileName()
	{
		return strtolower(\Str::random(5)).uniqid(true).".jpg";
	}


	/**
	 * Deletes from disk the current profile pic if it exists.
	 * @return null
	 */
	public function deleteCurrentProfilePic()
	{
		if($this->profile_picture) {
			Storage::delete($this->profilePicPath().$this->profile_picture);
		}
	}


    /**
     * Obtain the URL of the profile pic of the user.
     * @return string
     */
    public function getProfilePicUrl()
    {
        if($this->profile_picture)
            return Storage::url($this->profilePicPath().$this->profile_picture);
        else
            return asset("resources/img/avatar.jpg");
    }




}

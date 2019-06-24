<?php

namespace App\Lib;

use App\User;
use App\Instructor;
use App\Lib\Helpers\Strings;

class SocialLogin
{
		

	/**
	 * Create a user from socialite user response.
	 * @param  Socialite\One\User $socialUser
	 * @param  string $provider      social login provider
	 * @return App\User
	 */
	public static function createUser($socialUser, $provider)
	{
		$user = User::create([
            "name" => isset($socialUser->user["given_name"]) ? $socialUser->user["given_name"] : Strings::getFirstName($socialUser->name),
            "surname" => isset($socialUser->user["family_name"]) ? $socialUser->user["family_name"] : Strings::getLastName($socialUser->name),
			"email" => $socialUser->email,
			"provider" => $provider,
			"provider_id" => $socialUser->id,
		]);

		$user->setProfilePicFromImgUrl(str_replace("type=normal", "type=large", $socialUser->avatar));

		return $user;
	}


	/**
	 * Create a user from socialite user response.
	 * @param  Socialite\One\User $socialUser
	 * @param  string $provider      social login provider
	 * @return App\Instructor
	 */
	public static function createInstructor($socialUser, $provider)
	{
		$instructor = Instructor::create([
			"name" => isset($socialUser->user["given_name"]) ? $socialUser->user["given_name"] : Strings::getFirstName($socialUser->name),
			"surname" => isset($socialUser->user["family_name"]) ? $socialUser->user["family_name"] : Strings::getLastName($socialUser->name),
			"email" => $socialUser->email,
			"provider" => $provider,
			"provider_id" => $socialUser->id,
			"approved" => false,
		]);

		$instructor->setProfilePicFromImgUrl(str_replace("type=normal", "type=large", $socialUser->avatar));

		return $instructor;
	}


}
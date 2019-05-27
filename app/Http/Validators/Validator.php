<?php 
namespace App\Http\Validators;

use Illuminate\Support\MessageBag;

abstract class Validator 
{

    protected $request;
    public $messages;
    public static $rules;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function fails()
    {
        $validation = \Validator::make($this->request->all(), static::$rules);

        if ($validation->fails()) {
            $this->messages = $validation->messages();
            return true;
        }
        else {
            $this->messages = new MessageBag();
        }

        return false;
    }

    public function messages()
    {
        return $this->messages;
    }
}
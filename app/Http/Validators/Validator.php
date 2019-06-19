<?php 
namespace App\Http\Validators;

use Illuminate\Support\MessageBag;
use Illuminate\Contracts\Support\MessageProvider;

abstract class Validator implements MessageProvider
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
        $validator = \Validator::make($this->request->all(), static::$rules);

        if ($validator->fails()) {
            $this->messages = $validator->messages();
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


    /**
     * Get the messages for the instance.
     *
     * @return \Illuminate\Support\MessageBag
     */
    public function getMessageBag()
    {
        return $this->messages();
    }


}
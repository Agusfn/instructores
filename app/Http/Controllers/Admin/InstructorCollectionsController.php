<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\InstructorCollection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Filters\InstructorCollectionFilters;
use \App\Mail\Instructor\Collections\CollectionConfirmed;
use \App\Mail\Instructor\Collections\CollectionRejected;


class InstructorCollectionsController extends Controller
{
    
	public function __construct()
	{
		$this->middleware('auth:admin');
	}


    /**
     * Show list of all the instructor collections.
     * @return [type] [description]
     */
	public function list(InstructorCollectionFilters $filters)
	{
		$collections = InstructorCollection::filter($filters)->paginate(15);

		return view("admin.instructor-collections.list")->with("collections", $collections);

	}


	/**
	 * Confirm the collection for the instructor, and deduct it from their balance.
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function confirmCollection(Request $request)
	{
		$request->validate([
			"collection_id" => "required|integer"
		]);

		$collection = InstructorCollection::find($request->collection_id);

		if(!$collection || !$collection->isPending())
			return redirect()->back();


		$movement = $collection->instructorWallet->deductInstructorCollection($collection->id, $collection->amount);

		$collection->status = InstructorCollection::STATUS_COMPLETED;
		$collection->wallet_movement_id = $movement->id;
		$collection->save();

		Mail::to($collection->instructorWallet->instructor)->send(new CollectionConfirmed($collection->instructorWallet->instructor, $collection));

		return redirect()->back();
	}



	/**
	 * Reject the collection
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function rejectCollection(Request $request)
	{
		$request->validate([
			"collection_id" => "required|integer",

		]);

		$collection = InstructorCollection::find($request->collection_id);

		if(!$collection || !$collection->isPending())
			return redirect()->back();

		$collection->status = InstructorCollection::STATUS_REJECTED;
		$collection->reject_reason = $request->reason;
		$collection->save();

		Mail::to($collection->instructorWallet->instructor)->send(new CollectionRejected($collection->instructorWallet->instructor, $collection));

		return redirect()->back();
	}


}

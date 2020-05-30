<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class Profile extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

	/**
     * Update the photo
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	public function updateAvatar(Request $request) {
		Validator::make($request->all(), [
			'avatar' => ['required', 'image'],
		])->validate();
		$user = $request->user();
		//$result["user"] = Auth::user();
        $result['avatar'] = $request->file('avatar')->store('public');
        //$result['avatar'] = Storage::url($result['avatar']);
        //Storage::setVisibility($result['avatar'], 'public');
		if ($result['avatar']) {
			if ($user->avatar) Storage::delete($user->avatar);
			$user->avatar = $result['avatar'];
			$user->save();
		}
		return response()->json($result);
    }

    public function updateData (Request $request) {
        Validator::make(
			$request->all()
		, [
			'name' => array_merge(User::NAME_BASIC_VALIDATE_RULES),
			#'phone' => array_merge( User::PHONE_BASIC_VALIDATE_RULES),
			'direccion' => array_merge(User::DIRECCION_BASIC_VALIDATE_RULES),
			#'email' => array_merge(User::EMAIL_BASIC_VALIDATE_RULES, ['unique:users']),
			'pais' => User::PAIS_BASIC_VALIDATE_RULES
        ])->validate();
        
        $result = [];
        $user = $request->user();
        if ($request->filled("name")) {
            $user->name = $request->name;
            $user->save();
        }

        $result["status"] = "success";

        #$result["data"] = $request->all();
        #$result["user"] = $user;

        return response()->json($result);
    }
}

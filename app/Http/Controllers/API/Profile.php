<?php

namespace App\Http\Controllers\API;

use App\Driver;
use App\Http\Controllers\Controller;
use App\User;
use App\Place;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Kreait\Firebase\Database;

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
        $result['avatar'] = $request->file('avatar')->store('avatares');
        //$result['avatar'] = Storage::putFile('avatares', $request->file('avatar'));
        //$result['avatar'] = Storage::url($result['avatar']);
        Storage::setVisibility($result['avatar'], 'public');
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

	public function places(Request $request) {
		$result = [];
		$user = $request->user();
		$places = $user->places()->whereIn("nombre", ["Casa", "Trabajo", "Otro"])->get();

		$result["places"] = $places;

		return response()->json($result);
	}

	public function userPlace(Request $request) {
		Validator::make($request->all(), [
			"lugar" => ["array"],
			"lugar.nombre" => [Rule::requiredIf($request->filled('lugar')), "string"],
			"lugar.direccion" => [Rule::requiredIf($request->filled('lugar')), "string"],
			"lugar.identificador" => [Rule::requiredIf($request->filled('lugar')), "string"],
			"lugar.region.latitude" => [Rule::requiredIf($request->filled('lugar')), "numeric"],
			"lugar.region.latitudeDelta" => [Rule::requiredIf($request->filled('lugar')), "numeric"],
			"lugar.region.longitude" => [Rule::requiredIf($request->filled('lugar')), "numeric"],
			"lugar.region.longitudeDelta" => [Rule::requiredIf($request->filled('lugar')), "numeric"],
			'nombreLugar' => [Rule::requiredIf(!$request->filled('lugar')), 'string'],
			'landmark' => ['string'],
			'fullname' => ['string'],
			'phone' => ['digits_between:0,9'],
		])->validate();
	
		$user = $request->user();

		/*if (!(isset($data["lugar"]["id"]) && $lugar = Place::find($data["lugar"]["id"]))) {
			$lugar = new Place;
		}*/
		$result = [];
		#$result['user'] = $user;
		//$user->places()->detach();
		$columnas = [];
		$place = null;
		if (isset($request->lugar)) {
			if (isset($request->lugar["id"])) {
				$place = $user->places()->where("place_id", $request["lugar"]["id"])->first();
				if (!$place) {
					$place = Place::find($request->lugar["id"]);
					if ($place) $user->places()->attach($place->id);
				}
			} else {
				$lastPlace = $user->places()->where("nombre", $request->lugar["nombre"])->first();
				if ($lastPlace) $user->places()->detach($lastPlace->id);
			}

			if ($place) {
				//$user->places()->where("place_id", $data["lugar"]["id"])->first();
				//$place = Place::find($request->lugar["id"]);
				$place->updateData($request->lugar);
				$place->save();
				$atributos["landmark"] = isset($request->landmark) ? $request->landmark : null ;
				$atributos["fullname"] = isset($request->fullname) ? $request->fullname : null ;
				$atributos["phone"] = isset($request->phone) ? $request->phone : null ;
				$user->places()->updateExistingPivot($place, $atributos);
			} else {
				$place = new Place;
				$place->updateData($request->lugar);
				if (isset($request->landmark)) $columnas["landmark"] = $request->landmark;
				if (isset($request->fullname)) $columnas["fullname"] = $request->fullname;
				if (isset($request->phone)) $columnas["phone"] = $request->phone;
				$user->places()->save($place, $columnas);
			}
			$place = $user->places()->where("nombre", $request->lugar["nombre"])->first();
		} else {
			$place = $user->places()->where("nombre", $request->nombreLugar)->first();
			if ($place) $user->places()->detach($place->id);
			$place = $user->places()->where("nombre", $request->nombreLugar)->first();
		}
		if (true) $result["savedPlaceModel"] = $place;
		$result["status"] = "success";
		return response()->json($result);
	}

	public function userToDriverForm(Request $request) {
		Validator::make($request->all(), [
			'dni' => array_merge(['required'], Driver::DNI_BASIC_VALIDATE_RULES, ['unique:drivers'])
		])->validate();
		$result = [];
		if (!$request->user()->driver) {
			Driver::create([
				'dni' => $request->dni,
				'id' => $request->user()->id
			]);
		}
		/**
		 * @var Database
		 */
		$db = app('firebase.database');
		$userRef = $db->getReference('users/'.$request->user()->uid);
		$userRef->update(["driver"=>true, "driverActive"=>true]);
		$result["status"] = 'success';
		return response()->json($result);
	}

	public function driver(Request $request) {
		$user = $request->user();
		$user->load("driver");
	}

	public function saveLocation(Request $request) {
		Validator::make($request->all(), [
			"location"=>["required"]
		])->validate();
		$driver = $request->user()->driver;
		$driver->location = $request->location;
		$driver->save();
		$result = [];
		$result["driver"] = $driver;
		return response()->json($result);
	}
}

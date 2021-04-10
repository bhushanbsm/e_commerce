<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MasterController extends Controller {

    public function countries() {
        $countries = Country::get();
        return response(
            [
                "code" => "200",
                "status" => "success",
                "data" => $countries,
            ],
            200
        );
    }

    public function states($id) {
        $states = State::where('country_id', $id)->get();
        return response(
            [
                "code" => "200",
                "status" => "success",
                "data" => $states,
            ],
            200
        );
    }    

    public function cities($id) {
        $data = City::where('state_id', $id)->get();
        return response(
            [
                "code" => "200",
                "status" => "success",
                "data" => $data,
            ],
            200
        );
    }

}

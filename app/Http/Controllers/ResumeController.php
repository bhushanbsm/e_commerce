<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddResumeRequest;
use App\Models\Country;
use App\Models\Resume;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ResumeController extends Controller {
    /**
     * Loads resume form
     */
    public function index() {
        $countries = Country::get();
        return view('pages.resume', compact('countries'));
    }

    /**
     * Stores new product in data
     * @param  AddResumeRequest $request
     * @param  int               country_id   Country id of countries table
     * @param  int               state_id     State id of states table
     * @param  int               city_id      City id of cities table
     * @param  string            first_name   first name of user
     * @param  string            last_name    last name of user
     * @param  string            about
     * @param  string            username     unique username
     * @param  string            email        unique email
     * @param  string            address_line address line
     * @param  int               zipcode
     * @param  file              document[]   resume pdf
     * @return json
     */
    public function store(AddResumeRequest $request) {
        $data['user_id'] = auth()->user()->id;
        $data['country_id'] = $request->country_id;
        $data['state_id'] = $request->state_id;
        $data['city_id'] = $request->city_id;
        $data['first_name'] = $request->first_name;
        $data['last_name'] = $request->last_name;
        $data['about'] = $request->about;
        $data['username'] = $request->username;
        $data['email'] = $request->email;
        $data['address_line'] = $request->address_line;
        $data['zipcode'] = $request->zipcode;
        $data['path'] = "";

        // start transaction
        DB::beginTransaction();
        try {
            if ($request->hasfile('document')) {
                $name = time() . rand(1, 100) . '.' . $request->file('document')->getClientOriginalExtension();
                $request->file('document')->move(public_path('files'), $name);
                $data['path'] = $name;
            }

            $resume = Resume::create($data);

            if (!$resume) {
                DB::rollBack();
                return response(
                    [
                        "code" => "400",
                        "status" => "failed",
                        "msg" => 'unable to upload resume',
                    ],
                    400
                );
            }

            DB::commit();
            return response(
                [
                    "code" => "200",
                    "status" => "success",
                    "data" => $resume,
                ],
                200
            );
        } catch (Exceprion $e) {
            DB::rollBack();
            return response(
                [
                    "code" => "400",
                    "status" => "failed",
                    "msg" => 'unable to upload resume',
                ],
                400
            );
        }
    }

}

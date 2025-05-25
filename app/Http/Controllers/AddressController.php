<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\City;
use App\Models\State;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AddressController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        $address = new Address;
        if ($request->has('customer_id')) {
            $address->user_id   = $request->customer_id;
        } else {
            $address->user_id   = Auth::user()->id;
        }
        $address->address       = $request->address;
        $address->country_id    = $request->country_id;
        $address->state_id      = $request->state_id;
        $address->city_id       = $request->city_id;
        $address->longitude     = $request->longitude;
        $address->latitude      = $request->latitude;
        $address->postal_code   = $request->postal_code;
        $address->phone         = $request->phone;
        $address->save();

        return back();
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

    public function newAddressstore(Request $request)
    {
        $address = new Address;
        if ($request->has('customer_id')) {
            $address->user_id   = $request->customer_id;
        } else {
            $address->user_id   = Auth::user()->id;
        }
        $address->name       = $request->newname;
        $address->email       = $request->newemail;
        $address->address       = $request->newaddress;
        $address->city_id       = $request->city_id;
        $address->zone_id      = $request->zone_id;
        $address->area_id    = $request->area_id;
        $address->longitude     = $request->longitude;
        $address->latitude      = $request->latitude;
        $address->postal_code   = $request->postal_code;
        $address->phone         = $request->newphone;
        $address->save();

        flash(translate('Address Added'))->success();
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['address_data'] = Address::findOrFail($id);
        $data['states'] = State::where('status', 1)->where('country_id', $data['address_data']->country_id)->get();
        $data['cities'] = City::where('status', 1)->where('state_id', $data['address_data']->state_id)->get();

        $returnHTML = view('frontend.partials.address_edit_modal', $data)->render();
        return response()->json(array('data' => $data, 'html' => $returnHTML));
        //        return ;
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
        $address = Address::findOrFail($id);

        $address->name       = $request->newname;
        $address->email       = $request->newemail;
        $address->address       = $request->address;
        $address->city_id       = $request->city_id;
        $address->zone_id      = $request->zone_id;
        $address->area_id    = $request->area_id;
        $address->longitude     = $request->longitude;
        $address->latitude      = $request->latitude;
        $address->postal_code   = $request->postal_code;
        $address->phone         = $request->newphone;

        $address->save();

        flash(translate('Address info updated successfully'))->success();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $address = Address::findOrFail($id);
        if (!$address->set_default) {
            $address->delete();
            return back();
        }
        flash(translate('Default address can not be deleted'))->warning();
        return back();
    }

    public function getStates(Request $request)
    {

        $states = pathao_area($request->zone_id);
        // $states = State::where('status', 1)->where('country_id', $request->country_id)->get();
        $html = '<option value="">' . translate("Select Area") . '</option>';

        foreach ($states as $state) {
            $html .= '<option value="' . $state->area_id . '">' . $state->area_name . '</option>';
        }

        echo json_encode($html);
    }


    public function getCities(Request $request)
    {
        // $cities = City::where('status', 1)->where('state_id', $request->state_id)->get();
        $addressData = Session::get('addressData');
        $address = Address::where('id', $addressData)->first();
        if($address){
            $cities = pathao_zone($address->city_id);
        }else{
            $cities = pathao_zone($request->city_id); 
        }
        $html = '<option value="">' . translate("--Select--") . '</option>';

        foreach ($cities as $row) {
            $html .= '<option value="' . $row->zone_id . '">' . $row->zone_name . '</option>';
        }

        echo json_encode($html);
    }


    public function set_default($id)
    {
        foreach (Auth::user()->addresses as $key => $address) {
            $address->set_default = 0;
            $address->save();
        }
        $address = Address::findOrFail($id);
        $address->set_default = 1;
        $address->save();

        return back();
    }
}

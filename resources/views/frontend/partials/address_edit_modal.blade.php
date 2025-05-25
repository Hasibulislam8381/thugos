<form class="form-default" role="form" action="{{ route('addresses.update', $address_data->id) }}" method="POST">
    @csrf
    @php
    
        $address = App\Models\Address::where('id', $address_data->id)->first();
        $userAddress = App\Models\Address::where('user_id', auth()->user()->id)->first();
        $userAddress_all = App\Models\Address::where('user_id', auth()->user()->id)->get();

        if ($address) {
            $pathao_zone = pathao_zone($address->city_id);
            $pathao_area = pathao_area($address->zone_id);
        }

    @endphp
    <div class="row gutters-5">
        <div class="col-lg-12 shipping_addess">
            <div class="form-group">
                <label for="">Full Name</label>
                <input type="text" name="newname" class="form-control" id="" placeholder="Enter Name*"
                    value="{{ @$address->name ? @$address->name : (@$userAddress ? @$userAddress->name : auth()->user()->name) }}"
                    required>
            </div>
            <div class="form-group mt-3">
                <label for="">Phone Number <span class="text-danger">*</span></label>
                <input type="text" name="newphone" class="form-control" pattern="\d{11}" id=""
                    placeholder="Ex:012xxxxxxxx" required
                    value="{{ @$address->phone ? @$address->phone : (@$userAddress ? @$userAddress->phone : auth()->user()->phone) }}">
                <small class="text-muted">Please enter an 11-digit phone number.</small>
            </div>
            <div class="form-group mt-3">
                <label for="">E-Mail</label>
                <input type="text" name="newemail" class="form-control" id="" placeholder="Enter Email"
                    value="{{ @$address->email ? @$address->email : (@$userAddress ? @$userAddress->email : auth()->user()->email) }}">
            </div>

            {{-- <div class="form-group mt-3">
                <label for="">City</label>
                <input type="text" name="newcity" class="form-control" id=""
                    placeholder="City">
            </div> --}}
            <div class="form-group mt-3">
                <div class="">
                    <label>{{ translate('City') }}</label>
                </div>
                <div class="">

                    <select class="form-control mb-3" data-live-search="true" name="city_id" required>
                        <option value="">--Select--</option>
                        @foreach (pathao_city() as $row)
                            <option value="{{ $row->city_id }}">{{ $row->city_name }}</option>
                            @if ($address)
                                <option value="{{ $row->city_id }}"
                                    {{ $row->city_id == @$userAddress->city_id ? 'selected' : '' }}>
                                    {{ $row->city_name }}</option>
                            @endif
                        @endforeach

                    </select>
                </div>
            </div>
            <div class="form-group mt-3">
                <div class="">
                    <label>{{ translate('Zone') }}</label>
                </div>
                <div class="">
                    <select class="form-control mb-3" data-live-search="true" name="zone_id" required>
                        @if ($address)
                            @foreach ($pathao_zone as $row)
                                <option value="{{ $row->zone_id }}"
                                    {{ $row->zone_id == @$address->zone_id ? 'selected' : '' }}>
                                    {{ $row->zone_name }}</option>
                            @endforeach
                        @endif

                    </select>
                </div>
            </div>
            <div class="form-group mt-3">
                <div class="">
                    <label>{{ translate('Area') }}</label>
                </div>
                <div class="">
                    <select class="form-control mb-3" data-live-search="true" name="area_id" required>
                        @if ($address)
                            @foreach ($pathao_area as $row)
                                <option value="{{ $row->area_id }}"
                                    {{ $row->area_id == @$address->area_id ? 'selected' : '' }}>
                                    {{ $row->area_name }}</option>
                            @endforeach
                        @endif

                    </select>
                </div>
            </div>
            <div class="form-group mt-3">
                <label for="">Address</label>
                <textarea name="address" id="" class="form-control" placeholder="Enter Address" rows="3">{{ @$address->address ?? @$userAddress->address }}</textarea>
            </div>
        </div>
        <input type="hidden" name="checkout_type" value="logged">
        <div class="m-auto">
            <div class="mt-2">
                <button type="submit"
                    class="m-auto fw-600 fs-16 mr-0 btn btn-secondary btn-sm shadow-md mt-4 view_all_btn"
                    style="border: none">{{ translate('Update') }}</button>
            </div>

        </div>

    </div>
</form>

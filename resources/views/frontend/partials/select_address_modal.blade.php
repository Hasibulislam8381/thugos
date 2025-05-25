<div class="modal fade" id="select-address-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ translate('Add Address') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @foreach (Auth::user()->addresses as $key => $address)
                <div class="col-md-12 mb-3">
                    @php
                        $pathao_cities = session()->get('pathao_cities');
                        $filtered_city = null;
                        foreach ($pathao_cities as $city) {
                            if ($city->city_id == $address->city_id) {
                                $filtered_city = $city;
                                break;
                            }
                        }
                    @endphp


                    <label class="aiz-megabox d-block bg-white mb-0">
                        <input type="radio" name="address_id" value="{{ $address->id }}"
                            @if ($address->set_default) checked @endif required
                            onclick="addressData('{{ $address->id }}')">
                        <span class="d-flex p-3 aiz-megabox-elem" style="border:none">
                            <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                            <span class="flex-grow-1 pl-3 text-left">
                                <div>
                                    <span class="opacity-60">{{ translate('Name') }}:</span>
                                    <span class="fw-600 ml-2">{{ $address->name }}</span>
                                </div>
                                <div>
                                    <span class="opacity-60">{{ translate('Phone') }}:</span>
                                    <span class="fw-600 ml-2">{{ @$address->phone }}</span>
                                </div>
                                <div>
                                    <span class="opacity-60">{{ translate('Email') }}:</span>
                                    <span class="fw-600 ml-2">{{ @$address->email }}</span>
                                </div>


                                <div>
                                    <span class="opacity-60">{{ translate('Address') }}:</span>
                                    <span class="fw-600 ml-2">{{ $address->address }}</span>
                                </div>



                            </span>
                        </span>
                    </label>

                    <div class="dropdown position-absolute right-0 top-0">
                        <button class="btn bg-gray px-2" type="button" data-toggle="dropdown">
                            <i class="la la-ellipsis-v"></i>
                        </button>
                        {{-- onclick="edit_address('{{ $address->id }}')" --}}
                        <div class="dropdown-menu dropdown-menu-right select_address_dropdown"
                            aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" onclick="edit_address('{{ $address->id }}')"
                                style="cursor: pointer">
                                {{ translate('Edit') }}
                            </a>
                            <a class="dropdown-item" href="{{ route('addresses.destroy', $address->id) }}">
                                {{ translate('Delete') }}
                            </a>
                        </div>
                    </div>

                </div>
            @endforeach
            <div class="form-group text-center">
                <button type="submit" class="btn btn-sm btn-primary"
                    onclick="saveAddressData()">{{ translate('Save') }}</button>
            </div>
            {{-- <form class="form-default" role="form" action="{{ route('addresses.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="p-3">
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ translate('Address') }}</label>
                            </div>
                            <div class="col-md-10">
                                <textarea class="form-control mb-3" placeholder="{{ translate('Your Address') }}" rows="2" name="address"
                                    required></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ translate('Country') }}</label>
                            </div>
                            <div class="col-md-10">
                                <div class="mb-3">
                                    <select class="form-control aiz-selectpicker" data-live-search="true"
                                        data-placeholder="{{ translate('Select your country') }}" name="country_id"
                                        required>
                                        <option value="">{{ translate('Select your country') }}</option>
                                        @foreach (\App\Models\Country::where('status', 1)->get() as $key => $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ translate('State') }}</label>
                            </div>
                            <div class="col-md-10">
                                <select class="form-control mb-3 aiz-selectpicker" data-live-search="true"
                                    name="state_id" required>

                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ translate('City') }}</label>
                            </div>
                            <div class="col-md-10">
                                <select class="form-control mb-3 aiz-selectpicker" data-live-search="true"
                                    name="city_id" required>

                                </select>
                            </div>
                        </div>

                        @if (get_setting('google_map') == 1)
                            <div class="row">
                                <input id="searchInput" class="controls" type="text"
                                    placeholder="{{ translate('Enter a location') }}">
                                <div id="map"></div>
                                <ul id="geoData">
                                    <li style="display: none;">Full Address: <span id="location"></span></li>
                                    <li style="display: none;">Postal Code: <span id="postal_code"></span></li>
                                    <li style="display: none;">Country: <span id="country"></span></li>
                                    <li style="display: none;">Latitude: <span id="lat"></span></li>
                                    <li style="display: none;">Longitude: <span id="lon"></span></li>
                                </ul>
                            </div>

                            <div class="row">
                                <div class="col-md-2" id="">
                                    <label for="exampleInputuname">Longitude</label>
                                </div>
                                <div class="col-md-10" id="">
                                    <input type="text" class="form-control mb-3" id="longitude" name="longitude"
                                        readonly="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2" id="">
                                    <label for="exampleInputuname">Latitude</label>
                                </div>
                                <div class="col-md-10" id="">
                                    <input type="text" class="form-control mb-3" id="latitude" name="latitude"
                                        readonly="">
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ translate('Postal code') }}</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" class="form-control mb-3"
                                    placeholder="{{ translate('Your Postal Code') }}" name="postal_code"
                                    value="" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ translate('Phone') }}</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" class="form-control mb-3"
                                    placeholder="{{ translate('+880') }}" name="phone" value="" required>
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </div>
                </div>
            </form> --}}
        </div>
    </div>
</div>

<div class="modal fade" id="edit-address-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ translate('New Address') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="edit_modal_body">

            </div>
        </div>
    </div>
</div>

@section('script')
    <script type="text/javascript">
        function select_address() {
            $('#select-address-modal').modal('show');
        }

        function edit_address(address) {
            var url = '{{ route('addresses.edit', ':id') }}';
            url = url.replace(':id', address);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: 'GET',
                success: function(response) {
                    $('#edit_modal_body').html(response.html);
                    $('#edit-address-modal').modal('show');
                    AIZ.plugins.bootstrapSelect('refresh');

                    @if (get_setting('google_map') == 1)
                        var lat = -33.8688;
                        var long = 151.2195;

                        if (response.data.address_data.latitude && response.data.address_data.longitude) {
                            lat = response.data.address_data.latitude;
                            long = response.data.address_data.longitude;
                        }

                        initialize(lat, long, 'edit_');
                    @endif
                }
            });
        }

        $(document).on('change', '[name=country_id]', function() {
            var country_id = $(this).val();
            get_states(country_id);
        });

        $(document).on('change', '[name=state_id]', function() {
            var state_id = $(this).val();
            get_city(state_id);
        });

        function get_states(country_id) {
            $('[name="state"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('get-state') }}",
                type: 'POST',
                data: {
                    country_id: country_id
                },
                success: function(response) {
                    var obj = JSON.parse(response);
                    if (obj != '') {
                        $('[name="state_id"]').html(obj);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }

        function get_city(state_id) {
            $('[name="city"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('get-city') }}",
                type: 'POST',
                data: {
                    state_id: state_id
                },
                success: function(response) {
                    var obj = JSON.parse(response);
                    if (obj != '') {
                        $('[name="city_id"]').html(obj);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }
    </script>


    @if (get_setting('google_map') == 1)
        @include('frontend.partials.google_map')
    @endif
@endsection
@push('new_script')
    <script>
        function addressData(address_id) {
            $.ajax({
                type: 'POST',
                url: '{{ route('user.address') }}',
                data: {
                    address_id: address_id,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response) {


                },
                error: function(error) {
                    console.error('Error updating session variable:', error);
                }
            });
        }

        function saveAddressData() {
            location.reload();
            // Show flash message
            AIZ.plugins.notify('success', "{{ translate('Your Address Added!') }}");
        }
    </script>
@endpush

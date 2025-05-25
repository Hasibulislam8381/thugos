
<div class="modal fade" id="new-address-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content new_address_modal">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ translate('Add New Address') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="col-lg-12 py-4">
                <form id="NewaddressForm" class="form-default" data-toggle="validator" role="form" method="POST">
                    @csrf
                    <div class="row gutters-5">
                        <div class="col-lg-12 shipping_addess">
                            <div class="form-group">
                                <label for="">Full Name</label>
                                <input type="text" name="newname" class="form-control" id=""
                                    placeholder="Enter Name*" required>
                            </div>
                            <div class="form-group mt-3">
                                <label for="">Phone Number</label>
                                <input type="text" name="newphone" class="form-control" min="11" max="14" id=""
                                    placeholder="Ex:012xxxxxxx" required>
                            </div>
                            <div class="form-group mt-3">
                                <label for="">E-Mail</label>
                                <input type="text" name="newemail" class="form-control" id=""
                                    placeholder="Enter Email">
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

                                    <select class="form-control mb-3" data-live-search="true"
                                        name="city_id" required>
                                        <option value="">--Select--</option>
                                        @foreach (pathao_city() as $row)
                                            <option value="{{ $row->city_id }}">{{ $row->city_name }}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <div class="">
                                    <label>{{ translate('Zone') }}</label>
                                </div>
                                <div class="">
                                    <select class="form-control mb-3" data-live-search="true"
                                        name="zone_id" required>

                                    </select>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <div class="">
                                    <label>{{ translate('Area') }}</label>
                                </div>
                                <div class="">
                                    <select class="form-control mb-3" data-live-search="true"
                                        name="area_id" required>

                                    </select>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <label for="">Address</label>
                                <textarea name="newaddress" id="" class="form-control" placeholder="Enter Address" rows="3"></textarea>
                            </div>
                        </div>
                        <input type="hidden" name="checkout_type" value="logged">
                        <div class="m-auto">
                            <div class="mt-2">
                                <button type="button"
                                    onclick="submitNewForm('{{ route('addresses.newAddressstore') }}')"
                                    class="m-auto fw-600 fs-16 mr-0 btn btn-secondary btn-sm shadow-md mt-4 view_all_btn"
                                    style="border: none">{{ translate('Add Address') }}</button>
                            </div>

                        </div>

                    </div>
                </form>
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
        function add_new_address() {
            $('#new-address-modal').modal('show');
        }
    </script>
    <script>
        function submitNewForm(action) {
            var form = document.getElementById('NewaddressForm');
            form.action = action;
            form.submit();
        }
    </script>
    <script type="text/javascript">
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

        $(document).on('change', '[name=city_id]', function() {
            var city_id = $(this).val();
            get_zones(city_id);
        });

        $(document).on('change', '[name=zone_id]', function() {
            var zone_id = $(this).val();
            get_area(zone_id);
        });

        function get_zones(city_id) {
            $('[name="zone_id"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('get-city') }}",
                type: 'POST',
                data: {
                    city_id: city_id
                },
                success: function(response) {
                    var obj = JSON.parse(response);
                    if (obj != '') {
                        $('[name="zone_id"]').html(obj);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }

        function get_area(zone_id) {
            $('[name="area_id"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('get-state') }}",
                type: 'POST',
                data: {
                    zone_id: zone_id
                },
                success: function(response) {
                    var obj = JSON.parse(response);
                    if (obj != '') {
                        $('[name="area_id"]').html(obj);
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

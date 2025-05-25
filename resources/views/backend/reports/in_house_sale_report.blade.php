@extends('backend.layouts.app')


@section('content')
    <div class="card">
        <form class="sort_orders" action="" id="sort_orders" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-md-0 h6">{{ translate('All Orders') }}</h5>
                </div>

                <div class="col-lg-2">
                    <div class="form-group mb-0">
                        {{-- <input type="text" class="aiz-date-range form-control" value="{{ $date }}"
                            name="date" placeholder="{{ translate('Filter by date') }}" data-format="DD-MM-Y"
                            data-separator=" to " data-advanced-range="true" autocomplete="off"> --}}

                        <input type="text" class="form-control aiz-date-range report_date" name="date"
                            value="{{ $date }}" placeholder="{{ translate('Select Date') }}" data-time-picker="true"
                            data-format="DD-MM-Y" data-separator=" to " autocomplete="off">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group mb-0">
                        <input type="text" class="form-control" id="search"
                            name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset
                            placeholder="{{ translate('Type Order code or Customer Name or Customer Phone & hit Enter') }}">
                    </div>
                </div>
                <div class="col-auto">
                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary">{{ translate('Filter') }}</button>
                    </div>
                </div>
                {{-- <div class="col-auto">
                    <div class="form-group mb-0">
                        <button class="btn btn-primary" onclick="print_report()">Print</button>
                    </div>
                </div> --}}


            </div>

            <div class="card-body">


                <table class="table table-responsive orderReport  mb-0" id="orderReport">
                    <thead>
                        <tr>
                            <!--<th>#</th>-->

                            <th>{{ translate('Order Code') }}</th>
                            <th data-breakpoints="md">{{ translate('Num. of Products') }}</th>
                            <th data-breakpoints="md">{{ translate('Customer') }}</th>
                            <th data-breakpoints="md">{{ translate('Phone') }}</th>
                            <th data-breakpoints="md">{{ translate('Address') }}</th>
                            <th data-breakpoints="md">{{ translate('Amount') }}</th>
                            <th data-breakpoints="md">{{ translate('Due Amount') }}</th>
                            <th data-breakpoints="md">{{ translate('Delivery Status') }}</th>
                            <th data-breakpoints="md">{{ translate('Payment Status') }}</th>
                            {{-- @if (addon_is_activated('refund_request'))
                                <th>{{ translate('Refund') }}</th>
                            @endif --}}
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @php
                            $totalSum = 0;
                        @endphp
                        @foreach ($orders as $key => $order)
                            <tr>

                                <td>
                                    <div class="form-group">
                                        <div class="aiz-checkbox-inline">
                                            <label class="aiz-checkbox">
                                                <input type="checkbox" class="check-one" name="id[]"
                                                    value="{{ $order->id }}">
                                                <span class="aiz-square-check"></span>
                                            </label>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $shipping_address = json_decode($order->shipping_address, true);
                                        $name = $shipping_address['name'] ?? null;

                                    @endphp



                                    {{ $order->code }}
                                </td>
                                <td>
                                    {{ count($order->orderDetails) }}
                                </td>
                                <td>
                                    @if ($name)
                                        {{ $name }}
                                    @elseif ($order->user != null)
                                        {{ $order->user->name }}
                                    @else
                                        Guest ({{ $order->guest_id }})
                                    @endif
                                </td>
                                <td>
                                    {{ single_price(floatval($order->grand_total)) }}
                                </td>
                                <td>
                                    @if ($order->advance_payment == 'paid')
                                        {{ single_price(floatval($order->grand_total) - floatval(get_setting('advance_payment'))) }}
                                    @elseif($order->payment_status != 'paid')
                                        {{ single_price(floatval($order->grand_total)) }}
                                    @else
                                        0
                                    @endif
                                </td>

                                <td>
                                    @php
                                        $status = $order->delivery_status;
                                        if ($order->delivery_status == 'cancelled') {
                                            $status =
                                                '<span class="badge badge-inline badge-danger">' .
                                                translate('Cancel') .
                                                '</span>';
                                        }

                                    @endphp
                                    {!! $status !!}
                                </td>
                                <td>
                                    @if ($order->payment_status == 'paid')
                                        <span class="badge badge-inline badge-success paid_unpaid_btn"
                                            style="height: auto">{{ translate('Paid') }}</span>
                                    @else
                                        <span class="badge badge-inline badge-danger paid_unpaid_btn"
                                            style="height: auto">{{ translate('Unpaid') }}</span>
                                    @endif
                                </td>
                                @if (addon_is_activated('refund_request'))
                                    <td>
                                        @if (count($order->refund_requests) > 0)
                                            {{ count($order->refund_requests) }} {{ translate('Refund') }}
                                        @else
                                            {{ translate('No Refund') }}
                                        @endif
                                    </td>
                                @endif
                            </tr>
                            @php
                                $orderTotal = floatval($order->grand_total);
                                $totalSum += $orderTotal; // Accumulate order total for each order
                            @endphp
                            @if ($loop->last || $loop->iteration % $orders->perPage() == 0)
                                <!-- Display total sum for the current page when it's the last item or every perPage items -->
                                <tr>
                                    <td colspan="12" class="text-right">
                                        <h1>Total: {{ single_price($totalSum) }}</h1>
                                    </td>
                                </tr>
                                @php
                                    $totalSum = 0; // Reset total sum for the next page
                                @endphp
                            @endif
                        @endforeach --}}
                    </tbody>
                </table>

                {{-- <div class="d-flex justify-content-between">
                    <div class="aiz-pagination">
                        {{ $orders->appends(request()->input())->links() }}

                    </div>
                </div> --}}

            </div>
            <div class="card-footer">
                <strong>Grand Total: <span id="total_grand_total" class="footer_grand_total"></span></strong>
            </div>
        </form>


    </div>
@endsection
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ static_asset('assets/css/jquery.dataTables.css') }}">
    <!-- Include jQuery -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
@endsection



<!-- Include DataTables JS -->




@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>

    <!-- DataTables Buttons JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script type="text/javascript">
        $(document).on("change", ".check-all", function() {
            if (this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;
                });
            }

        });

        function print_report() {
            var printContent = document.querySelector('.card-body').innerHTML;
            var originalDocument = document.body.innerHTML;
            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalDocument;
        }
    </script>
    <script>
        $(document).ready(function() {
            $('#orderReport').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{{ route('in_house_sale_report.index') }}",
                    "type": "GET",
                    data: function(d) {
                        var selectedDate = $('.report_date').val();
                        var searchValue = $('#search').val();
                        d.date = selectedDate;
                        d.search = searchValue;
                    }
                },
                columns: [{
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'order_details_count',
                        name: 'order_details_count'
                    },
                    {
                        data: 'name_formatted',
                        name: 'name'
                    },
                    {
                        data: 'phone_formatted',
                        name: 'phone'
                    },
                    {
                        data: 'address_formatted',
                        name: 'address'
                    },
                    {
                        data: 'grand_total_formatted',
                        name: 'grand_total'
                    },
                    {
                        data: 'amount_formatted',
                        name: 'amount'
                    },
                    {
                        data: 'delivery_status',
                        name: 'delivery_status'
                    },
                    {
                        data: 'payment_status',
                        name: 'payment_status'
                    }
                ],
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'csv',
                        customize: function(csv) {
                            // Append the grand total as a new row at the end of the CSV
                            csv += '\nGrand Total: ' + $('#total_grand_total').text();
                            return csv;
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        customize: function(doc) {
                            doc.content.push({
                                text: 'Grand Total: ' + $('#total_grand_total').text(),
                                alignment: 'center'
                            });
                        }
                    },
                    {
                        extend: 'print',
                        customize: function(win) {
                            $(win.document.body).append('<div>Grand Total: ' + $(
                                '#total_grand_total').text() + '</div>');
                        }
                    }
                ],

                footerCallback: function(row, data, start, end, display) {
                    var footer_grand_total = 0;

                    for (var r in data) {
                        // Remove any non-numeric characters including the Taka symbol
                        var grand_total_formatted = parseFloat(data[r].grand_total_formatted.replace(
                            /[^\d.-]/g, ''));
                        if (!isNaN(grand_total_formatted)) {
                            footer_grand_total += grand_total_formatted;
                        }
                    }

                    $('.footer_grand_total').html('৳' + footer_grand_total.toFixed(
                        2)); // Add the Taka symbol back
                }

            });
        });
    </script>
@endsection

@extends('backend.layouts.app')

@section('content')
    <div class="card">
        <form class="" id="sort_support" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col text-center text-md-left">
                    <h5 class="mb-md-0 h6">{{ translate('Stock Request') }}</h5>
                </div>
            </div>
            </from>

            <div class="card-body">
                <table class="aiz-table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th data-breakpoints="lg">{{ translate('Sl') }}</th>
                            <th data-breakpoints="lg">{{ translate('Sending Date') }}</th>
                            <th>{{ translate('Product') }}</th>
                            <th data-breakpoints="lg">{{ translate('Number') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tickets as $key => $ticket)
                            <tr>
                                <td>{{ ($key+1) + ($tickets->currentPage() - 1)*$tickets->perPage() }}</td>
                                <td>{{ $ticket->created_at }}</td>
                                <td><a href="/product/{{ @$ticket->product->slug }}"
                                        target="_blank">{{ @$ticket->product->name }}</a></td>
                                <td>{{ $ticket->user_phone }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="clearfix">
                    <div class="pull-right">
                        {{ $tickets->appends(request()->input())->links() }}
                    </div>
                </div>
            </div>
    </div>
@endsection

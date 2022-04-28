@extends('layouts.app') @section('content')
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">Transaction list
                </div>
                <div class="card-body" style="padding: 0px;">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <table class="table table-striped tabled-condensed">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $item)
                                <tr>
                                    <td>{{ $item->customer_name }}</td>
                                    <td>{{ $item->customer_email }}</td>
                                    <td>{{ $item->customer_mobile }}</td>
                                    <td>
                                        <span
                                            @if ($item->status === 'CREATED') style="color:#df921f;font-weight: bold;" @endif
                                            @if ($item->status === 'REJECTED') style="color:#c15151;font-weight: bold;" @endif
                                            @if ($item->status === 'PAYED') style="color:#258525;font-weight: bold;" @endif>{{ $item->status }}</span>
                                    </td>
                                    <td>
                                        <a
                                            href="{{ route('ticket', ['platform' => 'placetoplay', 'id' => $item->request_id]) }}">
                                            <button class="btn btn-info btn-sm">Show</button>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            @if (count($orders) == 0)
                                <tr>
                                    <td colspan="5"> No records found </td>
                                </tr>
                            @endif
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

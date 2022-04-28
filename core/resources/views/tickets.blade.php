@extends('layouts.app') @section('content')
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="alert alert-success" role="alert">
                {{$intentPay->status->status}}
            </div>

            <div class="card">
                <div class="card-header">Transaction #{{$intentPay->requestId}}
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col">
                            <label for="customer_name"><b>Fullname</b></label> <br>
                            {{$intentPay->request->payer->name}} {{$intentPay->request->payer->surname}}
                        </div>
                        <div class="col">
                            <label for="customer_email"><b>Email</b></label> <br>
                            {{$intentPay->request->payer->email}}
                        </div>
                        <div class="col">
                            <label for="customer_mobile"><b>Mobile</b></label> <br>
                            {{$intentPay->request->payer->mobile}}
                        </div>
                    </div>
                    <br><br>
                    <div class="row">
                        <div class="col" style="text-align: left;">
                           <a href="{{$intentPay->request->fields[0]->value}}"><button type="submit" id="payButton"
                            class="btn btn-sm btn-success btn-primary btn-lg">Checkout</button></a>
                        </div>
                        <div class="col" style="text-align: right;">
                            <label for="customer_amount">Amount:</label>
                            <small>{{$intentPay->request->payment->amount->currency}}</small> <br><span style="font-size: 18px;font-weight: bold"> $ {{$intentPay->request->payment->amount->total}}</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

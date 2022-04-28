@extends('layouts.app') @section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Payment gateway</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form action="{{ route('pay') }}" method="POST" id="paymentForm">
                            @csrf
                            <input type="hidden" name="status" value="CREATED">
                            <input type="hidden" name="payment_platform" value="placetoplay">
                            <div class="row">
                                <div class="col">
                                    <label for="customer_name">Fullname</label>
                                    <input type="text" class="form-control" required name="customer_name" />
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-6">
                                    <label for="customer_email">Email</label>
                                    <input type="email" class="form-control" required name="customer_email" />
                                </div>

                                <div class="col-6">
                                    <label for="customer_mobile">Mobile</label>
                                    <input type="text" class="form-control" required name="customer_mobile" />
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-auto">
                                    <label for="total">Amount </label>
                                    <input type="number" min="5" step="0.01" class="form-control" required name="total" />
                                    <small class="form-text text-muted">
                                        Use values ​​with two decimal places using .
                                    </small>
                                </div>
                            </div>
                            <br>
                            <div class="text-center mt">
                                <button type="submit" id="payButton"
                                    class="btn btn-sm btn-success btn-primary btn-lg">Checkout</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

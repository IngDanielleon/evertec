<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaceToPlayRequest;
use App\Models\Orders;
use App\Resolvers\PaymentPlatformResolver;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $paymentPlatformResolver;

    /**
     * Constructor
     * Instance the class to call the payment platform
     * @param PaymentPlatformResolver $paymentPlatformResolver
     */
    public function __construct(PaymentPlatformResolver $paymentPlatformResolver)
    {
        $this->paymentPlatformResolver = $paymentPlatformResolver;
    }

    /**
     * Method to make the payment
     *
     * @param Request $request
     * @return void
     */
    public function pay(PlaceToPlayRequest $request)
    {
        $order = Orders::create($request->all());
        $request['reference'] = $order->id;
        $paymentPlatform = $this->paymentPlatformResolver->resolveService($request->payment_platform);
        session()->put('paymentPlatformId', $request->payment_platform);
        return $paymentPlatform->handlePayment($request);
    }

    /**
     * Validate the intention to pay in progress
     *
     * @return void
     */
    public function valid()
    {
        if (session()->has('paymentPlatformId')) {
            $paymentPlatform = $this->paymentPlatformResolver->resolveService(session()->get('paymentPlatformId'));
            return $paymentPlatform->handleApproval();
        }
        return null;
    }

    /**
     * Redirects the user in case the payment is accepted
     *
     * @return void
     */
    public function approval()
    {
        if ($response = $this->valid()) {
            return $response;
        }
        return redirect()
            ->route('home')
            ->withErrors('No se puede obtener la plataforma de pago, por favor intente de nuevo');
    }

    /**
     * Redirects the user if he has canceled the payment or if it was rejected
     *
     * @return void
     */
    public function cancelled()
    {
        if ($response = $this->valid()) {
            return $response;
        }
        return redirect()
            ->route('home')
            ->withErrors('Transacción cancelada');
    }

    /**
     * Check the status of a transaction
     *
     * @param String $platform
     * @param int $id
     * @return void
     */
    function search($platform, $id){
        $paymentPlatform = $this->paymentPlatformResolver->resolveService($platform);
        $intentPay = $paymentPlatform->handleStatus($id);
        // dd($intentPay);
        return view("tickets",compact('intentPay'));
    }
}

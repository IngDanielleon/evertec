<?php

namespace App\Services;

use App\FormRequest\StripeRequest;
use App\Http\Requests\PlaceToPlayRequest;
use App\Models\Orders;
use Illuminate\Http\Request;
use App\Traits\ConsumesExternalServices;
use Carbon\Carbon;

class PlaceToPlayService
{
    use ConsumesExternalServices;

    /**
     * Enpoint
     *
     * @var String
     */
    protected $baseUri;

    /**
     * ID client
     *
     * @var String
     */
    protected $key;

    /**
     * client secret
     *
     * @var String
     */
    protected $secret;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->baseUri = config('services.placetoplay.base_uri');
        $this->key = config('services.placetoplay.key');
        $this->secret = config('services.placetoplay.secret');
    }

    /**
     * Method to perform service authentication
     *
     * @param Array $queryParams
     * @param Array $formParams
     * @param Array $headers
     * @return void
     */
    public function resolveAuthorization(&$queryParams, &$formParams, &$headers)
    {
        $nonce = strtotime("now");
        $formParams['auth']['login'] = $this->key;
        $formParams['auth']['seed'] = Carbon::now()->toIso8601String();
        $formParams['auth']['nonce'] = base64_encode($nonce);
        $formParams['auth']['tranKey'] = base64_encode(sha1($nonce . $formParams['auth']['seed'] . $this->secret, true));
    }

    /**
     * Method to return the response in JSON
     *
     * @param String $response
     * @return void
     */
    public function decodeResponse($response)
    {
        return json_decode($response);
    }

    /**
     * Method to add a token to the service
     *
     * @return void
     */
    public function resolveAccessToken()
    {
    }

    /**
     * Method to make the payment request
     *
     * @param Request $request
     * @return void
     */
    public function createIntent($request)
    {
        return $this->makeRequest(
            'POST',
            '/redirection/api/session',
            [],
            [
                "locale" => "es_CO",
                "payment" => [
                    "reference" => $request->reference,
                    "description" => $request->description,
                    "amount" => [
                        "currency" => $request->currency ?? "USD",
                        "total" => $request->total
                    ],
                    "allowPartial" => false
                ],
                "expiration" => Carbon::now()->addMinute(30)->toIso8601String(),
                "returnUrl" => route('approval'),
                "cancelUrl" => route('cancelled'),
                "ipAddress" => "127.0.0.1",
                "userAgent" => "PlacetoPay Sandbox"
            ],
            [],
            $isJsonRequest = true
        );
    }

    /**
     * Confirmation request to the payment platform
     *
     * @param int $paymentIntentId
     * @return void
     */
    public function confirmPayment($paymentIntentId)
    {
        return $this->makeRequest(
            'POST',
            "/redirection/api/session/{$paymentIntentId}",
            [],
            [],
            [
                'Content-Type' => 'application/json',
            ],
            $isJsonRequest = true
        );
    }

    /**
     * Method to create the payment intention
     *
     * @param Request $request
     * @return void
     */
    public function handlePayment(PlaceToPlayRequest $request)
    {
        if ($intent = $this->createIntent($request)) {
            $order = Orders::find($request->reference);
            $order->fill(['request_id' => $intent->requestId])->save();
            session()->put('paymentIntentId', $intent->requestId);
            return redirect($intent->processUrl);
        };
    }

    /**
     * Accepted payment processing method
     *
     * @return void
     */
    public function handleApproval()
    {

        if (session()->has('paymentIntentId')) {
            $paymentIntentId = session()->get('paymentIntentId');
            $confirmation = $this->handleStatus($paymentIntentId);
            $status = null;
            if ($confirmation->status->status === "APPROVED") {
                $status = $confirmation->status->status;
            }
            if ($confirmation->status->status === "REJECTED") {
                $status = $confirmation->status->status;
            }
            if ($status != null) {
                // dd($confirmation);
                $order = Orders::where("request_id",$confirmation->requestId)->first();
                $order->fill(['status' => $status])->save();
            }
            return redirect()->route('home')->withSuccess($confirmation->status->message);
        }
        return redirect()->route('home')->withErrors("No hemos podido confirmar su pago, intente nuevamente por favor");
    }


    /**
     * Method to verify the status of the intention to pay
     *
     * @param String $platform
     * @param int $paymentId
     * @return void
     */
    function handleStatus($paymentId){
        return $this->confirmPayment($paymentId);
    }
}

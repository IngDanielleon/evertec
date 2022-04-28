<?php

namespace App\Resolvers;

class PaymentPlatformResolver
{
    protected $paymentPlatforms;

    /**
     * Undocumented function
     *
     * @param [type] $paymentPlatformId
     * @return void
     */
    public function resolveService($name)
    {
        if ($service = config("services.{$name}.class")) {
            return resolve($service);
        }
        throw new \Exception("La plataforma de pago seleccionada no ha sido configurada", 1);
    }
}

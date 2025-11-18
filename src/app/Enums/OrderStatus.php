<?php

namespace App\Enums;

enum OrderStatus: string
{
    case REGISTERED = 'registered';         
    case PROCESSING = 'processing';         
    case SHIPPED = 'shipped';               
    case PICKUP_AVAILABLE = 'pickup_ready'; 
    case DELIVERED = 'delivered';   
    case PAYMENT_FAILED = "payment_failde";        

    public function label(): string
    {
        return match ($this) {
            self::REGISTERED      => 'Rendelés rögzítve',
            self::PROCESSING      => 'Feldolgozás alatt',
            self::SHIPPED         => 'Átadva a futárnak',
            self::PICKUP_AVAILABLE => 'Üzletünkben átvehető',
            self::DELIVERED       => 'Kézbesítve',
            self::PAYMENT_FAILED  => 'Rendelés sikertelen',
        };
    }
}

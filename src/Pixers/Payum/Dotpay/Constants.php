<?php

namespace Pixers\Payum\Dotpay;

abstract class Constants
{

    const FIELD_PAID = 'paid';
    const FIELD_STATUS = 'status';
    const STATUS_NEW = 'new';
    const STATUS_CAPTURED = 'captured';
    const STATUS_PENDING = 'pending';
    const STATUS_AUTHORIZED = 'authorized';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELED = 'canceled';
    const STATUS_REFUNDED = 'refunded';
    const STATUS_COMPLAINT = 'complaint';

    /*
     * Docs: http://dotpay.pl/files/dotpay_instrukcja_techniczna.pdf
     */
    const TYPE_ONLY_RETURN_BUTTON = 0;
    const TYPE_INFINITY_POST_NOTIFY = 1;
    const TYPE_NO_INTERACTION = 2;
    const TYPE_RETURN_BUTTON_AND_NOTIFY = 3;
    
    /*
     * Docs: http://dotpay.pl/files/dotpay_instrukcja_techniczna.pdf
     */
    const DOTPAY_STATUS_NEW = 1;
    const DOTPAY_STATUS_SUCCESS = 2;
    const DOTPAY_STATUS_ERROR = 3;
    const DOTPAY_STATUS_CANCEL = 4;
    const DOTPAY_STATUS_COMPLAINT = 5;

    /**
     * @return array
     */
    public static function getSupportedCurrencies()
    {
        return [
            'PLN',
            'EUR',
            'USD',
            'GBP',
            'JPY',
            'CZK',
            'SEK',
            'DKK'
        ];
    }

    final private function __construct()
    {
        
    }

}

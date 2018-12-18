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

    /*
     * Docs: http://dotpay.pl/files/dotpay_instrukcja_techniczna.pdf
     */
    const TYPE_ONLY_RETURN_BUTTON = 0;
    const TYPE_NO_INTERACTION = 2;
    const TYPE_REDIRECT_IMMEDIATELY = 4;

    /*
     * Docs: http://dotpay.pl/files/dotpay_instrukcja_techniczna.pdf
     */
    const DOTPAY_STATUS_NEW = 'new';
    const DOTPAY_STATUS_PROCESSING = 'processing';
    const DOTPAY_STATUS_COMPLETED = 'completed';
    const DOTPAY_STATUS_REJECTED = 'rejected';

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
            'DKK',
        ];
    }

    private function __construct()
    {
    }
}

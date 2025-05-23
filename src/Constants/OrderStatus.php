<?php

namespace Coincraddle\Constants;

class OrderStatus
{
    public const NEW = 'new';
    public const WAITING_DEPOSIT = 'waiting_deposit';
    public const DEPOSIT_RECEIVED = 'deposit_received';
    public const EXCHANGING = 'exchanging';
    public const SENDING = 'sending';
    public const SUCCESS = 'success';
    public const TIME_EXPIRED = 'time_expired';
    public const PAYMENT_TIME_EXPIRED = 'payment_time_expired';
    public const FAILED = 'failed';
    public const SENDING_FAILED = 'sending_failed';
    public const REVERTED = 'reverted';
    public const PAYMENT_HALTED = 'payment_halted';
    public const EXPIRED = 'EXPIRED';
    public const LESS = 'LESS';
} 
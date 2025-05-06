<?php

namespace App\Constants;

class RabbitQueues
{
    const ORDER = 'order_queue';
    const ORDER_STATUS_UPDATE = 'order_status_update';
    const PAYMENT = 'payment';
    const NOTIFICATION = 'notifications';
    const LOGISTICS = 'logistics';
    const ERRORS = 'error_queue';
}

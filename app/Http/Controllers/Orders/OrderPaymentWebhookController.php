<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Models\Events\Event;
use App\Services\Orders\OrderPaymentUpdateService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderPaymentWebhookController extends Controller
{
    public function __invoke(Request $request, Event $event, OrderPaymentUpdateService $service): Response
    {
        // Mercado Pago sends `?type=payment&data.id=123`, but PHP mangles the
        // dot in query-string keys to an underscore (`data_id`) before it
        // ever reaches Laravel. The dotted `data.id` only shows up as-is in a
        // JSON body, where Laravel's own dot-notation array access applies.
        $paymentId = $request->input('data.id')
            ?? $request->query('data_id')
            ?? $request->input('id');

        $type = $request->input('type', $request->query('type'));

        if ($type === 'payment' && filled($paymentId)) {
            $service->execute($event, (string) $paymentId);
        }

        return response()->noContent();
    }
}

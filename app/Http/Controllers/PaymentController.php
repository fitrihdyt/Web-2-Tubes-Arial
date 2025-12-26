<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Midtrans\Notification;
use Midtrans\Config;

class PaymentController extends Controller
{
    public function midtransCallback()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        $notif = new Notification();

        $transaction = $notif->transaction_status;
        $fraud = $notif->fraud_status;
        $orderId = $notif->order_id; 

        $bookingId = str_replace('BOOKING-', '', $orderId);

        $booking = Booking::find($bookingId);

        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        if ($transaction === 'capture') {
            if ($fraud === 'accept') {
                $this->updateBookingStatus($booking, 'paid', $notif);
            }
        } elseif ($transaction === 'settlement') {
            $this->updateBookingStatus($booking, 'paid', $notif);
        } elseif ($transaction === 'pending') {
            $this->updateBookingStatus($booking, 'pending', $notif);
        } else {
            $this->updateBookingStatus($booking, 'cancelled', $notif);
        }

        return response()->json(['message' => 'Callback processed'], 200);
    }

    protected function updateBookingStatus(Booking $booking, string $status, $notif)
    {
        $booking->update(['status' => $status]);

        Payment::updateOrCreate(
            [
                'booking_id' => $booking->id,
            ],
            [
                'order_id' => $notif->order_id,
                'status' => $status,
                'paid_at' => $status === 'paid' ? now() : null,
            ]
        );
    }
}

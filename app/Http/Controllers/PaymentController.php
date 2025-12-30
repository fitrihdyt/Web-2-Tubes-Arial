<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Midtrans\Notification;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function pay(Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return redirect()
                ->route('bookings.show', $booking->id)
                ->with('error', 'Booking sudah diproses.');
        }

     
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $orderId = 'BOOKING-' . $booking->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $booking->total_price,
            ],
            'customer_details' => [
                'first_name' => $booking->user->name,
                'email' => $booking->user->email,
            ],
            'callbacks' => [
                'finish' => route('bookings.show', $booking->id),
            ],
        ];

        $payment = Payment::firstOrCreate(
            ['booking_id' => $booking->id],
            [
                'order_id' => $orderId,
                'status' => 'pending'
            ]
        );

        if (!$payment->snap_token) {
            $payment->update([
                'snap_token' => Snap::getSnapToken($params)
            ]);
        }

        return view('bookings.payment', [
            'booking' => $booking,
            'snapToken' => $payment->snap_token
        ]);
    }

    public function midtransCallback()
    {
        
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        $notif = new Notification();

        $orderId = $notif->order_id; 
        $transaction = $notif->transaction_status;
        $fraud = $notif->fraud_status ?? null;

       
        $bookingId = str_replace('BOOKING-', '', $orderId);
        $booking = Booking::find($bookingId);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        if (
            $transaction === 'capture' && $fraud === 'accept'
            || $transaction === 'settlement'
        ) {
            $status = 'paid';
        } elseif ($transaction === 'pending') {
            $status = 'pending';
        } else {
            $status = 'cancelled';
        }

        $this->updateBookingStatus($booking, $status, $notif);

        return response()->json(['message' => 'OK'], 200);
    }

    protected function updateBookingStatus(Booking $booking, string $status, $notif)
    {
        $booking->update([
            'status' => $status
        ]);

        Payment::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'order_id' => $notif->order_id,
                'status' => $status,
                'paid_at' => $status === 'paid' ? now() : null,
            ]
        );
    }
}

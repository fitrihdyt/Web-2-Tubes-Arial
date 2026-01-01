<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * List booking user
     */
    public function index()
    {
        $bookings = Booking::with('room.hotel')->where('user_id', Auth::id())->latest()->get();

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show booking form
     */
    public function create(Room $room)
    {
        return view('bookings.create', [
            'room' => $room,
            'maxQty' => $room->stock,
        ]);
    }

    /**
     * Store booking
     */
    public function store(Request $request)
    {
        $room = Room::findOrFail($request->room_id);

        $validated = $request->validate([
            'room_id'   => 'required|exists:rooms,id',
            'check_in'  => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'qty'       => 'required|integer|min:1|max:' . $room->stock,
        ]);

        $checkIn  = $validated['check_in'];
        $checkOut = $validated['check_out'];
        $qty      = $validated['qty'];

        DB::beginTransaction();

        try {
            $date = Carbon::parse($checkIn);

            while ($date->lt($checkOut)) {
                if ($this->availableStock($room, $date) < $qty) {
                    return back()->withErrors([
                        'qty' => 'Kamar tidak tersedia di tanggal ' . $date->format('d M Y')
                    ]);
                }
                $date->addDay();
            }

            $days = Carbon::parse($checkIn)->diffInDays(Carbon::parse($checkOut));
            $total = $days * $room->price * $qty;

            $booking = Booking::create([
                'user_id'     => Auth::id(),
                'room_id'     => $room->id,
                'check_in'    => $checkIn,
                'check_out'   => $checkOut,
                'qty'         => $qty,
                'total_price' => $total,
                'status'      => 'pending',
            ]);

            DB::commit();

            return redirect()
                ->route('bookings.show', $booking)
                ->with('success', 'Booking berhasil dibuat');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors('Terjadi kesalahan saat booking');
        }
    }

    /**
     * Show booking detail
     */
    public function show(Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);

        $booking->load('room.hotel');

        return view('bookings.show', compact('booking'));
    }

    /**
     * Cancel booking
     */
    public function destroy(Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);

        if ($booking->status === 'paid') {
            return back()->withErrors('Booking sudah dibayar');
        }

        $booking->update([
            'status' => 'cancelled'
        ]);

        return redirect()
            ->route('bookings.index')
            ->with('success', 'Booking dibatalkan');
    }

    /**
     * Booking history (paid & finished)
     */
    public function history()
    {
        $bookings = Booking::with('room.hotel')
            ->where('user_id', Auth::id())
            ->where('status', 'paid')
            ->whereDate('check_out', '<', now())
            ->orderByDesc('check_out')
            ->get();

        return view('bookings.history', compact('bookings'));
    }

    public function storeRating(Request $request, Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);

        if ($booking->status !== 'paid' || $booking->check_out >= now()) {
            return back()->withErrors('Booking belum selesai');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:255',
        ]);

        $booking->update([
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return back()->with('success', 'Rating berhasil dikirim');
    }

    protected function availableStock(Room $room, Carbon $date): int
    {
        $booked = Booking::where('room_id', $room->id)
            ->whereIn('status', ['pending', 'paid'])
            ->where('check_in', '<', $date)
            ->where('check_out', '>', $date)
            ->sum('qty');

        return max(0, $room->stock - $booked);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; 

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $bookings = Booking::with('room.hotel')->where('user_id', auth()->id())->latest()->get();
        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Room $room)
    {
        //
        if ($room->stock < 1) {
            abort(404);
        }

        return view('bookings.create', compact('room'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'room_id'   => 'required|exists:rooms,id',
            'check_in'  => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
        ]);

        $room = Room::findOrFail($validated['room_id']);

        if ($room->stock < 1) {
            return back()->withErrors('Stok kamar habis');
        }

        $days = Carbon::parse($validated['check_in'])
            ->diffInDays(Carbon::parse($validated['check_out']));

        if ($days < 1) {
            return back()->withErrors('Tanggal tidak valid');
        }

        $totalPrice = $days * $room->price;

        $booking = Booking::create([
            'user_id'     => auth()->id(),
            'room_id'     => $room->id,
            'check_in'    => $validated['check_in'],
            'check_out'   => $validated['check_out'],
            'total_price' => $totalPrice,
            'status'      => 'pending',
        ]);

        return redirect()->route('bookings.show', $booking)->with('success', 'Booking berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        //
        abort_if($booking->user_id !== auth()->id(), 403);
        $booking->load('room.hotel');

        return view('bookings.show', compact('booking'));
    }

    // PAYMENT (DUMMY)
    public function pay(Booking $booking)
    {
        abort_if($booking->user_id !== auth()->id(), 403);

        if ($booking->status !== 'pending') {
            return back()->withErrors('Booking tidak valid');
        }

        DB::transaction(function () use ($booking) {
            $booking->update([
                'status' => 'paid'
            ]);

            $booking->room->decrement('stock');
        });

        return redirect()
            ->route('bookings.show', $booking)
            ->with('success', 'Pembayaran berhasil');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        //
        abort_if($booking->user_id !== auth()->id(), 403);

        if ($booking->status === 'paid') {
            return back()->withErrors('Booking sudah dibayar');
        }

        $booking->update([
            'status' => 'cancelled'
        ]);

        return redirect()->route('bookings.index')->with('success', 'Booking dibatalkan');
    }
}

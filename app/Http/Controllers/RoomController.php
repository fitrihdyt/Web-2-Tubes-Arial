<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Hotel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $rooms = Room::with('hotel')->latest()->get();
        return view('rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $hotels = Hotel::all();
        return view('rooms.create', compact('hotels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'hotel_id'      => 'required|exists:hotels,id',
            'name'          => 'required|string|max:255',
            'price'         => 'required|integer|min:0',
            'capacity'      => 'required|integer|min:1',
            'stock'         => 'required|integer|min:0',
            'description'   => 'nullable|string',
            'thumbnail'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'images.*'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('rooms/thumbnails', 'public');
        }

        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('rooms/images', 'public');
            }
            $validated['images'] = $images;
        }

        Room::create($validated);

        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        //
        return view('rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        //
        $hotels = Hotel::all();
        return view('rooms.edit', compact('room', 'hotels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        //
        $validated = $request->validate([
            'hotel_id'      => 'required|exists:hotels,id',
            'name'          => 'required|string|max:255',
            'price'         => 'required|integer|min:0',
            'capacity'      => 'required|integer|min:1',
            'stock'         => 'required|integer|min:0',
            'description'   => 'nullable|string',
            'thumbnail'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'images.*'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($room->thumbnail) {
                Storage::disk('public')->delete($room->thumbnail);
            }

            $validated['thumbnail'] = $request->file('thumbnail')->store('rooms/thumbnails', 'public');
        } else {
            $validated['thumbnail'] = $room->thumbnail;
        }

        if ($request->hasFile('images')) {
            $images = $room->images ?? [];

            foreach ($request->file('images') as $image) {
                $images[] = $image->store('rooms/images', 'public');
            }

            $validated['images'] = $images;
        } else {
            $validated['images'] = $room->images;
        }

        $room->update($validated);

        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        //
        if ($room->thumbnail) {
            Storage::disk('public')->delete($room->thumbnail);
        }

        if ($room->images) {
            foreach ($room->images as $img) {
                Storage::disk('public')->delete($img);
            }
        }

        $room->delete();

        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil dihapus');
    }
}

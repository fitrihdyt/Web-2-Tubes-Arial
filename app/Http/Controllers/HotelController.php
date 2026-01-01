<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class HotelController extends Controller
{
    /**
     * List hotel
     */
    public function index(Request $request)
    {
        $hotels = Hotel::with('facilities')
            ->withMin('rooms', 'price')
            ->latest()
            ->get();

        return view('hotels.index', compact('hotels'));
    }

    // Dashboard
    public function dashboard(Request $request)
    {
        $query = Hotel::query()
            ->withMin('rooms', 'price')
            ->with('facilities');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('city', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('star')) {
            $query->whereIn('star', $request->star);
        }

        if ($request->filled('price')) {
            match ($request->price) {
                '0-500' => $query->having('rooms_min_price', '<=', 500000),
                '500-1000' => $query->havingBetween('rooms_min_price', [500000, 1000000]),
                '1000+' => $query->having('rooms_min_price', '>=', 1000000),
                default => null,
            };
        }

        if ($request->filled('sort')) {
            match ($request->sort) {
                'price_asc' => $query->orderBy('rooms_min_price'),
                'price_desc' => $query->orderByDesc('rooms_min_price'),
                'star_desc' => $query->orderByDesc('star'),
                default => null,
            };
        }

        $hotels = $query->get();

        return view('dashboard', compact('hotels'));
    }

    /**
     * Form tambah hotel
     */
    public function create()
    {
        $facilities = Facility::all();
        return view('hotels.create', compact('facilities'));
    }

    /**
     * Simpan hotel baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'star' => 'required|integer|min:1|max:5',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',
            'custom_facilities.*' => 'nullable|string|max:100',
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')
                ->store('hotels/thumbnails', 'public');
        }

        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('hotels/images', 'public');
            }
            $validated['images'] = $images;
        }

        $hotelData = collect($validated)->except([
            'facilities',
            'custom_facilities'
        ])->toArray();

        $hotel = Hotel::create($hotelData);

        $facilityData = [];
        foreach ($request->facilities ?? [] as $facilityId) {
            $facilityData[$facilityId] = [
                'custom_name' => $request->custom_facilities[$facilityId] ?? null
            ];
        }

        $hotel->facilities()->sync($facilityData);

        return redirect()->route('hotels.index')->with('success', 'Hotel berhasil ditambahkan');
    }

    /**
     * Detail hotel
     */
    public function show(Hotel $hotel)
    {
        // OPTIONAL: pastikan admin cuma lihat hotel miliknya
        if (Auth::check() && Auth::user()->hotel_id && Auth::user()->hotel_id != $hotel->id) {
            abort(403);
        }

        $hotel->load([
            'rooms',
            'facilities',
            'bookings.user',
            'bookings.room'
        ]);

        return view('hotels.show', compact('hotel'));
    }

    /**
     * Form edit hotel
     */
    public function edit(Hotel $hotel)
    {
        $facilities = Facility::all();
        $hotel->load('facilities');

        return view('hotels.edit', compact('hotel', 'facilities'));
    }

    /**
     * Update hotel
     */
    public function update(Request $request, Hotel $hotel)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'star' => 'required|integer|min:1|max:5',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',
            'custom_facilities.*' => 'nullable|string|max:100',
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')
                ->store('hotels/thumbnails', 'public');
        } else {
            $validated['thumbnail'] = $hotel->thumbnail;
        }

        if ($request->hasFile('images')) {
            $images = $hotel->images ?? [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('hotels/images', 'public');
            }
            $validated['images'] = $images;
        } else {
            $validated['images'] = $hotel->images;
        }

        $hotelData = collect($validated)->except([
            'facilities',
            'custom_facilities'
        ])->toArray();

        $hotel->update($hotelData);

        $facilityData = [];
        foreach ($request->facilities ?? [] as $facilityId) {
            $facilityData[$facilityId] = [
                'custom_name' => $request->custom_facilities[$facilityId] ?? null
            ];
        }

        $hotel->facilities()->sync($facilityData);

        return redirect()
            ->route('hotels.index')
            ->with('success', 'Hotel berhasil diperbarui');
    }

    /**
     * Hapus hotel
     */
    public function destroy(Hotel $hotel)
    {
        if ($hotel->thumbnail) {
            Storage::disk('public')->delete($hotel->thumbnail);
        }

        if ($hotel->images) {
            foreach ($hotel->images as $img) {
                Storage::disk('public')->delete($img);
            }
        }

        $hotel->delete();

        return redirect()
            ->route('hotels.index')
            ->with('success', 'Hotel berhasil dihapus');
    }

    /**
     * Booking hotel (ADMIN)
     */
    public function bookings(Hotel $hotel)
    {
        $hotel->load([
            'bookings.user',
            'bookings.room'
        ]);

        return view('hotels.bookings', compact('hotel'));
    }


}

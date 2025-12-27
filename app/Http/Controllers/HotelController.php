<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HotelController extends Controller
{
    /**
     * List hotel
     */
    public function index(Request $request)
    {
        $hotels = Hotel::latest()->get();
        return view('hotels.index', compact('hotels'));
    }

    // Dashboard
    public function dashboard(Request $request)
    {
        $query = Hotel::query()
            ->withMin('rooms', 'price');

        // SEARCH
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('city', 'like', '%' . $request->search . '%');
            });
        }

        // STAR
        if ($request->filled('star')) {
            $query->whereIn('star', $request->star);
        }

        // PRICE
        if ($request->filled('price')) {
            match ($request->price) {
                '0-500' => $query->having('rooms_min_price', '<=', 500000),
                '500-1000' => $query->havingBetween('rooms_min_price', [500000, 1000000]),
                '1000+' => $query->having('rooms_min_price', '>=', 1000000),
                default => null,
            };
        }

        // SORT
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
        return view('hotels.create');
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
        ]);

        // Upload thumbnail
        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')
                ->store('hotels/thumbnails', 'public');
        }

        // Upload multiple images
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('hotels/images', 'public');
            }
            $validated['images'] = $images;
        }

        Hotel::create($validated);

        return redirect()->route('hotels.index')->with('success', 'Hotel berhasil ditambahkan');
    }

    /**
     * Detail hotel
     */
    public function show(Hotel $hotel)
    {
        $hotel->load('rooms');
        return view('hotels.show', compact('hotel'));
    }

    /**
     * Form edit hotel
     */
    public function edit(Hotel $hotel)
    {
        return view('hotels.edit', compact('hotel'));
    }

    /**
     * Update hotel
     */
    public function update(Request $request, Hotel $hotel)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'address'       => 'required|string',
            'city'          => 'required|string|max:100',
            'star'          => 'required|integer|min:1|max:5',
            'latitude'      => 'nullable|numeric',
            'longitude'     => 'nullable|numeric',
            'thumbnail'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'images.*'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update thumbnail jika ada
        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')
                ->store('hotels/thumbnails', 'public');
        } else {
            $validated['thumbnail'] = $hotel->thumbnail;
        }

        // Gabungkan images lama + baru
        if ($request->hasFile('images')) {
            $images = $hotel->images ?? [];

            foreach ($request->file('images') as $image) {
                $images[] = $image->store('hotels/images', 'public');
            }

            $validated['images'] = $images;
        } else {
            $validated['images'] = $hotel->images;
        }

        $hotel->update($validated);

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
}

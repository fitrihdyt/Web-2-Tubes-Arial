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
    public function index()
    {
        $hotels = Hotel::latest()->get();
        return view('hotels.index', compact('hotels'));
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
        'address' => 'nullable|string',
        'city' => 'required|string|max:100',
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

    return redirect()
        ->route('hotels.index')
        ->with('success', 'Hotel berhasil ditambahkan');
}


    /**
     * Detail hotel
     */
    public function show(Hotel $hotel)
    {
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
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'address' => 'nullable|string',
        'city' => 'required|string|max:100',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
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

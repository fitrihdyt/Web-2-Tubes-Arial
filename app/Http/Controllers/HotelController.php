<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Validator;

class HotelController extends Controller
{

    /**
     * Import hotel data from CSV (Hotel Admin)
     */
    public function importCsv(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $file = fopen($request->file('file')->getRealPath(), 'r');

        $header = fgetcsv($file);

        $requiredHeaders = [
            'name', 'city', 'address', 'star', 'latitude', 'longitude'
        ];

        if ($header !== $requiredHeaders) {
            return back()->withErrors('Format CSV tidak sesuai');
        }

        $count = 0;

        while (($row = fgetcsv($file)) !== false) {
            [$name, $city, $address, $star, $latitude, $longitude] = $row;

            Hotel::create([
                'name' => $name,
                'city' => $city,
                'address' => $address,
                'star' => (int) $star,
                'latitude' => $latitude ?: null,
                'longitude' => $longitude ?: null,
            ]);

            $count++;
        }

        fclose($file);

        return redirect()
            ->route('hotels.index')
            ->with('success', "{$count} hotel berhasil di-import");
    }

    /**
     * Export hotel data to CSV (Hotel Admin)
     */
    public function exportCsv(): StreamedResponse
    {
        $user = auth()->user();
        $query = Hotel::query();

        if ($user->hotel_id) {
            $query->where('id', $user->hotel_id);
        }

        $hotels = $query->latest()->get();

        $filename = 'hotels_export_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($hotels) {
            $handle = fopen('php://output', 'w');

            // Header CSV
            fputcsv($handle, [
                'ID',
                'Nama Hotel',
                'Kota',
                'Alamat',
                'Bintang',
                'Latitude',
                'Longitude',
                'Created At',
            ]);

            // Rows
            foreach ($hotels as $hotel) {
                fputcsv($handle, [
                    $hotel->id,
                    $hotel->name,
                    $hotel->city,
                    $hotel->address,
                    $hotel->star,
                    $hotel->latitude,
                    $hotel->longitude,
                    optional($hotel->created_at)->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * List hotel
     */
    public function index(Request $request)
    {
        $query = Hotel::query()
            ->withMin('rooms', 'price')
            ->with('facilities');

        // SEARCH 
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                ->orWhere('city', 'ILIKE', "%{$search}%");
            });
        }

        if ($request->filled('star')) {
            $query->whereIn('star', $request->star);
        }

        // FILTER PRICE
        if ($request->filled('price')) {
            match ($request->price) {
                '0-500' =>
                    $query->whereHas('rooms', fn ($q) =>
                        $q->where('price', '<=', 500000)
                    ),

                '500-1000' =>
                    $query->whereHas('rooms', fn ($q) =>
                        $q->whereBetween('price', [500000, 1000000])
                    ),

                '1000+' =>
                    $query->whereHas('rooms', fn ($q) =>
                        $q->where('price', '>=', 1000000)
                    ),

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
        } else {
            $query->latest();
        }

        $hotels = $query->get();

        return view('hotels.index', compact('hotels'));
    }


    // Dashboard

    public function dashboard(Request $request)
    {
        $query = Hotel::query()
            ->withMin('rooms', 'price')
            ->with('facilities');

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                ->orWhere('city', 'ILIKE', "%{$search}%");
            });
        }

        if ($request->filled('star')) {
            $query->whereIn('star', $request->star);
        }

        if ($request->filled('price')) {
            match ($request->price) {
                '0-500' =>
                    $query->whereHas('rooms', function ($q) {
                        $q->where('price', '<=', 500000);
                    }),

                '500-1000' =>
                    $query->whereHas('rooms', function ($q) {
                        $q->whereBetween('price', [500000, 1000000]);
                    }),

                '1000+' =>
                    $query->whereHas('rooms', function ($q) {
                        $q->where('price', '>=', 1000000);
                    }),

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

    public function nearby(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $lat = $request->lat;
        $lng = $request->lng;

        $hotels = Hotel::query()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->select([
                'id',
                'name',
                'city',
                'latitude',
                'longitude',
            ])
            ->selectRaw(
                '(6371 * acos(
                    cos(radians(?)) *
                    cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) *
                    sin(radians(latitude))
                ))',
                [$lat, $lng, $lat]
            )
            ->withMin('rooms', 'price')
            ->whereRaw(
                '(6371 * acos(
                    cos(radians(?)) *
                    cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) *
                    sin(radians(latitude))
                )) <= ?',
                [$lat, $lng, $lat, 5] 
            )
            ->orderByRaw(
                '(6371 * acos(
                    cos(radians(?)) *
                    cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) *
                    sin(radians(latitude))
                ))',
                [$lat, $lng, $lat]
            )
            ->limit(6)
            ->get()
            ->map(fn ($hotel) => [
                'id' => $hotel->id,
                'name' => $hotel->name,
                'city' => $hotel->city,
                'latitude' => $hotel->latitude,
                'longitude' => $hotel->longitude,
                'min_price' => number_format($hotel->rooms_min_price ?? 0, 0, ',', '.'),
            ]);

        return response()->json($hotels);
    }



}

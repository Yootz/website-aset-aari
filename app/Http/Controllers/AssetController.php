<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AssetController extends Controller
{
    public function index(Request $request): View
    {
        $assetStatus = $request->validate([
            'a_status' => ['sometimes', 'nullable', Rule::in(['all', 'available', 'unavailable', 'pending', 'maintenance'])],
        ])['a_status'] ?? 'all';

        $assets = Asset::query()
            ->withCount('details')
            ->when($assetStatus !== 'all', fn ($query) => $query->whereRaw('LOWER(TRIM(a_status)) = ?', [$assetStatus]))
            ->orderBy('a_code')
            ->paginate(5)
            ->withQueryString();

        return view('asset.index', compact('assets', 'assetStatus'));
    }

    public function loadMore(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'a_status' => ['sometimes', 'nullable', Rule::in(['all', 'available', 'unavailable', 'pending', 'maintenance'])],
            'page' => ['sometimes', 'integer', 'min:1'],
        ]);
        $assetStatus = $validated['a_status'] ?? 'all';
        $page = $validated['page'] ?? 2;
        $assets = Asset::query()
            ->withCount('details')
            ->when($assetStatus !== 'all', fn ($query) => $query->whereRaw('LOWER(TRIM(a_status)) = ?', [$assetStatus]))
            ->orderBy('a_code')
            ->paginate(5, ['*'], 'page', $page);

        return response()->json([
            'data' => $assets->items(),
            'current_page' => $assets->currentPage(),
            'last_page' => $assets->lastPage(),
            'has_more' => $assets->hasMorePages(),
        ]);
    }

    public function report(Request $request): View
    {
        $assetStatus = $request->validate([
            'a_status' => ['sometimes', 'nullable', Rule::in(['all', 'available', 'unavailable', 'pending', 'maintenance'])],
        ])['a_status'] ?? 'all';
        $statusLabels = [
            'all' => 'Semua status',
            'available' => 'Available',
            'unavailable' => 'Unavailable',
            'pending' => 'Pending',
            'maintenance' => 'Maintenance',
        ];

        $assets = Asset::query()
            ->when($assetStatus !== 'all', fn ($query) => $query->whereRaw('LOWER(TRIM(a_status)) = ?', [$assetStatus]))
            ->orderBy('a_code')
            ->get(['a_code', 'a_name', 'a_status']);
        $assetStatusLabel = $statusLabels[$assetStatus];

        return view('laporan.aset', compact('assets', 'assetStatus', 'assetStatusLabel'));
    }

    public function maintenance(Asset $asset): RedirectResponse
    {
        $currentStatus = strtolower(trim((string) $asset->a_status));

        if (! in_array($currentStatus, ['available', 'maintenance'], true)) {
            return back()->withErrors([
                'asset' => 'Hanya aset available atau maintenance yang dapat diubah melalui tindakan ini.',
            ]);
        }

        $nextStatus = $currentStatus === 'maintenance' ? 'available' : 'maintenance';
        $asset->update(['a_status' => $nextStatus]);

        return back()->with('success', $nextStatus === 'maintenance'
            ? 'Aset berhasil diatur ke status maintenance.'
            : 'Aset berhasil diatur kembali menjadi available.');
    }

    public function create(): View
    {
        return view('asset.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'a_code' => ['required', 'string', 'max:255', 'unique:master_aset,a_code'],
            'a_name' => ['required', 'string', 'max:255'],
            'a_type' => ['required', 'string', 'max:255'],
            'a_desc' => ['required', 'string', 'max:255'],
            'a_status' => ['required', Rule::in(['available', 'unavailable', 'pending'])],
        ]);

        Asset::create($validated);

        return redirect()->route('asset.index')->with('success', 'Aset berhasil ditambahkan.');
    }

    public function show(Asset $asset): View
    {
        $asset->load(['details.peminjaman.employee']);

        return view('asset.show', compact('asset'));
    }

    public function lookup(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'a_code' => ['required', 'exists:master_aset,a_code'],
        ]);

        return redirect()->route('asset.show', $validated['a_code']);
    }
}

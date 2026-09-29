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
    public function index(): View
    {
        $assets = Asset::withCount('details')->orderBy('a_code')->paginate(5);

        return view('asset.index', compact('assets'));
    }

    public function loadMore(Request $request): JsonResponse
    {
        $page = $request->get('page', 2);
        $assets = Asset::withCount('details')->orderBy('a_code')->paginate(5, ['*'], 'page', $page);

        return response()->json([
            'data' => $assets->items(),
            'current_page' => $assets->currentPage(),
            'last_page' => $assets->lastPage(),
            'has_more' => $assets->hasMorePages(),
        ]);
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
            'a_status' => ['required', Rule::in(['available', 'unavailable'])],
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

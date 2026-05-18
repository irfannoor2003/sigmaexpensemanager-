<?php

namespace App\Http\Controllers;

use App\Models\DiaryProfile;
use App\Models\DiaryEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DiaryProfileExport;

class DiaryController extends Controller
{
    /**
     * Display a listing of diary profiles.
     */
    public function index()
    {
        $profiles = DiaryProfile::where('user_id', auth()->id())
            ->withCount('entries')
            ->withSum(['entries' => function ($query) {
                $query->where('is_cleared', false);
            }], 'price')
            ->latest()
            ->paginate(15);

        return view('manager.diary.index', compact('profiles'));
    }

    /**
     * Store a newly created profile in storage.
     */
    public function storeProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        DiaryProfile::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return back()->with('success', __('Profile created successfully!'));
    }

    /**
     * Update the specified profile.
     */
    public function updateProfile(Request $request, DiaryProfile $profile)
    {
        if ($profile->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $profile->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return back()->with('success', __('Profile updated successfully!'));
    }

    /**
     * Display the specified profile and its entries.
     */
    public function show(Request $request, DiaryProfile $profile)
    {
        if ($profile->user_id !== auth()->id()) {
            abort(403);
        }

        $query = $profile->entries();

        if ($request->status === 'cleared') {
            $query->where('is_cleared', true);
        } elseif ($request->status === 'pending') {
            $query->where('is_cleared', false);
        }

        if ($request->month) {
            $date = Carbon::parse($request->month);
            $query->whereMonth('created_at', $date->month)
                  ->whereYear('created_at', $date->year);
        }

        $entries = $query->latest()->paginate(15)->withQueryString();

        return view('manager.diary.show', compact('profile', 'entries'));
    }

    /**
     * Store a newly created diary entry.
     */
    public function storeEntry(Request $request, DiaryProfile $profile)
    {
        if ($profile->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('diary', 'public');
        }

        DiaryEntry::create([
            'diary_profile_id' => $profile->id,
            'title' => $request->title,
            'price' => $request->price,
            'image' => $imagePath,
        ]);

        return back()->with('success', __('Entry added successfully!'));
    }

    /**
     * Toggle the cleared status of an entry.
     */
    public function toggleClearEntry(DiaryEntry $entry)
    {
        if ($entry->profile->user_id !== auth()->id()) {
            abort(403);
        }

        $entry->is_cleared = !$entry->is_cleared;
        $entry->save();

        return back()->with('success', $entry->is_cleared ? __('Entry marked as cleared!') : __('Entry marked as outstanding!'));
    }

    /**
     * Update the specified diary entry.
     */
    public function updateEntry(Request $request, DiaryEntry $entry)
    {
        if ($entry->profile->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($entry->image) {
                Storage::disk('public')->delete($entry->image);
            }
            $entry->image = $request->file('image')->store('diary', 'public');
        }

        $entry->title = $request->title;
        $entry->price = $request->price;
        $entry->save();

        return back()->with('success', __('Entry updated successfully!'));
    }

    /**
     * Remove the specified diary entry.
     */
    public function destroyEntry(DiaryEntry $entry)
    {
        if ($entry->profile->user_id !== auth()->id()) {
            abort(403);
        }

        if ($entry->image) {
            Storage::disk('public')->delete($entry->image);
        }

        $entry->delete();

        return back()->with('success', __('Entry deleted successfully!'));
    }

    /**
     * Export diary entries for a specific profile.
     */
    public function export(Request $request, DiaryProfile $profile)
    {
        if ($profile->user_id !== auth()->id()) {
            abort(403);
        }

        $status = $request->status;
        $month = null;
        $year = null;

        if ($request->month) {
            $date = Carbon::parse($request->month);
            $month = $date->month;
            $year = $date->year;
        }

        $fileName = 'diary_' . str_replace(' ', '_', $profile->name) . '_' . ($status ?? 'all') . '_' . ($request->month ?? 'all_time') . '.xlsx';
        
        return Excel::download(new DiaryProfileExport($profile, $status, $month, $year), $fileName);
    }
}

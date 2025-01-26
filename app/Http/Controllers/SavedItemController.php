<?php

namespace App\Http\Controllers;

use App\Models\SavedItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedItemController extends Controller
{
    public function index()
    {
        $savedPlaces = SavedItem::where('user_id', Auth::id())
            ->where('type', 'place')
            ->latest()
            ->get();

        $savedEvents = SavedItem::where('user_id', Auth::id())
            ->where('type', 'event')
            ->with('event') // Eager load event relationship
            ->latest()
            ->get();

        return view('saved-items.index', compact('savedPlaces', 'savedEvents'));
    }

    public function destroy($id)
    {
        $savedItem = SavedItem::where('user_id', Auth::id())->findOrFail($id);
        $savedItem->delete();

        return back()->with('success', 'Item removed from saved items.');
    }
}

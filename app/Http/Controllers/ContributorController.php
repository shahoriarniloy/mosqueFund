<?php

namespace App\Http\Controllers;

use App\Models\Contributor;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ContributorController extends Controller implements HasMiddleware
{
    
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

   
    public function index(Request $request)
    {
        $query = Contributor::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        
        $contributors = $query->orderBy($sortField, $sortDirection)
            ->paginate(15)
            ->withQueryString();
        
        return view('contributors.index', compact('contributors'));
    }

   
    public function show(Contributor $contributor)
    {
        // Get donations for this contributor
        $donations = Donation::where('contributor_id', $contributor->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('contributors.show', compact('contributor', 'donations'));
    }

   
    public function edit(Contributor $contributor)
    {
        return view('contributors.edit', compact('contributor'));
    }

    
    public function update(Request $request, Contributor $contributor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:contributors,phone,' . $contributor->id,
        ]);

        $contributor->update($validated);

        return redirect()->route('contributors.show', $contributor)
            ->with('success', 'Contributor updated successfully.');
    }

  
    public function destroy(Contributor $contributor)
    {
        $donationCount = Donation::where('contributor_id', $contributor->id)->count();
        
        if ($donationCount > 0) {
            return back()->with('error', 'Cannot delete contributor with existing donations.');
        }

        $contributor->delete();

        return redirect()->route('contributors.index')
            ->with('success', 'Contributor deleted successfully.');
    }
}
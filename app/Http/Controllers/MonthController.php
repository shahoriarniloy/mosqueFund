<?php
namespace App\Http\Controllers;

use App\Models\Month;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class MonthController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
        ];
    }

    /**
     * Display a listing of months.
     */
    public function index(Request $request)
    {
        $query = Month::query();

        // Filter by year
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $months = $query->orderBy('year', 'desc')
                       ->orderByRaw("FIELD(name, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')")
                       ->paginate(12);

        // Get available years for filter
        $years = Month::select('year')->distinct()->orderBy('year', 'desc')->pluck('year');

        return view('months.index', compact('months', 'years'));
    }

    /**
     * Show the form for creating a new month.
     */
    public function create()
    {
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        
        return view('months.create', compact('months'));
    }

    /**
     * Store a newly created month in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|in:January,February,March,April,May,June,July,August,September,October,November,December',
            'year' => 'required|integer|min:2020|max:2100',
            'status' => 'required|in:active,inactive'
        ]);

        // Check if month for this year already exists
        $exists = Month::where('name', $validated['name'])
            ->where('year', $validated['year'])
            ->exists();
            
        if ($exists) {
            return back()->withErrors(['name' => 'This month for the selected year already exists.'])
                ->withInput();
        }

        Month::create($validated);

        return redirect()->route('months.index')
            ->with('success', 'Month created successfully.');
    }

    /**
     * Show the form for editing the specified month.
     */
    public function edit(Month $month)
    {
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        
        return view('months.edit', compact('month', 'months'));
    }

    /**
     * Update the specified month in storage.
     */
    public function update(Request $request, Month $month)
    {
        $validated = $request->validate([
            'name' => 'required|string|in:January,February,March,April,May,June,July,August,September,October,November,December',
            'year' => 'required|integer|min:2020|max:2100',
            'status' => 'required|in:active,inactive'
        ]);

        // Check if another month exists with same name and year
        $exists = Month::where('name', $validated['name'])
            ->where('year', $validated['year'])
            ->where('id', '!=', $month->id)
            ->exists();
            
        if ($exists) {
            return back()->withErrors(['name' => 'This month for the selected year already exists.'])
                ->withInput();
        }

        $month->update($validated);

        return redirect()->route('months.index')
            ->with('success', 'Month updated successfully.');
    }

    /**
     * Remove the specified month from storage.
     */
    public function destroy(Month $month)
    {
        // Check if month has transactions
        if ($month->transactions()->count() > 0) {
            return back()->with('error', 'Cannot delete month with existing transactions.');
        }

        $month->delete();
        
        return redirect()->route('months.index')
            ->with('success', 'Month deleted successfully.');
    }

    /**
     * Toggle month status
     */
    public function toggleStatus(Month $month)
    {
        $month->status = $month->status === 'active' ? 'inactive' : 'active';
        $month->save();

        return back()->with('success', 'Month status updated successfully.');
    }
}
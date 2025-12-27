<?php

namespace ME\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use ME\Http\Controllers\Controller;

class DataController extends Controller
{
    public function __construct()
    {
        $this->middleware('authorization:me.clearData')->only(['clearData', 'clearDataForm']);
        $this->middleware('authorization:me.dashboard')->only(['index']);
    }

    public function index()
    {
        return view('me::dashboard');
    }

    public function clearDataForm()
    {
        return view('me::settings.clear');
    }

    public function clearData(Request $request)
    {
        $request->validate([
            'confirm_text' => 'required|in:CLEAR ALL DATA',
        ]);

        try {
            // Preserve these tables (won’t be deleted)
            $preserveTables = [
                'users', 
                'roles', 
                'role_user',
                'migrations', // optional, can be removed if needed
            ];

            // Get all table names dynamically
            $allTables = collect(DB::select('SHOW TABLES'))
                ->map(function ($table) {
                    $property = 'Tables_in_' . DB::getDatabaseName();
                    return $table->$property;
                })
                ->filter(function ($table) use ($preserveTables) {
                    return !in_array($table, $preserveTables);
                })
                ->values();

            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            foreach ($allTables as $table) {
                try {
                    DB::table($table)->truncate();
                } catch (\Exception $e) {
                    // কিছু টেবিল truncate করা না গেলে delete fallback
                    try {
                        DB::table($table)->delete();
                    } catch (\Exception $e2) {
                        // still skip silently
                    }
                }
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            return redirect()->back()->with('success', 'All data cleared successfully. Users and roles are preserved.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error clearing data: ' . $e->getMessage());
        }
    }

    public function changeLocale($locale = 'en')
    {
        session(['locale' => $locale]);
        app()->setLocale($locale);
        return redirect()->back();
    }
}

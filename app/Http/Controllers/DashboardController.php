<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Redirect or load role-specific dashboards.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return app(AdminDashboardController::class)->index();
        }

        if ($user->isSeller()) {
            // Check approval status
            if ($user->status !== 'approved') {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Your merchant store registration is currently pending administrator verification.');
            }
            return app(SellerDashboardController::class)->index();
        }

        // Default is Farmer
        return app(FarmerDashboardController::class)->index();
    }
}

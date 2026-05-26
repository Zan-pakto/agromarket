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
            return app(SellerDashboardController::class)->index();
        }

        // Default is Farmer
        return app(FarmerDashboardController::class)->index();
    }
}

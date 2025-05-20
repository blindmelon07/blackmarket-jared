<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SellerApplication;
class SellerController extends Controller
{
    public function show()
    {
        // Prevent duplicate application
        $application = SellerApplication::where('user_id', auth()->id())->latest()->first();

        return view('frontend.become-seller', compact('application'));
    }

    public function apply(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        // Prevent duplicate application
        if (SellerApplication::where('user_id', auth()->id())->where('status', 'pending')->exists()) {
            return redirect()->back()->with('error', 'You have already applied. Please wait for admin review.');
        }

        SellerApplication::create([
            'user_id' => auth()->id(),
            'business_name' => $request->business_name,
            'phone' => $request->phone,
        ]);

        return redirect()->route('welcome')->with('success', 'Application submitted! Please wait for admin approval.');
    }
}
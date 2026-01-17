<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ParentSettingsController extends Controller
{
    /**
     * Show parent settings page
     */
    public function index()
    {
        $user = Auth::user();
        return view('parent.settings', compact('user'));
    }
    
    /**
     * Update parent settings
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);
        
        if ($validator->fails()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }
        
        $user->update($validator->validated());
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Settings updated successfully',
                'user' => $user
            ]);
        }
        
        return redirect()->route('parent.settings')->with('success', 'Settings updated successfully');
    }
}

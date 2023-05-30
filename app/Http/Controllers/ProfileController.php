<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        return view('pages.profile.index', [
            'title' => 'Account', 'user' => auth()->user(),
        ]);
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|String|max:255',
        ]);

        User::where('id', auth()->user()->id)->update($validatedData);

        return redirect()->route('user');
    }
}

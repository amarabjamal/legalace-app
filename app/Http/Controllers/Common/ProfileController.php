<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\IDType;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ProfileController extends Controller
{

    public function indexAdmin()
    {
        $user = Auth::user();
        $user->company;

        return Inertia::render('Admin/Profile/Show', [
            'user' => $user,
        ]);
    }

    public function indexLawyer()
    {
        $user = Auth::user();
        $user->company;

        return Inertia::render('Lawyer/Profile/Show', [
            'user' => $user,
        ]);
    }

    public function editAdmin()
    {
        $user = Auth::user();
        $user->company;

        return Inertia::render('Admin/Profile/Edit', [
            'user' => $user,
        ]);
    }

    public function updateAdmin(Request $request)
    {
        $user = Auth::user();

        // Validate the request
        $validated = $request->validate([
            'name' => 'required',
            'email' => ['required', 'email', Rule::unique('users')->where(fn($query) => $query->where([['id', '!=', $user->id]]))],
            'id_type_id' => ['required', 'exists:id_types,id'],
            'id_number' => ['required', $request->id_type_id == 1 ? 'regex:/^\d{6}-\d{2}-\d{4}$/' : null, Rule::unique('users')->where(fn($query) => $query->where([['company_id', '=', $user->company_id], ['id', '!=', $user->id]]))],
            'employee_id' => ['required', Rule::unique('users')->where(fn($query) => $query->where([['company_id', '=', $user->company_id], ['id', '!=', $user->id]]))],
            'contact_number' => ['required', 'min:9', 'numeric'],
            'birthdate' => ['required', 'date'],
            'is_active' => ['required', 'boolean'],
            'access_expiry_date' => ['nullable', 'date'],
        ]);

        // Update the user
        $user->update($validated);

        return redirect()->route('admin.profile.indexAdmin')->with('successMessage', 'Successfully updated your profile.');
    }
}

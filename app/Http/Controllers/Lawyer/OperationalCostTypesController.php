<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OperationalCostTypes;
use Inertia\Inertia;

class OperationalCostTypesController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        // Create a new cost type
        OperationalCostTypes::create([
            'name' => $request->name,
            'description' => $request->description,
            'enabled' => true, // Assuming 'enabled' is a required field
        ]);

        // Return a success response
        return redirect()->back()->with('successMessage', 'Cost type added successfully.');
    }
}

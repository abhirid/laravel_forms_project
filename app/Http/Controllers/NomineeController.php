<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Nominee;
use App\Models\Beneficiary;


class NomineeController extends Controller
{
    public function create($beneficiary_id)
    {
        // Remove this line: $beneficiary_id = Session::get('beneficiary_id');
        // Uncomment the next line for debugging purposes:
        // dd(compact('beneficiary_id'));
        return view('nominee', compact('beneficiary_id'));
    }

    public function store(Request $request, $beneficiary_id)
    {
        $request->validate([
            'full_name' => 'required|string|min:3|max:10',
            'has_account' => 'required|boolean',
            'bank_name' => 'nullable|required_if:has_account,0|string',
            'account_number' => 'required|digits_between:8,24|numeric|same:account_number_confirmation',
        ]);
        // dd($request);
        $beneficiary = Beneficiary::findOrFail($beneficiary_id);
        $nominee = new Nominee();
        $nominee->beneficiary_id = $beneficiary->id; // Update this line
        $nominee->full_name = $request->input('full_name');
        $nominee->has_account = $request->input('has_account');
        $nominee->bank_name = $request->input('bank_name');
        $nominee->account_number = $request->input('account_number');

        $nominee->save();

        return redirect()->back()->with(['alert-type' => "success", 'success' => 'Nominee added successfully!']);
    }
}

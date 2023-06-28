<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Nominee;
use App\Models\Beneficiary;
use Illuminate\Support\Facades\Session;



class NomineeController extends Controller
{

    public function create($beneficiary_id)
    {
        $beneficiary_id = Session::get('beneficiary_id');
        // dd(compact('beneficiary_id'));
        return view('nominee', compact('beneficiary_id'));
    }

    public function store(Request $request, $beneficiary_id)
    {

        // dd($request);
        $request->validate([
            'full_name' => 'required|string|min:3|max:40',
            'has_account' => 'required|boolean',
            'bank_name' => 'nullable|required_if:has_account,0|string',
           // 'account_number' => 'required|digits_between:8,24|numeric|confirmed',
           'account_number' => 'required|digits_between:8,24|numeric|same:account_number_confirmation',
        ]);

        $beneficiary = Beneficiary::findOrFail($beneficiary_id);
        $nominee = new Nominee();
        $nominee->beneficiary_id = $beneficiary_id;
        $nominee->full_name = $request->input('full_name');
        $nominee->has_account = $request->input('has_account');
        $nominee->bank_name = $request->input('bank_name');
        $nominee->account_number = $request->input('account_number');

        // Save the nominee to the database
        $nominee->save();


        // Redirect or show success message
        return redirect()->back()->with('success', 'Nominee added successfully!');
    }
}

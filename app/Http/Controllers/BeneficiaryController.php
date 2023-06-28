<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Beneficiary;
use Illuminate\Support\Facades\Session;

class BeneficiaryController extends Controller
{
    public function create()
    {
        return view('beneficiary');
    }
    function store(Request $request)
{
    $validatedData = $request->validate([
        'first_name' => 'required|min:3|max:10',
        'last_name' => 'required|min:3|max:10',
        'image' => 'required|image|max:512|mimes:png,jpg,jpeg',
        'has_aadhaar' => 'required',
        'aadhaar_number' => 'nullable|required_if:has_aadhaar,Yes|digits:12',
    ]);

    $beneficiary = new Beneficiary();
    $beneficiary->first_name = $validatedData['first_name'];
    $beneficiary->last_name = $validatedData['last_name'];

    $imagePath = $request->file('image')->store('beneficiary_images');
    $beneficiary->image = $imagePath;

    $beneficiary->has_aadhaar = $validatedData['has_aadhaar'] === 'Yes';

    if ($beneficiary->has_aadhaar) {
        $beneficiary->aadhaar_number = $validatedData['aadhaar_number'];
    }
    $beneficiary->save();
    Session::put('beneficiary_id', $beneficiary->id);

    return redirect()->route('nominee', ['id' => $beneficiary->id])->with('success', 'Beneficiary added successfully!');
   // return Redirect::route('nominee', ['id' => $beneficiary->id])->with('success', 'Beneficiary added successfully!');
    //return redirect()->route('nominee.create', ['id' => $beneficiary->id]);


}
}

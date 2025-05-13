<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactSale;
use Illuminate\Support\Facades\Validator;

class ContactSaleController extends Controller
{
    /**
     * Store a new contact sales entry.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'email'          => 'required|email|max:255',
            'phone'          => 'nullable|string|max:50',
            'country'        => 'nullable|string|max:100',
            'company_name'   => 'nullable|string|max:255',
            'searching_for'  => 'required|in:myself,client',
            'message'        => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $contact = ContactSale::create($request->all());

        return response()->json([
            'message' => 'Contact sale entry created successfully.',
            'data'    => $contact
        ], 201);
    }
}

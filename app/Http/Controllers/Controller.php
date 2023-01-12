<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController {

    public function validatePayload(Request $request) {

        // dd("I got here");
        $rules = [];
        $validationMessages = [];
        $payload = $request->all();

        foreach ($payload as $key => $value) {
            $rules[$key] = $value['rules'];
            $validationMessages[$key . '.alpha'] = 'The ' . $key . ' field must be a string.';
            $validationMessages[$key . '.required'] = 'The ' . $key . ' field is required.';
            $validationMessages[$key . '.email'] = 'The ' . $key . ' field must be a valid email address.';
            $validationMessages[$key . '.number'] = 'The ' . $key . ' field must be a number.';
        }

        $validator = Validator::make($payload, $rules, $validationMessages);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        } else {
            return response()->json(['status' => true]);
        }
    }
}

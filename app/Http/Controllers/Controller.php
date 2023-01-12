<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController {
    public function validatePayload(Request $request) {
        $payload = $request->all();
        $validationErrors = [];
        $validationRules = [
            'first_name' => 'alpha|required',
            'last_name' => 'alpha|required',
            'email' => 'email',
            'phone' => 'number'
        ];

        foreach ($validationRules as $field => $rules) {
            $fieldValue = $payload[$field];
            $rules = explode("|", $rules);

            foreach ($rules as $rule) {
                if ($rule === 'required' && empty($fieldValue)) {
                    $validationErrors[$field][] = 'The ' . $field . ' field is required.';
                }
                if ($rule === 'alpha' && !preg_match("/^[A-Za-z]+$/", $fieldValue)) {
                    $validationErrors[$field][] = 'The ' . $field . ' field must be alphabetic.';
                }
                if ($rule === 'email' && !filter_var($fieldValue, FILTER_VALIDATE_EMAIL)) {
                    $validationErrors[$field][] = 'The ' . $field . ' field must be a valid email address.';
                }
                if ($rule === 'number' && !is_numeric($fieldValue)) {
                    $validationErrors[$field][] = 'The ' . $field . ' field must be a number.';
                }
            }
        }

        //check if the validation error is empty i.e. the validation fails
        if (!empty($validationErrors)) {
            return response()->json([
                'status' => false,
                'errors' => $validationErrors
            ], 400);
        }

        return response()->json([
            'status' => true
        ], 200);
    }
}

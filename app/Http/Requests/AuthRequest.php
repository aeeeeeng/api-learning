<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AuthRequest extends FormRequest
{

    protected $stopOnFirstFailure = false;
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $routeName = Route::currentRouteName();
        $rules = [];
        switch ($routeName) {
            case 'auth.register':
                $rules = [
                    'name'      => 'required',
                    'email'     => 'required|email|unique:users',
                    'password'  => 'required|min:8|confirmed'
                ];
                break;
            case 'auth.login':
                $rules = [
                    'email' => 'required|email',
                    'password' => 'required'
                ];
                break;
            default:
                $rules = [];
                break;
        }
        return $rules;
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'data'    => $validator->errors()
        ], 422));
    }
}

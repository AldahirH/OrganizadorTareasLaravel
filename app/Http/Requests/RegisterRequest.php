<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest{
    public function authorize(): bool{
        return true; 
    } 

    public function rules():array{
        return[
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:72', 'confirmed'],
        ];
    }

    public function messages(): array{
        return[
            'name.required'      => 'El nombre es obligatorio.',
            'name.string'        => 'El nombre debe ser texto.',
            'name.max'           => 'El nombre no puede superar los 255 caracteres.',
            'email.required'     => 'El correo es obligatorio.',
            'email.string'       => 'El correo debe ser texto.',
            'email.email'        => 'Escribe un correo valido.',
            'email.max'          => 'El correo no puede superar los 255 caracteres.',
            'email.unique'       => 'Este correo ya esta registrado.',
            'password.required'  => 'La contrasena es obligatoria.',
            'password.string'    => 'La contrasena debe ser texto.',
            'password.min'       => 'La contrasena debe tener al menos 8 caracteres.',
            'password.max'       => 'La contrasena no puede superar los 72 caracteres.',
            'password.confirmed' => 'Las contrasenas no coinciden.',
        ];
    }
}
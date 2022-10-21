<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Email\StoreRequest;
use App\Models\Email;
use Illuminate\Http\JsonResponse;

class EmailController extends Controller
{
    public function store(StoreRequest $request): JsonResponse
    {
        Email::paramCreate($request->email);

        return new JsonResponse(['success' =>true, 'message' => 'Email created successfully']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Enumerators\KangarooEnumerator;
use App\Models\Kangaroo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class KangarooController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $kangaroos = Kangaroo::all();
        return response()->json($kangaroos, 200);
    }

    public function show(Kangaroo $kangaroo): JsonResponse
    {
        return $this->sendResponse($kangaroo, "Kangaroo retrieved successfully.");
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                Rule::unique('kangaroos', 'name')->where(function ($query) {
                    $query->whereNull('deleted_at');
                }),
            ],
            'nickname' => 'nullable|string',
            'weight' => 'required|numeric|min:0',
            'height' => 'required|numeric|min:0',
            'gender' => 'required|in:' . implode(',', KangarooEnumerator::GENDERS),
            'color' => 'nullable|string',
            'friendliness' => 'required|in:' . implode(',', KangarooEnumerator::FRIENDLINESS),
            'birthday' => 'required|date|before_or_equal:today'
        ]);

        if ($validator->fails()) {
            return $this->sendError('The given data was invalid.', $validator->errors(), 422);
        }
        $kangaroo = Kangaroo::create($request->all());
        return $this->sendResponse($kangaroo, "Kangaroo added successfully.");
    }

    public function update(Request $request, Kangaroo $kangaroo): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                Rule::unique('kangaroos', 'name')->where(function ($query) {
                    $query->whereNull('deleted_at');
                })->ignore($kangaroo),
            ],
            'nickname' => 'nullable|string',
            'weight' => 'required|numeric|min:0',
            'height' => 'required|numeric|min:0',
            'gender' => 'required|in:' . implode(',', KangarooEnumerator::GENDERS),
            'color' => 'nullable|string',
            'friendliness' => 'required|in:' . implode(',', KangarooEnumerator::FRIENDLINESS),
            'birthday' => 'required|date|before_or_equal:today'
        ]);

        if ($validator->fails()) {
            return $this->sendError('The given data was invalid.', $validator->errors(), 422);
        }
        $kangaroo->update($request->all());
        return $this->sendResponse($kangaroo, "Kangaroo updated successfully.");
    }

    public function destroy(Kangaroo $kangaroo): JsonResponse
    {
        $kangaroo->delete();
        return $this->sendResponse($kangaroo, "Kangaroo deleted successfully.");
    }
}

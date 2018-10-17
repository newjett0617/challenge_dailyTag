<?php

namespace App\Http\Controllers;

use App\Tag;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($request->query('day') == 'today') {
            $today = today()->format('Y-m-d');
            $tags = Tag::select(['tag'])->where('user_id', $user['id'])->where('created_at', 'like', $today . '%')->get();
            return response()->json([
                'today' => $today,
                'tags' => $tags
            ]);
        } else if ($request->query() == null) {
            $tags = Tag::select(['tag'])->where('user_id', $user['id'])->get();
            return response()->json([
                'tags' => $tags
            ]);
        } else {
            return response()->json([
                'status' => 'QueryString Error'
            ]);
        }
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $rules = [
            'api_token' => 'required',
            'tags' => 'required|max:255'
        ];
        $messages = [
            'required' => ':attribute 欄位必填',
            'tags.max' => ':attribute 長度限制 :max'
        ];
        $validator = Validator::make($input, $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ]);
        }

        $user = User::where('api_token', $input['api_token'])->first();
        $tags = explode(',', $input['tags']);

        foreach ($tags as $tag) {
            if ($tag != '') {
                Tag::create([
                    'user_id' => $user['id'],
                    'tag' => $tag
                ]);
            }
        }
        return response()->json([
            'status' => 'tag success'
        ]);
    }
}
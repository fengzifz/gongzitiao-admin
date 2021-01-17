<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function index(Request $request)
    {
        $users = User::where('type', User::TYPE_WECHAT)
            ->paginate(30);
        return view('user.index', compact('users'));
    }

    public function disabled(Request $request)
    {
        $user = User::find($request->id);
        if (!$user) {
            abort(404);
        }

        $user->status = User::STATUS_DISABLED;
        $user->save();

        return redirect()->back()->with(['status' => 1, 'msg' => '已锁定']);
    }

    public function enabled(Request $request)
    {
        $user = User::find($request->id);
        if (!$user) {
            abort(404);
        }

        $user->status = User::STATUS_ENABLED;
        $user->save();

        return redirect()->back()->with(['status' => 1, 'msg' => '已解锁']);
    }
}

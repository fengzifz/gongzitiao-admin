<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    //
    public function index(Request $request)
    {
        $params = $request->all();
        $query = User::where([]);

        if ($request->has('username')) {
            $username = $request->username;
            $query->where('username', 'like', "%$username%");
        }

        $users = $query->where('type', User::TYPE_WECHAT)
            ->paginate(30);
        return view('user.index', compact('users', 'params'));
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

    public function removeOpenid(Request $request)
    {
        if (!$request->has('id')) {
            abort(404);
        }

        $user = User::find($request->id);
        $user->openid = '';
        $user->save();

        return redirect()->back()->with(['status' => 1, 'msg' => '解绑成功']);
    }

    public function edit(Request $request)
    {
        if (!$request->has('id')) {
            abort(404);
        }

        $user = User::find($request->id);

        return view('user.edit', compact('user'));
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'username' => 'required',
            'phone' => 'required|digits:11|unique:users,phone,' . $request->id,
        ], [
            'username.required' => '用户名不能为空',
            'phone.required' => '电话不能为空',
            'phone.digits' => '电话的长度必须为 11 位',
            'phone.unique' => '电话已经存在，请重试'
        ]);

        if ($validation->fails()) {
            return redirect()->back()->with(['status' => 2, 'msg' => $validation->errors()->first()])->withInput();
        }

        $user = User::find($request->id);
        $user->username = $request->username;
        $user->phone = $request->phone;
        $user->status = $request->status;
        $user->save();

        return redirect()->back()->with(['status' => 1, 'msg' => '用户信息保存成功']);
    }

    public function changePwd(Request $request)
    {
        $user = Auth::user();
        return view('user.change_pwd', compact('user'));
    }

    public function storePwd(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'password' => 'required|confirmed|min:6'
        ], [
            'password.required' => '密码不能为空',
            'password.min' => '密码最少 6 位',
            'password.confirmed' => '两次输入的密码不一致'
        ]);

        if ($validation->fails()) {
            return redirect()->back()->with(['status' => 2, 'msg' => $validation->errors()->first()]);
        }

        $user = User::find($request->id);
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->back()->with(['status' => 1, 'msg' => '密码更新成功']);
    }
}

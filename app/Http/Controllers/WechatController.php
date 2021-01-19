<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\SalaryField;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WechatController extends Controller
{
    /**
     * 验证登录状态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        if (empty($request->code)) {
            return response()->json(['err' => 1, 'msg' => '参数 code 缺失']);
        }

        $data = [];

        $app = app('wechat.mini_program');
        $res = $app->auth->session($request->code);

        // 查找用户 openid 是否存在
        $openid = $res['openid'];
        $data['openid'] = $openid;
        $data['verify'] = 0;

        // 如果查找 openid 用户存在，则用户已经绑定
        $user = User::where('openid', $openid)
            ->get()->first();

        // verify: 0 未绑定，1 已绑定，2 禁止用户进入小程序
        if ($user) {
            if ($user->status == User::STATUS_ENABLED) {
                $data['verify'] = 1;
            } else {
                $data['verify'] = 2;
            }
        }

        return response()->json(['err' => 0, 'msg' => 'ok', 'data' => $data]);
    }

    /**
     * 解绑用户
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unbindUser(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'openid' => 'required'
        ], [
            'openid.required' => '参数 openid 有误，请联系开发'
        ]);

        if ($validation->fails()) {
            return response()->json(['err' => 1, 'msg' => $validation->errors()->first()]);
        }

        $user = User::where('openid', $request->openid)
            ->get()->first();

        $user->openid = '';
        $user->save();

        return response()->json(['err' => 0, 'msg' => 'ok']);
    }

    /**
     * 绑定用户
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bindUser(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'openid' => 'required'
        ], [
            'name.required' => '姓名不能为空',
            'phone.required' => '手机号码不能为空',
            'openid.required' => '参数 openid 不存在，请联系开发人员'
        ]);

        if ($validation->fails()) {
            $msg = $validation->errors()->first();
            return response()->json(['err' => 1, 'msg' => $msg]);
        }

        // 用户绑定逻辑
        // 1. 先查询 name + phone，看用户是否存在，不存在，就创建用户
        // 2. name + phone 用户存在时，对比 openid 是否一样，不一样就提示用户已经绑定，联系管理员解绑
        $name = trim($request->name);
        $phone = trim($request->phone);
        $user = User::where('username', $name)
            ->where('phone', $phone)
            ->get()->first();
        $id = null;

        // 1.
        if (!$user) {
            $newUser = new User();
            $newUser->username = $name;
            $newUser->phone = $phone;
            $newUser->type = User::TYPE_WECHAT;
            $newUser->openid = $request->openid;
            $newUser->email = $this->generateRandomString(6) . '@test.com';
            $newUser->password = bcrypt($phone);
            $newUser->save();
            $id = $newUser->id;
        } else {
            // 2.
            // 检查用户 openid 是否和数据库的一样
            if (!empty($user->openid)) {
                if ($user->openid != $request->openid) {
                    return response()->json(['err' => 1, 'msg' => '用户已经绑定，请先联系管理员解绑']);
                }
            } else {
                // openid 为空时，直接保存 openid
                $user->openid = $request->openid;
                $user->save();
                $id = $user->id;
            }




        }

        $data = [
            'truename' => $name,
            'id' => $id,
            'verify' => 1
        ];

        return response()->json(['err' => 0, 'msg' => 'ok', 'data' => $data]);
    }

    /**
     * 获取员工工资
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSalary(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'openid' => 'required'
        ], [
            'openid.required' => '参数 openid 有误，请联系开发人员'
        ]);

        if ($validation->fails()) {
            return response()->json(['err' => 1, 'msg' => $validation->errors()->first()]);
        }

        $user = User::where('openid', $request->openid)
            ->orderByDesc('created_at')
            ->get()->first();

        if (!$user) {
            return response()->json(['err' => 1, 'msg' => '用户不存在']);
        }

        $salaries = Salary::where('a1', $user->username)
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->limit(3) // 只获取前 3 个月的工资
            ->get();

        $data = [];

        if ($salaries->count() > 0) {
            foreach ($salaries as $k => $v) {

                $month = $v->month;
                $year = $v->year;

                $fields = SalaryField::where('month', $month)
                    ->where('year', $year)
                    ->get();

                $salary = [];
                foreach ($fields as $key => $val) {
                    $salary[$val->col_name] = $v[$val->field_key];
                }
                $salary['id'] = $v->id;

                array_push($data, $salary);
            }
        }


        return response()->json(['err' => 0, 'msg' => 'ok', 'data' => $data]);

    }

    /**
     * 工资详细
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function salaryDetails(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'required',
            'openid' => 'required'
        ], [
            'id.required' => '参数 ID 错误，请联系开发',
            'openid.required' => '参数 openid 错误，请联系开发'
        ]);

        if ($validation->fails()) {
            return response()->json(['err' => 1, 'msg' => $validation->errors()->first()]);
        }

        $user = User::where('openid', $request->openid)
            ->get()->first();

        if (!$user) {
            return response()->json(['err' => 1, 'msg' => 'Permission denied(403).']);
        }

        $salary = Salary::find($request->id);
        $fields = SalaryField::where('year', $salary->year)
            ->where('month', $salary->month)
            ->get();

        $data = [];
        foreach ($fields as $k => $v) {
            $data[][$v->col_name] = $salary[$v->field_key];
        }

        return response()->json(['err' => 0, 'msg' => 'ok', 'data' => $data]);
    }

    /**
     * 随机字符串
     * @param int $length
     * @return string
     */
    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}

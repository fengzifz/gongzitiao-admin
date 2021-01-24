<?php

namespace App\Http\Controllers;

use App\Imports\SalaryImport;
use App\Models\Salary;
use App\Models\SalaryField;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $date = Carbon::now()->subDays(30);
        $year = $date->year;
        $month = $date->month;
        return view('salary.index', compact('month', 'year'));
    }

    /**
     * 导入工资
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function import(Request $request)
    {
        if (!$request->hasFile('file')) {
            return redirect()->back()->with(['status' => 2, 'msg' => '请上传 excel 文件']);
        }

        $file = $request->file('file');

        if (!in_array($file->getClientOriginalExtension(), ['xlsx', 'xls'])) {
            return redirect()->back()->with(['status' => 2, 'msg' => '只能上传 xlsx 和 xls 格式']);
        }

        $excelData = (new SalaryImport)->toArray($file);
        $salary = $excelData[0];

        // 如果 excel 里面没有 year 和 month，就默认用当前的
        $year = isset($request->year) ? $request->year : Carbon::now()->year;
        $month = isset($request->month) ? $request->month : Carbon::now()->month;

        if (!empty($salary)) {
            // 字段
            $fields = $salary[0];
            unset($salary[0]);

            $flag = false;
            DB::beginTransaction();

            try {

                $fieldKeys = [];

                // 保存字段
                foreach ($fields as $k => $v) {

                    $key = 'a' . ($k + 1);

                    $sf = SalaryField::where('year', $year)
                        ->where('month', $month)
                        ->where('field_key', $key)
                        ->get()->first();

                    if (empty($sf)) {
                        $sf = new SalaryField();
                    }

                    $sf->year = $year;
                    $sf->month = $month;
                    $sf->col_name = $v;
                    $sf->field_key = $key;
                    $sf->save();

                    array_push($fieldKeys, 'a' . ($k + 1));
                }

                // 保存值
                foreach ($salary as $k => $v) {
                    $s = Salary::where('year', $year)
                        ->where('month', $month)
                        ->where('a1', $v[0]) // 名字
                        ->get()->first();

                    if (empty($s)) {
                        $s = new Salary();
                    }

                    foreach ($fieldKeys as $key => $val) {
                        $s->$val = $v[$key];
                    }
                    $s->year = $year;
                    $s->month = $month;
                    $s->save();
                }

            } catch (\Exception $e) {
                $flag = true;
                DB::rollBack();
            }

            if ($flag) {
                return redirect()->back()->with(['status' => 2, 'msg' => 'DB saved error']);
            }

            // 成功
            DB::commit();

            return redirect()->back()->with(['status' => 1, 'msg' => '保存成功']);

        }
    }

    public function salaryDetails(Request $request)
    {
        if (!$request->has('id')) {
            abort(404, '没有记录');
        }

        $fields = null;
        $salary = Salary::find($request->id);

        if ($salary) {
            $fields = SalaryField::where('year', $salary->year)
                ->where('month', $salary->month)
                ->get();
        }

        return view('salary.details', compact('salary', 'fields'));
    }

    public function history(Request $request)
    {
        $params = $request->all();
        $latestRow = Salary::limit(1)
            ->orderBy('year')
            ->orderBy('month')
            ->get()->first();

        // 页面可选的年份范围
        $yearRanges = Salary::groupBy('year')
            ->pluck('year')->toArray();

        if (empty($yearRanges)) {
            array_push($yearRanges, Carbon::now()->year);
        }

        $startYear = (!empty($latestRow) ? $latestRow->year : Carbon::now()->year);
        $startMonth = (!empty($latestRow) ? $latestRow->month : Carbon::now()->month);

        // 页面默认显示最新的 年份 + 月份 的数据
        $year = $request->has('year') && !empty($request->year) ? $request->year : $startYear;
        $month = $request->has('month') && !empty($request->month) ? $request->month : $startMonth;

        $list = Salary::where('year', $year)
            ->where('month', $month);

        if ($request->username) {
            $username = $request->username;
            $list->where('a1', 'like', "%$username%");
        }

        $salaries = $list->paginate(20);

        $fields = SalaryField::where('year', $year)
            ->where('month', $month)
            ->get();

        return view('salary.history', compact('year', 'month', 'startYear', 'startMonth',
            'fields', 'salaries', 'params', 'yearRanges'));
    }

    public function salaryDelete(Request $request)
    {
        $latestRow = Salary::limit(1)
            ->orderBy('year')
            ->orderBy('month')
            ->get()->first();

        $yearRanges = Salary::groupBy('year')->pluck('year')->toArray();
        if (empty($yearRanges)) {
            array_push($yearRanges, Carbon::now()->year);
        }

        $startYear = (!empty($latestRow) ? $latestRow->year : Carbon::now()->year);
        $startMonth = (!empty($latestRow) ? $latestRow->month : Carbon::now()->month);

        // 页面默认显示最新的 年份 + 月份 的数据
        $year = $request->has('year') && !empty($request->year) ? $request->year : $startYear;
        $month = $request->has('month') && !empty($request->month) ? $request->month : $startMonth;

        return view('salary.delete', compact('yearRanges', 'year', 'month'));
    }

    public function salaryDoDelete(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'year' => 'required',
            'month' => 'required'
        ], [
            'year.required' => '参数 年份 有误，请联系开发人员',
            'month.required' => '参数 月份 有误，请联系开发人员'
        ]);

        if ($validation->fails()) {
            return redirect()->back()->with(['status' => 2, 'msg' => $validation->errors()->first()]);
        }

        $year = $request->year;
        $month = $request->month;

        $query = Salary::where('year', $year)
            ->where('month', $month);

        if ($query->count() == 0) {
            return redirect()->back()->with(['status' => 2, 'msg' => "$year 年 $month 月没有工资数据，请重新选择"]);
        }

        // 删除工资
        $query->delete();

        // 删除对应的 fields
        SalaryField::where('year', $year)
            ->where('month', $month)
            ->delete();

        return redirect()->back()->with(['status' => 1, 'msg' => "$year 年 $month 月工资已经删除成功"]);
    }

    public function settings(Request $request)
    {
        $ip = $request->ip();
        $ips = Setting::where('key_name', 'ip_allowed')
            ->get()->first();
        $maintain = Setting::where('key_name', 'maintain')
            ->get()->first();
        return view('settings.index', compact('ips', 'ip', 'maintain'));
    }

    public function storeMaintain(Request $request)
    {
        $setting = Setting::where('key_name', 'maintain')
            ->get()->first();
        $setting->value = $request->maintain;
        $setting->save();
        return redirect()->back()->with(['status' => 1, 'msg' => '系统维护保存成功']);
    }

    public function storeIp(Request $request)
    {
        $ips = $request->ip_allowed;

        if (empty($ips)) {
            return redirect()->back()->with(['status' => 2, 'msg' => 'ip 地址不能为空']);
        }

        $ipArr = explode(',', $ips);

        foreach ($ipArr as $ip) {
            if(!filter_var(trim($ip), FILTER_VALIDATE_IP)) {
                return redirect()->back()->with(['status' => 2, 'msg' => "$ip 不是有效的 IP 地址"])->withInput();
            }
        }

        $setting = Setting::where('key_name', 'ip_allowed')
            ->get()->first();
        $setting->value = str_replace(' ', '', $ips);
        $setting->save();

        return redirect()->back()->with(['status' => 1, 'msg' => 'IP 白名单保存成功']);

    }
}

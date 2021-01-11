<?php

namespace App\Http\Controllers;

use App\Imports\SalaryImport;
use App\Models\Salary;
use App\Models\SalaryField;
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
}

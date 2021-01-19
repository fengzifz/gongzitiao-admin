@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header"><strong>导入工资</strong></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <form method="post" enctype="multipart/form-data" action="{{ route('salary.import') }}">
                                @csrf
                                <div class="form-group row">
                                    <div class="offset-sm-2 col-sm-10">
                                        <span class="text-danger">
                                            1. 年份、月份要和 excel 里面的“年份”、“月份”保持一致。<br/>
                                            2. excel 里面，“姓名”需要固定放在第一列。<br/>
                                            3. 重复导入某个年份、月份的工资，会进行覆盖操作。
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="year">年份</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="year">
                                            @for($i = 0; $i < 10; $i++)
                                                <option value="{{ $year + $i }}">{{ $year + $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="month">月份</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="month">
                                            @for($i = 0; $i < 12; $i++)
                                                <option
                                                    @if($i + 1 == $month) selected @endif
                                                value="{{ $i + 1 }}">{{ $i + 1 }}月
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="month">文件</label>
                                    <div class="col-sm-10">
                                        <input type="file" name="file" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-10 offset-sm-2">
                                        <button type="submit" class="btn btn-primary">提交</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">

                        <div class="col-sm-2">
                            <strong>历史记录</strong>
                        </div>
                        <div class="col-sm-10">
                            <form class="form-inline float-sm-right">
                                <div class="form-group mb-2">
                                    <select class="form-control" name="year">
                                        @for($i = 0; $i < 10; $i++)
                                            <option
                                                @if($year == ($startYear + $i)) selected @endif
                                            value="{{ $startYear + $i }}">{{ $startYear + $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="form-group mx-sm-3 mb-2">
                                    <select class="form-control" name="month">
                                        @for($i = 0; $i < 12; $i++)
                                            <option
                                                @if($month == ($i + 1)) selected @endif
                                            value="{{ $i + 1 }}">{{ $i + 1 }}月
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="form-group mx-sm-3 mb-2">
                                    <input autocomplete="off" class="form-control" name="username"
                                           value="{{ isset($params['username']) ? $params['username'] : '' }}"
                                           placeholder="(可选) 输入员工名字">
                                </div>
                                <button type="submit" class="btn btn-primary mb-2">确认</button>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">

                            @if($fields->count())
                                <table class="table table-bordered table-striped">
                                    <tr>
                                        @foreach($fields as $k => $v)
                                            <th>{{ $v->col_name }}</th>
                                        @endforeach
                                        <th>查看</th>
                                    </tr>
                                    @if($salaries->count())
                                        @foreach($salaries as $key => $val)
                                            <tr>
                                                @foreach($fields as $k => $v)
                                                    <td>{{ $val[$v->field_key] }}</td>
                                                @endforeach
                                                <td>
                                                    <a href="{{ route('salary.details', ['id' => $val->id]) }}">查看</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        还没有导入记录
                                    @endif
                                </table>
                            @else
                                没有记录
                            @endif

                            @if(!empty($salaries))
                                {{ $salaries->appends($params)->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

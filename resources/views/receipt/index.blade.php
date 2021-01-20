@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>回执记录 |
                                @if($year && $month)
                                    <span class="text-primary">所得工资时间：{{ $year }}年{{ $month }}月</span>
                                @endif
                            </strong>
                        </div>
                        <div class="col-sm-8">
                            <form class="form-inline float-sm-right">
                                <div class="form-group mb-2">
                                    <select class="form-control" name="year">
                                        @foreach($yearRanges as $k => $v)
                                            <option @if($year == $v) selected @endif value="{{ $v }}">{{ $v }}</option>
                                        @endforeach
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
                                <button type="submit" class="btn btn-primary mb-2">确认</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <h5 class="text-success">已查看工资名单</h5>
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th>姓名</th>
                                    <th>所得工资时间</th>
                                    <th>查看时间</th>
                                </tr>
                                @foreach($readSalaries as $k => $v)
                                    <tr>
                                        <td>{{ $v->name }}</td>
                                        <td>{{ $v->year }}年{{ $v->month }}月</td>
                                        <td>{{ $v->read_at }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                        <div class="col-sm-6">
                            <h5 class="text-danger">未查看工资名单</h5>
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th>姓名</th>
                                    <th>所得工资时间</th>
                                </tr>
                                @foreach($salaries as $k => $v)
                                    @if(!in_array($v->id, $readList))
                                        <tr>
                                            <td>{{ $v->name }}</td>
                                            <td>{{ $v->year }}年{{ $v->month }}月</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

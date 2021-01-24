@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-2">
                            <strong>删除工资</strong>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <form class="form-inline float-left" method="post">
                                @csrf
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
                                <button onclick="return confirm('删除后不可恢复，是否确认删除？');"
                                        type="submit" class="btn btn-danger mb-2">删除</button>
                            </form>
                        </div>
                        <div class="col-sm-4 text-danger">
                            注意：
                            <ul>
                                <li>删除后不可恢复。</li>
                                <li>删除 x 年 x 月的工资数据后，对应的 x 年 x 月的<strong><u>回执 - 未查看名单</u></strong>也会被删除，
                                    <strong><u><span class="text-success">回执 - 已查看名单不受影响。</span></u></strong></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

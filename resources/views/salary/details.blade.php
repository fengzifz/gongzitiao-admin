@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-2">
                            <strong>详细记录</strong>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="text-center">
                                @if($fields && $salary)
                                    <div class="row">
                                        <div class="col-sm-4 offset-sm-4">
                                            <table class="table table-bordered table-striped">
                                                @foreach($fields as $k => $v)
                                                    <tr>
                                                        <th>{{ $v->col_name }}</th>
                                                        <td>{{ $salary[$v->field_key] }}</td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                            <a class="btn btn-primary" href="#"
                                               onclick="window.history.go(-1); return false;">返回</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

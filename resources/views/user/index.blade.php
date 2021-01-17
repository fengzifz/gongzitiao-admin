@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header"><strong>员工管理</strong></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th>姓名</th>
                                    <th>电话</th>
                                    <th>是否绑定微信</th>
                                    <th>状态</th>
                                    <th>绑定日期</th>
                                    <th>操作</th>
                                </tr>
                                @foreach($users as $k => $v)
                                    <tr>
                                        <td>{{ $v->username }}</td>
                                        <td>{{ $v->phone }}</td>
                                        <td>
                                            @if(!empty($v->openid))
                                                <span class="badge bg-success">已绑定</span>
                                            @else
                                                <span class="badge badge-danger">未绑定</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($v->status == 1)
                                                <span class="text-danger">禁用</span>
                                            @else
                                                <span class="text-success">可用</span>
                                            @endif
                                        </td>
                                        <td>{{ $v->created_at }}</td>
                                        <td>
                                            @if($v->status == 1)
                                                <a href="{{ route('user.enabled', ['id' => $v->id]) }}"
                                                   onclick="return confirm('解锁后，员工可以登录小程序')"
                                                   class="btn btn-sm btn-primary">解锁</a>
                                            @else
                                                <a href="{{ route('user.disabled', ['id' => $v->id]) }}"
                                                   onclick="return confirm('锁定后，该员工无法登录小程序，是否确认？')"
                                                   class="btn btn-sm btn-danger">锁定</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

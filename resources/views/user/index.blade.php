@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-2">
                            <strong>员工管理</strong>
                        </div>
                        <div class="col-sm-10">
                            <form class="form-inline float-sm-right">
                                <div class="form-group mx-sm-3 mb-2">
                                    <input autocomplete="off" class="form-control" name="username"
                                           value="{{ isset($params['username']) ? $params['username'] : '' }}"
                                           placeholder="输入员工名字查询">
                                </div>
                                <button type="submit" class="btn btn-primary mb-2">查询</button>
                            </form>
                        </div>
                    </div>
                </div>
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
                                        <td class="text-center">
                                            @if($v->status == 1)
                                                <a href="{{ route('user.enabled', ['id' => $v->id]) }}"
                                                   onclick="return confirm('解锁后，员工可以登录小程序')"
                                                   class="btn btn-sm btn-primary">解锁</a>
                                            @else
                                                <a href="{{ route('user.disabled', ['id' => $v->id]) }}"
                                                   onclick="return confirm('锁定后，该员工无法登录小程序，是否确认？')"
                                                   class="btn btn-sm btn-danger">锁定</a>
                                            @endif
                                            @if(!empty($v->openid))
                                                <a href="{{ route('user.remove.openid', ['id' => $v->id]) }}"
                                                   onclick="return confirm('解绑后，用户需要在微信端重新登录，是否确认？')"
                                                   class="btn btn-sm btn-warning">
                                                    解绑
                                                </a>
                                            @endif
                                            <a href="{{ route('user.edit', ['id' => $v->id]) }}" class="btn btn-sm btn-dark">编辑</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                            {{ $users->appends($params)->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

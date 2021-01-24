@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header"><strong>IP 白名单</strong></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 offset-sm-0">

                            <form method="post" action="{{ route('settings.store.ip') }}">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-sm-10 offset-sm-2">
                                        <div class="text-success">当前 IP 地址是：{{ $ip }}</div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label text-right" for="year">IP 地址</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="ip_allowed" class="form-control"
                                               value="{{ old('ip_allowed') ? old('ip_allowed') : $ips->value }}">
                                        <div class="text-danger">
                                            说明：
                                            <ul>
                                                <li>该 IP 限制只对后台有效。</li>
                                                <li>默认是 0.0.0.0，表示允许所有 IP 地址登录后台。</li>
                                                <li>允许多个 IP 地址，用英文逗号 “,” 分隔。</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-10 offset-sm-2">
                                        <button class="btn btn-primary" type="submit">保存</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="card">
                <div class="card-header"><strong>系统维护</strong></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 offset-sm-0">
                            <form method="post" action="{{ route('settings.store.maintain') }}">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label text-right" for="year">系统维护</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="maintain">
                                            <option value="0" @if($maintain->value == 0) selected @endif>关闭</option>
                                            <option value="1" @if($maintain->value == 1) selected @endif>开启</option>
                                        </select>
                                        <div class="text-danger">
                                            说明：开启后，所有用户无法登录小程序。
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-10 offset-sm-2">
                                        <button class="btn btn-primary" type="submit">保存</button>
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

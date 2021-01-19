@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header"><strong>员工管理</strong></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 offset-sm-3">

                            <form method="post">
                                @csrf
                                <input type="hidden" name="id" value="{{ $user->id }}">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label text-right" for="year">姓名</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="username"
                                               value="{{ old('username') ? old('username') : $user->username }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label text-right" for="year">电话</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="phone"
                                               value="{{ old('phone') ? old('phone') : $user->phone }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label text-right" for="year">openid</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="openid" disabled
                                               value="{{ $user->openid }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label text-right" for="year">状态</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="status">
                                            <option value="0" @if($user->status == 0) selected @endif>可用</option>
                                            <option value="1" @if($user->status == 1) selected @endif>锁定</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-10 offset-sm-2">
                                        <button class="btn btn-primary" type="submit">保存</button>
                                        <a href="{{ route('user') }}" class="btn btn-dark">返回</a>
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

@extends('layout')
@section('content')
<div class="container">
    <div class="row">
        <div class="col col-md-offset-3 col-md-6">
            <div class="text-center">
                <h2>404 | NOT FOUND</h2>
                <p>お探しのページは見つかりませんでした。</p>
                <a href="{{ route('home') }}" class="btn">
                    ホームへ戻る
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
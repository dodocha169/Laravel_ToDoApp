@extends('layout')
@section('content')
<div class="container">
    <div class="row">
        <div class="col col-md-offset-3 col-md-6">
            <div class="text-center">
                <h2>500 | SERVER ERROR</h2>
                <p>サーバーのエラーが起きました。</p>
                <p>申し訳ありませんが、以下のリンクからホームページへお戻りください。</p>
                <a href="{{ route('home') }}" class="btn">
                    ホームへ戻る
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
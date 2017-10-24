@extends('layout') @section('content')

<div class="ui grid cycuvote-container centered">

    <div class="six wide column">
        <form class="ui form">
            <div class="field">
                <label>學號</label>
                <input type="text" placeholder="學號">
            </div>
            <div class="field">
                <label>iTouch 密碼</label>
                <input type="text" name="last-name" placeholder="iTouch 密碼">
            </div>
            <button class="ui fluid black button" type="submit">驗證身分</button>
        </form>
    </div>

</div>

@endsection()
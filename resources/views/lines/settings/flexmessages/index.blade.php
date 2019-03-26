@extends('layouts.app') @section('title', 'LINE - Flex Message')

@section('style')
    <link rel="stylesheet" href="/css/sgrid.css">
@endsection

@section('header')
    Flex Message
@endsection

@section('linknav')
    <span>Home  / </span>
    <span>Line  /</span>
    <span>Settings  /</span>
    <span style="color: #525252;">Flex Message</span>
@endsection

@section('content')
    <section id="formeec">

        <div class="cd-bubble">
            <div style="text-align: left;">
                <a href="./create">
                    <button class="cd-btn">New</button>
                </a>
            </div>

        </div>

    </section>
@endsection @section('script')

    <script type="text/javascript" src="/js/views/lines/index.js"></script>
    <script src="/js/tabs.js"></script>

    <script>
        // console.log(userProperties);
        new Vue({
            el: '#asset',

            data: {},

        });
    </script>
@endsection
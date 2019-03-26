@extends('layouts.app')

@section('title', 'Index')

@section('searchfield')
    <input v-model="searchfield" type="search" placeholder="Search...">
@endsection

@section('style')
    <link rel="stylesheet" href="/css/sgrid.css">
@endsection

@section('header')
    All @{{ view }}s
@endsection

@section('linknav')
    <span>Home  / </span>
    <span>Manage  /</span>
    <span>@{{ subDirectory }}  /</span>
    <span style="color: #525252;">  All @{{ view }}s</span>
@endsection

@section('content')
    <div class="cd-bubble">
        <section id="formeec">

            <div class="row">
                <div class="col-md-12">
                    <div>
                        <indexgrid :data.sync="records" :groups="groups" :userproperties="userProperties" :searchfield="searchfield" :view="view" :allowview="(view == 'Customer' || view == 'Route')"
                                   ></indexgrid>
                    </div>
                </div>
            </div>


        </section>
    </div>
@endsection @section('script')

    <script type="text/javascript" src="/js/views/properties/index.js"></script>

    <script>
        // console.log(userProperties);
        new Vue({
            el: '#asset',

            data: {
                showLoader: true,
                groups,
                userProperties,
                records,
                view,
                mainDirectory,
                subDirectory,
                searchfield: '',
            },

        });
    </script>
@endsection
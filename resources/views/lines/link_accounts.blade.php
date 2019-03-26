@extends('layouts.app') @section('title', 'LINE - Flex Message')

@section('style')
    <link rel="stylesheet" href="/css/sgrid.css">
@endsection

@section('content')
    <section class="cid-r0kOe0qdGK" id="formeec">

        <div class="row" id="app">
            <div class="container">
                <div class="col-md-12">
                    <h4 class="section-content-title align-center mbr-fonts-style display-5">
                        Linking Account
                    </h4>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div>
                    <hr>
                    {{-- Form '' --}}
                    {{ Form::open(['route' => 'line.linkAccount']) }}

                    {{--  Form Input --}}
                    <input type="text" name="linkToken" value="{{ $linkToken }}" v-show="false">
                    <div class="form-group">
                        <div class="form-group col-md-6">
                            <div class="e-float-input e-control-wrapper">
                                <input id="Uri" name="uri" style="padding-left: 8px;" type="text" required/>
                                <span class="e-float-line"></span>
                                <label class="e-float-text" for="Uri">ID number / เลขบัตรประชาชน</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <input type="submit" class="eec_button" style="font-size: 13px" value="Link">
                    </div>

                    {{ Form::close() }}

                </div>
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
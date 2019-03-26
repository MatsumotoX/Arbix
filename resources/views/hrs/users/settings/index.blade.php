@extends('layouts.app') @section('title', 'LINE - Rich Menu')

@section('searchfield')
    <input v-model="searchfield" type="search" placeholder="Search...">
@endsection

@section('header')
    Account Setting
@endsection

@section('style')
    <link rel="stylesheet" href="/css/sgrid.css">
@endsection

@section('content')
    <section id="formeec">

        <div class="cd-bubble-tab">
            <tabs>
                <tab id="Basic Settings" name="Basic Settings" :selected="true">

                    <nav>
                        <ol class="cd-multi-steps text-bottom count">
                            <li class="visited"><a href="#0">Cart</a></li>
                            <li class="visited" ><a href="#0">Billing</a></li>
                            <li class="current"><em>Delivery</em></li>
                            <li><em>Review</em></li>
                        </ol>
                    </nav>

                </tab>
                <tab id="Security Settings" name="Security Settings">

                </tab>
                <tab id="Financial Settings" name="Financial Settings">

                </tab>
                <tab id="Account Binding" name="Account Binding">

                </tab>
            </tabs>
        </div>

    </section>
@endsection @section('script')

    <script type="text/javascript" src="/js/views/lines/createRichMenu.js"></script>
    <script src="/js/form/form.js"></script>

    <script>
        new Vue({
            el: '#asset',

            data: {
                searchfield: '',
            },

            methods: {
                notify(content, type = null, title = null) {
                    this.eNoti.type = type;
                    this.eNoti.content = content;
                    this.eNoti.title = title;
                    this.$refs.notification.show(this.eNoti);
                },
                previewImage: function (args) {
                    this.addLeave['attachment'] = args;
                    //Credit to Mani Jagadeesan https://jsfiddle.net/mani04/5zyozvx8/
                    var reader = new FileReader();
                    reader.onload = (e) => {
                        this.imageData = e.target.result;
                    };
                    reader.readAsDataURL(args);
                },
            }

        });
    </script>
@endsection
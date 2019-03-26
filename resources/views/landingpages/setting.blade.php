@extends('layouts.app') @section('title', 'Landing Page - Setting')

@section('searchfield')
    <input v-model="searchfield" type="search" placeholder="Search...">
@endsection

@section('header')
    Landing Page
@endsection

@section('style')
    <link rel="stylesheet" href="/css/sgrid.css">
@endsection

@section('content')
    <section id="formeec">

        <div class="cd-bubble-tab">
            <tabs>
                <tab id="Home" name="Home" :selected="true">
                    <allpurpose-grid name="Home" :data.sync="home.data" :columns.sync="home.column" :searchfield="searchfield" :allowadd="true"
                                     :allowdelete="true" :allowedit="true" maindirectory="LandingPage" subdirectory="Setting"
                                     style="margin-top: 20px; margin-bottom: 20px;"></allpurpose-grid>
                </tab>
                <tab id="Product" name="Product">

                </tab>
                <tab id="Algorithm" name="Algorithm">

                </tab>
                <tab id="Performance" name="Performance">

                </tab>
            </tabs>
        </div>

    </section>
@endsection @section('script')

    <script type="text/javascript" src="/js/views/lines/createRichMenu.js"></script>

    <script>
        new Vue({
            el: '#asset',

            data: {
                searchfield: '',
                home,
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
                onSubmit() {

                }
            }

        });
    </script>
@endsection
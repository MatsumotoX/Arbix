@extends('layouts.app') @section('title', 'HR - Customer')

@section('searchfield')
    <input v-model="searchfield" type="search" placeholder="Search...">
@endsection

@section('style')
    <link rel="stylesheet" href="/css/sgrid.css">
@endsection

@section('header')
    @{{ view }} Detail
@endsection

@section('linknav')
    <span>Home  / </span>
    <span>@{{ mainDirectory }}  /</span>
    <span>@{{ subDirectory }}  /</span>
    <span style="color: #525252;">@{{ view }} Detail</span>
@endsection

@section('content')
    <section id="formeec">
        <div class="cd-bubble">
            <div class="row">
                <div class="col-md-12 col-xl-3" style="padding-top: 10px" v-for="(property, propertyName) in data.Info" :key="propertyName">
                    <div class="control">
                        <eec-inputbox :value.sync="property" :placeholder="propertyName" type="disabled"></eec-inputbox>
                    </div>
                </div>
            </div>
        </div>
        <div class="cd-bubble-tab">
            <div>
                <tabs>
                    <tab id="Contact" name="Contact" :selected="true">
                        <hc-contact ref="contact" :id="id" :searchfield="searchfield"></hc-contact>
                    </tab>
                    <tab id="Contract" name="Contract">
                        <hc-contract ref="contract" :id="id" :searchfield="searchfield"></hc-contract>
                    </tab>
                    <tab id="Location" name="Location">
                        <hc-location ref="location" :id="id" :searchfield="searchfield"></hc-location>
                    </tab>
                </tabs>
            </div>
        </div>
    </section>

@endsection @section('script')

    <script type="text/javascript" src="/js/views/hrs/customers/view.js"></script>
    <script src="/js/form/form.js"></script>

    <script>

        window.vm = new Vue({
            el: '#asset',

            data: {
                searchfield: '',
                data,
                id,
                view,
                mainDirectory,
                subDirectory,

                addContact: new Form({
                    CompanyName: null
                }),
            },

            created() {
                // console.log(data);
            }

        });
    </script>
@endsection
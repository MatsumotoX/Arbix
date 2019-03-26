@extends('layouts.app') @section('title', 'LINE - Flex Message')

@section('searchfield')
    <input v-model="searchfield" type="search" placeholder="Search...">
@endsection

@section('style')
    <link rel="stylesheet" href="/css/sgrid.css">
@endsection

@section('header')
    Create Flex Message
@endsection

@section('linknav')
    <span>Home  / </span>
    <span>Line  /</span>
    <span>Settings  /</span>
    <a href="./index" class="cd-a">
        <span>Flex Message</span>
    </a>
    <span>/</span>
    <span style="color: #525252;">Create</span>
@endsection

@section('content')
    <section id="formeec">

        <div class="cd-bubble-tab">
            <tabs buttonvalue="Test" :buttonenable="addFlexMessage.errors.any()" @click="testFlexMessage">
                <tab name="Flex Message" key=0 id=0 selected=true>
                    <div class="row">
                        <div class="col-md-6" style="padding-top: 10px">
                            <div class="control">
                                <eec-inputbox :value.sync="addFlexMessage['name']" placeholder="Title" @input="addFlexMessage['name'] = $event"
                                              type="string"
                                ></eec-inputbox>
                            </div>
                        </div>

                        <div class="col-md-6" style="padding-top: 10px">
                            <div class="control">
                                <eec-inputbox :value.sync="addFlexMessage['contents']" placeholder="Contents"
                                              @input="addFlexMessage['contents'] = ($event)" :option="flex.column[3].option"
                                              type="multiselect"
                                              allowfilter="true" filtertype="contains"></eec-inputbox>
                            </div>
                        </div>

                        <div class="col-md-6" style="padding-top: 10px">
                            <div class="control">
                                <eec-inputbox :value.sync="addFlexMessage['quickreply_id']" placeholder="Quick Reply"
                                              @input="addFlexMessage['quickreply_id'] = ($event)" :option="flex.column[4].option"
                                              type="select"
                                              allowfilter="true" filtertype="contains"></eec-inputbox>
                            </div>
                        </div>

                        <div class="col-md-6" style="padding-top: 10px">
                            <div class="control">
                                <eec-inputbox :value.sync="addFlexMessage['altTextSpecial']" placeholder="Alternate Text (Special)"
                                              @input="addFlexMessage['altTextSpecial'] = $event"
                                              type="string"
                                ></eec-inputbox>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6" style="padding-top: 10px">
                            <div class="control">
                                <eec-inputbox :value.sync="addFlexMessage['altText']" placeholder="Alternate Text" @input="addFlexMessage['altText'] = $event"
                                              type="textarea"
                                ></eec-inputbox>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-12" style="text-align: center; margin-top:20px;">
                        <button @click="onSubmit" class="cd-btn" :disabled="addFlexMessage.errors.any()">Save</button>
                    </div>
                </tab>
                @include('lines.settings.flexmessages._components')

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
                flex,
                bubble,
                bubbleStyle,
                box,
                button,
                icon,
                image,
                separator,
                spacer,
                text,
                component,
                action,
                quickReply,
                quickReplyButton,

                addFlexMessage: new Form({
                    name: null,
                    altText: null,
                    altTextSpecial: null,
                    contents: null,
                    quickreply_id: null,
                }),

                imageData: ""
            },

            methods: {
                getData: function () {
                    return new Promise((resolve, reject) => {
                        axios.get('./getData')
                            .then(response => {
                                this.flex = response.data.flex;
                                this.bubble = response.data.bubble;
                                this.bubbleStyle = response.data.bubbleStyle;
                                this.box = response.data.box;
                                this.button = response.data.button;
                                this.icon = response.data.icon;
                                this.image = response.data.image;
                                this.separator = response.data.separator;
                                this.spacer = response.data.spacer;
                                this.text = response.data.text;
                                this.component = response.data.component;
                                this.action = response.data.action;
                                this.quickReply = response.data.quickReply;
                                this.quickReplyButton = response.data.quickReplyButton;
                                resolve(response.data);
                            })
                            .catch(error => {
                                console.log(error);
                                reject(error.response);
                            });
                    });
                },
                testFlexMessage: function () {
                    return new Promise((resolve, reject) => {
                        axios['post']('./testFlex', this.addFlexMessage)
                            .then(response => {
                                console.log(response.data);
                                resolve(response.data);
                            })
                            .catch(error => {
                                console.log(error.response);
                                alert(error.response.data.message);
                                reject(error.response);
                            });
                    });
                },
                onSubmit: function () {
                    return new Promise((resolve, reject) => {
                        this.addFlexMessage.post('./store')
                            .then(response => {
                                console.log(response.data);
                                resolve(response.data);
                            })
                            .catch(error => {
                                console.log(error);
                                alert('Fail: Tell worakorn.');
                                reject(error.response);
                            });
                    });
                }
            }

        });
    </script>
@endsection
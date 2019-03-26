@extends('layouts.app') @section('title', 'LINE - Rich Menu')

@section('searchfield')
    <input v-model="searchfield" type="search" placeholder="Search...">
@endsection

@section('style')
    <link rel="stylesheet" href="/css/sgrid.css">
@endsection

@section('header')
    Rich Menu
@endsection

@section('linknav')
    <span>Home  / </span>
    <span>Line  /</span>
    <span>Settings  /</span>
    <span style="color: #525252;">Rich Menu</span>
@endsection

@section('content')
    <section id="formeec">

        <div class="cd-bubble">
            <h4 class="cd-header-1">
                Set Rich Menu
            </h4>
            <hr>
            <div class="row">
                <div class="col-md-4" style="padding-top: 10px">
                    <eec-inputbox :value.sync="setRichMenu['user']" placeholder="User"
                                  @input="setRichMenu['user'] = $event" @clear="setRichMenu.errors.clear('user')" type="select"
                                  :option="userOptions" optiongroup="group"></eec-inputbox>
                    <span class="help is-danger" v-if="setRichMenu.errors.has('user')" v-text="setRichMenu.errors.get('user')"></span>
                </div>
                <div class="col-md-4" style="padding-top: 10px">
                    <eec-inputbox :value.sync="setRichMenu['richMenu']" placeholder="Rich Menu"
                                  @input="setRichMenu['richMenu'] = $event" @clear="setRichMenu.errors.clear('richMenu')" type="select"
                                  :option="richMenu.option" optiongroup="group"></eec-inputbox>
                    <span class="help is-danger" v-if="setRichMenu.errors.has('richMenu')" v-text="setRichMenu.errors.get('richMenu')"></span>
                </div>
                <div class="col-md-4">
                    <button @click="onSubmit" class="cd-btn cd-btn-mt">Set</button>
                </div>
            </div>
        </div>

        <div class="cd-bubble">
            <a href="./create">
                <button class="cd-btn">New</button>
            </a>

            <allpurpose-grid name="RichMenu" :data.sync="richMenu.data" :columns="richMenu.column" :searchfield="searchfield"
                             maindirectory="Line" subdirectory="Bot" :allowtoolbar="false" :allowgroup="false"
                             style="margin-top: 20px; margin-bottom: 20px;"></allpurpose-grid>
        </div>

    </section>
@endsection @section('script')

    <script type="text/javascript" src="/js/views/lines/index.js"></script>
    <script src="/js/form/form.js"></script>

    <script>
        // console.log(userProperties);
        new Vue({
            el: '#asset',

            data: {
                richMenu,
                searchfield: '',
                setRichMenu: new Form({
                    user: null,
                    richMenu: null,
                }),
                user: new Property('user'),
                userOptions: null,
                eNoti: {title: null, content: null, type: null},
            },

            created() {
                // this.userOptions = this.user.getIdentity('Role');
                this.user.whereHas('LineId').getIdentity('Role')
                    .then(response => {
                        this.userOptions = response;
                    })
            },

            methods: {
                onSubmit() {
                    this.setRichMenu.post('./setRichMenu')
                        .then(response => {
                            this.notify(response.message, 'success');
                            // console.log(response.data)
                        })
                        .catch(response => {
                            console.log(response.errors);
                            let content = '';
                            for (error in response.errors) {
                                if (content) {
                                    content += "\r\n";
                                }
                                content += error;
                                content += ' - ';
                                content += response.errors[error];
                            }
                            this.notify(content, 'danger');
                            if (!this.setRichMenu.errors.errors) {
                                alert('Fail tell Worakorn');
                            }
                        })

                },
                notify(content, type = null, title = null) {
                    this.eNoti.type = type;
                    this.eNoti.content = content;
                    this.eNoti.title = title;
                    this.$refs.notification.show(this.eNoti);
                },
            },

        });
    </script>
@endsection
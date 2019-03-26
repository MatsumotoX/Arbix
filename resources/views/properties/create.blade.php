@extends('layouts.app') @section('title', 'Create')

@section('style')
    <link rel="stylesheet" href="/css/sgrid.css">
@endsection

@section('header')
    Add @{{ view }}
@endsection

@section('linknav')
    <span>Home  / </span>
    <span>@{{ mainDirectory }}  /</span>
    <span>@{{ subDirectory }}  /</span>
    <span style="color: #525252;">  Add @{{ view }}</span>
@endsection

@section('content')

    <div class="cd-bubble-tab">
        <section id="formeec">
            <tabs buttonvalue="Create" :buttonenable="addRecords.errors.any()" @click="onSubmit">
                <tab v-for="(group, index) in data" :key='index' :name="group['name']" :selected="group['name']==defaultGroup"
                     :id='index'>
                    <form method="POST" action="/projects" @submit.prevent="onSubmit" @keydown="addRecords.errors.clear()">
                        <div class="row">
                            <div class="col-md-6" style="padding-top: 10px" v-for="(property, property_index) in group['property']"
                                 :key="property_index" v-if="property['isSpecial'] == 0">
                                <div class="control">
                                    <eec-inputbox :value.sync="addRecords[property['name']]" :placeholder="property['name']"
                                                  @input="addRecords[property['name']] = $event" :type="property['type']"
                                                  :option="property['option']" optiontext='text' optionvalue='value' :digit="property['digit']"
                                                  :decimal="property['decimal']" allowfilter="true" filtertype="contains"></eec-inputbox>
                                    <span class="help is-danger" v-if="addRecords.errors.has(property['name'])"
                                          v-text="addRecords.errors.get(property['name'])"></span>
                                </div>
                            </div>
                        </div>
                    </form>
                    {{--<div class="col-md-12" style="text-align: center; margin-top:20px;">--}}
                    {{--<button @click="onSubmit" class="eec_button" :disabled="addRecords.errors.any()">Create</button>--}}
                    {{--</div>--}}
                </tab>
                </template>
            </tabs>
        </section>
    </div>
@endsection

@section('script')

    <script src="/js/views/properties/form_property.js"></script>
    <script src="/js/views/properties/create.js"></script>

    <script>

        new Vue({
            el: '#asset',

            data: {
                data,
                view,
                mainDirectory,
                subDirectory,
                defaultGroup,
                addRecords: new Form(data),
                eNoti: {
                    title: null,
                    content: null,
                    type: null,
                }
            },

            methods: {
                notify(content, type = null, title = null) {
                    this.eNoti.type = type;
                    this.eNoti.content = content;
                    this.eNoti.title = title;
                    this.$refs.notification.show(this.eNoti);
                },
                onSubmit() {
                    this.addRecords.post('./store')
                        .then(response => {
                            this.notify(this.view + ' has been successfully saved.', 'success');
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
                            if (!this.addRecords.errors.errors) {
                                alert('Fail tell Worakorn');
                            }
                        });
                }
            },
        });
    </script>
@endsection
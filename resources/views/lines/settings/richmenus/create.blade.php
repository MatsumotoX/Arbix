@extends('layouts.app') @section('title', 'LINE - Rich Menu')

@section('searchfield')
    <input v-model="searchfield" type="search" placeholder="Search...">
@endsection

@section('header')
    Create Rich Menu
@endsection

@section('linknav')
    <span>Home  / </span>
    <span>Line  /</span>
    <span>Settings  /</span>
    <a href="./index" class="cd-a">
        <span>Rich Menu </span>
    </a>
    <span>/ </span>
    <span style="color: #525252;"> Create</span>
@endsection

@section('style')
    <link rel="stylesheet" href="/css/sgrid.css">
@endsection

@section('content')
    <section id="formeec">

        <div class="cd-bubble-tab">
            <tabs buttonvalue="Create" :buttonenable="addRichMenu.errors.any()" :showbutton="false" @click="onSubmit">
                <template slot-scope="props">
                    <tab :button='props' name="Rich Menu" key=0 id=0 selected=true>
                        <div class="row">
                            <div class="col-md-6" style="padding-top: 10px">
                                <div class="control">
                                    <eec-inputbox :value.sync="addRichMenu['name']" placeholder="Title" @input="addRichMenu['name'] = $event"
                                                  type="string"
                                    ></eec-inputbox>
                                    {{--<span class="help is-danger" v-if="addRecords.errors.has(property['name'])" v-text="addRecords.errors.get(property['name'])"></span>--}}
                                </div>
                            </div>
                            <div class="col-md-6" style="padding-top: 10px">
                                <div class="control">
                                    <eec-inputbox :value.sync="addRichMenu['language']" placeholder="Language"
                                                  @input="addRichMenu['language'] = $event" type="select"
                                                  :option="[{'text': 'EN', 'value': 'EN'}, {'text': 'TH', 'value': 'TH'}]"></eec-inputbox>
                                    {{--<span class="help is-danger" v-if="addRecords.errors.has(property['name'])" v-text="addRecords.errors.get(property['name'])"></span>--}}
                                </div>
                            </div>
                            <div class="col-md-6" style="padding-top: 10px">
                                <div class="control">
                                    <eec-inputbox :value.sync="addRichMenu['size']" placeholder="Size"
                                                  @input="addRichMenu['size'] = $event" type="select"
                                                  :option="[{'text': '2500x1686', 'value': 'full'}, {'text': '2500x843', 'value': 'half'}]"></eec-inputbox>
                                    {{--<span class="help is-danger" v-if="addRecords.errors.has(property['name'])" v-text="addRecords.errors.get(property['name'])"></span>--}}
                                </div>
                            </div>
                            <div class="col-md-6" style="padding-top: 10px">
                                <div class="control">
                                    <eec-inputbox :value.sync="addRichMenu['chatBarText']" placeholder="Menu Name Appeared in LINE"
                                                  @input="addRichMenu['chatBarText'] = $event"
                                                  type="string"
                                    ></eec-inputbox>
                                    {{--<span class="help is-danger" v-if="addRecords.errors.has(property['name'])" v-text="addRecords.errors.get(property['name'])"></span>--}}
                                </div>
                            </div>
                            <div class="col-md-6" style="color: #777777; text-align: left; padding-top: 35px;">
                                <span style="margin-right: 30px;">Set as default: </span>
                                <input type="radio" name="importOption" id="false" value=1
                                       v-model="addRichMenu['selected']"
                                       style="cursor: pointer;">
                                <label for="false" style="cursor: pointer; margin-right: 10px; font-size: 0.9em;">Yes</label>
                                <input type="radio" name="importOption" id="true" value=0
                                       v-model="addRichMenu['selected']"
                                       style="cursor: pointer;">
                                <label for="true" style="cursor: pointer; font-size: 0.9em;">No</label>
                            </div>
                            <div class="col-12" style="padding-top: 20px">
                                <div class="control">
                                    <hr>
                                </div>
                            </div>
                            <div class="col-md-6" style="padding-top: 10px">
                                <div class="control">
                                    <eec-inputbox :value.sync="addRichMenu['image']" placeholder="Image"
                                                  @input="previewImage($event)"
                                                  type="image"
                                    ></eec-inputbox>
                                    <eec-inputbox :value.sync="addRichMenu['area']" placeholder="Area"
                                                  @input="addRichMenu['area_id'] = ($event)" :option="areaoptions"
                                                  style="padding-top: 10px" type="multiselect" mode="CheckBox"
                                                  allowfilter="true" filtertype="contains"></eec-inputbox>
                                </div>
                            </div>
                            <div class="col-md-6" style="padding-top: 10px">
                                <div class="control">
                                    <div class="image-preview" v-if="imageData.length > 0">
                                        <img class="preview" :src="imageData">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </tab>

                <tab name="Area" key=1 id=1>
                    <linearea-grid name="Area" :data.sync="areas.data" :columns.sync="areas.column" :searchfield="searchfield" :allowadd="true"
                                   :allowdelete="true" :allowedit="true" :allowpage="true" maindirectory="Line" subdirectory="Bot" @getdata="getData"
                                   editmode='Dialog' style="margin-top: 20px; margin-bottom: 20px;"></linearea-grid>
                </tab>

                <tab name="Action" key=2 id=2>
                    <lineaction-grid name="Action" :data.sync="actions.data" :columns.sync="actions.column" :searchfield="searchfield" :allowadd="true"
                                     :allowdelete="true" :allowedit="true" :allowpage="true" maindirectory="Line" subdirectory="Bot" editmode="Dialog"
                                     @getdata="getData" style="margin-top: 20px; margin-bottom: 20px;"></lineaction-grid>
                </tab>

                <tab name="Bound" key=3 id=3>
                    <allpurpose-grid name="Bound" :data.sync="bounds.data" :columns.sync="bounds.column" :searchfield="searchfield" :allowadd="true"
                                     :allowdelete="true" :allowedit="true" :allowpage="true" maindirectory="Line" subdirectory="Bot" editmode="Batch"
                                     @getdata="getData" confirmdelete="false" style="margin-top: 20px; margin-bottom: 20px;"></allpurpose-grid>
                </tab>
                </template>
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
                bounds,
                areas,
                actions,
                areaoptions,

                addRichMenu: new Form({
                    name: null,
                    language: null,
                    selected: 0,
                    chatBarText: null,
                    size: null,
                    area_id: null,
                    image: null,
                }),

                imageData: "",
            },

            methods: {
                getData: function () {
                    return new Promise((resolve, reject) => {
                        axios.get('./getData')
                            .then(response => {
                                console.log(response.data);
                                this.bounds = response.data.bounds;
                                this.areas = response.data.areas;
                                this.actions = response.data.actions;
                                this.areaoptions = response.data.areaoptions;
                                resolve(response.data);
                            })
                            .catch(error => {
                                console.log(error);
                                reject(error.response);
                            });
                    });
                },
                previewImage: function (args) {
                    this.addRichMenu['image'] = args;
                    //Credit to Mani Jagadeesan https://jsfiddle.net/mani04/5zyozvx8/
                    var reader = new FileReader();
                    reader.onload = (e) => {
                        this.imageData = e.target.result;
                    };
                    reader.readAsDataURL(args);
                },
                onSubmit: function () {
                    return new Promise((resolve, reject) => {
                        this.addRichMenu.post('./store')
                            .then(response => {
                                console.log(response.data);
                                this.selected = 0;
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
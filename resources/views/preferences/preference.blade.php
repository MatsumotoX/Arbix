@extends('layouts.app') @section('title', 'Vehicle Preference')

@section('searchfield')
    <input v-model="searchfield" type="search" placeholder="Search...">
@endsection

@section('style')
    <link rel="stylesheet" href="/css/sgrid.css"> <!-- CSS reset -->
@endsection

@section('header')
    @{{ view }} Preferences
@endsection

@section('linknav')
    <span>Home  / </span>
    <span>Preferences  /</span>
    <span style="color: #525252;">@{{ view }}</span>
@endsection

@section('content')
    <section id="formeec">

        <div class="cd-bubble-tab">
            <tabs>

                @include('preferences._tab_properties')

                @include('preferences._tab_groups')

                @include('preferences._tab_options')

            </tabs>
        </div>


        @include('preferences._modal_add_properties')

        @include('preferences._modal_add_group')

        @include('preferences._modal_success')

    </section>
@endsection @section('script')

    <script src="/js/form/formPreference.js"></script>
    <script type="text/javascript" src="/js/views/preferences/app.js"></script>

    <script>
        new Vue({
            el: '#asset',

            data: {
                reorder: false,
                showLoader: true,
                groupOption,
                groupLists,
                propertyLists,
                optionLists,
                optionParentData,
                view,
                relation,
                showAdvance: false,
                typeOption: ['currency', 'date', 'decimal', 'file', 'image', 'integer', 'json', 'percentage', 'phone', 'relation', 'select', 'string'],
                addProperty: new Form({
                    name: null,
                    type: null,
                    group_id: null,
                    relation: null,
                    isUnique: 0,
                    hasMultiple: 0,
                    hasDate: 0,
                    allow: 1,
                    isSpecial: 0,
                    digit: null,
                    digit1: 16,
                    digit2: 2,
                }),
                addGroup: new Form({
                    name: null,
                }),
                isImporting: false,
                hasCreated: null,
                searchfield: '',
                eNoti: {title: null, content: null, type: null},
            },
            computed: {
                digitExample: function () {
                    return (parseInt(this.addProperty['digit1']) + parseInt(this.addProperty['digit2']) > 27) ? 'Too many digits' : "9".repeat(this.addProperty['digit1']).replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '.' + "9".repeat(this.addProperty['digit2']);
                }
            },
            methods: {
                modalShow(modal) {
                    this.$refs[modal].open();
                },
                modalHide(modal) {
                    this.$refs[modal].close();
                },
                getData() {
                    return new Promise((resolve, reject) => {
                        axios.get('getdata')
                            .then(response => {
                                console.log(response.data);
                                this.groupOption = response.data.groupOption;
                                this.groupLists = response.data.groupLists;
                                this.propertyLists = response.data.propertyLists;
                                this.optionLists = response.data.optionLists;
                                this.optionParentData = response.data.optionParentData;
                                resolve(response.data);
                            })
                            .catch(error => {
                                console.log(error.response.data);
                                reject(error.response.data);
                            });
                    });
                },
                notify(content, type = null, title = null) {
                    this.eNoti.type = type;
                    this.eNoti.content = content;
                    this.eNoti.title = title;
                    this.$refs.notification.show(this.eNoti);
                },
                onSubmit(form) {
                    switch (form) {
                        case 'addGroup':
                            this.$refs['modalLoader'].open();
                            this.isImporting = true;
                            this[form].post('./addGroup')
                                .then(function (response) {
                                    this.isImporting = false;
                                    this.hasCreated = response.message;
                                    this.$refs['modalSuccess'].open();
                                    this.$refs['modalLoader'].close();
                                    this.$refs['modalAddGroup'].close();
                                    this.getData();
                                }.bind(this))
                                .catch(response => {
                                    if (!this[form].errors.errors) {
                                        this.addGroup.errors.errors = {'name': [this.addGroup['name'] + ' group is already existed']};
                                    }
                                    this.$refs['modalLoader'].close();
                                });
                            break;
                        case 'addProperty':
                            this.$refs['modalLoader'].open();
                            this.isImporting = true;

                            switch (this[form]['type']) {
                                case 'currency':
                                    this[form]['digit1'] = 12;
                                    this[form]['digit2'] = 2;
                                    this[form]['digit'] = [this[form]['digit1'] + this[form]['digit2'], this[form]['digit2']];
                                    break;
                                case 'percentage':
                                    this[form]['digit1'] = 7;
                                    this[form]['digit2'] = 4;
                                    this[form]['digit'] = [this[form]['digit1'] + this[form]['digit2'], this[form]['digit2']];
                                    break;
                                case 'decimal':
                                    this[form]['digit'] = [parseInt(this[form]['digit1']) + parseInt(this[form]['digit2']), this[form]['digit2']];
                                    if (parseInt(this.addProperty['digit1']) + parseInt(this.addProperty['digit2']) > 27) {
                                        this.$refs['modalLoader'].close();
                                        break
                                    }
                                    break;
                                default:
                                    break;
                            }
                            if (this[form]['type'] != 'relation') {
                                this[form]['relation'] = null;
                            }
                            this[form].post('./addProperty')
                                .then(function (response) {
                                    console.log(response.message);
                                    this.isImporting = false;
                                    this.hasCreated = response.message.name;
                                    this.getData();
                                    this.addProperty.isUnique = 0;
                                    this.addProperty.digit1 = 16;
                                    this.addProperty.digit2 = 2;
                                    this.addProperty.hasActive = 1;
                                    this.addProperty.hasDate = 0;
                                    this.addProperty.allow = 1;
                                    this.addProperty.isSpecial = 0;
                                    this.addProperty.hasMultiple = 0;
                                    this.$refs['modalSuccess'].open();
                                    this.$refs['modalLoader'].close();
                                    this.$refs['modalAddGroup'].close();
                                }.bind(this))
                                .catch(response => {
                                    if (!this[form].errors.errors) {
                                        alert('Fail: Tell worakorn.');
                                    }
                                    this.$refs['modalLoader'].close();
                                });
                            break;

                    }
                },
            },

        });
    </script>
@endsection
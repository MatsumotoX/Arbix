@extends('layouts.app') @section('title', 'LINE - Rich Menu')

@section('searchfield')
    <input v-model="searchfield" type="search" placeholder="Search...">
@endsection

@section('header')
    Apply for Leave
@endsection

@section('linknav')
    <span>Home  / </span>
    <span>HR  /</span>
    <span>User  /</span>
    <span>Leave /</span>
    <span style="color: #525252;"> Apply</span>
@endsection

@section('style')
    <link rel="stylesheet" href="/css/sgrid.css">
@endsection

@section('content')
    <section id="formeec">

        <div class="cd-bubble">

            <div class="row">
                <div class="col-md-8 col-lg-6">
                    <eec-inputbox :value.sync="addLeave['value']" placeholder="ชื่อ" @input="addLeave['value'] = $event" @clear="addLeave.errors.clear('value')"
                                  type="disabled"></eec-inputbox>
                    <span class="help is-danger" v-if="addLeave.errors.has('value')" v-text="addLeave.errors.get('value')"></span>

                    <eec-inputbox :value.sync="addLeave['leaveType']" placeholder="ประเภท" @input="addLeave['leaveType'] = $event"
                                  @clear="addLeave.errors.clear('leaveType')" type="select" :option="type" class='cd-mt'
                    ></eec-inputbox>
                    <span class="help is-danger" v-if="addLeave.errors.has('leaveType')" v-text="addLeave.errors.get('leaveType')"></span>

                    <eec-inputbox :value.sync="addLeave['leaveDate']" placeholder="วันลา" @input="addLeave['leaveDate'] = $event"
                                  @clear="addLeave.errors.clear('leaveDate')"
                                  type="daterange" class='cd-mt'></eec-inputbox>
                    <span class="help is-danger" v-if="addLeave.errors.has('leaveDate')" v-text="addLeave.errors.get('leaveDate')"></span>

                    <eec-inputbox :value.sync="addLeave['reason']" placeholder="เหตุผล" @input="addLeave['reason'] = $event" @clear="addLeave.errors.clear('reason')"
                                  type="textarea"
                                  class='cd-mt'></eec-inputbox>
                    <span class="help is-danger" v-if="addLeave.errors.has('reason')" v-text="addLeave.errors.get('reason')"></span>

                    <eec-inputbox :value.sync="addLeave['attachment']" placeholder="Attachment" @input="previewImage($event)" @clear="addLeave.errors.clear('attachment')"
                                  type="image"
                                  class='cd-mt'></eec-inputbox>
                    <span class="help is-danger" v-if="addLeave.errors.has('attachment')" v-text="addLeave.errors.get('attachment')"></span>

                    <button @click="onSubmit" :disabled="addLeave.errors.any()" class="cd-btn cd-btn-mt cd-mt">Submit Leave</button>

                </div>
                <div class="col-lg-6">
                    <div class="image-preview cd-btn-mt" v-if="imageData.length > 0">
                        <img class="preview" :src="imageData">
                    </div>
                </div>
            </div>

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
                addLeave: new Form({
                    value: userInfo['value'],
                    user_id: userInfo['id'],
                    leaveType: null,
                    leaveDate: null,
                    reason: null,
                    attachment: null,
                }),
                eNoti: {title: null, content: null, type: null},
                type,
                imageData: ""
            },

            methods: {
                onSubmit: function () {
                    this.addLeave.post('./storeLeave')
                        .then(response => {
                            this.notify(response.message, 'success');
                            this.addLeave.value = userInfo['value'];
                            this.addLeave.user_id = userInfo['id'];
                            console.log(response.data)
                        })
                        .catch(response => {
                            // console.log(response);
                            if (response.errors) {
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
                            }else{
                                this.notify('Tell Worakorn.', 'danger');
                            }
                        })
                },
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
<template>
    <div id="preferenceReorder">
        <div class="row" v-if="group">
            <div class="col-md-6">
                <eec-inputbox :value.sync="selectGroup['group1']" placeholder="Group" @input="groupSelected('group1', $event)" :option="group.column[1].option"
                              type="select" :enable="allowSelect"
                              allowfilter="true" filtertype="contains"></eec-inputbox>
            </div>
            <div class="col-md-6">
                <eec-inputbox :value.sync="selectGroup['group2']" placeholder="Group" @input="groupSelected('group2', $event)" :option="group.column[1].option"
                              type="select" :enable="allowSelect"
                              allowfilter="true" filtertype="contains"></eec-inputbox>
            </div>
        </div>

        <reorder-list v-if="isSelect" @reorder="reorder($event)" style="margin-top: 20px;" :listprop1="propertyList[selectGroup.group1]"
                      :listprop2="propertyList[selectGroup.group2]"></reorder-list>

        <div style="text-align: center; padding-top:20px;">
            <button class="eec_button" @click="saveReorder">Save</button>
            <button class="eec_button" style="margin-left: 10px;" @click="$emit('cancel')">Cancel</button>
        </div>

        <notify ref="notification" notitype="success" content="test"></notify>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                selectGroup: new Form({
                    group1: null,
                    group2: null
                }),
                group: null,
                propertyList: null,
                isSelect: false,
                reorderList: new Form({
                    group1: null,
                    list: null,
                    group2: null,
                    list2: null,
                    switch: false,
                }),
                allowSelect: true,
                eNoti: {title: null, content: null, type: null},
            };
        },
        created() {
            this.getData();
        },
        watch: {
        },
        methods: {
            getData: function () {
                return new Promise((resolve, reject) => {
                    axios.get('./getReorder')
                        .then(response => {
                            this.group = response.data.group;
                            this.propertyList = response.data.propertyList;
                            console.log(response);
                            resolve(response.data);
                        })
                        .catch(error => {
                            console.log(error);
                            reject(error.response);
                        });
                });
            },
            groupSelected: function (args, event) {
                this.selectGroup[args] = event;
                this.isSelect = true;

            },
            reorder: function (args) {
                this.reorderList.list = args[0];
                this.reorderList.list2 = args[1];
                this.reorderList.group1 = this.selectGroup.group1;
                this.reorderList.group2 = this.selectGroup.group2;
                if (args[0].length != this.propertyList[this.selectGroup.group1].length){
                    this.allowSelect = false;
                    this.reorderList.switch = true;
                }
                // console.log(args);
            },
            saveReorder: function(){
                return new Promise((resolve, reject) => {
                    this.reorderList.post('./saveReorder')
                        .then(response => {
                            this.allowSelect = true;
                            this.notify('Reordered Successfully', 'success');

                            console.log(response);
                            resolve(response.data);
                        })
                        .catch(error => {
                            console.log(error);
                            reject(error.response);
                        });
                });
            },
            notify(content, type = null, title = null) {
                this.eNoti.type = type;
                this.eNoti.content = content;
                this.eNoti.title = title;
                this.$refs.notification.show(this.eNoti);
            },
        }
    };
</script>


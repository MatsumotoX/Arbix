<template>
    <div id="app">
        <ejs-grid ref='grid' :searchSettings='searchOptions' :dataSource="parentRecords" :allowSorting='true'
                  :allowGrouping='false' :childGrid="childGrid">
            <e-columns>
                <e-column field='property_id' headerText='ID' :isPrimaryKey='true' :visible="false"></e-column>
                <e-column field='name' headerText='Property' textAlign='Left'></e-column>
                <e-column field='group.name' headerText='Group'></e-column>
            </e-columns>
        </ejs-grid>
        <notify ref="notification" notitype="success" content="test"></notify>
    </div>
</template>
<script>
    import {DetailRow, Sort, Search, Toolbar, Edit} from "@syncfusion/ej2-vue-grids";

    export default {
        data() {
            return {
                searchOptions: {ignoreCase: true},
                parentRecords: this.parentdata,
                childGrid: {
                    dataSource: this.data,
                    queryString: 'property_id',
                    toolbar: ['Add', 'Edit', 'Delete', 'Update', 'Cancel'],
                    editSettings: {showDeleteConfirmDialog: true, allowEditing: true, allowAdding: true, allowDeleting: true},
                    columns: [
                        {field: 'id', headerText: 'ID', isPrimaryKey:true, visible: false},
                        {field: 'property_id', headerText: 'Property ID', visible: false},
                        {field: 'name', headerText: 'Option', allowEditing: true},
                    ],
                    actionBegin: function (args) { //Add the handler here...
                        if (args.requestType === "add") {
                            args.data.property_id = this.parentDetails.parentKeyFieldValue;
                        }
                    },
                    actionComplete: this.actionComplete,
                },
                eNoti: {title: null, content: null, type: null},
            }
        },

        provide: {
            grid: [DetailRow, Sort, Toolbar, Edit]
        },
        props: {
            data: {required: true},
            parentdata: {required: true},
            searchfield: '',
        },

        watch: {
            searchfield: function (newVal, oldVal) { // watch it
                let searchText = newVal;
                this.$refs.grid.search(searchText);
            },
            parentdata: function (newVal) {
                this.parentRecords = newVal;
            }
        },

        methods: {
            notify(content, type = null, title = null) {
                this.eNoti.type = type;
                this.eNoti.content = content;
                this.eNoti.title = title;
                this.$refs.notification.show(this.eNoti);
            },
            actionComplete(args) {

                console.log(args);
                
                switch (args.requestType) {
                    case 'save':
                        if (args.action == 'add') {
                            return new Promise((resolve, reject) => {
                                axios['post']('./addOption', args)
                                    .then(response => {
                                        args.data.id = response.data.data.id;
                                        this.notify('Option Added.', 'success');

                                        resolve(response.data);
                                    })
                                    .catch(error => {
                                        alert('Error: Tell Worakorn.');
                                        reject(error.response.data);
                                    });
                            });
                        } else {
                            return new Promise((resolve, reject) => {
                                axios['post']('./updateOption', args)
                                    .then(response => {
                                        this.notify('Option Updated.', 'success');
                                        resolve(response.data);
                                    })
                                    .catch(error => {
                                        alert('Error: Tell Worakorn.');
                                        reject(error.response.data);
                                    });
                            });
                        }
                        break;
                    case 'delete':
                        return new Promise((resolve, reject) => {
                            axios['post']('./deleteOption', args)
                                .then(response => {
                                    this.notify('Option Deleted.', 'success');
                                    resolve(response.data);
                                })
                                .catch(error => {
                                    alert('Error: Tell Worakorn.');
                                    reject(error.response.data);
                                });
                        });
                        break;
                    default:
                        break;
                }
            },
        },

    }
</script>

<style>
    .e-detailrowcollapse {
        width: 31px;
    }

    .e-detailrowexpand {
        width: 31px;
    }

    .e-detailheadercell {
        width: 31px !important;
        padding: 0 !important;
    }
</style>


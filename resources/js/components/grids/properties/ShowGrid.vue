<template>
    <div id="app">
        <ejs-grid ref='grid' :searchSettings='searchOptions' :toolbar="toolbar" :dataSource="data" :allowSorting='true' :allowGrouping='false'
                  :toolbarClick='clickHandler'>
            <e-columns>
                <e-column field='id' headerText='ID' :isPrimaryKey='true' :visible="false"></e-column>
                <e-column v-if="!isfile" field='value' :headerText='property'></e-column>
                <e-column v-else :headerText='property' :template='viewFile'></e-column>
                <e-column field='created_by.user.name' :valueAccessor='valueAccess' headerText='Created by'></e-column>
                <e-column field='created_at' headerText='Created at'></e-column>
                <e-column field='isActive' headerText='Active' :visible="false"></e-column>
            </e-columns>
        </ejs-grid>
    </div>
</template>
<script>
    import {Sort, Group, Search, Toolbar, Edit} from "@syncfusion/ej2-vue-grids";

    export default {
        data() {
            return {
                toolbar: [{text: 'Add / Edit', tooltipText: 'Add Group', prefixIcon: 'e-add', id: 'add'}],
                searchOptions: {ignoreCase: true},
                editSettings: {allowEditing: true, allowAdding: true, allowDeleting: true, mode: 'Dialog'},
            };
        },

        provide: {
            grid: [Sort, Group, Toolbar, Edit]
        },
        props: {
            data: {required: true},
            property: {required: true},
            isfile: {required: true},
            searchfield: '',
        },

        watch: {
            searchfield: function (newVal, oldVal) { // watch it
                let searchText = newVal;
                this.$refs.grid.search(searchText);
            }
        },

        methods: {
            valueAccess: function (field, data, column) {
                if (this.property == 'ID') {
                    return data['createdBy']['user']['name'];
                } else {
                    if (data['createdBy_id'] == 0) {
                        return 'System';
                    }
                    return data['created_by']['user']['name'];
                }
            },
            clickHandler: function (args) {
                console.log(this.data);
                // console.log(args);
                switch (args.item.id) {
                    case 'add':
                        this.$emit('add');
                        break;
                }
            },
            actionComplete(args) {
                if ((args.requestType === 'save')) {
                    // console.log(args);
                    // if (args.data.name != args.previousData.name) {
                    return new Promise((resolve, reject) => {
                        axios['post']('./updateGroup', args.data)
                            .then(response => {
                                resolve(response.data);
                            })
                            .catch(error => {
                                alert('Error: Tell Worakorn.');
                                reject(error.response.data);
                            });
                    });
                    // }
                }
            },
            viewFile: function () {
                return {
                    template: Vue.component('viewFileTemplate', {
                        template: `<div>
                    <a :href="'/viewfile/' + this.data.value" target="_blank">View</a> | <a :href="'/downloadfile/' + this.data.value" target="_blank">Download</a>
                </div>`,
                        data: function () {
                            return {
                                data: {},
                            }
                        },
                    })
                }
            },
        },

    }
</script>


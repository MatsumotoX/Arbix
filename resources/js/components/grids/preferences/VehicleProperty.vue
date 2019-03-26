<template>
    <div id="app">
        <ejs-grid ref='grid' :searchSettings='searchOptions' :toolbar="toolbar" :dataSource="gridData" :beforeBatchSave="updateData" :allowSorting='true'
                  :allowGrouping='true' :sortSettings='sortOptions'
                  :editSettings='editSettings' :toolbarClick='clickHandler'>
            <e-columns>
                <e-column field='name' headerText='Property' :isPrimaryKey='true'></e-column>
                <e-column field='group' headerText='Group' editType="dropdownedit" :edit="groupOptions"></e-column>
                <e-column field='show' headerText='Show' type="boolean" editType='booleanedit' displayAsCheckBox="true"></e-column>
            </e-columns>
        </ejs-grid>
    </div>
</template>
<script>
    import {Sort, Group, Search, Toolbar, Edit} from "@syncfusion/ej2-vue-grids";
    import { Query } from '@syncfusion/ej2-data';

    export default {
        data() {
            return {
                gridData:'',
                groupOptions: {
                    params:   {
                        dataSource: this.grouplists,
                        fields: {text:"name",value:"name"},
                        query: new Query(),
                        actionComplete: () => false
                    }
                },
                sortOptions: { columns: [{ field: 'group', direction: 'Ascending' }] },
                toolbar: [{text: 'Add', tooltipText: 'Add Property', prefixIcon: 'e-add', id: 'add'}, 'Delete', 'Update', 'Cancel', {text: 'Reorder', tooltipText: 'Reorder Property', prefixIcon: 'e-icon-sortdirect', id: 'reorder', align: 'Right'}],
                searchOptions: {ignoreCase: true},
                editSettings: {allowEditing: true, allowAdding: true, allowDeleting: true, mode: 'Batch'},
            };
        },

        provide: {
            grid: [Sort, Group, Toolbar, Edit]
        },
        props: {
            data: {required: true},
            grouplists: {required: true},
            searchfield: '',
        },

        mounted() {
            this.gridData = this.data;
        },

        watch: {
            searchfield: function (newVal, oldVal) { // watch it
                let searchText = newVal;
                this.$refs.grid.search(searchText)
                ;
            },
            data: function (newVal) {
                this.gridData = newVal;
            }
        },

        methods: {
            dataBound: function() {
                if (this.autofit) {
                    this.$refs.grid.autoFitColumns();
                }else {
                    return false;
                }
            },
            clickHandler: function (args) {
                // console.log(args);
                switch (args.item.id) {
                    case 'add':
                        this.$emit('add');
                        break;
                    case 'reorder':
                        this.$emit('reorder');
                        break;
                }
            },
            updateData: function (args) {
                console.log(args);
                return new Promise((resolve, reject) => {
                    axios['post']('./updateProperty', args)
                        .then(response => {
                            console.log(response.data.message);
                            this.$emit('getdata');
                            resolve(response.data);
                        })
                        .catch(error => {
                            alert('Fail: Tell worakorn.');
                            reject(error.response.data);
                        });
                });
            },

        },

    }
</script>


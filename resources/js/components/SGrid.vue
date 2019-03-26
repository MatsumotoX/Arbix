<template>
    <div id="app">
        <ejs-grid ref='grid' :searchSettings='searchOptions' :toolbar="toolbar" :dataSource="data" :beforeBatchSave="updateData" :allowSorting='true' :allowGrouping='true'
                  :editSettings='editSettings' :toolbarClick='clickHandler'>
            <e-columns>
                <e-column field='name' headerText='Property' :isPrimaryKey='true'></e-column>
                <e-column field='group' headerText='Group' editType="dropdownedit"></e-column>
                <e-column field='show' headerText='Show' type="boolean" editType='booleanedit' displayAsCheckBox="true"></e-column>
            </e-columns>
        </ejs-grid>
    </div>
</template>
<script>
    import Vue from "vue";
    import {GridPlugin, Sort, Group, Search, Toolbar, Edit} from "@syncfusion/ej2-vue-grids";

    Vue.use(GridPlugin);

    export default {
        data() {
            return {
                toolbar: [{text: 'Add', tooltipText: 'Add Property', prefixIcon: 'e-add', id: 'add'}, 'Delete', 'Update', 'Cancel'],
                searchOptions: {ignoreCase: true},
                pageSettings: {pageSize: 20},
                editSettings: {allowEditing: true, allowAdding: true, allowDeleting: true, mode: 'Batch'},
            };
        },

        provide: {
            grid: [Sort, Group, Toolbar, Edit]
        },
        props: {
            data: {required: true},
            searchfield: '',
        },

        watch: {
            searchfield: function (newVal, oldVal) { // watch it
                let searchText = newVal;
                this.$refs.grid.search(searchText);
            }
        },

        methods: {
            clickHandler: function (args) {
                // console.log(args);
                switch (args.item.id) {
                    case 'add':
                        this.$emit('add');
                        break;
                }
            },
            updateData: function (args) {
                console.log(args);
                return new Promise((resolve, reject) => {
                    axios['post']('./updateProperty', args)
                        .then(response => {
                            console.log(response.data);
                            alert('success');
                            resolve(response.data);
                        })
                        .catch(error => {
                            alert('fail');
                            reject(error.response.data);
                        });
                });
            }
        },

    }
</script>


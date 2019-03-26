<template>
    <ejs-splitbutton :click="onSubmit" :select="onSelect" v-if="type == 'splitbutton'" cssClass="white" :items='items' :content='content'></ejs-splitbutton>
</template>

<script>

    import {SplitButtonPlugin} from "@syncfusion/ej2-vue-splitbuttons";
    Vue.use(SplitButtonPlugin);

    export default {
        data() {
            return {};
        },
        props: {
            content: {required: true},
            items: {required: false},
            type: {required: true},
        },
        methods: {
            onSubmit() {
                this.$emit('click', this.content);
            },
            onSelect(args) {
                this.$emit('click', args.item.properties.text);
            }
        },
    }
</script>

<style>
    .e-split-btn-wrapper {
        width: 100%;
        height: 100%
    }
    .e-split-btn {
        width: 80%;
    }
    .e-dropdown-btn {
        width: 20%;
    }
    .white {
        background-color: white;
        color: #474d56;
    }
</style>
<template>
    <div>
        <div class="cd-tab">
            <ul class="cd-top-tab">
                <li v-for="(tab, tabindex) in tabs" :key='tabindex' @click="selectTab(tab.id)" :class="getClass(tab.isActive)">
                    <a>{{ tab.name }}</a>
                </li>
            </ul>
            <button v-if="buttonvalue" @click="$emit('click')" :disabled="buttonenable" class="cd-button">{{ buttonvalue }}</button>
        </div>
        <div class="cd-tab-content">

            <slot :buttonvalue="buttonvalue" :buttonenable="buttonenable"></slot>

        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                // screenWidth: screen.width,
                tabs: [],
                index: 0,
            };
        },

        props: {
            navigation: {default: true},
            buttonvalue: {default: null},
            buttonenable: {default: true},
            showbutton: {default: true}
        },

        // computed: {
        //     screenWidth: function() {
        //         return screen.width;
        //     }
        // },

        created() {
            this.tabs = this.$children;
        },

        mounted() {
            if (this.buttonvalue && this.showbutton) {
                this.tabs.forEach(tab => {
                    tab.ebutton = {buttonvalue: this.buttonvalue, buttonenable: this.buttonenable};
                });
            }
            this.$on('click', function () {
                this.$parent.$emit('click');
            })
        },

        watch: {
            buttonenable: function (newVal) {
                if (this.showbutton) {
                    this.tabs.forEach(tab => {
                        tab.ebutton = {buttonvalue: this.buttonvalue, buttonenable: this.buttonenable};
                    });
                }
            }
        },

        methods: {
            getClass(args) {
                if (args) {
                    return 'cd-tab-is-active cd-tab-button'
                } else {
                    return 'cd-tab-button'
                }
            },
            // getTabClass(args){
            //     if (this.screenWidth <= 768) {
            //         return 'tabs'
            //     } else {
            //         return 'cd-tab'
            //     }
            // },
            selectTab(selectedTab) {
                this.tabs.forEach(tab => {
                    tab.isActive = (tab.href == '#' + selectedTab);
                });
            },

            previousIndex() {
                this.index--;
                if (this.index < 0) {
                    this.index = this.tabs.length - 1;
                }
                this.tabs.forEach(tab => {
                    tab.isActive = (tab.href == '#' + this.index);
                });
            },

            nextIndex() {
                this.index++;
                if (this.index > this.tabs.length - 1) {
                    this.index = 0;
                }
                this.tabs.forEach(tab => {
                    tab.isActive = (tab.href == '#' + this.index);
                });
            }
        }
    }
</script>
<style>
    .cd-tab > ul > li:hover {
        color: #2CA9D4 !important;
    }

    .cd-tab > button:hover {
        background-color: #2CA9D4b3;
        border-color: #2CA9D4b3;
    }

    .cd-tab-content {

        float: left;
        padding-left: 2.0em;
        padding-right: 1.0em;
        width: 100%;
        min-height: 68vh;
    }

    .cd-tab-button {
        /*padding: 0 3.0em;*/
        padding-bottom: 1.0em;
        font-size: 14px;
        line-height: 40px;
        height: 40px;
        overflow: hidden;
        text-overflow: ellipsis;
        cursor: pointer;
    }

    .cd-tab-is-active > a {
        color: #2CA9D4 !important;
    }

    .cd-button {
        visibility: hidden;
        font-size: 0;
    }

    @media only screen and (min-width: 1024px) {

        .cd-tab {

            float: left;
            width: 184px;
            min-height: 68vh;

        }

        .cd-tab-content {

            padding-left: 3.0em;
            padding-right: 0;
            border-left: 1px solid #e8e8e8;
            width: 620px;
        }

        .cd-tab-button {
            padding-left: 3.0em;
            font-size: 14px;
            line-height: 40px;
            height: 40px;
            margin-top: 4px;
            margin-bottom: 8px;
            overflow: hidden;
            text-overflow: ellipsis;
            cursor: pointer;
        }

        .cd-tab-is-active {
            background-color: #2CA9D414;
            border-right: 3px solid #2CA9D4;
        }

        .cd-tab-is-active > a {
            font-weight: 700;
            color: #2CA9D4 !important;
        }

        .cd-button {
            visibility: visible;
            margin-top: 20px;
            position: relative;
            width: 154px;
            left: 15px;
            color: #fff;
            background-color: #2CA9D4;
            border-color: #2CA9D4;
            text-shadow: 0 -1px 0 rgba(0, 0, 0, .12);
            box-shadow: 0 2px 0 rgba(0, 0, 0, .045);
            line-height: 1.499;
            display: inline-block;
            font-weight: 400;
            text-align: center;
            touch-action: manipulation;
            cursor: pointer;
            white-space: nowrap;
            padding: 0 15px;
            font-size: 14px;
            border-radius: 4px;
            height: 32px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        button:disabled,
        button[disabled] {
            background-color: #2CA9D4 !important;
            cursor: not-allowed !important;
        }

        .cd-scope-button {
            /*font-size: 0;*/
        }
    }

    @media only screen and (min-width: 1366px) {

        .cd-tab-content {

            width: 840px;
        }

        .cd-tab {

            width: 224px;

        }

        .cd-button {

            width: 184px;
            left: 20px;
        }
    }

    @media only screen and (min-width: 1920px) {

        .cd-tab-content {

            width: 1370px;
        }

        .cd-tab {

            min-height: 74vh;

        }

        .cd-tab-content {

            min-height: 74vh;
        }
    }

    @media only screen and (max-width: 1023px) {

        .cd-tab-is-active {
            line-height: 30px !important;
            border-bottom: 2px solid #2CA9D4;
        }

        .cd-top-tab {
            margin-left: 1.5em;
        }

        .cd-tab {
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-box-align: stretch;
            -ms-flex-align: stretch;
            align-items: stretch;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            line-height: 24px;
            overflow: hidden;
            overflow-x: auto;
            white-space: nowrap;
        }

        .cd-tab a {
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            border-bottom: 1px solid #dbdbdb;
            color: #4a4a4a;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            margin-bottom: -1px;
            padding: 6px 12px;
            vertical-align: top;
        }

        .cd-tab a:hover {
            border-bottom-color: #363636;
            color: #363636;
        }

        .cd-tab li {
            display: block;
        }

        .cd-tab li.is-active a {
            border-bottom-color: #00d1b2;
            color: #2CA9D4;
        }

        .cd-tab ul {
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            border-bottom: 1px solid #dbdbdb;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1;
            -ms-flex-negative: 0;
            flex-shrink: 0;
            -webkit-box-pack: start;
            -ms-flex-pack: start;
            justify-content: flex-start;
        }

        .cd-tab ul.is-left {
            padding-right: 10px;
        }

        .cd-tab ul.is-center {
            -webkit-box-flex: 0;
            -ms-flex: none;
            flex: none;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            padding-left: 10px;
            padding-right: 10px;
        }

        .cd-tab ul.is-right {
            -webkit-box-pack: end;
            -ms-flex-pack: end;
            justify-content: flex-end;
            padding-left: 10px;
        }

        .cd-tab .icon:first-child {
            margin-right: 8px;
        }

        .cd-tab .icon:last-child {
            margin-left: 8px;
        }

        .cd-tab.is-centered ul {
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
        }

        .cd-tab.is-right ul {
            -webkit-box-pack: end;
            -ms-flex-pack: end;
            justify-content: flex-end;
        }

        .cd-tab.is-boxed a {
            border: 1px solid transparent;
            border-radius: 3px 3px 0 0;
            padding-bottom: 5px;
            padding-top: 5px;
        }

        .cd-tab.is-boxed a:hover {
            background-color: whitesmoke;
            border-bottom-color: #dbdbdb;
        }

        .cd-tab.is-boxed li.is-active a {
            background-color: white;
            border-color: #dbdbdb;
            border-bottom-color: transparent !important;
        }

        .cd-tab.is-fullwidth li {
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1;
            -ms-flex-negative: 0;
            flex-shrink: 0;
        }

        .cd-tab.is-toggle a {
            border: 1px solid #dbdbdb;
            margin-bottom: 0;
            padding-bottom: 5px;
            padding-top: 5px;
            position: relative;
        }

        .cd-tab.is-toggle a:hover {
            background-color: whitesmoke;
            border-color: #b5b5b5;
            z-index: 2;
        }

        .cd-tab.is-toggle li + li {
            margin-left: -1px;
        }

        .cd-tab.is-toggle li:first-child a {
            border-radius: 3px 0 0 3px;
        }

        .cd-tab.is-toggle li:last-child a {
            border-radius: 0 3px 3px 0;
        }

        .cd-tab.is-toggle li.is-active a {
            background-color: #00d1b2;
            border-color: #00d1b2;
            color: white;
            z-index: 1;
        }

        .cd-tab.is-toggle ul {
            border-bottom: none;
        }

        .cd-tab.is-small {
            font-size: 11px;
        }

        .cd-tab.is-small a {
            padding: 2px 8px;
        }

        .cd-tab.is-small.is-boxed a, .cd-tab.is-small.is-toggle a {
            padding-bottom: 1px;
            padding-top: 1px;
        }

        .cd-tab.is-medium {
            font-size: 18px;
        }

        .cd-tab.is-medium a {
            padding: 10px 16px;
        }

        .cd-tab.is-medium.is-boxed a, .cd-tab.is-medium.is-toggle a {
            padding-bottom: 9px;
            padding-top: 9px;
        }

        .cd-tab.is-large {
            font-size: 28px;
        }

        .cd-tab.is-large a {
            padding: 14px 20px;
        }

        .cd-tab.is-large.is-boxed a, .cd-tab.is-large.is-toggle a {
            padding-bottom: 13px;
            padding-top: 13px;
        }
    }

</style>


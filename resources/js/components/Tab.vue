<template>
    <div v-show="isActive">
        <div class="row">
            <div class="col-6 col-lg-12">
                <h4 class="cd-tab-header">
                    {{ name }}
                </h4>
            </div>
            <div class="col-6">
                <div class="cd-scope-hover">
                    <button v-if='ebutton' @click="$parent.$emit('click')" :disabled="ebutton.buttonenable" class="cd-scope-button">{{ebutton.buttonvalue}}</button>
                </div>
            </div>
        </div>
        <slot></slot>
    </div>
</template>

<script>
    export default {
        props: {
            name: {required: true},
            id: {required: true},
            selected: {default: false},
            button: {default: false},
        },

        data() {
            return {
                isActive: false,
                ebutton: false
            };
        },

        watch: {
            button: function (newVal) {
                if (newVal) {
                    this.ebutton = this.button;
                }
            }
        },

        computed: {
            href() {
                return '#' + this.id;
            }
        },

        mounted() {
            this.isActive = this.selected;
            if (this.button) {
                this.ebutton = this.button;
            }
        },
    }
</script>

<style>
    .cd-tab-header {
        color: #525252;
        padding-top: 0.7em;
        font-size: 20px;
        font-weight: 500;
    }

    .cd-scope-button {
        font-size: 0;
        position: fixed;
        height: 0;
        width: 0;
        visibility: hidden;
    }

    @media only screen and (max-width: 1023px) {
        .cd-scope-button {
            /*float: right;*/
            position: absolute;
            visibility: visible;
            margin-top: 10px;
            margin-right: 1.0em;
            width: auto;
            min-width: 75px;
            right: 0;
            color: #fff;
            background-color: #C2000b;
            border-color: #C2000b;
            /*line-height: 1.499;*/
            /*display: inline-block;*/
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

        .cd-scope-hover > button:hover {
            background-color: #c2000bb3;
            border-color: #c2000bb3;
        }

        button:disabled,
        button[disabled]{
            background-color: #C2000b !important;
            cursor: not-allowed !important;
        }
    }
</style>
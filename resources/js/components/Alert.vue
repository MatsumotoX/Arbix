<template>
    <div id="alert-area" class="alert-area">
        <div :class="'alert-box ' + type + '-box'">
            <div class="alert-content">
                <slot></slot>
            </div>
            <a href="#" class="alert-close" @click="$emit('close')"> </a>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            type: '',
        },
        mounted() {
            console.log('alert mounted')
        }
    }
</script>

<style scoped>
    * {
        padding: 0;
        margin: 0;
    }

    /* ALERT RELATED CLASS (START) */

    .alert-area {
        max-height: 100%;
        position: fixed;
        left: 20px;
        right: 20px;
        z-index: 1000000000000;
    }

    .alert-box {
        color: #777777;
        background: white;
        font-size: 14px;
        line-height: 3rem;
        padding: 10px 15px;
        margin: 5px 10px;
        position: relative;
        border-radius: 2px;
        transition: opacity 0.5s ease-in;
    }

    .alert-box b {
        font-weight: 600;
    }

    .simple-box {
        background: black !important;
    }
    .success-box {
        background: #dff0d8 !important;
        color: #3c763d;
        border-color: #d6e9c6;
    }
    .info-box {
        background: #d9edf7 !important;
        color: #31708f;
        border-color: #bce8f1;
    }
    .danger-box {
        background: #f2dede !important;
        color: #a94442;
        border-color: #ebccd1;
    }
    .warning-box {
        background: #fcf8e3 !important;
        color: #8a6d3b;
        border-color: #faebcc;
    }

    .alert-box.hide {
        opacity: 0;
    }

    .alert-close {
        background: transparent;
        width: 12px;
        height: 12px;
        position: absolute;
        top: 15px;
        right: 15px;
    }

    .alert-close:before,
    .alert-close:after {
        content: '';
        width: 15px;
        border-top: solid 2px #777777;
        position: absolute;
        top: 5px;
        right: -1px;
        display: block;
    }

    .alert-close:before {
        transform: rotate(45deg);
    }

    .alert-close:after {
        transform: rotate(135deg);
    }

    .alert-close:hover:before,
    .alert-close:hover:after {
        border-top: solid 2px #d8d8d8;
    }

    @media (max-width: 767px) and (min-width: 481px) {
        .alert-area {
            left: 100px;
            right: 100px;
        }
    }

    @media (max-width: 767px)  {
        .alert-area {
            top: 5px;
        }
    }

    @media (min-width: 768px) {
        .alert-area {
            width: 350px;
            left: auto;
            right: 0;
            bottom: 5px;
        }
    }
</style>
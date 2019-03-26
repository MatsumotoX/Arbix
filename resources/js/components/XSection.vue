<template>
    <section :id="id" class="cd-section" :class="background">
        <div class="content-wrapper">
            <slot></slot>
        </div>
    </section>
</template>

<script>
    export default {
        props: {
            id: {default: 'section'},
            background: {default: 'section'},
        },

        mounted() {
            console.log('kuy')
        }
    }
</script>


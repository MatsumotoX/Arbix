<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CryptovationX</title>

    {{--For Login--}}
    <link rel="shortcut icon" href="/assets/images/SVG/Logo.svg" type="image/x-icon">
    <link rel="stylesheet" href="/css/login/reset.css"> <!-- CSS reset -->
    <link rel="stylesheet" href="/css/login/style.css"> <!-- Resource style -->
    <link rel="stylesheet" href="/css/landing.css"> <!-- Resource style -->
    {{--<link rel="stylesheet" href="/css/login/demo.css"> <!-- Demo style -->--}}


</head>
<body>

<div id="asset">

    <nav class="cd-vertical-nav">
        <ul class="js-signin-modal-trigger">
            <li><a href="#home" class="active"><span class="label">Home</span></a></li>
            <li><a href="#cryptovationx"><span class="label">Product</span></a></li>
            <li><a href="#experiances"><span class="label">Product</span></a></li>
            <li><a href="#ava"><span class="label">Algorithm</span></a></li>
            <li><a href="#ava2"><span class="label">Performance</span></a></li>

            @if (Auth::check())
                <li><a href="/apps/hrs/users/users/settings/index"><span class="label">To Console</span></a></li>

            @else
                <li><a href="#login" @click="showLogin = true"> <span class="label">Log in</span></a></li>
            @endif
        </ul>
    </nav><!-- .cd-vertical-nav -->

    <button class="cd-nav-trigger cd-image-replace">Open navigation<span aria-hidden="true"></span></button>

    <home></home>
    <cryptovationx></cryptovationx>
    <cryptovationx2></cryptovationx2>
    <ava></ava>
    <ava2></ava2>

    {{--<section id="product" class="cd-section">--}}
        {{--<div class="content-wrapper">--}}
            {{--<h2>Events</h2>--}}
            {{--<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto numquam, totam iusto officia earum perferendis, culpa ad atque eveniet praesentium--}}
                {{--nobis--}}
                {{--expedita similique beatae tenetur. Distinctio vel tenetur, id cum.</p>--}}
        {{--</div>--}}
    {{--</section><!-- cd-section -->--}}

    {{--<section id="product2" class="cd-section">--}}
        {{--<div class="content-wrapper">--}}
            {{--<h2>Help</h2>--}}
            {{--<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto numquam, totam iusto officia earum perferendis, culpa ad atque eveniet praesentium--}}
                {{--nobis--}}
                {{--expedita similique beatae tenetur. Distinctio vel tenetur, id cum.</p>--}}
        {{--</div>--}}
    {{--</section><!-- cd-section -->--}}

    {{--<section id="algorithm" class="cd-section">--}}
        {{--<div class="content-wrapper">--}}
            {{--<h2>Share</h2>--}}
            {{--<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto numquam, totam iusto officia earum perferendis, culpa ad atque eveniet praesentium--}}
                {{--nobis--}}
                {{--expedita similique beatae tenetur. Distinctio vel tenetur, id cum.</p>--}}
        {{--</div>--}}
    {{--</section><!-- cd-section -->--}}

    {{--<section id="performance" class="cd-section">--}}
        {{--<div class="content-wrapper">--}}
            {{--<h1>Vertical Fixed Navigation <b>#2</b></h1>--}}
            {{--<a href="#product" class="cd-scroll-down cd-image-replace">scroll down</a>--}}
        {{--</div>--}}
    {{--</section><!-- cd-section -->--}}

    @include('landingpages._login')

</div>

@include('layouts._javascript')

<script src="/js/views/manifest.js"></script>
<script src="/js/views/vendor.js"></script>
<script type="text/javascript" src="/js/views/landing/index.js"></script> <!-- Resource JavaScript -->

{{--For Login--}}
<script src="/js/login/placeholders.min.js"></script> <!-- polyfill for the HTML5 placeholder attribute -->
<script src="/js/login/main.js"></script> <!-- Resource JavaScript -->
<script src="/js/landingPage.js"></script> <!-- Resource JavaScript -->

<script>

    new Vue({
        el: '#asset',

        data: {
            ref,
            eNoti: {title: null, content: null, type: null},
            showLogin: false,
            showSignup: false,
            loginPassword: 'Show',
            hasRef: false,
        },

        mounted() {
            if (this.ref) {
                this.hasRef = true;
            }
        },

        methods: {
            hide(args) {
                switch (args) {
                    case 'login-password':
                        switch (this.$refs['login-password'].type) {
                            case 'password':
                                this.$refs['login-password'].type = 'text';
                                this.loginPassword = 'Hide';
                                break;
                            case 'text':
                                this.$refs['login-password'].type = 'password';
                                this.loginPassword = 'Show';
                                break;
                        }
                        break;
                }
            },
            notify(content, type = null, title = null) {
                this.eNoti.type = type;
                this.eNoti.content = content;
                this.eNoti.title = title;
                this.$refs.notification.show(this.eNoti);
            },
        },
    });
</script>


</body>
</html>
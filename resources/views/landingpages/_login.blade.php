<div class="cd-signin-modal js-signin-modal login-fontsize" :class="{cdVisible: showLogin}" @click="showLogin = false"> <!-- this is the entire modal form, including the background -->
    <div class="cd-signin-modal__container" @click.stop> <!-- this is the container wrapper -->
        <ul class="cd-signin-modal__switcher js-signin-modal-switcher js-signin-modal-trigger">
            <li><a href="#0" :class="{cdTabSelected : !showSignup}" @click="showSignup = false">Sign in</a></li>
            <li><a href="#0" :class="{cdTabSelected : showSignup}" @click="showSignup = true">Sign up</a></li>
        </ul>

        <div class="cd-signin-modal__block js-signin-modal-block" :class="{cdSelected : !showSignup}" data-type="login"> <!-- log in form -->
            <form class="cd-signin-modal__form" method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                @csrf
                <p class="cd-signin-modal__fieldset">
                    <label class="cd-signin-modal__label cd-signin-modal__label--email cd-signin-modal__label--image-replace" for="signin-email">E-mail</label>
                    <input class="cd-signin-modal__input cd-signin-modal__input--full-width cd-signin-modal__input--has-padding cd-signin-modal__input--has-border" id="emailx" type="email" placeholder="E-mail" name="email" required>
                    <span class="cd-signin-modal__error">Error message here!</span>
                </p>

                <p class="cd-signin-modal__fieldset">
                    <label class="cd-signin-modal__label cd-signin-modal__label--password cd-signin-modal__label--image-replace" for="signin-password">Password</label>
                    <input ref="login-password" class="cd-signin-modal__input cd-signin-modal__input--full-width cd-signin-modal__input--has-padding cd-signin-modal__input--has-border" id="passwordx" type="password"  placeholder="Password" name="password" required>
                    <a href="#0" class="cd-signin-modal__hide-password" @click="hide('login-password')">@{{loginPassword}}</a>
                    <span class="cd-signin-modal__error">Error message here!</span>
                </p>

                <p class="cd-signin-modal__fieldset">
                    <input type="checkbox" id="remember-me" checked class="cd-signin-modal__input ">
                    <label for="remember-me" style="color: black; font-size: 1.4rem;">Remember me</label>
                </p>

                <p class="cd-signin-modal__fieldset">
                    <input class="cd-signin-modal__input cd-signin-modal__input--full-width" type="submit" value="Login">
                </p>
            </form>

            <p class="cd-signin-modal__bottom-message js-signin-modal-trigger login-fontsizerem"><a href="#0" data-signin="reset">Forgot your password?</a></p>
        </div> <!-- cd-signin-modal__block -->

        <div class="cd-signin-modal__block js-signin-modal-block" :class="{cdSelected : showSignup}" data-type="signup"> <!-- sign up form -->
            <form method="POST" action="{{ route('register') }}" aria-label="{{ __('Register') }}" class="cd-signin-modal__form">
                @csrf

                <p class="cd-signin-modal__fieldset login-fontsize">
                    <label class="cd-signin-modal__label cd-signin-modal__label--username cd-signin-modal__label--image-replace" for="signup-username">Username</label>
                    <input name="signup-username" class="cd-signin-modal__input cd-signin-modal__input--full-width cd-signin-modal__input--has-padding cd-signin-modal__input--has-border" id="name" type="text" placeholder="Username" required autofocus readonly>
                    <span class="cd-signin-modal__error">Error message here!</span>
                </p>

                <p class="cd-signin-modal__fieldset">
                    <label class="cd-signin-modal__label cd-signin-modal__label--email cd-signin-modal__label--image-replace" for="signup-email">E-mail</label>
                    <input name="signup-email" class="cd-signin-modal__input cd-signin-modal__input--full-width cd-signin-modal__input--has-padding cd-signin-modal__input--has-border" id="email" type="email" placeholder="E-mail" required>
                    <span class="cd-signin-modal__error">Error message here!</span>
                </p>

                <p class="cd-signin-modal__fieldset">
                    <label class="cd-signin-modal__label cd-signin-modal__label--password cd-signin-modal__label--image-replace" for="signup-password">Password</label>
                    <input name="signup-password" class="cd-signin-modal__input cd-signin-modal__input--full-width cd-signin-modal__input--has-padding cd-signin-modal__input--has-border" id="password" type="password"  placeholder="Password" required>
                    <a href="#0" class="cd-signin-modal__hide-password js-hide-password">Show</a>
                    <span class="cd-signin-modal__error">Error message here!</span>
                </p>

                <p class="cd-signin-modal__fieldset">
                    <label class="cd-signin-modal__label cd-signin-modal__label--password cd-signin-modal__label--image-replace" for="password_confirmation">Confirm Password</label>
                    <input name="password_confirmation" class="cd-signin-modal__input cd-signin-modal__input--full-width cd-signin-modal__input--has-padding cd-signin-modal__input--has-border" id="password-confirm" type="password"  placeholder="Confirm Password" required>
                    <span class="cd-signin-modal__error">Error message here!</span>
                </p>

                <p class="cd-signin-modal__fieldset">
                    <label class="cd-signin-modal__label cd-signin-modal__label--referral cd-signin-modal__label--image-replace" for="signup-referral">Referral</label>
                    <input name="signup-referral" class="cd-signin-modal__input cd-signin-modal__input--full-width cd-signin-modal__input--has-padding cd-signin-modal__input--has-border" id="referral" type="text"  placeholder="Referral (Optional)" v-model="ref" :disabled="hasRef" required>
                    <span class="cd-signin-modal__error">Error message here!</span>
                </p>

                <p class="cd-signin-modal__fieldset">
                    <input type="checkbox" id="accept-terms" class="cd-signin-modal__input ">
                    <label for="accept-terms" style="color: black; font-size: 1.4rem">I agree to the <a href="#0" style="color: #148abc;">Terms</a></label>
                </p>

                <p class="cd-signin-modal__fieldset">
                    <input class="cd-signin-modal__input cd-signin-modal__input--full-width cd-signin-modal__input--has-padding" type="submit" value="Create account">
                </p>
            </form>
        </div> <!-- cd-signin-modal__block -->

        <div class="cd-signin-modal__block js-signin-modal-block" data-type="reset"> <!-- reset password form -->
            <p class="cd-signin-modal__message login-fontsizerem" style="color: black;">Lost your password? Please enter your email address. You will receive a link to create a new password.</p>

            <form class="cd-signin-modal__form">
                <p class="cd-signin-modal__fieldset">
                    <label class="cd-signin-modal__label cd-signin-modal__label--email cd-signin-modal__label--image-replace" for="reset-email">E-mail</label>
                    <input class="cd-signin-modal__input cd-signin-modal__input--full-width cd-signin-modal__input--has-padding cd-signin-modal__input--has-border" id="reset-email" type="email" placeholder="E-mail">
                    <span class="cd-signin-modal__error">Error message here!</span>
                </p>

                <p class="cd-signin-modal__fieldset">
                    <input class="cd-signin-modal__input cd-signin-modal__input--full-width cd-signin-modal__input--has-padding" type="submit" value="Reset password">
                </p>
            </form>

            <p class="cd-signin-modal__bottom-message js-signin-modal-trigger login-fontsizerem"><a href="#0" data-signin="login">Back to log-in</a></p>
        </div> <!-- cd-signin-modal__block -->
        <a href="#0" class="cd-signin-modal__close js-close">Close</a>
    </div> <!-- cd-signin-modal__container -->
</div> <!-- cd-signin-modal -->
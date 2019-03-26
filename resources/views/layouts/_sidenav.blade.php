<nav class="cd-side-nav">
    <ul>
        <li class="cd-label">Main</li>
        <li class="has-children overview">
            <a href="#0">Overview</a>

            <ul>
                <li><a href="#0">All Data</a></li>
                <li><a href="#0">Category 1</a></li>
                <li><a href="#0">Category 2</a></li>
            </ul>
        </li>
        <li class="has-children notifications">
            <a href="#0">Notifications<span class="count">3</span></a>

            <ul>
                <li><a href="#0">All Notifications</a></li>
                <li><a href="#0">Friends</a></li>
                <li><a href="#0">Other</a></li>
            </ul>
        </li>

        <li class="has-children comments">
            <a href="#0">Comments</a>

            <ul>
                <li><a href="#0">All Comments</a></li>
                <li><a href="#0">Edit Comment</a></li>
                <li><a href="#0">Delete Comment</a></li>
            </ul>
        </li>
    </ul>

    <ul>
        <li class="cd-label">Manage</li>

        <li class="has-children users {{ (Request::is('properties/hrs/users/*') || Request::is('preferences/hrs/users/*') ) ? "active" : "" }}">
            <a href="#0">Users</a>

            <ul>
                <li><a href="/properties/hrs/users/index">All Users</a></li>
                <li><a href="/properties/hrs/users/create">Add Users</a></li>
                <li><a href="/properties/hrs/users/show">View Users</a></li>
                <li><a href="/properties/hrs/users/import">Import Users</a></li>
                <li><a href="/preferences/hrs/users/index">Preferences</a></li>
            </ul>
        </li>
    </ul>


    <ul>
        <li class="cd-label">LINE</li>

        <li class="has-children settings {{ Request::is('lines/settings/*') ? "active" : "" }}">
            <a href="#0">Settings</a>

            <ul>
                <li><a href="/lines/settings/richMenus/index">Rich Menu</a></li>
                <li><a href="/lines/settings/flexMessages/index">Flex Message</a></li>
            </ul>
        </li>

    </ul>

    <ul>
        <li class="cd-label">SETTING</li>

        <li class="has-children users {{ (Request::is('apps/*/settings/*') || Request::is('landingpage/*') ) ? "active" : "" }}">
            <a href="#0">Account</a>

            <ul>
                <li><a href="/apps/hrs/users/users/settings/index">Account Settings</a></li>
                <li><a href="/apps/hrs/users/users/referral/index">Affiliate Program</a></li>
                <li><a href="/landingpage/setting">Landing Page</a></li>
            </ul>
        </li>

    </ul>

</nav>
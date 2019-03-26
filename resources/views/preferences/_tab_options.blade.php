<tab name="Select Options" key=2 id=2>
    <div style="padding-top: 10px;">
        <form method="POST" action="/projects"
              @submit.prevent="onSubmit('importGoogle')">

            <optiongrid ref="optionGrid" :data.sync="optionLists" :parentdata.sync="optionParentData" :searchfield="searchfield"
                        style="margin-top: 20px; margin-bottom: 20px;"></optiongrid>

        </form>
    </div>
</tab>


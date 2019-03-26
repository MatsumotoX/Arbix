<tab name="Properties Group" key=1 id=1>
    <div style="padding-top: 10px;">
        <form method="POST" action="/projects"
              @submit.prevent="onSubmit('importGoogle')">

            <groupgrid ref="groupGrid" :data.sync="groupLists" :searchfield="searchfield" @add="modalShow('modalAddGroup')" @getdata="getData()"
                       style="margin-top: 20px; margin-bottom: 20px;"></groupgrid>

        </form>
    </div>
</tab>


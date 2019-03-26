{{--Modal--}}
<sweet-modal ref="modalSuccess" icon="success">
    "@{{ hasCreated }}" has been added.
</sweet-modal>

<sweet-modal ref="modalImportSuccess" icon="success">
    Import Successfully.
</sweet-modal>

<sweet-modal ref="modalLoader">
    <spinner
            style="margin: auto; margin-bottom: 36px;"
            color="#4fc08d"
            :size="88"
            :depth="4"
            :speed="0.5">
    </spinner>
    Adding...
</sweet-modal>

<sweet-modal ref="modalImportLoader">
    <spinner
            style="margin: auto; margin-bottom: 36px;"
            color="#4fc08d"
            :size="88"
            :depth="4"
            :speed="0.5">
    </spinner>
    Importing...
</sweet-modal>


<div wire:ignore>
    <div id="{{ $quillId }}" style="max-height:400px;overflow:auto;"></div>
</div>

@script
<script>
    const toolbarOptions = [
        [{ 'font': [] }],
        ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
        ['image'],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
        [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
        [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
        [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
        [{ 'align': [] }],
        //['blockquote', 'code-block'],
        ['link', 'image', 'video', 'formula'],
        //[{ 'header': 1 }, { 'header': 2 }],               // custom button values
        //[{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
        //[{ 'direction': 'rtl' }],                         // text direction
        //['clean']                                         // remove formatting button
    ];
    Quill.register('modules/imageResize', QuillResizeModule);
    Quill.register("modules/imageCompressor", imageCompressor);
    
    var Parchment = Quill.import("parchment");
    let Custom1 = new Parchment.StyleAttributor('padding', 'padding', {scope: Parchment.Scope.INLINE});
    Quill.register(Custom1, true);
    let Custom2 = new Parchment.StyleAttributor('text-align', 'text-align', {scope: Parchment.Scope.INLINE});
    Quill.register(Custom2, true);
    let Custom3 = new Parchment.StyleAttributor('border-radius', 'border-radius', {scope: Parchment.Scope.INLINE});
    Quill.register(Custom3, true);

    // no usar const quill ni var quill si no no puedo acceder desde javascript
    <?php
        $_quill="quill".md5($quillId); // para poder tener varios en la misma pagina

    ?>
    //console.log("{{$_quill}}");
    //alert(idid);
    //console.log(this);
    tema=(@js($theme));
    disabled=false;
    if(tema=="snowdisabled"){
        tema="snow";
        disabled=true;
    }
    {{$_quill}} = new Quill('#' + @js($quillId), {
        theme: tema,
        modules: {
            imageResize: {
                displaySize: true
            },
            toolbar: toolbarOptions,
            imageCompressor: {
            quality: 0.8,
            maxWidth: 800, // default
            maxHeight: 800, // default
            imageType: 'image/jpeg'
            },
        }
    });
    {{$_quill}}.root.innerHTML = $wire.get('value');
    {{$_quill}}.on('text-change', function () {
        let value = {{$_quill}}.root.innerHTML;
        @this.set('value', value);
    });
    if(disabled){
        {{$_quill}}.disable();
    }
    window.addEventListener('refreshquill', event => { 
        {{$_quill}}.root.innerHTML = event.detail[0].ob;
        //quill.root.innerHTML = $wire.get('value');
        //alert(event.detail[0].ob);
    });
    //window.addEventListener('disablequill', event => { 
    //    {{$_quill}}.disable();
    //});
    window.addEventListener('addtoquill', event => { 
        {{$_quill}}.focus();
        indice={{$_quill}}.getSelection().index;
        //quill.insertText(indice, event.detail[0].ob, 'script',true);
        //quill.disable();
        {{$_quill}}.clipboard.dangerouslyPasteHTML(indice,event.detail[0].ob);
        //quill.enable();
        //quill.root.innerHTML = event.detail[0].ob;
        //quill.insertText(0, 'Hello', 'bold', true);
        //alert(quill.clipboard.convert(event.detail[0].ob));
        //alert(quill.clipboard.dangerouslyPasteHtml(0, "raw  html"));
        //quill.root.innerHTML = event.detail[0].ob;
        //quill.root.innerHTML = $wire.get('value');
        //alert(event.detail[0].ob);
    });
</script>
@endscript
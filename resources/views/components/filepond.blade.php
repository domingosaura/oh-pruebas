<div 
id="xx{{ $attributes['wire:model'] }}"
x-data x-init="
FilePond.registerPlugin(FilePondPluginImagePreview);
FilePond.registerPlugin(FilePondPluginFileValidateSize);

pond{{isset($attributes['varname'])?$attributes['varname']:''}}=FilePond.create(
$refs.input{{ $attributes['wire:model'] }},
{
    //acceptedFileTypes: ['image/*'],
    server: {
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    }
}
);

pond{{isset($attributes['varname'])?$attributes['varname']:''}}.setOptions({
    labelIdle: 'Arrastra aquí tus archivos o <span class = \'filepond--label-action\'> pulsa para examinar <span>',
    labelInvalidField: 'El campo contiene archivos inválidos',
    labelFileWaitingForSize: 'Esperando tamaño',
    labelFileSizeNotAvailable: 'Tamaño no disponible',
    labelFileLoading: 'Cargando',
    labelFileLoadError: 'Error durante la carga',
    labelFileProcessing: 'Subiendo',
    labelFileProcessingComplete: 'Subida completa',
    labelFileProcessingAborted: 'Subida cancelada',
    labelFileProcessingError: 'Error durante la subida',
    labelFileProcessingRevertError: 'Error durante la reversión',
    labelFileRemoveError: 'Error durante la eliminación',
    labelTapToCancel: 'toca para cancelar',
    labelTapToRetry: 'tocar para reintentar',
    labelTapToUndo: 'tocar para deshacer',
    labelButtonRemoveItem: 'Eliminar',
    labelButtonAbortItemLoad: 'Cancelar',
    labelButtonRetryItemLoad: 'Reintentar',
    labelButtonAbortItemProcessing: 'Cancelar',
    labelButtonUndoItemProcessing: 'Deshacer',
    labelButtonRetryItemProcessing: 'Reintentar',
    labelButtonProcessItem: 'Subir',
    labelMaxFileSizeExceeded: 'El archivo es demasiado grande',
    labelMaxFileSize: 'El tamaño máximo del archivo es {filesize}',
    labelMaxTotalFileSizeExceeded: 'Tamaño total máximo excedido',
    labelMaxTotalFileSize: 'El tamaño total máximo del archivo es {filesize}',
    labelFileTypeNotAllowed: 'Archivo de tipo inválido',
    fileValidateTypeLabelExpectedTypes: 'Espera {allButLastType} o {lastType}',
    imageValidateSizeLabelFormatError: 'Tipo de imagen no soportada',
    imageValidateSizeLabelImageSizeTooSmall: 'La imagen es demasiado pequeña',
    imageValidateSizeLabelImageSizeTooBig: 'La imagen es demasiado grande',
    imageValidateSizeLabelExpectedMinSize: 'El tamaño mínimo es {minWidth} x {minHeight}',
    imageValidateSizeLabelExpectedMaxSize: 'El tamaño máximo es {maxWidth} x {maxHeight}',
    imageValidateSizeLabelImageResolutionTooLow: 'La resolución es demasiado baja',
    imageValidateSizeLabelImageResolutionTooHigh: 'La resolución es demasiado alta',
    imageValidateSizeLabelExpectedMinResolution: 'La resolución mínima es {minResolution}',
    imageValidateSizeLabelExpectedMaxResolution: 'La resolución máxima es {maxResolution}',
    maxParallelUploads:10,
    itemInsertInterval:4,
    instantUpload:true,
    chunkUploads:true,
    maxFileSize:'{{ $attributes['maxsize'] }}',
      onprocessfiles: () => {
        postprocesado_init();
        @this.saveimage('{{ $attributes['wire:model'] }}');
        pond{{isset($attributes['varname'])?$attributes['varname']:''}}.removeFiles();
        postprocesado_endd();
        {{isset($attributes['w5sec'])?'postprocesado_endd5();':'postprocesado_endd();'}}
      },
    allowMultiple: {{isset($attributes['multiple'])?'true':'false'}},
        server: {
        process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
            @this.upload('{{ $attributes['wire:model'] }}',file,load,error,(event) => {
            //console.log(event.detail.progress);
      progress(event.detail.progress, event.detail.progress, 100);})
        },
        revert: (filename,load) => {
        @this.removeUpload('{{ $attributes['wire:model'] }}',filename,load)
        },
    },
});
" 
wire:ignore>
<input type="file" name="file" wire:model="{{ $attributes['wire:model'] }}" id="{{ $attributes['wire:model'] }}" x-ref="input{{ $attributes['wire:model'] }}">
</div>
<script>
</script>


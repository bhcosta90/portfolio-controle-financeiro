@extends('layouts.app')
@section('content')
<div class='card'>
    <div class='card-header'>
        <div class='float-start'><h4>Editar cobrança</h4></div>
    </div>
    <div class='card-body'>{!! form($form) !!}</div>
</div>
@endsection

@section('js')
<script>
$(function(){
    $('#recurrency').change(function(){
        const labelDueDate = $('label[for=due_date]');
        const labelValue = $('label[for=value]');

        if ($(this).val() == -1) {
            labelDueDate.html(labelDueDate.data('default'))
            labelValue.html(labelValue.data('default'))
            $('#parcel').prop('disabled', true);
        }

        if ($(this).val() == -2) {
            labelDueDate.html(labelDueDate.data('parcel'))
            labelValue.html(labelValue.data('parcel'))
            $('#parcel').prop('disabled', false);
        }

        if ($(this).val() != -1 && $(this).val() != -2) {
            labelDueDate.html(labelDueDate.data('recurrency'))
            labelDueDate.html(labelDueDate.data('recurrency'))
            labelValue.html(labelValue.data('recurrency'))
            $('#parcel').prop('disabled', true);
        }

    }).trigger('change');
})
</script>
@endsection

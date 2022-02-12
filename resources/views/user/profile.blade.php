@extends('layouts.app')

@section('content')
<div class="container rounded bg-white">
    <div class="row">
        <div class="col-md-3 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5"><img class="rounded-circle mt-5"
                    src="http://via.placeholder.com/180x279"><span
                    class="font-weight-bold">{{ $user->name }}</span><span class="text-black-50">{{ $user->email }}</span><span>
                </span></div>
        </div>
        <div class="col-md-5 border-right">
            {!! form_start($form) !!}
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-right">{{ __('Profile Settings') }}</h4>
                </div>
                <div class="row">
                    <div class="col-md-6">{!! form_row($form->name) !!}</div>
                    <div class="col-md-6">{!! form_row($form->email) !!}</div>
                    <div class="col-md-12">{!! form_row($form->password) !!}</div>
                </div>
                <div class="row">
                    <div class="col-md-12">{!! form_row($form->balance_value) !!}</div>
                    {{-- <div class="col-md-12"><label class="labels">Address</label><input type="text" class="form-control"
                            placeholder="enter address" value=""></div>
                    <div class="col-md-12"><label class="labels">Email ID</label><input type="text" class="form-control"
                            placeholder="enter email id" value=""></div>
                    <div class="col-md-12"><label class="labels">Education</label><input type="text"
                            class="form-control" placeholder="education" value=""></div> --}}
                </div>
                {{-- <div class="row mt-3">
                    <div class="col-md-6"><label class="labels">Country</label><input type="text" class="form-control"
                            placeholder="country" value=""></div>
                    <div class="col-md-6"><label class="labels">State/Region</label><input type="text"
                            class="form-control" value="" placeholder="state"></div>
                </div> --}}
                <div class="mt-3 text-center"><button class="btn btn-primary profile-button" type="submit">{{ __('Save Profile') }}</button></div>
            </div>
            {!! form_end($form) !!}
        </div>
        <div class="col-md-4">
                <div class="p-3 py-5">
                {!! form_start($sharedForm) !!}
                    <div class="d-flex justify-content-between align-items-center experience"><span>{{ __('Compartilhar Sistema') }}</span><button class="border px-3 p-1 add-experience"><i
                                class="fa fa-plus"></i>&nbsp;{{ __('E-mail') }}</button></div><br>
                    <div class="col-md-12">{!! form_row($sharedForm->email) !!}</div> <br>
                {!! form_end($sharedForm) !!}

                @if(count($shareds))
                    <table class='table table-striped table-hover'>
                        <tr>
                            <th>E-mail</th>
                            <td class='min'></td>
                            <td class='min'></td>
                        </tr>
                        @foreach($shareds as $shared)
                        <tr>
                            <td>{{ $shared->email }}</td>
                            <td>
                                <i class='{{ $shared->icon_status }}'></i>
                            </td>
                            <td>
                                {!! btnLinkDelIcon(route('user.shared.delete', $shared->uuid, 'fas fa-trash-alt', 'btn-outline-danger btn-sm btn-link-delete')) !!}
                            </td>
                        </tr>
                        @endforeach
                    </table>
                @endif

                </div>
        </div>
    </div>
</div>

@endsection

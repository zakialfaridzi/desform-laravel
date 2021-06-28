<button class="btn btn-md btn-block dropdown-toggle" data-toggle="dropdown" style="background-color:#F8B310">Menu &nbsp;&nbsp;&nbsp; <span class="caret"></span></button>
<ul class="dropdown-menu dropdown-menu-right">
    <li class="dropdown-header highlight">Bagikan</li>
    @if ($form->status === $form::STATUS_OPEN)
        <li><a data-toggle="modal" data-target="#share-form" data-backdrop="static" data-keyboard="false"><i class="fas fa-share-square"></i> Bagikan Form</a></li>
    @endif

    @if ($form->user_id === $current_user->id)
        <li><a data-toggle="modal" data-target="#form-collaborate" data-backdrop="static" data-keyboard="false"><i class="fas fa-people-arrows"></i> Kolaborator Form</a></li>
    @endif

    @if (in_array($form->status, [$form::STATUS_OPEN, $form::STATUS_CLOSED]))
        <li class="dropdown-header highlight"> Respon</li>
        @if (Route::currentRouteName() !== 'forms.responses.index')
            <li><a href="{{ route('forms.responses.index', $form->code) }}"><i class="fas fa-eye"></i> Lihat Respon</a></li>
        @endif
        @if ($form->responses()->has('fieldResponses')->exists())
            <li><a href="{{ route('forms.response.export', $form->code) }}"><i class="fas fa-file-download"></i> Unduh Respon</a></li>
            <li><a id="delete-button" data-href="{{ route('forms.responses.destroy.all', $form->code) }}" data-message="Anda yakin ingin menghapus seluruh respon?"><i class="fas fa-trash-alt"></i> Hapus Seluruh Respon</a></li>
        @endif
    @endif

    <li class="dropdown-header highlight"> Menu Form</li>
    @if (in_array($form->status, [$form::STATUS_PENDING, $form::STATUS_CLOSED]))
        <li><a href="{{ route('forms.open', $form->code) }}" data-method="post"><i class="fas fa-envelope-open"></i> Buka Untuk Di Respon</a></li>
    @endif
    @if ($form->status === $form::STATUS_OPEN)
        <li><a href="{{ route('forms.close', $form->code) }}" data-method="post"><i class="fas fa-envelope"></i> Tutup Untuk Di Respon</a></li>
    @endif

    <li class="divider"></li>

    <li><a href="{{ route('forms.edit', $form->code) }}"><i class="fas fa-edit"></i> Ubah Form</a></li>
    @if ($form->status !== $form::STATUS_OPEN)
        <li><a id="delete-button" data-href="{{ route('forms.destroy', $form->code) }}" data-item="form - {{ $form->title }}"><i class="fas fa-trash"></i> Hapus Form</a></li>
    @endif
    <li><a href="{{ route('forms.index') }}"><i class="fas fa-folder-open"></i> Lihat Seluruh Forms</a></li>
</ul>

<div id="form-collaborate" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-indigo">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title" style="color:#F8B310"><strong>Kolaborator Form</strong></h6>
            </div>

            <form id="edit-form-collaborators" method="post" action="{{ route('form.collaborators.store', $form->code) }}" autocomplete="off">
                @csrf
                <div class="modal-body">

                    <ul class="media-list media-list-bordered">
                        <li class="media">
                            <div class="media-left">
                                <a class="btn border-default text-default btn-flat btn-icon btn-sm"><i class="fas fa-user-circle fa-2x"></i></a>
                            </div>

                            <div class="media-body">
                                <div class="media-heading text-semibold">{{ $form->user->full_name }} (Anda)</div>
                                <span class="text-muted">{{ $form->user->email }}</span>
                            </div>

                            <div class="media-right media-middle">
                                <span class="label label-primary">Pemilik Form</span>
                            </div>
                        </li>

                        @if ($form->collaborationUsers->isNotEmpty())
                            @foreach ($form->collaborationUsers as $user)
                                <li class="media">
                                    <div class="media-left">
                                        <a class="btn border-default text-default btn-flat btn-icon btn-sm"><i class="icon-user"></i></a>
                                    </div>

                                    <div class="media-body">
                                        <div class="media-heading text-semibold">{{ ($user->hasVerifiedEmail()) ? $user->full_name : '-' }}</div>
                                        <span class="text-muted">{{ $user->email }}</span>
                                    </div>

                                    <div class="media-right media-middle">
                                        <button type="button" class="btn btn-danger btn-xs delete-collaborator" data-href="{{ route('form.collaborator.destroy', [$form->code, $user->id]) }}">Hapus</button>
                                    </div>
                                </li>
                            @endforeach
                        @endif
                    </ul>

                    <div class="row mt-30">
                        <div class="col-sm-12">
                            <h6 class="text-semibold">Tambah Kolaborator</h6>
                            <div class="form-group" id="add-collaborators">
                                <input type="text" name="collaborator_emails" id="collaborator_emails" class="form-control tags-input" placeholder="Email" required="required">
                            </div>

                            <div class="form-group hidden collaborate-email-actions">
                                <button type="submit" class="btn bg-teal btn-xs submit" data-loading-text="Menambahkan">Tambah</button>
                                <button type="button" class="btn btn-default btn-xs cancel" data-dismiss="modal">Batal</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
    <script>
        $(function () {
            $("#add-collaborators").on('mousedown', function () {
                $(".collaborate-email-actions").removeClass('hidden');
                $('#collaborate_email_message').removeClass('hidden');
            });

            $("#form-collaborate").on('hidden.bs.modal', function () {
                $(".collaborate-email-actions").addClass('hidden');
                $('#collaborate_email_message').addClass('hidden');

                $('#collaborator_emails').tagsinput('removeAll');;
            });

            $('#edit-form-collaborators').validate({
                submitHandler: function (form) {
                    addCollaborators(form);
                    return false;
                },
                rules: {
                    collaborator_emails: 'required',
                    optional_email_message: {
                        maxlength: 30000,
                        minWords: 3,
                    }
                },
            });

            $('.delete-collaborator').on('click', function () {
                $delete_button = $(this);

                $.ajax({
                    url: $delete_button.data('href'),
                    type: 'POST',
                    data: { _token: csrf_token, _method: 'DELETE' },
                    dataType: 'json'
                })
                .done(function (response) {
                    if (response.success) {
                        console.log('yes', $delete_button);
                        $delete_button.closest('li.media').fadeOut('slow');
                    } else {
                        notify('error', 'Ada yang error: ' + response.error);
                    }
                });
            });

            function addCollaborators(form) {
                var $form = $(form);

                submit_button = $form.find('.submit');
                submit_button.button('loading');

                $.ajax({
                    url: $form.attr('action'),
                    type: $form.attr('method'),
                    data: $form.serialize(),
                    dataType: 'json'
                })
                .done(function (response) {
                    submit_button.button('reset');

                    if (response.success) {
                        $form.closest('#form-collaborate').modal('hide');

                        notify('success', 'Kolaborator berhasil ditambahkan');
                    } else {
                        notify('error', 'Ada yang error: ' + response.error);
                    }
                });
            }
        });
    </script>
@endpush

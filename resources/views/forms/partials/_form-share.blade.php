<div id="share-form" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-indigo">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title"  style="color:#F8B310"><strong>Bagikan Form</strong></h6>
            </div>

                <div class="modal-body">
                    <h6 class="text-semibold">Bagikan via tautan berikut:</h6>
                    <div class="row mb-20">
                        <div class="col-sm-12">
                            <div class="input-group">
                                <input type="text" class="form-control" id="form-url" value="{{ route('forms.view', $form->code) }}" readonly>
                                <span class="input-group-btn">
                                    <button id="copy" class="btn bg-indigo btn-icon btn-xs" type="button" data-popup="tooltip-copy" title="Copy to Clipboard">Salin</button>
                                </span>
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
            $('[data-popup=tooltip-copy]').tooltip({
                template: '<div class="tooltip"><div class="bg-teal"><div class="tooltip-arrow"></div><div class="tooltip-inner" id="copy-tooltip"></div></div></div>',
                container: 'body'
            });

            $('#copy').on('click', function (e) {
                var input = $('#form-url').select();

                document.execCommand("copy");

                var tooltip = $('#copy-tooltip');
                tooltip.text('Berhasil di salin');
            });

            $('#copy').on('mouseout', function () {
                setTimeout(function () {
                    $('#copy-tooltip').text('Copy to Clipboard');
                }, 3000);
            });
        });
    </script>
@endpush

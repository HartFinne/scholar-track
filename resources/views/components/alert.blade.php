@if (session('success') || session('failure'))
    <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="alertModalLabel">
                        {{ session('success') ? 'Success' : 'Error' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- {!! session('success') ?? session('failure') !!} --}}
                    {!! session('success') ?? session('failure') !!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn {{ session('success') ? 'btn-primary' : 'btn-danger' }}"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
            alertModal.show();
        });
    </script>
@endif

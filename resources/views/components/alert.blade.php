@if (session('success') || session('failure'))
    <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- Dynamically set the modal title based on success or failure -->
                    <h5 class="modal-title" id="alertModalLabel">
                        {{ session('success') ? 'Success' : 'Failure' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Dynamically display the success or failure message -->
                    {{ session('success') ?? session('failure') }}
                </div>
                <div class="modal-footer">
                    <!-- Button style changes based on success or failure -->
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

            // Optionally, automatically hide the modal after a few seconds (if desired)
            // setTimeout(function() {
            //     alertModal.hide();
            // }, 3000);
        });
    </script>
@endif

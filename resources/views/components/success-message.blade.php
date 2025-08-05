@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" id="successAlert">
        <div class="d-flex align-items-center">
            <i class="ph ph-check-circle me-2 text-success fs-5"></i>
            <span>{{ session('success') }}</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <style>
        .alert-success {
            color: #0f5132;
            background-color: #d1e7dd;
            border-color: #badbcc;
            border-radius: 0.5rem;
            padding: 1rem 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
        }
        
        .alert-success .btn-close {
            padding: 0.5rem 0.5rem;
            margin: -0.25rem -0.25rem -0.25rem auto;
        }
        
        .alert-dismissible {
            padding-right: 3rem;
        }
        
        .fade {
            transition: opacity 0.15s linear;
        }
        
        .fade:not(.show) {
            opacity: 0;
        }
        
        .fade.show {
            opacity: 1;
        }
    </style>

    <script>
        // Auto-hide success message after 5 seconds
        setTimeout(function() {
            const successAlert = document.getElementById('successAlert');
            if (successAlert) {
                const bsAlert = new bootstrap.Alert(successAlert);
                bsAlert.close();
            }
        }, 5000);
    </script>
@endif
<div class="profile-view">
    <div class="profile-info">
        <h4>BASIC INFORMATION</h4>

        <div class="container">
            <h1>Manage Profile</h1>

            <div class="card">
                <div class="card-header">
                    <h3>Personal Information</h3>
                </div>
                <div class="card-body">
                    <p><strong>Scholar ID:</strong> {{ $scholar->scholarID }}</p>
                    <p><strong>Email:</strong> {{ $scholar->scEmail }}</p>
                    <p><strong>Phone Number:</strong> {{ $scholar->scPhoneNum }}</p>
                    <p><strong>Status:</strong> {{ $scholar->scStatus }}</p>
                    <!-- Add other fields as necessary -->
                </div>
            </div>
        </div>

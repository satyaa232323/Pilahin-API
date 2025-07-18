@extends('layout')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Scan User</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Scan User Form -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Scan QR Code User</h6>
                </div>
                <div class="card-body">
                    <!-- QR Scanner Section -->
                    <div class="text-center mb-4">
                        <div id="qr-reader"
                            style="width: 100%; height: 300px; border: 2px dashed #e3e6f0; border-radius: 10px; display: flex; align-items: center; justify-content: center; background-color: #f8f9fc;">
                            <div class="text-muted">
                                <i class="fas fa-camera fa-3x mb-3"></i>
                                <p>Klik "Start Scanner" untuk memulai scan QR Code</p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-success mt-3" id="start-scanner">
                            <i class="fas fa-camera"></i> Start Scanner
                        </button>
                        <button type="button" class="btn btn-danger mt-3" id="stop-scanner" style="display: none;">
                            <i class="fas fa-stop"></i> Stop Scanner
                        </button>
                    </div>

                    <!-- Manual Input -->
                    <div class="border-top pt-3">
                        <h6 class="text-muted mb-3">Atau Input Manual:</h6>
                        <form id="scan-user-form">
                            @csrf
                            <div class="form-group">
                                <label for="user_id">User ID / QR Code</label>
                                <input type="text" class="form-control" id="user_id" name="user_id"
                                    placeholder="Masukkan User ID atau hasil scan QR" required>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Cari User
                            </button>
                        </form>
                    </div>

                    <!-- User Info Display -->
                    <div id="user-info" class="mt-4" style="display: none;">
                        <div class="alert alert-success">
                            <h6><i class="fas fa-user"></i> User Ditemukan:</h6>
                            <div id="user-details"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Waste Deposit Form -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-success">Input Data Sampah</h6>
                </div>
                <div class="card-body">
                    <form id="waste-deposit-form" action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="selected_user_id" name="user_id" value="">

                        <div class="form-group">
                            <label for="category">Kategori Sampah <span class="text-danger">*</span></label>
                            <select class="form-control" id="category" name="category" required>
                                <option value="">Pilih Kategori</option>
                                <option value="plastik">Plastik</option>
                                <option value="kertas">Kertas</option>
                                <option value="logam">Logam</option>
                                <option value="kaca">Kaca</option>
                                <option value="organik">Organik</option>
                                <option value="elektronik">Elektronik</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="weight_kg">Berat Sampah (kg) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="weight_kg" name="weight_kg"
                                    placeholder="0.00" step="0.01" min="0.01" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">kg</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="photo_url">Foto Sampah</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="photo_url" name="photo_url"
                                    accept="image/*">
                                <label class="custom-file-label" for="photo_url">Pilih foto sampah...</label>
                            </div>
                            <small class="form-text text-muted">Format: JPG, PNG, JPEG. Max: 2MB</small>
                        </div>

                        <div class="form-group">
                            <label for="location_id">Drop Point <span class="text-danger">*</span></label>
                            <select class="form-control" id="location_id" name="location_id" required>
                                <option value="">Pilih Drop Point</option>
                                <option value="1">Drop Point A - Jl. Sudirman No. 1</option>
                                <option value="2">Drop Point B - Jl. Thamrin No. 5</option>
                                <option value="3">Drop Point C - Jl. Gatot Subroto No. 10</option>
                                <option value="4">Drop Point D - Jl. Kuningan No. 15</option>
                                <option value="5">Drop Point E - Jl. Kemang No. 20</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="points_earned">Poin Yang Didapat <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="points_earned" name="points_earned"
                                    placeholder="0" min="1" required readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text">Poin</span>
                                </div>
                            </div>
                            <small class="form-text text-muted">Poin akan dihitung otomatis berdasarkan berat dan
                                kategori</small>
                        </div>

                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-success btn-lg" disabled id="submit-btn">
                                <i class="fas fa-save"></i> Simpan Data Sampah
                            </button>
                            <button type="reset" class="btn btn-secondary btn-lg ml-2">
                                <i class="fas fa-redo"></i> Reset Form
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-check-circle"></i> Berhasil!
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h5>Data sampah berhasil disimpan!</h5>
                    <p class="text-muted">Poin telah ditambahkan ke akun user.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto calculate points based on weight and category
            const weightInput = document.getElementById('weight_kg');
            const categorySelect = document.getElementById('category');
            const pointsInput = document.getElementById('points_earned');
            const submitBtn = document.getElementById('submit-btn');
            const userIdInput = document.getElementById('user_id');
            const selectedUserIdInput = document.getElementById('selected_user_id');

            // Points per kg for each category
            const pointsPerKg = {
                'plastik': 10,
                'kertas': 8,
                'logam': 15,
                'kaca': 12,
                'organik': 5,
                'elektronik': 20
            };

            function calculatePoints() {
                const weight = parseFloat(weightInput.value) || 0;
                const category = categorySelect.value;

                if (weight > 0 && category && pointsPerKg[category]) {
                    const points = Math.round(weight * pointsPerKg[category]);
                    pointsInput.value = points;
                } else {
                    pointsInput.value = '';
                }

                checkFormValidity();
            }

            function checkFormValidity() {
                const hasUser = selectedUserIdInput.value !== '';
                const hasWeight = weightInput.value !== '' && parseFloat(weightInput.value) > 0;
                const hasCategory = categorySelect.value !== '';
                const hasLocation = document.getElementById('location_id').value !== '';
                const hasPoints = pointsInput.value !== '' && parseInt(pointsInput.value) > 0;

                submitBtn.disabled = !(hasUser && hasWeight && hasCategory && hasLocation && hasPoints);
            }

            // Event listeners
            weightInput.addEventListener('input', calculatePoints);
            categorySelect.addEventListener('change', calculatePoints);
            document.getElementById('location_id').addEventListener('change', checkFormValidity);

            // Handle scan user form
            document.getElementById('scan-user-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const userId = userIdInput.value.trim();

                if (userId) {
                    // Simulate user lookup (replace with actual API call)
                    setTimeout(() => {
                        selectedUserIdInput.value = userId;
                        document.getElementById('user-details').innerHTML = `
                    <strong>ID:</strong> ${userId}<br>
                    <strong>Nama:</strong> User Demo<br>
                    <strong>Email:</strong> user@demo.com
                `;
                        document.getElementById('user-info').style.display = 'block';
                        checkFormValidity();
                    }, 500);
                }
            });

            // Handle file input label update
            document.getElementById('photo_url').addEventListener('change', function(e) {
                const fileName = e.target.files[0]?.name || 'Pilih foto sampah...';
                document.querySelector('.custom-file-label').textContent = fileName;
            });

            // Handle form reset
            document.querySelector('button[type="reset"]').addEventListener('click', function() {
                selectedUserIdInput.value = '';
                document.getElementById('user-info').style.display = 'none';
                document.querySelector('.custom-file-label').textContent = 'Pilih foto sampah...';
                pointsInput.value = '';
                checkFormValidity();
            });

            // QR Scanner simulation (replace with actual QR scanner implementation)
            document.getElementById('start-scanner').addEventListener('click', function() {
                document.getElementById('qr-reader').innerHTML = `
            <div class="text-center">
                <div class="spinner-border text-primary mb-3" role="status"></div>
                <p class="text-muted">Scanning QR Code...</p>
                <p class="small">Arahkan kamera ke QR Code user</p>
            </div>
        `;
                this.style.display = 'none';
                document.getElementById('stop-scanner').style.display = 'inline-block';

                // Simulate QR detection after 3 seconds
                setTimeout(() => {
                    const simulatedQR = 'USER_' + Math.random().toString(36).substr(2, 9);
                    userIdInput.value = simulatedQR;
                    document.getElementById('scan-user-form').dispatchEvent(new Event('submit'));
                    document.getElementById('stop-scanner').click();
                }, 3000);
            });

            document.getElementById('stop-scanner').addEventListener('click', function() {
                document.getElementById('qr-reader').innerHTML = `
            <div class="text-muted">
                <i class="fas fa-camera fa-3x mb-3"></i>
                <p>Klik "Start Scanner" untuk memulai scan QR Code</p>
            </div>
        `;
                this.style.display = 'none';
                document.getElementById('start-scanner').style.display = 'inline-block';
            });

            // Handle form submission
            document.getElementById('waste-deposit-form').addEventListener('submit', function(e) {
                e.preventDefault();

                // Show loading state
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
                submitBtn.disabled = true;

                // Simulate API call
                setTimeout(() => {
                    $('#successModal').modal('show');
                    submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Data Sampah';

                    // Reset form after success
                    this.reset();
                    selectedUserIdInput.value = '';
                    document.getElementById('user-info').style.display = 'none';
                    document.querySelector('.custom-file-label').textContent =
                        'Pilih foto sampah...';
                    pointsInput.value = '';
                    checkFormValidity();
                }, 2000);
            });
        });
    </script>
@endpush

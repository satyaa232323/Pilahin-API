@extends('layout')

@push('styles')
    <style>
        #qr-reader {
            width: 100% !important;
            border: 2px solid #e3e6f0;
            border-radius: 10px;
            overflow: hidden;
        }

        #qr-reader video {
            width: 100% !important;
            height: auto !important;
            max-height: 400px;
            object-fit: cover;
        }

        #qr-reader canvas {
            width: 100% !important;
            height: auto !important;
        }

        @media (max-width: 768px) {
            #qr-reader {
                max-height: 300px;
            }

            #qr-reader video {
                max-height: 250px;
            }
        }

        @media (max-width: 480px) {
            #qr-reader {
                max-height: 250px;
            }

            #qr-reader video {
                max-height: 200px;
            }
        }
    </style>
@endpush

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
                        <div id="qr-reader" style="width: 100%; display: none;"></div>
                        <div id="qr-reader-placeholder"
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
                                <label for="qr_code">QR Code</label>
                                <input type="text" class="form-control" id="qr_code" name="qr_code"
                                    placeholder="Masukkan QR Code atau hasil scan" required>
                                <small class="form-text text-muted">
                                    Test QR Code: <button type="button" class="btn btn-sm btn-outline-primary"
                                        onclick="document.getElementById('qr_code').value='TEST123456'">TEST123456</button>
                                </small>
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
                    <form id="waste-deposit-form" method="POST" enctype="multipart/form-data">
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
                                    placeholder="0" min="1" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text">Poin</span>
                                </div>
                            </div>
                            <small class="form-text text-muted">Poin: 10 poin per kg (dihitung otomatis)</small>
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
                    <p class="text-muted" id="success-message">Poin telah ditambahkan ke akun user.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <!-- HTML5 QR Code Scanner -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let html5QrcodeScanner = null;

            // Form elements
            const weightInput = document.getElementById('weight_kg');
            const pointsInput = document.getElementById('points_earned');
            const submitBtn = document.getElementById('submit-btn');
            const qrCodeInput = document.getElementById('qr_code');
            const selectedUserIdInput = document.getElementById('selected_user_id');

            // Auto calculate points based on weight (10 points per kg)
            function calculatePoints() {
                const weight = parseFloat(weightInput.value) || 0;
                if (weight > 0) {
                    const points = Math.round(weight * 10);
                    pointsInput.value = points;
                } else {
                    pointsInput.value = '';
                }
                checkFormValidity();
            }

            function checkFormValidity() {
                const hasUser = selectedUserIdInput.value !== '';
                const hasWeight = weightInput.value !== '' && parseFloat(weightInput.value) > 0;
                const hasCategory = document.getElementById('category').value !== '';
                const hasLocation = document.getElementById('location_id').value !== '';
                const hasPoints = pointsInput.value !== '' && parseInt(pointsInput.value) > 0;

                submitBtn.disabled = !(hasUser && hasWeight && hasCategory && hasLocation && hasPoints);
            }

            // QR Scanner functions
            function onScanSuccess(decodedText, decodedResult) {
                console.log('QR Code berhasil discan:', decodedText);

                // Set QR code ke input
                qrCodeInput.value = decodedText;

                // Stop scanner setelah berhasil scan
                stopScanner();

                // Langsung proses scan user
                processQrScan(decodedText);
            }

            function onScanFailure(error) {
                // Handle scan failure silently - jangan console.log setiap error
                // karena akan spam console
            }

            function processQrScan(qrCode) {
                if (!qrCode.trim()) {
                    console.log('QR Code kosong');
                    alert('QR Code tidak boleh kosong');
                    return;
                }

                console.log('Memproses QR Code:', qrCode);

                // Show loading indicator
                document.getElementById('user-info').style.display = 'none';
                const loadingHtml = `
                    <div class="alert alert-info">
                        <i class="fas fa-spinner fa-spin"></i> Mencari user...
                    </div>
                `;
                document.getElementById('user-info').innerHTML = loadingHtml;
                document.getElementById('user-info').style.display = 'block';

                // Call API to scan QR
                fetch('/api/scan-qr', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            qr_code: qrCode.trim()
                        })
                    })
                    .then(async response => {
                        console.log('Response status:', response.status);
                        console.log('Response headers:', response.headers);

                        const text = await response.text();
                        console.log('Raw response:', text);

                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            console.error('JSON parse error:', e);
                            throw new Error('Invalid JSON response: ' + text);
                        }
                    })
                    .then(data => {
                        console.log('Response data:', data);

                        if (data.success) {
                            const user = data.data;
                            selectedUserIdInput.value = user.id;

                            document.getElementById('user-info').innerHTML = `
                                <div class="alert alert-success">
                                    <h6><i class="fas fa-user"></i> User Ditemukan:</h6>
                                    <div id="user-details">
                                        <strong>ID:</strong> ${user.id}<br>
                                        <strong>Nama:</strong> ${user.name}<br>
                                        <strong>Email:</strong> ${user.email}<br>
                                        <strong>Phone:</strong> ${user.phone || '-'}<br>
                                        <strong>Total Poin:</strong> ${user.points}<br>
                                        <strong>QR Code:</strong> ${user.qr_code}
                                    </div>
                                </div>
                            `;
                            document.getElementById('user-info').style.display = 'block';
                            checkFormValidity();
                        } else {
                            const errorMsg = data.message || 'User tidak ditemukan';
                            console.error('API Error:', errorMsg);

                            document.getElementById('user-info').innerHTML = `
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle"></i> ${errorMsg}
                                </div>
                            `;
                            document.getElementById('user-info').style.display = 'block';
                            selectedUserIdInput.value = '';
                            checkFormValidity();
                        }
                    })
                    .catch(error => {
                        console.error('Fetch Error:', error);

                        document.getElementById('user-info').innerHTML = `
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> Error: ${error.message}
                            </div>
                        `;
                        document.getElementById('user-info').style.display = 'block';
                        selectedUserIdInput.value = '';
                        checkFormValidity();
                    });
            }

            function startScanner() {
                document.getElementById('qr-reader-placeholder').style.display = 'none';
                document.getElementById('qr-reader').style.display = 'block';
                document.getElementById('start-scanner').style.display = 'none';
                document.getElementById('stop-scanner').style.display = 'inline-block';

                // Inisialisasi scanner
                html5QrcodeScanner = new Html5Qrcode("qr-reader");

                // Konfigurasi scanner dengan responsive sizing
                const qrReaderElement = document.getElementById('qr-reader');
                const parentWidth = qrReaderElement.parentElement.offsetWidth;
                const scannerWidth = Math.min(parentWidth - 40, 400); // Max 400px, min parent width - padding
                const scannerHeight = Math.min(scannerWidth * 0.75, 300); // Aspect ratio 4:3, max 300px

                const config = {
                    fps: 10,
                    qrbox: {
                        width: Math.min(scannerWidth * 0.7, 250),
                        height: Math.min(scannerHeight * 0.7, 250)
                    },
                    aspectRatio: 1.0,
                    supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
                };

                console.log('Starting scanner with config:', config);

                // Mulai scanner dengan prioritas kamera belakang
                Html5Qrcode.getCameras().then(devices => {
                    console.log('Available cameras:', devices);

                    if (devices && devices.length > 0) {
                        // Cari kamera belakang dulu
                        let selectedCamera = devices.find(device =>
                            device.label.toLowerCase().includes('back') ||
                            device.label.toLowerCase().includes('rear') ||
                            device.label.toLowerCase().includes('environment')
                        );

                        // Jika tidak ada kamera belakang, gunakan kamera pertama
                        if (!selectedCamera) {
                            selectedCamera = devices[0];
                        }

                        console.log('Selected camera:', selectedCamera);

                        // Start scanner dengan kamera yang dipilih
                        html5QrcodeScanner.start(
                            selectedCamera.id,
                            config,
                            onScanSuccess,
                            onScanFailure
                        ).catch(err => {
                            console.error("Error starting scanner with specific camera:", err);
                            // Fallback ke kamera default
                            startScannerFallback(config);
                        });
                    } else {
                        // Tidak ada kamera yang terdeteksi
                        alert("Tidak ada kamera yang terdeteksi pada perangkat ini.");
                        stopScanner();
                    }
                }).catch(err => {
                    console.error("Error getting cameras:", err);
                    // Fallback ke kamera default
                    startScannerFallback(config);
                });
            }

            function startScannerFallback(config) {
                // Fallback menggunakan facingMode
                html5QrcodeScanner.start({
                        facingMode: "environment"
                    }, // Kamera belakang
                    config,
                    onScanSuccess,
                    onScanFailure
                ).catch(err => {
                    console.error("Error starting scanner with environment camera:", err);

                    // Last fallback - kamera depan
                    html5QrcodeScanner.start({
                            facingMode: "user"
                        },
                        config,
                        onScanSuccess,
                        onScanFailure
                    ).catch(err => {
                        console.error("Error starting scanner with user camera:", err);
                        alert(
                            "Error starting camera. Please check camera permissions and try again."
                        );
                        stopScanner();
                    });
                });
            }

            function stopScanner() {
                if (html5QrcodeScanner) {
                    html5QrcodeScanner.stop().then(() => {
                        console.log('Scanner stopped successfully');
                        html5QrcodeScanner.clear();
                        html5QrcodeScanner = null;
                    }).catch(err => {
                        console.error("Error stopping scanner:", err);
                        html5QrcodeScanner = null;
                    });
                }

                document.getElementById('qr-reader').style.display = 'none';
                document.getElementById('qr-reader-placeholder').style.display = 'flex';
                document.getElementById('start-scanner').style.display = 'inline-block';
                document.getElementById('stop-scanner').style.display = 'none';
            }

            // Event listeners
            weightInput.addEventListener('input', calculatePoints);
            document.getElementById('category').addEventListener('change', checkFormValidity);
            document.getElementById('location_id').addEventListener('change', checkFormValidity);

            // Scanner button events
            document.getElementById('start-scanner').addEventListener('click', startScanner);
            document.getElementById('stop-scanner').addEventListener('click', stopScanner);

            // Handle manual scan user form
            document.getElementById('scan-user-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const qrCode = qrCodeInput.value.trim();

                if (qrCode) {
                    processQrScan(qrCode);
                } else {
                    alert('Masukkan QR Code terlebih dahulu');
                }
            });

            // Handle file input label update
            const photoInput = document.getElementById('photo_url');
            if (photoInput) {
                photoInput.addEventListener('change', function(e) {
                    const fileName = e.target.files[0]?.name || 'Pilih foto sampah...';
                    const label = document.querySelector('.custom-file-label');
                    if (label) {
                        label.textContent = fileName;
                    }
                });
            }

            // Handle form reset
            const resetBtn = document.querySelector('button[type="reset"]');
            if (resetBtn) {
                resetBtn.addEventListener('click', function() {
                    selectedUserIdInput.value = '';
                    document.getElementById('user-info').style.display = 'none';
                    const label = document.querySelector('.custom-file-label');
                    if (label) {
                        label.textContent = 'Pilih foto sampah...';
                    }
                    pointsInput.value = '';
                    qrCodeInput.value = '';
                    checkFormValidity();
                });
            }

            // Handle waste deposit form submission
            document.getElementById('waste-deposit-form').addEventListener('submit', function(e) {
                e.preventDefault();

                // Show loading state
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
                submitBtn.disabled = true;

                // Create FormData for file upload
                const formData = new FormData(this);

                fetch('/api/submit-waste', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('success-message').innerHTML =
                                `Poin ${data.data.points_earned} telah ditambahkan.<br>Total poin user: ${data.data.user_total_points}`;
                            $('#successModal').modal('show');

                            // Reset form after success
                            this.reset();
                            selectedUserIdInput.value = '';
                            document.getElementById('user-info').style.display = 'none';
                            const label = document.querySelector('.custom-file-label');
                            if (label) {
                                label.textContent = 'Pilih foto sampah...';
                            }
                            pointsInput.value = '';
                            qrCodeInput.value = '';
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error submitting data');
                    })
                    .finally(() => {
                        submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Data Sampah';
                        checkFormValidity();
                    });
            });

            // Handle window resize untuk responsive scanner
            window.addEventListener('resize', function() {
                if (html5QrcodeScanner && document.getElementById('qr-reader').style.display !== 'none') {
                    // Restart scanner dengan ukuran baru jika sedang aktif
                    stopScanner();
                    setTimeout(startScanner, 500);
                }
            });

            // Initial check
            checkFormValidity();
        });
    </script>
@endpush

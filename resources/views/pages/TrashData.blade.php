@extends('layout')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Sampah</h1>
        <div>
            <a href="{{ route('input') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> Input Sampah Baru
            </a>
            <button class="btn btn-primary btn-sm" onclick="refreshData()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Sampah (Hari Ini)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">23.5 kg</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-weight-hanging fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Poin Dihasilkan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">2,340</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-coins fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Transaksi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">156</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Kategori Terpopuler</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Plastik</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-recycle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-12">
            <!-- DataTables Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Riwayat Deposit Sampah</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Actions:</div>
                            <a class="dropdown-item" href="#" onclick="exportData()">
                                <i class="fas fa-download fa-sm fa-fw mr-2 text-gray-400"></i>
                                Export Excel
                            </a>
                            <a class="dropdown-item" href="#" onclick="printTable()">
                                <i class="fas fa-print fa-sm fa-fw mr-2 text-gray-400"></i>
                                Print
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Filter -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" class="form-control" id="searchInput"
                                    placeholder="Cari user atau kategori...">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" onclick="searchData()">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" id="categoryFilter" onchange="filterByCategory()">
                                <option value="">Semua Kategori</option>
                                <option value="plastik">Plastik</option>
                                <option value="kertas">Kertas</option>
                                <option value="logam">Logam</option>
                                <option value="kaca">Kaca</option>
                                <option value="organik">Organik</option>
                                <option value="elektronik">Elektronik</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" id="locationFilter" onchange="filterByLocation()">
                                <option value="">Semua Lokasi</option>
                                <option value="1">Drop Point A</option>
                                <option value="2">Drop Point B</option>
                                <option value="3">Drop Point C</option>
                                <option value="4">Drop Point D</option>
                                <option value="5">Drop Point E</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control" id="dateFrom" onchange="filterByDate()">
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control" id="dateTo" onchange="filterByDate()">
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="trashTable" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="15%">User</th>
                                    <th width="12%">Kategori</th>
                                    <th width="10%">Berat (kg)</th>
                                    <th width="10%">Foto</th>
                                    <th width="15%">Drop Point</th>
                                    <th width="8%">Poin</th>
                                    <th width="12%">Tanggal</th>
                                    <th width="13%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Sample Data -->
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img class="img-profile rounded-circle mr-2"
                                                src="https://via.placeholder.com/30x30"
                                                style="width: 30px; height: 30px;">
                                            <div>
                                                <strong>John Doe</strong><br>
                                                <small class="text-muted">USR001</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">Plastik</span>
                                    </td>
                                    <td class="font-weight-bold">2.5</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-info"
                                            onclick="viewPhoto('https://via.placeholder.com/400x300')">
                                            <i class="fas fa-image"></i> Lihat
                                        </button>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>Drop Point A</strong><br>
                                            <small class="text-muted">Jl. Sudirman No. 1</small>
                                        </div>
                                    </td>
                                    <td><span class="text-success font-weight-bold">25</span></td>
                                    <td>
                                        <div>18 Jul 2025</div>
                                        <small class="text-muted">10:30 AM</small>
                                    </td>
                                    <td>
                                        <button class="btn btn-info btn-sm" onclick="viewDeposit(1)" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-warning btn-sm" onclick="editDeposit(1)" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteDeposit(1)" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img class="img-profile rounded-circle mr-2"
                                                src="https://via.placeholder.com/30x30"
                                                style="width: 30px; height: 30px;">
                                            <div>
                                                <strong>Jane Smith</strong><br>
                                                <small class="text-muted">USR002</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">Kertas</span>
                                    </td>
                                    <td class="font-weight-bold">1.8</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-info"
                                            onclick="viewPhoto('https://via.placeholder.com/400x300')">
                                            <i class="fas fa-image"></i> Lihat
                                        </button>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>Drop Point B</strong><br>
                                            <small class="text-muted">Jl. Thamrin No. 5</small>
                                        </div>
                                    </td>
                                    <td><span class="text-success font-weight-bold">14</span></td>
                                    <td>
                                        <div>17 Jul 2025</div>
                                        <small class="text-muted">02:15 PM</small>
                                    </td>
                                    <td>
                                        <button class="btn btn-info btn-sm" onclick="viewDeposit(2)" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-warning btn-sm" onclick="editDeposit(2)" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteDeposit(2)" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img class="img-profile rounded-circle mr-2"
                                                src="https://via.placeholder.com/30x30"
                                                style="width: 30px; height: 30px;">
                                            <div>
                                                <strong>Bob Wilson</strong><br>
                                                <small class="text-muted">USR003</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-warning">Logam</span>
                                    </td>
                                    <td class="font-weight-bold">3.2</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-info"
                                            onclick="viewPhoto('https://via.placeholder.com/400x300')">
                                            <i class="fas fa-image"></i> Lihat
                                        </button>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>Drop Point C</strong><br>
                                            <small class="text-muted">Jl. Gatot Subroto No. 10</small>
                                        </div>
                                    </td>
                                    <td><span class="text-success font-weight-bold">48</span></td>
                                    <td>
                                        <div>16 Jul 2025</div>
                                        <small class="text-muted">09:45 AM</small>
                                    </td>
                                    <td>
                                        <button class="btn btn-info btn-sm" onclick="viewDeposit(3)" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-warning btn-sm" onclick="editDeposit(3)" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteDeposit(3)" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img class="img-profile rounded-circle mr-2"
                                                src="https://via.placeholder.com/30x30"
                                                style="width: 30px; height: 30px;">
                                            <div>
                                                <strong>Alice Johnson</strong><br>
                                                <small class="text-muted">USR004</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">Kaca</span>
                                    </td>
                                    <td class="font-weight-bold">1.1</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-info"
                                            onclick="viewPhoto('https://via.placeholder.com/400x300')">
                                            <i class="fas fa-image"></i> Lihat
                                        </button>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>Drop Point D</strong><br>
                                            <small class="text-muted">Jl. Kuningan No. 15</small>
                                        </div>
                                    </td>
                                    <td><span class="text-success font-weight-bold">13</span></td>
                                    <td>
                                        <div>15 Jul 2025</div>
                                        <small class="text-muted">04:20 PM</small>
                                    </td>
                                    <td>
                                        <button class="btn btn-info btn-sm" onclick="viewDeposit(4)" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-warning btn-sm" onclick="editDeposit(4)" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteDeposit(4)" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img class="img-profile rounded-circle mr-2"
                                                src="https://via.placeholder.com/30x30"
                                                style="width: 30px; height: 30px;">
                                            <div>
                                                <strong>Mike Davis</strong><br>
                                                <small class="text-muted">USR005</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-danger">Elektronik</span>
                                    </td>
                                    <td class="font-weight-bold">0.5</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-info"
                                            onclick="viewPhoto('https://via.placeholder.com/400x300')">
                                            <i class="fas fa-image"></i> Lihat
                                        </button>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>Drop Point E</strong><br>
                                            <small class="text-muted">Jl. Kemang No. 20</small>
                                        </div>
                                    </td>
                                    <td><span class="text-success font-weight-bold">10</span></td>
                                    <td>
                                        <div>14 Jul 2025</div>
                                        <small class="text-muted">11:00 AM</small>
                                    </td>
                                    <td>
                                        <button class="btn btn-info btn-sm" onclick="viewDeposit(5)" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-warning btn-sm" onclick="editDeposit(5)" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteDeposit(5)" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="row mt-3">
                        <div class="col-sm-5">
                            <div class="dataTables_info">
                                Showing 1 to 5 of 87 entries
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-end">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Photo Modal -->
    <div class="modal fade" id="photoModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-image"></i> Foto Sampah
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img id="photoModalImage" src="" class="img-fluid" style="max-width: 100%; height: auto;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Deposit Detail Modal -->
    <div class="modal fade" id="viewDepositModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-recycle"></i> Detail Deposit Sampah
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="depositDetailContent">
                    <!-- Content will be loaded dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Search functionality
        function searchData() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const table = document.getElementById('trashTable');
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const text = row.textContent.toLowerCase();

                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }

        // Filter by category
        function filterByCategory() {
            const categoryFilter = document.getElementById('categoryFilter').value.toLowerCase();
            const table = document.getElementById('trashTable');
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const categoryCell = row.cells[2].textContent.toLowerCase();

                if (categoryFilter === '' || categoryCell.includes(categoryFilter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }

        // Filter by location
        function filterByLocation() {
            const locationFilter = document.getElementById('locationFilter').value;
            const table = document.getElementById('trashTable');
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const locationCell = row.cells[5].textContent;

                if (locationFilter === '' || locationCell.includes('Drop Point ' + String.fromCharCode(64 + parseInt(
                        locationFilter)))) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }

        // Filter by date
        function filterByDate() {
            // Implement date range filtering logic here
            alert('Date filtering functionality');
        }

        // View photo
        function viewPhoto(photoUrl) {
            document.getElementById('photoModalImage').src = photoUrl;
            $('#photoModal').modal('show');
        }

        // View deposit details
        function viewDeposit(id) {
            // Simulate deposit data fetch
            const depositData = {
                1: {
                    id: 'DEP001',
                    user: 'John Doe (USR001)',
                    category: 'Plastik',
                    weight: '2.5',
                    points: '25',
                    location: 'Drop Point A - Jl. Sudirman No. 1',
                    date: '18 Jul 2025 10:30 AM',
                    photo: 'https://via.placeholder.com/300x200'
                }
            };

            const deposit = depositData[id] || depositData[1];

            document.getElementById('depositDetailContent').innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%"><strong>ID Deposit:</strong></td>
                        <td>${deposit.id}</td>
                    </tr>
                    <tr>
                        <td><strong>User:</strong></td>
                        <td>${deposit.user}</td>
                    </tr>
                    <tr>
                        <td><strong>Kategori:</strong></td>
                        <td><span class="badge badge-primary">${deposit.category}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Berat:</strong></td>
                        <td><strong>${deposit.weight} kg</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Poin Didapat:</strong></td>
                        <td><span class="text-success font-weight-bold">${deposit.points}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Lokasi:</strong></td>
                        <td>${deposit.location}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal:</strong></td>
                        <td>${deposit.date}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6 text-center">
                <h6>Foto Sampah:</h6>
                <img src="${deposit.photo}" class="img-fluid rounded" style="max-width: 100%; height: auto;">
            </div>
        </div>
    `;

            $('#viewDepositModal').modal('show');
        }

        // Edit deposit
        function editDeposit(id) {
            alert('Edit deposit functionality - ID: ' + id);
        }

        // Delete deposit
        function deleteDeposit(id) {
            if (confirm('Apakah Anda yakin ingin menghapus data deposit ini?')) {
                alert('Delete deposit functionality - ID: ' + id);
            }
        }

        // Refresh data
        function refreshData() {
            alert('Data refreshed!');
        }

        // Export data
        function exportData() {
            alert('Export Excel functionality');
        }

        // Print table
        function printTable() {
            window.print();
        }

        // Enter key search
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchData();
            }
        });

        // Set default date values
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            const lastWeek = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);

            document.getElementById('dateFrom').value = lastWeek.toISOString().split('T')[0];
            document.getElementById('dateTo').value = today.toISOString().split('T')[0];
        });
    </script>
@endpush

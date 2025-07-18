@extends('layout')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data User</h1>
        <div>
            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#addUserModal">
                <i class="fas fa-plus"></i> Tambah User
            </button>
            <button class="btn btn-primary btn-sm" onclick="refreshData()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-12">
            <!-- DataTables Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar User Terdaftar</h6>
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
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" id="searchInput"
                                    placeholder="Cari nama, email, atau phone...">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" onclick="searchData()">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" id="roleFilter" onchange="filterByRole()">
                                <option value="">Semua Role</option>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                                <option value="superadmin">Super Admin</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" id="statusFilter" onchange="filterByStatus()">
                                <option value="">Semua Status</option>
                                <option value="active">Aktif</option>
                                <option value="inactive">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="userTable" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="15%">Nama</th>
                                    <th width="20%">Email</th>
                                    <th width="15%">Phone</th>
                                    <th width="10%">Role</th>
                                    <th width="10%">Total Poin</th>
                                    <th width="10%">Status</th>
                                    <th width="10%">Join Date</th>
                                    <th width="15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Sample Data -->
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img class="img-profile rounded-circle mr-2"
                                                src="https://via.placeholder.com/40x40" style="width: 40px; height: 40px;">
                                            <div>
                                                <strong>John Doe</strong><br>
                                                <small class="text-muted">ID: USR001</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>john.doe@email.com</td>
                                    <td>+62 812-3456-7890</td>
                                    <td><span class="badge badge-primary">User</span></td>
                                    <td><span class="text-success font-weight-bold">1,250</span></td>
                                    <td><span class="badge badge-success">Aktif</span></td>
                                    <td>15 Jan 2025</td>
                                    <td>
                                        <button class="btn btn-info btn-sm" onclick="viewUser(1)" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-warning btn-sm" onclick="editUser(1)" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteUser(1)" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img class="img-profile rounded-circle mr-2"
                                                src="https://via.placeholder.com/40x40" style="width: 40px; height: 40px;">
                                            <div>
                                                <strong>Jane Smith</strong><br>
                                                <small class="text-muted">ID: USR002</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>jane.smith@email.com</td>
                                    <td>+62 813-4567-8901</td>
                                    <td><span class="badge badge-success">Admin</span></td>
                                    <td><span class="text-success font-weight-bold">890</span></td>
                                    <td><span class="badge badge-success">Aktif</span></td>
                                    <td>10 Jan 2025</td>
                                    <td>
                                        <button class="btn btn-info btn-sm" onclick="viewUser(2)" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-warning btn-sm" onclick="editUser(2)" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteUser(2)" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img class="img-profile rounded-circle mr-2"
                                                src="https://via.placeholder.com/40x40"
                                                style="width: 40px; height: 40px;">
                                            <div>
                                                <strong>Bob Wilson</strong><br>
                                                <small class="text-muted">ID: USR003</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>bob.wilson@email.com</td>
                                    <td>+62 814-5678-9012</td>
                                    <td><span class="badge badge-primary">User</span></td>
                                    <td><span class="text-success font-weight-bold">2,150</span></td>
                                    <td><span class="badge badge-warning">Tidak Aktif</span></td>
                                    <td>08 Jan 2025</td>
                                    <td>
                                        <button class="btn btn-info btn-sm" onclick="viewUser(3)" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-warning btn-sm" onclick="editUser(3)" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteUser(3)" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img class="img-profile rounded-circle mr-2"
                                                src="https://via.placeholder.com/40x40"
                                                style="width: 40px; height: 40px;">
                                            <div>
                                                <strong>Alice Johnson</strong><br>
                                                <small class="text-muted">ID: USR004</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>alice.johnson@email.com</td>
                                    <td>+62 815-6789-0123</td>
                                    <td><span class="badge badge-primary">User</span></td>
                                    <td><span class="text-success font-weight-bold">745</span></td>
                                    <td><span class="badge badge-success">Aktif</span></td>
                                    <td>05 Jan 2025</td>
                                    <td>
                                        <button class="btn btn-info btn-sm" onclick="viewUser(4)" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-warning btn-sm" onclick="editUser(4)" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteUser(4)" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img class="img-profile rounded-circle mr-2"
                                                src="https://via.placeholder.com/40x40"
                                                style="width: 40px; height: 40px;">
                                            <div>
                                                <strong>Mike Davis</strong><br>
                                                <small class="text-muted">ID: USR005</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>mike.davis@email.com</td>
                                    <td>+62 816-7890-1234</td>
                                    <td><span class="badge badge-danger">Super Admin</span></td>
                                    <td><span class="text-success font-weight-bold">3,520</span></td>
                                    <td><span class="badge badge-success">Aktif</span></td>
                                    <td>01 Jan 2025</td>
                                    <td>
                                        <button class="btn btn-info btn-sm" onclick="viewUser(5)" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-warning btn-sm" onclick="editUser(5)" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteUser(5)" title="Delete">
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
                                Showing 1 to 5 of 57 entries
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

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-user-plus"></i> Tambah User Baru
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="text" class="form-control" id="phone" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <select class="form-control" id="role" required>
                                        <option value="">Pilih Role</option>
                                        <option value="user">User</option>
                                        <option value="admin">Admin</option>
                                        <option value="superadmin">Super Admin</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" onclick="saveUser()">Simpan User</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View User Modal -->
    <div class="modal fade" id="viewUserModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-user"></i> Detail User
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="userDetailContent">
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
            const table = document.getElementById('userTable');
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

        // Filter by role
        function filterByRole() {
            const roleFilter = document.getElementById('roleFilter').value.toLowerCase();
            const table = document.getElementById('userTable');
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const roleCell = row.cells[4].textContent.toLowerCase();

                if (roleFilter === '' || roleCell.includes(roleFilter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }

        // Filter by status
        function filterByStatus() {
            const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
            const table = document.getElementById('userTable');
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const statusCell = row.cells[6].textContent.toLowerCase();

                if (statusFilter === '' || statusCell.includes(statusFilter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }

        // View user details
        function viewUser(id) {
            // Simulate user data fetch
            const userData = {
                1: {
                    name: 'John Doe',
                    email: 'john.doe@email.com',
                    phone: '+62 812-3456-7890',
                    role: 'User',
                    points: '1,250',
                    status: 'Aktif',
                    joinDate: '15 Jan 2025',
                    address: 'Jl. Sudirman No. 123, Jakarta',
                    lastLogin: '18 Jul 2025 10:30'
                }
            };

            const user = userData[id] || userData[1];

            document.getElementById('userDetailContent').innerHTML = `
        <div class="row">
            <div class="col-md-4 text-center">
                <img src="https://via.placeholder.com/150x150" class="img-fluid rounded-circle mb-3">
                <h5>${user.name}</h5>
                <span class="badge badge-primary">${user.role}</span>
            </div>
            <div class="col-md-8">
                <table class="table table-borderless">
                    <tr>
                        <td width="30%"><strong>Email:</strong></td>
                        <td>${user.email}</td>
                    </tr>
                    <tr>
                        <td><strong>Phone:</strong></td>
                        <td>${user.phone}</td>
                    </tr>
                    <tr>
                        <td><strong>Total Poin:</strong></td>
                        <td><span class="text-success font-weight-bold">${user.points}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td><span class="badge badge-success">${user.status}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Join Date:</strong></td>
                        <td>${user.joinDate}</td>
                    </tr>
                    <tr>
                        <td><strong>Last Login:</strong></td>
                        <td>${user.lastLogin}</td>
                    </tr>
                    <tr>
                        <td><strong>Address:</strong></td>
                        <td>${user.address}</td>
                    </tr>
                </table>
            </div>
        </div>
    `;

            $('#viewUserModal').modal('show');
        }

        // Edit user
        function editUser(id) {
            alert('Edit user functionality - ID: ' + id);
        }

        // Delete user
        function deleteUser(id) {
            if (confirm('Apakah Anda yakin ingin menghapus user ini?')) {
                alert('Delete user functionality - ID: ' + id);
            }
        }

        // Save new user
        function saveUser() {
            const form = document.getElementById('addUserForm');
            if (form.checkValidity()) {
                alert('User berhasil ditambahkan!');
                $('#addUserModal').modal('hide');
                form.reset();
            } else {
                form.reportValidity();
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
    </script>
@endpush

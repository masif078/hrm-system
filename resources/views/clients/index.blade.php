@extends('layouts.app')

@section('title', 'Clients')

@section('content')

<div class="container-fluid px-0">

    {{-- Breadcrumbs --}}
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => '/dashboard'],
        ['label' => 'Clients']
    ]" />

    <div id="alertContainer">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4 shadow-sm">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    {{-- Header Banner Card --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
        <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h3 class="fw-bold text-dark mb-1">Clients</h3>
                <p class="text-secondary small mb-0">Manage company clients, primary contact representatives, and business profiles.</p>
            </div>
            <button type="button" class="btn btn-primary fw-bold px-4 py-2 rounded-3 text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#addClientModal">
                <i class="bi bi-plus-lg me-1"></i> Add Client
            </button>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0" id="clientsTable">
                <thead class="table-dark" style="background-color: #0F172A;">
                    <tr>
                        <th class="ps-4 py-3">ID</th>
                        <th class="py-3">Company Name</th>
                        <th class="py-3">Contact Person</th>
                        <th class="py-3">Email</th>
                        <th class="py-3">Phone</th>
                        <th class="pe-4 text-end py-3" width="140">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                        <tr class="hover-row" id="clientRow_{{ $client->id }}">
                            <td class="ps-4 fw-bold text-secondary">#{{ $client->id }}</td>
                            <td class="fw-bold text-dark">{{ $client->company_name }}</td>
                            <td class="text-dark fw-medium">{{ $client->contact_person }}</td>
                            <td class="text-secondary small">{{ $client->email }}</td>
                            <td class="text-secondary small">{{ $client->phone }}</td>
                            <td class="pe-4 text-end">
                                <div class="d-inline-flex gap-1.5">
                                    <a href="{{ route('clients.edit', $client) }}" class="btn btn-action-edit" title="Edit Client" aria-label="Edit Client">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('clients.destroy', $client) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-action-delete" title="Delete Client" aria-label="Delete Client" onclick="return confirm('Delete Client?')">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="noClientsRow">
                            <td colspan="6" class="p-0">
                                <x-empty-state title="No Clients Found" icon="bi-briefcase" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($clients->hasPages())
            <div class="card-footer bg-white border-top border-light-subtle p-3">
                {{ $clients->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>

{{-- Add Client Modal Popup Overlay --}}
<div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true" style="backdrop-filter: blur(4px); background: rgba(15, 23, 42, 0.4);">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            
            {{-- Modal Header --}}
            <div class="modal-header border-bottom border-light-subtle px-4 py-3 bg-white">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="addClientModalLabel">
                    <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-briefcase-fill fs-6"></i>
                    </div>
                    Add New Client
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Form --}}
            <form id="addClientForm" action="{{ route('clients.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div id="modalClientErrors" class="alert alert-danger d-none rounded-3 mb-3"></div>

                    <div class="row g-3">
                        {{-- Company Name --}}
                        <div class="col-md-6">
                            <label for="company_name" class="form-label fw-bold text-dark small">Company Name <span class="text-danger">*</span></label>
                            <input type="text" name="company_name" id="company_name" class="form-control rounded-3 border-light-subtle" placeholder="e.g. Acme Corporation" required>
                        </div>

                        {{-- Client Name / Contact Person --}}
                        <div class="col-md-6">
                            <label for="contact_person" class="form-label fw-bold text-dark small">Client Name / Contact Person <span class="text-danger">*</span></label>
                            <input type="text" name="contact_person" id="contact_person" class="form-control rounded-3 border-light-subtle" placeholder="e.g. John Doe" required>
                        </div>

                        {{-- Email Address --}}
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-bold text-dark small">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control rounded-3 border-light-subtle" placeholder="john@acme.com" required>
                        </div>

                        {{-- Phone Number --}}
                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-bold text-dark small">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" name="phone" id="phone" class="form-control rounded-3 border-light-subtle" placeholder="+1 (555) 000-0000" required>
                        </div>

                        {{-- Address --}}
                        <div class="col-12">
                            <label for="address" class="form-label fw-bold text-dark small">Business Address</label>
                            <textarea name="address" id="address" rows="2" class="form-control rounded-3 border-light-subtle" placeholder="123 Business Way, Suite 100, New York, NY"></textarea>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="modal-footer border-top border-light-subtle px-4 py-3 bg-light d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="saveClientBtn" class="btn btn-primary rounded-3 px-4 py-2 fw-bold text-white shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> Save Client
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const addClientForm = document.getElementById('addClientForm');
    const saveClientBtn = document.getElementById('saveClientBtn');
    const modalClientErrors = document.getElementById('modalClientErrors');
    const clientsTableBody = document.querySelector('#clientsTable tbody');
    const alertContainer = document.getElementById('alertContainer');

    if (addClientForm) {
        addClientForm.addEventListener('submit', function (e) {
            e.preventDefault();
            
            saveClientBtn.disabled = true;
            saveClientBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';
            modalClientErrors.classList.add('d-none');
            modalClientErrors.innerHTML = '';

            const formData = new FormData(addClientForm);

            fetch("{{ route('clients.store') }}", {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                saveClientBtn.disabled = false;
                saveClientBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Save Client';

                if (data.success) {
                    // Close Modal
                    const modalEl = document.getElementById('addClientModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) {
                        modalInstance.hide();
                    }

                    // Reset form
                    addClientForm.reset();

                    // Remove empty state row if present
                    const noClientsRow = document.getElementById('noClientsRow');
                    if (noClientsRow) {
                        noClientsRow.remove();
                    }

                    // Prepend new client row
                    const client = data.client;
                    const editUrl = "{{ url('clients') }}/" + client.id + "/edit";
                    const destroyUrl = "{{ url('clients') }}/" + client.id;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    const newRow = document.createElement('tr');
                    newRow.className = 'hover-row';
                    newRow.id = 'clientRow_' + client.id;
                    newRow.innerHTML = `
                        <td class="ps-4 fw-bold text-secondary">#${client.id}</td>
                        <td class="fw-bold text-dark">${client.company_name}</td>
                        <td class="text-dark fw-medium">${client.contact_person}</td>
                        <td class="text-secondary small">${client.email}</td>
                        <td class="text-secondary small">${client.phone}</td>
                        <td class="pe-4 text-end">
                            <div class="d-inline-flex gap-1.5">
                                <a href="${editUrl}" class="btn btn-action-edit" title="Edit Client" aria-label="Edit Client">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="${destroyUrl}" method="POST" class="d-inline">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-action-delete" title="Delete Client" aria-label="Delete Client" onclick="return confirm('Delete Client?')">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    `;
                    clientsTableBody.prepend(newRow);

                    // Show success alert
                    alertContainer.innerHTML = `
                        <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4 shadow-sm">
                            <i class="bi bi-check-circle-fill me-2"></i> ${data.message}
                            <button class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                } else {
                    let errHtml = '<ul class="mb-0 ps-3">';
                    if (data.errors) {
                        Object.values(data.errors).forEach(errArray => {
                            errArray.forEach(err => {
                                errHtml += `<li>${err}</li>`;
                            });
                        });
                    } else if (data.message) {
                        errHtml += `<li>${data.message}</li>`;
                    }
                    errHtml += '</ul>';
                    modalClientErrors.innerHTML = errHtml;
                    modalClientErrors.classList.remove('d-none');
                }
            })
            .catch(error => {
                saveClientBtn.disabled = false;
                saveClientBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Save Client';
                modalClientErrors.innerHTML = 'An unexpected error occurred. Please try again.';
                modalClientErrors.classList.remove('d-none');
            });
        });
    }
});
</script>

@endsection

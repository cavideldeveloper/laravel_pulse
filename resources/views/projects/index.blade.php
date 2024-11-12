@extends('layouts.app')

@section('content')

<!-- Modal for Adding and Editing Projects -->
<div class="modal fade" id="projectModal" tabindex="-1" aria-labelledby="projectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="projectForm" action="{{ route('projects.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" value="POST"> <!-- Hidden method for PUT when editing -->
                <div class="modal-header">
                    <h5 class="modal-title" id="projectModalLabel">Add Pulse Link</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="projectName" class="form-label">Project Name</label>
                        <input type="text" name="name" class="form-control" id="projectName" required>
                    </div>
                   <div class="mb-3">
                        <label for="projectLink" class="form-label">Pulse Link</label>
                        <input type="url" name="link" class="form-control" id="projectLink" required>
                        @error('link')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning" id="formButton">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Display Projects in Cards -->
<div class="row">
    @forelse($projects as $project)
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $project->name }}</h5>
                    <p class="card-text"><a href="{{ $project->link }}" target="_blank">{{ $project->link }}</a></p>
                    <!-- Edit button -->
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#projectModal" onclick="editProject({{ $project->id }}, '{{ $project->name }}', '{{ $project->link }}')">Edit</button>

                    <!-- Delete button -->
                    <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete(event)">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <p class="text-center text">No project is currently available. Click the Add Pulse Link to create add project link.</p>
    @endforelse
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function resetForm() {
        const form = document.getElementById('projectForm');
        form.reset(); // Reset form fields
        form.action = "{{ route('projects.store') }}"; // Set the action back to "Add"
        form._method.value = 'POST'; // Set method to POST for adding new projects
        document.getElementById('projectModalLabel').textContent = 'Add Pulse Link'; // Change title back to "Add Project"
        document.getElementById('formButton').textContent = 'Save'; // Reset the button text to "Save"
    }

    function editProject(id, name, link) {
        const form = document.getElementById('projectForm');
        form.action = `/projects/${id}`; // Update the form action to the update route
        form._method.value = 'PUT'; // Change method to PUT for editing
        document.getElementById('projectName').value = name; // Pre-fill the project name
        document.getElementById('projectLink').value = link; // Pre-fill the project link
        document.getElementById('projectModalLabel').textContent = 'Edit Pulse Link'; // Change modal title to "Edit Project"
        document.getElementById('formButton').textContent = 'Update'; // Change the button text to "Update"
    }

    // Reset the form when the modal is closed (this ensures "Add Project" is shown again)
    const projectModal = document.getElementById('projectModal');
    projectModal.addEventListener('hidden.bs.modal', resetForm);

    // Swal for Delete Confirmation
    function confirmDelete(event) {
        event.preventDefault(); // Prevent the default form submission
        Swal.fire({
            title: 'Are you sure?',
            text: 'You will not be able to recover this project!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            customClass: {
                confirmButton: 'btn btn-danger', // Use Bootstrap's btn-danger class
                cancelButton: 'btn btn-warning'  // Use Bootstrap's btn-warning class
            }
        }).then((result) => {
            if (result.isConfirmed) {
                event.target.submit(); // Submit the form if confirmed
            }
        });
    }

    // Show success alerts on add/update/delete
    @if(session('status'))
        Swal.fire({
            title: 'Success!',
            text: '{{ session('status') }}',
            icon: 'success',
            confirmButtonText: 'OK',
            customClass: {
                confirmButton: 'btn btn-success' 
            }
        });
    @endif
</script>

@endsection

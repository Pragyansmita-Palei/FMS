let branchCounter = 1;

function addNewBranch(containerId = 'branchesContainer') {

    const container = document.getElementById(containerId);

    if (!container) return;

    const branchCounter = container.querySelectorAll('.branch-item').length + 1;

    const newBranchHTML = `
    <div class="branch-item border rounded-3 p-3 mb-3">

        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-semibold mb-0">Branch #${branchCounter}</h6>

            <button type="button"
                class="btn btn-sm btn-outline-danger"
                onclick="removeBranch(this)">
                Remove
            </button>
        </div>

        <div class="row">

            <div class="col-md-6 mb-3">
                <label class="form-label">Branch Name <span class="text-danger">*</span></label>
                <input type="text" name="branch_name[]" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Branch Code</label>
                <input type="text" name="branch_code[]" class="form-control">
            </div>

        </div>

        <h6 class="fw-semibold mt-2">Branch Contact Person</h6>

        <div class="row">

            <div class="col-md-4 mb-3">
                <label class="form-label">Contact Name <span class="text-danger">*</span></label>
                <input type="text" name="branch_contact_name[]" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Phone <span class="text-danger">*</span></label>
                <input type="text" name="branch_contact_phone[]" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Contact Email</label>
                <input type="email" name="branch_contact_email[]" class="form-control">
            </div>

        </div>

    </div>
    `;

    container.insertAdjacentHTML('beforeend', newBranchHTML);
}


function removeBranch(button) {

    const container = button.closest('[id^="branchesContainer"]');
    const branchItems = container.querySelectorAll('.branch-item');

    if (branchItems.length <= 1) {
        alert('At least one branch is required.');
        return;
    }

    button.closest('.branch-item').remove();
}

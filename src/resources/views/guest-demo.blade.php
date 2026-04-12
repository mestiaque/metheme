@extends('me::guestMaster')

@section('content')
<div class="card">

    <h2 class="card-title">Glassmorphism Full Demo</h2>

    <!-- ALERT -->
    <div class="alert">This is a glass alert!</div>

    <!-- BUTTON -->
    <button class="btn btn-sm" id="openModalBtn">Open Modal</button>

    <!-- ROW & COL (Bootstrap) -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card p-3">Column 1</div>
        </div>
        <div class="col-md-6">
            <div class="card p-3">Column 2</div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="table-responsive mt-4">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>John</td>
                    <td>john@mail.com</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Jane</td>
                    <td>jane@mail.com</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- FORM INPUTS -->
    <form class="form mt-4">

        <!-- TEXT -->
        <input type="text" class="input form-control mb-2" placeholder="Your Name" />

        <!-- EMAIL -->
        <input type="email" class="input form-control mb-2" placeholder="Your Email" />

        <!-- RADIO -->
        <div class="form-check">
            <input class="form-check-input" type="radio" name="gender" id="male">
            <label class="form-check-label" for="male">Male</label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="radio" name="gender" id="female">
            <label class="form-check-label" for="female">Female</label>
        </div>

        <!-- CHECKBOX -->
        <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" id="terms">
            <label class="form-check-label" for="terms">Accept Terms</label>
        </div>

        <!-- SWITCH (Bootstrap) -->
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" id="switch">
            <label class="form-check-label" for="switch">Toggle Switch</label>
        </div>

        <!-- SELECT -->
        <select class="input form-select mt-2">
            <option>Select Option</option>
            <option>Option 1</option>
            <option>Option 2</option>
        </select>

        <!-- FILE -->
        <input type="file" class="form-control mt-2" />

        <!-- BUTTON -->
        <button type="submit" class="btn mt-3">Submit</button>
    </form>
</div>

<!-- MODAL -->
<div class="modal" id="demoModal">
    <div class="modal-content">
        <span class="modal-close" id="closeModalBtn">&times;</span>
        <h3>Glassmorphism Modal</h3>
        <p>This is a modal with glass effect.</p>
    </div>
</div>

@endsection
    
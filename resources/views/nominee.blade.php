<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<div class="container mt-4">
    <h2>Nominee forms</h2>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    {{-- @dd(request()) --}}
    <form method="POST" action="/nominee/{{ $beneficiary_id }}">
        @csrf

        <div class="mb-3">
            <label>Nominee name</label>
            <input type="text" class="form-control" name='full_name'  value="{{old('full_name')}}">


            <span class="text-danger">
                @error('full_name')
                    {{ $message }}
                @enderror
            </span>
        </div>


        <div>
            <label for="has_account">Does the nominee have an account with our bank?</label>
            <div>
                <input type="radio" name="has_account" id="has_account_yes" value="0">
                <label for="has_account_yes">Yes</label>
            </div>
            <div>
                <input type="radio" name="has_account" id="has_account_no" value="1">
                <label for="has_account_no">No</label>
            </div>
            @error('has_account')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div id="bank_name_container" style="display: none;">
            <label for="bank_name">Bank Name:</label>
            <input type="text" name="bank_name" id="bank_name">
            @error('bank_name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="account_number">Nominee Account Number:</label>
            <input type="text" name="account_number" class="form-control"
            id="account_number" value="{{old('account_number')}}" >
            <span class="text-danger">
                @error('account_number')
                    {{ $message }}
                @enderror
            </span>
        </div>

        <div>
            <label for="account_number_confirmation">Confirm Nominee Account Number:</label>
            <input type="text" name="account_number_confirmation" class="form-control"
                id="account_number_confirmation" value="{{old('account_number_confirmation')}}">
            @error('account_number_confirmation')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <button type="submit">Add Nominee</button>
        </div>
    </form>

</div>

<script>
    // Toggle the bank name input based on the selected radio button
    const hasAccountYes = document.getElementById('has_account_yes');
    const hasAccountNo = document.getElementById('has_account_no');
    const bankNameContainer = document.getElementById('bank_name_container');

    hasAccountYes.addEventListener('change', function() {
        if (hasAccountYes.checked) {
            bankNameContainer.style.display = 'none';
        }
    });

    hasAccountNo.addEventListener('change', function() {
        if (hasAccountNo.checked) {
            bankNameContainer.style.display = 'block';
        }
    });
</script>

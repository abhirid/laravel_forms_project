<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/css/bootstrap.css" rel="stylesheet">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<div class="container mt-4">
<h1>User Login</h1>


<form action="/beneficiary" method="POST" enctype="multipart/form-data">
    @csrf
    <div>
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" class="form-control" name="first_name" value="{{ old('first_name') }}"><br>
        @error('first_name')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div>
        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" class="form-control" name="last_name" value="{{ old('last_name') }}"><br>

        @error('last_name')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div>
        <label for="image">Image:</label>
        <input type="file" id="image" class="form-control"  name="image"><br>

        @error('image')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div>
        <label for="has_aadhaar">Has Aadhaar number?</label>
        <input type="radio" name="has_aadhaar" value="Yes">
        <label>Yes</label>
        <input type="radio" name="has_aadhaar" value="No" onclick="toggleAadhaarInput()">
        <label>No</label>
        <br>
        @error('has_aadhaar')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div id="aadhaar_number_field" style="display: {{ old('has_aadhaar') === 'No' ? 'none' : 'block' }};">
        <label for="aadhaar_number">Aadhaar Number:</label>
        <input type="text" id="aadhaar_number" class="form-control"  name="aadhaar_number" value="{{ old('aadhaar_number') }}"><br>

        @error('aadhaar_number')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <button type="submit">Submit</button>



</form>
</div>

<script>

     @if(Session::has('success'))
        toastr.options = {
            "closeButton" : true,
            "progressBar" : true
        }
        toastr.success("{{ session('success') }}");
    @endif

      const hasAadhaarYes = document.querySelector('input[name="has_aadhaar"][value="Yes"]');
    const hasAadhaarNo = document.querySelector('input[name="has_aadhaar"][value="No"]');
    const aadhaarField = document.getElementById('aadhaar_number_field');

    hasAadhaarYes.addEventListener('change', function() {
        if (hasAadhaarYes.checked) {
            aadhaarField.style.display = 'block';
        }
    });

    hasAadhaarNo.addEventListener('change', function() {
        if (hasAadhaarNo.checked) {
            aadhaarField.style.display = 'none';
        }
    });
</script>

<!DOCTYPE html>
<html>

<head>
    <title>User Login</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>

<body>

    <div class="container mt-4">
        <h1>User Login</h1>

        <form id="beneficiary-form" action="/beneficiary" method="POST" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" class="form-control" name="first_name"
                    value="{{ old('first_name') }}"><br>
                <span id="first_name_error" class="text-danger"></span>
            </div>

            <div>
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" class="form-control" name="last_name"
                    value="{{ old('last_name') }}"><br>
                <span id="last_name_error" class="text-danger"></span>
            </div>

            <div>
                <label for="image">Image:</label>
                <input type="file" id="image" class="form-control" name="image"><br>
                <span id="image_error" class="text-danger"></span>
            </div>

            <div>
                <label for="has_aadhaar">Has Aadhaar number?</label>
                <input type="radio" name="has_aadhaar" value="Yes">
                <label>Yes</label>
                <input type="radio" name="has_aadhaar" value="No" onclick="toggleAadhaarInput()">
                <label>No</label>
                <br>
                <span id="has_aadhaar_error" class="text-danger"></span>
            </div>

            <div id="aadhaar_number_field" style="display: none;">
                <label for="aadhaar_number">Aadhaar Number:</label>
                <input type="text" id="aadhaar_number" class="form-control" name="aadhaar_number"
                    value="{{ old('aadhaar_number') }}"><br>
                <span id="aadhaar_number_error" class="text-danger"></span>
            </div>

            <button id="submit-btn" type="submit">Submit</button>
            <button type="button" id="saveButton">Save Data Locally</button>
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <script>
        // Function to save form data to local storage and show a notification
        function saveToLocalStorage() {
            const formData = {
                first_name: $('#first_name').val(),
                last_name: $('#last_name').val(),
                image: $('#image').val(),
                has_aadhaar: $('input[name="has_aadhaar"]:checked').val(),
                aadhaar_number: $('#aadhaar_number').val(),
            };
            localStorage.setItem('formData', JSON.stringify(formData));
            toastr.success('Form data saved locally');
        }

        // Bind the click event of the "Save" button to the saveToLocalStorage function
        $('#saveButton').on('click', function() {
            saveToLocalStorage();
        });

        // Function to load form data from local storage
        function loadFromLocalStorage() {
            const savedData = localStorage.getItem('formData');
            if (savedData) {
                const formData = JSON.parse(savedData);
                $('#first_name').val(formData.first_name);
                $('#last_name').val(formData.last_name);
                $('input[name="has_aadhaar"][value="' + formData.has_aadhaar + '"]').prop('checked', true);
                $('#aadhaar_number').val(formData.aadhaar_number);

                // Set other form field values here

                // Show a notification indicating that data has been loaded from local storage
                toastr.info('Form data loaded from local storage');
            }
        }

        $(document).ready(function() {
            // Load the form data from local storage when the internet connection is restored
            function setInternetStatus(status) {
                if (status) {
                    loadFromLocalStorage();
                    toastr.success('Internet Connection Restored');
                } else {
                    toastr.error('Internet Connection Lost');
                }
            }

            window.addEventListener('online', () => setInternetStatus(true));
            window.addEventListener('offline', () => setInternetStatus(false));

            // Get form element and submit button
            const form = document.getElementById('beneficiary-form');
            const submitBtn = document.getElementById('submit-btn');

            const firstNameInput = document.getElementById('first_name');
            const lastNameInput = document.getElementById('last_name');
            const imageInput = document.getElementById('image');
            const hasAadhaarYes = document.querySelector('input[name="has_aadhaar"][value="Yes"]');
            const hasAadhaarNo = document.querySelector('input[name="has_aadhaar"][value="No"]');
            const aadhaarNumberInput = document.getElementById('aadhaar_number');

            // Disable submit button initially
            submitBtn.disabled = true;

            // Real-time validation for first name field
            firstNameInput.addEventListener('input', function() {
                const firstNameError = document.getElementById('first_name_error');
                const firstName = this.value.trim();

                if (firstName === '') {
                    firstNameError.textContent = 'First Name is required.';
                } else if (firstName.length < 3 || firstName.length > 10) {
                    firstNameError.textContent = 'First Name must be between 3 and 10 characters.';
                } else {
                    firstNameError.textContent = '';
                }

                validateForm();
            });

            // Real-time validation for last name field
            lastNameInput.addEventListener('input', function() {
                const lastNameError = document.getElementById('last_name_error');
                const lastName = this.value.trim();

                if (lastName === '') {
                    lastNameError.textContent = 'Last Name is required.';
                } else if (lastName.length < 3 || lastName.length > 10) {
                    lastNameError.textContent = 'Last Name must be between 3 and 10 characters.';
                } else {
                    lastNameError.textContent = '';
                }

                validateForm();
            });

            // Real-time validation for image field
            imageInput.addEventListener('change', function() {
                const imageError = document.getElementById('image_error');
                const file = this.files[0];
                const maxSizeInBytes = 512 * 1024; // 512KB

                if (!file) {
                    imageError.textContent = 'Image is required.';
                } else if (file.size > maxSizeInBytes) {
                    imageError.textContent = 'Image size should not be greater than 512KB.';
                } else {
                    imageError.textContent = '';
                }

                validateForm();
            });

            // Real-time validation for "Has Aadhaar" radio button
            hasAadhaarYes.addEventListener('change', function() {
                toggleAadhaarInput(true);
                validateForm();
            });

            hasAadhaarNo.addEventListener('change', function() {
                toggleAadhaarInput(false);
                validateForm();
            });

            // Real-time validation for Aadhaar number field
            aadhaarNumberInput.addEventListener('input', function() {
                const aadhaarNumberField = document.getElementById('aadhaar_number_field');
                const aadhaarNumberError = document.getElementById('aadhaar_number_error');
                const aadhaarNumber = this.value.trim();

                if (aadhaarNumberField.style.display !== 'none' && aadhaarNumber === '') {
                    aadhaarNumberError.textContent = 'Aadhaar Number is required.';
                } else if (aadhaarNumber.length !== 12 || !/^\d+$/.test(aadhaarNumber)) {
                    aadhaarNumberError.textContent = 'Aadhaar Number must be a 12-digit numeric value.';
                } else {
                    aadhaarNumberError.textContent = '';
                }

                validateForm();
            });

            function validateForm() {
                const isValidFirstName = document.getElementById('first_name_error').textContent === '';
                const isValidLastName = document.getElementById('last_name_error').textContent === '';
                const isValidImage = document.getElementById('image_error').textContent === '';
                const isValidHasAadhaar = validateHasAadhaar();
                const isValidAadhaarNumber = document.getElementById('aadhaar_number_error').textContent === '';

                // Enable or disable submit button based on overall form validity
                const isValid = isValidFirstName && isValidLastName && isValidImage && isValidHasAadhaar &&
                    isValidAadhaarNumber;

                // Enable submit button if "No" is selected for "Has Aadhaar" and other fields are valid
                if (hasAadhaarNo.checked && isValidFirstName && isValidLastName && isValidImage) {
                    submitBtn.disabled = false;
                } else {
                    submitBtn.disabled = !isValid;
                }

                // Add or remove CSS class to submit button based on form validity
                if (isValid) {
                    submitBtn.classList.add('valid');
                } else {
                    submitBtn.classList.remove('valid');
                }

                // Return validation result
                return isValid;
            }

            function validateHasAadhaar() {
                const hasAadhaarYes = document.querySelector('input[name="has_aadhaar"][value="Yes"]');
                const hasAadhaarNo = document.querySelector('input[name="has_aadhaar"][value="No"]');
                const hasAadhaarError = document.getElementById('has_aadhaar_error');

                if (!hasAadhaarYes.checked && !hasAadhaarNo.checked) {
                    hasAadhaarError.textContent = 'Please select if you have an Aadhaar number.';
                    return false;
                } else {
                    hasAadhaarError.textContent = '';
                    return true;
                }
            }

            function toggleAadhaarInput(hasAadhaar) {
                const aadhaarField = document.getElementById('aadhaar_number_field');
                const aadhaarNumberInput = document.getElementById('aadhaar_number');
                const aadhaarNumberError = document.getElementById('aadhaar_number_error');

                if (hasAadhaar) {
                    aadhaarField.style.display = 'block';
                } else {
                    aadhaarField.style.display = 'none';
                    aadhaarNumberInput.value = ''; // Clear the input field
                    aadhaarNumberError.textContent = ''; // Clear the error message
                }

                // Re-validate the form after toggling Aadhaar input
                validateForm();
            }

            // Prevent form data from being stored in local storage when the form is submitted
            form.addEventListener('submit', function() {
                localStorage.removeItem('formData');
            });

        });

    </script>

</body>

</html>

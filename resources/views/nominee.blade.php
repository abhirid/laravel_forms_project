<!DOCTYPE html>
<html>

<head>
    <title>Nominee Forms</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>

<body>

    <div class="container mt-4">
        <h2>Nominee forms</h2>

        <form method="POST" action="/nominee/{{ $beneficiary_id }}">
            @csrf

            <div class="mb-3">
                <label>Nominee name</label>
                <input type="text" class="form-control" id='full_name' name='full_name' value="{{ old('full_name') }}">

                <span class="text-danger">
                    @error('full_name')
                        {{ $message }}
                    @enderror
                </span>
            </div>

            <div>
                <label for="has_account">Does the nominee have an account with our bank?</label>
                <div>
                    <input type="radio" name="has_account" id="has_account_yes" value="1">
                    <label for="has_account_yes">Yes</label>
                </div>
                <div>
                    <input type="radio" name="has_account" id="has_account_no" value="0">
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
                <input type="text" name="account_number" class="form-control" id="account_number"
                    value="{{ old('account_number') }}">
                <span class="text-danger">
                    @error('account_number')
                        {{ $message }}
                    @enderror
                </span>
            </div>

            <div>
                <label for="account_number_confirmation">Confirm Nominee Account Number:</label>
                <input type="text" name="account_number_confirmation" class="form-control"
                    id="account_number_confirmation" value="{{ old('account_number_confirmation') }}">
                @error('account_number_confirmation')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <button type="submit" id="addNomineeButton">Add Nominee</button>
            </div>
        </form>

        <br>
        <br>

        <div>
            <button type="button" id="saveButton">Save Data Locally</button>
        </div>

    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <script>
        // Toggle the bank name input based on the selected radio button
        $(document).ready(function() {
            const hasAccountYes = $('#has_account_yes');
            const hasAccountNo = $('#has_account_no');
            const bankNameContainer = $('#bank_name_container');

            hasAccountYes.on('change', function() {
                if (hasAccountYes.prop('checked')) {
                    bankNameContainer.hide();
                }
            });

            hasAccountNo.on('change', function() {
                if (hasAccountNo.prop('checked')) {
                    bankNameContainer.show();
                }
            });

            // Real-time validation for name field
            $('#full_name').on('input', function() {
                const fullName = $(this).val().trim();
                const errorElement = $('#full_name_error');

                if (fullName.length < 3 || fullName.length > 10) {
                    errorElement.text('Name must be between 3 and 10 characters');
                } else {
                    errorElement.text('');
                }
            });

            // Real-time validation for account number field
            $('#account_number').on('input', function() {
                const accountNumber = $(this).val().trim();
                const errorElement = $('#account_number_error');
                const accountNumberPattern = /^\d{12}$/;

                if (!accountNumberPattern.test(accountNumber)) {
                    errorElement.text('Account number must be 12 digits');
                } else {
                    errorElement.text('');
                }
            });

            // Real-time validation for account number confirmation field
            $('#account_number_confirmation').on('input', function() {
                const confirmationNumber = $(this).val().trim();
                const accountNumber = $('#account_number').val().trim();
                const errorElement = $('#account_number_confirmation_error');

                if (confirmationNumber !== accountNumber) {
                    errorElement.text('Account numbers do not match');
                } else {
                    errorElement.text('');
                }
            });
        });

        // Function to save form data to local storage
        function saveToLocalStorage() {
            const formData = {
                full_name: $('#full_name').val(),
                has_account: $('input[name="has_account"]:checked').val(),
                bank_name: $('#bank_name').val(),
                account_number: $('#account_number').val(),
                account_number_confirmation: $('#account_number_confirmation').val(),
            };
            localStorage.setItem('nomineeFormData', JSON.stringify(formData));
            toastr.success('Form data saved locally');
        }

        // Bind the click event of the "Save" button to the saveToLocalStorage function
        $('#saveButton').on('click', function() {
            saveToLocalStorage();
        });

        function setInternetStatus(status) {
            if (status) {
                toastr.success('Internet Connection Restored');
            } else {
                toastr.error('Internet Connection Lost');
            }

            // Load the form data from local storage when the internet connection is restored
            if (status) {
                loadFromLocalStorage();
            }
        }

        window.addEventListener('online', () => setInternetStatus(1));
        window.addEventListener('offline', () => setInternetStatus(0));

        function loadFromLocalStorage() {
            const savedData = localStorage.getItem('nomineeFormData');
            if (savedData) {
                const formData = JSON.parse(savedData);
                $('#full_name').val(formData.full_name);
                $('input[name="has_account"][value="' + formData.has_account + '"]').prop('checked', true);
                $('#bank_name').val(formData.bank_name);
                $('#account_number').val(formData.account_number);
                $('#account_number_confirmation').val(formData.account_number_confirmation);

                // Show a notification indicating that data has been loaded from local storage
                toastr.info('Form data loaded from local storage');
            }
        }

        $(document).ready(function() {
            // Load the form data from local storage when the page loads
            if (navigator.onLine) {
                loadFromLocalStorage();
            }
        });

        // Prevent form data from being stored in local storage when the form is submitted
        $('form').on('submit', function() {
            localStorage.removeItem('nomineeFormData');
        });

        @if (session('success'))
    
        toastr.success('{{ session('success') }}');

    @endif

    </script>
</body>

</html>

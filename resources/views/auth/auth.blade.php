@extends('layouts.master')

@section('title', 'User authentication')

@section('body')
    <script>
        $(document).ready(function() {
            $("#login-form").submit(function(event) {
                // Obtains the values of the login fields
                var email = $("#login-form input[name='email']").val();
                var password = $("#login-form input[name='password']").val();
                var error = false;
                
                // Checks if password field is blank
                if (password.trim() === "") {
                    error = true;
                    $("#invalid-password").text("The password is mandatory.");
                    event.preventDefault();
                    $("#login-form input[name='password']").focus();
                } else {
                    $("#invalid-password").text("");
                }

                // Checks if email field is blank
                if (email.trim() === "") {
                    error = true;
                    $("#invalid-email").text("The email address is mandatory.");
                    event.preventDefault();
                    $("#login-form input[name='email']").focus();
                } else {
                    $("#invalid-email").text("");
                } 
            });

            $("#register-form").submit(function(event) {
                // Obtains the values of the register fields
                var name = $("input[name='name']").val();
                var email = $("#register-form input[name='email']").val();
                var password = $("#register-form input[name='password']").val();
                // Regular expression for the password (at least 8 characters, 1+ one digit, 1+ special
                // character among ! - * [ ] $ & /)
                var passwordRegex = /^(?=.*[0-9])(?=.*[!-\*\[\]\$&\/]).{8,}$/;
                var confirmPassword = $("input[name='password_confirmation']").val();
                var error = false;

                // Checks if the confirm-password field is blank
                if (confirmPassword.trim() === "") {
                    error = true;
                    $("#invalid-confirmPassword").text("The re-entry of the password is mandatory.");
                    event.preventDefault();
                    $("input[name='password_confirmation']").focus();
                } else {
                    $("#invalid-confirmPassword").text("");
                } 

                // Check if the password field is blank
                if (password.trim() === "") {
                    error = true;
                    $("#invalid-registrationPassword").text("The password is mandatory.");
                    event.preventDefault();
                    $("#register-form input[name='password']").focus();
                } else if(!passwordRegex.test(password)) {
                    error = true;
                    $("#invalid-registrationPassword").text("The format of the password is wrong (at least 8 characters, of which at least one digit and one special character among ! - * [ ] $ & /).");
                    event.preventDefault();
                    $("#register-form input[name='password']").focus();
                } else {
                    $("#invalid-registrationPassword").text("");
                } 

                // Checks if the email field is blank
                if (email.trim() === "") {
                    error = true;
                    $("#invalid-registrationEmail").text("The email address is mandatory.");
                    event.preventDefault();
                    $("#register-form input[name='email']").focus();
                } else {
                    $("#invalid-registrationEmail").text("");
                }

                // Checks if the name field is blank
                if (name.trim() === "") {
                    error = true;
                    $("#invalid-name").text("The name is mandatory.");
                    event.preventDefault();
                    $("input[name='name']").focus();
                } else {
                    $("#invalid-name").text("");
                } 

                if(!error) {
                    // Checks if the value of the password field and the confirm-password one are the same
                    if(confirmPassword.trim() !== password.trim())
                    {
                        $("#invalid-confirmPassword").text("Insert the same password two times.");
                        event.preventDefault();
                        $("input[name='password_confirmation']").focus();
                    } else {
                        $("#invalid-confirmPassword").text("");
                    } 

                    // Makes the ajax call to verify that the email isn't already in the DB
                    $.ajax({

                        type: 'GET',

                        url: '/ajaxUser',

                        data: {email: email.trim()},

                        success: function (data) {

                            if (data.found)
                            {
                                error = true;
                                $("#invalid-registrationEmail").text("The email already exists in the database.");
                            } else {
                                $("#register-form")[0].submit();
                            }
                        }
                    });

                    return false;
                }
            });

            // Assures the name field has only letters
            document.querySelector("input[name='name']")
            .addEventListener('input', function () {
                    this.value = this.value.replace(/[^a-zA-ZàèéìòùÀÈÉÌÒÙ]/g, '');
            });
        });
    </script>

    <div class="container-fluid">
        <!-- Toggle to change module -->
        <div class="row">
            <ul class="nav nav-tabs mb-3 justify-content-end">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#login-tab">{{ trans('messages.login') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#register-tab">{{ trans('messages.register') }}</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="tab-content">
                <!-- Login module -->
                <div class="tab-pane active" id="login-tab">
                    <form id="login-form" action="{{ route('login') }}" method="post">
                    @csrf
                        <div class="form-group">
                            <input type="text" name="email" class="form-control" placeholder="Email..."/>
                        </div>
                        <span class="invalid-input" id="invalid-email"></span>

                        <div class="form-group mt-3">
                            <input type="password" name="password" class="form-control" placeholder="Password..."/>
                        </div>
                        <span class="invalid-input" id="invalid-password"></span>

                        <div class="form-group text-center mt-3">
                            <label for="login-submit" class="btn btn-primary w-100"><i class="bi bi-door-open"></i> {{ trans('messages.login') }}</label>
                            <input id="login-submit" class="d-none" type="submit" value="Login">
                        </div>
                    </form>
                </div>
                
                <!-- Register module -->
                <div class="tab-pane" id="register-tab">
                    <form id="register-form" action="{{ route('register') }}" method="post">
                    @csrf
                        <div class="form-group">
                            <input type="text" name="name" class="form-control" placeholder="{{ trans('messages.register_name') }}"/>
                        </div>
                        <span class="invalid-input" id="invalid-name"></span>

                        <div class="form-group mt-3">
                            <input type="text" name="email" class="form-control" placeholder="{{ trans('messages.register_email') }}">
                        </div>
                        <span class="invalid-input" id="invalid-registrationEmail"></span>

                        <div class="form-group mt-3">
                            <input type="password" name="password" class="form-control" placeholder="{{ trans('messages.type_password') }}"/>
                        </div>
                        <span class="invalid-input" id="invalid-registrationPassword"></span>

                        <div class="form-group mt-3">
                            <input type="password" name="password_confirmation" class="form-control" placeholder="{{ trans('messages.re_type_password') }}"/>
                        </div>
                        <span class="invalid-input" id="invalid-confirmPassword"></span>

                        <div class="form-group text-center mt-3">
                            <label for="register-submit" class="btn btn-primary w-100"><i class="bi bi-person-plus"></i> {{ trans('messages.register') }}</label>
                            <input id="register-submit" class="d-none" type="submit" value="Register">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
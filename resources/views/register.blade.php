<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
<section class="vh-100" style="min-height: 100vh;">
  <div class="container-fluid h-custom vh-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-md-9 col-lg-6 col-xl-5">
        <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.webp"
          class="img-fluid" alt="Sample image">
      </div>
      <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1 mb-5">
        <form method="post" action="/register">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <p class="lead fw-normal mb-3 me-3 fs-1">Sign Up</p>
          <!-- Email input -->
          <!-- <div class="form-outline mb-4">
            <input type="text" id="form_fullname" class="form-control form-control-lg"
              placeholder="Enter your full name" />
            <label class="form-label" for="form_fullname">Full name</label>
          </div> -->
<!--           
          <div class="form-outline mb-4">
            <input type="text" id="form_alias" class="form-control form-control-lg"
              placeholder="Enter your short name" />
            <label class="form-label" for="form_alias">Short name</label>
          </div>
          
          <div class="form-outline mb-4 d-none">
            <input type="color" class="form-control form-control-color" id="form_color" value="#563d7c" title="Choose your color">
            <label class="form-label" for="form_color">Theme Color</label>
          </div> -->

          <!-- <div class="form-group form-outline mb-4">
              @if ($errors->has('type'))
                  <span class="text-danger text-left">{{ $errors->first('type') }}</span>
              @endif
              <select class="form-control form-control-lg" id="form_type" name="type">
                  <option value="" selected disabled>Choose election coverage</option>
                  <option value="national">National</option>
                  <option value="Regional">Regional</option>
                  <option value="city">City</option>
                  <option value="district">District</option>
                  <option value="municipality">Municipality</option>
                  <option value="barangay">Barangay</option>
                <option value="sk">SK</option>
              </select>
              <label for="exampleFormControlSelect11">Election Coverage</label>
          </div> -->
<!--           
          <div class="form-outline mb-4">
            <input type="text" id="form_color" class="form-control form-control-lg"
              placeholder="Enter your color" />
            <label class="form-label" for="form_color">Theme Color</label>
          </div> -->
          
          <!-- Email input -->
          <div class="form-outline mb-4">
            @if ($errors->has('email'))
                <span class="text-danger text-left">{{ $errors->first('email') }}</span>
            @endif
            <input type="email" id="form_email" class="form-control form-control-lg"
              placeholder="Enter a valid email address" name="email" value="{{ old('email') }}" required="required" autofocus/>
            <label class="form-label" for="form_email">Email address</label>
          </div>

          <!-- Password input -->
          <div class="form-outline mb-3">
             @if ($errors->has('password'))
                <span class="text-danger text-left">{{ $errors->first('password') }}</span>
            @endif
            <input type="password" id="form_password" class="form-control form-control-lg"
              placeholder="Enter password" name="password" value="{{ old('password') }}" required="required"/>
            <label class="form-label" for="form_password">Password</label>
           
          </div>
          <div class="form-outline mb-3">
            @if ($errors->has('password_confirmation'))
                <span class="text-danger text-left">{{ $errors->first('password_confirmation') }}</span>
            @endif
            <input type="password" id="form_confirm_password" class="form-control form-control-lg"
              placeholder="Enter confirm password" name="password_confirmation" value="{{ old('password_confirmation') }}" required="required"/>
            <label class="form-label" for="form_confirm_password">Confirm Password</label>
          </div>

          <div class="text-center text-lg-start mt-4 pt-2">
            <button type="submit" class="btn btn-primary btn-lg"
              style="padding-left: 2.5rem; padding-right: 2.5rem;" >Register</button>
            <p class="small fw-bold mt-2 pt-1 mb-0">Already have an account? <a href="/login"
                class="link-danger">Login</a></p>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div
    class="d-flex flex-column flex-md-row text-center text-md-start justify-content-between py-4 px-4 px-xl-5 bg-primary d-none">
    <!-- Copyright -->
    <div class="text-white mb-3 mb-md-0">
      Copyright © 2024. All rights reserved.
    </div>
    <!-- Copyright -->

    <!-- Right -->
    <!-- <div>
      <a href="#!" class="text-white me-4">
        <i class="fab fa-facebook-f"></i>
      </a>
      <a href="#!" class="text-white me-4">
        <i class="fab fa-twitter"></i>
      </a>
      <a href="#!" class="text-white me-4">
        <i class="fab fa-google"></i>
      </a>
      <a href="#!" class="text-white">
        <i class="fab fa-linkedin-in"></i>
      </a>
    </div> -->
    <!-- Right -->
  </div>
</section>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

</head>

<body>
    <section class="vh-100" style="min-height: 100vh;">
        <div class="container-fluid h-custom vh-100">
            <div class="row d-flex justify-content-center align-items-centers mt-5 h-100 text-center">
                <div class="col-md-8 col-lg-6 col-xl-4">
                    <form method="post" action="/update-account">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <p class="lead fw-normal mb-3 me-3 fs-1">Setup Account</p>
                        <!-- Fullname input -->
                        <div class="form-outline mb-4">
                            <label class="form-label" for="form_name">Name your first survey!</label>
                            <input type="text" id="from_name" class="form-control form-control-lg" placeholder="Enter your name" name="name" required="required" autofocus />
                        </div>

                        <div class=" mt-4">
                            <button type="submit" class="btn btn-lg btn-primary w-100"></i>Submit</button>
                        </div>

                        <!-- <div class="w-100 text-center mb-3">
              <span class="w-100 text-danger text-center">{{ $errors->first('failed') }}</span>
            </div> -->

                    </form>
                </div>
            </div>
        </div>

    </section>
</body>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

<script>

</script>

</html>
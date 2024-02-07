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
              <label class="form-label" for="form_name">Fullname</label>
              <input type="text" id="from_name" class="form-control form-control-lg" placeholder="Enter your name" name="name" value="{{old('name')}}" required="required" autofocus />
              @if ($errors->has('name'))
              <span class="text-danger text-left">{{ $errors->first('name') }}</span>
              @endif
            </div>
            <!-- Alias input -->
            <div class="form-outline mb-4">
              <label class="form-label" for="form_alias">Alias</label>
              <input type="text" id="form_alias" class="form-control form-control-lg" placeholder="Enter your alias" name="alias" value="{{old('alias','Me')}}" required="required" autofocus />
              @if ($errors->has('name'))
              <span class="text-danger text-left">{{ $errors->first('name') }}</span>
              @endif
            </div>

            <div class="form-outline mb-4">
              <label class="form-label" for="form_color">Your Color</label>
              <div class="d-flex justify-content-center">
                <input type="color" class="form-control form-control-color" id="form_color" value="#Ffc0cb" name="color" title="Choose your color" style="width: 100px;height:100px">
              </div>
            </div>

            <div class="form-group form-outline mb-4">
              <label class="form-label" for="exampleFormControlSelect11">Election Coverage</label>
              <select onchange="onCoverageChanged()" class="form-control form-control-lg" id="form_type" name="type" required="required" autofocus>
                <option value="" selected disabled>Choose election coverage</option>
                <!-- <option value="national">National</option> -->
                <!-- <option value="Regional">Regional</option> -->
                <option value="city">City</option>
                <!-- <option value="district">District</option> -->
                <option value="municipality">Municipality</option>
                <option value="barangay">Barangay</option>
              </select>
              @if ($errors->has('type'))
              <span class="text-danger text-left">{{ $errors->first('type') }}</span>
              @endif
            </div>


            <div>
              <div id="address_city" class="form-group mb-3 d-none">
                <label class="form-label" for="exampleFormControlSelect11">Choose City</label>
                <select onchange="loadMunicipality()" onload="loadCities()" class="form-control border-01 bg-white1" id="t_city" name="t_city" required="required" autofocus>
                  <option value="" selected disabled>Choose City</option>
                </select>
              </div>
              <div id="address_municipality" class="form-group mb-3 d-none">
                <label class="form-label" for="exampleFormControlSelect11">Choose Municipality</label>
                <select onchange="loadBarangay()" class="form-control border-01 bg-white1" id="t_municipality" name="t_municipality" autofocus>
                  <option value="" selected disabled>Choose Municipality</option>
                </select>
            </div>
            <div id="address_barangay" class="form-group mb-4 d-none">
              <label class="form-label" for="exampleFormControlSelect11">Choose Barangay</label>
              <select class="form-control border-01 bg-white1" id="t_barangay"  name="t_barangay"autofocus>
                <option value="" selected disabled>Choose Barangay</option>
              </select>
            </div>
        </div>

        <div class=" mt-4 mb-5">
          <button type="submit" class="btn btn-lg btn-primary w-100"></i>Save</button>
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
  let cities = [];
  let municipalities = [];
  let barangay = [];

  loadCities()

  function onCoverageChanged() {
    var c_data = document.getElementById('form_type').value

    document.getElementById('address_city').classList.add('d-none')
    document.getElementById('address_municipality').classList.add('d-none')
    document.getElementById('address_barangay').classList.add('d-none')

    if(c_data == 'city'){
      document.getElementById('address_city').classList.remove('d-none')

      document.getElementById('t_city').setAttribute('required', 'required')
      document.getElementById('t_municipality').removeAttribute('required')
      document.getElementById('t_barangay').removeAttribute('required')
    }
    if(c_data == 'municipality'){
      document.getElementById('address_city').classList.remove('d-none')
      document.getElementById('address_municipality').classList.remove('d-none')

      document.getElementById('t_city').setAttribute('required', 'required')
      document.getElementById('t_municipality').setAttribute('required', 'required')
      document.getElementById('t_barangay').removeAttribute('required')
    }
    if(c_data == 'barangay'){
      document.getElementById('address_city').classList.remove('d-none')
      document.getElementById('address_municipality').classList.remove('d-none')
      document.getElementById('address_barangay').classList.remove('d-none')

      document.getElementById('t_city').setAttribute('required', 'required')
      document.getElementById('t_municipality').setAttribute('required', 'required')
      document.getElementById('t_barangay').setAttribute('required', 'required')
    }
  }

  function loadCities(){
    axios.get('/api/cities?region=V',{
                "content-type": "application/json",
                "Accept": "application/json"
            })
      .then((res) => {
        var t = '<option value="" selected disabled>Choose City</option>'
        res.data.forEach(c => {
          t += '<option value="'+ c.id +'">'+ c.name +'</option>'
        });
        document.getElementById("t_city").innerHTML = t
      })
  }

  function loadMunicipality(){
    var id = document.getElementById("t_city").value
    axios.get('/api/municipalities?id=' + id,{
                "content-type": "application/json",
                "Accept": "application/json"
            })
      .then((res) => {
        var t = '<option value="" selected disabled>Choose Municipality</option>'
        res.data.forEach(c => {
          t += '<option value="'+ c.id +'">'+ c.name +'</option>'
        });
        document.getElementById("t_municipality").innerHTML = t
      })
  }
  function loadBarangay(){
    var id = document.getElementById("t_municipality").value
    axios.get('/api/barangay?id=' + id,{
                "content-type": "application/json",
                "Accept": "application/json"
            })
      .then((res) => {
        var t = '<option value="" selected disabled>Choose Barangay</option>'
        res.data.forEach(c => {
          t += '<option value="'+ c.id +'">'+ c.name +'</option>'
        });
        document.getElementById("t_barangay").innerHTML = t
      })
  }
</script>

</html>
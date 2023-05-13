@extends('layouts.app')

@section('content')

	@if($errors->any())
		<div class="alert alert-danger">
			@foreach ($errors->all() as $error)
				{{ $error }} <br>
			@endforeach
		</div>
	@endif

	Copy code
	<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Upload Images</div>
                <div class="card-body">
                    <form method="POST"  enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="aries">Aries:</label>
                            <input type="file" name="aries" id="aries" class="form-control-file" onchange="previewImage('ariesPreview', this)">
                            <img id="ariesPreview" src="#" alt="Aries Preview" class="img-thumbnail hidden">
                        </div>
                        <div class="form-group">
                            <label for="taurus">Taurus:</label>
                            <input type="file" name="taurus" id="taurus" class="form-control-file" onchange="previewImage('taurusPreview', this)">
                            <img id="taurusPreview" src="#" alt="Taurus Preview" class="img-thumbnail hidden">
                        </div>
                        <div class="form-group">
                            <label for="gemini">Gemini:</label>
                            <input type="file" name="gemini" id="gemini" class="form-control-file" onchange="previewImage('geminiPreview', this)">
                            <img id="geminiPreview" src="#" alt="Gemini Preview" class="img-thumbnail hidden">
                        </div>
                        <div class="form-group">
                            <label for="cancer">Cancer:</label>
                            <input type="file" name="cancer" id="cancer" class="form-control-file" onchange="previewImage('cancerPreview', this)">
                            <img id="cancerPreview" src="#" alt="Cancer Preview" class="img-thumbnail hidden">
                        </div>
                        <div class="form-group">
                            <label for="leo">Leo:</label>
                            <input type="file" name="leo" id="leo" class="form-control-file" onchange="previewImage('leoPreview', this)">
                            <img id="leoPreview" src="#" alt="Leo Preview" class="img-thumbnail hidden">
                        </div>
                        <div class="form-group">
                            <label for="virgo">Virgo:</label>
                            <input type="file" name="virgo" id="virgo" class="form-control-file" onchange="previewImage('virgoPreview', this)">
                            <img id="virgoPreview" src="#" alt="Virgo Preview" class="img-thumbnail hidden">
                        </div>
                        <div class="form-group">
                            <label for="libra">Libra:</label>
                            <input type="file" name="libra" id="libra" class="form-control-file" onchange="previewImage('libraPreview', this)">
                            <img id="libraPreview" src="#" alt="Libra Preview" class="img-thumbnail hidden">
                        </div>
                        <div class="form-group">
                            <label for="scorpio">Scorpio:</label>
                            <input type="file" name="scorpio" id="scorpio" class="form-control-file" onchange="previewImage('scorpioPreview', this)">
                            <img id="scorpioPreview" src="#" alt="Scorpio Preview" class="img-thumbnail hidden">
                        </div>
                        <div class="form-group">
                            <label for="sagitt
							</div>
                    <div class="form-group">
                        <label for="capricorn">Capricorn:</label>
                        <input type="file" name="capricorn" id="capricorn" class="form-control-file" onchange="previewImage('capricornPreview', this)">
                        <img id="capricornPreview" src="#" alt="Capricorn Preview" class="img-thumbnail hidden">
                    </div>
                    <div class="form-group">
                        <label for="aquarius">Aquarius:</label>
                        <input type="file" name="aquarius" id="aquarius" class="form-control-file" onchange="previewImage('aquariusPreview', this)">
                        <img id="aquariusPreview" src="#" alt="Aquarius Preview" class="img-thumbnail hidden">
                    </div>
                    <div class="form-group">
                        <label for="pisces">Pisces:</label>
                        <input type="file" name="pisces" id="pisces" class="form-control-file" onchange="previewImage('piscesPreview', this)">
                        <img id="piscesPreview" src="#" alt="Pisces Preview" class="img-thumbnail hidden">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Upload Images</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    function previewImage(previewId, input) {
        var preview = document.getElementById(previewId);
        var file = input.files[0];
        var reader = new FileReader();

        reader.onloadend = function () {
            preview.src = reader.result;
            preview.classList.remove('hidden');
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "";
            preview.classList.add('hidden');
        }
    }
</script>



@stop
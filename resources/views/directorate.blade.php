@extends('layouts.app')
@section('content')
    <form class="d-flex justify-content-end mb-2">
        <button name="upload_teacher" class="btn btn-warning">Загрузить нагрузку преподавателей</button>
    </form>

    <div class="accordion" id="accordionFormat{{ $faculty_id }}">
        @foreach($formEducation as $form)
			@switch ($form)
				@case("Bakalavr")
					{{ $formRus = "Бакалавриат" }}
					@break
				@case("Magis")
					{{ $formRus = "Магистратура" }}
					@break
				@case("Zaoch")
					{{ $formRus = "Заочное обучение" }}
					@break
			@endswitch
            <div class="accordion" id="accordionInstitute">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading{{ $form . $inst['id'] }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $form . $inst['id'] }}" aria-expanded="false" aria-controls="collapse{{ $form . $inst['id'] }}">
                            {{ $formRus }}
                        </button>
                    </h2>
                    <div id="collapse{{ $form . $inst['id'] }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $form . $inst['id'] }}" >
                        <div class="accordion-body">
							@foreach ($profiles as $prof)
								<div class="accordion" id="accordionInstitute">
									@switch($form_key)
										@case('Bakalavr')
											{{ view('stream_accord', ['inst' => $inst, 'streams' => $prof->streams_b, 'form' => $form_key]) }}
											@break

										@case('Magis')
											{{ view('stream_accord', ['inst' => $inst, 'streams' => $prof->streams_m, 'form' => $form_key]) }}
											@break

										@case('Zaoch')
											{{ view('stream_accord', ['inst' => $inst, 'streams' => $prof->streams_z, 'form' => $form_key]) }}
											@break

										@default
									@endswitch
								</div>
							@endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
	<script>
        function showComment(id) {
            let comment = document.getElementById('comment' + id);
            comment.style.display = 'block';
        }
    </script>
@endsection
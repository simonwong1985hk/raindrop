# Request

<meta name="csrf-token" content="{{ csrf_token() }}">

<script type="text/javascript">
	$(document).ready(function () {
		$('#submit').click(function (event) {
			event.preventDefault();

			$.ajaxSetup({
			    headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    }
			});
			
			var name = $('#name').val();
			var email = $('#email').val();
			var languages = [];
			$('input[name="languages"]:checked').each(function(){
				languages.push(this.value);
			});
			
			$.ajax({
				method: "post",
				url: "{{ route('CONTROLLER.FUNCTION') }}",
				data: {
					name: name,
					email: email,
					languages: languages
				},
				success: function (response) {
					$('#SELECTOR1').text(response.name);
					$('#SELECTOR2').text(response.email);
					$('#SELECTOR3').text(response.languages);
				},
				error: function (message) {                                   
					console.log(message); 
				}
			});
		});
	});
</script>

#Response

return response()->json([
    'name' => $name,
    'email' => $email,
    'languages' => $languages
]);

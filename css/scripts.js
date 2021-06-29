$(document).ready(function() {
	$('#policy-div').show();
	
});
function GetAccept(answer){
		var answer = document.getElementById("AcceptP").value;

		changePolicy(answer);
	};	
function GetNAccept(){
		var answer = document.getElementById("NAcceptP").value;
		changePolicy(answer);
	};
function changePolicy(answer){
			$.ajax({
				url: 'changepolicy.php',
				type: 'POST',
				data: {
					answer: answer
				},
				cache: false,
				success: function(dataResult){
					var dataResult = JSON.parse(dataResult);
					if(dataResult.statusCode==200){
						alert('YOUR PRIVACY POLICY AGREEMENT HAS BEEN CHANGED');						
					}
					else if(dataResult.statusCode==201){
					   alert('Error occured !');
					}
					
				}
			});
	};
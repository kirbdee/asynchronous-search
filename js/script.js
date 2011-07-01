/* Author: Kirby Domingo @kirbdee

*/

$('#search_bar').focus( function(){
	if($(this).val() == $(this).attr('data-initial') || $(this).val() == '' ){
			$(this).val('');
		}
	});
	
$('#search_bar').blur( function(){
		if($(this).val() == $(this).attr('data-initial') || $(this).val() == '' ){
			$(this).val($(this).attr('data-initial'));
		}
	});
	
var t_out;
var src_val;
$('#search_bar').keyup( function(event){
	if (event.keyCode == '13') {
    event.preventDefault();
		$("#results li.place:first-child").click();
   }
	else{
		if(t_out) clearTimeout(t_out); 
		src_val =$(this).val();
		t_out = setTimeout("search()",100);
	}
	});


	function search(){
		$('#results').empty();
		if(src_val != ''){
			$.post("database.php", { query: src_val },function(data) {				
				var j_data = $.parseJSON(data);
				if(j_data != ""){
					$('#results').show()
					$.each(j_data,function(){
	  				$('#results').append("<li class='place'l data-lat='"+this.latitude+"' data-lng='"+this.longitude+"'>"+this.place_name+" "+this.admin_code1+", "+this.postal_code+"</li>");
						});
				}
				else $('#results').append("<li>Nothing Found ):</li>");
				});
			}
		else{ $('#results').hide();}
		}

	$("#results li.place").live('click',function(){
		$('#search_bar').val($(this).text());
		$('#search_bar').data('coord',{ lat: $(this).attr('data-lat'), lng: $(this).attr('data-lng') });
		$('#results').empty();
		$('#results').hide();
		$("#search_area form").submit();
	});	
	

	$("#search_area form").submit(function(event){
	event.preventDefault();
	
	var lat = $('#search_bar').data("coord").lat;
	var lng = $('#search_bar').data("coord").lng;
	var place_mark = new google.maps.LatLng(lat, lng);
	map.setCenter(place_mark);
	marker.setPosition(place_mark);
	});










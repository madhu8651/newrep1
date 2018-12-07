<style>
.graph_row_style{
	background: #afabab;
    height: 29px;
    padding-top: 4px;
    color: white;
    font-weight: bold;
}
.col{
	height: 206px;
    padding-top: 0px;
    //border-top: 1px solid #e4dcdc;
	font-size: 126px;	
    //margin-top: -43px;
	margin-bottom: -23px;
}
.legend_title{
	height: 0px;
    background: #2C8329;
    margin-top: 1px;
    color: green;
    font-size: 9px;
}
.legend_title1{
	height: 0px;
    background: #B42222;
    margin-top: 1px;
    color: #B42222;
    font-size: 9px;
}
.text_area{
	font-size: 70px;
    word-wrap: break-word;
    margin-top: 33px;
}
</style>
<script>
function drawSarahChart(a,b,c,d,f,g) {
			console.log(a)    
			var color1="", target="", target_title="";
			var data = new google.visualization.DataTable();
			data.addColumn('string', 'User');
			data.addColumn('number', g);
			data.addColumn({type: 'number', role: 'ID'});
			data.addColumn({type: 'string', role: 'style'});
			data.addColumn({type: 'string', role: 'tooltip', p: {html:true}});
			for(j=0;j<a.length;j++){
				if(d!=""){
					if(parseInt(a[j].num_calls) > parseInt(d)){
						target = 0;
					}else{
						target = d - a[j].num_calls;
					}					
					if(parseInt(a[j].num_calls) >= f){
						color1='#2C8329';
						data.addRow([a[j].callersname, parseInt(a[j].num_calls),parseInt(a[j].User_ID),'color:green',g+': '+a[j].num_calls]);	
						data.addRow(["Pending", parseInt(target),1,'color:#959795','Pending: '+target]);	
					}else{			
						color1='#B42222';
						data.addRow([a[j].callersname, parseInt(a[j].num_calls),parseInt(a[j].User_ID),'color:#B42222',g+': '+a[j].num_calls]);
						data.addRow(["Pending", parseInt(target),1,'color:#959795','Pending: '+target]);
					}
				}else{
					if(parseInt(a[j].num_calls) >= f){
						color1='#2C8329';
						data.addRow([a[j].callersname, parseInt(a[j].num_calls),parseInt(a[j].User_ID),'color:green',g+': '+a[j].num_calls]);				
					}else{			
						color1='#B42222';
						data.addRow([a[j].callersname, parseInt(a[j].num_calls),parseInt(a[j].User_ID),'color:#B42222',g+': '+a[j].num_calls]);
					}
				}
			}
			if(d != ""){
				target_title = 'Total Target: ' + d;
			}else{
				target_title = "";
			}
			var options = {
				height:200,
				is3D:'true',
				title: target_title,
				legend: {
					position: 'bottom'
				},
				chartArea: 'auto',
				pieSliceText: 'percentage',
				crosshair: { 
					focused: { color: '#3bc', opacity: 0.8 } 
				},
				vAxis: {title: g},
				//hAxis: {title: "Name"},
				//vAxis:{minValue:0,maxValue:5,gridlines:{count:6}},
				//vAxis: {format: 'currency'},
				curveType:'function',
				pointsVisible:'true',
				colors:[color1,'#959795']
			};
			if(c=="pie"){
				var chart = new google.visualization.PieChart(document.getElementById(b));
				chart.draw(data, options);
				function resize () {
					// change dimensions if necessary
					chart.draw(data, options);
				}
				if (window.addEventListener) {
					window.addEventListener('resize', resize);
				}
				else {
					window.attachEvent('onresize', resize);
				}
			}
			if(c=="line"){
				var chart = new google.visualization.LineChart(document.getElementById(b));
				chart.draw(data, options);
				function resize () {
					// change dimensions if necessary
					chart.draw(data, options);
				}
				if (window.addEventListener) {
					window.addEventListener('resize', resize);
				}
				else {
					window.attachEvent('onresize', resize);
				}
			}
			if(c=="column"){
				var chart = new google.visualization.ColumnChart(document.getElementById(b));
				chart.draw(data, options);
				function resize () {
					// change dimensions if necessary
					chart.draw(data, options);
				}
				if (window.addEventListener) {
					window.addEventListener('resize', resize);
				}
				else {
					window.attachEvent('onresize', resize);
				}
			}
			if(c=="bar"){
				var chart = new google.visualization.BarChart(document.getElementById(b));
				chart.draw(data, options);
				function resize () {
					// change dimensions if necessary
					chart.draw(data, options);
				}
				if (window.addEventListener) {
					window.addEventListener('resize', resize);
				}
				else {
					window.attachEvent('onresize', resize);
				}
			}
			/* var selectHandler = function(e) { 
				var selectedItem = chart.getSelection()[0];
				if (selectedItem) {
					var topping = data.getValue(selectedItem.row, 2);
					if(topping!=1){
						alert('The user selected ' + topping);
					}
				}
				chart.setSelection([]);
			}*/
			var selectHandler1 = function(e) { 
				$('#charts').css('cursor','default')
			}
		//google.visualization.events.addListener(chart, 'select', selectHandler);
		google.visualization.events.addListener(chart, 'onmouseover', selectHandler1);
      }
	  function drawSarahChart1(a,b,c,d,f,g) {
			console.log(d)        
			console.log(f)  
			var row="";
				//row +='<div class="row graph_row_style"><div class="col-md-12">User '+g+' Score</div></div>';
			for(i=0;i<a.length;i++){
				if(a[i].num_calls>=f){					
					row +='<div class="row col col1" onclick="get_id()" id="'+a[i].User_ID+'"><div class="col-md-12 text_area" style="color:green;">'+a[i].num_calls+'</div></div><div class="row"><div class="col-md-3"></div><div class="col-md-8" style="margin-left: -31px;font-size: 12px;"><span class="legend_title">aaaaa</span><span style="margin-left: 5px">'+g+'</span></div></div>';
				}else{				
					row +='<div class="row col col1" onclick="get_id()" id="'+a[i].User_ID+'"><div class="col-md-12 text_area" style="color:#B42222;">'+a[i].num_calls+'</div></div><div class="row"><div class="col-md-3"></div><div class="col-md-8" style="margin-left: -31px;font-size: 12px;"><span class="legend_title1">aaaaa</span><span style="margin-left: 5px">'+g+'</span></div></div>';
				}
				if(a[i].num_calls.length>=10){
					$(".text_area").css({fontSize: 50});
				}
			}
			$("#"+b).append(row);
      }
	  function get_id(){
		var val = $(".col1").attr("id");
		//alert(val)
	  }	  
		</script>
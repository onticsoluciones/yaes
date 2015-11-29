/*** First Chart in Dashboard page ***/

	$(document).ready(function() {
		progressInfo = new Highcharts.Chart({
			chart: {
				renderTo: 'load',
				margin: [0, 0, 0, 0],
				backgroundColor: null,
                plotBackgroundColor: 'none',
							
			},
			
			title: {
				text: null
			},

			tooltip: {
				formatter: function() { 
					return this.point.name +': '+ this.y +' %';
						
				} 	
			},
		    series: [
				{
				borderWidth: 2,
				borderColor: '#F1F3EB',
				shadow: false,	
				type: 'pie',
				name: 'Income',
				innerSize: '65%',
				data: [
					{ name: 'load percentage', y: 0.0, color: '#b2c831' },
					{ name: 'rest', y: 100.0, color: '#3d3d3d' }
				],
				dataLabels: {
					enabled: false,
					color: '#000000',
					connectorColor: '#000000'
				}
			}]
		});

	});

function updateProgressInfo(percent)
{
	progressInfo.series[0].update({
		data: [
			{ name: 'load percentage', y: percent, color: '#b2c831' },
			{ name: 'rest', y: 100 - percent, color: '#3d3d3d' }
		]
	});

	$("#load-label").html(Math.round(percent * 100) / 100 + "%");
}

function updateVulnInfo(percent)
{
	vulnInfo.series[0].update({
		data: [
			{ name: 'Used', y: percent, color: '#fa1d2d' },
			{ name: 'Rest', y: 100 - percent, color: '#3d3d3d' }
		]
	});

	$("#space-label").html(Math.round(percent * 100) / 100 + "%");
}

/*** second Chart in Dashboard page ***/

	$(document).ready(function() {
		vulnInfo = new Highcharts.Chart({
			chart: {
				renderTo: 'space',
				margin: [0, 0, 0, 0],
				backgroundColor: null,
                plotBackgroundColor: 'none',
							
			},
			
			title: {
				text: null
			},

			tooltip: {
				formatter: function() { 
					return this.point.name +': '+ this.y +' %';
						
				} 	
			},
		    series: [
				{
				borderWidth: 2,
				borderColor: '#F1F3EB',
				shadow: false,	
				type: 'pie',
				name: 'SiteInfo',
				innerSize: '65%',
				data: [
					{ name: 'Used', y: 0.0, color: '#fa1d2d' },
					{ name: 'Rest', y: 100.0, color: '#3d3d3d' }
				],
				dataLabels: {
					enabled: false,
					color: '#000000',
					connectorColor: '#000000'
				}
			}]
		});
		
	});


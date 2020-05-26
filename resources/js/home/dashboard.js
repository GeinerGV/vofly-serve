import { fetcherApiAuth } from "../functions";

var ctxUsers = document.getElementById('dashboardChart').getContext('2d');
	
const dias = ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sábado"]
const meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"]

function getLastData(len, currentPos, data) {
	const maxLen = data.length;
	currentPos = maxLen<=currentPos ? maxLen-1 : (currentPos<0 ? 0 : currentPos);
	const last = []
	let lastPos = currentPos-((len % maxLen)-1);
	lastPos += lastPos<0 ? maxLen : 0;
	//return {len, currentPos, maxLen, lastPos}
	for (let i = 0; i < len; i++) {
		const pos = (lastPos+i) % maxLen;
		last.push(data[pos]);
	}
	return last;
}

function getLastWeek() {
	return getLastData(7, new Date().getDay(), dias);
}

function getLastYear() {
	return getLastData(12, new Date().getMonth(), meses);
}

function getDataDashboard(dataRequest) {
	return axiosApi.post("dashboard", dataRequest).then((res)=>{
		const len = dataRequest.len;
		const resultData = [...res.data.data];
		const dataset = new Array(len).fill().map((emptyVal, idx)=>{
			if (!resultData.length) return 0;
			let date = new Date();
			switch (dataRequest.time_type) {
				case "dia":
					date.setDate(date.getDate()-(len-1)+idx);
					break;
				case "mes":
					date.setMonth(date.getMonth()-(len-1)+idx);
					break;
			}
			let index = resultData.findIndex(itm=>{
				let itmDate;
				switch (dataRequest.time_type) {
					case "dia":
						itmDate = new Date(itm.date+" ");
						return itmDate.getDate() == date.getDate() && itmDate.getMonth()==date.getMonth() && itmDate.getFullYear() == date.getFullYear();
					case "mes":
						itmDate = new Date(itm.year, itm.month-1);
						return itmDate.getMonth()==date.getMonth() && itmDate.getFullYear() == date.getFullYear();
				}
			});
			if (index>=0) {
				const count = resultData[index].count;
				resultData.splice(index, 1);
				return count;
			}
			return 0;
		});
		let labels;
		switch (dataRequest.time_type) {
			case "dia":
				labels = getLastData(len, new Date().getDay(), dias);
				break;
			case "mes":
				labels = getLastData(len, new Date().getMonth(), meses);
				break;
		}
		return {dataset, labels, resultData};
	}).catch(function (err) {
		console.log(err);
	});
}

function getLenFromTimeType(type) {
	switch (type) {
		case "mes":
			return 12;
		case "dia":
			return 7;
		default:
			return 7;
	}
}

function getColorFromDataType(type) {
	switch (type) {
		case "usuarios":
			return "#17a2b8";
		case "drivers":
			return "#dc3545";
		case "pedidos":
			return "#007bff";
		default:
			return "#007bff";
	}
}

const defDataDashboard = {
	time_type: "dia",
	data_type: "pedidos",
}

getDataDashboard({
	len: getLenFromTimeType(defDataDashboard.time_type),
	...defDataDashboard
}).then(all=>{
	//console.log(all);
	window.time_type = defDataDashboard.time_type;
	window.data_type = defDataDashboard.data_type;

	window.dasboardChart = new Chart(ctxUsers, {
		// The type of chart we want to create
		type: 'line',
	
		// The data for our dataset
		data: {
			labels: all.labels,
			datasets: [{
				label: 'Pedidos',
				backgroundColor: '#007bff',
				borderColor: '#fff',
				data: all.dataset
			}]
		},
	
		// Configuration options go here
		options: {
			responsive: true
		}
	});
}).catch(err=>{

})

$('#time_type').on('change', function() {
	if (window.dasboardChart) {
		const len = getLenFromTimeType(this.value);
		time_type = this.value;
		getDataDashboard({
			time_type,
			data_type,
			len
		}).then(function(all){
			dasboardChart.data.labels.splice(0,dasboardChart.data.labels.length, ...all.labels);
			dasboardChart.data.datasets[0].data.splice(0, 
				dasboardChart.data.datasets[0].data.length, ...all.dataset
			);
			dasboardChart.update();
		}).catch(function(err) {
			console.log("ChangeTimetype: No se puedo actualizar data");
		})
	}
});

$('.select_data_type').on("click", function () {
	const value = $(this).attr("data-value");
	if (window.dasboardChart && value && data_type!==value.toLowerCase()) {
		data_type = value.toLowerCase();
		const len = getLenFromTimeType(this.value);
		getDataDashboard({
			time_type,
			data_type,
			len
		}).then(function(all){
			console.log(all);
			
			dasboardChart.data.labels.splice(0,dasboardChart.data.labels.length, ...all.labels);
			dasboardChart.data.datasets[0].data.splice(0, 
				dasboardChart.data.datasets[0].data.length, ...all.dataset
			);
			dasboardChart.data.datasets[0].backgroundColor = getColorFromDataType(data_type);
			dasboardChart.data.datasets[0].label = value;
			dasboardChart.update();
		}).catch(function(err) {
			console.log("ChangeDatatype: No se puedo actualizar data");
		})
	}
})

$(window).resize(function() {
	for (var id in Chart.instances) {
        Chart.instances[id].resize();
    }
});
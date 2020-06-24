import React from "react"
import {distanciaFormatoStr, getEstado, getDisplayPhone, getDisplayPrecio} from "../funciones"
import {FormBody} from "./FormBodyComponent"
import { Modal } from "./ComunComponents";

const modalRef = React.createRef();
window.DataBottomComponentsRef = modalRef;
const onClickTrackid = (id) => {
	if (modalRef.current) {
		modalRef.current.setState({trackid: id}, ()=>{
			$("#track-pedido-modal").modal("show");
		});
	}
}
window.COLUMNAS_TABLE = HEADS.filter(head=>head!=="#").map(head=>{
	let col = {displayName: head};
	/* 
			// ["Usuario", "Driver", "Origen", "Destino", "Pedido", "Plan", "Recorrido", "Estado"];
			<th scope="row">{{++$row}}</th>
			<td>{{isset($item->user) ? $item->user->phone : $item->user_id}}</td>
			<td>{{isset($item->driver) ? $item->driver->dni : ""}}</td>
			<td>{{$item->recogible->place->direccion}}</td>
			<td>{{$item->entregable->place->direccion}}</td>
			<td>{{$item->cargable->tipo}}</td>
			<td>{{$item->plan->nombre}}</td>
			<td>{{$item->distanciaFormatoStr()}}</td>
			<td>{{$item->getEstado()}}</td>
	*/
	switch (head) {
		case "trackid":
			col.getDisplayValue = (row) => {
				return <a href={"/track?id="+row.trackid} target="_blank" 
					className="btn btn-link" onClick={(e)=>{
						e.preventDefault();
						onClickTrackid(row.trackid)
					}}>
						<small>{row.trackid||""}</small>
					</a>;
			}
			break;
		case "Usuario":
			col.getDisplayValue = (row) => {
				return row.user ? (row.user.phone||"").replace("+51", "") : row.user_id;
			}
			break;
		case "Driver":
			col.getDisplayValue = (row) => {
				return row.driver_id ? (row.driver ? row.driver.dni : row.driver_id) : "";
			}
			break;
		case "Origen":
			col.getDisplayValue = (row) => {
				return row.recogible.place.direccion;
			}
			break;
		case "Destino":
			col.getDisplayValue = (row) => {
				return row.entregable.place.direccion;
			}
			break;
		case "Pedido":
			col.getDisplayValue = (row) => {
				return row.cargable.tipo;
			}
			break;
		case "Plan":
			col.getDisplayValue = (row) => {
				return row.plan?.nombre||getDisplayPrecio(row.precio_plan);
			}
			break;
		case "Recorrido":
			col.getDisplayValue = (row) => {
				return distanciaFormatoStr(row.distancia);
			}
			break;
		case "Estado":
			col.getDisplayValue = (row) => {
				return getEstado(row.estado);
			}
			break;
	}
	return col;
})

class PedidosUpdateForm extends FormBody {

	state = {
		trackid: "",
		user: "",
		driver: "",
		origen: "",
		destino: "",
		pedido: "",
		plan: "",
		recorrido: "",
		estado: "",
	}

	static create = false;
	
	propsToState = () => {
		return {
			trackid: this.props.trackid||"",
			user: this.props.user?getDisplayPhone(this.props.user.phone):this.props.user_id||"",
			driver: this.props.driver_id?(this.props.driver?this.props.driver.dni:this.props.driver_id):"",
			origen: "",
			destino: "",
			pedido: "",
			plan: "",
			recorrido: this.props.distancia||"",
			estado: this.props.estado||"",
		}
	}

	inputsProps = [
		{name: "trackid", className: "form-control", label: "TrackID", type: "text",
			required: true, maxLength: 16, minLength: 4, placeholder: "4 - 16 caracteres (ID único)"},
		{name: "user", className: "form-control", label: "Usuario", type: "text", list: "users",
			maxLength: 9, required: true},
		{name: "driver", className: "form-control", label: "Driver", type: "text", list: "drivers",
			maxLength: 8},
	]
	
	render() {
		let inputs = this.getInputs();
		return <>
			<div className="form-row">
				<div className="form-group col-md-3">
					{inputs.trackid}
				</div>
				<div className="form-group col-md-5">
					{inputs.user}
				</div>
				<div className="form-group col-md-4">
					{inputs.driver}
				</div>
			</div>
		</>
	}
}

class DataBottom extends React.Component {
	state = {
		trackid: '',
	}

	componentDidMount() {
		$("#track-pedido-modal").on("hide.bs.modal", (e)=>{
			this.setState({trackid: ''})
		})
	}

	/* componentDidUpdate(prevProps, prevState) {
		if (prevState.trackid!==this.state.trackid) {

		}
	} */

render() {
	return <>
	<div>
			<div id="track-pedido-modal" className="modal" tabIndex="-1" role="dialog">
				
			<div className="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
				<div className="modal-content h-100">
					<div className="modal-header">
						<h5 className="modal-title">
							Track del recorrido 
						</h5>
						<button type="button" className="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div className="modal-body" style={{position: "relative"}}>
						{this.state.trackid?<div style={{width: "100%", height:"100%", position: "absolute", top:0, left:0}} 
							dangerouslySetInnerHTML={{
								__html: this.state.trackid && 
									`<iframe src="/track?id=${this.state.trackid}&embed=true" frameborder="0" width="100%" height="100%"></iframe>`
							}}
						/>:null}
					</div>
					<div className="modal-footer">

					</div>
				</div>
			</div>
		</div></div>
	{/* <Modal key="trackId">
	</Modal> */}
	</>
}
	
}

window.DataBottomComponents = DataBottom

window.EditFormComponent = PedidosUpdateForm

import React from "react"
import {distanciaFormatoStr, getEstado, getDisplayPhone, getDisplayPrecio, getPeso, getMedidas} from "../funciones"
import {FormBody} from "./FormBodyComponent"
import { Modal } from "./ComunComponents";
import ButtonCrud from "./ButtonCrud";

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

	getModalBody = () => {
		let body = null;
		return  body;
	}

	static initBtns = (idx) => {
		return <ButtonCrud onClick={()=>this.handleEditBtn(idx)}>
			<svg width="1em" height="1em" viewBox="0 0 16 16" className="bi bi-eye-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
			  <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
			  <path fillRule="evenodd" d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
			</svg>
		</ButtonCrud>
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
								__html: this.state.trackid && //>
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

window.TableActionsButtons = (props) => {
	const onClick = () => props.table.handleGeneralBtn(props.idx, "view");
	return <ButtonCrud onClick={onClick} tipo="warning">
		<svg width="1em" height="1em" viewBox="0 0 16 16" className="bi bi-eye-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg" className="text-info">
		  <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
		  <path fillRule="evenodd" d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
		</svg>
	</ButtonCrud>
}

const Label = ({className, ...props}) => {
	const baseClassName = "text-muted";
	const resultClassName = baseClassName + (className?" "+className:"");
	return <small className={resultClassName} {...props} />
}

const Value = ({className, ...props}) => {
	const baseClassName = "val";
	const resultClassName = baseClassName + (className?" "+className:"");
	return <b className={resultClassName} {...props} />
}

const Agente = ({className, type, ...props}) => {
	const leftWid = 40;
	const baseClassName = "d-flex flex-column";
	const resultClassName = baseClassName + (className?" "+className:"");
	let icon = null;
	switch (type) {
		case "recojo":
			icon = <svg width="1em" height="1em" viewBox="0 0 16 16" className="bi bi-geo-alt-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fillRule="evenodd" d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
</svg>
			break;
		case "entrega":
			icon = <svg width="1em" height="1em" viewBox="0 0 16 16" className="bi bi-cursor-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
			  <path fillRule="evenodd" d="M14.082 2.182a.5.5 0 0 1 .103.557L8.528 15.467a.5.5 0 0 1-.917-.007L5.57 10.694.803 8.652a.5.5 0 0 1-.006-.916l12.728-5.657a.5.5 0 0 1 .556.103z"/>
			</svg>
			break;
	}
	return <div className={resultClassName}>
		<div style={{paddingLeft: leftWid}}>
			<Label>{props.place?.nombre}</Label>
		</div>
		<div className="d-flex">
			<div className="text-center" style={{width: leftWid}}>
				{icon}
			</div>
			<div className="col px-0">
				<b>{props.fullname}</b>
			</div>
		</div>
		<div style={{paddingLeft: leftWid}}>
			{props.place?.direccion}
		</div>
	</div>
}

const UserRol = (props) => {
	return <>
		{false && <div className="col-auto">
			{props.user?.avatar && <img width="50" src={props.user?.avatar} alt={(props.user?.name||"").split(" ").map(nam=>nam[0]||"").join("")||"-"} className="rounded-circle d-block" />}
		</div>}
		<div className="col" >
			<div>
				<Label>{props.label}</Label>
			</div>
			<div>
				<Value>{(props.user?.name||props.user_id)}
				</Value>
			</div>
		</div>
		<div className="col-auto">
			<div>
				<Label>{props.labelId}</Label>
			</div>
			<div>
				<Value>{props.id}
				</Value>
			</div>
		</div>
	</>
}

const PedidoCard = (props) => {
	const modo = <div className="col" >
					<div>
						<Label>Modo</Label>
					</div>
					<div className="row">
						<div className="col" >
							<div>
								<Value>{props.plan?.nombre||"-"}</Value>
							</div>
							{props.plan && <div>
								<Label>{props.plan?.descripcion}</Label>
							</div>}
						</div>
						<div className="ml-3 col-auto" >
							<Value>{getDisplayPrecio(props.plan?.precio||props.precio_plan)}</Value>
						</div>
					</div>
				</div>
	return <div className="card mb-3" id="card-pedido-selected">
	  <ul className="list-group list-group-flush">
		<li className="list-group-item">
			<div className="row">
				<div className="col">
					<div className="row">
						{props.driver_id ? <UserRol user={props.driver.user} user_id={props.driver_id} id={props.driver.dni} label="Repartidor" labelId="DNI" /> : <div className="col" >
							<div>
								<Label>Repartidor</Label>
							</div>
							<div>
								<Value>-</Value>
							</div>
						</div>}
					</div>
				</div>
				<div className="col-12 col-md-auto text-right">
					<div>
						<Label>Estado del pedido</Label>
					</div>
					<div>
						<Value>{getEstado(props.estado)}</Value>
					</div>
				</div>
			</div>
		</li>
		<li className="list-group-item">
			<div className="row">
				<UserRol user={props.user}  id={getDisplayPhone(props.user.phone)} user_id={props.user_id} label="Usuario" labelId="Celular" />
			</div>
		</li>
		<li className="list-group-item">
			<div className="row">
				<Agente className="col-sm-6" {...props.recogible} type="recojo" />
				<Agente className="col-sm-6" {...props.entregable} type="entrega" />
			</div>
		</li>
		<li className="list-group-item">
			<div className="row">
				<div className="col-auto mr-4">
					<div>
						<Label>Distancia</Label>
					</div>
					<div>
						<Value>{distanciaFormatoStr(props.distancia)}</Value>
					</div>
				</div>
				<div className="col" >
					<div>
						<Label>Dirección</Label>
					</div>
					<div>
						<Value>{props.entregable.place.direccion}</Value>
					</div>
				</div>
			</div>
		</li>
		<li className="list-group-item">
			<div className="row">
				<div className="col-6 col-md">
					<div>
						<Label>Tipo</Label>
					</div>
					<div>
						<Value>{props.cargable?.tipo}</Value>
					</div>
				</div>
				<div className="col-6 col-md" >
					<div>
						<Label>Frágil</Label>
					</div>
					<div>
						<Value>{props.cargable?.fragil ? "Sí" : "No"}</Value>
					</div>
				</div>
				<div className="col-6 col-md" >
					<div>
						<Label>Medidas</Label>
					</div>
					<div>
						<Value>{getMedidas(props.cargable)||"-"}</Value>
					</div>
				</div>
				<div className="col-6 col-md" >
					<div>
						<Label>Peso</Label>
					</div>
					<div>
						<Value>{getPeso(props.cargable.peso)||"-"}</Value>
					</div>
				</div>
			</div>
		</li>
		<li className="list-group-item">
			<div className="row">
				<div className="col col-lg-auto mr-sm-3">
					<div>
						<Label>Nombre del producto</Label>
					</div>
					<div>
						<Value>{props.cargable?.nombre}</Value>
					</div>
				</div>
				<div className="col-auto mr-lg-3" >
					<div>
						<Label>Precio</Label>
					</div>
					<div>
						<Value>{getDisplayPrecio(props.cargable.precio)}</Value>
					</div>
				</div>
				<div className="d-none d-lg-block col-lg">
					<div className="row">
						{modo}
					</div>
				</div>
			</div>
		</li>
		<li className="list-group-item d-block d-lg-none">
			<div className="row">
				{modo}
			</div>
		</li>
	  </ul>
	</div>
}

function printDiv(divName) {
	printJS({printable: divName, type: 'html', targetStyles: ["*"], css: "/css/vendor.css"})
}

window.GeneralModalContent = ({type, ...props}) => {
	let title = "";
	let body = null;
	let footer = null;
	switch (type) {

		case "view":
			title = "Ver Pedido"
			body = <PedidoCard {...props} />
			break;
	}

	const print = ()=>{
		printDiv("card-pedido-selected")
	}
	return <><div className="modal-header">
		<h5 className="modal-title col">
			<span>{title} {"  "}</span>
		</h5>
		<a className="btn btn-info" onClick={print}>Imprimir</a>
		<button type="button" className="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	<div className="modal-body">
		{body}
	</div>
	{footer && <div className="modal-footer">
		{footer}
	</div>}</>
}

window.DataBottomComponents = DataBottom

window.EditFormComponent = PedidosUpdateForm

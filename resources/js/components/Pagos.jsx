import React from 'react';
import {FormBody} from "./FormBodyComponent"
import {getDisplayPrecio} from "../funciones";

window.COLUMNAS_TABLE = HEADS.filter(head=>head!=="#").map(head=>{
	let col = {displayName: head};
	switch (head) {
		case "Nombre":
			col.getDisplayValue = (row) => {
				return row.nombre;
			}
			break;
		case "Descripción":
			col.getDisplayValue = (row) => {
				return row.descripcion;
			}
			break;
		case "Precio":
			col.getDisplayValue = (row) => {
				return getDisplayPrecio(row.precio);
			}
			break;
		case "Límite":
			col.getDisplayValue = (row) => {
				return Number(row.limite) && getDisplayPrecio(row.limite) || "";
			}
			break;
	}
	return col;
})

class PagosEditForm extends FormBody {

	static title = "Editar plan de pago"

	// static delete = true;
	
	state = {
		descripcion: "",
		nombre:  "",
		precio: "",
		limite: "",
	}
	
	propsToState = () => {
		return {
			descripcion: this.props.descripcion||"",
			nombre:  this.props.nombre||"",
			precio: this.props.precio||"",
			limite: this.props.limite||"",
		}
	}

	inputsProps = [
		{type:"text" ,className:"form-control" ,id:"nombre" ,name:"nombre" ,placeholder:"Nombre",
			label: "Nombre", required: true
		},
		{type:"text", className:"form-control", id:"descripcion", name:"descripcion", 
			placeholder:"Descripción", label:"Descripción", required: true},
		{type:"number", className:"form-control", id:"precio", name:"precio", 
			placeholder:"Precio", label:"Precio", required: true},
		{type:"number", className:"form-control", id:"limite", name:"limite", 
			placeholder:"Sín límites", label:"Límite"}

	]

	render() {
		let inputs = this.getInputs();
		return <>
			<div className="form-group">
				{inputs.nombre}
			</div>
			<div className="form-group">
				{inputs.descripcion}
			</div>
			<div className="form-row">
				<div className="col-md-6 mb-3">
					{inputs.precio}
				</div>
				<div className="col-md-6 mb-3">
					{inputs.limite}
				</div>
			</div>
		</>;
	}
}

window.EditFormComponent = PagosEditForm

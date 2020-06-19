import React from "react"
import {FormBody} from "./FormBodyComponent"

const Check = () => {
	return <div style={{color: "var(--success)", textAlign: "center"}}>
		<svg className="bi bi-check-circle-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
			<path fillRule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
		</svg>
	</div>
}

const Uncheck = () => {
	return <div style={{color: "var(--danger)", textAlign: "center"}}>
		<svg className="bi bi-x-circle-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
			<path fillRule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.146-3.146a.5.5 0 0 0-.708-.708L8 7.293 4.854 4.146a.5.5 0 1 0-.708.708L7.293 8l-3.147 3.146a.5.5 0 0 0 .708.708L8 8.707l3.146 3.147a.5.5 0 0 0 .708-.708L8.707 8l3.147-3.146z"/>
		</svg>
	</div>
}

window.COLUMNAS_TABLE = HEADS.filter(head=>head!=="#").map(head=>{
	let col = {displayName: head};
	/* 
		<td>{{$item->user->name}}</td>
		<td>{{$item->user->email}}</td>
		<td>{{str_replace("+51", "", $item->user->phone)}}</td>
		<td>{{$item->user->direccion}}</td>
	*/
	switch (head) {
		case "DNI":
			col.getDisplayValue = (row) => {
				return row.dni;
			}
			break;
		case "Activo":
			col.getDisplayValue = (row) => {
				if (!row.verified_at) return null;
				if (row.activo) {
					return <Check />
				}
				return <Uncheck />
			}
			break;
		case "Habilitado":
			col.getDisplayValue = (row) => {
				if (row.verified_at) {
					return <Check />
				}
				return <Uncheck />
			}
			break;
		case "Nombre":
			col.getDisplayValue = (row) => {
				return row.user.name;
			}
			break;
		case "Correo":
			col.getDisplayValue = (row) => {
				return row.user.email;
			}
			break;
		case "Celular":
			col.getDisplayValue = (row) => {
				return (row.user.phone||"").replace("+51", "");
			}
			break;
		case "Dirección":
			col.getDisplayValue = (row) => {
				return row.user.direccion;
			}
			break;
	}
	return col;
})

class DriverFormUpdate extends FormBody {
	state = {
		dni: "",
		verified_at: false,
	}
	
	propsToState = () => {
		return {
			dni: this.props.dni||"",
			verified_at: Boolean(this.props.verified_at)
		}
	}

	inputsProps = [
		{type:"text" ,className:"form-control" ,name:"dni", label: "DNI", 
			required: true, minLength: 8, maxLength: 8
		},
		{type:"checkbox" ,className:"form-check-input" ,name:"verified_at", label: "Habilitado",
		},

	]

	render() {
        let inputs = this.getInputs();
		return <>
			<div className="form-group">
				{inputs.dni}
			</div>
			<div className="form-row">
				<div className="col">
					<div className="form-check">
						{inputs.verified_at}
					</div>
				</div>
			</div>
		</>
	}
}

window.EditFormComponent = DriverFormUpdate
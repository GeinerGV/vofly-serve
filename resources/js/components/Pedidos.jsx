import React from "react"
import {distanciaFormatoStr, getEstado, getDisplayPhone} from "../funciones"
import {FormBody} from "./FormBodyComponent"

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
                return row.plan.nombre;
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
		user: "",
		driver: "",
		origen: "",
		destino: "",
		pedido: "",
		plan: "",
		recorrido: "",
		estado: "",
	}
	
	propsToState = () => {
		return {
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
		{name: "user", className: "form-control", label: "Usuario", type: "text", list: "users",
			maxLength: 9, required: true},
		{name: "driver", className: "form-control", label: "Driver", type: "text", list: "drivers",
			maxLength: 8, required: true},
	]
	
	render() {
		let inputs = this.getInputs();
		return <>
			<div className="form-row">
				<div className="form-group col-md-6">
					{inputs.user}
				</div>
				<div className="form-group col-md-6">
					{inputs.driver}
				</div>
			</div>
		</>
	}
}

window.EditFormComponent = PedidosUpdateForm

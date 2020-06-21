import React from "react"
import {getDisplayPhone} from "../funciones"
import {FormBody} from "./FormBodyComponent"

window.COLUMNAS_TABLE = HEADS.filter(head=>head!=="#").map(head=>{
    let col = {displayName: head};
    switch (head) {
        case "Nombre":
            col.getDisplayValue = (row) => {
                return row.name;
            }
            break;
        case "Correo":
            col.getDisplayValue = (row) => {
                return row.email;
            }
            break;
        case "Celular":
            col.getDisplayValue = (row) => {
                return (row.phone||"").replace("+51", "");
            }
            break;
        case "Dirección":
            col.getDisplayValue = (row) => {
                return row.direccion;
            }
            break;
    }
    return col;
})

class UsuariosEditForm extends FormBody {
	state = {
        name: "",
        email: "",
        phone: "",
        direccion: "",
    }
	
	propsToState = () => {
		return {
            name: this.props.name||"",
            email: this.props.email||"",
            phone: getDisplayPhone(this.props.phone),
            direccion: this.props.direccion||""
        }
	}

	inputsProps = [
        {type:"text" ,className:"form-control" ,name:"name", label: "Nombre", 
            required: true
		},
		{type:"email", className:"form-control", name:"email", 
			label:"Correo", required: true},
		{type:"tel" ,className:"form-control" ,name:"phone" 
        ,label:"Celular", required: true ,maxLength:9 ,minLength:9},
		{type:"text", className:"form-control", name:"direccion", 
			label:"Dirección"}

	]

    render () {
        let inputs = this.getInputs();
        return <>
            <div className="form-row">
                <div className="form-group col-md-6">
                    {inputs.name}
                </div>
                <div className="form-group col-md-6">
                    {inputs.email}
                </div>
            </div>
            <div className="form-row">
                <div className="form-group col-md-6">
                    {inputs.phone}
                </div>
                <div className="form-group col-md-6">
                    {inputs.direccion}
                </div>
            </div>
        </>
    }
}

window.EditFormComponent = UsuariosEditForm

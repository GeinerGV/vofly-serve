import React from 'react';

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
				return row.precio;
			}
			break;
		case "Límite":
			col.getDisplayValue = (row) => {
				return row.limite;
			}
			break;
	}
	return col;
})

class PagosEditForm extends React.Component {

	static title = "Editar plan de pago"

	constructor(props) {
		super(props);
		this.state = {
			descripcion: this.props.descripcion||"",
			nombre:  this.props.nombre||"",
			precio: this.props.precio||"",
			limite: this.props.limite||"",
		}
	}

	handleChangeInput = (event) => {
		this.setState({[event.target.name]: event.target.value});
	}

	componentDidUpdate(prevProps) {
		if (prevProps!=this.props) {
			this.setState({
				descripcion: this.props.descripcion||"",
				nombre:  this.props.nombre||"",
				precio: this.props.precio||"",
				limite: this.props.limite||"",
			})
		}
	}

	render() {
		let inputs = {};
		[
			{type:"text" ,className:"form-control" ,id:"nombre" ,name:"nombre" ,placeholder:"Nombre",
				label: "Nombre"
			},
			{type:"text", className:"form-control", id:"descripcion", name:"descripcion", 
				placeholder:"Descripción", label:"Descripción"},
			{type:"number", className:"form-control", id:"precio", name:"precio", 
				placeholder:"Precio", label:"Precio"},
			{type:"number", className:"form-control", id:"limite", name:"limite", 
				placeholder:"Sín límites", label:"Precio"}

		].forEach(val=>{
			inputs[val.name] = <>
				{val.label && <label htmlFor={val.id||val.name}>{val.label}</label>}
				<input id={val.name} value={this.state[val.name]} 
					onChange={this.handleChangeInput}  placeholder={val.label||undefined} {...val}
				/>
			</>
		})

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
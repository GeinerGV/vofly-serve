import React from 'react';
import ReactDOM from 'react-dom';

const ButtonCrud = (props) => {
	return <button type="button" className={"ml-1 btn btn-"+(props.tipo||"primary")} onClick={props.onClick}>
		{props.children}
	</button>
}
//data-toggle="modal" data-target="#modal-data-table"

class Table extends React.Component {

constructor(props) {
	super(props);
	this.state = {
		lenRows: '',
		rowToEdit: undefined,
	}
}

formBodyRef = React.createRef();

componentDidMount() {

}

handleChangeLenRows = (event) => {
	this.setState({lenRows: event.target.value});
}

handleEditBtn = (row) => {
	this.setState({rowToEdit: row}, ()=> {
		$("#modal-data-table").modal("show");
	})
	console.log(row);
}

handleSubmitUpdateForm = (e) => {
	e.preventDefault();
	e.stopPropagation();
}

clickSave = (e) => {
	const form = document.getElementById("form-data");
	if (form.checkValidity() === false) {
		$("#form-data :invalid").addClass("is-invalid")
		$("#form-data :valid").removeClass("is-invalid")
	}
}

render() {
	const columnas = this.props.columnas.map((col, idx)=>{
		return <th scope="col" key={"col-"+this.props.current_page+"-"+idx}>
			{col.displayName}
		</th>
	})

	const filas = this.props.data.map((row, idx)=>{
		return <tr key={"row-"+this.props.current_page+"-"+idx}>
			<th scope="col" key={"row-"+idx+"col-"+this.props.current_page}>
				{(this.props.current_page-1)*this.props.per_page + idx+1}
			</th>
			{this.props.columnas.map((col, idx2)=>{
				return <td key={"row-"+idx+"col-"+this.props.current_page+"-"+idx2}>
					{col.getDisplayValue ? col.getDisplayValue(row) : ""}
				</td>
			})}			
			<th scope="col" key={"row-"+idx+"col-"+this.props.current_page+"-crud"}>
				<div style={{display: "flex", flexFlow: "row nowrap"}}>
					<ButtonCrud onClick={()=>this.handleEditBtn(row)}>
						<svg className="bi bi-pencil" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
							<path fillRule="evenodd" d="M11.293 1.293a1 1 0 0 1 1.414 0l2 2a1 1 0 0 1 0 1.414l-9 9a1 1 0 0 1-.39.242l-3 1a1 1 0 0 1-1.266-1.265l1-3a1 1 0 0 1 .242-.391l9-9zM12 2l2 2-9 9-3 1 1-3 9-9z"/>
							<path fillRule="evenodd" d="M12.146 6.354l-2.5-2.5.708-.708 2.5 2.5-.707.708zM3 10v.5a.5.5 0 0 0 .5.5H4v.5a.5.5 0 0 0 .5.5H5v.5a.5.5 0 0 0 .5.5H6v-1.5a.5.5 0 0 0-.5-.5H5v-.5a.5.5 0 0 0-.5-.5H3z"/>
						</svg>
					</ButtonCrud>
					<ButtonCrud tipo="danger">
						<svg className="bi bi-trash-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
							<path fillRule="evenodd" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0v-7z"/>
						</svg>
					</ButtonCrud>
				</div>
			</th>
		</tr>
	})

	return (<>
		<form className="w-100">
			<div className="form-row">
				<div className="col-md-6 mb-3">
				<label htmlFor="len_rows">Rangos de tiempo</label>
				<select className="custom-select" id="len_rows" value={this.state.value} 
					onChange={this.handleChange}
				>
					<option value="">15</option>
					<option value={30}>30</option>
					<option value={50}>50</option>
				</select>
				</div>
			</div>
		</form>
		<div className="table-cnt table-responsive">
			<table id="table-data" className="table table-hover">
				<thead>
					<tr>
						<th scope="col" key={"col-"+this.props.current_page}>
							{"#"}
						</th>
						{columnas}
						<th scope="col" key={"col-"+this.props.current_page+"-crud"}>
							{""}
						</th>
					</tr>
				</thead>
				<tbody>
					{filas}
				</tbody>
			</table>
		</div>
		<Modal>
			<div id="modal-data-table" className="modal" tabIndex="-1" role="dialog">
				<div className="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
					<div className="modal-content">
						<div className="modal-header">
							<h5 className="modal-title">
								{window.EditFormComponent?.title ? EditFormComponent.title : "Editar Tabla"}
							</h5>
							<button type="button" className="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div className="modal-body">
							<form className="needs-validation" id="form-data" method="POST" 
								onSubmit={this.handleSubmitUpdateForm} noValidate
							>
								{/* @csrf
								@yield('edit-form-content') */}
								<input type="hidden" name="_token" value={$('meta[name="csrf-token"]').attr('content')} />
								{window.EditFormComponent ? 
									<EditFormComponent {...this.state.rowToEdit} ref={this.formBodyRef} /> 
								: null}
								<input type="hidden" name="id" id="rowid" />
							</form>
						</div>
						<div className="modal-footer">
							<button type="button" className="btn btn-secondary" data-dismiss="modal">Cancelar</button>
							<button onClick={this.clickSave} id="guardar-cambios" type="button" className="btn btn-primary">Guardar Cambios</button>
						</div>
					</div>
				</div>
			</div>
		</Modal>
	</>);
}

}

export default Table;

const modalRoot = document.getElementById('modal-root');

class Modal extends React.Component {
constructor(props) {
	super(props);
	this.el = document.createElement('div');
}

componentDidMount() {
	// The portal element is inserted in the DOM tree after
	// the Modal's children are mounted, meaning that children
	// will be mounted on a detached DOM node. If a child
	// component requires to be attached to the DOM tree
	// immediately when mounted, for example to measure a
	// DOM node, or uses 'autoFocus' in a descendant, add
	// state to Modal and only render the children when Modal
	// is inserted in the DOM tree.
	modalRoot.appendChild(this.el);
}

/* componentDidUpdate(prevProps) {
	if (this.props.isOpen !== prevProps.isOpen) {
		if (this.props.isOpen) {
			$(this.el).children(".modal").modal("show");
		} else {
			$(this.el).children(".modal").modal("hide");
		}
	}
} */

componentWillUnmount() {
	modalRoot.removeChild(this.el);
}

render() {
	return ReactDOM.createPortal(
	this.props.children,
	this.el
	);
}
}

if (document.getElementById('tbl-blq') && window.PAGINATION_DATA) {
	const columnas = window.COLUMNAS_TABLE ? COLUMNAS_TABLE : [];
    ReactDOM.render(<Table {...PAGINATION_DATA} columnas={columnas} />, document.getElementById('tbl-blq'));
}

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
		isDataChanged: false,
		loadingUpdate: false,
		rowIdEdit: undefined,
		loadingDelete: false,
		alerta: undefined,
	}
}

formBodyRef = React.createRef();

componentDidMount() {

}

getRowNumber = (idx) => {
	return (this.props.current_page-1)*this.props.per_page + idx+1
}

getRowToEdit = () => {
	return this.state.rowIdEdit>=0 ? this.props.data[this.state.rowIdEdit] : undefined;
}

handleChangeLenRows = (event) => {
	this.setState({lenRows: event.target.value});
}

handleEditBtn = (rowid) => {
	this.setState({rowIdEdit: rowid}, ()=> {
		$("#modal-data-table").modal("show");
		//console.log(this.getRowToEdit(), rowid);
	})
}

handleCreateBtn = () => {
	this.setState({rowIdEdit: undefined}, ()=> {
		$("#modal-data-table").modal("show");
		//console.log(this.getRowToEdit(), rowid);
	})
}

handleDeleteBtn = (rowid) => {
	this.setState({rowIdEdit: rowid}, ()=> {
		$("#modal-delete-row").modal("show");
	})
}

handleSubmitUpdateForm = (e) => {
	e.preventDefault();
	e.stopPropagation();
}

closeAlerta = (e) => {
	e.target.closest(".alert").style.display = "none"
}

setAlerta = (alert) => {
	const alerta = (<Alert>
		<div key={new Date().getTime()} className={"alert alert-"+alert[0]+" alert-dismissible fade show"} role="alert">
			{alert[1]}
			<button type="button" className="close" onClick={this.closeAlerta} aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	</Alert>)
	console.log(alert);
	this.setState({alerta});
}

confirmEliminar = (e) => {
	if (this.state.loadingDelete) return;
	this.setState({loadingDelete: true}, ()=>{
		const body = {delete: true, id: this.getRowToEdit().id}
		axios.post(window.location.pathname, body).then(res=>{
			console.log(res);
			if (res.data?.status=="success") {
				$("#modal-delete-row").modal("hide");
				this.setState({rowIdEdit: undefined}, ()=>{
					this.props.setPagination(res.data.pagination);
				});
			}
			if (res.data?.alert) {
				this.setAlerta(res.data?.alert);
			}
		}).catch(()=>null).then(()=>{
			this.setState({loadingDelete: false});
		})
	});
}

clickSave = (e) => {
	if (this.state.loadingUpdate) return;
	let isCreator = !(this.state.rowIdEdit>=0);
	let idModal = false ? "modal-create-row" : "modal-data-table";
	let idForm = false ? "form-data-create" : "form-data";
	if (!this.state.isDataChanged) {
		$("#"+idModal).modal("hide");
	} else {
		const form = document.getElementById(idForm);		
		$("#"+idForm+" :valid").removeClass("is-invalid")
		let validForm = true;
		if (form.checkValidity() === false) {
			$("#"+idForm+" :invalid").addClass("is-invalid")
			validForm = false;
		}
		validForm = validForm && this.formBodyRef.current ? this.formBodyRef.current.isValidForm() : false;
		//console.log("validado", validForm);
		if (validForm) {
			const dataChanged = this.formBodyRef.current.getDataChanged();
			const rowCurr =  this.getRowToEdit();
			const body = {...dataChanged, ...(isCreator ? {create:true} : {id: rowCurr?.id})}
			this.setState({loadingUpdate: true}, ()=>{
				axios.post(window.location.pathname, body)
					.then(res=>{
						console.log(res);
						
						if (res.data && res.data.status=="success") {
							//console.log(newRow);
							//this.setState({rowToEdit: }, ()=>{
							if (!isCreator) {
								const newRow = {...rowCurr, ...dataChanged};
								this.props.updatePaginationData(this.state.rowIdEdit, newRow)
							}
							else {
								$("#"+idModal).modal("hide");
								if (this.props.current_page===1) {
									this.setState({rowIdEdit: 0}, ()=>{
										let data = this.props.data;
										if (data.length>=this.props.per_page) {
											data.splice(data.length-1, 1);
										}
										data.splice(0,0,res.data.alert[2]);
										this.props.setDataPagination(res.data.pagination);
									});
								}
							}
							//});
						} else if (res.data && res.data.status=="danger") {
							console.log(res.data.alert[2]);
							res.data.alert[2] && Object.keys(res.data.alert[2]).forEach(key=>{
								this.formBodyRef.current._setInvalid(key);
								this.formBodyRef.current._getCurrentValidator()
							});
						}
						if (res.data?.alert) {
							this.setAlerta(res.data?.alert);
						}
						//this.props.setPagination(res.data.pagination)
					}).catch(error=>{
						if (error) console.log(error.response?.data);
					}).then(()=>{
						this.setState({loadingUpdate: false});
					})
			})
			//console.log(this.formBodyRef.current.getDataChanged());
		}
	}
}

setDataChanged = (changed) => {
	this.setState({isDataChanged: changed});
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
				{this.getRowNumber(idx)}
			</th>
			{this.props.columnas.map((col, idx2)=>{
				return <td key={"row-"+idx+"col-"+this.props.current_page+"-"+idx2}>
					{col.getDisplayValue ? col.getDisplayValue(row) : ""}
				</td>
			})}			
			<th scope="col" key={"row-"+idx+"col-"+this.props.current_page+"-crud"}>
				<div style={{display: "flex", flexFlow: "row nowrap"}}>
					<ButtonCrud onClick={()=>this.handleEditBtn(idx)}>
						<svg className="bi bi-pencil" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
							<path fillRule="evenodd" d="M11.293 1.293a1 1 0 0 1 1.414 0l2 2a1 1 0 0 1 0 1.414l-9 9a1 1 0 0 1-.39.242l-3 1a1 1 0 0 1-1.266-1.265l1-3a1 1 0 0 1 .242-.391l9-9zM12 2l2 2-9 9-3 1 1-3 9-9z"/>
							<path fillRule="evenodd" d="M12.146 6.354l-2.5-2.5.708-.708 2.5 2.5-.707.708zM3 10v.5a.5.5 0 0 0 .5.5H4v.5a.5.5 0 0 0 .5.5H5v.5a.5.5 0 0 0 .5.5H6v-1.5a.5.5 0 0 0-.5-.5H5v-.5a.5.5 0 0 0-.5-.5H3z"/>
						</svg>
					</ButtonCrud>
					<ButtonCrud tipo="danger" onClick={()=>this.handleDeleteBtn(idx)}>
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
				<div className="col-md-6 mb-3 d-flex align-items-end justify-content-end pr-5">
					
					<ButtonCrud tipo="success" onClick={()=>this.handleCreateBtn()}>
						Añadir{"  "}
						<svg className="bi bi-plus-square" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
						  <path fillRule="evenodd" d="M8 3.5a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5H4a.5.5 0 0 1 0-1h3.5V4a.5.5 0 0 1 .5-.5z"/>
						  <path fillRule="evenodd" d="M7.5 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0V8z"/>
						  <path fillRule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
						</svg>
					</ButtonCrud>
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
								{window.EditFormComponent?.title ? EditFormComponent.title : "Editar registro"}
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
									<EditFormComponent {...this.getRowToEdit()} ref={this.formBodyRef} 
										setDataChanged={this.setDataChanged}
									/> 
								: null}
								<input type="hidden" name="id" id="rowid" />
							</form>
						</div>
						<div className="modal-footer">
							<button type="button" className="btn btn-secondary" data-dismiss="modal">Cancelar</button>
							<button onClick={this.clickSave} id="guardar-cambios" type="button"
								className={"btn btn-"+(this.state.isDataChanged?"warning":"primary")}
								
							>
								{this.state.isDataChanged ?
										(this.state.loadingUpdate ? "Guardando..." : "Guardar Cambios") 
								: "Cerrar"}
							</button>
						</div>
					</div>
				</div>
			</div>
		</Modal>
		<Modal>
			<div id="modal-delete-row" className="modal" tabIndex="-1" role="dialog">
				<div className="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
					<div className="modal-content">
						<div className="modal-header">
							<h5 className="modal-title">
								{window.EditFormComponent?.titleEliminar ? EditFormComponent.titleEliminar : "Eliminar registro"}
							</h5>
							<button type="button" className="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div className="modal-body">
							<p>{this.formBodyRef.current?.getEliminarDescripcion && this.formBodyRef.current?.getEliminarDescripcion() || "¿Estás seguro que deseas eliminarlo? Esta acción no se puede deshacer."}
							</p>
							<div className="table-cnt table-responsive">
								<table id="tmp-table-delete-row" className="table table-hover">
									<thead>
										<tr>
											<th scope="col" key={"col-"+this.props.current_page}>
												{"#"}
											</th>
											{columnas}
										</tr>
									</thead>
									<tbody>
										<tr key={"delete-row-"+this.props.current_page}>
											<th scope="col" key={"delete-row-col-"+this.props.current_page}>
												{this.state.rowIdEdit>=0 && 
													this.getRowNumber(this.state.rowIdEdit)}
											</th>
											{this.props.columnas.map((col, idx)=>{
												return <td key={"delete-row-col-"+this.props.current_page+"-"+idx}>
													{col.getDisplayValue && this.getRowToEdit() ? col.getDisplayValue(this.getRowToEdit()) : ""}
												</td>
											})}
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div className="modal-footer">
							<button type="button" className="btn btn-secondary" data-dismiss="modal">
								Cancelar
							</button>
							<button onClick={this.confirmEliminar} id="confirm-delete" type="button"
								className={"btn btn-danger"}
								
							>
								{this.state.loadingDelete ? "Eliminando..." : "Eliminar"}
							</button>
						</div>
					</div>
				</div>
			</div>
		</Modal>
		{/*<Modal>
			<div id="modal-create-row" className="modal" tabIndex="-1" role="dialog">
				<div className="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
					<div className="modal-content">
						<div className="modal-header">
							<h5 className="modal-title">
								{window.EditFormComponent?.title ? EditFormComponent.title : "Crear un nuevo registro"}
							</h5>
							<button type="button" className="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div className="modal-body">
							<form className="needs-validation" id="form-data-create" method="POST" 
								onSubmit={this.handleSubmitUpdateForm} noValidate
							>
								<input type="hidden" name="_token" value={$('meta[name="csrf-token"]').attr('content')} />
								{window.EditFormComponent ? 
									<EditFormComponent ref={this.formBodyRef} 
										setDataChanged={this.setDataChanged}
									/> 
								: null}
								<input type="hidden" name="id" id="rowid" />
							</form>
						</div>
						<div className="modal-footer">
							<button type="button" className="btn btn-secondary" data-dismiss="modal">Cancelar</button>
							<button onClick={this.clickSave} id="crear-registro" type="button"
								className={"btn btn-"+(this.state.isDataChanged?"warning":"primary")}
								
							>
								{this.state.isDataChanged ?
										(this.state.loadingUpdate ? "Agregando..." : "Añadir") 
								: "Cerrar"}
							</button>
						</div>
					</div>
				</div>
			</div>
		</Modal>*/}
		{this.state.alerta}
	</>);
}

}

export default Table;

const modalRoot = document.getElementById('modal-root');
const alertRoot = document.getElementById('alert-root');

class Alert extends React.Component {
	constructor(props) {
		super(props);
		//console.log("nueva alerta");
		this.el = document.createElement('div');
		this.el.id = new Date().getTime();
	}

	componentDidMount() {
		//this.el = document.createElement('div');
		alertRoot.appendChild(this.el);
		//console.log("montado");
	}

	componentWillUnmount() {
		alertRoot.removeChild(this.el);
		//console.log("desmontado");
	}

	render() {
		return ReactDOM.createPortal(
			this.props.children||null,
			this.el
		);
	}
}

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

const Pagination = (props) => {
	return <div className="pagination-cnt">
		<nav>
		    <ul className="pagination">
		        <li className="page-item">
		        	<a className="page-link" href="http://192.168.0.6/pedidos?pag=1" rel="prev" aria-label="« Previous">‹</a>
	            </li>
			    <li className="page-item">
					<a className="page-link" href="http://192.168.0.6/pedidos?pag=1">1</a>
				</li>
				<li className="page-item active" aria-current="page"><span className="page-link">2</span></li>
	            <li className="page-item disabled" aria-disabled="true" aria-label="Next »">
	                <span className="page-link" aria-hidden="true">›</span>
	            </li>
			</ul>
		</nav>
	</div>
}

const TblBlq = (props) => {
	const [pagination, setPagination] = React.useState(props.pagination);
	const updatePaginationData = React.useCallback((id, row)=>{
		let newPagination = pagination;
		newPagination.data[id] = row;
		setPagination(newPagination);
	}, [pagination])
	const setDataPagination = React.useCallback((data)=>{
		let newPagination = pagination;
		newPagination.data = data;
		setPagination(newPagination);
	}, [pagination])
	return <>
		<Table {...pagination} columnas={props.columnas} setPagination={setPagination} 
			updatePaginationData={updatePaginationData} setDataPagination={setDataPagination}/>
		
	</>
}

if (document.getElementById('tbl-blq') && window.PAGINATION_DATA) {
	const columnas = window.COLUMNAS_TABLE ? COLUMNAS_TABLE : [];
    ReactDOM.render(<TblBlq pagination={PAGINATION_DATA} columnas={columnas} />, document.getElementById('tbl-blq'));
}

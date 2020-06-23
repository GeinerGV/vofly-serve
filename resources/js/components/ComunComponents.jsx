import React from "react";
import ReactDOM from "react-dom";

const alertRoot = document.getElementById('alert-root');

export class Alert extends React.Component {
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
			this.props.children||<div />,
			this.el
		);
	}
}

const modalRoot = document.getElementById('modal-root');

export class Modal extends React.Component {
constructor(props) {
	super(props);
	this.el = document.createElement('div');
	this.el.id = "modal-"+new Date().getTime();
	// console.log(props.children, this.el);
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
	// console.log("mount", this.el);
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
	console.log("unmount");
	modalRoot.removeChild(this.el);
}

render() {
	return ReactDOM.createPortal(
	this.props.children,
	this.el
	);
}
}

//window.Alert_COMPONENT = Alert
import React from "react";

export class FormBody extends React.Component {

    propsToState = ()=>{
        return {}
    }

    constructor(props) {
        super(props)
    }

	inputsProps = []
	
	static super_componentDidUpdate = (_this, prevProps) => {
		const props = _this.props;
        if (Object.keys(props).some((key)=>props[key]!==prevProps[key]) || 
			Object.keys(prevProps).some((key)=>props[key]!==prevProps[key])) {
			$("#form-data .is-invalid").removeClass("is-invalid");
            _this.setState(_this.propsToState(), ()=>{
				if (_this.props.setDataChanged) _this.props.setDataChanged(_this.isDataChanged());
			});
        }
	}

    componentDidUpdate(prevProps, prevState) {
		FormBody.super_componentDidUpdate(this, prevProps);
    }

	isDataChanged = ()=>{
		let stateBase = this.propsToState();
		return Object.keys(stateBase).some((key)=>stateBase[key]!==this.state[key])
	}

	getDataChanged = ()=> {
		let stateBase = this.propsToState();
		const keys = Object.keys(stateBase).filter((key)=>stateBase[key]!==this.state[key])
		let dataChanged = {};
		keys.forEach((key)=>{
			dataChanged[key] = this.state[key];
		});
		return dataChanged;
	}

    handleChangeInput = (event) => {
        const target = event.target;
        const value = target.type === 'checkbox' ? target.checked : target.value;
        this.setState({[target.name]: value}, ()=> {
			if (this.props.setDataChanged) this.props.setDataChanged(this.isDataChanged());
		});
	}

	_valid = true;

	_setInvalid = (id) => {
		$("#"+id).addClass("is-invalid");
		this._valid = false;
	}

	_setValid = (id) => {
		$("#"+id).removeClass("is-invalid")
	}

	_getCurrentValidator = () => {
		const res = !!this._valid;
		this._valid = true;
		return res;
	}

	isValidForm = () => {
		return this._getCurrentValidator();
	}

    getInputs = () => {
        let inputs = {};
        this.inputsProps.forEach(val=>{
			inputs[val.name] = <>
				{val.label && val.type!="checkbox" && <label htmlFor={val.id||val.name}>{val.label}</label>}
                <input id={val.name} key={val.name}
					{...(val.type!=="checkbox" ? {value: this.state[val.name]} :	
						{checked:Boolean(this.state[val.name])})}
					onChange={this.handleChangeInput}  placeholder={val.label||undefined} {...val}
				/>
				{val.label && val.type=="checkbox" && 
					<label className="form-check-label" htmlFor={val.id||val.name}>{val.label}</label>}
			</>
        })
        return inputs;
    }
}
